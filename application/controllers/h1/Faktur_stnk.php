<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faktur_stnk extends CI_Controller {

  var $tables =   "tr_faktur_stnk";	
	var $folder =   "dealer";
	var $page		=		"faktur_stnk";
  var $pk     =   "id_faktur_stnk";
  var $title  =   "Faktur STNK";

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
		$data['dt_faktur'] = $this->db->query("SELECT * FROM tr_faktur_stnk WHERE id_dealer = '$id_dealer' limit 100");				
		$this->template($data);			
	}
	public function konfirmasi()
	{				
		$id    					= $this->input->get("id");		
		$data['isi']    = $this->page;		
		$data['title']	= "Konfirmasi ".$this->title;															
		$data['set']		= "konfirmasi";
		$data['dt_faktur'] = $this->m_admin->getByID($this->tables,"no_bastd",$id);				
		$this->template($data);			
	}
	public function detail()
	{				
		$id    					= $this->input->get("id");		
		$data['isi']    = $this->page;		
		$data['title']	= "Detail ".$this->title;															
		$data['set']		= "detail";
		$no_bastd 			= $this->input->get('id');				
		$data['dt_stnk'] = $this->db->query("SELECT * FROM tr_faktur_stnk_detail WHERE no_bastd = '$no_bastd'");		 		
		$data['dt_faktur'] = $this->m_admin->getByID($this->tables,"no_bastd",$id);				
		$this->template($data);			
	}
	public function cari_id(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
				
		$pr_num = $this->db->query("SELECT * FROM tr_faktur_stnk ORDER BY no_bastd DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_bastd)-11;
			$id 	= substr($row->no_bastd,$pan,5)+1;	
			if($id < 10){
					$kode1 = $th."/"."0000".$id."/BASTD";          					
      }elseif($id>9 && $id<=99){
					$kode1 = $th."/"."000".$id."/BASTD";          					
      }elseif($id>99 && $id<=999){
					$kode1 = $th."/"."00".$id."/BASTD";          					
      }elseif($id>999){
					$kode1 = $th."/"."0".$id."/BASTD";          					
      }
			$kode = $kode1;
		}else{
			$kode = $th."/00001/BASTD";
		}						
		return $kode;
	}
	public function t_stnk(){
		$start_date = $this->input->post('start_date');
		$end_date 	= $this->input->post('end_date');
		$id_dealer 	= $this->m_admin->cari_dealer(); 
		$data['dt_stnk'] = $this->db->query("SELECT * FROM tr_sales_order 
				WHERE tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' 
				AND id_dealer = '$id_dealer' AND status_so = 'so_invoice' 
				AND tr_sales_order.id_sales_order NOT IN (SELECT id_sales_order FROM tr_faktur_stnk_detail WHERE id_sales_order IS NOT NULL)");		 
		$data['status'] = "input";
		$this->load->view('dealer/t_stnk',$data);
	}
	public function t_stnk_detail(){
		$no_bastd = $this->input->post('no_bastd');				
		$data['dt_stnk'] = $this->db->query("SELECT * FROM tr_faktur_stnk_detail INNER JOIN tr_sales_order ON tr_faktur_stnk_detail.no_spk = tr_sales_order.no_spk 
			WHERE tr_faktur_stnk_detail.no_bastd = '$no_bastd'");		 
		$data['status'] = "detail";
		$this->load->view('dealer/t_stnk',$data);
	}	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";					
		$this->template($data);										
	}
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');		
		$no_mesin		= $this->input->post("no_mesin");
		$no_bastd 	= $this->cari_id();
		$da['no_bastd'] 			= $no_bastd;
		$da['tgl_bastd'] 			= $tgl;
		$da['start_date'] 		= $this->input->post("start_date");
		$da['end_date'] 			= $this->input->post("end_date");
		$da['id_dealer'] 			= $this->m_admin->cari_dealer();
		$da['status_faktur'] 	= $this->input->post("mode");


		$data['no_bastd'] 		= $no_bastd;
		if(is_array($no_mesin)){
		foreach($no_mesin AS $key => $val){
			$no_mesin 	= $_POST['no_mesin'][$key];
			$no_rangka 	= $_POST['no_rangka'][$key];
			$nama_konsumen 	= $_POST['nama_konsumen'][$key];
			$alamat 		= $_POST['alamat'][$key];			
			
			$data["biaya_bbn"] 			= $_POST['biaya_bbn'][$key];			
			$data["harga_unit"]			= $_POST['harga_unit'][$key];			
			$data["no_spk"] 				= $_POST['no_spk'][$key];			
			$data["id_sales_order"] = $_POST['id_sales_order'][$key];			
			$data["no_mesin"] 			= $no_mesin;
			$data["no_rangka"] 			= $no_rangka;
			$data["nama_konsumen"] 	= $nama_konsumen;
			$data["alamat"] 				= $alamat;
			if(isset($_POST['check_ktp'][$key])){
				$data["ktp"] = "ya";									
			}else{
				$data["ktp"] 		= "tidak";									
			}
			if(isset($_POST['check_fisik'][$key])){
				$data["fisik"] = "ya";									
			}else{
				$data["fisik"] 		= "tidak";									
			}
			if(isset($_POST['check_stnk'][$key])){
				$data["stnk"] = "ya";									
			}else{
				$data["stnk"] 		= "tidak";									
			}
			if(isset($_POST['check_bpkb'][$key])){
				$data["bpkb"] = "ya";									
			}else{
				$data["bpkb"] 		= "tidak";									
			}
			if(isset($_POST['check_kuasa'][$key])){
				$data["kuasa"] = "ya";									
			}else{
				$data["kuasa"] 		= "tidak";									
			}
			if(isset($_POST['check_ckd'][$key])){
				$data["ckd"] = "ya";									
			}else{
				$data["ckd"] 		= "tidak";									
			}
			if(isset($_POST['check_permohonan'][$key])){
				$data["permohonan"] = "ya";									
			}else{
				$data["permohonan"] 		= "tidak";									
			}
			
			$cek = $this->db->query("SELECT * FROM tr_faktur_stnk_detail WHERE no_mesin = '$no_mesin'");
			if($cek->num_rows() > 0){						
				$this->m_admin->update("tr_faktur_stnk_detail",$data,"no_mesin",$no_mesin);								
			}else{
				$this->m_admin->insert("tr_faktur_stnk_detail",$data);								
			}	
									
		}	
	}
		
		$this->m_admin->insert("tr_faktur_stnk",$da);								
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/faktur_stnk'>";
	}
	public function cetak(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$no_bastd 			= $this->input->get('id');			

		$data['status_faktur'] = "proses";		
		$data['updated_at']		= $waktu;		
		$data['updated_by']		= $login_id;			
		$this->m_admin->update("tr_faktur_stnk",$data,"no_bastd",$no_bastd);								
		

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
	  $pdf->Cell(50, 5, 'Jambi, '.date('d/m/Y').'', 0, 1, 'L');
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
	  $pdf->MultiCell(190,5, 'Bersama dengan surat ini kami dari '.$get_stnk->nama_dealer.' mengirimkan map untuk proses BBN sebanyak 14 unit dengan perincian sebagai berikut '.$jum.' unit dengan perincian sebagai berikut unit dengan perincian sebagai berikut  :', 0, 1);	  
	  
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
		 		WHERE tr_faktur_stnk_detail.no_mesin = '$r->no_mesin'")->row();                		  	
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
	  $pdf->Cell(30, 5, 'Bank Rekening ', 0, 0);	  	  
	  $pdf->Cell(70, 5, ': ', 0, 1);	  	  
	  $pdf->Cell(30, 5, 'Bank ', 0, 0);	  	  
	  $pdf->Cell(70, 5, ': ', 0, 1);	  	  
	  $pdf->Cell(30, 5, 'Tanggal Transfer ', 0, 0);	  	  
	  $pdf->Cell(70, 5, ': ', 0, 1);	  	  

	  $pdf->MultiCell(190,5, 'Demikian Surat pengantar ini untuk diproses BBN ini kami sampaikan. Atas perhatian dan kerjasamanya kami ucapkan terima kasih', 0, 1);	  

	  $pdf->Cell(50, 5, 'Dibuat Oleh', 0, 0,'C');	  
	  $pdf->Cell(50, 5, 'Mengetahui', 0, 1,'C');	  	  
	  $pdf->Cell(10, 8, '', 0, 0);	  	  
	  $pdf->Cell(10, 10, '', 0, 1);
	  $pdf->Cell(10, 5, '', 0, 1);	  
	  $pdf->SetFont('TIMES','',8);	  
	  $pdf->Cell(10, 3, 'Catatan', 0, 1,'L');
	  $pdf->Cell(10, 3, '1. Pengisian daftar map harus diurutkan sesuai Tipe Motor', 0, 1,'L');
	  $pdf->Cell(10, 3, '2. Ujung kanan map harus dibuat nomor sesuai dengan nama dalam surat', 0, 1,'L');
	  $pdf->Cell(10, 3, '3. Map yang dikirim harus telah lengkap sesuai dengan persyaratan yang berlaku', 0, 1,'L');
	  $pdf->Cell(10, 3, '4. Fotocopy bukti transfer harap dilampirkan', 0, 1,'L');
	  
	  $pdf->Cell(70, 5, '=======================================================================================================================', 0, 1);	  	  
	  
	  $pdf->SetFont('TIMES','',10);
	  $pdf->Cell(195, 1, 'Map telah diterima oleh pihak PT. Sinar Sentosa Primatama', 0, 1, 'C');	  
	  $pdf->Cell(50, 5, '1. Bagian Keuangan', 0, 0,'C');	  
	  $pdf->Cell(50, 5, '2. Bagian Faktur', 0, 1,'C');	  	  
	  $pdf->Cell(60, 5, 'Nama : __________________', 0, 0);	  	  
	  $pdf->Cell(10, 5, 'Nama : __________________', 0, 1);	  	  
	  $pdf->Cell(60, 5, 'Tanggal : ________Jam:____WIB', 0, 0);	  	  
	  $pdf->Cell(10, 5, 'Tanggal : ________Jam:____WIB', 0, 1);	  	  
	  $pdf->Cell(10, 10, '', 0, 1);
	  $pdf->Cell(60, 5, 'TTD : ___________________', 0, 0);	  	  
	  $pdf->Cell(10, 5, 'TTD : ___________________', 0, 1);	  	  


	  $pdf->Output(); 
	}
}