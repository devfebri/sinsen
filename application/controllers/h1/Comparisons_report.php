<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comparisons_report extends CI_Controller {
	
	var $folder =   "h1/laporan";
	var $page		=		"comparisons_report";	
	var $title  =   "Comparisons Report";

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
      ini_set('max_execution_time', 10000);
      $mpdf                           = $this->pdf->load();
      $mpdf->allow_charset_conversion =true;  // Set by default to TRUE
      $mpdf->charset_in               ='UTF-8';
      $mpdf->autoLangToFont           = true;
      $data['set']                   	= 'cetak';            
      $data['tipe']              			= $this->input->get('tipe');
      $data['check_1']              	= $this->input->get('check_1');
      $data['tahun_1']              	= $this->input->get('tahun_1');            
      $data['check_2']              	= $this->input->get('check_2');
      $data['tahun_2']              	= $this->input->get('tahun_2');            
      $data['check_3']              	= $this->input->get('check_3');
      $data['tahun_3']              	= $this->input->get('tahun_3');            
      $data['check_4']              	= $this->input->get('check_4');
      $data['tahun_4']              	= $this->input->get('tahun_4');            
      $data['check_5']              	= $this->input->get('check_5');
      $data['tahun_5']              	= $this->input->get('tahun_5');            
      $data['check_6']              	= $this->input->get('check_6');
      $data['tahun_6']              	= $this->input->get('tahun_6');            
      $data['check_7']              	= $this->input->get('check_7');
      $data['tahun_7']              	= $this->input->get('tahun_7');            
      $data['check_8']              	= $this->input->get('check_8');
      $data['tahun_8']              	= $this->input->get('tahun_8');            
      $data['check_9']              	= $this->input->get('check_9');
      $data['tahun_9']              	= $this->input->get('tahun_9');            
      $data['check_10']              	= $this->input->get('check_10');
      $data['tahun_10']              	= $this->input->get('tahun_10');            
      $data['check_11']              	= $this->input->get('check_11');
      $data['tahun_11']              	= $this->input->get('tahun_11');            
      $data['check_12']              	= $this->input->get('check_12');
      $data['tahun_12']              	= $this->input->get('tahun_12');
      $data['tgl1']              			= sprintf("%'.02d",$this->input->get('tgl1'));            
      $data['tgl2']              			= sprintf("%'.02d",$this->input->get('tgl2'));            
      $data['bulan']              			= $this->input->get('bulan');            
      $data['tahun']              			= $this->input->get('tahun');            
      //$this->load->view('h1/laporan/comparisons_report', $data, true);
      $html = $this->load->view('h1/laporan/comparisons_report', $data, true);      
      $mpdf->WriteHTML($html);
      $output = 'print.pdf';
      $mpdf->showImageErrors = true;
      $mpdf->Output("$output", 'I');      
    }else{
			$data['isi']    = $this->page;		
			$data['title']	= $this->title;															
			$data['set']		= "view";
			$this->template($data);		    	
    }
	}			
}