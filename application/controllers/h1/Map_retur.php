<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Map_retur extends CI_Controller {

    var $tables =   "tr_map_retur";	
		var $folder =   "h1";
		var $page		=		"map_retur";
    var $pk     =   "no_map_retur";
    var $title  =   "Map Retur";

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
		$data['dt_retur']	= $this->db->query("SELECT * FROM tr_map_retur LEFT JOIN ms_dealer ON tr_map_retur.id_dealer = ms_dealer.id_dealer");
		$this->template($data);			
	}
	public function terima()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "terima";				
		$id = $this->input->get('id');		
		$data['dt_retur']	= $this->db->query("SELECT * FROM tr_map_retur WHERE no_map_retur = '$id'");
		$this->template($data);			
	}	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$data['dt_dealer'] = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");
		$this->template($data);			
	}
	public function t_data(){
		$id_dealer = $this->input->post('id_dealer');		
		$tgl_retur = $this->input->post('tgl_retur');		
		$data['dt_retur'] = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail INNER JOIN tr_faktur_stnk ON tr_pengajuan_bbn_detail.no_bastd = tr_faktur_stnk.no_bastd
			WHERE tr_faktur_stnk.id_dealer = '$id_dealer' AND tr_pengajuan_bbn_detail.reject = 'ya' AND (tr_pengajuan_bbn_detail.cetak != 'ya' OR tr_pengajuan_bbn_detail.cetak IS NULL)");
		$this->load->view('h1/t_map_retur',$data);
	}
	public function cari_id(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
				
		$pr_num = $this->db->query("SELECT * FROM tr_map_retur ORDER BY no_map_retur DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_map_retur)-8;
			$id 	= substr($row->no_map_retur,$pan,5)+1;	
			if($id < 10){
					$kode1 = $th."/"."0000".$id."/RM";          					
      }elseif($id>9 && $id<=99){
					$kode1 = $th."/"."000".$id."/RM";          					
      }elseif($id>99 && $id<=999){
					$kode1 = $th."/"."00".$id."/RM";          					
      }elseif($id>999){
					$kode1 = $th."/"."0".$id."/RM";          					
      }
			$kode = $kode1;
		}else{
			$kode = $th."/00001/RM";
		}						
		return $kode;
	}
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');						
		$no_map_retur 					= $this->cari_id();
		$da['no_map_retur'] 		= $no_map_retur;
		$da['tgl_retur'] 				= $this->input->post("tgl_retur");
		$da['id_dealer'] 				= $this->input->post("id_dealer");
		$da['status'] 					= "input";		
		$da['created_at'] 			= $waktu;		
		$da['created_by'] 			= $login_id;		
		
		$jum 	= $this->input->post("jum");		
		$isi 	= 0;
		for ($i=1; $i <= $jum; $i++) { 
			if(isset($_POST["cek_retur_".$i])){
				$nosin 									= $_POST["no_mesin_".$i];			
				$data['no_map_retur'] 	= $no_map_retur;
				$data['no_mesin'] 			= $nosin;
				$data["status_nosin"] 	= "input";				

				$cek = $this->db->query("SELECT * FROM tr_map_retur_detail WHERE no_mesin = '$nosin'");
				if($cek->num_rows() > 0){						
					$this->m_admin->update("tr_map_retur_detail",$data,"no_mesin",$nosin);								
				}else{
					$this->m_admin->insert("tr_map_retur_detail",$data);								
				}
				$isi++;
			}			
		}
		$da['jumlah_unit'] 				= $isi;
		$ce = $this->db->query("SELECT * FROM tr_map_retur WHERE no_map_retur = '$no_map_retur'");
		if($ce->num_rows() > 0){						
			$this->m_admin->update("tr_map_retur",$da,"no_map_retur",$no_map_retur);								
		}else{
			$this->m_admin->insert("tr_map_retur",$da);								
		}
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/map_retur'>";
	}
	public function save_terima()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');								
		$no_map_retur 					= $this->input->post("id_dealer");		
		$da['status'] 					= "terima";		
		$da['updated_at'] 			= $waktu;		
		$da['updated_by'] 			= $login_id;		
		
		$jum 	= $this->input->post("jum");				
		for ($i=1; $i <= $jum; $i++) { 
			if(isset($_POST["cek_retur_".$i])){
				$nosin 									= $_POST["no_mesin_".$i];							
				$data["status_nosin"] 	= "terima";								
				$this->m_admin->update("tr_map_retur_detail",$data,"no_mesin",$nosin);																
				$this->db->query("UPDATE tr_pengajuan_bbn_detail SET reject = '' WHERE no_mesin = '$nosin'");										
			}			
		}		
		$this->m_admin->update("tr_map_retur",$da,"no_map_retur",$no_map_retur);												
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/map_retur'>";
	}			
	
	public function cetak_tenda_terima()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "cetak_terima";				
		$this->template($data);			
	}	

	public function cetak()
	{
		$id = $this->input->get('id');
		// $dt_plat = $this->m_admin->getByID("tr_penyerahan_plat","no_serah_plat",$id)->row();
		$dt_plat = $this->m_admin->getByID("tr_map_retur","no_map_retur",$id)->row();
		$dealer = $this->m_admin->getByID("ms_dealer","id_dealer",$dt_plat->id_dealer)->row();


		$pdf = new FPDF('p','mm','A4');
		$pdf->AddPage();
       // head
		$pdf->SetFont('ARIAL','B',14);
		// $pdf->Cell(190, 9, 'RETUR MAP', 1, 1, 'C');

		// $pdf->SetY(25);
		$pdf->SetFont('ARIAL','',11); 
		$pdf->Cell(35,5, 'Jambi, '.date('d-m-Y'),0,1,'L'); 
		$pdf->Cell(35,5, 'Kepada YTH :',0,2,'L');
		$pdf->Cell(190,5, strtoupper($dealer->nama_dealer),0,1,'L');
		$pdf->Cell(190,5, 'NOMOR : '.$id,0,1,'L');
		
		// $pdf->Cell(90,5, '',0,1,'L');
		// $pdf->Cell(35,5, 'Kepada Yth',0,1,'L');
		// $pdf->Cell(35,5, 'Kepala Bagian STNK',0,1,'L');
		// $pdf->Cell(35,5, 'Di PT. Sinar Sentosa Primatama',0,1,'L');
		// $pdf->Cell(90,5, '',0,1,'L');
		$pdf->Cell(35,5, 'Dengan Hormat',0,1,'L');
		$pdf->Cell(190,5, 'Berikut kami kirimkan map BBN dikarenakan persyaratan map tidak lengkap.',0,1,'L');
		$pdf->Cell(190,5, 'Dengan rincian sebagai berikut :',0,1,'L');
		$pdf->Cell(35,3, '',0,1,'L');
		$pdf->Cell(15,5, 'No.',1,0,'C');
		$pdf->Cell(65,5, 'Nama Konsumen',1,0,'C');
		$pdf->Cell(45,5, 'No. Mesin',1,0,'C');
		$pdf->Cell(60,5, 'Keterangan',1,1,'C');
		$no = 1;
		$dt_b = $this->db->get_where('tr_map_retur_detail', ['no_map_retur'=> $id]);
		foreach ($dt_b->result() as $val) {
			$pdf->Cell(15,5, $no,1,0,'C');
            $rt = $this->db->query("SELECT tr_map_retur_detail.*,tr_pengajuan_bbn_detail.id_tipe_kendaraan,tr_pengajuan_bbn_detail.nama_konsumen,tr_pengajuan_bbn_detail.nama_konsumen,tr_pengajuan_bbn_detail.kekurangan,
                tr_pengajuan_bbn_detail.tahun,tr_pengajuan_bbn.id_dealer,ms_tipe_kendaraan.tipe_ahm FROM tr_map_retur_detail 
                INNER JOIN tr_pengajuan_bbn_detail ON tr_map_retur_detail.no_mesin = tr_pengajuan_bbn_detail.no_mesin
                INNER JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd
                INNER JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                WHERE tr_map_retur_detail.no_mesin = '$val->no_mesin'")->row();
  //           $pdf->Cell(11,5, $no,1,0,'C');
		// 	$pdf->Cell(51,5, $dealer->nama_dealer,1,0,'C');
		// 	$pdf->Cell(60,5, $rt->nama_konsumen,1,0,'C');
			$pdf->Cell(65,5, $rt->nama_konsumen,1,0,'L');             
			$pdf->Cell(45,5, $rt->no_mesin,1,0,'L');
			$pdf->Cell(60,5, $rt->kekurangan,1,1,'L');
            $no++;
            }
		$pdf->Cell(190,5, 'Map tersebut diatas telah kami kambalikan melalui :',0,1,'L');
		$pdf->Cell(9,5, '',1,0,'L');$pdf->Cell(9,5, 'Ekspedisi :...........................',0,1,'L');
		$pdf->Cell(9,2, '',0,1,'L');
		$pdf->Cell(9,5, '',1,0,'L');$pdf->Cell(9,5, 'Diambil sendiri. Nama Penerima :...........................',0,1,'L');
		$pdf->Cell(9,9, '',0,1,'L');
		$pdf->Cell(190,5, 'Demikian Memo Retur ini kami sampaikan. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.',0,1,'L');
		$pdf->Cell(95,5, 'Dibuat Oleh,',0,0,'L');
		$pdf->Cell(95,5, 'Mengetahui',0,1,'L');
		$pdf->Cell(95,20, '',0,1,'L');
		$pdf->Cell(95,5, 'Adm. Faktur,',0,0,'L');
		$pdf->Cell(95,5, 'Manager Keuangan,',0,1,'L');
		$pdf->Cell(190,5, '',0,1,'L');
		
		$pdf->Cell(190,5, '1. Memo Retur ini sebagi bukti yang syah untuk pengurangan terhadap biaya BBN yang telah dibayarkan',0,1,'L');
		$pdf->Cell(190,5, '2. Pada saat pengiriman map ulang tetap di daftarkan di surat pengantar BBn atau seperti pengajuan baru lagi',0,1,'L');

		$pdf->Cell(190,5, '',0,1,'L');
		$pdf->Cell(190,5, 'Cc :',0,1,'L');
		$pdf->Cell(190,5, '1. Asli untuk Kepala Bagian Keuangan PT. SSP',0,1,'L');
		$pdf->Cell(190,5, '2. Copy untuk Dealer',0,1,'L');



		// $pdf->Cell(35,3, '',0,1,'L');
		// $pdf->Cell(190,5, 'Demikian kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.',0,1,'L');
		// $pdf->Cell(35,3, '',0,1,'L');
		// $pdf->Cell(190,5, '=====================================================================================',0,1,'C');
		// $pdf->Cell(35,3, '',0,1,'L');
		// $pdf->SetFont('ARIAL','B',11);
		// $pdf->Cell(190,5, 'Plat Nomor Polisi Kendaraan telah diterima oleh Pihak PT.Sinar Sentosa Primatama',0,1,'C');
		// $pdf->Cell(35,3, '',0,1,'L');
		// $pdf->SetFont('ARIAL','',11);
		// $pdf->Cell(95,5, 'Yang Menyerahkan',0,0,'L');
		// $pdf->Cell(95,5, 'Yang Menerima',0,1,'L');
		// $pdf->Cell(30,5, 'Nama',0,0,'L');
		// $pdf->Cell(65,5, ': _______________________',0,0,'L');
		// $pdf->Cell(30,5, 'Nama',0,0,'L');
		// $pdf->Cell(65,5, ': _______________________',0,1,'L');
		// $pdf->Cell(30,5, 'Tanggal',0,0,'L');
		// $pdf->Cell(65,5, ': _______________________',0,0,'L');
		// $pdf->Cell(30,5, 'Tanggal',0,0,'L');
		// $pdf->Cell(65,5, ': _______________________',0,1,'L');
		// $pdf->Cell(30,5, 'Jam',0,0,'L');
		// $pdf->Cell(65,5, ': ____________________WIB',0,0,'L');
		// $pdf->Cell(30,5, 'Jam',0,0,'L');
		// $pdf->Cell(65,5, ': ____________________WIB',0,1,'L');
		// $pdf->Cell(65,20, '',0,1,'L');
		// $pdf->Cell(30,5, 'TTD',0,0,'L');
		// $pdf->Cell(65,5, ': _______________________',0,0,'L');
		// $pdf->Cell(30,5, 'TTD',0,0,'L');
		// $pdf->Cell(65,5, ': _______________________',0,1,'L');

	  	$pdf->Output(); 

	}

}