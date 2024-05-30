<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_data_kpb extends CI_Controller {
	

	var $folder = "h2/laporan";
	var $page   = "laporan_data_kpb";	
	var $title  = "Laporan Data KPB";

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
	   set_time_limit(500);
		ini_set('memory_limit', '5000M');
      ini_set('max_execution_time', 1000000000000);
	   $mpdf                           = $this->pdf->load();
	   $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
	   $mpdf->charset_in               ='UTF-8';
	   $mpdf->autoLangToFont           = true;
		$data['set']       = 'cetak';            
		$start_date        = $data['start_date'] = $this->input->get('start_date');
		$mode              = $data['mode'] = $this->input->get('mode');
		$end_date          = $data['end_date']   = $this->input->get('end_date');
		$data['id_dealer'] = $this->input->get('id_dealer');
		$data['title']     = $this->title;
		$judul_file        = $data['judul_file'] = $this->title.'_'.$start_date.'_'.$end_date;
		if ($mode=='download') {
			$html = $this->load->view('h2/laporan/laporan_data_kpb', $data);
		}elseif ($mode=='print') {
			$html = $this->load->view('h2/laporan/laporan_data_kpb', $data, true);	    
	      // render the view into HTML
	      $mpdf->WriteHTML($html);
	      // write the HTML into the mpdf
	      $mpdf->Output("$judul_file.pdf", 'I');	
		}
	  }else{
				$data['isi']    = $this->page;		
				$data['title']	= $this->title;															
				$data['set']		= "view";
				$this->template($data);		    	
	  }
	}	
	// public function	tes(){
	// 	$data['isi']    = $this->page;		
	// 	$data['title']	= $this->title;															
	// 	$data['set']		= "view";
	// 	$this->load->view("h1/laporan/tes_laporan");		
	// }	
}