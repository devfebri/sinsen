<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lap_fitur_dgi extends CI_Controller {
	var $folder =   "h1/laporan";
	var $page	=   "lap_fitur_dgi";
	var $isi	=   "lap_fitur_dgi";
    	var $title  =   "Laporan Penggunaan DGI";

	public function __construct()
	{		
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');

		//===== Load Model =====
		$this->load->model('m_admin');	
		$this->load->model('m_fitur_dgi');	
		//===== Load Library =====

		$this->load->library('upload');

	        $name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						

		if($name=="" OR $auth=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}
	}

	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if($name==""){
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
		$this->template($data);	
	}

	public function generate_file()	{
		if($_POST['process']=='excel_all'){
			$data['list_data'] = $this->m_fitur_dgi->get_data_all();
			$this->load->view('h1/report/template/temp_laporan_dgi_all',$data);
		}elseif($_POST['process']=='excel_tgl'){
			$data['list_data'] = $this->m_fitur_dgi->get_data();
			$this->load->view('h1/report/template/temp_laporan_dgi_tgl',$data);
		}elseif($_POST['process']=='excel_tipe'){
			$data['list_data'] = $this->m_fitur_dgi->get_data();
			$this->load->view('h1/report/template/temp_laporan_dgi_endpoint',$data);
		}
	}
}