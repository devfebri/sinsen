<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_laporan_gr_per_periode extends Honda_Controller {
	var $folder = "dealer";
	var $page   = "h3_dealer_laporan_gr_per_periode";
	var $title  = "Laporan Good Receipt";

	protected $excel;
	protected $monthLastIndex = 2;

	public function __construct()
	{		 
		parent::__construct();
		$name = $this->session->userdata('nama');
		if ($name=="") echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";

		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$this->excel = new PHPExcel();

		$this->load->database();
		$this->load->model('m_admin');
		$this->load->library('Mcarbon');
	}

	public function index(){
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$this->template($data);	
	}
}