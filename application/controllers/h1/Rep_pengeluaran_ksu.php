<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_pengeluaran_ksu extends CI_Controller {
	
	var $folder =   "h1/report";
	var $page		=		"rep_pengeluaran_ksu";	
	var $isi		=		"laporan_3";	
	var $title  =   "Pengeluaran KSU";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====		
		$this->load->library('PDF_HTML');
		$this->load->library('PDF_HTML_Table');
		$this->load->helper('terbilang');
		$this->load->library('mpdf_l');
		$this->load->library('pdf');		

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
		$data['isi']    = $this->isi;		
		$data['title']	= $this->title;															
		$data['set']		= "view";		
		$data['dt_ksu']	= $this->db->query("SELECT * FROM ms_ksu WHERE active = 1");
		$this->template($data);		    	    
	}		
	public function download()
	{		
		$data['tgl1'] 		= $this->input->post('tgl1');						 
		$data['tgl2'] 		= $this->input->post('tgl2');						 
		$data['id_ksu'] 		= $this->input->post('id_ksu');						 
		$this->load->view('h1/report/template/temp_pengeluaran_ksu',$data);
	}
}