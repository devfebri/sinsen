<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entry_stnk extends CI_Controller {

    var $tables =   "tr_proses_bbn";	
		var $folder =   "h1";
		var $page		=		"entry_stnk";
    var $pk     =   "no_invoice";
    var $title  =   "Entry STNK, BPKB & Plat";

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
		$this->load->library('mpdf_l');


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

	public function index_cari()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view";
		$where ='';
		if (isset($_GET['tgl_mohon_samsat1'])) {
			$tgl_mohon_samsat1 = $data['tgl_mohon_samsat1'] = $this->input->get('tgl_mohon_samsat1');
			$tgl_mohon_samsat2 = $data['tgl_mohon_samsat2'] = $this->input->get('tgl_mohon_samsat2');
			if ($tgl_mohon_samsat1!='') {
				$where .="AND tr_pengajuan_bbn_detail.tgl_mohon_samsat BETWEEN '$tgl_mohon_samsat1' AND '$tgl_mohon_samsat2'";
			}
		}
		if(isset($_GET['no_mesin'])){
			$no_mesin = $data['no_mesin'] = $this->input->get("no_mesin");
			if($no_mesin != ''){
				$where .= "AND tr_pengajuan_bbn_detail.no_mesin LIKE '%$no_mesin%'";
			}
		}
		$data['dt_bbn'] = $this->db->query("SELECT tr_konfirmasi_map_detail.no_mesin,tr_proses_bbn_detail.notice_pajak,tr_pengajuan_bbn_detail.nama_konsumen,tr_pengajuan_bbn_detail.no_rangka,tr_pengajuan_bbn_detail.tgl_mohon_samsat,
				ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_dealer.id_dealer,ms_dealer.nama_dealer,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna
			FROM tr_konfirmasi_map_detail
				LEFT JOIN tr_proses_bbn_detail ON tr_konfirmasi_map_detail.no_mesin = tr_proses_bbn_detail.no_mesin
				INNER JOIN tr_pengajuan_bbn_detail ON tr_konfirmasi_map_detail.no_mesin= tr_pengajuan_bbn_detail.no_mesin 
				INNER JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				INNER JOIN ms_warna ON tr_pengajuan_bbn_detail.id_warna = ms_warna.id_warna
				LEFT JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn.no_bastd=tr_pengajuan_bbn_detail.no_bastd
				LEFT JOIN ms_dealer ON ms_dealer.id_dealer=tr_pengajuan_bbn.id_dealer
				LEFT JOIN tr_entry_stnk ON tr_konfirmasi_map_detail.no_mesin = tr_entry_stnk.no_mesin
				WHERE (tr_proses_bbn_detail.status_stnk = '' OR tr_proses_bbn_detail.status_bpkb = '' OR tr_proses_bbn_detail.status_plat = ''
				OR tr_proses_bbn_detail.status_stnk IS NULL OR tr_proses_bbn_detail.status_bpkb IS NULL OR tr_proses_bbn_detail.status_plat IS NULL
				OR tr_entry_stnk.no_stnk = '' OR tr_entry_stnk.no_bpkb = '' OR tr_entry_stnk.no_plat = '' OR tr_entry_stnk.no_pol = '')
				$where
				");		
		$this->template($data);			
	}
	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view_new";		
		$this->template($data);			
	}
	public function history()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "History ".$this->title;															
		$data['set']		= "history";		
		$this->template($data);			
	}
	public function normalisasi()
	{				
		$tgl 				= gmdate("Y-m-d", time()+60*60*7);
		$sql = $this->db->query("SELECT * FROM tr_entry_stnk ORDER BY id_entry_stnk ASC LIMIT 0,5");
		foreach ($sql->result() as $isi) {
			$no_mesin = $isi->no_mesin;
			if($isi->no_stnk != ""){
				$cek_stnk = $this->m_admin->getByID("tr_kirim_stnk_detail","no_mesin",$no_mesin);
				if($cek_stnk->num_rows() > 0){
					$no_stnk = $cek_stnk->row()->no_stnk;
					$this->db->query("UPDATE tr_entry_stnk SET no_stnk = '$no_stnk',print_stnk='printable',tgl_stnk='$tgl' WHERE no_mesin = '$no_mesin'");
				}
			}
			if($isi->no_plat == ""){
				$cek_plat = $this->m_admin->getByID("tr_kirim_plat_detail","no_mesin",$no_mesin);
				if($cek_plat->num_rows() > 0){
					$no_plat = $cek_plat->row()->no_plat;
					$this->db->query("UPDATE tr_entry_stnk SET no_pol = '$no_plat', no_plat = '$no_plat',print_plat='printable',tgl_plat='$tgl' WHERE no_mesin = '$no_mesin'");					
				}
			}
			if($isi->no_bpkb == ""){
				$cek_bpkb = $this->m_admin->getByID("tr_kirim_bpkb_detail","no_mesin",$no_mesin);
				if($cek_bpkb->num_rows() > 0){
					$no_bpkb = $cek_bpkb->row()->no_bpkb;
					$this->db->query("UPDATE tr_entry_stnk SET no_bpkb = '$no_bpkb',print_bpkb='printable',tgl_bpkb='$tgl' WHERE no_mesin = '$no_mesin'");
				}
			}

		}
	}
	public function cetak_ulang()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Cetak Ulang ".$this->title;															
		$data['set']		= "cetak_ulang";		
		$this->template($data);			
	}
	public function generate()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Generate ".$this->title;															
		$data['set']	= "generate";
		$data['dt_nosin'] = $this->db->query("SELECT * FROM tr_entry_stnk WHERE (no_serah_terima='' or print_stnk='input' OR print_plat='input' OR print_bpkb='input')
		    ORDER BY id_entry_stnk DESC");
		$this->template($data);	
		
	}

	public function generateSave()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 			= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');								
		$save 			= $this->input->post('save');		
		$no_serah_terima = $this->cari_id();
	    
		$jum = $this->input->post("jum"); 
		if(count($this->input->post('cek_[]'))>0) {
			foreach ($this->input->post('cek_[]') as $no_mesin => $status_cek) {
				$nosin = $no_mesin;
				$cek = $this->m_admin->getByID("tr_entry_stnk","no_mesin",$nosin);
				if($cek->num_rows() > 0){
					if(is_null($cek->row()->no_serah_terima) OR $cek->row()->no_serah_terima==""){
					    $data['no_serah_terima'] = $no_serah_terima;  
					}else{
					    $no_serah_terima = $cek->row()->no_serah_terima;
					} 

					if($cek->num_rows() > 0){		
						$da['updated_at']      = $waktu;		
						$da['updated_by']      = $login_id;						
						$this->m_admin->update("tr_serah_terima",$da,"no_serah_terima",$no_serah_terima);
					}else{
						$da['no_serah_terima'] = $no_serah_terima;		
						$da['created_at']      = $waktu;		
						$da['created_by']      = $login_id;	
						$this->m_admin->insert("tr_serah_terima",$da);			
					}
					
					$_SESSION['pesan'] 	= "Data berhasil disimpan";
					$_SESSION['tipe'] 	= "success";
				
					if($save=="stnk" && $cek->row()->print_stnk == 'input'){
						$data['print_stnk'] = "printable";
						$link = "cetak_stnk";
						$this->m_admin->update("tr_entry_stnk",$data,"no_mesin",$nosin);
						echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/entry_stnk/".$link."'>";
					}elseif($save=="bpkb" && $cek->row()->print_bpkb == 'input'){
						$data['print_bpkb'] = "printable";
						$link = "cetak_bpkb";
						$this->m_admin->update("tr_entry_stnk",$data,"no_mesin",$nosin);
						echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/entry_stnk/".$link."'>";
					}elseif($save=="plat" && $cek->row()->print_plat == 'input'){
						$data['print_plat'] = "printable";
						$link = "cetak_plat";
						$this->m_admin->update("tr_entry_stnk",$data,"no_mesin",$nosin);
						echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/entry_stnk/".$link."'>";
					}else{
						if(count($this->input->post('cek_[]'))==1){
							$_SESSION['pesan'] 	= "Tidak ada data yang disimpan";
							$_SESSION['tipe'] 	= "danger";		
							echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/entry_stnk/generate'>";
						}
					}
				}else{
					$_SESSION['pesan'] 	= "Gagal! Terjadi kesalahan";
					$_SESSION['tipe'] 	= "danger";		
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/entry_stnk/generate'>";
				}
			}		
		}else{
				$_SESSION['pesan'] 	= "Silahkan pilih data terlebih dahulu!";
				$_SESSION['tipe'] 	= "danger";		
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/entry_stnk/generate'>";
		}
	}

    public function generateSave_bak()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');								
		$save 				= $this->input->post('save');		
		$no_serah_terima = $this->cari_id();
	    
		$jum = $this->input->post("jum");
		for ($i=1; $i <= $jum; $i++) { 
			if(isset($_POST["cek_".$i])){
				$nosin = $_POST["no_mesin_".$i];
				$cek = $this->m_admin->getByID("tr_entry_stnk","no_mesin",$nosin)->row()->no_serah_terima;
				if(is_null($cek) OR $cek==""){
				    $data['no_serah_terima'] = $no_serah_terima;  
				}else{
				    $no_serah_terima = $cek;
				} 
				
				if($save=="stnk"){
				    $data['print_stnk'] = "printable";
				    $link = "cetak_stnk";
				}elseif($save=="bpkb"){
				    $data['print_bpkb'] = "printable";
				    $link = "cetak_bpkb";
				}elseif($save=="plat"){
				    $data['print_plat'] = "printable";
				    $link = "cetak_plat";
				}
				$this->m_admin->update("tr_entry_stnk",$data,"no_mesin",$nosin);
				
				$da['no_serah_terima'] = $no_serah_terima;		
        			$da['created_at']      = $waktu;		
        			$da['created_by']      = $login_id;		
		
			    $ce = $this->db->query("SELECT * FROM tr_serah_terima WHERE no_serah_terima = '$no_serah_terima'");
    			if($ce->num_rows() > 0){						
    				$this->m_admin->update("tr_serah_terima",$da,"no_serah_terima",$no_serah_terima);
    			}else{
    				$this->m_admin->insert("tr_serah_terima",$da);			
    			}
    

				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/entry_stnk/".$link."'>";
			}			
		}
	}
	public function cetak()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Cetak ".$this->title;															
		$data['set']		= "cetak";		
		$this->template($data);			
	}		
	public function cetak_stnk(){		
		$waktu 			= gmdate("Y-m-d H:i:s", time()+60*60*7);
		$tgl 				= gmdate("Y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;				
		if(!isset($_GET['id'])){
		  $no_kirim_stnk 		= $this->cari_stnk();
		  $dt['no_kirim_stnk'] 	= $no_kirim_stnk;
		  $dt['tgl_kirim_stnk'] = $tgl;
		  $dt['status_stnk'] 		= "input";
		  $dt['created_by'] 		= $login_id;
		  $dt['created_at'] 		= $waktu;
			$this->m_admin->insert("tr_kirim_stnk",$dt);
			$query = "SELECT tr_entry_stnk.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_dealer.nama_dealer FROM tr_entry_stnk INNER JOIN ms_tipe_kendaraan ON tr_entry_stnk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
	  					INNER JOIN ms_warna ON tr_entry_stnk.id_warna = ms_warna.id_warna
	  					inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
		  				inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
		  				inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
	   					WHERE tr_entry_stnk.updated_by = '$login_id' AND tr_entry_stnk.print_stnk = 'printable'
	   					ORDER BY ms_dealer.nama_dealer,tr_entry_stnk.nama_konsumen ASC";
		}else{
			$no_mesin = $this->input->get("id");
			$cek_plat = $this->db->query("SELECT * FROM tr_kirim_stnk INNER JOIN tr_kirim_stnk_detail ON tr_kirim_stnk.no_kirim_stnk = tr_kirim_stnk_detail.no_kirim_stnk
			 WHERE tr_kirim_stnk_detail.no_mesin = '$no_mesin'");
			if($cek_plat->num_rows() > 0){
				$no_kirim_stnk = $cek_plat->row()->no_kirim_stnk;
				$cek_query = "WHERE tr_kirim_stnk.no_kirim_stnk = '$no_kirim_stnk'";
			}else{
				$no_kirim_stnk = "";
				$cek_query = "";
			}
			$query = "SELECT tr_entry_stnk.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_dealer.nama_dealer,tr_kirim_stnk_detail.no_stnk FROM tr_entry_stnk INNER JOIN ms_tipe_kendaraan ON tr_entry_stnk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
	  					INNER JOIN ms_warna ON tr_entry_stnk.id_warna = ms_warna.id_warna
	  					inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
		  				inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
		  				inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
		  				inner join tr_kirim_stnk_detail ON tr_faktur_stnk_detail.no_mesin = tr_kirim_stnk_detail.no_mesin
		  				inner join tr_kirim_stnk ON tr_kirim_stnk_detail.no_kirim_stnk = tr_kirim_stnk.no_kirim_stnk
	   					WHERE tr_kirim_stnk.no_kirim_stnk = '$no_kirim_stnk'
	   					ORDER BY ms_dealer.nama_dealer,tr_entry_stnk.nama_konsumen ASC";
		}
		$data['query'] = $this->db->query($query);
		$data['tgl'] = $tgl;
		$data['no_kirim_stnk'] = $no_kirim_stnk;
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
    $mpdf->charset_in='UTF-8';
    $mpdf->autoLangToFont = true;
  	$data['cetak'] = 'stnk';  	  
  	$html = $this->load->view('h1/cetak_stnk_file', $data, true);    
    $mpdf->WriteHTML($html);    
    $output = 'cetak_.pdf';
    $mpdf->Output("$output", 'I');	
	}
	public function cetak_stnk_old(){		
		$waktu    = gmdate("Y-m-d H:i:s", time()+60*60*7);
		$tgl      = gmdate("Y-m-d", time()+60*60*7);
		$login_id = $this->session->userdata('id_user');
		$tabel    = $this->tables;
		$pk       = $this->pk;	

	  $pdf = new FPDF('p','mm','A4');
		$pdf->AddPage();
       // head
		$pdf->SetFont('ARIAL','B',14);
		$pdf->Cell(190, 9, 'SERAH TERIMA STNK', 1, 1, 'C');
		// $tgl = date('d-m-Y', strtotime($tgl))	;
		if(!isset($_GET['id'])){
		  $no_kirim_stnk 				= $this->cari_stnk();
		  $dt['no_kirim_stnk'] 	= $no_kirim_stnk;
		  $dt['tgl_kirim_stnk'] = $tgl;
		  $dt['status_stnk'] 		= "input";
		  $dt['created_by'] 		= $login_id;
		  $dt['created_at'] 		= $waktu;
			$this->m_admin->insert("tr_kirim_stnk",$dt);
			$query = "SELECT tr_entry_stnk.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_dealer.nama_dealer FROM tr_entry_stnk INNER JOIN ms_tipe_kendaraan ON tr_entry_stnk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
	  					INNER JOIN ms_warna ON tr_entry_stnk.id_warna = ms_warna.id_warna
	  					inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
		  				inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
		  				inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
	   					WHERE tr_entry_stnk.updated_by = '$login_id' AND tr_entry_stnk.print_stnk = 'printable'";
		}else{
			$no_mesin = $this->input->get("id");
			$cek_plat = $this->db->query("SELECT * FROM tr_kirim_stnk INNER JOIN tr_kirim_stnk_detail ON tr_kirim_stnk.no_kirim_stnk = tr_kirim_stnk_detail.no_kirim_stnk
			 WHERE tr_kirim_stnk_detail.no_mesin = '$no_mesin'");
			if($cek_plat->num_rows() > 0){
				$no_kirim_stnk = $cek_plat->row()->no_kirim_stnk;
				$cek_query = "WHERE tr_kirim_bpkb.no_kirim_stnk = '$no_kirim_stnk'";
			}else{
				$no_kirim_stnk = "";
				$cek_query = "";
			}
			$query = "SELECT tr_entry_stnk.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_dealer.nama_dealer FROM tr_entry_stnk INNER JOIN ms_tipe_kendaraan ON tr_entry_stnk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
	  					INNER JOIN ms_warna ON tr_entry_stnk.id_warna = ms_warna.id_warna
	  					inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
		  				inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
		  				inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
		  				inner join tr_kirim_stnk_detail ON tr_faktur_stnk_detail.no_mesin = tr_kirim_stnk_detail.no_mesin
		  				inner join tr_kirim_stnk ON tr_kirim_stnk_detail.no_kirim_stnk = tr_kirim_stnk.no_kirim_stnk
	   					WHERE tr_kirim_stnk.no_kirim_stnk = '$no_kirim_stnk'";
		}
		$pdf->SetY(25);
		$pdf->SetFont('ARIAL','',11);
		$pdf->Cell(35,5, 'No Serah Terima : ',0,0,'L');
		$pdf->Cell(90,5, ': '.$no_kirim_stnk,0,1,'L');
		$pdf->Cell(35,5, 'Tanggal',0,0,'L');
		$pdf->Cell(90,5, ': '.$tgl,0,1,'L');
		$pdf->Cell(90,5, '',0,1,'L');
		$pdf->Cell(35,5, 'Kepada Yth',0,1,'L');
		$pdf->Cell(35,5, 'Kepala Bagian STNK',0,1,'L');
		$pdf->Cell(35,5, 'Di PT. Sinar Sentosa Primatama',0,1,'L');
		$pdf->Cell(90,5, '',0,1,'L');
		$pdf->Cell(35,5, 'Dengan Hormat',0,1,'L');
		$pdf->Cell(190,5, 'Dengan ini kami serah terimakan dokumen STNK sebagai berikut :',0,1,'L');
		$pdf->Cell(35,3, '',0,1,'L');
		$pdf->Cell(10,5, 'No.',1,0,'C');
		$pdf->Cell(45,5, 'Nama Dealer',1,0,'C');
		$pdf->Cell(55,5, 'Nama Konsumen',1,0,'C');
		$pdf->Cell(30,5, 'No. Mesin',1,0,'C');
		$pdf->Cell(25,5, 'No. Polisi',1,0,'C');
		$pdf->Cell(25,5, 'No. STNK',1,1,'C');

		$get_nosin 	= $this->db->query($query);
		  $i=1;	  
		 							
		  foreach ($get_nosin->result() as $r)
		  {

		  	$ds["no_kirim_stnk"]	= $no_kirim_stnk;
		  	$ds["no_stnk"] 				= $r->no_stnk;
				$ds["no_mesin"] 	= $nosin		= $r->no_mesin;	
				$ds["cetak"] 					= "ya";	

				
				if(!isset($_GET['id'])){
					$this->m_admin->insert("tr_kirim_stnk_detail",$ds);								
					$dw["print_stnk"] 		= "ya";	
					$this->db->query("UPDATE tr_entry_stnk SET print_stnk = 'ya' WHERE no_mesin = '$r->no_mesin'");
				}
				$pdf->Cell(10,5, $i,1,0,'C');
		$pdf->Cell(45,5, $r->nama_dealer,1,0,'C');
		$pdf->Cell(55,5, $r->nama_konsumen,1,0,'C');
		$pdf->Cell(30,5, $r->no_mesin,1,0,'C');
		$pdf->Cell(25,5, $r->no_plat,1,0,'C');
		$pdf->Cell(25,5, $r->no_stnk,1,1,'C');    
			  	$i++; 	   		    
			  }	   

		$pdf->Cell(35,3, '',0,1,'L');
		$pdf->Cell(190,5, 'Demikian kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.',0,1,'L');
		$pdf->Cell(35,3, '',0,1,'L');
		$pdf->Cell(190,5, '=====================================================================================',0,1,'C');
		$pdf->Cell(35,3, '',0,1,'L');
		$pdf->SetFont('ARIAL','B',11);
		$pdf->Cell(190,5, 'Dokumen telah diterima oleh Pihak PT.Sinar Sentosa Primatama',0,1,'C');
		$pdf->Cell(35,3, '',0,1,'L');
		$pdf->SetFont('ARIAL','',11);
		$pdf->Cell(95,5, 'Yang Menyerahkan',0,0,'L');
		$pdf->Cell(95,5, 'Yang Menerima',0,1,'L');
		$pdf->Cell(30,5, 'Nama',0,0,'L');
		$pdf->Cell(65,5, ': _______________________',0,0,'L');
		$pdf->Cell(30,5, 'Nama',0,0,'L');
		$pdf->Cell(65,5, ': _______________________',0,1,'L');
		$pdf->Cell(30,5, 'Tanggal',0,0,'L');
		$pdf->Cell(65,5, ': _______________________',0,0,'L');
		$pdf->Cell(30,5, 'Tanggal',0,0,'L');
		$pdf->Cell(65,5, ': _______________________',0,1,'L');
		$pdf->Cell(30,5, 'Jam',0,0,'L');
		$pdf->Cell(65,5, ': ____________________WIB',0,0,'L');
		$pdf->Cell(30,5, 'Jam',0,0,'L');
		$pdf->Cell(65,5, ': ____________________WIB',0,1,'L');
		$pdf->Cell(65,20, '',0,1,'L');
		$pdf->Cell(30,5, 'TTD',0,0,'L');
		$pdf->Cell(65,5, ': _______________________',0,0,'L');
		$pdf->Cell(30,5, 'TTD',0,0,'L');
		$pdf->Cell(65,5, ': _______________________',0,1,'L');

	  	$pdf->Output(); 
		
	}
	public function cetak_plat(){		
		$waktu 			= gmdate("Y-m-d H:i:s", time()+60*60*7);
		$tgl 				= gmdate("Y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;				
		if(!isset($_GET['id'])){
		 	$no_kirim_plat 				= $this->cari_plat();
		  $dt['no_kirim_plat'] 	= $no_kirim_plat;
		  $dt['tgl_kirim_plat'] = $tgl;
		  $dt['status_plat'] 		= "input";
		  $dt['created_by'] 		= $login_id;
		  $dt['created_at'] 		= $waktu;
			$this->m_admin->insert("tr_kirim_plat",$dt);	
			$tgl = date('d-m-Y', strtotime($tgl));
			$query = "SELECT tr_entry_stnk.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_dealer.nama_dealer FROM tr_entry_stnk INNER JOIN ms_tipe_kendaraan ON tr_entry_stnk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
	  					INNER JOIN ms_warna ON tr_entry_stnk.id_warna = ms_warna.id_warna
	  					inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
		  				inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
		  				inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
	   					WHERE tr_entry_stnk.updated_by = '$login_id' AND tr_entry_stnk.print_plat = 'printable'
	   					ORDER BY ms_dealer.nama_dealer,tr_entry_stnk.nama_konsumen ASC";
		}else{
			$no_mesin = $this->input->get("id");
			$cek_plat = $this->db->query("SELECT * FROM tr_kirim_plat INNER JOIN tr_kirim_plat_detail ON tr_kirim_plat.no_kirim_plat = tr_kirim_plat_detail.no_kirim_plat
			 WHERE tr_kirim_plat_detail.no_mesin = '$no_mesin'");
			if($cek_plat->num_rows() > 0){
				$no_kirim_plat = $cek_plat->row()->no_kirim_plat;
				$cek_query = "WHERE tr_kirim_plat_detail.no_kirim_plat = '$no_kirim_plat'";
			}else{
				$no_kirim_plat = "";
				$cek_query = "";
			}
			$query = "SELECT tr_entry_stnk.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_dealer.nama_dealer,tr_kirim_plat_detail.no_plat FROM tr_entry_stnk INNER JOIN ms_tipe_kendaraan ON tr_entry_stnk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
	  					INNER JOIN ms_warna ON tr_entry_stnk.id_warna = ms_warna.id_warna
	  					inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
		  				inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
		  				inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
		  				inner join tr_kirim_plat_detail ON tr_faktur_stnk_detail.no_mesin = tr_kirim_plat_detail.no_mesin
		  				inner join tr_kirim_plat ON tr_kirim_plat_detail.no_kirim_plat = tr_kirim_plat.no_kirim_plat $cek_query
	   					ORDER BY ms_dealer.nama_dealer,tr_entry_stnk.nama_konsumen ASC";
		}
		$data['query'] = $this->db->query($query);
		$data['tgl'] = $tgl;
		$data['no_kirim_plat'] = $no_kirim_plat;
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
    $mpdf->charset_in='UTF-8';
    $mpdf->autoLangToFont = true;
  	$data['cetak'] = 'bpkb';  	  
  	$html = $this->load->view('h1/cetak_plat_file', $data, true);    
    $mpdf->WriteHTML($html);    
    $output = 'cetak_.pdf';
    $mpdf->Output("$output", 'I');	
	}
	public function cetak_plat_old(){		
		$waktu 			= gmdate("Y-m-d H:i:s", time()+60*60*7);
		$tgl 				= gmdate("Y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;		

	  $pdf = new FPDF('p','mm','A4');
		$pdf->AddPage();
       // head
		$pdf->SetFont('ARIAL','B',14);
		$pdf->Cell(190, 9, 'SERAH TERIMA PLAT NOMOR POLISI KENDARAAN', 1, 1, 'C');

		$pdf->SetY(25);
		$pdf->SetFont('ARIAL','',11);
		$pdf->Cell(35,5, 'No Serah Terima',0,0,'L');
		if(!isset($_GET['id'])){
		 	$no_kirim_plat 				= $this->cari_plat();
		  $dt['no_kirim_plat'] 	= $no_kirim_plat;
		  $dt['tgl_kirim_plat'] = $tgl;
		  $dt['status_plat'] 		= "input";
		  $dt['created_by'] 		= $login_id;
		  $dt['created_at'] 		= $waktu;
			$this->m_admin->insert("tr_kirim_plat",$dt);	
			$tgl = date('d-m-Y', strtotime($tgl));
			$query = "SELECT tr_entry_stnk.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_dealer.nama_dealer FROM tr_entry_stnk INNER JOIN ms_tipe_kendaraan ON tr_entry_stnk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
	  					INNER JOIN ms_warna ON tr_entry_stnk.id_warna = ms_warna.id_warna
	  					inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
		  				inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
		  				inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
	   					WHERE tr_entry_stnk.updated_by = '$login_id' AND tr_entry_stnk.print_plat = 'printable'";
		}else{
			$no_mesin = $this->input->get("id");
			$cek_plat = $this->db->query("SELECT * FROM tr_kirim_plat INNER JOIN tr_kirim_plat_detail ON tr_kirim_plat.no_kirim_plat = tr_kirim_plat_detail.no_kirim_plat
			 WHERE tr_kirim_plat_detail.no_mesin = '$no_mesin'");
			if($cek_plat->num_rows() > 0){
				$no_kirim_plat = $cek_plat->row()->no_kirim_plat;
				$cek_query = "WHERE tr_kirim_plat_detail.no_kirim_plat = '$no_kirim_plat'";
			}else{
				$no_kirim_plat = "";
				$cek_query = "";
			}
			$query = "SELECT tr_entry_stnk.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_dealer.nama_dealer FROM tr_entry_stnk INNER JOIN ms_tipe_kendaraan ON tr_entry_stnk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
	  					INNER JOIN ms_warna ON tr_entry_stnk.id_warna = ms_warna.id_warna
	  					inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
		  				inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
		  				inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
		  				inner join tr_kirim_plat_detail ON tr_faktur_stnk_detail.no_mesin = tr_kirim_plat_detail.no_mesin
		  				inner join tr_kirim_plat ON tr_kirim_plat_detail.no_kirim_plat = tr_kirim_plat.no_kirim_plat $cek_query";
		}
		$pdf->Cell(90,5, ': '.$no_kirim_plat,0,1,'L');
		$pdf->Cell(35,5, 'Tanggal',0,0,'L');
		$pdf->Cell(90,5, ': '.$tgl,0,1,'L');
		$pdf->Cell(90,5, '',0,1,'L');
		$pdf->Cell(35,5, 'Kepada Yth',0,1,'L');
		$pdf->Cell(35,5, 'Kepala Bagian STNK',0,1,'L');
		$pdf->Cell(35,5, 'Di PT. Sinar Sentosa Primatama',0,1,'L');
		$pdf->Cell(90,5, '',0,1,'L');
		$pdf->Cell(35,5, 'Dengan Hormat',0,1,'L');
		$pdf->Cell(190,5, 'Dengan ini kami serah terimakan dokumen Plat Nomor Polisi Kendaraan sebagai berikut :',0,1,'L');
		$pdf->Cell(35,3, '',0,1,'L');
		$pdf->Cell(11,5, 'No.',1,0,'C');
		$pdf->Cell(51,5, 'Nama Dealer',1,0,'C');
		$pdf->Cell(61,5, 'Nama Konsumen',1,0,'C');
		$pdf->Cell(36,5, 'No. Mesin',1,0,'C');
		$pdf->Cell(31,5, 'No. Polisi',1,1,'C');
		$no = 1;
	       $get_nosin 	= $this->db->query($query);
		  $i=1;	  
		 							
		  foreach ($get_nosin->result() as $r)
		  {

		  	$ds["no_kirim_plat"]	= $no_kirim_plat;
		  	$ds["no_plat"] 				= $r->no_plat;
				$ds["no_mesin"] 			= $r->no_mesin;	
				$ds["cetak"] 					= "ya";	
				if(!isset($_GET['id'])){
					$this->m_admin->insert("tr_kirim_plat_detail",$ds);								
					$dw["print_stnk"] 		= "ya";	
					$this->db->query("UPDATE tr_entry_stnk SET print_plat = 'ya' WHERE no_mesin = '$r->no_mesin'");
				}
			$pdf->Cell(11,5, $i,1,0,'C');
			$pdf->Cell(51,5, $r->nama_dealer,1,0,'C');
			$pdf->Cell(61,5, $r->nama_konsumen,1,0,'C');
			$pdf->Cell(36,5, $r->no_mesin,1,0,'C');
			$pdf->Cell(31,5, $r->no_plat,1,1,'C');	    
		  	$i++; 	   		    
		  }	   
		$pdf->Cell(35,3, '',0,1,'L');
		$pdf->Cell(190,5, 'Demikian kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.',0,1,'L');
		$pdf->Cell(35,3, '',0,1,'L');
		$pdf->Cell(190,5, '=====================================================================================',0,1,'C');
		$pdf->Cell(35,3, '',0,1,'L');
		$pdf->SetFont('ARIAL','B',11);
		$pdf->Cell(190,5, 'Plat Nomor Polisi Kendaraan telah diterima oleh Pihak PT.Sinar Sentosa Primatama',0,1,'C');
		$pdf->Cell(35,3, '',0,1,'L');
		$pdf->SetFont('ARIAL','',11);
		$pdf->Cell(95,5, 'Yang Menyerahkan',0,0,'L');
		$pdf->Cell(95,5, 'Yang Menerima',0,1,'L');
		$pdf->Cell(30,5, 'Nama',0,0,'L');
		$pdf->Cell(65,5, ': _______________________',0,0,'L');
		$pdf->Cell(30,5, 'Nama',0,0,'L');
		$pdf->Cell(65,5, ': _______________________',0,1,'L');
		$pdf->Cell(30,5, 'Tanggal',0,0,'L');
		$pdf->Cell(65,5, ': _______________________',0,0,'L');
		$pdf->Cell(30,5, 'Tanggal',0,0,'L');
		$pdf->Cell(65,5, ': _______________________',0,1,'L');
		$pdf->Cell(30,5, 'Jam',0,0,'L');
		$pdf->Cell(65,5, ': ____________________WIB',0,0,'L');
		$pdf->Cell(30,5, 'Jam',0,0,'L');
		$pdf->Cell(65,5, ': ____________________WIB',0,1,'L');
		$pdf->Cell(65,20, '',0,1,'L');
		$pdf->Cell(30,5, 'TTD',0,0,'L');
		$pdf->Cell(65,5, ': _______________________',0,0,'L');
		$pdf->Cell(30,5, 'TTD',0,0,'L');
		$pdf->Cell(65,5, ': _______________________',0,1,'L');

	  	$pdf->Output(); 
		
	}
	public function cetak_bpkb(){		
		$waktu 			= gmdate("Y-m-d H:i:s", time()+60*60*7);
		$tgl 				= gmdate("Y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;				
		if(!isset($_GET['id'])){
			$no_kirim_bpkb 				= $this->cari_bpkb();
		  $dt['no_kirim_bpkb'] 	= $no_kirim_bpkb;
		  $dt['tgl_kirim_bpkb'] = $tgl;
		  $dt['status_bpkb'] 		= "input";
		  $dt['created_by'] 		= $login_id;
		  $dt['created_at'] 		= $waktu;		  
			$this->m_admin->insert("tr_kirim_bpkb",$dt);

			$query = "SELECT tr_entry_stnk.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_dealer.nama_dealer FROM tr_entry_stnk INNER JOIN ms_tipe_kendaraan ON tr_entry_stnk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
	  					INNER JOIN ms_warna ON tr_entry_stnk.id_warna = ms_warna.id_warna
	  					inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
		  				inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
		  				inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
	   					WHERE tr_entry_stnk.updated_by = '$login_id' AND tr_entry_stnk.print_bpkb = 'printable'
	   					ORDER BY ms_dealer.nama_dealer,tr_entry_stnk.nama_konsumen ASC";
		}else{
			$no_mesin = $this->input->get("id");
			$cek_bpkb = $this->db->query("SELECT * FROM tr_kirim_bpkb INNER JOIN tr_kirim_bpkb_detail ON tr_kirim_bpkb.no_kirim_bpkb = tr_kirim_bpkb_detail.no_kirim_bpkb
			 WHERE tr_kirim_bpkb_detail.no_mesin = '$no_mesin'");
			if($cek_bpkb->num_rows() > 0){
				$no_kirim_bpkb = $cek_bpkb->row()->no_kirim_bpkb;
				$cek_query = "WHERE tr_kirim_bpkb.no_kirim_bpkb = '$no_kirim_bpkb'";		
			}else{
				$no_kirim_bpkb = "";
				$cek_query = "";
			} 
			$query = "SELECT tr_entry_stnk.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_dealer.nama_dealer FROM tr_entry_stnk INNER JOIN ms_tipe_kendaraan ON tr_entry_stnk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
	  					INNER JOIN ms_warna ON tr_entry_stnk.id_warna = ms_warna.id_warna
	  					inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
		  				inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
		  				inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
		  				inner join tr_kirim_bpkb_detail ON tr_faktur_stnk_detail.no_mesin = tr_kirim_bpkb_detail.no_mesin
		  				inner join tr_kirim_bpkb ON tr_kirim_bpkb_detail.no_kirim_bpkb = tr_kirim_bpkb.no_kirim_bpkb $cek_query
	   					ORDER BY ms_dealer.nama_dealer,tr_entry_stnk.nama_konsumen ASC";	   					
		}
		$data['query'] = $this->db->query($query);
		$data['tgl'] = $tgl;
		$data['no_kirim_bpkb'] = $no_kirim_bpkb;
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
    $mpdf->charset_in='UTF-8';
    $mpdf->autoLangToFont = true;
  	$data['cetak'] = 'bpkb';  	  
  	$html = $this->load->view('h1/cetak_bpkb_file', $data, true);    
    $mpdf->WriteHTML($html);    
    $output = 'cetak_.pdf';
    $mpdf->Output("$output", 'I');	
	}
	public function cetak_bpkb_old(){		
		$waktu 			= gmdate("Y-m-d H:i:s", time()+60*60*7);
		$tgl 				= gmdate("Y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;		


	  $pdf = new FPDF('p','mm','A4');
		$pdf->AddPage();
       // head
		$pdf->SetFont('ARIAL','B',14);
		$pdf->Cell(190, 9, 'SERAH TERIMA BPKB', 1, 1, 'C');
		
		if(!isset($_GET['id'])){
			$no_kirim_bpkb 				= $this->cari_bpkb();
		  $dt['no_kirim_bpkb'] 	= $no_kirim_bpkb;
		  $dt['tgl_kirim_bpkb'] = $tgl;
		  $dt['status_bpkb'] 		= "input";
		  $dt['created_by'] 		= $login_id;
		  $dt['created_at'] 		= $waktu;		  
			$this->m_admin->insert("tr_kirim_bpkb",$dt);

			$query = "SELECT tr_entry_stnk.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_dealer.nama_dealer FROM tr_entry_stnk INNER JOIN ms_tipe_kendaraan ON tr_entry_stnk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
	  					INNER JOIN ms_warna ON tr_entry_stnk.id_warna = ms_warna.id_warna
	  					inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
		  				inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
		  				inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
	   					WHERE tr_entry_stnk.updated_by = '$login_id' AND tr_entry_stnk.print_bpkb = 'printable'";
		}else{
			$no_mesin = $this->input->get("id");
			$cek_bpkb = $this->db->query("SELECT * FROM tr_kirim_bpkb INNER JOIN tr_kirim_bpkb_detail ON tr_kirim_bpkb.no_kirim_bpkb = tr_kirim_bpkb_detail.no_kirim_bpkb
			 WHERE tr_kirim_bpkb_detail.no_mesin = '$no_mesin'");
			if($cek_bpkb->num_rows() > 0){
				$no_kirim_bpkb = $cek_bpkb->row()->no_kirim_bpkb;
				$cek_query = "WHERE tr_kirim_bpkb.no_kirim_bpkb = '$no_kirim_bpkb'";
			}else{
				$no_kirim_bpkb = "";
				$cek_query = "";
			} 
			$query = "SELECT tr_entry_stnk.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_dealer.nama_dealer FROM tr_entry_stnk INNER JOIN ms_tipe_kendaraan ON tr_entry_stnk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
	  					INNER JOIN ms_warna ON tr_entry_stnk.id_warna = ms_warna.id_warna
	  					inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
		  				inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
		  				inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
		  				inner join tr_kirim_bpkb_detail ON tr_faktur_stnk_detail.no_mesin = tr_kirim_bpkb_detail.no_mesin
		  				inner join tr_kirim_bpkb ON tr_kirim_bpkb_detail.no_kirim_bpkb = tr_kirim_bpkb.no_kirim_bpkb $cek_query";	   					
		}
		$pdf->SetY(25);
		$pdf->SetFont('ARIAL','',11);
		$pdf->Cell(35,5, 'No Serah Terima',0,0,'L');
		$pdf->Cell(90,5, ': '.$no_kirim_bpkb,0,1,'L');
		$pdf->Cell(35,5, 'Tanggal',0,0,'L');
			$tgl = date('d-m-Y', strtotime($tgl));
		$pdf->Cell(90,5, ': '.$tgl,0,1,'L');
		$pdf->Cell(90,5, '',0,1,'L');
		$pdf->Cell(35,5, 'Kepada Yth',0,1,'L');
		$pdf->Cell(35,5, 'Kepala Bagian BPKB',0,1,'L');
		$pdf->Cell(35,5, 'Di PT. Sinar Sentosa Primatama',0,1,'L');
		$pdf->Cell(90,5, '',0,1,'L');
		$pdf->Cell(35,5, 'Dengan Hormat',0,1,'L');
		$pdf->Cell(190,5, 'Dengan ini kami serah terimakan dokumen BPKB sebagai berikut :',0,1,'L');
		$pdf->Cell(35,3, '',0,1,'L');
		$pdf->Cell(10,5, 'No.',1,0,'C');
		$pdf->Cell(45,5, 'Nama Dealer',1,0,'C');
		$pdf->Cell(55,5, 'Nama Konsumen',1,0,'C');
		$pdf->Cell(30,5, 'No. Mesin',1,0,'C');
		$pdf->Cell(25,5, 'No. Polisi',1,0,'C');
		$pdf->Cell(25,5, 'No. BPKB',1,1,'C');
		
		$get_nosin 	= $this->db->query($query);

		  $i=1;	  
		  								
		  foreach ($get_nosin->result() as $r)
		  {

		  	$ds["no_kirim_bpkb"]	= $no_kirim_bpkb;
		  	$ds["no_bpkb"] 				= $r->no_bpkb;
				$ds["no_mesin"] 			= $r->no_mesin;	
				$ds["cetak"] 					= "ya";	
				if(!isset($_GET['id'])){
					$this->m_admin->insert("tr_kirim_bpkb_detail",$ds);								
					$dw["print_stnk"] 		= "ya";	
					$this->db->query("UPDATE tr_entry_stnk SET print_bpkb = 'ya' WHERE no_mesin = '$r->no_mesin'");
				}
				$pdf->Cell(10,5, $i,1,0,'C');
				$pdf->Cell(45,5, $r->nama_dealer,1,0,'C');
				$pdf->Cell(55,5, $r->nama_konsumen,1,0,'C');
				$pdf->Cell(30,5, $r->no_mesin,1,0,'C');
				$pdf->Cell(25,5, $r->no_plat,1,0,'C');
				$pdf->Cell(25,5, $r->no_bpkb,1,1,'C');
		  	$i++; 	   		    
		  }	   
		   

		$pdf->Cell(35,3, '',0,1,'L');
		$pdf->Cell(190,5, 'Demikian kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.',0,1,'L');
		$pdf->Cell(35,3, '',0,1,'L');
		$pdf->Cell(190,5, '=====================================================================================',0,1,'C');
		$pdf->Cell(35,3, '',0,1,'L');
		$pdf->SetFont('ARIAL','B',11);
		$pdf->Cell(190,5, 'Dokumen telah diterima oleh Pihak PT.Sinar Sentosa Primatama',0,1,'C');
		$pdf->Cell(35,3, '',0,1,'L');
		$pdf->SetFont('ARIAL','',11);
		$pdf->Cell(95,5, 'Yang Menyerahkan',0,0,'L');
		$pdf->Cell(95,5, 'Yang Menerima',0,1,'L');
		$pdf->Cell(30,5, 'Nama',0,0,'L');
		$pdf->Cell(65,5, ': _______________________',0,0,'L');
		$pdf->Cell(30,5, 'Nama',0,0,'L');
		$pdf->Cell(65,5, ': _______________________',0,1,'L');
		$pdf->Cell(30,5, 'Tanggal',0,0,'L');
		$pdf->Cell(65,5, ': _______________________',0,0,'L');
		$pdf->Cell(30,5, 'Tanggal',0,0,'L');
		$pdf->Cell(65,5, ': _______________________',0,1,'L');
		$pdf->Cell(30,5, 'Jam',0,0,'L');
		$pdf->Cell(65,5, ': ____________________WIB',0,0,'L');
		$pdf->Cell(30,5, 'Jam',0,0,'L');
		$pdf->Cell(65,5, ': ____________________WIB',0,1,'L');
		$pdf->Cell(65,20, '',0,1,'L');
		$pdf->Cell(30,5, 'TTD',0,0,'L');
		$pdf->Cell(65,5, ': _______________________',0,0,'L');
		$pdf->Cell(30,5, 'TTD',0,0,'L');
		$pdf->Cell(65,5, ': _______________________',0,1,'L');

	  	$pdf->Output(); 
		
	}



	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}			
	public function cari_id(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
				
		$pr_num = $this->db->query("SELECT * FROM tr_serah_terima where LEFT(created_at,4) = '$th' ORDER BY no_serah_terima DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_serah_terima)-8;
			$id 	= substr($row->no_serah_terima,$pan,5)+1;	
			if($id < 10){
					$kode1 = $th."/"."0000".$id."/SRH";          					
      }elseif($id>9 && $id<=99){
					$kode1 = $th."/"."000".$id."/SRH";          					
      }elseif($id>99 && $id<=999){
					$kode1 = $th."/"."00".$id."/SRH";          					
      }elseif($id>999){
					$kode1 = $th."/"."0".$id."/SRH";          					
      }
			$kode = $kode1;
		}else{
			$kode = $th."/00001/SRH";
		}						
		return $kode;
	}
	public function cari_stnk_old(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
				
		$pr_num = $this->db->query("SELECT * FROM tr_kirim_stnk ORDER BY no_kirim_stnk DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_kirim_stnk)-8;
			$id 	= substr($row->no_kirim_stnk,$pan,5)+1;	
			if($id < 10){
					$kode1 = $th."/"."0000".$id."/STNK";          					
      }elseif($id>9 && $id<=99){
					$kode1 = $th."/"."000".$id."/STNK";          					
      }elseif($id>99 && $id<=999){
					$kode1 = $th."/"."00".$id."/STNK";          					
      }elseif($id>999){
					$kode1 = $th."/"."0".$id."/STNK";          					
      }
			$kode = $kode1;
		}else{
			$kode = $th."/00001/STNK";
		}						
		return $kode;
	}
	public function cari_stnk(){
		$th = date("Y");
		$q = $this->db->query("SELECT MAX(MID(no_kirim_stnk,6,5)) AS kd_max FROM tr_kirim_stnk WHERE LEFT(created_at,4) = '$th' ORDER BY no_kirim_stnk DESC LIMIT 0,1");		
        $kd = "";
        if($q->num_rows()>0){
            foreach($q->result() as $k){
                $tmp = ((int)$k->kd_max)+1;            
                $kd = sprintf("%05s", $tmp);
                $kode = $th."/".$kd."/STNK";
            }
        }else{        
            $kode = $th."/00001/STNK";
        }
        return $kode;
	}
	public function get_plat(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
				
		$pr_num = $this->db->query("SELECT * FROM tr_kirim_plat ORDER BY no_kirim_plat DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_kirim_plat)-8;
			$id 	= substr($row->no_kirim_plat,$pan,5)+1;	
			if($id < 10){
					$kode1 = $th."/"."0000".$id."/PLAT";          					
      }elseif($id>9 && $id<=99){
					$kode1 = $th."/"."000".$id."/PLAT";          					
      }elseif($id>99 && $id<=999){
					$kode1 = $th."/"."00".$id."/PLAT";          					
      }elseif($id>999){
					$kode1 = $th."/"."0".$id."/PLAT";          					
      }
			$kode = $kode1;
		}else{
			$kode = $th."/00001/PLAT";
		}						
		return $kode;
	}
	public function cari_plat(){
		$th = date("Y");
		$q = $this->db->query("SELECT MAX(MID(no_kirim_plat,6,5)) AS kd_max FROM tr_kirim_plat WHERE LEFT(created_at,4) = '$th' ORDER BY no_kirim_plat DESC LIMIT 0,1");		
        $kd = "";
        if($q->num_rows()>0){
            foreach($q->result() as $k){
                $tmp = ((int)$k->kd_max)+1;            
                $kd = sprintf("%05s", $tmp);
                $kode = $th."/".$kd."/PLAT";
            }
        }else{        
            $kode = $th."/00001/PLAT";
        }
        return $kode;
	}
	public function cari_bpkb_old(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
				
		$pr_num = $this->db->query("SELECT * FROM tr_kirim_bpkb ORDER BY no_kirim_bpkb DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_kirim_bpkb)-8;
			$id 	= substr($row->no_kirim_bpkb,$pan,5)+1;	
			if($id < 10){
					$kode1 = $th."/"."0000".$id."/BPKB";          					
      }elseif($id>9 && $id<=99){
					$kode1 = $th."/"."000".$id."/BPKB";          					
      }elseif($id>99 && $id<=999){
					$kode1 = $th."/"."00".$id."/BPKB";          					
      }elseif($id>999){
					$kode1 = $th."/"."0".$id."/BPKB";          					
      }
			$kode = $kode1;
		}else{
			$kode = $th."/00001/BPKB";
		}						
		return $kode;
	}
	public function cari_bpkb(){
		$th = date("Y");
		$q = $this->db->query("SELECT MAX(MID(no_kirim_bpkb,6,5)) AS kd_max FROM tr_kirim_bpkb WHERE LEFT(created_at,4) = '$th' ORDER BY no_kirim_bpkb DESC LIMIT 0,1");		
        $kd = "";
        if($q->num_rows()>0){
            foreach($q->result() as $k){
                $tmp = ((int)$k->kd_max)+1;            
                $kd = sprintf("%05s", $tmp);
                $kode = $th."/".$kd."/BPKB";
            }
        }else{        
            $kode = $th."/00001/BPKB";
        }
        return $kode;
	}

	public function save()
	{				
		// ini_set('post_max_size',"200M");

		$waktu                 = gmdate("Y-m-d H:i:s", time()+60*60*7);
		$tgl                   = gmdate("Y-m-d", time()+60*60*7);
		$login_id              = $this->session->userdata('id_user');						
		$no_serah_terima       = $this->cari_id();
// 		$da['no_serah_terima'] = $no_serah_terima;		
		$da['created_at']      = $waktu;		
		$da['created_by']      = $login_id;		
		$ins_entry_stnk	= array();		
		$upd_entry_stnk = array();
		$upd_proses_bbn_detail = array();

		$jum 										= $this->input->post("jum");

		// print_r($_POST);die;

		//$am=0;$tot=0;
		for ($i=1; $i <= $jum; $i++) { 
			$is_insert=0;
			unset($data);
			$nosin                     = $_POST["no_mesin_".$i];			
			$data['no_mesin']          = $nosin;
// 			$data['no_serah_terima']   = $no_serah_terima;
			$data['no_rangka']         = $_POST["no_rangka_".$i];			
			$data['nama_konsumen']     = $_POST["nama_konsumen_".$i];			
			$data['id_tipe_kendaraan'] = $_POST["id_tipe_kendaraan_".$i];			
			$data['id_warna']          = $_POST["id_warna_".$i];			
			$data['notice_pajak']      = ($_POST["notice_pajak_".$i]!="")?$_POST["notice_pajak_".$i]:0;	
			$data['status_stnk'] 	   = "input";	
				 
			// $cek = $this->m_admin->getByID("tr_entry_stnk","no_mesin",$nosin);
			
			$cek = $this->db->query("SELECT * FROM tr_entry_stnk WHERE no_mesin = '$nosin'");
			if($cek->num_rows() > 0){
				if($_POST["no_stnk_".$i] != "" && $cek->row()->print_stnk !='printable' && $cek->row()->print_stnk !='ya'){
					$data["no_stnk"]    = $_POST["no_stnk_".$i];
					$data["tgl_stnk"]   = $tgl;
					$data["print_stnk"] = "input";	
					$upd_proses_bbn_detail[] = ['status_stnk'=>'ya','no_mesin'=>$nosin];
				}

				if($_POST["no_pol_".$i] != ""){
					$data["no_pol"] = 'BH '.$_POST["no_pol_".$i].' '.$_POST["b_pol_".$i];	
				}else{
					if($cek->num_rows() > 0 AND (!is_null($cek->row()->no_pol) OR $cek->row()->no_pol!='')){
						$data['no_pol']    = $cek->row()->no_pol;
					}else{
						$data['no_pol']    = "";
					}
				}

				if($_POST["no_plat_".$i] != "" && $cek->row()->print_plat !='printable' && $cek->row()->print_plat !='ya'){
					$data["no_plat"]    = 'BH '.$_POST["no_plat_".$i].' '.$_POST["b_plat_".$i];						 
					$data["tgl_plat"]   = $tgl;			
					$data["print_plat"] = "input";	
					$upd_proses_bbn_detail[] = ['status_plat'=>'ya','no_mesin'=>$nosin];
				}
				
				if($_POST["no_bpkb_".$i] != "" && $cek->row()->print_bpkb !='printable' && $cek->row()->print_bpkb !='ya'){
					$data["no_bpkb"]    = $_POST["no_bpkb_".$i];
					$data["tgl_bpkb"]   = $tgl;			
					$data["print_bpkb"] = "input";	

					$upd_proses_bbn_detail[] = ['status_bpkb'=>'ya','no_mesin'=>$nosin];
				}
		
				$data['updated_at'] = $waktu;		
				$data['updated_by'] = $login_id;	
				$upd_entry_stnk[]=$data;
				// $this->m_admin->update("tr_entry_stnk",$data,"no_mesin",$nosin);
			}else{	
				$data["no_pol"] = '';	
				$data["no_stnk"]    = '';
				$data["no_bpkb"]    = '';
				$data["no_plat"]    = '';
				$data["print_bpkb"] = '';			
				$data["print_plat"] = '';						
				$data["print_stnk"] = '';
				$data["tgl_plat"]   = '';
				$data["tgl_bpkb"]   = '';
				$data["tgl_stnk"]   = '';
	
				if($_POST["no_stnk_".$i] != ""){
					$is_insert++;
					$data["no_stnk"]    = $_POST["no_stnk_".$i];
					$data["tgl_stnk"]   = $tgl;
					$data["print_stnk"] = "input";	
					$upd_proses_bbn_detail[] = ['status_stnk'=>'ya','no_mesin'=>$nosin];
				}

				if($_POST["no_pol_".$i] != ""){
					$is_insert++;
					$data["no_pol"] = 'BH '.$_POST["no_pol_".$i].' '.$_POST["b_pol_".$i];	
				}else{
					if($cek->num_rows() > 0 AND (!is_null($cek->row()->no_pol) OR $cek->row()->no_pol!='')){
						$is_insert++;
						$data['no_pol']    = $cek->row()->no_pol;
					}else{
						$data['no_pol']    = "";
					}
				}

				if($_POST["no_plat_".$i] != ""){
					$is_insert++;
					$data["no_plat"]    = 'BH '.$_POST["no_plat_".$i].' '.$_POST["b_plat_".$i];						 
					$data["tgl_plat"]   = $tgl;			
					$data["print_plat"] = "input";	
					$upd_proses_bbn_detail[] = ['status_plat'=>'ya','no_mesin'=>$nosin];
				}
				
				if($_POST["no_bpkb_".$i] != ""){
					$is_insert++;
					$data["no_bpkb"]    = $_POST["no_bpkb_".$i];
					$data["tgl_bpkb"]   = $tgl;			
					$data["print_bpkb"] = "input";	

					$upd_proses_bbn_detail[] = ['status_bpkb'=>'ya','no_mesin'=>$nosin];
				}

				if($nosin!='' || $is_insert > 0){
					$ins_entry_stnk[]=$data;
				}			
				// $this->m_admin->insert("tr_entry_stnk",$data);	
			}
			
		} //End For

		// print_r($ins_entry_stnk);
		// echo '<br>';
		// echo '<br>';
		// // print_r($upd_entry_stnk);
		// echo '<br>';
		// echo '<br>';
		// print_r($upd_proses_bbn_detail);
		// echo '<br>';
		// echo '<br>';
		
		// echo $jum; die;


		$this->db->trans_begin();

		if (count($ins_entry_stnk)>0) {
			$this->db->insert_batch('tr_entry_stnk',$ins_entry_stnk);
		}

		if (count($upd_entry_stnk)>0) {
			$this->db->update_batch('tr_entry_stnk',$upd_entry_stnk,'no_mesin');
		}

		if (count($upd_proses_bbn_detail)>0) {
			$this->db->update_batch('tr_proses_bbn_detail',$upd_proses_bbn_detail,'no_mesin');
		}

		if ($this->db->trans_status() === FALSE){
      			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something went wrong !";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
      		}else{
        		$this->db->trans_commit();
			$_SESSION['pesan'] 	= "Data has been save successfully";
			$_SESSION['tipe'] 	= "success";		
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/entry_stnk'>";
		}
	}

	public function save_oold()
	{				
		$waktu                 = gmdate("Y-m-d H:i:s", time()+60*60*7);
		$tgl                   = gmdate("Y-m-d", time()+60*60*7);
		$login_id              = $this->session->userdata('id_user');						
		$no_serah_terima       = $this->cari_id();
// 		$da['no_serah_terima'] = $no_serah_terima;		
		$da['created_at']      = $waktu;		
		$da['created_by']      = $login_id;		
		
		$jum 										= $this->input->post("jum");
		//$am=0;$tot=0;
		for ($i=1; $i <= $jum; $i++) { 
			$nosin                     = $_POST["no_mesin_".$i];			
			$data['no_mesin']          = $nosin;
// 			$data['no_serah_terima']   = $no_serah_terima;
			$data['no_rangka']         = $_POST["no_rangka_".$i];			
			$data['nama_konsumen']     = $_POST["nama_konsumen_".$i];			
			$data['id_tipe_kendaraan'] = $_POST["id_tipe_kendaraan_".$i];			
			$data['id_warna']          = $_POST["id_warna_".$i];			
			$data['notice_pajak']      = ($_POST["notice_pajak_".$i]!="")?$_POST["notice_pajak_".$i]:0;						 
			$cek = $this->m_admin->getByID("tr_entry_stnk","no_mesin",$nosin);
			if($_POST["no_stnk_".$i] != ""){
				$data["no_stnk"]    = $_POST["no_stnk_".$i];
				$data["tgl_stnk"]   = $tgl;
				$data["print_stnk"] = "input";	
				$upd_proses_bbn_detail[] = ['status_stnk'=>'ya','no_mesin'=>$nosin];
			}else{
			    if($cek->num_rows() > 0 AND (!is_null($cek->row()->no_stnk) OR $cek->row()->no_stnk!='')){
			        $data['no_stnk']    = $cek->row()->no_stnk;
    				$data['tgl_stnk']   = $cek->row()->tgl_stnk;
    				$data["print_stnk"] = $cek->row()->print_stnk;
			    }else{
			        $data['no_stnk']    = "";
    				$data['tgl_stnk']   = "0000-00-00";
    				$data["print_stnk"] = "";	 
			    }
			}
			if($_POST["no_plat_".$i] != ""){
				$data["no_plat"]    = 'BH '.$_POST["no_plat_".$i].' '.$_POST["b_plat_".$i];						 
				$data["tgl_plat"]   = $tgl;			
				$data["print_plat"] = "input";	
				$upd_proses_bbn_detail[] = ['status_plat'=>'ya','no_mesin'=>$nosin];
			}else{
			    if($cek->num_rows() > 0 AND (!is_null($cek->row()->no_plat) OR $cek->row()->no_plat!='')){
			        $data['no_plat']    = $cek->row()->no_plat;
    				$data['tgl_plat']   = $cek->row()->tgl_plat;
    				$data["print_plat"] = $cek->row()->print_plat;
			    }else{
			        $data['no_plat']    = "";
    				$data['tgl_plat']   = "0000-00-00";
    				$data["print_plat"] = "";	 
			    }
			}
			if($_POST["no_pol_".$i] != ""){
				$data["no_pol"] = 'BH '.$_POST["no_pol_".$i].' '.$_POST["b_pol_".$i];	
			}else{
			    if($cek->num_rows() > 0 AND (!is_null($cek->row()->no_pol) OR $cek->row()->no_pol!='')){
			        $data['no_pol']    = $cek->row()->no_pol;
			    }else{
			        $data['no_pol']    = "";
			    }
			}
			
			if($_POST["no_bpkb_".$i] != ""){
				$data["no_bpkb"]    = $_POST["no_bpkb_".$i];
				$data["tgl_bpkb"]   = $tgl;			
				$data["print_bpkb"] = "input";	
				$upd_proses_bbn_detail[] = ['status_bpkb'=>'ya','no_mesin'=>$nosin];
			}else{
			    if($cek->num_rows() > 0 AND (!is_null($cek->row()->no_bpkb) OR $cek->row()->no_bpkb!='')){
			        $data['no_bpkb']    = $cek->row()->no_bpkb;
    				$data['tgl_bpkb']   = $cek->row()->tgl_bpkb;
    				$data["print_bpkb"] = $cek->row()->print_bpkb;
			    }else{
			        $data['no_bpkb']    = "";
    				$data['tgl_bpkb']   = "0000-00-00";
    				$data["print_bpkb"] = "";	 
			    }
			}
			
			$data['status_stnk'] 			= "input";		

			$cek = $this->db->query("SELECT * FROM tr_entry_stnk WHERE no_mesin = '$nosin'");
			if($cek->num_rows() > 0){		
				$data['updated_at'] = $waktu;		
				$data['updated_by'] = $login_id;	
				$upd_entry_stnk[]=$data;
				// $this->m_admin->update("tr_entry_stnk",$data,"no_mesin",$nosin);
			}else{	
				$data['created_at'] = $waktu;		
				$data['created_by'] = $login_id;	
				$ins_entry_stnk[]=$data;			
				// $this->m_admin->insert("tr_entry_stnk",$data);	
			}	
		} //End For

// 		$cek_result = ['ins_entry_stnk'=>isset($ins_entry_stnk)?$ins_entry_stnk:null,
// 						'upd_entry_stnk'=>isset($upd_entry_stnk)?$upd_entry_stnk:null,
// 						'upd_proses_bbn_detail'=>isset($upd_proses_bbn_detail)?$upd_proses_bbn_detail:null
// 					  ];
// 		echo json_encode($cek_result);
// 		exit;
		$this->db->trans_begin();
			if (isset($ins_entry_stnk)) {
				$this->db->insert_batch('tr_entry_stnk',$ins_entry_stnk);
			}

			if (isset($upd_entry_stnk)) {
				$this->db->update_batch('tr_entry_stnk',$upd_entry_stnk,'no_mesin');
			}

			if (isset($upd_proses_bbn_detail)) {
				$this->db->update_batch('tr_proses_bbn_detail',$upd_proses_bbn_detail,'no_mesin');
			}


		if ($this->db->trans_status() === FALSE)
      	{
      		$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something went wrong !";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
      	}
      	else
      	{
        	$this->db->trans_commit();
			$_SESSION['pesan'] 	= "Data has been save successfully";
			$_SESSION['tipe'] 	= "success";		
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/entry_stnk'>";
		}
	}
	public function save_old()
	{				
		$waktu                 = gmdate("Y-m-d H:i:s", time()+60*60*7);
		$tgl                   = gmdate("Y-m-d", time()+60*60*7);
		$login_id              = $this->session->userdata('id_user');						
		$no_serah_terima       = $this->cari_id();
		$da['no_serah_terima'] = $no_serah_terima;		
		$da['created_at']      = $waktu;		
		$da['created_by']      = $login_id;		
		
		$jum 										= $this->input->post("jum");
		//$am=0;$tot=0;
		for ($i=1; $i <= $jum; $i++) { 
			$nosin                     = $_POST["no_mesin_".$i];			
			$data['no_mesin']          = $nosin;
			$data['no_serah_terima']   = $no_serah_terima;
			$data['no_rangka']         = $_POST["no_rangka_".$i];			
			$data['nama_konsumen']     = $_POST["nama_konsumen_".$i];			
			$data['id_tipe_kendaraan'] = $_POST["id_tipe_kendaraan_".$i];			
			$data['id_warna']          = $_POST["id_warna_".$i];			
			$data['notice_pajak']      = $_POST["notice_pajak_".$i];						 
			if($_POST["no_stnk_".$i] != ""){
				$data["no_stnk"]    = $_POST["no_stnk_".$i];
				$data["tgl_stnk"]   = $tgl;
				$data["print_stnk"] = "printable";	
				// $this->db->query("UPDATE tr_proses_bbn_detail SET status_stnk = 'ya' WHERE no_mesin = '$nosin'");
				$upd_proses_bbn_detail[] = ['status_stnk'=>'ya','no_mesin'=>$nosin];
			}else{
				$data['no_stnk']    = "";
				$data['tgl_stnk']   = "";
				$data["print_stnk"] = "";	
			}
			if($_POST["no_plat_".$i] != ""){
				$data["no_plat"]    = 'BH '.$_POST["no_plat_".$i].' '.$_POST["b_plat_".$i];						 
				$data["tgl_plat"]   = $tgl;			
				$data["print_plat"] = "printable";	
				// $this->db->query("UPDATE tr_proses_bbn_detail SET status_plat = 'ya' WHERE no_mesin = '$nosin'");
				$upd_proses_bbn_detail[] = ['status_plat'=>'ya','no_mesin'=>$nosin];
			}else{
				$data['no_plat']  			= "";
				$data['tgl_plat']  			= "";
				$data["print_plat"] 		= "";	
			}
			if($_POST["no_pol_".$i] != ""){
				$data["no_pol"] = 'BH '.$_POST["no_pol_".$i].' '.$_POST["b_pol_".$i];	
			}else{
				$data['no_pol'] = "";
			}
			
			if($_POST["no_bpkb_".$i] != ""){
				$data["no_bpkb"]    = $_POST["no_bpkb_".$i];
				$data["tgl_bpkb"]   = $tgl;			
				$data["print_bpkb"] = "printable";	
				// $this->db->query("UPDATE tr_proses_bbn_detail SET status_bpkb = 'ya' WHERE no_mesin = '$nosin'");
				$upd_proses_bbn_detail[] = ['status_bpkb'=>'ya','no_mesin'=>$nosin];
			}else{
				$data['no_bpkb']  			= "";
				$data['tgl_bpkb']  			= "";
				$data["print_bpkb"] 		= "";	
			}				
			
			$data['status_stnk'] 			= "input";		

			$cek = $this->db->query("SELECT * FROM tr_entry_stnk WHERE no_mesin = '$nosin'");
			if($cek->num_rows() > 0){		
				$data['updated_at'] = $waktu;		
				$data['updated_by'] = $login_id;	
				$upd_entry_stnk[]=$data;
				// $this->m_admin->update("tr_entry_stnk",$data,"no_mesin",$nosin);
			}else{	
				$data['created_at'] = $waktu;		
				$data['created_by'] = $login_id;	
				$ins_entry_stnk[]=$data;			
				// $this->m_admin->insert("tr_entry_stnk",$data);	
			}	
		} //End For

		// $cek_result = ['ins_entry_stnk'=>isset($ins_entry_stnk)?$ins_entry_stnk:null,
		// 				'upd_entry_stnk'=>isset($upd_entry_stnk)?$upd_entry_stnk:null,
		// 				'upd_proses_bbn_detail'=>isset($upd_proses_bbn_detail)?$upd_proses_bbn_detail:null
		// 			  ];
		// echo json_encode($cek_result);
		// exit;
		$this->db->trans_begin();
			if (isset($ins_entry_stnk)) {
				$this->db->insert_batch('tr_entry_stnk',$ins_entry_stnk);
			}

			if (isset($upd_entry_stnk)) {
				$this->db->update_batch('tr_entry_stnk',$upd_entry_stnk,'no_mesin');
			}

			if (isset($upd_proses_bbn_detail)) {
				$this->db->update_batch('tr_proses_bbn_detail',$upd_proses_bbn_detail,'no_mesin');
			}

			$ce = $this->db->query("SELECT * FROM tr_serah_terima WHERE no_serah_terima = '$no_serah_terima'");
			if($ce->num_rows() > 0){						
				$this->m_admin->update("tr_serah_terima",$da,"no_serah_terima",$no_serah_terima);
			}else{
				$this->m_admin->insert("tr_serah_terima",$da);			
			}

		if ($this->db->trans_status() === FALSE)
      	{
      		$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something went wrong !";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
      	}
      	else
      	{
        	$this->db->trans_commit();
			$_SESSION['pesan'] 	= "Data has been sent successfully";
			$_SESSION['tipe'] 	= "success";		
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/entry_stnk/cetak?id=1'>";
		}
	}

	public function konfirmasi()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;				
		$id = $this->input->get('id');		
		$a 	= $this->input->get('a');		
		$data['dt_biro']= $this->m_admin->getByID("tr_kirim_biro","no_tanda_terima",$a);
		$data['dt_map']	= $this->db->query("SELECT tr_pengajuan_bbn_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_pengajuan_bbn_detail INNER JOIN ms_tipe_kendaraan 
				ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				INNER JOIN ms_warna ON tr_pengajuan_bbn_detail.id_warna = ms_warna.id_warna
				WHERE tr_pengajuan_bbn_detail.id_generate='$id'");											
		$data['set']		= "konfirmasi";				
		$this->template($data);			
	}
	
	public function edit_data()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Edit Data ".$this->title;															
		$data['set']	= "edit_data";
		$search = $this->input->get('no_mesin');
		if ($search) {
			$data['status_search'] = true;
			$data['data_search'] = $this->db->query("SELECT a.nama_konsumen,a.no_bpkb,a.no_mesin,a.no_plat,a.no_pol,a.no_rangka,a.no_stnk,a.notice_pajak,a.updated_at,d.nama_dealer 
			FROM tr_entry_stnk a 
			JOIN tr_pengajuan_bbn_detail b on b.no_mesin=a.no_mesin 
			JOIN tr_pengajuan_bbn c on c.no_bastd =b.no_bastd 
			JOIN ms_dealer d on d.id_dealer=c.id_dealer where a.no_mesin='$search'")->row();
		} else {
			$data['status_search'] = false;
			$data['data_search'] = [];
		}		
		$this->template($data);			
	}

	public function update_data($no_mesin=''){
		$no_mesin     = $this->input->post('no_mesin');
		$no_stnk      = $this->input->post('no_stnk');
		$no_bpkb      = $this->input->post('no_bpkb');
		$no_plat      = $this->input->post('no_plat');
		$no_pol       = $this->input->post('no_pol');
		$notice_pajak = $this->input->post('notice_pajak');
		$waktu        = gmdate("Y-m-d H:i:s", time()+60*60*7);
		$login_id     = $this->session->userdata('id_user');
		
		$this->db->set('no_stnk', $no_stnk);
		$this->db->set('no_pol', $no_pol);
		$this->db->set('no_bpkb', $no_bpkb);
		$this->db->set('no_plat', $no_plat);
		$this->db->set('notice_pajak', $notice_pajak);

		if($no_mesin!=''){
			// $this->db->set('updated_at', $waktu);
			// $this->db->set('updated_by', $login_id);
			$this->db->where('no_mesin',  $no_mesin);
			$this->db->update('tr_entry_stnk');

			$this->db->set('no_stnk', $no_stnk);
			$this->db->set('no_bpkb', $no_bpkb);
			$this->db->set('no_plat', $no_plat);
			$this->db->set('notice_pajak', $notice_pajak);
			// $this->db->set('updated_at', $waktu);
			// $this->db->set('updated_by', $login_id);
			$this->db->where('no_mesin',  $no_mesin);
			$this->db->update('tr_terima_bj');
			
			$this->db->set('notice_pajak', $notice_pajak);
			$this->db->where('no_mesin',  $no_mesin);
			$this->db->update('tr_proses_bbn_detail');

			$this->db->set('no_stnk', $no_stnk);
			$this->db->where('no_mesin',  $no_mesin);
			$this->db->update('tr_kirim_stnk_detail');

			$this->db->set('no_bpkb', $no_bpkb);
			$this->db->where('no_mesin',  $no_mesin);
			$this->db->update('tr_kirim_bpkb_detail');

			$this->db->set('no_plat', $no_plat);
			$this->db->where('no_mesin',  $no_mesin);
			$this->db->update('tr_kirim_plat_detail');

			send_json('Berhasil Update Data');
		}else{
			send_json('Error! No Mesin Tidak ditemukan');
		}
	}
}