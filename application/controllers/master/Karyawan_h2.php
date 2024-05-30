<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan_h2 extends CI_Controller {

	var $folder =   "master";
	var $page		=		"karyawan_h2";
    var $title  =   "Karyawan H2";

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
		$this->load->helper('tgl_indo');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();		
		if($name=="" OR $auth=='false' OR $sess=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}


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
		$data['isi']       = $this->page;
		$data['title']     = $this->title;															
		$data['set']       = "view";
		$data['dt_result'] = $this->db->query("SELECT ms_karyawan_dealer_h2.*,ms_jabatan.jabatan FROM ms_karyawan_dealer_h2 
			JOIN ms_jabatan ON ms_karyawan_dealer_h2.jabatan=ms_jabatan.id_jabatan
			ORDER BY id_karyawan ASC");
		$this->template($data);	
	}

	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "form";					
		$data['mode']		= "insert";					
		$this->template($data);										
	}

	// function get_id_ptca()
	// {
	// 	$th       = date('Y');
	// 	$bln      = date('m');
	// 	$th_bln   = date('Y-m');
	// 	$th_kecil = date('y');
	// 	$ymd 	  = date('Y-m-d');
	// 	$ymd2 	  = date('ymd');
	// 	$get_data  = $this->db->query("SELECT * FROM tr_rekap_tagihan_ptca
	// 		WHERE LEFT(created_at,4)='$th' 
	// 		ORDER BY created_at DESC LIMIT 0,1");
	//    		if ($get_data->num_rows()>0) {
	// 			$row      = $get_data->row();
	// 			$id_ptca  = substr($row->id_ptca, 5,5);
	// 			$new_kode = $th.'/'.sprintf("%'.05d",$id_ptca+1).'/PTCA';
	// 			$i=0;
	// 			while ($i<1) {
	// 				$cek = $this->db->get_where('tr_rekap_tagihan_ptca',['id_ptca'=>$new_kode])->num_rows();
	// 			    if ($cek>0) {
	// 					$neww     = substr($new_kode, 5,5);
	// 					$new_kode = $th.'/'.sprintf("%'.05d",$neww+1).'/PTCA';
	// 					$i        = 0;
	// 			    }else{
	// 			    	$i++;
	// 			    }
	// 			}
	//    		}else{
	// 			$new_kode   = $th.'/00001/PTCA';
	//    		}
 //   		return strtoupper($new_kode);
	// }

	public function save()
	{		
		$waktu    = gmdate("Y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("Y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');

		$id_karyawan  = $this->input->post('id_karyawan');
		$cek_id_kry = $this->db->get_where('ms_karyawan_dealer_h2',['id_karyawan'=>$id_karyawan]);
		if ($cek_id_kry->num_rows()>0) {
			$rsp = ['status'=> 'error',
					'pesan'=> 'ID Karyawan sudah ada !'
				   ];
      		echo json_encode($rsp);
			exit;
		}
		$honda_id = $this->input->post('honda_id');
		$cek_honda_id = $this->db->get_where('ms_karyawan_dealer_h2',['honda_id'=>$honda_id]);
		if ($cek_honda_id->num_rows()>0) {
			$rsp = ['status'=> 'error',
					'pesan'=> 'Honda ID sudah ada !'
				   ];
      		echo json_encode($rsp);
			exit;
		}

		$data 	= ['id_karyawan'=>$id_karyawan,
				'honda_id'                 => $honda_id,
				'id_dealer'                => $this->input->post('id_dealer'),
				'nama_lengkap'             => $this->input->post('nama_lengkap'),
				'tgl_masuk_kerja'          => $this->input->post('tgl_masuk_kerja'),
				'tgl_resign'               => $this->input->post('tgl_resign'),
				'jabatan'                  => $this->input->post('jabatan'),
				'asal_rekruitment'         => $this->input->post('asal_rekruitment'),
				'tahun_rekruitment'        => $this->input->post('tahun_rekruitment'),
				'train_mekanik'            => $this->input->post('train_mekanik'),
				'seminar_pemilik_ahass'    => $this->input->post('seminar_pemilik_ahass'),
				'train_kepala_bengkel'     => $this->input->post('train_kepala_bengkel'),
				'train_kepala_mekanik'     => $this->input->post('train_kepala_mekanik'),
				'service_advisor'          => $this->input->post('service_advisor'),
				'train_final_inspection'   => $this->input->post('train_final_inspection'),
				'paa_komputer'             => $this->input->post('paa_komputer'),
				'train_claim_proces'       => $this->input->post('train_claim_proces'),
				'sertifikasi_claim_proces' => $this->input->post('sertifikasi_claim_proces'),
				'train_bigbike'            => $this->input->post('train_bigbike'),
				'status'                   => isset($_POST['status'])?'y':null,
				'created_at'               => $waktu,
				'created_by'               => $login_id
			 ];

		// echo json_encode($dt_detail);
		// echo json_encode($upd_claim);
		// echo json_encode($data);
		// exit;
		$this->db->trans_begin();
			$this->db->insert('ms_karyawan_dealer_h2',$data);
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
					'link'=>base_url('master/karyawan_h2')
				   ];
        	$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
      	}
      	echo json_encode($rsp);
	}

	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		$id_karyawan = $this->input->get('id');
		$row = $this->db->query("SELECT * FROM ms_karyawan_dealer_h2 WHERE id_karyawan='$id_karyawan'");
		if ($row->num_rows()>0) {
			$row = $data['row'] = $row->row();
			$data['dealer'] = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer='$row->id_dealer'")->row();
			$data['set']		= "form";
			$data['mode']		= "detail";
			$this->template($data);												
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/karyawan_h2'>";
		}
	}

	public function edit()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		$id_karyawan = $this->input->get('id');
		$row = $this->db->query("SELECT * FROM ms_karyawan_dealer_h2 WHERE id_karyawan='$id_karyawan'");
		if ($row->num_rows()>0) {
			$row = $data['row'] = $row->row();
			$data['dealer'] = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer='$row->id_dealer'")->row();
			$data['set']		= "form";
			$data['mode']		= "edit";
			$this->template($data);												
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/karyawan_h2'>";
		}
	}

	public function save_edit()
	{		
		$waktu    = gmdate("Y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("Y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');

		$id_karyawan  = $this->input->post('id_karyawan');
		// $cek_id_kry = $this->db->get_where('ms_karyawan_dealer_h2',['id_karyawan'=>$id_karyawan]);
		// if ($cek_id_kry->num_rows()>0) {
		// 	$rsp = ['status'=> 'error',
		// 			'pesan'=> 'ID Karyawan sudah ada !'
		// 		   ];
		// 	exit;
		// }
		$honda_id = $this->input->post('honda_id');
		// $cek_honda_id = $this->db->get_where('ms_karyawan_dealer_h2',['honda_id'=>$honda_id]);
		// if ($cek_honda_id->num_rows()>0) {
		// 	$rsp = ['status'=> 'error',
		// 			'pesan'=> 'Honda ID sudah ada !'
		// 		   ];
		// 	exit;
			
		// }

		$data 	= ['honda_id'                 => $honda_id,
				'id_dealer'                => $this->input->post('id_dealer'),
				'nama_lengkap'             => $this->input->post('nama_lengkap'),
				'tgl_masuk_kerja'          => $this->input->post('tgl_masuk_kerja'),
				'tgl_resign'               => $this->input->post('tgl_resign'),
				'jabatan'                  => $this->input->post('jabatan'),
				'asal_rekruitment'         => $this->input->post('asal_rekruitment'),
				'tahun_rekruitment'        => $this->input->post('tahun_rekruitment'),
				'train_mekanik'            => $this->input->post('train_mekanik'),
				'seminar_pemilik_ahass'    => $this->input->post('seminar_pemilik_ahass'),
				'train_kepala_bengkel'     => $this->input->post('train_kepala_bengkel'),
				'train_kepala_mekanik'     => $this->input->post('train_kepala_mekanik'),
				'service_advisor'          => $this->input->post('service_advisor'),
				'train_final_inspection'   => $this->input->post('train_final_inspection'),
				'paa_komputer'             => $this->input->post('paa_komputer'),
				'train_claim_proces'       => $this->input->post('train_claim_proces'),
				'sertifikasi_claim_proces' => $this->input->post('sertifikasi_claim_proces'),
				'train_bigbike'            => $this->input->post('train_bigbike'),
				'status'                   => isset($_POST['status'])?'y':null,
				'updated_at'               => $waktu,
				'updated_by'               => $login_id
			 ];

		// echo json_encode($dt_detail);
		// echo json_encode($upd_claim);
		// echo json_encode($data);
		// exit;
		$this->db->trans_begin();
			$this->db->update('ms_karyawan_dealer_h2',$data,['id_karyawan'=>$id_karyawan]);
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
					'link'=>base_url('master/karyawan_h2')
				   ];
        	$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
      	}
      	echo json_encode($rsp);
	}

	// public function save_approve()
	// {		
	// 	$waktu    = gmdate("Y-m-d H:i:s", time()+60*60*7);
	// 	$tgl      = gmdate("Y-m-d", time()+60*60*7);
	// 	$login_id = $this->session->userdata('id_user');
		
	// 	$id_karyawan    = $this->get_id_ptca();
	// 	$id_dealer  = $this->input->post('id_dealer');
	// 	$start_date = $this->input->post('start_date');
	// 	$end_date   = $this->input->post('end_date');

	// 	$get_detail = $this->generate($start_date, $end_date,$id_dealer);
	// 	foreach ($get_detail as $rs) {
	// 		$upd_claim[] = ['id_rekap_claim'=>$rs->id_rekap_claim,'id_ptca'=>$id_ptca];
	// 	}

	// 	$data 	= ['id_ptca'=>$id_ptca,
	// 			'start_date'         => $start_date,
	// 			'end_date'           => $end_date,
	// 			'id_dealer' => $id_dealer,
	// 			'tgl_ptca'           => date('Y-m-d'),
	// 			'status'             => 'input',
	// 			'created_at'         => $waktu,
	// 			'created_by'         => $login_id
	// 		 ];

	// 	// // echo json_encode($dt_detail);
	// 	// echo json_encode($upd_claim);
	// 	// echo json_encode($data);
	// 	// exit;
	// 	$this->db->trans_begin();
	// 		$this->db->insert('tr_rekap_tagihan_ptca',$data);
	// 		if (isset($upd_claim)) {
	// 			$this->db->update_batch('tr_rekap_claim_waranty',$upd_claim,'id_rekap_claim');
	// 		}
	// 	if ($this->db->trans_status() === FALSE)
 //      	{
	// 		$this->db->trans_rollback();
	// 		$rsp = ['status'=> 'error',
	// 				'pesan'=> ' Something went wrong'
	// 			   ];
 //      	}
 //      	else
 //      	{
 //        	$this->db->trans_commit();
 //        	$rsp = ['status'=> 'sukses',
	// 				'link'=>base_url('master/karyawan_h2')
	// 			   ];
 //        	$_SESSION['pesan'] 	= "Data has been saved successfully";
	// 		$_SESSION['tipe'] 	= "success";
	// 		// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
 //      	}
 //      	echo json_encode($rsp);
	// }

}