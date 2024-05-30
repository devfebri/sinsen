<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konfirmasi_pu extends CI_Controller {

    var $tables =   "tr_penerimaan_unit_dealer";	
		var $folder =   "dealer";
		var $page		=		"konfirmasi_pu";
    var $pk     =   "id_penerimaan_unit_dealer";
    var $title  =   "Konfirmasi Penerimaan Unit";

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
		$this->load->library('PDF_HTML');
		$this->load->library('mpdf_l');		
		$this->load->library('cart');


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
		$data['set']	= "view";
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_sj'] = $this->db->query("SELECT tr_surat_jalan.*,tr_do_po.no_do,tr_do_po.tgl_do FROM tr_surat_jalan INNER JOIN tr_picking_list ON tr_surat_jalan.no_picking_list=tr_picking_list.no_picking_list 
								INNER JOIN tr_do_po ON tr_picking_list.no_do=tr_do_po.no_do
								WHERE (tr_surat_jalan.status = 'proses') AND tr_surat_jalan.id_dealer = '$id_dealer' ORDER BY tr_surat_jalan.id_surat_jalan DESC");				
		$data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL)");		
		$this->normalisasi();
		$this->template($data);			
	}
	public function normalisasi(){
		$id_dealer = $this->m_admin->cari_dealer();
		$cek = $this->db->query("SELECT *,tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer AS id_ku FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_surat_jalan ON tr_penerimaan_unit_dealer_detail.id_sj = tr_surat_jalan.id_surat_jalan
				INNER JOIN tr_penerimaan_unit_dealer ON tr_surat_jalan.no_surat_jalan = tr_penerimaan_unit_dealer.no_surat_jalan
				WHERE tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer <> tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
				AND tr_penerimaan_unit_dealer_detail.id_sj <> 0 AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'");
		foreach ($cek->result() as $isi) {
			$this->db->query("UPDATE tr_penerimaan_unit_dealer_detail SET id_penerimaan_unit_dealer ='$isi->id_ku' WHERE id_sj = '$isi->id_sj'");
			$this->db->query("UPDATE tr_surat_jalan_detail SET terima = 'ya' WHERE no_mesin = '$isi->no_mesin'");				
			$this->db->query("UPDATE tr_scan_barcode SET status = '4' WHERE no_mesin = '$isi->no_mesin'");							
			$cek_ksu = $this->m_admin->getByID("tr_penerimaan_ksu_dealer","no_surat_jalan",$isi->no_surat_jalan);
			if($cek_ksu->num_rows() > 0){
				$this->db->query("UPDATE tr_penerimaan_ksu_dealer SET id_penerimaan_unit_dealer ='$isi->id_ku' WHERE no_surat_jalan = '$isi->no_surat_jalan'");	
			}			
		}
	}
	public function history()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "history";
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_sj'] = $this->db->query("SELECT tr_penerimaan_unit_dealer.*,tr_surat_jalan.id_surat_jalan,tr_sppm.no_do,tr_surat_jalan.tgl_surat,tr_surat_jalan.no_surat_jalan,tr_sppm.tgl_do
							  FROM tr_penerimaan_unit_dealer LEFT JOIN tr_surat_jalan ON tr_penerimaan_unit_dealer.no_surat_jalan=tr_surat_jalan.no_surat_jalan								
							  LEFT JOIN tr_sppm ON tr_sppm.no_surat_sppm = tr_surat_jalan.no_surat_sppm
								WHERE tr_surat_jalan.status = 'close' AND tr_surat_jalan.id_dealer = '$id_dealer'");				
		$data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL)");
		$this->template($data);			
	}
	public function view()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Detail Konfirmasi Penerimaan Unit";	
		$id 						= $this->input->get("id");	
		$cek_approval   = $this->m_admin->cek_approval($this->tables,$this->pk,$id);		
		if($cek_approval == 'salah'){
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';										
			$_SESSION['tipe'] 	= "danger";			
			echo "<script>history.go(-1)</script>";
		}else{
			$data['set']		= "detail";	
			$data['dt_pu']	= $this->m_admin->getByID("tr_penerimaan_unit_dealer","id_penerimaan_unit_dealer",$id);
			$data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
	    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
	            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
	            	WHERE tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL)");		
			$dq = "SELECT tr_penerimaan_unit_dealer_detail.*,tr_scan_barcode.no_rangka,tr_scan_barcode.id_item,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM tr_penerimaan_unit_dealer_detail 
								INNER JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin = tr_penerimaan_unit_dealer_detail.no_mesin							
								INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
								INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
								WHERE tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = '$id' 
								AND tr_penerimaan_unit_dealer_detail.jenis_pu = 'rfs'";
			$data['dt_rfs'] = $this->db->query($dq);

			$dqe = "SELECT tr_penerimaan_unit_dealer_detail.*,tr_scan_barcode.no_rangka,tr_scan_barcode.id_item,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM tr_penerimaan_unit_dealer_detail 
								INNER JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin = tr_penerimaan_unit_dealer_detail.no_mesin							
								INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
								INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
								WHERE tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = '$id' 
								AND tr_penerimaan_unit_dealer_detail.jenis_pu = 'nrfs'";
			$data['dt_nrfs'] = $this->db->query($dqe);		
			$this->template($data);	
		}		
	}
	
	public function gudang()
	{				
		$data['isi']    	= $this->page;		
		$data['title']		= "Data Penyimpanan";		
		$data['set']	   	= "gudang";							
		$data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL)");
		$this->template($data);	
	}

	public function gudang_show()
	{				
		$data['isi']    	= $this->page;		
		$data['title']		= "Data Lokasi Penyimpanan";		
		$data['set']	   	= "gudang_show_on_menu_lokasi_penyimpanan";							
		$data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL)");
		$this->template($data);	
	}

	public function ksu()
	{				
		$id = $this->input->get('id');
		$data['isi']    	= $this->page;		
		$data['title']		= "Konfirmasi Penerimaan KSU";		
		$data['set']	   	= "ksu";	
		$data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL)");
		$data['dt_ksu'] = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin=tr_scan_barcode.no_mesin
				INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer
				INNER JOIN tr_surat_jalan ON tr_penerimaan_unit_dealer.no_surat_jalan = tr_surat_jalan.no_surat_jalan
				WHERE tr_surat_jalan.id_surat_jalan = '$id'");		
		// $data['v_ksu'] = $this->db->query("SELECT * FROM tr_surat_jalan_ksu INNER JOIN tr_surat_jalan ON tr_surat_jalan_ksu.no_surat_jalan=tr_surat_jalan.no_surat_jalan
		// 	INNER JOIN ms_ksu ON tr_surat_jalan_ksu.id_ksu = ms_ksu.id_ksu
		// 	INNER JOIN ms_tipe_kendaraan ON tr_surat_jalan_ksu.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
		// 	INNER JOIN ms_warna ON tr_surat_jalan_ksu.id_warna = ms_warna.id_warna			
		// 	WHERE tr_surat_jalan.id_surat_jalan = '$id'");
		$data['id_surat_jalan'] = $id;
		$data['ksu_d'] = $this->db->query("SELECT SUM(tr_surat_jalan_ksu.qty) as jum,ms_ksu.id_ksu,ms_ksu.ksu FROM tr_surat_jalan_ksu INNER JOIN ms_ksu ON tr_surat_jalan_ksu.id_ksu = ms_ksu.id_ksu
			INNER JOIN tr_surat_jalan ON tr_surat_jalan_ksu.no_surat_jalan = tr_surat_jalan.no_surat_jalan WHERE tr_surat_jalan.id_surat_jalan = '$id' GROUP BY tr_surat_jalan_ksu.id_ksu");
		$this->template($data);	
	}
	public function cetak_accu()
	{				
		$id = $this->input->get('id');
		$data['isi']    	= $this->page;		
		$data['title']		= "Cetak ACCU";		
		$data['set']	   	= "cetak_accu";	
		$data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL)");
		$data['dt_ksu'] = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin=tr_scan_barcode.no_mesin
							INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer
							INNER JOIN tr_surat_jalan ON tr_penerimaan_unit_dealer.no_surat_jalan = tr_surat_jalan.no_surat_jalan
							WHERE tr_surat_jalan.id_surat_jalan = '$id'");				
		$data['id_surat_jalan'] = $id;
		$data['ksu_d'] = $this->db->query("SELECT SUM(tr_surat_jalan_ksu.qty) as jum,ms_ksu.id_ksu,ms_ksu.ksu FROM tr_surat_jalan_ksu INNER JOIN ms_ksu ON tr_surat_jalan_ksu.id_ksu = ms_ksu.id_ksu
							INNER JOIN tr_surat_jalan ON tr_surat_jalan_ksu.no_surat_jalan = tr_surat_jalan.no_surat_jalan WHERE tr_surat_jalan.id_surat_jalan = '$id' GROUP BY tr_surat_jalan_ksu.id_ksu");
		$this->template($data);	
	}
	public function cetak_act(){
		$id 				= $this->input->get('id');		
		$id_p 			= $this->input->get('id_p');		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;		
		$dt_stiker 	= $this->db->query("SELECT * FROM tr_penerimaan_ksu_dealer INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_ksu_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer
			INNER JOIN ms_ksu ON tr_penerimaan_ksu_dealer.id_ksu = ms_ksu.id_ksu
		 	WHERE tr_penerimaan_ksu_dealer.id_penerimaan_unit_dealer = '$id_p' AND tr_penerimaan_ksu_dealer.id_ksu = '$id'");
		if ($dt_stiker->num_rows()>0) {			
			$mpdf = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
      $mpdf->charset_in='UTF-8';
      $mpdf->autoLangToFont = true;
    	$data['cetak'] = 'cetak_accu';    	    	
    	$data['isi_file'] = $dt_stiker->row();
    	$html = $this->load->view('dealer/cetak_accu', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'cetak_.pdf';
      $mpdf->Output("$output", 'I');


		 //  $pdf = new PDF_HTML('L','cm','A5');
		 //  //$pdf = new FPDF('L','cm',array(8.4,5.2));
		 //  $pdf->SetAutoPageBreak(false);
	  //   $pdf->AddPage();	       

		 //  $pdf->SetFont('ARIAL','',8);		  
		 //  $tgl_mohon 	= date("d F Y", strtotime($row->tgl_penerimaan));		
		 //  $pdf->Cell(0, 1, 'Tgl Penerimaan', 0, 0, 'L');$pdf->Cell(10, 1, ': '.strtoupper($tgl_mohon), 0, 1, 'L');
		 // 	$pdf->Cell(0, 1, 'Kode KSU', 0, 0, 'L');$pdf->Cell(0, 1, ': '.strtoupper($row->id_ksu), 0, 1, 'L');
			// $pdf->Cell(0, 1, 'Nama KSU', 0, 0, 'L');$pdf->Cell(0, 1, ': '.strtoupper($row->ksu), 0, 1, 'L');					 
	 	//   $pdf->Output(); 
		}
	}
	public function cetak_cover()
	{
		$pdf = new FPDF('L','inch',array(8.4,5.2));
	      $pdf->AddPage();
	      $pdf->SetAutoPageBreak(false);
		 //$pdf->SetMargins(8, 8, 8);
	      $id = $this->input->get('id');
	     $dt_so = $this->db->query("SELECT tr_sales_order.*,tr_spk.nama_bpkb as nama_bpkb1, tr_sales_order.no_mesin as no_mesinalias, 
	  				tr_spk.*,tr_prospek.*,tr_scan_barcode.id_item,
	  				ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.deskripsi_ahm, ms_warna.warna,tr_scan_barcode.no_rangka, ms_dealer.kode_dealer_md,ms_dealer.nama_dealer,tr_sales_order.no_mesin,ms_karyawan_dealer.nama_lengkap as nama_sales,tr_spk.id_kelurahan,tr_spk.alamat FROM tr_sales_order 
			LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
			LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
			LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
			
			LEFT JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			LEFT JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna
			LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
			LEFT JOIN ms_karyawan_dealer on tr_prospek.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer
			WHERE tr_sales_order.id_sales_order = '$id'
			");

	    if ($dt_so->num_rows() > 0) {
	    	$so=$dt_so->row();
	    	$fkb = $this->db->query("SELECT nomor_faktur from tr_fkb WHERE no_mesin_spasi='$so->no_mesin'");
			if ($fkb->num_rows() > 0) {
				$fkb = $fkb->row()->nomor_faktur;
			}else{
				$fkb='';
			}	


			$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
		  		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
		  		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
		  		WHERE ms_kelurahan.id_kelurahan = '$so->id_kelurahan'")->row();

			$kelurahan 		= $dt_kel->kelurahan;
			$id_kecamatan = $dt_kel->id_kecamatan;
			$kecamatan 		= $dt_kel->kecamatan;
			$id_kabupaten = $dt_kel->id_kabupaten;
			$kabupaten  	= $dt_kel->kabupaten;
			$id_provinsi  = $dt_kel->id_provinsi;
			$provinsi  		= $dt_kel->provinsi;

	    $pdf->SetFont('ARIAL','B',13);
			$pdf->Cell(120, 5, 'No Faktur : '.$fkb, 0, 1, 'L');
			$pdf->Ln(6);

		    $pdf->SetFont('ARIAL','B',20);
			$pdf->Cell(190, 5, $so->nama_dealer, 0, 1, 'C');
			$pdf->Ln(9);
			$pdf->SetFont('ARIAL','B',18);
			$pdf->Cell(65, 9, 'NAMA', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->nama_bpkb), 0, 1, 'L');
			$pdf->Cell(65, 9, 'ALAMAT', 0, 0, 'L');$pdf->Cell(3, 9, ':', 0, 0, 'L');
			$pdf->MultiCell(142, 9, strtoupper($so->alamat),0, 1);
			$pdf->Cell(65, 9, 'KELURAHAN', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($kelurahan), 0, 1, 'L');
			$pdf->Cell(65, 9, 'KECAMATAN', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($kecamatan), 0, 1, 'L');
			$pdf->Cell(65, 9, 'KOTA', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($kabupaten), 0, 1, 'L');
			$pdf->Cell(65, 9, 'TYPE', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->tipe_ahm), 0, 1, 'L');
			$pdf->Cell(65, 9, 'DESK AHM', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->deskripsi_ahm), 0, 1, 'L');
			$pdf->Cell(65, 9, 'WARNA', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->warna), 0, 1, 'L');
			$thn = $this->m_admin->getByID("tr_fkb","no_mesin_spasi",$so->no_mesin)->row();
			$pdf->Cell(65, 9, 'THN PRODUKSI', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($thn->tahun_produksi), 0, 1, 'L');
			$pdf->Cell(65, 9, 'NO. RANGKA', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->no_rangka), 0, 1, 'L');
			$pdf->Cell(65, 9, 'NO. MESIN', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->no_mesin), 0, 1, 'L');
	   	$tgl = date('d-m-Y', strtotime($so->tgl_cetak_so));
			$pdf->Cell(65, 9, 'TGL PEMBELIAN', 0, 0, 'L');$pdf->Cell(145, 9, ': '.$tgl, 0, 1, 'L');			
			if($so->jenis_beli == 'Kredit'){
				$ft = $this->m_admin->getByID("ms_finance_company","id_finance_company",$so->id_finance_company)->row();
				$fc = "( ".$ft->finance_company." )";
			}else{
				$fc = "";
			}
			$pdf->Cell(65, 9, 'PEMBAYARAN', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->jenis_beli).$fc, 0, 1, 'L');			
			$pdf->Cell(65, 9, 'SALES PEOPLE', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->nama_sales), 0, 1, 'L');
			//$pdf->Line(5,148.5,5,0);
			$pdf->Output(); 
	    }
	}
	public function gudang_save()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		
		$id_s 								= $this->input->post('id');
		$data['gudang'] 			= $this->input->post('gudang');
		$data['kapasitas'] 		= $this->input->post('kapasitas');	
		$data['id_dealer'] 		= $this->m_admin->cari_dealer();				
		if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
			else $data['active'] 		= "";					
		$data['created_at']				= $waktu;		
		$data['created_by']				= $login_id;	
		$this->m_admin->insert("ms_gudang_dealer",$data);
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/konfirmasi_pu/gudang?id=".$id_s."'>";		
	}
	public function gudang_edit()
	{		
		$tabel		= "ms_gudang_dealer";
		$pk 			= "id_gudang_dealer";		
		$id 			= $this->input->get('id');
		$idg 			= $this->input->get('idg');		
		$data['dt_gudang'] = $this->db->query("SELECT * FROM ms_gudang_dealer WHERE id_gudang_dealer = '$idg'");
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "gudang_edit";									
		$data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL)");
		$this->template($data);	
	}
	public function gudang_update()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		
		$id					= $this->input->post("idg");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$id_s 								= $this->input->post('id');
			$data['gudang'] 			= $this->input->post('gudang');
			$data['kapasitas'] 		= $this->input->post('kapasitas');
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;		
			$this->m_admin->update("ms_gudang_dealer",$data,"id_gudang_dealer",$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/konfirmasi_pu/gudang?id=".$id_s."'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function gudang_delete()
	{		
		$tabel		= "ms_gudang_dealer";
		$pk 			= "id_gudang_dealer";
		$id_s 		= $this->input->get('id');		
		$idg 			= $this->input->get('idg');		
		$this->db->trans_begin();			
		$this->db->delete($tabel,array($pk=>$idg));
		$this->db->trans_commit();			
		$result = 'Success';									

		if($this->db->trans_status() === FALSE){
			$result = 'You can not delete this data because it already used by the other tables';										
			$_SESSION['tipe'] 	= "danger";			
		}else{
			$result = 'Data has been deleted succesfully';										
			$_SESSION['tipe'] 	= "success";			
		}
		$_SESSION['pesan'] 	= $result;
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/konfirmasi_pu/gudang?id=".$id_s."'>";
	}	
	public function unit()
	{						
		$id								= $this->input->get('id');		
		$data['isi']    	= $this->page;		
		$data['title']		= "Konfirmasi Penerimaan Unit";		
		$data['set']	   	= "unit";					
		$data['dt_item'] 	= $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");						
		$data['dt_pu']		= $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_sppm ON tr_surat_jalan.no_surat_sppm = tr_sppm.no_surat_sppm
						INNER JOIN tr_do_po ON tr_sppm.no_do = tr_do_po.no_do
						INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
						WHERE tr_surat_jalan.id_surat_jalan = '$id'");		    
    $data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan.id_surat_jalan = '$id' AND tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL)");    
		$this->template($data);										
	}
	public function list_data()
	{				
		$id								= $this->input->get('id');		
		$data['isi']    	= $this->page;		
		$data['title']		= "Konfirmasi Penerimaan Unit";		
		$data['set']	   	= "list";					
		$data['dt_item'] 	= $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");						
		$data['dt_pu']		= $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_sppm ON tr_surat_jalan.no_surat_sppm = tr_sppm.no_surat_sppm
						INNER JOIN tr_do_po ON tr_sppm.no_do = tr_do_po.no_do
						INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
						WHERE tr_surat_jalan.id_surat_jalan = '$id'");		
    
    $data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan.id_surat_jalan = '$id' AND tr_surat_jalan_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL)");
		$this->template($data);										
	}
	public function cari_id(){
		$no_sj					= $this->input->get('no_sj');		
		$kode = $this->m_admin->get_token(20);
		echo $kode;
	
		$kode2="nihil";$kode3="nihil";
		$ambil = $this->db->query("SELECT * FROM tr_surat_jalan WHERE id_surat_jalan = '$no_sj'")->row();
		$cek = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan = '$ambil->no_surat_jalan'")->row();		
		$cek2 = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail WHERE id_sj = '$ambil->id_surat_jalan'")->row();
		if(isset($cek->id_penerimaan_unit_dealer)){			
			$kode3 = $cek->id_penerimaan_unit_dealer;						
		}elseif(isset($cek2->id_penerimaan_unit_dealer)){	
			$kode2 = $cek2->id_penerimaan_unit_dealer;						
		}		
		//$kode3 = "ok";
		echo $kode."|".$kode2."|".$kode3;
	}
	public function t_data(){
		$id 			= $this->input->post('id_pu');
		$jenis_pu = $this->input->post('jenis_pu');					
		$id_sj 		= $this->input->post('id_sj');							
		$data['jenis']  = $this->input->post('jenis_pu');		
		$data['no_sj']  = $this->input->post('no_sj');		
		$cek = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer INNER JOIN tr_surat_jalan ON tr_penerimaan_unit_dealer.no_surat_jalan = tr_surat_jalan.no_surat_jalan 
    				WHERE tr_penerimaan_unit_dealer.no_surat_jalan = '$id'");
		$cek_2 = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_surat_jalan ON tr_penerimaan_unit_dealer_detail.id_sj = tr_surat_jalan.id_surat_jalan 
    				WHERE tr_penerimaan_unit_dealer_detail.id_sj = '$id_sj'");
    if($cek->num_rows() > 0){
    	$tt = $cek->row();
    	$data['mode'] = 'view';
    	$data['dt_data'] = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka,tr_scan_barcode.id_item FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
										INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
										INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
										WHERE tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = '$id' AND tr_penerimaan_unit_dealer_detail.jenis_pu = '$jenis_pu'");		 			
    }elseif($cek_2->num_rows() > 0){
    	$tt = $cek->row();
    	$data['mode'] = 'view';    	
    	$data['dt_data'] = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka,tr_scan_barcode.id_item FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
										INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
										INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
										WHERE tr_penerimaan_unit_dealer_detail.id_sj = '$id_sj' AND tr_penerimaan_unit_dealer_detail.jenis_pu = '$jenis_pu'");		 			
    }else{
    	$data['dt_data'] = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka,tr_scan_barcode.id_item FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
										INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
										INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
										WHERE tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = '$id' AND tr_penerimaan_unit_dealer_detail.jenis_pu = '$jenis_pu'");		 			
    	$data['mode'] = 'input';
    }
		$this->load->view('dealer/t_konfirmasi_pu',$data);				
	}
	public function t_data_list(){
		$id 			= $this->input->post('id_pu');
		$jenis_pu = $this->input->post('jenis_pu');		
		$data['dt_data'] = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka,tr_scan_barcode.id_item FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
										INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
										INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
										WHERE tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = '$id' AND tr_penerimaan_unit_dealer_detail.jenis_pu = '$jenis_pu'");		 			
		$data['mode']  = "edit";			
		$data['jenis']  = $this->input->post('jenis_pu');		
		$data['no_sj']  = $this->input->post('no_sj');		
		$this->load->view('dealer/t_konfirmasi_pu',$data);				
	}
	public function cari_id_real($no_sj){		
		
		if(!empty($no_sj)){
			$sj = $no_sj;
		}else{
			$sj = "";
		}		
		$th 						= date("Y");
		$waktu 					= gmdate("Y-m-d h:i:s", time()+60*60*7);		
		$t 							= gmdate("Y-m-d", time()+60*60*7);				

		$id_dealer = $this->m_admin->cari_dealer();
	 	$get_dealer = $this->db->query("SELECT kode_dealer_md from ms_dealer WHERE id_dealer='$id_dealer'");	
	 	if ($get_dealer->num_rows() > 0) {
			$get_dealer = $get_dealer->row()->kode_dealer_md;
			$panjang = strlen($get_dealer);
		}else{
			$get_dealer ='';
			$panjang = '';
		}

		$cek 						= $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan = '$sj'");									
		$pr_num 				= $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer WHERE RIGHT(id_penerimaan_unit_dealer,$panjang) = '$get_dealer' ORDER BY id_penerimaan_unit_dealer DESC LIMIT 0,1");									
		
		if($cek->num_rows() == 0){
			if($pr_num->num_rows()>0){
				$row 	= $pr_num->row();				
				$pan  = strlen($row->id_penerimaan_unit_dealer)-($panjang + 6);
				$id 	= substr($row->id_penerimaan_unit_dealer,$pan,10)+1;	
				if($id < 10){
						$kode1 = $th."0000".$id;          
	      }elseif($id>9 && $id<=99){
						$kode1 = $th."000".$id;          
	      }elseif($id>99 && $id<=999){
						$kode1 = $th."00".$id;          
	      }elseif($id>999){
						$kode1 = $th."0".$id;          
	      }
				$kode = "KU".$kode1."-".$get_dealer;
			}else{
				$kode = "KU".$th."00001-".$get_dealer;          
			} 	
		}else{
			$r = $cek->row();
			$kode = $r->id_penerimaan_unit_dealer;
		}			
		return $kode;
	}	
	public function save_nosin(){
		$no_mesin		= $this->input->post('no_mesin');
		$id_pu			= $this->input->post('id_pu');
		$id_sj			= $this->input->post('id_sj');
		$waktu 			= date("y-m-d");
		$nosin_spasi  = substr_replace($no_mesin," ", 5, -strlen($no_mesin));
    $cek_th       = $this->db->query("SELECT * FROM tr_fkb WHERE no_mesin = '$nosin_spasi'");
    if($cek_th->num_rows() > 0){
      $amb_th       = $cek_th->row();
      $th_produksi  = $amb_th->tahun_produksi;
    }else{
      $th_produksi  = date('Y');
    }
    $fifo = $this->m_admin->cari_fifo_d($th_produksi);
    //$fifo = "918767822";


		$data['id_penerimaan_unit_dealer']	= $id_pu;
		$data['no_mesin']										= $no_mesin;
		$data['jenis_pu']										= $this->input->post("jenis_pu");
		$data['id_sj']											= $this->input->post("id_sj");
		$data['id_user']										= $this->session->userdata("id_user");
		$jenis_pu														= strtoupper($this->input->post("jenis_pu"));
		$data['fifo']												= $fifo;		
		$data['status_dealer']							= "input";		
		$data['id_user']										= $this->session->userdata("id_user");		
		$cek = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin='$no_mesin' AND id_penerimaan_unit_dealer = '$id_pu'");
		if($cek->num_rows() > 0){
			echo "No Mesin tersebut sudah pernah di scan sebelumnya";
		}else{
			$this->m_admin->insert("tr_penerimaan_unit_dealer_detail",$data);									
			echo "ok";
		}		
		//$this->m_admin->update_stock($row->id_modell,$row->id_warna,"RFS",'+','1');
		
		
	}
	public function delete_data(){
		$id_pu 				= $this->input->post('id_pu');				
		$no_mesin 		= $this->input->post('no_mesin');				
		$mode 				= $this->input->post('mode');				
		
		$rt = $this->m_admin->getByID("tr_penerimaan_unit_dealer_detail","id_penerimaan_unit_dealer_detail",$id_pu)->row();			
		$jenis_pu = strtoupper($rt->jenis_pu);
		$this->db->query("UPDATE tr_surat_jalan_detail SET terima = '' WHERE no_mesin = '$no_mesin'");				
		$rs = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$no_mesin)->row();			
		$id_item 	= $rs->id_item;
		$this->m_admin->update_stock_dealer($id_item,$jenis_pu,"-",1);		
		$this->db->query("DELETE FROM tr_penerimaan_unit_dealer_detail WHERE id_penerimaan_unit_dealer_detail = '$id_pu'");			
		
		
		echo "nihil";
	}
	public function hapus_auto(){
		$id = $this->input->post('id_p');		
		$cek = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail WHERE id_penerimaan_unit_dealer = '$id'");			
		foreach ($cek->result() as $val) {
			$this->db->query("UPDATE tr_surat_jalan_detail SET terima = '' WHERE no_mesin = '$val->no_mesin'");				
			$rt = $this->m_admin->getByID("tr_penerimaan_unit_dealer_detail","id_penerimaan_unit_dealer_detail",$id_pu)->row();			
			$rs = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$val->no_mesin)->row();			
			$jenis_pu = strtoupper($rt->jenis_pu);
			$id_item 	= $rs->id_item;
			$this->m_admin->update_stock_dealer($id_item,$jenis_pu,"-",1);
		}
	$cek = $this->db->query("DELETE FROM tr_penerimaan_unit_dealer_detail WHERE id_penerimaan_unit_dealer = '$id'");			
		echo "nihil";
	}
	public function save()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$id_dealer  = $this->m_admin->cari_dealer();
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		
			$data['tgl_surat_jalan'] 						= $this->input->post('tgl_surat');	
			$data['no_do'] 											= $this->input->post('no_do');	
			$data['id_dealer'] 									= $id_dealer;	
			$data['id_gudang_dealer'] 					= $this->input->post('id_gudang_dealer');	
			$data['tgl_penerimaan'] 						= $this->input->post('tgl_penerimaan');				
			$data['status']											= "input";			
			$data['created_at']									= $waktu;		
			$data['created_by']									= $login_id;	
			$mode																= $this->input->post("mode");
			$no_sj 															= $this->input->post('no_surat_jalan');	
			$id_penerimaan_unit_dealer 					= $this->cari_id_real($no_sj);
			$data['no_surat_jalan'] 						= $no_sj;
			$id_pu 															= $this->input->post('id_penerimaan_unit_dealer');
			$data['id_penerimaan_unit_dealer'] 	= $id_penerimaan_unit_dealer;

			
			$cek_tmp = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail WHERE id_penerimaan_unit_dealer = '$id_pu'");
			if($cek_tmp->num_rows() > 0){
				foreach ($cek_tmp->result() as $amb) {					
					$this->db->query("UPDATE tr_penerimaan_unit_dealer_detail SET id_penerimaan_unit_dealer='$id_penerimaan_unit_dealer' WHERE id_penerimaan_unit_dealer = '$id_pu'");
					$this->db->query("UPDATE tr_surat_jalan_detail SET terima = 'ya' WHERE no_mesin = '$amb->no_mesin'");				
					$this->db->query("UPDATE tr_scan_barcode SET status = '4' WHERE no_mesin = '$amb->no_mesin'");				
					$r = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$amb->no_mesin)->row();
					//$this->m_admin->update_stock_dealer($r->id_item,$amb->jenis_pu,"+",1);										
				}
			}

			if($mode == 'new'){
				$this->m_admin->insert($tabel,$data);		
			}else{			
				$this->m_admin->update("tr_penerimaan_unit_dealer",$data,"id_penerimaan_unit_dealer",$id_penerimaan_unit_dealer);						
			}
				
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/konfirmasi_pu'>";
		// }else{
		// 	$_SESSION['pesan'] 	= "Duplicate entry for primary key";
		// 	$_SESSION['tipe'] 	= "danger";
		// 	echo "<script>history.go(-1)</script>";
		// }
	}
	public function save_ksu(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$no_surat_jalan 		= $this->input->post('no_sj');				
		$id_sj 		= $this->input->post('id_sj');				
		$id_penerimaan_unit_dealer 		= $this->input->post('id_penerimaan_unit_dealer');				
		$cek = 0;
		foreach($no_surat_jalan AS $key => $val){
		 	$id_ksu  	= $_POST['id_ksu'][$key];
			$no_sj 		= $_POST['no_sj'][$key];
			//$id_item 	= $_POST['id_item'][$key];
			$qty_terima  	= $_POST['qty_terima'][$key];
		 	$qty_md  	= $_POST['qty_md'][$key];
			// $no_sl = $_POST['no_sl'][$key];
		 	$result[] = array(
				"id_penerimaan_unit_dealer"  => $id_penerimaan_unit_dealer,
				"id_ksu"  => $_POST['id_ksu'][$key],
				//"id_item"  => $_POST['id_item'][$key],
				"no_surat_jalan"  => $no_sj,
				"qty_md"  => $_POST['qty_md'][$key],
				"qty_terima"  => $_POST['qty_terima'][$key],
				"created_at"  => $waktu,
				"created_by"  => $login_id
		 	); 
		 	if($qty_md < $qty_terima){
		 		$cek = $cek + 1;		 		
		 	}

		 	$rty = $this->db->query("SELECT * FROM tr_penerimaan_ksu_dealer WHERE id_ksu = '$id_ksu' AND no_surat_jalan = '$no_sj' AND id_penerimaan_unit_dealer = '$id_penerimaan_unit_dealer'");
      if($rty->num_rows() > 0){
      	$e = $rty->row();      	
      	$this->db->query("DELETE FROM tr_penerimaan_ksu_dealer WHERE id_penerimaan_ksu_dealer = '$e->id_penerimaan_ksu_dealer'");
      }

		}
		if($cek > 0){			
			$_SESSION['pesan'] 	= "Qty Penerimaan KSU tidak boleh lebih dari jumlah KSU yg di-supply oleh MD";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
			//echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/konfirmasi_pu/ksu?id=".$id_sj."'>";
		}else{			
      $test2 = $this->db->insert_batch('tr_penerimaan_ksu_dealer', $result);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/konfirmasi_pu/ksu?id=".$id_sj."'>";
		}		
	}
	public function close(){
		$id_pu 			= $this->input->get('id');		
		$pr 				= $this->input->get('id_pu');		
		$cek_approval  = $this->m_admin->cek_approval($this->tables,$this->pk,$pr);		
		if($cek_approval == 'salah'){
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';										
			$_SESSION['tipe'] 	= "danger";			
			echo "<script>history.go(-1)</script>";
		}else{
			$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
			$login_id		= $this->session->userdata('id_user');
			$tabel			= "tr_surat_jalan";
			$pk					= "id_surat_jalan";		

			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;	
			$data['status']						= "close";	
			$this->m_admin->update($tabel,$data,$pk,$id_pu);
			$this->db->query("UPDATE tr_penerimaan_unit_dealer SET status = 'close' WHERE id_penerimaan_unit_dealer = '$pr'");

			$cek_tmp = $this->db->query("SELECT tr_scan_barcode.no_mesin FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin 
				WHERE tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = '$id_pu' AND tr_scan_barcode.status <> 4");
			if($cek_tmp->num_rows() > 0){
				foreach ($cek_tmp->result() as $amb) {										
					$this->db->query("UPDATE tr_scan_barcode SET status = '4' WHERE no_mesin = '$amb->no_mesin'");				
				}
			}

			$_SESSION['pesan'] 	= "Status has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/konfirmasi_pu/'>";
		}
	}
		
	public function getScanModal()
	{	
		$id = $this->input->post('id');
		 $data['dt_scan'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_detail 
    					INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            	INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            	WHERE tr_surat_jalan.id_surat_jalan = '$id' AND tr_surat_jalan_detail.no_mesin NOT IN(SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL)");    
		$data['scan'] =	$this->input->post('scan');
		$this->load->view('dealer/t_konfirmasi_pu',$data);
	}
}