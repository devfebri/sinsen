<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitor_kerja_picker extends CI_Controller {

	var $tables =   "tr_pemenuhan_po";	
	var $folder =   "h3";
	var $page		=		"monitor_kerja_picker";
	var $pk     =   "no_pemenuhan_po";
	var $title  =   "Monitoring Kerja Picker";

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
		$data['dt_monitor_picking_list'] = $this->db->query("SELECT *,ms_dealer.alamat AS alamat2 FROM tr_pl_part LEFT JOIN ms_dealer ON tr_pl_part.id_dealer = ms_dealer.id_dealer
				LEFT JOIN ms_karyawan ON tr_pl_part.id_karyawan = ms_karyawan.id_karyawan WHERE status_pl <> 'input'");		
		$this->template($data);			
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";			
		$data['dt_paket'] = $this->db->query("SELECT * FROM ms_paket_bundling WHERE active = 1");									
		$data['dt_karyawan'] 	= $this->m_admin->getSortCond("ms_karyawan","nama_lengkap","ASC");			
		$this->template($data);	
	}
	public function t_detail(){		
		$data['isi'] 		= "tes";
		$this->load->view('h3/t_monitor_picking_list',$data);
	}


	

}