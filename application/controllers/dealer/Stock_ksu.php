<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_ksu extends CI_Controller {

    var $tables =   "tr_real_stock_dealer";	
		var $folder =   "dealer";
		var $page		=		"stock_ksu";
    var $pk     =   "id_real_stok_dealer";
    var $title  =   "Stock KSU Dealer";

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
		$this->template($data);	
	}

	public function stock_oem()
	{				
        $id_dealer = $this->m_admin->cari_dealer();
		$data['isi']     = $this->page;		
		$data['title']	 = 'Stock OEM';															
		$data['set']	 = "stock_oem";	
		$data['battery'] = $this->db->query("SELECT *, sum(part_id) as qty from tr_stock_battery where id_dealer = '$id_dealer' group by id_dealer, part_id")->result();
		$this->template($data);	
	}

	public function stock_oem_detail()
	{				
        $id_dealer = $this->m_admin->cari_dealer();
		$data['isi']     = $this->page;		
		$data['title']	 = 'Stock OEM';															
		$data['set']	 = "stock_oem";	
		$data['battery'] = $this->db->query("SELECT * from tr_stock_battery where id_dealer = '$id_dealer' group by id_dealer")->result();
		$this->template($data);	
	}


}