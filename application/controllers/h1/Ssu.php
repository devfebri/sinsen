<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ssu extends CI_Controller {

	var $tables =   "tr_ssu";	
	var $folder =   "h1";
	var $page		=		"ssu";
	var $pk     =   "id_ssu";
	var $title  =   "SSU";

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
		$this->load->library('zip');
		$this->load->library('csvimport');

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
		$data['set']		= "generate";
		$data['dt_ssu'] = $this->m_admin->getAll($this->tables);			
		$this->template($data);		
	}
	public function generate()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "generate";		
		$this->template($data);		
	}	
	public function t_detail(){
		$start_date 	= $this->input->post('start_date');
		$end_date 		= $this->input->post('end_date');
		$dq = "SELECT * FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
				WHERE tr_sales_order.tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' AND (tr_sales_order.generate_ssu IS NULL OR tr_sales_order.generate_ssu = '' OR tr_sales_order.generate_date IS NULL)
				AND (tr_sales_order.status_so = 'so_invoice' OR tr_sales_order.tgl_cetak_invoice IS NOT NULL OR tr_sales_order.tgl_cetak_invoice2 IS NOT NULL)";
		$data['dt_detail'] = $this->db->query($dq);
		$dw = "SELECT tr_sales_order_gc.*,tr_sales_order_gc_nosin.no_mesin,tr_spk_gc.nama_npwp AS nama_konsumen,tr_scan_barcode.no_rangka,tr_spk_gc.no_ktp,tr_spk_gc.alamat FROM tr_sales_order_gc_nosin INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
		 	 	INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc 
		 	 	INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
				WHERE tr_sales_order_gc.tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' AND (tr_sales_order_gc.generate_ssu IS NULL OR tr_sales_order_gc.generate_ssu = '' OR tr_sales_order_gc.generate_date IS NULL)
				AND (tr_sales_order_gc.status_so = 'so_invoice' OR tr_sales_order_gc.tgl_cetak_invoice IS NOT NULL OR tr_sales_order_gc.tgl_cetak_invoice2 IS NOT NULL)";
		$data['dt_detail_gc'] = $this->db->query($dw);
		$this->load->view('h1/t_ssu',$data);
	}
	public function cari_id(){				
		$th 						= date("y");
		$bln 						= date("m");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_ssu ORDER BY id_ssu DESC LIMIT 0,1");						       
	  if($pr_num->num_rows()>0){
	   	$row 	= $pr_num->row();		
	   	$id 	= substr($row->id_ssu,2,5); 
	    $kode = $th.sprintf("%05d", $id+1);
		}else{
			$kode = $th."00001";
		}
		return $kode;
	}
	function create(){					

		//Create SSU

		$tgl 		= gmdate("dmY", time()+60*60*7);				
		$id_ssu								= $this->cari_id();
		$nama_file						= "E20-".$tgl;
		$start_date						= $this->input->post('start_date');
		$end_date							= $this->input->post('end_date');					
		$tanggal							= gmdate("Y-m-d", time()+60*60*7);    
		$waktu								= gmdate("Y-m-d h:i:s", time()+60*60*7);    
		$login_id							= $this->session->userdata('id_user');
		$sql = $this->db->query("SELECT * FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
				WHERE tr_sales_order.tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' AND (tr_sales_order.generate_ssu IS NULL OR tr_sales_order.generate_ssu = '' OR tr_sales_order.generate_date IS NULL)
				AND (tr_sales_order.status_so = 'so_invoice' OR tr_sales_order.tgl_cetak_invoice IS NOT NULL OR tr_sales_order.tgl_cetak_invoice2 IS NOT NULL)");
		foreach ($sql->result() as $isi) {						
			$da['no_mesin']	= $isi->no_mesin;
			$da['id_ssu']		= $id_ssu;
			$cek = $this->db->query("SELECT * FROM tr_ssu_detail WHERE no_mesin='$isi->no_mesin'");
			if ($cek->num_rows()==0) {
				$cek1 = $this->m_admin->insert("tr_ssu_detail",$da);	
			}
		}
		$sql_gc = $this->db->query("SELECT tr_sales_order_gc.*,tr_sales_order_gc_nosin.no_mesin,tr_spk_gc.nama_npwp AS nama_konsumen,tr_scan_barcode.no_rangka,tr_spk_gc.no_ktp,tr_spk_gc.alamat FROM tr_sales_order_gc_nosin INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
		 	 	INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc 
		 	 	INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
				WHERE tr_sales_order_gc.tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' AND (tr_sales_order_gc.generate_ssu IS NULL OR tr_sales_order_gc.generate_ssu = '' OR tr_sales_order_gc.generate_date IS NULL)
				AND (tr_sales_order_gc.status_so = 'so_invoice' OR tr_sales_order_gc.tgl_cetak_invoice IS NOT NULL OR tr_sales_order_gc.tgl_cetak_invoice2 IS NOT NULL)");
		foreach ($sql_gc->result() as $isi) {						
			$da['no_mesin']	= $isi->no_mesin;
			$da['id_ssu']		= $id_ssu;
			$cek = $this->db->query("SELECT * FROM tr_ssu_detail WHERE no_mesin='$isi->no_mesin'");
			if ($cek->num_rows()==0) {
				$cek1 = $this->m_admin->insert("tr_ssu_detail",$da);	
			}							
		}
		$data['id_ssu']				= $id_ssu;
		$data['start_date']		= $start_date;
		$data['end_date']			= $end_date;
		$data['nama_file']		= $nama_file.".SSU";
		$cek2 = $this->m_admin->insert("tr_ssu",$data);											

		$dt['no'] 		= $nama_file;
		$dt['id_ssu'] = $id_ssu;	
		$dt['hari_ini']			= $start_date;			
		$this->load->view("h1/file_ssu",$dt);
	}	
	public function download()
	{					
		$id_ssu				= $this->input->get('id');
		$tr = $this->m_admin->getByID("tr_ssu","id_ssu",$id_ssu)->row();
		$dt['no'] 		= $tr->nama_file;
		$dt['id_ssu'] = $id_ssu;				
		$this->load->view("h1/file_ssu",$dt);		
	}
	public function autodownload(){		
		$tgl 									= gmdate("dmY", time()+60*60*7);				
		$hari_ini 						= gmdate("Y-m-d", time()+60*60*7);				
		$id_ssu								= $this->cari_id();
		$nama_file						= "E20-".$tgl;
		$start_date						= $hari_ini;
		$end_date							= $hari_ini;
		// $start_date						= "2019-03-01";
		// $end_date							= "2019-07-01";
		$tanggal							= gmdate("Y-m-d", time()+60*60*7);    
		$waktu								= gmdate("Y-m-d h:i:s", time()+60*60*7);    
		$login_id							= $this->session->userdata('id_user');
		$sql = $this->db->query("SELECT * FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
				WHERE tr_sales_order.tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' AND (tr_sales_order.generate_ssu IS NULL OR tr_sales_order.generate_ssu = '' OR tr_sales_order.generate_date IS NULL)
				AND (tr_sales_order.status_so = 'so_invoice' OR tr_sales_order.tgl_cetak_invoice IS NOT NULL OR tr_sales_order.tgl_cetak_invoice2 IS NOT NULL)");
		foreach ($sql->result() as $isi) {						
			$da['no_mesin']	= $isi->no_mesin;
			$da['id_ssu']		= $id_ssu;
			$cek1 = $this->m_admin->insert("tr_ssu_detail",$da);														
		}
		$sql_gc = $this->db->query("SELECT tr_sales_order_gc.*,tr_sales_order_gc_nosin.no_mesin,tr_spk_gc.nama_npwp AS nama_konsumen,tr_scan_barcode.no_rangka,tr_spk_gc.no_ktp,tr_spk_gc.alamat FROM tr_sales_order_gc_nosin INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
		 	 	INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc 
		 	 	INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
				WHERE tr_sales_order_gc.tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' AND (tr_sales_order_gc.generate_ssu IS NULL OR tr_sales_order_gc.generate_ssu = '' OR tr_sales_order_gc.generate_date IS NULL)
				AND (tr_sales_order_gc.status_so = 'so_invoice' OR tr_sales_order_gc.tgl_cetak_invoice IS NOT NULL OR tr_sales_order_gc.tgl_cetak_invoice2 IS NOT NULL)");
		foreach ($sql_gc->result() as $isi) {						
			$da['no_mesin']	= $isi->no_mesin;
			$da['id_ssu']		= $id_ssu;
			$cek1 = $this->m_admin->insert("tr_ssu_detail",$da);														
		}
		$data['id_ssu']				= $id_ssu;
		$data['start_date']		= $start_date;
		$data['end_date']			= $end_date;
		$data['nama_file']		= $nama_file.".SSU";
		$cek2 = $this->m_admin->insert("tr_ssu",$data);											

		$no 		= $nama_file;
		$id_ssu = $id_ssu;				
		//$this->load->view("h1/file_ssu",$dt);	


	  $fileLocation = getenv("DOCUMENT_ROOT") . "/HONDA/downloads/ssu/".$no.".SSU";
	  $file = fopen($fileLocation,"w");		
	  $content = "";

		$sql = $this->db->query("SELECT * FROM tr_ssu INNER JOIN tr_ssu_detail ON tr_ssu.id_ssu = tr_ssu_detail.id_ssu 
				INNER JOIN tr_sales_order ON tr_ssu_detail.no_mesin = tr_sales_order.no_mesin
				INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk WHERE tr_ssu.id_ssu = '$id_ssu'");
		foreach ($sql->result() as $isi) {	
			$scan = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$isi->no_mesin);	
			if($scan->num_rows() > 0){
				$r = $scan->row();
				$tipe = $r->tipe;
				$tgl_penerimaan = $r->tgl_penerimaan;
				$tanggal_p = date("dmY", strtotime($tgl_penerimaan));    
			}else{	
				$tipe = "";	
				$tanggal_p = "";
			}
			$dealer = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
							INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
							INNER JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer
							WHERE tr_penerimaan_unit_dealer_detail.no_mesin = '$isi->no_mesin'");	
			if($dealer->num_rows() > 0){
				$d = $dealer->row();
				$kode_dealer_md = $d->kode_dealer_md;
				if($kode_dealer_md=='PSB'){
					$kode_dealer_md = '13384';
				}		
				$tgl_terima = date("dmY", strtotime($d->tgl_penerimaan));    
			}else{
				$kode_dealer_md = "";
				$tgl_terima = "";
			}

			$cek_md = $this->db->query("SELECT * FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
							INNER JOIN tr_invoice_dealer ON tr_picking_list.no_do = tr_invoice_dealer.no_do
							WHERE tr_picking_list_view.no_mesin = '$isi->no_mesin'");
			if($cek_md->num_rows() > 0){
				$y = $cek_md->row();		
				$tgl_md = date("dmY", strtotime($y->tgl_faktur));
			}else{
				$tgl_md = "";
			}

			if($tgl_md==""){
				$tgl_md = date("dmY", strtotime($scan->row()->tgl_faktur_invoice));
			}

			$waktu								= gmdate("Y-m-d h:i:s", time()+60*60*7);    
			$login_id							= $this->session->userdata('id_user');
			
			$dat['generate_ssu']	= $login_id;
			$dat['generate_date']	= $waktu;
			//$cek3 = $this->m_admin->update("tr_sales_order",$dat,"no_mesin",$isi->no_mesin);											

			$tgl_cetak_invoice = date("dmY", strtotime($isi->tgl_cetak_invoice));		
			$tgl_create_ssu = date("dmY", strtotime($isi->tgl_create_ssu));
			$id_kelurahan = $isi->id_kelurahan2;
			$prov = $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
				INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
				INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
				WHERE ms_kelurahan.id_kelurahan = '$id_kelurahan'");
			if($prov->num_rows() > 0){
				$pro = $prov->row();
				$id_provinsi = $pro->id_provinsi;
				$id_kabupaten = $pro->id_kabupaten;
				$id_kecamatan = $pro->id_kecamatan;
				$id_kelurahan = $pro->id_kelurahan;
			}else{
				$id_provinsi = "";$id_kelurahan="";$id_kecamatan="";$id_provinsi="";
			}

			$tr_prospek = $this->m_admin->getByID("tr_prospek","id_customer",$isi->id_customer);
			if($tr_prospek->num_rows() > 0){
				$r = $tr_prospek->row();
				$id_flp = $r->id_flp_md;
			}else{
				$id_flp = "";
			}

			if($isi->jenis_beli == 'Cash'){
				$jenis_beli = 1;
				$dp_stor = "";
				$tenor = "";
				$angsuran = "";
				$id_finance_company = '';
			}else{
				$dp_stor = $isi->dp_stor;
				$tenor = $isi->tenor;
				if($isi->id_finance_company != '' OR $isi->id_finance_company != '- Choose -' OR $isi->id_finance_company != ' - Choose - '){
					$id_finance_company = $isi->id_finance_company;
				}else{
					$id_finance_company = '';
				}
				$angsuran = $isi->angsuran;
				$jenis_beli = 2;
			}

			$sj = $this->db->query("SELECT * FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.id_surat_jalan = tr_surat_jalan.id_surat_jalan
				WHERE tr_surat_jalan_detail.no_mesin = '$isi->no_mesin'");
			$tgl_sj = "";
			if($sj->num_rows() > 0){
				$tgl_sj = date("dmY", strtotime($sj->row()->tgl_surat));				
			}

			$tgl_spes_md = "";
			$tgl_sp = $this->db->query("SELECT DISTINCT(tr_sipb.no_sipb),tgl_spes FROM tr_shipping_list INNER JOIN tr_sipb ON tr_shipping_list.no_sipb = tr_sipb.no_sipb 
				WHERE tr_shipping_list.no_mesin = '$isi->no_mesin'");
			if($tgl_sp->num_rows() > 0){
				$tgl_spes_md = date("dmY", strtotime($tgl_sp->row()->tgl_spes));				
			}

			$content .= "E20;".$isi->no_mesin.";".$isi->no_rangka.";".$tipe.";".$tanggal_p.";".$tgl_sj.";".$kode_dealer_md.";".$tgl_spes_md.";".$tgl_md.";".$tgl_create_ssu.";".$tgl_cetak_invoice.";".$tgl_cetak_invoice.";".$jenis_beli.";".$id_finance_company.";".$dp_stor.";".$tenor.";".$angsuran.";".$tgl_terima.";I;".$id_provinsi.";".$id_kabupaten.";".$id_kecamatan.";".$id_kelurahan.";".$id_flp.";;";
			$content .= "\r\n";		
			//echo "<br>";
		}


		$sql = $this->db->query("SELECT * FROM tr_ssu INNER JOIN tr_ssu_detail ON tr_ssu.id_ssu = tr_ssu_detail.id_ssu 
				INNER JOIN tr_sales_order_gc_nosin ON tr_ssu_detail.no_mesin = tr_sales_order_gc_nosin.no_mesin
				INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
				INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
				INNER JOIN tr_spk_gc ON tr_sales_order_gc_nosin.no_spk_gc = tr_spk_gc.no_spk_gc 
				INNER JOIN tr_spk_gc_detail ON tr_spk_gc.no_spk_gc = tr_spk_gc_detail.no_spk_gc
				WHERE tr_ssu.id_ssu = '$id_ssu'");
		foreach ($sql->result() as $isi) {	
			$scan = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$isi->no_mesin);	
			if($scan->num_rows() > 0){
				$r = $scan->row();
				$tipe = $r->tipe;
				$tgl_penerimaan = $r->tgl_penerimaan;
				$tanggal_p = date("dmY", strtotime($tgl_penerimaan));    
			}else{	
				$tipe = "";	
				$tanggal_p = "";
			}
			$dealer = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
							INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
							INNER JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer
							WHERE tr_penerimaan_unit_dealer_detail.no_mesin = '$isi->no_mesin'");	
			if($dealer->num_rows() > 0){
				$d = $dealer->row();
				$kode_dealer_md = $d->kode_dealer_md;		
				if($kode_dealer_md=='PSB'){
					$kode_dealer_md = '13384';
				}		
				$tgl_terima = date("dmY", strtotime($d->tgl_penerimaan));    
			}else{
				$kode_dealer_md = "";
				$tgl_terima = "";
			}

			$cek_md = $this->db->query("SELECT * FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
							INNER JOIN tr_invoice_dealer ON tr_picking_list.no_do = tr_invoice_dealer.no_do
							WHERE tr_picking_list_view.no_mesin = '$isi->no_mesin'");
			if($cek_md->num_rows() > 0){
				$y = $cek_md->row();		
				$tgl_md = date("dmY", strtotime($y->tgl_faktur));
			}else{
				$tgl_md = "";
			}

			if($tgl_md==""){
				$tgl_md = date("dmY", strtotime($scan->row()->tgl_faktur_invoice));
			}

			$waktu								= gmdate("Y-m-d h:i:s", time()+60*60*7);    
			$login_id							= $this->session->userdata('id_user');
			
			$dat['generate_ssu']	= $login_id;
			$dat['generate_date']	= $waktu;
			//$cek3 = $this->m_admin->update("tr_sales_order_gc",$dat,"id_sales_order_gc",$isi->id_sales_order_gc);											

			$tgl_cetak_invoice = date("dmY", strtotime($isi->tgl_cetak_invoice));		
			$tgl_create_ssu = date("dmY", strtotime($isi->tgl_create_ssu));
			$id_kelurahan = $isi->id_kelurahan2;
			$prov = $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
				INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
				INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
				WHERE ms_kelurahan.id_kelurahan = '$id_kelurahan'");
			if($prov->num_rows() > 0){
				$pro = $prov->row();
				$id_provinsi = $pro->id_provinsi;
				$id_kabupaten = $pro->id_kabupaten;
				$id_kecamatan = $pro->id_kecamatan;
				$id_kelurahan = $pro->id_kelurahan;
			}else{
				$id_provinsi = "";$id_kelurahan="";$id_kecamatan="";$id_provinsi="";
			}

			$tr_prospek = $this->m_admin->getByID("tr_prospek_gc","id_prospek_gc",$isi->id_prospek_gc);
			if($tr_prospek->num_rows() > 0){
				$r = $tr_prospek->row();
				$id_flp = $r->id_flp_md;
			}else{
				$id_flp = "";
			}

			if($isi->jenis_beli == 'Cash'){
				$jenis_beli = 1;
				$dp_stor = "";
				$tenor = "";
				$angsuran = "";
				$id_finance_company = '';
			}else{
				$dp_stor = $isi->dp_stor;
				$tenor = $isi->tenor;
				if($isi->id_finance_company != '' OR $isi->id_finance_company != '- Choose -' OR $isi->id_finance_company != ' - Choose - '){
					$id_finance_company = $isi->id_finance_company;
				}else{
					$id_finance_company = '';
				}
				$angsuran = $isi->angsuran;
				$jenis_beli = 2;
			}

			$sj = $this->db->query("SELECT * FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.id_surat_jalan = tr_surat_jalan.id_surat_jalan
				WHERE tr_surat_jalan_detail.no_mesin = '$isi->no_mesin'");
			$tgl_sj = "";
			if($sj->num_rows() > 0){
				$tgl_sj = date("dmY", strtotime($sj->row()->tgl_surat));				
			}

			$tgl_spes_md = "";
			$tgl_sp = $this->db->query("SELECT DISTINCT(tr_sipb.no_sipb),tgl_spes FROM tr_shipping_list INNER JOIN tr_sipb ON tr_shipping_list.no_sipb = tr_sipb.no_sipb 
				WHERE tr_shipping_list.no_mesin = '$isi->no_mesin'");
			if($tgl_sp->num_rows() > 0){
				$tgl_spes_md = date("dmY", strtotime($tgl_sp->row()->tgl_spes));				
			}

			$content .= "E20;".$isi->no_mesin.";".$isi->no_rangka.";".$tipe.";".$tanggal_p.";".$tgl_sj.";".$kode_dealer_md.";".$tgl_spes_md.";".$tgl_md.";".$tgl_create_ssu.";".$tgl_cetak_invoice.";".$tgl_cetak_invoice.";".$jenis_beli.";".$id_finance_company.";".$dp_stor.";".$tenor.";".$angsuran.";".$tgl_terima.";G;".$id_provinsi.";".$id_kabupaten.";".$id_kecamatan.";".$id_kelurahan.";".$id_flp.";;";
			$content .= "\r\n";		
			//echo "<br>";
		}


		$tr_scan = $this->db->query("SELECT * FROM tr_scan_barcode WHERE status BETWEEN 1 AND 3");
		foreach ($tr_scan->result() as $isi) {	
			$md = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$isi->no_mesin'");	
			if($md->num_rows() > 0){
				$d = $md->row();
				$tipe = $d->tipe;
				$tgl_penerimaan = $d->tgl_penerimaan;
				$tanggal_p = date("dmY", strtotime($tgl_penerimaan));		
			}else{
				$tipe = "";
				$tgl_penerimaan = "";
				$tanggal_p = "";
			}

			$tgl_spes_md = "";
			$tgl_sp = $this->db->query("SELECT * FROM tr_scan_barcode LEFT JOIN tr_shipping_list ON tr_scan_barcode.no_shipping_list = tr_shipping_list.no_shipping_list
    		LEFT JOIN tr_sipb ON tr_shipping_list.no_sipb = tr_sipb.no_sipb WHERE tr_scan_barcode.no_mesin = '$isi->no_mesin'");
			if($tgl_sp->num_rows() > 0){
				$tgl_spes_md = date("dmY", strtotime($tgl_sp->row()->tgl_spes));				
			}
			
			$content .= "E20;".$isi->no_mesin.";".$isi->no_rangka.";".$tipe.";".$tanggal_p.";;;;".$tgl_spes_md.";;;;;;;;;;;;;;;;;";
			$content .= "\r\n";		
			//echo "<br>";		
		}
		$tr_scan = $this->db->query("SELECT * FROM tr_scan_barcode WHERE status = 4");
		foreach ($tr_scan->result() as $isi) {	
			$dealer = $this->db->query("SELECT tr_penerimaan_unit_dealer.*,ms_dealer.*,tr_scan_barcode.tipe FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
							INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
							INNER JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer
							WHERE tr_penerimaan_unit_dealer_detail.no_mesin = '$isi->no_mesin'");	
			if($dealer->num_rows() > 0){
				$d = $dealer->row();
				$kode_dealer_md = $d->kode_dealer_md;
				if($kode_dealer_md=='PSB'){
					$kode_dealer_md = '13384';
				}		
				$tipe = $d->tipe;
				$tgl_penerimaan = $d->tgl_penerimaan;
				$tanggal_p = date("dmY", strtotime($tgl_penerimaan));		
				$tgl_surat = $d->tgl_surat_jalan;
				$tgl_md_out = date("dmY", strtotime($tgl_surat));		
				$tgl_pm = $d->tgl_penerimaan;
				$tgl_dealer = date("dmY", strtotime($tgl_pm));		
			}else{
				$kode_dealer_md = "";
				$tipe = "";
				$tgl_penerimaan = "";
				$tanggal_p = "";
				$tgl_md_out = "";
				$tgl_dealer = "";
			}	
			$cek_sj = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
				WHERE tr_surat_jalan_detail.no_mesin = '$isi->no_mesin'");
			if($cek_sj->num_rows() > 0){
				$t = $cek_sj->row();		
			}else{		
			}
			
			$tgl_in_md = "";
			$tgl_spes_md = "";
			$tgl_sp = $this->db->query("SELECT DISTINCT(tr_sipb.no_sipb),tgl_spes FROM tr_shipping_list INNER JOIN tr_sipb ON tr_shipping_list.no_sipb = tr_sipb.no_sipb 
				WHERE tr_shipping_list.no_mesin = '$isi->no_mesin'");
			if($tgl_sp->num_rows() > 0){
				$tgl_spes_md = date("dmY", strtotime($tgl_sp->row()->tgl_spes));				
			}

			$content .= "E20;".$isi->no_mesin.";".$isi->no_rangka.";".$tipe.";".$tanggal_p.";".$tgl_md_out.";".$kode_dealer_md.";".$tgl_spes_md.";".$tgl_in_md.";;;;;;;;;".$tgl_dealer.";;;;;;;;";
			$content .= "\r\n";					
		}
	  fwrite($file,$content);
	  fclose($file);	
	}
}