<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kontribusi_finance_company extends CI_Controller {
	
	var $folder =   "dealer/laporan";
	var $page		=		"kontribusi_finance_company";
	var $title  =   "Kontribusi Finance Company";

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
      ini_set('memory_limit', '-1');
      ini_set('max_execution_time', 900);
      $mpdf                           = $this->pdf->load();
      $mpdf->allow_charset_conversion =true;  // Set by default to TRUE
      $mpdf->charset_in               ='UTF-8';
      $mpdf->autoLangToFont           = true;
      $data['set']                   	= 'cetak';            
      $data['bulan']              		= $this->input->get('bulan');      
      $data['tahun']              		= $this->input->get('tahun');
      $data['id_dealer']              = $this->m_admin->cari_dealer();
      $html = $this->load->view('dealer/laporan/kontribusi_finance_company', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'print.pdf';
      $mpdf->Output("$output", 'I');
    }else{
			$data['isi']    = $this->page;		
			$data['title']	= $this->title;															
			$data['set']		= "view";			
			$this->template($data);		    	
    }
	}		
	public function tes(){		
    $data['tahun']  = 2018;
		$this->load->view('h1/laporan/tes_laporan', $data);
	}
}