<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_monitoring_proses_claim_customer extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_monitoring_proses_claim_customer";
    protected $title  = "Monitoring Proses Claim Customer";

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

		$this->load->model('h3_md_claim_dealer_model', 'claim_dealer');
		$this->load->model('h3_md_claim_dealer_parts_model', 'claim_dealer_parts');
		$this->load->model('h3_md_claim_part_ahass_model', 'claim_part_ahass');
		$this->load->model('h3_md_claim_part_ahass_parts_model', 'claim_part_ahass_parts');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}
}