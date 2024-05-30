<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_opname extends CI_Controller {

  var $tables =   "tr_stock_opname";	
	var $folder =   "dealer";
	var $page		=		"stock_opname";
  var $pk     =   "id_stock_opname";
  var $title  =   "Stock Opname";

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
		$data['set']		= "view";
		$id_dealer 			= $this->m_admin->cari_dealer();
		$data['dt_stock_op'] = $this->db->query("SELECT * FROM tr_stock_opname WHERE id_dealer = '$id_dealer' ORDER BY id_stock_opname DESC");				
		$this->template($data);			
	}
	
	public function detail()
	{				
		$id    	    = $this->input->get("id");	
		$id_dealer 	= $this->m_admin->cari_dealer();

		$data['isi']    = $this->page;		
		$data['title']	= "Detail ".$this->title;															
		$data['set']		= "detail";
		$id_stock_opname 			= $this->input->get('id');				
		$data['dt_stock_op'] = $this->db->query("SELECT * FROM tr_stock_opname WHERE id_stock_opname = '$id_stock_opname'");
		$data['dt_'] = $this->db->query("SELECT *, tr_stock_opname_detail.no_mesin as nomesin FROM tr_stock_opname_detail
			join tr_stock_opname on tr_stock_opname_detail.id_stock_opname = tr_stock_opname.id_stock_opname
				join ms_tipe_kendaraan on tr_stock_opname_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				join ms_warna on tr_stock_opname_detail.id_warna=ms_warna.id_warna
				WHERE tr_stock_opname_detail.id_stock_opname='$id_stock_opname'  and tr_stock_opname.id_dealer='$id_dealer'
			");			 						
		$this->template($data);			
	}

	public function show_data(){
		$id_dealer 	= $this->m_admin->cari_dealer();
		//$this->tambah_tipe();
			$cek_tipe='kosong';
		
		if ($this->input->post('tipe')!='all') {
			if (isset($_SESSION['type'])) {
				foreach ($_SESSION['type'] as $key => $value) {
					$tipe[$key] = "'$value'";
				}			$tipe_imp = implode(',', $tipe);
				$cek_tipe="WHERE tr_shipping_list.id_modell in($tipe_imp)";
			}else{
				$tipe_imp=$this->input->post('tipe');
				$cek_tipe="WHERE tr_shipping_list.id_modell ='$tipe_imp'";
			}
		}elseif ($this->input->post('tipe') == '' or $this->input->post('tipe')=='--Pilih--') {
			$cek_tipe='kosong';
		}elseif ($this->input->post('tipe')=='all') {
			$cek_tipe='';
		}

		if ($cek_tipe !='kosong') {
			 	$data['dt_'] = $this->db->query("SELECT *,tr_scan_barcode.id_item,ms_warna.warna as nama_warna FROM tr_penerimaan_unit_dealer_detail
				join tr_penerimaan_unit_dealer on tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer AND tr_penerimaan_unit_dealer.id_dealer='$id_dealer'
				left join tr_shipping_list on tr_shipping_list.no_mesin = tr_penerimaan_unit_dealer_detail.no_mesin 
				join ms_tipe_kendaraan on tr_shipping_list.id_modell = ms_tipe_kendaraan.id_tipe_kendaraan
				join ms_warna on tr_shipping_list.id_warna=ms_warna.id_warna
				join tr_scan_barcode on tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
				$cek_tipe
				order by tr_penerimaan_unit_dealer_detail.fifo ASC
			");	
		}else{
			$data=0;
		}	 
		$this->load->view('dealer/t_stock_opname',$data);
	}

	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";	
		unset($_SESSION['type']);				
		$this->template($data);										
	}

	public function edit()
	{				
		$id_dealer 	= $this->m_admin->cari_dealer();
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;	
		$id_stock_opname = $this->input->get("id");
		$data['stock_opname'] = $this->db->query("SELECT * FROM tr_stock_opname WHERE id_stock_opname='$id_stock_opname' ")->row();	

		$data['set']		= "edit";	
		$data['dt_'] = $this->db->query("SELECT *, tr_stock_opname_detail.no_mesin as nomesin FROM tr_stock_opname_detail
			join tr_stock_opname on tr_stock_opname_detail.id_stock_opname = tr_stock_opname.id_stock_opname
				join ms_tipe_kendaraan on tr_stock_opname_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				join ms_warna on tr_stock_opname_detail.id_warna=ms_warna.id_warna
				WHERE tr_stock_opname_detail.id_stock_opname='$id_stock_opname' and tr_stock_opname.id_dealer='$id_dealer'
			");
		unset($_SESSION['type']);				
		$this->template($data);										
	}

	public function pilih_tipe()
	{
		$id_dealer 	= $this->m_admin->cari_dealer();
		$tipe =$this->input->post('tipe');
		 if ($tipe != 'all' AND $tipe !='') {
		 	if (isset($_SESSION['type'])) {

				$x=count($_SESSION['type']);
				if ($x>0) {	
					if (in_array( $tipe , $_SESSION['type']	)) {
						# code...
					}else{
						$x += 1;
					$_SESSION['type'][$x] =$this->input->post("tipe");
					}
				}	
			}else{
				$_SESSION['type'][0] =$this->input->post("tipe");	
			}
			
		 }elseif($tipe=='all'){
			unset($_SESSION['type']);				
		 	$cek_tipe ="";

		 }else{
		 	unset($_SESSION['type']);	
		 	$cek_tipe='';
		 }
			$this->load->view('dealer/t_stock_opname_tipe');
	}


	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');		
		$no_mesin		= $this->input->post("no_mesin");

		if(is_array($no_mesin)){
		$stock_opname = 0;
		foreach($no_mesin AS $key => $val){
			$no_mesin 	= $_POST['no_mesin'][$key];
			$no_rangka 	= $_POST['no_rangka'][$key];
			$id_item 	= $_POST['id_item'][$key];		
			$id_warna 	= $_POST['id_warna'][$key];		
			$id_tipe_kendaraan 	= $_POST['id_tipe_kendaraan'][$key];		
				
			$data["id_item"] = $id_item;			
			$data["id_warna"] = $id_warna;			
			$data["id_tipe_kendaraan"] = $id_tipe_kendaraan;			
			$data["no_mesin"] 			= $no_mesin;
			$data["no_rangka"] 			= $no_rangka;
			if (isset($_POST["check_$key"])) {
				$data["checked"] 			= '1';
				$stock_opname++;
			}else{
				$data["checked"] 			= '0';
			}

			$data["status"] 	= 'new';
			$data['created_at']		= $waktu;		
			$data['created_by']		= $login_id;
			/*
			$cek = $this->db->query("SELECT * FROM tr_faktur_stnk_detail WHERE no_mesin = '$no_mesin'");
			if($cek->num_rows() > 0){						
				$this->m_admin->update("tr_faktur_stnk_detail",$data,"no_mesin",$no_mesin);								
			}else{
				$this->m_admin->insert("tr_faktur_stnk_detail",$data);								
			}*/	
				$this->m_admin->insert("tr_stock_opname_detail",$data);		
									
		}	
	}
	$da['created_at']		= $waktu;		
	$da['created_by']		= $login_id;
	$da['tanggal'] 			= $this->input->post('tgl_stock_opname');
	$da['keterangan'] 			= $this->input->post('keterangan');
	$da['qty_on_hand'] 			= count($this->input->post("no_mesin"));
	$da['qty_stock_opname'] 			= $stock_opname;
	$selisih =$da['qty_on_hand'] - $da['qty_stock_opname'] ;
	$da['selisih'] 			= $selisih;
	$da['id_dealer'] 			= $this->m_admin->cari_dealer();
	$this->m_admin->insert("tr_stock_opname",$da);
	$get_max = $this->db->query("SELECT id_stock_opname from tr_stock_opname WHERE created_by='$login_id' order by id_stock_opname desc limit 0,1")->row()->id_stock_opname;
	
	$upd_detail = $this->db->query("UPDATE tr_stock_opname_detail set id_stock_opname='$get_max',status='input' WHERE status='new' AND created_by='$login_id'");

	$_SESSION['pesan'] 	= "Data has been saved successfully";
	$_SESSION['tipe'] 	= "success";		
	echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/stock_opname'>";
	}

	public function update()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');		
		$id_detail		= $this->input->post("id_detail");
		$id_stock_opname		= $this->input->post("id_stock_opname");

		if(is_array($id_detail)){
		$stock_opname = 0;
		foreach($id_detail AS $key => $val){
			if (isset($_POST["check_$key"])) {
				$data["checked"] 			= '1';
				$stock_opname++;
			}else{
				$data["checked"] 			= '0';
			}

			$data["status"] 	= 'update';
			$data['updated_at']		= $waktu;		
			$data['updated_by']		= $login_id;
			/*
			$cek = $this->db->query("SELECT * FROM tr_faktur_stnk_detail WHERE no_mesin = '$no_mesin'");
			if($cek->num_rows() > 0){						
				$this->m_admin->update("tr_faktur_stnk_detail",$data,"no_mesin",$no_mesin);								
			}else{
				$this->m_admin->insert("tr_faktur_stnk_detail",$data);								
			}*/	
		$this->m_admin->update("tr_stock_opname_detail",$data,"id_detail",$id_detail[$key]);	
									
		}	
	}
	$da['created_at']		= $waktu;		
	$da['created_by']		= $login_id;
	$da['tanggal'] 			= $this->input->post('tgl_stock_opname');
	$da['keterangan'] 			= $this->input->post('keterangan');
	$da['qty_on_hand'] 			= count($this->input->post("no_mesin"));
	$da['qty_stock_opname'] 			= $stock_opname;
	$selisih =$da['qty_on_hand'] - $da['qty_stock_opname'] ;
	$da['selisih'] 			= $selisih;
	$da['id_dealer'] 			= $this->m_admin->cari_dealer();
		$this->m_admin->update("tr_stock_opname",$da,"id_stock_opname",$id_stock_opname);	
	$_SESSION['pesan'] 	= "Data has been saved successfully";
	$_SESSION['tipe'] 	= "success";		
	echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/stock_opname'>";
	}

	public function cari_id_bbn(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
				
		$pr_num = $this->db->query("SELECT * FROM tr_monout_piutang_bbn ORDER BY no_rekap DESC LIMIT 0,1");							
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
		$data['updated_by']		= $login_id;			
		$this->m_admin->update("tr_faktur_stnk",$data,"no_bastd",$no_bastd);								
		
		$sql = $this->db->query("SELECT SUM(biaya_bbn) AS jum FROM tr_faktur_stnk_detail WHERE no_bastd = '$no_bastd'")->row();
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
			 	FROM tr_faktur_stnk INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer		 		
		 		WHERE tr_faktur_stnk.no_bastd = '$no_bastd'")->row();        
		
		$s = $this->db->query("SELECT COUNT(no_mesin) AS qty FROM tr_faktur_stnk_detail WHERE no_bastd = '$no_bastd'")->row();          
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
	  $pdf->Cell(50, 5, 'Nama', 1, 0);
	  $pdf->Cell(35, 5, 'No Mesin', 1, 0);
	  $pdf->Cell(35, 5, 'No Rangka', 1, 0);
	  $pdf->Cell(35, 5, 'Biaya BBN (Rp)', 1, 1);	  

	  $pdf->SetFont('times','',10);
	  $get_nosin 	= $this->db->query("SELECT * FROM tr_faktur_stnk_detail INNER JOIN tr_scan_barcode ON tr_faktur_stnk_detail.no_mesin=tr_scan_barcode.no_mesin 
	  		WHERE tr_faktur_stnk_detail.no_bastd = '$no_bastd' ORDER BY tr_scan_barcode.tipe_motor ASC");
	  $i=1;$to=0;	  
	  foreach ($get_nosin->result() as $r)
	  {
	  	$cek_pik = $this->db->query("SELECT tr_faktur_stnk_detail.*,tr_scan_barcode.no_rangka,tr_scan_barcode.tipe_motor,tr_spk.tipe_customer
			 	FROM tr_faktur_stnk_detail INNER JOIN tr_scan_barcode ON tr_faktur_stnk_detail.no_mesin = tr_scan_barcode.no_mesin		 					 	
			 	LEFT JOIN tr_spk ON tr_faktur_stnk_detail.no_spk = tr_spk.no_spk
		 		WHERE tr_faktur_stnk_detail.no_mesin = '$r->no_mesin' ORDER BY tr_faktur_stnk_detail.no_mesin ASC")->row();                		  	
	  	$cek_harga = $this->m_admin->getByID("tr_sales_order","no_mesin",$cek_pik->no_mesin)->row();
	  	// if(isset($cek_pik->no_mesin)){
	  	// 	$cek_harga = $this->m_admin->getByID("ms_bbn_dealer","id_tipe_kendaraan",$cek_pik->tipe_motor);
	  	// 	if($cek_harga->num_rows() > 0){
	  	// 		$m = $cek_harga->row();
	  	// 		if($cek_pik->tipe_customer == 'Customer Umum'){
	  	// 			$harga_m = $m->biaya_bbn;	
	  	// 		}else{
	  	// 			$harga_m = $m->biaya_instansi;	
	  	// 		}	  		
	  	// 	}else{
	  	// 		$harga_m = 0;
	  	// 	}
	  	// }else{
	  	// 	$harga_m = 0;
	  	// }
	  	$harga_m = $cek_harga->biaya_bbn;

	    $pdf->Cell(10, 5, $i, 1, 0);
	    $pdf->Cell(50, 5, $r->nama_konsumen, 1, 0);
	    $pdf->Cell(35, 5, $cek_pik->no_mesin, 1, 0);
	    $pdf->Cell(35, 5, $cek_pik->no_rangka, 1, 0);    	    
	    $pdf->Cell(35, 5, number_format($harga_m, 0, ',', '.'), 1, 1);	    
	  	$i++; 	   		    
	  	$to = $to + $harga_m;
	  }
	  	$pdf->Cell(10, 5, '', 1, 0);
	    $pdf->Cell(50, 5, '', 1, 0);
	    $pdf->Cell(35, 5, '', 1, 0);
	    $pdf->Cell(35, 5, 'Total Biaya BBN', 1, 0);    	    
	    $pdf->Cell(35, 5, number_format($to, 0, ',', '.'), 1, 1);	
	   
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