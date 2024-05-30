<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_guest_book extends CI_Controller {

    var $tables =   "tr_do_dealer";	
		var $folder =   "h1";
		var $page		=		"monitoring_guest_book";
    var $pk     =   "no_do";
    var $title  =   "Monitoring Guest Book";

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
		$data['dt_guest_book'] = $this->db->query("SELECT * FROM tr_guest_book 
						INNER JOIN tr_prospek ON tr_prospek.id_list_appointment=tr_guest_book.id_list_appointment
						INNER JOIN ms_tipe_kendaraan ON tr_guest_book.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON tr_guest_book.id_warna = ms_warna.id_warna						
						ORDER BY tr_guest_book.id_list_appointment ASC");			
		$this->template($data);			
	}		
}