<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Simulasi_kredit extends CI_Controller {

	var $tables = "ms_simulasi_kredit";	
	var $folder = "master";
	var $page   = "simulasi_kredit";
	var $title  = "Master Simulasi Kredit";
   	// var $order_column_part = array("id_part","nama_part",'kelompok_vendor',null); 

	public function __construct()
	{		
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');

	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{
			$this->load->view('template/header',$data);
			$this->load->view('template/aside');			
			$this->load->view($this->folder."/".$this->page);		
			$this->load->view('template/footer');
		}
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "index";	
		$data['event']    = $this->db->query("SELECT ms_simulasi_kredit.*,tipe_ahm FROM ms_simulasi_kredit
					JOIN ms_tipe_kendaraan ON ms_simulasi_kredit.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
					ORDER BY created_at DESC");					
		$this->template($data);	
	}

	public function add()
	{				
		$data['isi']     = $this->page;		
		$data['title']   = $this->title;		
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$data['tipe_unit'] = $this->db->get('ms_tipe_kendaraan');
		$this->template($data);	
	}
	
	public function get_id_simulasi()
	{
		$th       = date('Y');
		$bln      = date('m');
		$th_bln   = date('Y-m');
		$th_kecil = date('y');
		$get_data  = $this->db->query("SELECT * FROM ms_simulasi_kredit
			WHERE LEFT(created_at,7)='$th_bln' 
			ORDER BY created_at DESC LIMIT 0,1");
	   		if ($get_data->num_rows()>0) {
				$row        = $get_data->row();
				$id_simulasi = substr($row->id_simulasi, -4);
				$new_kode   = 'SIM_'.$th_kecil.$bln.'_'.sprintf("%'.04d",$id_simulasi+1);
				$i=0;
				while ($i<1) {
					$cek = $this->db->get_where('ms_simulasi_kredit',['id_simulasi'=>$new_kode])->num_rows();
				    if ($cek>0) {
						$neww     = substr($new_kode, -4);
						$new_kode = 'SIM_'.$th_kecil.$bln.'_'.sprintf("%'.04d",$neww+1);
						$i        = 0;
				    }else{
				    	$i++;
				    }
				}
	   		}else{
				$new_kode   = 'SIM_'.$th_kecil.$bln.'_0001';
	   		}
   		return strtoupper($new_kode);
	}

	public function save()
	{		
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		
		$id_simulasi = $data['id_simulasi']           = $this->get_id_simulasi();
		$data['id_tipe_kendaraan']           = $this->input->post('id_tipe_kendaraan');
		$data['harga_unit'] = $this->input->post('harga_unit');

		$data['created_at']           = $waktu;		
		$data['created_by']           = $login_id;

		$this->db->trans_begin();
			$this->db->insert('ms_simulasi_kredit',$data);
			$details          = $this->input->post('details');
			foreach ($details as $key => $val) {
				$dt_detail = ['id_simulasi'=> $id_simulasi,
								'uang_muka'   => $val['uang_muka'],
								'voucher'     => $val['voucher'],
								'cukup_bayar' => $val['cukup_bayar']
						 	 ];	
				$this->db->insert('ms_simulasi_kredit_detail',$dt_detail);
				$id_detail = $this->db->insert_id();
				$tenorAngsuran = array();
				foreach ($val['tenorAngsuran'] as $tr) {
					$tenorAngsuran[] = ['id_detail'=>$id_detail,
										'id_simulasi' =>$id_simulasi,
										'tenor'       => $tr['tenor'],
										'angsuran'    => $tr['angsuran'],
										'angsuran_bundling'    => $tr['angsuran_bundling'],
									 ];
				}
				if (count($tenorAngsuran)>0) {
					$this->db->insert_batch('ms_simulasi_kredit_detail_angsuran',$tenorAngsuran);
				}
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
					'link'=>base_url('master/simulasi_kredit')
				   ];
        	$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
      	}
      	echo json_encode($rsp);
	}

	public function detail()
	{				
		$id_simulasi = $this->input->get('id');
		$data['isi']       = $this->page;		
		$data['title']     = $this->title;		
		$data['mode']      = 'detail';
		$data['set']       = "form";
		$data['tipe_unit'] = $this->db->get('ms_tipe_kendaraan');
		$row    = $this->db->query("SELECT * FROM ms_simulasi_kredit WHERE id_simulasi='$id_simulasi'");
		if ($row->num_rows()>0) {
			$row = $data['row'] = $row->row();
			$id_simulasi = $row->id_simulasi;
			$details = $this->db->query("SELECT * FROM ms_simulasi_kredit_detail WHERE id_simulasi='$id_simulasi'")->result();
			foreach ($details as $dt) {
				$get_tenor = $this->db->get_where('ms_simulasi_kredit_detail_angsuran',['id_detail'=>$dt->id_detail])->result();
				$tenorAngsuran = array();
				foreach ($get_tenor as $tn) {
					$tenorAngsuran[] = ['tenor'=>$tn->tenor,'angsuran'=>$tn->angsuran,'angsuran_bundling'=>$tn->angsuran_bundling];
				}
				$detail[] = ['uang_muka'=>$dt->uang_muka,
						 'voucher'=>$dt->voucher,
						 'cukup_bayar'=>$dt->cukup_bayar,
						 'id_simulasi'=>$dt->id_simulasi,
						 'tenorAngsuran' => $tenorAngsuran
						];
			}
			if (isset($detail)) {
				$data['details'] = $detail;
			}
			// echo json_encode($data);
			$this->template($data);	
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/event'>";
		}
	}

	public function edit()
	{				
		$id_simulasi = $this->input->get('id');
		$data['isi']       = $this->page;		
		$data['title']     = $this->title;		
		$data['mode']      = 'edit';
		$data['set']       = "form";
		$data['tipe_unit'] = $this->db->get('ms_tipe_kendaraan');
		$row    = $this->db->query("SELECT * FROM ms_simulasi_kredit WHERE id_simulasi='$id_simulasi'");
		if ($row->num_rows()>0) {
			$row = $data['row'] = $row->row();
			$id_simulasi = $row->id_simulasi;
			$details = $this->db->query("SELECT * FROM ms_simulasi_kredit_detail WHERE id_simulasi='$id_simulasi'")->result();
			foreach ($details as $dt) {
				$get_tenor = $this->db->get_where('ms_simulasi_kredit_detail_angsuran',['id_detail'=>$dt->id_detail])->result();
				$tenorAngsuran = array();
				foreach ($get_tenor as $tn) {
					$tenorAngsuran[] = ['tenor'=>$tn->tenor,'angsuran'=>$tn->angsuran,'angsuran_bundling'=>$tn->angsuran_bundling];
				}
				$detail[] = ['uang_muka'=>$dt->uang_muka,
						 'voucher'=>$dt->voucher,
						 'cukup_bayar'=>$dt->cukup_bayar,
						 'id_simulasi'=>$dt->id_simulasi,
						 'tenorAngsuran' => $tenorAngsuran
						];
			}
			if (isset($detail)) {
				$data['details'] = $detail;
			}
			// echo json_encode($data);
			$this->template($data);	
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/event'>";
		}
	}

	public function save_edit()
	{		
		$waktu    = gmdate("y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		
		$id_simulasi   = $this->input->post('id_simulasi');
		$data['id_tipe_kendaraan']           = $this->input->post('id_tipe_kendaraan');
		$data['harga_unit'] = $this->input->post('harga_unit');

		$data['updated_at']           = $waktu;		
		$data['updated_by']           = $login_id;

		$this->db->trans_begin();
			$this->db->update('ms_simulasi_kredit',$data,['id_simulasi'=>$id_simulasi]);
			$this->db->delete('ms_simulasi_kredit_detail',['id_simulasi'=>$id_simulasi]);
			$this->db->delete('ms_simulasi_kredit_detail_angsuran',['id_simulasi'=>$id_simulasi]);
			$details          = $this->input->post('details');
			foreach ($details as $key => $val) {
				$dt_detail = ['id_simulasi'=> $id_simulasi,
								'uang_muka'   => $val['uang_muka'],
								'voucher'     => $val['voucher'],
								'cukup_bayar' => $val['cukup_bayar']
						 	 ];	
				$this->db->insert('ms_simulasi_kredit_detail',$dt_detail);
				$id_detail = $this->db->insert_id();
				$tenorAngsuran = array();
				foreach ($val['tenorAngsuran'] as $tr) {
					$tenorAngsuran[] = ['id_detail'=>$id_detail,
										'id_simulasi' =>$id_simulasi,
										'tenor'       => $tr['tenor'],
										'angsuran'    => $tr['angsuran'],
										'angsuran_bundling'    => $tr['angsuran_bundling']
									 ];
				}
				if (count($tenorAngsuran)>0) {
					$this->db->insert_batch('ms_simulasi_kredit_detail_angsuran',$tenorAngsuran);
				}
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
					'link'=>base_url('master/simulasi_kredit')
				   ];
        	$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
      	}
      	echo json_encode($rsp);
	}

	public function delete()
	{		
		$tabel			= $this->tables;
		$pk 			= 'id_simulasi';
		$id 			= $this->input->get('id');		
		$this->db->trans_begin();			
			$this->db->delete('ms_simulasi_kredit_detail',array($pk=>$id));
			$this->db->delete('ms_simulasi_kredit_detail_angsuran',array($pk=>$id));
			$this->db->delete($tabel,array($pk=>$id));
		$this->db->trans_commit();			
		$result = 'Success';									

		if($this->db->trans_status() === FALSE){
			$result = 'You can not delete this data because it already used by the other tables';										
			$_SESSION['tipe'] 	= "danger";			
		}else{
			$result = 'Data has been deleted succesfully';										
			$_SESSION['tipe'] 	= "success";			
		}
		$_SESSION['pesan'] 	= $result;
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/simulasi_kredit'>";
	}
	// public function ajax_bulk_delete()
	// {
	// 	$tabel			= $this->tables;
	// 	$pk 			= $this->pk;
	// 	$list_id 		= $this->input->post('id');
	// 	foreach ($list_id as $id) {
	// 		$this->m_admin->delete($tabel,$pk,$id);
	// 	}
	// 	echo json_encode(array("status" => TRUE));
	// }

	// public function edit()
	// {				
	// 	$id              = $this->input->get('id');
	// 	$data['isi']     = $this->page;		
	// 	$data['title']   = $this->title;		
	// 	$data['mode']    = 'edit';
	// 	$data['dealer']  = $this->db->get('ms_dealer');
	// 	$data['row'] 	 = $this->db->get_where('ms_event',['id_event'=>$id])->row();
	// 	$data['set']     = "form";									
	// 	$this->template($data);	
	// }

	// public function save_edit()
	// {		
	// 	$waktu          = gmdate("y-m-d H:i:s", time()+60*60*7);
	// 	$login_id		= $this->session->userdata('id_user');
	// 	$tabel			= $this->tables;

	// 		$id_event = $data['id_event'] = $this->input->post('id_event');
	// 		$data['nama_event'] = $this->input->post('nama_event');
	// 		$data['sumber']     = $this->input->post('sumber');
	// 		$id_dealer = $data['id_dealer']  = isset($_POST['id_dealer'])?$_POST['id_dealer']:null;
	// 		$tipe      = $data['tipe']       = $this->input->post('tipe',$id_dealer);
	// 		$data['kode_event'] = $this->get_kode_event($tipe, $id_dealer);
	// 		$data['updated_at'] = $waktu;		
	// 		$data['updated_by'] = $login_id;

	// 		$this->db->trans_begin();
	// 			$this->db->update('ms_event',$data,['id_event'=>$id_event]);
	// 		if ($this->db->trans_status() === FALSE)
	//       	{
	// 			$this->db->trans_rollback();
	// 			$_SESSION['pesan'] 	= "Something went wrong";
	// 			$_SESSION['tipe'] 	= "danger";
	// 			echo "<script>history.go(-1)</script>";
	//       	}
	//       	else
	//       	{
	//         	$this->db->trans_commit();
	//         	$_SESSION['pesan'] 	= "Data has been saved successfully";
	// 			$_SESSION['tipe'] 	= "success";
	// 			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/event'>";			
	//       	}							
	// } 
}