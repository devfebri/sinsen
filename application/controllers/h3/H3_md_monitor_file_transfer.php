<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_monitor_file_transfer extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_monitor_file_transfer";
    protected $title  = "Monitoring File Transfer";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');

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

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';

		$this->template($data);
	}
  }