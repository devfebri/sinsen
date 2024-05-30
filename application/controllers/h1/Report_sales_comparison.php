<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_sales_comparison extends CI_Controller {
	
	var $folder =   "h1/laporan";
	var $page		=		"report_sales_comparison";	
	var $title  =   "Report Sales Comparison";

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
		if (isset($_GET['cetak'])) {            
      $data['set']                   	= 'cetak';            
      $data['tgl1']              			= $this->input->get('tgl1');
      $data['tgl2']              			= $this->input->get('tgl2');
      $data['bulan_1']              	= $this->input->get('bulan_1');
      $data['tahun_1']              	= $this->input->get('tahun_1');            
      $data['bulan_2']              	= $this->input->get('bulan_2');
      $data['tahun_2']              	= $this->input->get('tahun_2');            
      $data['bulan_3']              	= $this->input->get('bulan_3');
      $data['tahun_3']              	= $this->input->get('tahun_3');            
      $data['bulan_4']              	= $this->input->get('bulan_4');
      $data['tahun_4']              	= $this->input->get('tahun_4');            
      $data['bulan_5']              	= $this->input->get('bulan_5');
      $data['tahun_5']              	= $this->input->get('tahun_5');            
      $data['bulan_6']              	= $this->input->get('bulan_6');
      $data['tahun_6']              	= $this->input->get('tahun_6');            
      $data['bulan_7']              	= $this->input->get('bulan_7');
      $data['tahun_7']              	= $this->input->get('tahun_7');            
      $data['bulan_8']              	= $this->input->get('bulan_8');
      $data['tahun_8']              	= $this->input->get('tahun_8');            
      $data['bulan_9']              	= $this->input->get('bulan_9');
      $data['tahun_9']              	= $this->input->get('tahun_9');            
      $data['bulan_10']              	= $this->input->get('bulan_10');
      $data['tahun_10']              	= $this->input->get('tahun_10');            
      $data['bulan_11']              	= $this->input->get('bulan_11');
      $data['tahun_11']              	= $this->input->get('tahun_11');            
      $data['bulan_12']              	= $this->input->get('bulan_12');
      $data['tahun_12']              	= $this->input->get('tahun_12');            
      $this->load->view('h1/laporan/report_sales_comparison_cetak', $data);      
    }else{
			$data['isi']    = $this->page;		
			$data['title']	= $this->title;															
			$data['set']		= "view";
			$this->template($data);		    	
    }
	}		
	public function	tes(){
		$mpdf                           = $this->pdf->load();
      $mpdf->allow_charset_conversion =true;  // Set by default to TRUE
      $mpdf->charset_in               ='UTF-8';
      $mpdf->autoLangToFont           = true;
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view";
		$this->load->view('template/header',$data);
		$this->load->view("h1/laporan/tes_laporan");				

	}	
}