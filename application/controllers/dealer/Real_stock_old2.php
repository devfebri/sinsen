<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Real_stock extends CI_Controller {

    var $tables =   "tr_real_stock_dealer";	
		var $folder =   "dealer";
		var $page		=		"real_stock";
    var $pk     =   "id_real_stock_dealer";
    var $title  =   "Real Stock Dealer";

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
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view";		
		$id_dealer 			= $this->m_admin->cari_dealer();
		$data['dt_real_stock'] = $this->db->query("SELECT tr_real_stock_dealer.*,ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_real_stock_dealer
						INNER JOIN ms_item ON tr_real_stock_dealer.id_item = ms_item.id_item
						INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna 
						WHERE (tr_real_stock_dealer.stok_rfs + tr_real_stock_dealer.stok_nrfs + tr_real_stock_dealer.stok_pinjaman + tr_real_stock_dealer.stok_booking) > 0
						AND tr_real_stock_dealer.id_dealer = '$id_dealer'	ORDER BY tr_real_stock_dealer.id_item DESC");						
		$this->template($data);	
	}
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$id							= $this->input->get('id');															
		$data['set']		= "detail";		
		$id_dealer 			= $this->m_admin->cari_dealer();
    $am = $this->db->query("SELECT * FROM ms_item WHERE id_item = '$id'")->row();           	
		$data['dt_real_stock'] = $this->db->query("SELECT tr_real_stock_dealer.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_real_stock_dealer
											INNER JOIN ms_item ON tr_real_stock_dealer.id_item = ms_item.id_item
											INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
											INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna 
											WHERE tr_real_stock_dealer.id_item = '$id' AND tr_real_stock_dealer.id_dealer = '$id_dealer' ORDER BY tr_real_stock_dealer.id_item DESC");
		$data['dt_pu'] 	= $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,tr_penerimaan_unit_dealer.id_dealer,tr_scan_barcode.no_mesin,tr_scan_barcode.no_rangka 
											FROM tr_penerimaan_unit_dealer INNER JOIN tr_penerimaan_unit_dealer_detail ON 
											tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer 
											INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
											WHERE tr_scan_barcode.id_item = '$id' AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' 
											ORDER BY tr_scan_barcode.status ASC");
		$this->template($data);	
	}
			
}