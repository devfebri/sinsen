<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class List_ap extends CI_Controller {

	var $tables =   "tr_do_dealer";	
	var $folder =   "h1";
	var $page   =		"list_ap";
	var $isi    =		"list_ap_ar";
	var $pk     =   "no_do";
	var $title  =   "List AP";

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
		$data['dt_rekap']	= $this->db->query("SELECT * FROM tr_rekap_tagihan INNER JOIN ms_vendor ON tr_rekap_tagihan.id_vendor = ms_vendor.id_vendor where tr_rekap_tagihan.status_pelunasan = 0
				ORDER BY tr_rekap_tagihan.id_rekap_tagihan DESC");	
		$this->template($data);			
	}
	public function add()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}
	public function view()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";				
		$this->template($data);			
	}	

	public function detail()
	{				
		$id = $this->input->get('id');
		$tipe = $this->input->get('t');
		if ($tipe=='claim') {
			$set='detail_claim';
			$row 	= $this->db->query("SELECT * FROM tr_claim_sales_program 
					LEFT JOIN ms_dealer on tr_claim_sales_program.id_dealer=ms_dealer.id_dealer
					LEFT JOIN tr_sales_program on tr_claim_sales_program.id_program_md=tr_sales_program.id_program_md
					WHERE id_claim_sp='$id'");
			$data['row']		= $row;
		}
		if ($tipe=='inv_ahm') {
			$set='detail_inv_ahm';
			$data['dt_mon'] = $this->db->query("SELECT tgl_pokok,no_faktur,tgl_faktur,SUM(ppn) AS jum_ppn,SUM(pph) AS jum_pph,SUM(disc_quo+disc_type+disc_other) AS jum_disc,SUM(qty) jum_qty,SUM(harga) AS jum_bayar,SUM(harga) AS jum_amount FROM tr_invoice WHERE tgl_pokok = '$id' GROUP BY no_faktur");
			$data['dt']			= $this->m_admin->getByID("tr_monitor_tempo","tgl_jatuh_tempo",$id);
		}
		if ($tipe=='retur') {
			$set='retur';			
			$data['dt_retur'] = $this->db->query("SELECT  tr_scan_barcode.id_item , tr_scan_barcode.no_rangka , tr_scan_barcode.no_mesin , ms_dealer.id_dealer ,ms_dealer.nama_dealer , tr_retur_dealer.tgl_retur FROM tr_retur_dealer_detail 
				INNER JOIN tr_retur_dealer ON tr_retur_dealer_detail.no_retur_dealer = tr_retur_dealer.no_retur_dealer
				INNER JOIN tr_scan_barcode ON tr_retur_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
				LEFT JOIN ms_dealer ON tr_retur_dealer.id_dealer = ms_dealer.id_dealer
				WHERE tr_retur_dealer_detail.no_retur_dealer = '$id'");                
		}
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= 'Detail '.$this->title;
		$data['set']		= $set;			
		$this->template($data);			
	}	
}