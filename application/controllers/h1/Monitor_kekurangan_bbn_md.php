<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitor_kekurangan_bbn_md extends CI_Controller {
	
	var $folder =   "h1/laporan";
	var $page		=		"monitor_kekurangan_bbn";
	var $title  =   "Monitor Kekurangan BBN";

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
		$auth = $this->m_admin->user_auth('monitor_kekurangan_bbn_md',"select");		
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
			$data['id_menu'] = $this->m_admin->getMenu("monitor_kekurangan_bbn_md");
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
      $html = $this->load->view('h1/laporan/monitor_kekurangan_bbn', $data, true);
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
	public function export_xls(){		    
		$this->load->view('h1/laporan/monitor_kekurangan_bbn_xl');
	}
}