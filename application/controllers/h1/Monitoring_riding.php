<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_riding extends CI_Controller {

    var $tables =   "tr_do_dealer";	
		var $folder =   "h1";
		var $page		=		"monitoring_riding";
    var $pk     =   "no_do";
    var $title  =   "Monitoring Riding";

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
		$data['dt_riding'] = $this->db->query("SELECT * FROM tr_riding_test INNER JOIN tr_guest_book 
				ON tr_riding_test.id_guest_book=tr_guest_book.id_guest_book INNER JOIN tr_prospek
				ON tr_guest_book.id_list_appointment = tr_prospek.id_list_appointment 				
				ORDER BY id_riding_test ASC");		
		$this->template($data);			
	}		
}