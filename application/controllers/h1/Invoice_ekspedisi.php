<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_ekspedisi extends CI_Controller {

    var $tables =   "tr_do_dealer";	
		var $folder =   "h1";
		var $page		=		"invoice_ekspedisi";
		var $isi		=		"invoice_terima";
    var $pk     =   "no_do";
    var $title  =   "Invoice Ekspedisi";

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
		$data['dt_inv']	= $this->m_admin->getAll("tr_invoice_ekspedisi");
		$this->template($data);			
	}
	public function detail()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";	
		$id = $this->input->get('id');
		$data['dt']	= $this->db->query("SELECT tr_penerimaan_unit.no_surat_jalan,tr_penerimaan_unit.tgl_surat_jalan,tr_penerimaan_unit.no_polisi,
				tr_invoice_ekspedisi.no_penerimaan,tr_invoice_ekspedisi.tgl_penerimaan,tr_invoice_ekspedisi.qty_terima,tr_penerimaan_unit.ekspedisi 
				FROM tr_invoice_ekspedisi INNER JOIN tr_penerimaan_unit ON tr_invoice_ekspedisi.no_penerimaan = tr_penerimaan_unit.id_penerimaan_unit
				WHERE tr_invoice_ekspedisi.no_penerimaan = '$id'");			
		$data['dt_inv']	= $this->db->query("SELECT tr_scan_barcode.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_detail 
				ON tr_scan_barcode.no_shipping_list=tr_penerimaan_unit_detail.no_shipping_list
				INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
				INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna				
				WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id'");
		$this->template($data);			
	}	
}