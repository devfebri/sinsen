<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_subsidi_penjualan extends CI_Controller {
	
	var $folder =   "h1/report";
	var $page		=		"rep_subsidi_penjualan";	
	var $isi		=		"laporan_4";	
	var $title  =   "Penjualan Harian";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====		
		$this->load->library('PDF_HTML');
		$this->load->library('PDF_HTML_Table');
		$this->load->helper('terbilang');
		$this->load->library('mpdf_l');
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
		$data['dt_dealer'] = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1");
		$this->template($data);		    	    
	}		
	public function download()
	{								
		$data['tgl1'] = $this->input->post('tgl1');
		$data['tgl2'] = $this->input->post('tgl2');		
		$data['id_dealer'] = $this->input->post('id_dealer');		
		$this->load->view('h1/report/template/temp_subsidi_penjualan',$data);
	}
	public function cetak(){
		$data['tgl1'] = $this->input->post('tgl1');
		$data['tgl2'] = $this->input->post('tgl2');		
		$data['id_dealer'] = $this->input->post('id_dealer');		
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
    $mpdf->charset_in='UTF-8';
    $mpdf->autoLangToFont = true;  	
  	$html = $this->load->view('h1/report/template/temp_subsidi_penjualan', $data, true);    
    $mpdf->WriteHTML($html);    
    $output = 'cetak_.pdf';
    $mpdf->Output("$output", 'I');
  	//$this->load->view('h1/report/template/temp_subsidi_penjualan', $data);    
	}
}