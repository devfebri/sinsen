<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bantuan_bbn_d extends CI_Controller {

  var $tables =   "tr_bantuan_bbn_d";	
	var $folder =   "dealer";
	var $page		=		"bantuan_bbn_d";
  var $pk     =   "id_bantuan_bbn_d";
  var $title  =   "Bantuan BBN";

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
		$this->load->library('cfpdf');

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
		$isi = $this->input->get("p");
		if($isi=='joint_promo'){			
			$data['set']		= "view";
			$id_dealer 			= $this->m_admin->cari_dealer();
			$data['dt_joint'] = $this->db->query("SELECT *,tr_scan_barcode.no_rangka,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_bantuan_bbn_joint LEFT JOIN tr_scan_barcode ON tr_bantuan_bbn_joint.no_mesin = tr_scan_barcode.no_mesin
					LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
					LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna 
					WHERE tr_bantuan_bbn_joint.id_dealer = '$id_dealer' ORDER BY tr_bantuan_bbn_joint.id_bantuan_bbn_joint DESC");				
		}else{
			$data['set']		= "view2";
			$id_dealer 			= $this->m_admin->cari_dealer();			
			$data['dt_luar'] = $this->db->query("SELECT tr_bantuan_bbn_luar.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_bantuan_bbn_luar 
					LEFT JOIN ms_tipe_kendaraan ON tr_bantuan_bbn_luar.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
					LEFT JOIN ms_warna ON tr_bantuan_bbn_luar.id_warna = ms_warna.id_warna 
					WHERE tr_bantuan_bbn_luar.id_dealer = '$id_dealer' ORDER BY tr_bantuan_bbn_luar.id_bantuan_bbn_luar DESC");				
		}
		$this->template($data);			
	}	
	public function take_nosin()
	{				
		$no_mesin    		= $this->input->post("no_mesin");		
		$cek = $this->db->query("SELECT tr_spk_gc.*,tr_sales_order_gc_nosin.*,tr_scan_barcode.no_rangka,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_sales_order_gc_nosin INNER JOIN tr_spk_gc ON tr_sales_order_gc_nosin.no_spk_gc = tr_spk_gc.no_spk_gc
				LEFT JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
				LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
				LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna");		

		if($cek->num_rows() > 0){
			$amb = $cek->row();
			
			$id_kelurahan = $amb->id_kelurahan;	
			$region = explode("-",$this->m_admin->getRegion($id_kelurahan));             
			$kelurahan		= $region[4];
			$kecamatan		= $region[5];
			$kabupaten		= $region[6];
			$provinsi 		= $region[7];

			echo "ok|".$amb->no_mesin."|".$amb->no_rangka."|".$amb->alamat."|".$amb->tipe_ahm."|".$amb->warna."|".$amb->no_ktp."|".$amb->no_hp."|".$kelurahan."|".$kecamatan."|".$kabupaten."|".$provinsi;
		}else{
			echo "nihil";
		}
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$isi = $this->input->get("p");
		if($isi=='joint_promo'){
			$data['set']		= "insert";					
		}else{
			$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan","id_pekerjaan","ASC");								
			$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");										
			$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna","warna","ASC");										
			$data['set']		= "insert2";					
		}
		$this->template($data);										
	}
	public function edit()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;			
		$id = $this->input->get("id");
		$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan","id_pekerjaan","ASC");								
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");										
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna","warna","ASC");										
		$data['dt_bantuan'] = $this->m_admin->getByID("tr_bantuan_bbn_luar","id_bantuan_bbn_luar",$id);
		$data['set']		= "edit";							
		$this->template($data);										
	}

	public function view()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;			
		$id = $this->input->get("id");
		$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan","id_pekerjaan","ASC");								
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");										
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna","warna","ASC");										
		$data['dt_bantuan'] = $this->m_admin->getByID("tr_bantuan_bbn_luar","id_bantuan_bbn_luar",$id);
		$data['set']		= "detail";							
		$this->template($data);										
	}

	public function cari_bbn(){		
		$id_tipe_kendaraan 	= $this->input->post('id_tipe_kendaraan');		
		$cek = $this->db->query("SELECT a.id_tipe_kendaraan, a.biaya_bbn , b.tahun_produksi , b.biaya_bbn as biaya_bbn_bj, b.biaya_instansi as biaya_bj_instansi, b.created_at, b.updated_at  
			FROM ms_bbn_dealer a 
			join ms_bbn_biro b on a.id_tipe_kendaraan = b.id_tipe_kendaraan 
			where a.id_tipe_kendaraan ='$id_tipe_kendaraan'
			order by b.created_at DESC limit 1");						
		if($cek->num_rows()>0){
			$io = $cek->row();
			$biaya = $io->biaya_bbn;
			$biaya_bj = $io->biaya_bbn_bj;
		}else{
			$biaya = 0;
			$biaya_bj = 0;
		}		
		echo $biaya.'|'.$biaya_bj;
	}
	
	public function send()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');		
		$id = $this->input->get("id");		
		$status_approval = $this->input->get("s");	
		if($status_approval ==1){	
			$ds['status'] 			= "approved";
		}else if($status_approval ==2){
			$ds['status'] 			= "cancel";
		}
		$ds['updated_at'] 	= $waktu;
		$ds['updated_by'] 	= $login_id;
		$this->m_admin->update("tr_bantuan_bbn_luar",$ds,"id_bantuan_bbn_luar",$id);								
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/bantuan_bbn_d'>";				
	}

	public function cari_id()
	{
		$tgl						= date("d");
		$bln 						= date("m");
		$th 						= date("Y");
		$id_dealer	= $this->m_admin->cari_dealer();
		$get_dealer = $this->db->query("SELECT kode_dealer_md from ms_dealer WHERE id_dealer='$id_dealer'");
		if ($get_dealer->num_rows() > 0) {
			$get_dealer = $get_dealer->row()->kode_dealer_md;
			$panjang = strlen($get_dealer);
		} else {
			$get_dealer = '';
			$panjang = '';
		}
		$kode_dealer = $this->m_admin->getByID("ms_dealer", "id_dealer", $id_dealer)->row()->kode_dealer_md;
		$pr_num = $this->db->query("SELECT id_bantuan_bbn_luar FROM tr_bantuan_bbn_luar WHERE RIGHT(id_bantuan_bbn_luar,$panjang) = '$kode_dealer' AND LEFT(id_bantuan_bbn_luar,4) = '$th' ORDER BY id_bantuan_bbn_luar DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->id_bantuan_bbn_luar) - (10 + $panjang);
			$id 	= substr($row->id_bantuan_bbn_luar, $pan, 5) + 1;
			$id_s 	= substr($row->id_bantuan_bbn_luar, $pan, 5);

			if ($id < 10) {
				$kode1 = $th . "/" . "0000" . $id . "/BBL";
			} elseif ($id > 9 && $id <= 99) {
				$kode1 = $th . "/" . "000" . $id . "/BBL";
			} elseif ($id > 99 && $id <= 999) {
				$kode1 = $th . "/" . "00" . $id . "/BBL";
			} elseif ($id > 999) {
				$kode1 = $th . "/" . "0" . $id . "/BBL";
			}
			$kode = $kode1 . "-" . $kode_dealer;
		} else {
			$kode = $th . "/00001/BBL-" . $kode_dealer;
		}
		return $kode;
	}

	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');		
		$isi = $this->input->get("p");
		$id_bantuan_bbn = $this->cari_id();

		$no_mesin = $this->input->post("no_mesin");
		$cek_stok= $this->db->query("	
			select no_mesin from
			(
				select no_mesin_spasi as no_mesin from tr_fkb 
				union 
				select no_mesin from tr_scan_barcode
				union 
				select no_mesin from tr_bantuan_bbn_luar
			)z where z.no_mesin ='$no_mesin'
		");

		if ($cek_stok->num_rows() > 0) {
			$_SESSION['pesan'] 	= "Gagal Simpan! Silahkan Hubungi Administrator.";
			$_SESSION['tipe'] 	= "danger";		
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/bantuan_bbn_d'>";		
		}else{

		if($isi=='joint_promo'){			
			$ds['no_mesin'] 		= $this->input->post("no_mesin");		
			$ds['nama_stnk'] 		= $this->input->post("nama_stnk");		
			$ds['id_dealer'] 		= $this->m_admin->cari_dealer();
			$ds['status'] 			= "input";
			$ds['created_at'] 	= $waktu;
			$ds['created_by'] 	= $login_id;
			$this->m_admin->insert("tr_bantuan_bbn_joint",$ds);								
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";		
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/bantuan_bbn_d'>";		
		}else{
			$da['id_bantuan_bbn_luar'] 	= $id_bantuan_bbn;
			$da['id_dealer'] 		= $this->m_admin->cari_dealer();
			$da['no_faktur'] 		= $this->input->post("no_faktur");		
			$da['tgl_faktur'] 		= $this->input->post("tgl_faktur");		
			$da['pemohon'] 		= $this->input->post("pemohon");		
			$da['no_mesin'] 		= $this->input->post("no_mesin");		
			$da['no_rangka'] 		= $this->input->post("no_rangka");		
			$da['id_warna'] 		= $this->input->post("id_warna");		
			$da['id_tipe_kendaraan'] 		= $this->input->post("id_tipe_kendaraan");		
			$da['no_ktp'] 		= $this->input->post("no_ktp");		
			$da['no_kk'] 		= $this->input->post("no_kk");	
			$da['no_npwp'] 		= $this->input->post("no_npwp");
			$da['no_tdp'] 		= $this->input->post("no_tdp");		
			$da['nama_konsumen'] 		= $this->input->post("nama_konsumen");		
			$da['no_telp'] 		= $this->input->post("no_telp");
			$da['no_hp'] 		= $this->input->post("no_hp");		
			$da['nama_gadis_ibu'] 		= $this->input->post("nama_gadis_ibu");		
			$da['tgl_lahir_ibu'] 		= $this->input->post("tgl_lahir_ibu");		
			$da['tgl_lahir'] 		= $this->input->post("tgl_lahir");		
			$da['tempat_lahir'] 		= $this->input->post("tempat_lahir");		
			$da['id_pekerjaan'] 		= $this->input->post("pekerjaan");		
			$da['pemenang'] 		= $this->input->post("pemenang");		
			$da['tahun_produksi'] 		= $this->input->post("tahun_produksi");		
			$da['tagih_ke'] 		= $this->input->post("tagih_ke");		
			$da['pemenang_dari'] 		= $this->input->post("pemenang_dari");		
			$da['biaya_adm'] 		= $this->input->post("biaya_adm");		
			$da['biaya_bbn'] 		= $this->input->post("biaya_bbn");
			$da['biaya_bbn_bj'] 		= $this->input->post("biaya_bbn_bj");
			$da['total'] 		= $this->input->post("total");		
			$da['id_kelurahan'] 		= $this->input->post("id_kelurahan");		
			$da['alamat'] 		= $this->input->post("alamat");		
			$da['status'] 			= "input";
			$da['created_at'] 	= $waktu;
			$da['created_by'] 	= $login_id;
			$this->m_admin->insert("tr_bantuan_bbn_luar",$da);								
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";		
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/bantuan_bbn_d'>";		
		}

		}
	}
	public function	update(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');		
		$id = $this->input->post("id_bantuan_bbn_luar");		
		$da['no_faktur'] 		= $this->input->post("no_faktur");		
		$da['tgl_faktur'] 		= $this->input->post("tgl_faktur");		
		$da['pemohon'] 		= $this->input->post("pemohon");		
		$da['no_mesin'] 		= $this->input->post("no_mesin");		
		$da['no_rangka'] 		= $this->input->post("no_rangka");		
		$da['id_warna'] 		= $this->input->post("id_warna");		
		$da['id_tipe_kendaraan'] 		= $this->input->post("id_tipe_kendaraan");		
		$da['no_ktp'] 		= $this->input->post("no_ktp");		
		$da['no_kk'] 		= $this->input->post("no_kk");		
		$da['no_npwp'] 		= $this->input->post("no_npwp");	
		$da['no_tdp'] 		= $this->input->post("no_tdp");		
		$da['nama_konsumen'] 		= $this->input->post("nama_konsumen");		
		$da['no_telp'] 		= $this->input->post("no_telp");	
		$da['no_hp'] 		= $this->input->post("no_hp");		
		$da['nama_gadis_ibu'] 		= $this->input->post("nama_gadis_ibu");		
		$da['tgl_lahir_ibu'] 		= $this->input->post("tgl_lahir_ibu");		
		$da['tempat_lahir'] 		= $this->input->post("tempat_lahir");		
		$da['tgl_lahir'] 		= $this->input->post("tgl_lahir");		
		$da['id_pekerjaan'] 		= $this->input->post("pekerjaan");		
		$da['pemenang'] 		= $this->input->post("pemenang");		
		$da['tagih_ke'] 		= $this->input->post("tagih_ke");		
		$da['pemenang_dari'] 		= $this->input->post("pemenang_dari");		
		$da['biaya_adm'] 		= $this->input->post("biaya_adm");		
		$da['biaya_bbn'] 		= $this->input->post("biaya_bbn");	
		$da['biaya_bbn_bj'] 		= $this->input->post("biaya_bbn_bj");	
		$da['tahun_produksi'] 		= $this->input->post("tahun_produksi");	
		$da['total'] 		= $this->input->post("total");		
		$da['id_kelurahan'] 		= $this->input->post("id_kelurahan");		
		$da['alamat'] 		= $this->input->post("alamat");				
		$da['updated_at'] 	= $waktu;
		$da['updated_by'] 	= $login_id;
		$this->m_admin->update("tr_bantuan_bbn_luar",$da,"id_bantuan_bbn_luar",$id);								
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/bantuan_bbn_d'>";				
	}
	public function cari_id_bbn(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
				
		$pr_num = $this->db->query("SELECT * FROM tr_monout_piutang_bbn where LEFT(no_rekap ,4) = '$th'  ORDER BY no_rekap DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_rekap)-3;
			$id 	= substr($row->no_rekap,11,5)+1;			
			$isi 	= sprintf("%'.05d",$id);		
			$kode = $th.$bln."/PIB/".$isi;
		}else{
			$kode = $th.$bln."/PIB/00001";
		}						
		return $kode;
	}
	public function cetak(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$no_bastd 			= $this->input->get('id');			

		$data['status_faktur'] = "proses";		
		$data['updated_at']		= $waktu;		
		$data['tgl_cetak']		= date('Y-m-d');		
		$data['updated_by']		= $login_id;			
		$this->m_admin->update("tr_bantuan_bbn_d",$data,"no_bastd",$no_bastd);								
		
		$sql = $this->db->query("SELECT SUM(biaya_bbn) AS jum FROM tr_bantuan_bbn_d_detail WHERE no_bastd = '$no_bastd'")->row();
		$dr['no_rekap'] 	= $this->cari_id_bbn();
		$dr['tgl_rekap'] 	= $tgl;
		$dr['no_bastd'] 	= $no_bastd;
		$dr['total'] 			= $sql->jum;		
		$dr['status_mon']	= "input";		
		$cek = $this->m_admin->getByID("tr_monout_piutang_bbn","no_bastd",$no_bastd);
		if($cek->num_rows() > 0){
			$f = $cek->row();
			$dr['updated_at'] 					= $waktu;
			$dr['updated_by'] 					= $login_id;
			$this->m_admin->update("tr_monout_piutang_bbn",$dr,"no_bastd",$f->no_bastd);
		}else{
			$dr['created_at'] 					= $waktu;
			$dr['created_by'] 					= $login_id;
			$this->m_admin->insert("tr_monout_piutang_bbn",$dr);
		}

		$get_stnk 	= $this->db->query("SELECT *
			 	FROM tr_bantuan_bbn_d INNER JOIN ms_dealer ON tr_bantuan_bbn_d.id_dealer = ms_dealer.id_dealer		 		
		 		WHERE tr_bantuan_bbn_d.no_bastd = '$no_bastd'")->row();        
		
		$s = $this->db->query("SELECT COUNT(no_mesin) AS qty FROM tr_bantuan_bbn_d_detail WHERE no_bastd = '$no_bastd'")->row();          
    if(isset($s->qty)){
      $jum = $s->qty;
    }else{
      $jum = 0;
    }

		$pdf = new FPDF('p','mm','A4');
    $pdf->AddPage();
       // head	  
	  $pdf->SetFont('TIMES','',10);
	  $pdf->Cell(50, 5, 'Jambi, '.date("d-m-Y", strtotime($get_stnk->tgl_bastd)).'', 0, 1, 'L');
	  $pdf->Cell(50, 5, 'Kepada Yth,', 0, 1, 'L');
	  $pdf->Cell(50, 5, 'PT. SINAR SENTOSA PRIMATAMA', 0, 1, 'L');
	  $pdf->Cell(50, 5, 'Jl.Kolonel Abunjani No.09 Jambi', 0, 1, 'L');
	  $pdf->Line(11, 31, 200, 31);
	   	  

	  $pdf->SetFont('TIMES','',10);
	  $pdf->Cell(1,2,'',0,1);
	  $pdf->Cell(30, 5, 'Nomor', 0, 0);	  
	  $pdf->Cell(70, 5, ': '.$no_bastd.'', 0, 1);	  

	  $pdf->Cell(30, 5, 'Perihal ', 0, 0);	  	  
	  $pdf->Cell(70, 5, ': Map Berkas untuk BBN', 0, 1);	  	  

	  $pdf->Cell(30,5, 'Dengan Hormat ', 0, 1);	  
	  $pdf->MultiCell(190,5, 'Bersama dengan surat ini kami dari '.$get_stnk->nama_dealer.' mengirimkan map untuk proses BBN sebanyak '.$jum.' unit dengan perincian sebagai berikut  :', 0, 1);	  
	  
	  $pdf->Cell(2,3,'',5,10);	  
	  $pdf->SetFont('TIMES','',12);
	   // buat tabel disini
	  $pdf->SetFont('TIMES','B',10);
	   
	   // kasi jarak
	  $pdf->Cell(2,5,'',5,10);	  
	   
	  $pdf->Cell(10, 5, 'No', 1, 0);
	  $pdf->Cell(70, 5, 'Nama', 1, 0);
	  $pdf->Cell(28, 5, 'No Mesin', 1, 0);
	  $pdf->Cell(53, 5, 'Kode Tipe', 1, 0);
	  $pdf->Cell(28, 5, 'Biaya BBN (Rp)', 1, 1);	  

	  $pdf->SetFont('times','',10);
	  $get_nosin 	= $this->db->query("SELECT * FROM tr_bantuan_bbn_d_detail INNER JOIN tr_scan_barcode ON tr_bantuan_bbn_d_detail.no_mesin=tr_scan_barcode.no_mesin 
	  		WHERE tr_bantuan_bbn_d_detail.no_bastd = '$no_bastd' ORDER BY tr_scan_barcode.tipe_motor ASC");
	  $i=1;$to=0;	  
	  foreach ($get_nosin->result() as $r)
	  {
	  	$cek_pik = $this->db->query("SELECT tr_bantuan_bbn_d_detail.*,tr_scan_barcode.no_rangka,tr_scan_barcode.tipe_motor,tr_spk.tipe_customer,tr_spk.id_tipe_kendaraan
			 	FROM tr_bantuan_bbn_d_detail INNER JOIN tr_scan_barcode ON tr_bantuan_bbn_d_detail.no_mesin = tr_scan_barcode.no_mesin		 					 	
			 	LEFT JOIN tr_spk ON tr_bantuan_bbn_d_detail.no_spk = tr_spk.no_spk

		 		WHERE tr_bantuan_bbn_d_detail.no_mesin = '$r->no_mesin' ORDER BY tr_spk.id_tipe_kendaraan ASC")->row();
	  		$tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan ='$cek_pik->id_tipe_kendaraan' ");
	  		if ($tipe->num_rows() > 0) {
	  			$tipe = $tipe->row();
	  		}
	  	$cek_harga = $this->m_admin->getByID("tr_sales_order","no_mesin",$cek_pik->no_mesin)->row();	  	
	  	$harga_m = $cek_pik->biaya_bbn;

	    $pdf->Cell(10, 5, $i, 1, 0);
	    $pdf->Cell(70, 5, $r->nama_konsumen, 1, 0);
	    $pdf->Cell(28, 5, $cek_pik->no_mesin, 1, 0);
	    $pdf->Cell(53, 5, $tipe->id_tipe_kendaraan.' | '.strip_tags($tipe->deskripsi_ahm), 1, 0);    	    
	    $pdf->Cell(28, 5, number_format($cek_pik->biaya_bbn, 0, ',', '.'), 1, 1,'R');	    
	  	$i++; 	   		    
	  	$to = $to + $harga_m;
	  }
	  	$pdf->Cell(10, 5, '', 1, 0);
	    $pdf->Cell(70, 5, '', 1, 0);
	    $pdf->Cell(28, 5, '', 1, 0);
	    $pdf->Cell(53, 5, 'Total Biaya BBN', 1, 0);    	    
	    $pdf->Cell(28, 5, number_format($to, 0, ',', '.'), 1, 1,'R');	
	   
	  $pdf->Cell(9,3,'',5,10);	  
	  $pdf->SetFont('TIMES','',10);	  
	  $pdf->Cell(10, 5, '', 0, 1);
	  $pdf->Cell(10, 15, '', 0, 0);
	  $pdf->Cell(30, 5, 'Pembayaran Biaya BBN tersebut di atas telah kami transfer ke rekening :', 0, 1,'L');	  
	  
	  $pdf->Cell(30, 5, 'Atas Nama ', 0, 0);	  	  
	  $pdf->Cell(70, 5, ': PT. Sinar Sentosa Primatama', 0, 1);	  	  
	  $pdf->Cell(30, 5, 'No. Rekening ', 0, 0);	  	  
	  $pdf->Cell(70, 5, ': ', 0, 1);	  	  
	  $pdf->Cell(30, 5, 'Nama Bank ', 0, 0);	  	  
	  $pdf->Cell(70, 5, ': ', 0, 1);	  	  
	  $pdf->Cell(30, 5, 'Tanggal Transfer ', 0, 0);	  	  
	  $pdf->Cell(70, 5, ': ', 0, 1);	  	  

	  $pdf->MultiCell(190,5, 'Demikian surat pengantar ini kami buat untuk pemrosesan BBN. Atas perhatian dan kerjasamanya kami ucapkan terima kasih', 0, 1);	
	  $pdf->Cell(50, 5, '', 0, 1,'C');	   
	  $pdf->Cell(50, 5, 'Dibuat :', 0, 0,'C');	   
	  $pdf->Cell(50, 5, 'Diketahui:', 0, 1,'C');	  	  
	  $pdf->Cell(10, 8, '', 0, 0);	  	  
	  $pdf->Cell(10, 10, '', 0, 1);
	  $pdf->Cell(10, 5, '', 0, 1);	  
	  $pdf->SetFont('TIMES','',8);	  
	  $pdf->Cell(10, 3, 'Catatan :', 0, 1,'L');
	  $pdf->Cell(10, 3, '1. Pengisian daftar map harus diurutkan sesuai Tipe Motor', 0, 1,'L');
	  $pdf->Cell(10, 3, '2. Ujung kanan map harus dibuat nomor sesuai dengan nama dalam surat', 0, 1,'L');
	  $pdf->Cell(10, 3, '3. Map yang dikirim harus telah lengkap sesuai dengan persyaratan yang berlaku', 0, 1,'L');
	  $pdf->Cell(10, 3, '4. Fotocopy bukti transfer harus dilampirkan', 0, 1,'L');
	  
	  $pdf->Cell(70, 5, '=======================================================================================================================', 0, 1);	  	  
	  $pdf->Cell(50, 5, '', 0, 1,'C');	   
	  
	  $pdf->SetFont('TIMES','',10);
	  $pdf->Cell(195, 1, 'Map telah diterima oleh pihak PT. Sinar Sentosa Primatama', 0, 1, 'C');	
	  $pdf->Cell(50, 5, '', 0, 1,'C');	   
		  $pdf->SetX(7); 
	  $pdf->Cell(85, 5, '1. Bagian Keuangan', 0, 0,'L');	

	  $pdf->Cell(50, 5, '2. Bagian Faktur', 0, 1,'L');	  	  
	  $pdf->Cell(15, 5, 'Nama', 0, 0);	  	  
	  $pdf->Cell(70, 5, ': ______________________', 0, 0);
	 $pdf->Cell(15, 5, 'Nama', 0, 0);	  	  
	  $pdf->Cell(60, 5, ': ______________________', 0, 1);

	  $pdf->Cell(15, 5, 'Tanggal', 0, 0);	  	  
	  $pdf->Cell(70, 5, ': _________________Jam :________WIB', 0, 0);
	 $pdf->Cell(15, 5, 'Tanggal', 0, 0);	  	  
	  $pdf->Cell(60, 5, ': _________________Jam :_________WIB', 0, 1);
	  $pdf->Cell(50, 5, '', 0, 1,'C');	   
	  $pdf->Cell(50, 5, '', 0, 1,'C');	   
	 
	   $pdf->Cell(15, 5, 'TTD', 0, 0);	  	  
	  $pdf->Cell(70, 5, ': ______________________', 0, 0);
	 $pdf->Cell(15, 5, 'TTD', 0, 0);	  	  
	  $pdf->Cell(60, 5, ': ______________________', 0, 1);

	  $pdf->Output(); 
	}
}