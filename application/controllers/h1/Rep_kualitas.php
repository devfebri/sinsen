<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_kualitas extends CI_Controller {
	
	var $folder =   "h1/report";
	var $page		=		"rep_kualitas";	
	var $isi		=		"laporan_1";	
	var $title  =   "Data Kualitas Akibat Transportasi";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====		
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
		$this->template($data);		    	    
	}		
	public function	download(){						
		$data['tgl1'] = $tgl1	=  $this->input->post('tgl1');													
		$data['tgl2'] = $tgl2	=  $this->input->post('tgl2');			
		$data['sql'] = $this->db->query("SELECT tr_checker.*,ms_part.nama_part,MID(tr_shipping_list.tgl_sl,3,2) AS bulan,tr_checker_detail.*, tr_shipping_list.no_shipping_list FROM tr_checker 
 			LEFT JOIN tr_checker_detail ON tr_checker.id_checker = tr_checker_detail.id_checker
 			LEFT JOIN tr_shipping_list ON tr_checker.no_mesin = tr_shipping_list.no_mesin  			
 			LEFT JOIN tr_penerimaan_unit_detail ON tr_penerimaan_unit_detail.no_shipping_list = tr_shipping_list.no_shipping_list
 			LEFT JOIN tr_penerimaan_unit ON tr_penerimaan_unit_detail.id_penerimaan_unit = tr_penerimaan_unit.id_penerimaan_unit
 			LEFT JOIN ms_part ON tr_checker_detail.id_part = ms_part.id_part
 			WHERE tr_penerimaan_unit.tgl_penerimaan BETWEEN '$tgl1' AND '$tgl2'");
		$this->load->view("h1/report/template/temp_kualitas",$data);
	}	
}