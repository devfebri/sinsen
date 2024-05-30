<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitor_stock extends CI_Controller {

    var $tables =   "tr_po_aksesoris";	
		var $folder =   "h1";
		var $page		=		"monitor_stock";
    var $pk     =   "no_po_aksesoris";
    var $title  =   "Monitor Stock Accessoris Bundling";

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
		$data['dt_part'] = $this->db->query("SELECT * FROM tr_stok_part_h1 INNER JOIN ms_part ON tr_stok_part_h1.id_part = ms_part.id_part 
			WHERE tr_stok_part_h1.qty_h1 > 0");
		// $data['dt_part'] = $this->db->query("SELECT sum(terima) as jum,tr_po_aksesoris_detail.id_part,ms_part.nama_part 
		// 	FROM tr_po_aksesoris_detail INNER JOIN ms_part ON tr_po_aksesoris_detail.id_part = ms_part.id_part 
		// 	GROUP BY tr_po_aksesoris_detail.id_part ORDER BY ms_part.nama_part ASC");
		$this->template($data);			
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}
}