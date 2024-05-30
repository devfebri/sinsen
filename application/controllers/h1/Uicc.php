<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uicc extends CI_Controller {

	var $tables =   "tr_cdb";	
	var $folder =   "h1";
	var $page		=		"uicc";
	var $pk     =   "id_cdb_generate";
	var $title  =   "UICC";

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
		$this->load->library('zip');		
		$this->load->library('csvimport');

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
		$data['set']		= "generate";
		$data['dt_cdb'] = $this->m_admin->getAll($this->tables);			
		$this->template($data);		
	}
	public function generate()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "generate";		
		$this->template($data);		
	}		
	function create_file(){					
		$tgl 		= gmdate("Ymd", time()+60*60*7);				
		$tgl2		= gmdate("his", time()+60*60*7);						
		$nama_file		= "AHM-E20-".$tgl.$tgl2;		
		$dt['no'] 		= $nama_file;		
		$this->load->view("h1/file_uicc",$dt);
	}	
}