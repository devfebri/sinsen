<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Packing_sheet extends CI_Controller {

	var $tables =   "tr_pemenuhan_po";	
	var $folder =   "h3";
	var $page		=		"packing_sheet";
	var $pk     =   "no_pemenuhan_po";
	var $title  =   "Packing Sheet";

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
		$data['dt_monitor_picking_list'] = $this->m_admin->getAll("ms_user");
		$this->template($data);			
	}
	public function scan()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "scan";			
		$data['dt_paket'] = $this->db->query("SELECT * FROM ms_paket_bundling WHERE active = 1");									
		$data['dt_karyawan'] 	= $this->m_admin->getSortCond("ms_karyawan","nama_lengkap","ASC");			
		$this->template($data);	
	}
	public function t_detail(){		
		$data['isi'] 		= "tes";
		$this->load->view('h3/t_monitor_picking_list',$data);
	}


	

}