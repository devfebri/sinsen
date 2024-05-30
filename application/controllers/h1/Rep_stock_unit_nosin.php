<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Rep_stock_unit_nosin extends CI_Controller {
	
	var $folder =   "h1/report";
	var $page		=		"rep_stock_unit_nosin";	
	var $isi		=		"laporan_1";	
	var $title  =   "Data Stock Unit No mesin";
	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====		
		$this->load->library('pdf');		
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
		$data['set']		= "view";
		$this->template($data);		    	    
	}		
	public function	download(){		
		$data['no_do'] = $no_do				= $this->input->post('no_do');
		$data['kode_item'] = $kode_item		= $this->input->post('kode_item');
		$data['status_ssu'] = $status_ssu		= $this->input->post('status_ssu');
		$data['status_sale'] = $status_sale	= $this->input->post('status_sale');
		$data['status_lokasi'] = $status_lokasi = $this->input->post('status_lokasi');
		$data['tahun_ssu'] = $tahun_ssu		= $this->input->post('tahun_ssu');
		$data['bulan_ssu'] = $bulan_ssu		= $this->input->post('bulan_ssu');		
		$where = "WHERE 1=1 ";
		if ($no_do!='') {
			$where .=" AND tr_do_po.no_do = '$no_do'";
		}
		if ($kode_item!='') {
			$where .=" AND tr_surat_jalan_detail.id_item = '$kode_item'";
		}
		if($status_ssu=='Ya'){
			$where .= " AND (tr_sales_order.tgl_create_ssu IS NOT NULL OR tr_sales_order_gc.tgl_create_ssu IS NOT NULL)";
		}elseif($status_ssu=='Tidak'){
			$where .= " AND (tr_sales_order.tgl_create_ssu IS NULL OR tr_sales_order_gc.tgl_create_ssu IS NULL)";
		}
		if($status_sale!=''){
			$where .= " AND tr_scan_barcode.tipe = '$status_sale'";
		}
		if($tahun_ssu!=''){
			$where .= " AND (LEFT(tr_sales_order.tgl_create_ssu,4) = '$tahun_ssu' OR LEFT(tr_sales_order_gc.tgl_create_ssu,4) = '$tahun_ssu')";
		}
		$bulan_2 = sprintf("%'.02d",$bulan_ssu);		
		if($bulan_ssu!=''){
			$where .= " AND (MID(tr_sales_order.tgl_create_ssu,6,2) = '$bulan_2' OR MID(tr_sales_order_gc.tgl_create_ssu,6,2) = '$bulan_2')";
		}
		if($status_lokasi!=''){
			$where .= " AND tr_scan_barcode.status = '$status_lokasi'";
		}
		$data['sql'] = $this->db->query("SELECT tr_scan_barcode.no_mesin,tr_scan_barcode.id_item,ms_tipe_kendaraan.deskripsi_samsat,tr_fkb.tahun_produksi,
					tr_scan_barcode.fifo,tr_scan_barcode.lokasi,tr_scan_barcode.slot,tr_scan_barcode.status,tr_scan_barcode.status,tr_do_po.no_do,tr_scan_barcode.tipe,
					ms_dealer.kode_dealer_md,ms_dealer.nama_dealer FROM tr_surat_jalan_detail LEFT JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
					LEFT JOIN tr_sales_order ON tr_scan_barcode.no_mesin = tr_sales_order.no_mesin
					LEFT JOIN tr_sales_order_gc_nosin ON tr_scan_barcode.no_mesin = tr_sales_order_gc_nosin.no_mesin
					INNER JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
					LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
					LEFT JOIN tr_fkb ON tr_scan_barcode.no_mesin = tr_fkb.no_mesin_spasi
					LEFT JOIN tr_do_po ON tr_surat_jalan_detail.no_do = tr_do_po.no_do
					LEFT JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
					$where");
		$this->load->view("h1/report/template/temp_stock_unit_nosin",$data);
	}	
}