<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testing extends CI_Controller {

	public function __construct()
	{		
		parent::__construct();		
		//===== Load Database =====

	}
	protected function template($page, $data)
	{		
		$this->load->view('template/header',$data);			
		$this->load->view('template/aside');
		$this->load->view("$page");		
		$this->load->view('template/footer');		
	}
	public function index(){
		$page						= "denied";		
		$data['title']	= "Access Denied";			
		$data['isi']		= "";		
		$data['judul']	= "Maaf, Anda tidak Memiliki hak akses untuk menu ini!";		
		
		var_dump('sdsd');
		die();

	}	

}
