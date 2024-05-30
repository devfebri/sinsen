<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assign_event extends CI_Controller {

	var $folder = "dealer";
	var $page   = "assign_event";
	var $title  = "Assign Event";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		// $this->load->library('upload');
		$this->load->library('mpdf_l');
		$this->load->helper('tgl_indo');
		$this->load->helper('terbilang');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}


	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{
			$data['id_menu'] = $this->m_admin->getMenu($this->page);
			$data['group'] 	= $this->session->userdata("group");
			$this->load->view('template/header',$data);
			$this->load->view('template/aside');			
			$this->load->view($this->folder."/".$this->page);		
			$this->load->view('template/footer');
		}
	}

	public function index()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = $this->title;															
		$data['set']   = "index";
		$id_dealer     = $this->m_admin->cari_dealer();
		$data['assign'] = $this->db->query("SELECT *,
			(SELECT id_assign FROM tr_assign_event WHERE id_dealer=$id_dealer AND id_event=ms_event.id_event) AS id_assign, (SELECT status FROM tr_assign_event WHERE id_dealer=$id_dealer AND id_event=ms_event.id_event) AS status FROM ms_event JOIN ms_jenis_event ON ms_event.id_jenis_event=ms_jenis_event.id_jenis_event WHERE status='approved' AND '$id_dealer' IN (SELECT id_dealer FROM ms_event_dealer WHERE kode_event=ms_event.kode_event) ORDER BY created_at DESC");						
		$this->template($data);	
	}

	public function prepare()
	{				
		$data['isi']       = $this->page;		
		$data['title']     = $this->title;		
		$data['set']       = "form";					
		$data['mode']      = "insert";
		$data['id_assign'] = $this->input->get('id_assign');
		$id_event          = $this->input->get('id');
		$data['ev'] = $this->db->get_where('ms_event',['id_event'=>$id_event])->row();
		$this->template($data);										
	}

	public function save()
	{		
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		
		$id_assign      = $this->input->post('id_assign');
		$data['status'] = 'prepare';
		$details        = $this->input->post('details');
		foreach ($details as $key => $val) {
			$dt_detail[] = ['id_assign'=> $id_assign,
							'id_sales_program' => $val['id_sales_program']
					 	 ];
		}

		$this->db->trans_begin();
			$this->db->update('tr_assign_event',$data,['id_assign'=>$id_assign]);
			if (isset($dt_detail)) {
				$this->db->insert_batch('tr_assign_event_detail',$dt_detail);
			}
		if ($this->db->trans_status() === FALSE)
      	{
			$this->db->trans_rollback();
			$rsp = ['status'=> 'error',
					'pesan'=> ' Something went wrong'
				   ];
      	}
      	else
      	{
        	$this->db->trans_commit();
        	$rsp = ['status'=> 'sukses',
					'link'=>base_url('dealer/assign_event')
				   ];
        	$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
      	}
      	echo json_encode($rsp);
	}

	function get_last_dokumen_nrfs_id($dokumen_nrfs_id=null)
   	{
   		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('ym');
		$id_dealer = $this->m_admin->cari_dealer();
			$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();

   		if ($dokumen_nrfs_id==null) {
   			$get_data = $this->db->query("SELECT * FROM tr_dokumen_nrfs WHERE id_dealer=$id_dealer AND LEFT(tgl_dokumen,7)='$th_bln' ORDER BY dokumen_nrfs_id DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$new_kode = $get_data->row()->dokumen_nrfs_id;
	   		}else{
	   			$new_kode = 'kosong';
	   		}
   		}else{
			$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
			if ($dokumen_nrfs_id=='kosong') {
				$new_kode = 'NRFS/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
			}else{
				$dokumen_nrfs_id = substr($dokumen_nrfs_id, -4);
				$new_kode        = 'NRFS/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$dokumen_nrfs_id+1);
			}
   		}
   		return $new_kode;
   	}
	public function assign()
	{		
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();
		$data['id_event']   = $this->input->get('id');
		$data['id_dealer']  = $id_dealer;
		
		$data['status']     = 'input';						
		$data['created_at'] = $waktu;		
		$data['created_by'] = $login_id;
		$this->db->trans_begin();
			$this->db->insert('tr_assign_event',$data);
		if ($this->db->trans_status() === FALSE)
      	{
			$this->db->trans_rollback();
			$rsp = ['status'=> 'error',
					'pesan'=> ' Something went wrong'
				   ];
      	}
      	else
      	{
        	$this->db->trans_commit();
        	$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/assign_event'>";
      	}
	}

	public function get_sj()
	{
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('ym');
		$id_dealer = $this->m_admin->cari_dealer();
		$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
		
		$get_data  = $this->db->query("SELECT * FROM tr_mutasi
			WHERE id_dealer='$id_dealer'
			AND LEFT(created_at,7)='$th_bln'
			AND no_sj IS NOT NULL
			ORDER BY created_at DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$row      = $get_data->row();
				$no_sj    = substr($row->no_sj, -4);
				$new_kode = 'SL/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$no_sj+1);
				$i=0;
				while ($i<1) {
					$cek = $this->db->get_where('tr_mutasi',['no_sj'=>$new_kode])->num_rows();
				    if ($cek>0) {
				    	$no_sj    = substr($new_kode, -4);
						$new_kode = 'SL/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$no_sj+1);
				    	$i=0;
				    }else{
				    	$i++;
				    }
				}
	   		}else{
				$new_kode = 'SL/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
	   		}
   		return strtoupper($new_kode);
	}

	public function print_sj(){
		$tgl       = gmdate("y-m-d", time()+60*60*7);
		$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id  = $this->session->userdata('id_user');
		$id_mutasi = $this->input->get('id');				
  
  		$get_data = $this->db->get_where('tr_mutasi',['id_mutasi'=>$id_mutasi,'status_mutasi'=>'intransit','tipe_stok_trf'=>'event']);
  		if ($get_data->num_rows()>0) {
  			$row = $get_data->row();
  			$no_sj = $row->no_sj;
  			if ($row->no_sj==null)$no_sj=$this->get_sj();

  			$upd = ['print_sj_ke'=> $row->print_sj_ke+1,
  					'print_sj_at'=> $waktu,
  					'print_sj_by'=> $login_id,
  					'no_sj' => $no_sj
  				   ];

  			$this->db->update('tr_mutasi',$upd,['id_mutasi'=>$id_mutasi]);
			
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
			$mpdf->autoLangToFont           = true;

			$data['set']    = 'print_sj';
			$row            = $data['row'] = $this->db->get_where('tr_mutasi',['id_mutasi'=>$id_mutasi,'status_mutasi'=>'intransit','tipe_stok_trf'=>'event'])->row();
			$data['event']  = $this->db->get_where('ms_event',['id_event'=>$row->id_event])->row();
			$data['dealer'] = $this->db->get_where('ms_dealer',['id_dealer'=>$row->id_dealer])->row()->nama_dealer;
        	$data['details'] = $this->db->query("SELECT tr_mutasi_detail.no_mesin,no_rangka,tr_scan_barcode.id_item,tipe_ahm,ms_warna.warna,close,id_mutasi_detail
			 FROM tr_mutasi_detail 
			 INNER JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin = tr_mutasi_detail.no_mesin
			 INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
            INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
            INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
			 WHERE id_mutasi='$id_mutasi'")->result();

        	$html = $this->load->view('dealer/mutasi_stok_cetak', $data, true);
        	// render the view into HTML
	        $mpdf->WriteHTML($html);
	        // write the HTML into the mpdf
	        $output = 'cetak_.pdf';
	        $mpdf->Output("$output", 'I');	        
        }else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok'>";		
        }
        
	}
}