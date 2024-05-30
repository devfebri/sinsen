<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Crash extends CI_Controller {

	public function __construct()
	{		
		parent::__construct();		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url','string');    				
		$this->load->model("m_admin");
	}
	protected function template($page, $data)
	{		
		$this->load->view('template/header',$data);			
		$this->load->view('template/aside');
		$this->load->view("$page");		
		$this->load->view('template/footer');		
	}
	public function index(){
		$page						= "crash";		
		$data['title']	= "Access Denied";			
		$data['isi']		= "";		
		$data['judul']	= "Maaf, terdapat user lain yg sedang login dg akun anda!";			
		//$this->template($page, $data);
		$this->load->view($page, $data);
	}	
}
