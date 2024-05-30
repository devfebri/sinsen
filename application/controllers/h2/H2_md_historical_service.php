<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H2_md_historical_service extends CI_Controller{
	
	var $folder ="h2";
	var $page   ="h2_md_historical_service";	
	var $isi    ="Historical Service";	
	var $title  ="Historical Service";
	
	public function __construct(){	
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');	
		$this->load->model('H2_md_historical_service_model','historical_service');	
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
		$data['dt_dealer'] = $this->historical_service->getDataDealer();
		$this->template($data);		    	    
	}	

	public function downloadExcel(){
		$data['id_dealer'] = $id_dealer	= $this->input->post('id_dealer');
		$data['start_date']= $start_date= $this->input->post('tgl1');
		$data['end_date']  = $end_date	= $this->input->post('tgl2');
		// $data['type']      = $type	    = $this->input->post('type');
		
		$data['report'] = $this->historical_service->historical_service($id_dealer,$start_date,$end_date);
		if($_POST['process']=='excel'){
			$this->load->view("h2/laporan/temp_report_historical_service",$data);
		}
	}
	
}
