<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Outstanding_bpkb extends CI_Controller {

    var $tables =   "tr_do_dealer";	
		var $folder =   "h1";
		var $page		=		"outstanding_bpkb";
    var $pk     =   "no_do";
    var $title  =   "Outstanding STNK, Plat & BPKB";

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
		$data['dt_out']	= $this->db->query("SELECT * FROM tr_terima_bj WHERE no_kirim_stnk is null OR no_kirim_plat is null OR no_kirim_bpkb is null ORDER BY id_terima_bj ASC");
		$this->template($data);			
	}	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}		
	public function cetak_tenda_terima()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "cetak_terima";				
		$this->template($data);			
	}	
	public function history()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "history";				
		$data['dt_out']	= $this->db->query("SELECT * FROM tr_terima_bj WHERE no_kirim_stnk is not null AND no_kirim_plat is not null AND no_kirim_bpkb is not null ORDER BY id_terima_bj ASC");
		$this->template($data);			
	}	
}