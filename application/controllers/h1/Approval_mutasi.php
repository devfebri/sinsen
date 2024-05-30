<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approval_mutasi extends CI_Controller {

    var $tables =   "tr_mutasi";	
		var $folder =   "h1";
		var $page		=		"approval_mutasi";
    var $pk     =   "id_mutasi";
    var $title  =   "Approval Mutasi POS Dealer";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');

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
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view";				
		$data['dt_mutasi'] = $this->db->query("SELECT * FROM tr_mutasi ORDER BY tgl_mutasi ASC");						
		$this->template($data);			
	}
	public function detail()
	{				
		$data['isi']    		=	$this->page;		
		$data['title']			= "Detail ".$this->title;	
		$id_mutasi 					= $this->input->get("id");	
		$data['set']				= "detail";
		$data['dt_data'] = $this->db->query("SELECT tr_mutasi_detail.*,tr_scan_barcode.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_mutasi_detail INNER JOIN tr_scan_barcode ON tr_mutasi_detail.no_mesin = tr_scan_barcode.no_mesin
										INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
										INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
										WHERE tr_mutasi_detail.id_mutasi = '$id_mutasi'");	
		$data['dt_isi']			= $this->db->query("SELECT * FROM tr_mutasi WHERE id_mutasi = '$id_mutasi'");										 					
		$this->template($data);			
	}	
	public function approve()
	{				
		$data['isi']    		=	$this->page;		
		$data['title']			= $this->title;	
		$id_mutasi 					= $this->input->get("id");	
		$data['set']				= "approve";
		$data['dt_data'] = $this->db->query("SELECT tr_mutasi_detail.*,tr_scan_barcode.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_mutasi_detail INNER JOIN tr_scan_barcode ON tr_mutasi_detail.no_mesin = tr_scan_barcode.no_mesin
										INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
										INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
										WHERE tr_mutasi_detail.id_mutasi = '$id_mutasi'");	
		$data['dt_isi']			= $this->db->query("SELECT * FROM tr_mutasi WHERE id_mutasi = '$id_mutasi'");										 					
		$this->template($data);			
	}	
	public function reject()
	{				
		$data['isi']    		=	$this->page;		
		$data['title']			= $this->title;	
		$id_mutasi 					= $this->input->get("id");	
		$data['set']				= "reject";
		$data['dt_data'] = $this->db->query("SELECT tr_mutasi_detail.*,tr_scan_barcode.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_mutasi_detail INNER JOIN tr_scan_barcode ON tr_mutasi_detail.no_mesin = tr_scan_barcode.no_mesin
										INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
										INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
										WHERE tr_mutasi_detail.id_mutasi = '$id_mutasi'");	
		$data['dt_isi']			= $this->db->query("SELECT * FROM tr_mutasi WHERE id_mutasi = '$id_mutasi'");										 					
		$this->template($data);			
	}	
	public function reject_save()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 1){			
			$data['alasan_reject'] 				= $this->input->post('alasan_reject');	
			$data['status_mutasi'] 				= "rejected";
			$data['updated_at']						= $waktu;		
			$data['updated_by']						= $login_id;	
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/approval_mutasi'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function approve_save()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 1){						
			$data['status_mutasi'] 				= "approve";
			$data['updated_at']						= $waktu;		
			$data['updated_by']						= $login_id;	
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/approval_mutasi'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}