<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_indent_ahm extends CI_Controller{
	
	var $folder ="h1/laporan";
	var $page   ="laporan_indent_ahm";	
	var $isi    ="Laporan Indent AHM .UIND";	
	var $title  ="Laporan Indent AHM .UIND";
	
	public function __construct(){	
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');	
		$this->load->model('H1_md_laporan_indent_ahm');	
		//===== Load Library =====		
		// $this->load->library('pdf');		
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

	protected function template($data){
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
	
	public function index(){	
		$data['isi']    = $this->isi;		
		$data['title']	= $this->title;															
		$data['set']	= "view";
		$data['dt_dealer'] = $this->H1_md_laporan_indent_ahm->getDataDealer();
		$this->template($data);		    	    
	}	

	public function downloadExcel(){
		$data['id_dealer'] = $id_dealer	= $this->input->post('id_dealer');
		$data['start_date']= $start_date= $this->input->post('tgl1');
		$data['end_date']  = $end_date	= $this->input->post('tgl2');
		$data['spk']	   = $this->H1_md_laporan_indent_ahm->downloadSPK($id_dealer,$start_date,$end_date);
		
		if($_POST['process']=='excel'){
			$this->load->view("h1/laporan/temp_laporan_indent_ahm",$data);
		}else if($_POST['process']=='csv'){
			$this->load->view("h1/laporan/temp_laporan_indent_ahm_csv",$data);

		}	
	}	
}
?>