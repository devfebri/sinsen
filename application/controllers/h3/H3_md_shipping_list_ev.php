<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_shipping_list_ev extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_shipping_list_ev";
    protected $title  = "Shipping List EV";

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
	

	public function add(){
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->template($data);
	}


	public function ev_send_status_9(){
		$this->load->model('ev_model');		
		$id_user = $this->session->userdata('id_user');
		$serial_number = '';
		$phoneNo = '';
		$invDirectSalesNo ='';
		$invDirectSalesDate ='';

		$set_acc = array(
			'acc' => '9',
			'invDirectSalesNo'=>$invDirectSalesNo,
			'invDirectSalesDate'=>$invDirectSalesDate,
			'phoneNo'=>$phoneNo,
			'serial_number' => $serial_number,
			'user' => $id_user,
		);

		$this->ev_model->UpdateAcc($set_acc);

	}
	
	


}