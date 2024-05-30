<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retur_bastd extends CI_Controller {

    var $tables =   "tr_do_dealer";	
		var $folder =   "h1";
		var $page		=		"retur_bastd";
    var $pk     =   "no_do";
    var $title  =   "Retur BASTD";

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
		$data['dt_bbn'] = $this->db->query("SELECT * FROM tr_faktur_stnk INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
					 WHERE tr_faktur_stnk.status_faktur = 'rejected'");
		$this->template($data);			
	}	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}		
	public function terima()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "terima";				
		$this->template($data);			
	}	

	public function terima_retur()
	{				
	
		$no_bastd		=	$this->input->get('id');			
		$dt_upd['status_faktur']		=	'proses';
		$this->m_admin->update('tr_faktur_stnk',$dt_upd,'no_bastd',$no_bastd);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/retur_bastd/'>";					
	}	

}