<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adm_bbn extends CI_Controller {

    var $tables =   "tr_adm_bbn";	
		var $folder =   "h1";
		var $page		=		"adm_bbn";
		var $isi		=		"invoice_terima";
    var $pk     =   "id_adm_bbn";
    var $title  =   "Biaya BBN dan Biro Jasa";

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
		$data['isi']    = $this->isi;															
		$data['title']	= $this->title;															
		$data['page']   = $this->page;		
		$data['dt_adm'] = $this->db->query("SELECT * FROM tr_adm_bbn ORDER BY id_adm_bbn DESC");
		$data['set']		= "view";				
		$this->template($data);			
	}
	public function add()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}
	public function detail()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";				
		$this->template($data);			
	}	
}