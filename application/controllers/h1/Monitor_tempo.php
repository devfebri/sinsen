<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitor_tempo extends CI_Controller {

    var $tables =   "tr_do_dealer";	
		var $folder =   "h1";
		var $page		=		"monitor_tempo";
		var $isi		=		"invoice_terima";
    var $pk     =   "no_do";
    var $title  =   "Monitoring Jatuh Tempo Pembayaran Hutang AHM";

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
		$data['set']		= "view";				
		$data['dt_mon'] = $this->m_admin->getAll("tr_monitor_tempo");
		$this->template($data);			
	}
	public function detail()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";			
		$id							= $this->input->get('id');
		// $data['dt_mon'] = $this->db->query("SELECT tgl_pokok,no_faktur,tgl_faktur,SUM(ppn * qty) AS jum_ppn,SUM(pph * qty) AS jum_pph,SUM(disc_quo+disc_type+disc_other) AS jum_disc,SUM(qty) jum_qty,SUM(qty*harga) AS jum_bayar,SUM(harga * qty) AS jum_amount FROM tr_invoice WHERE tgl_pokok = '$id' GROUP BY no_faktur");
		$data['dt_mon'] = $this->db->query("SELECT tgl_pokok,no_faktur,tgl_faktur,SUM(ppn) AS jum_ppn,SUM(pph) AS jum_pph,SUM(disc_quo+disc_type+disc_other) AS jum_disc,SUM(qty) jum_qty,SUM(harga) AS jum_bayar,SUM(harga) AS jum_amount FROM tr_invoice WHERE tgl_pokok = '$id' GROUP BY no_faktur");
		$data['dt']			= $this->m_admin->getByID("tr_monitor_tempo","tgl_jatuh_tempo",$id);
		$this->template($data);			
	}	
}