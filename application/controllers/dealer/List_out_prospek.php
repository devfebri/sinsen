<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class List_out_prospek extends CI_Controller {

	var $folder = "dealer";
	var $page   = "list_out_prospek";
	var $title  = "List Outstanding Prospek";

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
		$date = date('Y-m-d');
		$data['prosp'] = $this->db->query("SELECT * FROM tr_prospek 
			WHERE (status_prospek='Hot Prospect' OR status_prospek='hot' OR status_prospek='low') 
			AND (SELECT COUNT(id_prospek) FROM tr_prospek_fol_up WHERE tgl_fol_up='$date' AND  tr_prospek.id_prospek=id_prospek )=1 
			AND tr_prospek.id_dealer=$id_dealer
			ORDER BY created_at DESC ");						
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

	public function konfirmasi_save()
	{		
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		
		$id_list              = $this->get_id_list();
		$data['id_list']      = $id_list;
		$data['tanggal_list'] = date('Y-m-d');
		$id_dealer            = $this->m_admin->cari_dealer();
		$data['id_dealer']    = $id_dealer;		
		$data['created_at']   = $waktu;		
		$data['created_by']   = $login_id;

		$id_prospek         = $this->input->post('id_prospek');
		$id_karyawan_dealer = $this->input->post('id_karyawan_dealer');
		if (count($id_karyawan_dealer)>0) {
			foreach ($id_karyawan_dealer as $kry) {
				$id_user = $this->db->query("SELECT id_user FROM ms_user WHERE id_karyawan_dealer='$kry'");
				if ($id_user->num_rows()>0) {
					$id_users[] = $id_user->row()->id_user;
				}
			}
		}else{
			$_SESSION['pesan'] 	= "Data kosong. Konfirmasi tidak dapat dilanjutkan !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/list_out_prospek'>";
			exit;
		}
		$ktg_notif      = $this->db->get_where('ms_notifikasi_kategori',['id_notif_kat'=>15])->row();
		$get_notif_grup = $this->db->get_where('ms_notifikasi_grup',['id_notif_kat'=>15]);
		$id_users = implode(',', array_unique($id_users));
		$notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
				'id_referensi' =>'',
				'judul'        => "List Outstanding prospek",
				'pesan'        => "Terdapat List Outstanding prospek per tanggal ".date('d/m/Y'),
				'id_dealer'    => $id_dealer,
				'id_user'	   => $id_users,
				'link'         => $ktg_notif->link.'?tgl='.date('Y-m-d'),
				'status'       => 'baru',
				'created_at'   => $waktu,
				'created_by'   => $login_id
			 ];
		foreach ($id_prospek as $key => $val) {
			$dt_detail[] = ['id_list'=> $id_list,
							'id_prospek' => $val
					 	 ];
		}

		$this->db->trans_begin();
			$this->db->insert('tr_list_out_prospek',$data);
			$this->db->insert('tr_notifikasi',$notif);
			if (isset($dt_detail)) {
				$this->db->insert_batch('tr_list_out_prospek_detail',$dt_detail);
			}
		if ($this->db->trans_status() === FALSE)
      	{
			$this->db->trans_rollback();
      	}
      	else
      	{
        	$this->db->trans_commit();
        	$_SESSION['pesan'] 	= "Data has been confirmed successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/list_out_prospek'>";
      	}
	}

	public function get_id_list()
	{
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('ym');
		$id_dealer = $this->m_admin->cari_dealer();
		$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
		
		$get_data  = $this->db->query("SELECT * FROM tr_list_out_prospek
			WHERE id_dealer='$id_dealer'
			AND LEFT(created_at,7)='$th_bln'
			AND id_list IS NOT NULL
			ORDER BY created_at DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$row      = $get_data->row();
				$id_list    = substr($row->id_list, -4);
				$new_kode = 'L-P/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$id_list+1);
				$i=0;
				while ($i<1) {
					$cek = $this->db->get_where('tr_list_out_prospek',['id_list'=>$new_kode])->num_rows();
				    if ($cek>0) {
				    	$id_list    = substr($new_kode, -4);
						$new_kode = 'L-P/'.$dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$id_list+1);
				    	$i=0;
				    }else{
				    	$i++;
				    }
				}
	   		}else{
				$new_kode = 'L-P/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
	   		}
   		return strtoupper($new_kode);
	}

	public function cetak(){
		$tgl       = gmdate("y-m-d", time()+60*60*7);
		$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id  = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();
		
  
  		// $get_data = $this->db->get_where('tr_mutasi',['id_mutasi'=>$id_mutasi,'status_mutasi'=>'intransit','tipe_stok_trf'=>'event']);
  		// if ($get_data->num_rows()>0) {
  		// 	$row = $get_data->row();
  		// 	$no_sj = $row->no_sj;
  		// 	if ($row->no_sj==null)$no_sj=$this->get_sj();

  		// 	$upd = ['print_sj_ke'=> $row->print_sj_ke+1,
  		// 			'print_sj_at'=> $waktu,
  		// 			'print_sj_by'=> $login_id,
  		// 			'no_sj' => $no_sj
  		// 		   ];

  		// 	$this->db->update('tr_mutasi',$upd,['id_mutasi'=>$id_mutasi]);
			
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
			$mpdf->autoLangToFont           = true;

			$data['prosp'] = $this->db->query("SELECT * FROM tr_prospek 
			WHERE (status_prospek='Hot Prospect' OR status_prospek='hot' OR status_prospek='low') 
			AND (SELECT COUNT(id_prospek) FROM tr_prospek_fol_up WHERE tgl_fol_up='$date' AND  tr_prospek.id_prospek=id_prospek )=1 
			AND tr_prospek.id_dealer=$id_dealer
			ORDER BY created_at DESC ");	
        	$html = $this->load->view('dealer/list_out_prospek_cetak', $data, true);
        	// render the view into HTML
	        $mpdf->WriteHTML($html);
	        // write the HTML into the mpdf
	        $output = 'cetak_.pdf';
	        $mpdf->Output("$output", 'I');	        
   //      }else{
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/list_out_prospek'>";		
   //      }
	}

	public function fetch_sales()
   	{
		$fetch_data = $this->make_query_sales();  
		$data = array();  
		foreach($fetch_data->result() as $rs)  
		{  
			$sub_array     = array();
			$sub_array[] = $rs->id_flp_md;
			$sub_array[] = $rs->nama_lengkap;
			$row         = json_encode($rs);

			$link        ='<button data-dismiss=\'modal\' onClick=\'return pilihSales('.$row.')\' class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>';
			$sub_array[] = $link;
			$data[]      = $sub_array;  
		}  
		$output = array(  
          "draw"            =>     intval($_POST["draw"]),  
          "recordsFiltered" =>     $this->get_filtered_data_sales(),  
          "data"            =>     $data  
		);  
		echo json_encode($output);  
   	}

   	function make_query_sales($no_limit=null)  
   	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$order_column = array('id_flp_md','nama_lengkap',null); 
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY nama_lengkap ASC';
		$search       = $this->input->post('search')['value'];
		$id_dealer    = $this->m_admin->cari_dealer();
		$searchs      = "WHERE id_dealer=$id_dealer";

		if ($search!='') {
	      $searchs .= " AND (nama_lengkap LIKE '%$search%' 
	      	          OR id_flp_md LIKE '%$search%')
	      ";
	  	}
     	
     	if(isset($_POST["order"]))  
		{	
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
     	}
     	
     	if ($no_limit=='y')$limit='';

   		return $this->db->query("SELECT * FROM ms_karyawan_dealer
   			$searchs $order $limit ");
   	}

   	function get_filtered_data_sales(){  
		return $this->make_query_sales('y')->num_rows();  
   	}

   	public function editSales()
   	{
		$id_prospek         = $this->input->post('id_prospek');
		$data['id_karyawan_dealer'] = $this->input->post('id_karyawan_dealer');
		$data['id_flp_md']          = $this->input->post('id_flp_md');
		if ($this->db->update('tr_prospek',$data,['id_prospek'=>$id_prospek])) {
			$rsp = ['status'=> 'sukses',
					'link'=>base_url('dealer/list_out_prospek')
				   ];
		}else{
			$rsp = ['status'=> 'error',
					'pesan'=> ' Something went wrong'
				   ];
		}
      	echo json_encode($rsp);
   	}
}