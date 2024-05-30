<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitor_out_bantuan extends CI_Controller {

    var $tables =   "tr_do_dealer";	
		var $folder =   "h1";
		var $page		=		"monitor_out_bantuan";
		var $isi		=		"invoice_keluar";
    var $pk     =   "no_do";
    var $title  =   "Monitor Outstanding Bantuan BBN";

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
		$data['page']   = $this->page;		
		$data['dt_rekap'] = $this->db->query("SELECT * FROM tr_monout_bantuan_bbn LEFT JOIN ms_tipe_kendaraan ON tr_monout_bantuan_bbn.tipe = ms_tipe_kendaraan.id_tipe_kendaraan
				LEFT JOIN ms_warna ON ms_warna.id_warna = tr_monout_bantuan_bbn.warna WHERE tr_monout_bantuan_bbn.status_mon <> 'Lunas'");
		$data['set']		= "view";				
		$this->template($data);			
	}	
	public function view()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";				
		$id_rekap = $this->input->get('id');
		$data['dt_rekap'] = $this->db->query("SELECT * FROM tr_monout_piutang_bbn INNER JOIN tr_faktur_stnk ON tr_monout_piutang_bbn.no_bastd = tr_faktur_stnk.no_bastd								
				INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
				WHERE tr_monout_piutang_bbn.no_rekap = '$id_rekap'");
		$this->template($data);			
	}	
}