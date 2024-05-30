<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riding_test extends CI_Controller {

    var $tables =   "tr_riding_test";	
		var $folder =   "dealer";
		var $page		=		"riding_test";
    var $pk     =   "id_riding_test";
    var $title  =   "Riding Test Guest Book";

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
		$data['set']	= "view";
		$id_dealer = $this->m_admin->cari_dealer();
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
		if (isset($start_date) AND isset($end_date)) {
			if ($start_date !='' or $end_date !='') {
				$data['dt_riding_test'] = $this->db->query("SELECT ms_tipe_kendaraan.tipe_ahm,tr_guest_book.id_list_appointment, tr_riding_test.*,ms_warna.warna FROM tr_riding_test 
					INNER JOIN tr_guest_book ON tr_guest_book.id_guest_book=tr_riding_test.id_guest_book				 	
					INNER JOIN ms_tipe_kendaraan ON tr_riding_test.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
					INNER JOIN ms_warna ON tr_riding_test.id_warna	= ms_warna.id_warna
					WHERE tr_riding_test.id_dealer = '$id_dealer' AND 
					tgl_riding BETWEEN '$start_date' AND '$end_date'
					");	
			}else{
				$data['dt_riding_test'] = $this->db->query("SELECT ms_tipe_kendaraan.tipe_ahm,tr_guest_book.id_list_appointment, tr_riding_test.*,ms_warna.warna FROM tr_riding_test 
					INNER JOIN tr_guest_book ON tr_guest_book.id_guest_book=tr_riding_test.id_guest_book				 	
					INNER JOIN ms_tipe_kendaraan ON tr_riding_test.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
					INNER JOIN ms_warna ON tr_riding_test.id_warna	= ms_warna.id_warna
					WHERE tr_riding_test.id_dealer = '$id_dealer'");	
			}
		}
		else{
			$data['dt_riding_test'] = $this->db->query("SELECT ms_tipe_kendaraan.tipe_ahm,tr_guest_book.id_list_appointment, tr_riding_test.*,ms_warna.warna FROM tr_riding_test 
					INNER JOIN tr_guest_book ON tr_guest_book.id_guest_book=tr_riding_test.id_guest_book				 	
					INNER JOIN ms_tipe_kendaraan ON tr_riding_test.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
					INNER JOIN ms_warna ON tr_riding_test.id_warna	= ms_warna.id_warna
					WHERE tr_riding_test.id_dealer = '$id_dealer'");	
		}							
		$this->template($data);			
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";					
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");										
		$data['dt_guest_book'] = $this->m_admin->getSortCond("tr_guest_book","id_guest_book","ASC");						
		$this->template($data);										
	}	
	public function take_guest()
	{		
		$id_guest_book	= $this->input->post('id_guest_book');	
                       $id_dealer = $this->m_admin->cari_dealer();

		$dt_guest				= $this->db->query("SELECT * FROM tr_guest_book INNER JOIN tr_prospek ON tr_guest_book.id_list_appointment=tr_prospek.id_list_appointment 
						WHERE tr_guest_book.id_guest_book = '$id_guest_book' AND tr_prospek.id_dealer='$id_dealer'");								
		if($dt_guest->num_rows() > 0){
			$da = $dt_guest->row();
			$id_customer = $da->id_customer;
			$nama_konsumen = $da->nama_konsumen;
			$alamat = $da->alamat;
			$no_hp = $da->no_hp;
		}else{
			$id_customer = "";
			$nama_konsumen = "";
			$alamat = "";
			$no_hp = "";
		}
		echo $id_customer."|".$nama_konsumen."|".$alamat."|".$no_hp;
	}	
	public function save()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			$data['id_guest_book'] 					= $this->input->post('id_guest_book');			
			$data['id_tipe_kendaraan'] 			= $this->input->post('id_tipe_kendaraan');	
			$data['id_warna	'] 							= $this->input->post('id_warna');	
			$data['tgl_riding'] 						= $this->input->post('tgl_riding');	
			$data['motor_disukai']					= $this->input->post('motor_disukai');	
			$data['saran'] 									= $this->input->post('saran');	
			$id_dealer = $this->m_admin->cari_dealer();
			$data['id_dealer']				 				= $id_dealer;
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;	
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/riding_test/add'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}		
	public function detail()
	{				
		$id = $this->input->get("id");
		$data['isi']    = $this->page;		
		$data['title']	= "Detail ".$this->title;		
		$data['set']		= "detail";					
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");										
		$data['dt_guest_book'] = $this->m_admin->getSortCond("tr_guest_book","id_guest_book","ASC");						
		$data['dt_riding_test'] = $this->m_admin->getByID("tr_riding_test","id_riding_test",$id);
		$this->template($data);										
	}
}