<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penyerahan_plat extends CI_Controller {

    var $tables =   "tr_penyerahan_plat";	
		var $folder =   "h1";
		var $page		=		"penyerahan_plat";
    var $pk     =   "no_serah_plat";
    var $title  =   "Penyerahan Plat Ke Dealer";


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
		$this->load->library('PDF_HTML');
		$this->load->library('CustomPenyerahanPlat');		




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
		$data['dt_plat']	= $this->db->query("SELECT * FROM tr_penyerahan_plat INNER JOIN ms_dealer ON tr_penyerahan_plat.id_dealer = ms_dealer.id_dealer 
				ORDER BY tr_penyerahan_plat.no_serah_plat DESC");
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
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";		
		$id = $this->input->get('id');
		$data['dt_plat'] = $this->m_admin->getByID("tr_penyerahan_plat","no_serah_plat",$id);
		$this->template($data);			
	}		
// 	public function t_plat(){
// 		$id_dealer = $this->input->post('id_dealer');		
// 		$data['dt_plat'] = $this->db->query("SELECT tr_terima_bj.*,tr_pengajuan_bbn_detail.id_tipe_kendaraan,
// 				tr_pengajuan_bbn_detail.tahun,tr_pengajuan_bbn.id_dealer,ms_tipe_kendaraan.tipe_ahm FROM tr_terima_bj 
// 				INNER JOIN tr_pengajuan_bbn_detail ON tr_terima_bj.no_mesin = tr_pengajuan_bbn_detail.no_mesin
// 				INNER JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd
// 				INNER JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
// 				WHERE tr_pengajuan_bbn.id_dealer = '$id_dealer' AND tr_terima_bj.status_bj = 'input'  AND tr_terima_bj.serah_plat IS NULL");		 		
// 		$this->load->view('h1/t_penyerahan_plat',$data);
// 	}
	
	public function t_plat(){
	$id_dealer = $this->input->post('id_dealer');		
	$data['dt_plat'] = $this->db->query("SELECT tr_entry_stnk.*,tr_pengajuan_bbn_detail.id_tipe_kendaraan,a.status_bj,a.serah_plat,
			tr_pengajuan_bbn_detail.tahun,tr_pengajuan_bbn.id_dealer,ms_tipe_kendaraan.tipe_ahm FROM tr_entry_stnk 
			INNER JOIN tr_pengajuan_bbn_detail ON tr_entry_stnk.no_mesin = tr_pengajuan_bbn_detail.no_mesin
			INNER JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd
			INNER JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN tr_terima_bj a ON tr_entry_stnk.no_mesin=a.no_mesin
			WHERE tr_pengajuan_bbn.id_dealer = '$id_dealer' AND a.status_bj = 'input'  AND a.serah_plat IS NULL");		 		
	$this->load->view('h1/t_penyerahan_plat',$data);
}
	

	
	public function cari_alamat(){
		$id_dealer = $this->input->post('id_dealer');		
		$cari = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer = '$id_dealer'")->row();		 		
		echo $cari->alamat;
	}
	public function cari_id(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
				
		$pr_num = $this->db->query("SELECT * FROM tr_penyerahan_plat ORDER BY no_serah_plat DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_serah_plat)-8;
			$id 	= substr($row->no_serah_plat,$pan,5)+1;	
			if($id < 10){
					$kode1 = $th."/"."0000".$id."/SRP";          					
      }elseif($id>9 && $id<=99){
					$kode1 = $th."/"."000".$id."/SRP";          					
      }elseif($id>99 && $id<=999){
					$kode1 = $th."/"."00".$id."/SRP";          					
      }elseif($id>999){
					$kode1 = $th."/"."0".$id."/SRP";          					
      }
			$kode = $kode1;
		}else{
			$kode = $th."/00001/SRP";
		}						
		return $kode;
	}
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');						
		$no_serah_plat 					= $this->cari_id();
		$da['no_serah_plat'] 		= $no_serah_plat;
		$da['tgl_serah_terima'] = $tgl;				
		$da['id_dealer'] 				= $this->input->post("id_dealer");
		$da['status_plat'] 			= "input";		
		$da['created_at'] 			= $waktu;		
		$da['created_by'] 			= $login_id;		
		
		$jum 										= $this->input->post("jum");		
		for ($i=1; $i <= $jum; $i++) { 
			if(isset($_POST["cek_nosin_".$i])){
				$nosin 								= $_POST["no_mesin_".$i];			
				$data['no_serah_plat'] 		= $no_serah_plat;
				$data['no_mesin'] 		= $nosin;
				$data["status_nosin"] = "input";
				$this->db->query("UPDATE tr_terima_bj SET serah_plat = 'ya' WHERE no_mesin = '$nosin'");										

				$cek = $this->db->query("SELECT * FROM tr_penyerahan_plat_detail WHERE no_mesin = '$nosin'");
				if($cek->num_rows() > 0){						
					$this->m_admin->update("tr_penyerahan_plat_detail",$data,"no_mesin",$nosin);								
				}else{
					$this->m_admin->insert("tr_penyerahan_plat_detail",$data);								
				}
			}			
		}
			
		$ce = $this->db->query("SELECT * FROM tr_penyerahan_plat WHERE no_serah_plat = '$no_serah_plat'");
		if($ce->num_rows() > 0){						
			$this->m_admin->update("tr_penyerahan_plat",$da,"no_serah_plat",$no_serah_plat);								
		}else{
			$this->m_admin->insert("tr_penyerahan_plat",$da);								
		}
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penyerahan_plat'>";
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
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$tgl 				= gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get("id");
		$dt_plat 		= $this->m_admin->getByID("tr_penyerahan_plat","no_serah_plat",$id)->row();
		$dealer 		= $this->m_admin->getByID("ms_dealer","id_dealer",$dt_plat->id_dealer)->row();

		$detail = $this->db->query("SELECT * FROM tr_penyerahan_plat_detail INNER JOIN tr_pengajuan_bbn_detail
        ON tr_penyerahan_plat_detail.no_mesin = tr_pengajuan_bbn_detail.no_mesin 
        WHERE tr_penyerahan_plat_detail.no_serah_plat='$id' ORDER BY tr_pengajuan_bbn_detail.nama_konsumen ASC");		
		$jum = $detail->num_rows();

		global $nomor,$lamp,$nama_dealer,$alamat,$tanggal,$ambilY;
		$pdf = $this->custompenyerahanplat->getInstance();
		$nomor 				= $dt_plat->no_serah_plat;
		$lamp 				= $jum;
		$nama_dealer 	= $dealer->nama_dealer;
		$alamat 			= $dealer->alamat;	
		$tanggal 			= date_dmy($dt_plat->tgl_serah_terima);	
	
		$pdf->AliasNbPages();
		$pdf->AddPage('P', 'A4');
		$pdf->SetAutoPageBreak(true, 52);				

		$pdf->SetFont('times', '', 11);			
		$pdf->SetWidths(array(10,80,35,35,30));
		srand(microtime()*1000000);
		if ($detail->num_rows()>0) {
			$no=1;
			foreach ($detail->result() as $key => $rs) {
				 $rt = $this->db->query("SELECT tr_terima_bj.*,tr_pengajuan_bbn_detail.id_tipe_kendaraan,
                            tr_pengajuan_bbn_detail.tahun,tr_pengajuan_bbn.id_dealer,ms_tipe_kendaraan.tipe_ahm,tr_pengajuan_bbn_detail.no_faktur FROM tr_terima_bj 
                            INNER JOIN tr_pengajuan_bbn_detail ON tr_terima_bj.no_mesin = tr_pengajuan_bbn_detail.no_mesin
                            INNER JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd
                            INNER JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                            WHERE tr_terima_bj.no_mesin = '$rs->no_mesin'")->row();				 
				$pdf->Row(array($no,$rs->nama_konsumen,$rs->no_mesin,$rt->no_plat,$rt->no_faktur));    								
				$no++;
			}
		}			
		$pdf->Output();
	}


	public function cetak_ulang()
	{
		$id = $this->input->get('id');
		$dt_plat = $this->m_admin->getByID("tr_penyerahan_plat","no_serah_plat",$id)->row();
		$dealer = $this->m_admin->getByID("ms_dealer","id_dealer",$dt_plat->id_dealer)->row();

		$pdf = new PDF_HTML('P','mm','A4');
		$pdf->SetLeftMargin(7);
        $pdf->AddPage();
        //$detail = $this->db->query("SELECT * FROM tr_penyerahan_plat_detail WHERE no_serah_plat='$id' ");
        $detail = $this->db->query("SELECT tr_penyerahan_plat_detail.no_mesin FROM tr_penyerahan_plat_detail 
        	INNER JOIN tr_entry_stnk ON tr_penyerahan_plat_detail.no_mesin = tr_entry_stnk.no_mesin
        	WHERE tr_penyerahan_plat_detail.no_serah_plat='$id' ORDER BY tr_entry_stnk.nama_konsumen ASC");
        $jum = $detail->num_rows();
        $pdf->SetFont('TIMES','',12);
		$pdf->Cell(35,6,'Nomor',0,0,'L');
		$pdf->Cell(65,6,': '.$dt_plat->no_serah_plat,0,0,'L');
		$pdf->Cell(35,6,'JAMBI',0,1,'L');
		$pdf->Cell(35,6,'LAMPIRAN',0,0,'L');
		$pdf->Cell(65,6,": $jum plat",0,0,'L');
		$pdf->Cell(35,6,'Kepada Yth.',0,1,'L');
		$pdf->Cell(35,6,'Perihal',0,0,'L');
		$pdf->Cell(65,6,": PENYERAHAN PLAT HONDA",0,0,'L');
		$pdf->Cell(35,6,$dealer->nama_dealer,0,1,'L');
		$pdf->Cell(100,6,'',0,0,'L');
		$pdf->Multicell(95,6,$dealer->alamat,0,1);
		$pdf->Ln(7);
		$pdf->Cell(35,6,'Dengan Hormat,',0,1,'L');

		$pdf->Multicell(190,6,"Dengan ini kami serahkan kepada Bapak/Ibu, PLAT HONDA penjualan $dealer->nama_dealer sebanyak $jum plat dengan keterangan sbb :",0);
		$pdf->Ln(3);
		$pdf->Cell(10,6,'NO.',1,0,'C');
		$pdf->Cell(80,6,'NAMA KONSUMEN',1,0,'C');
		// $pdf->Cell(28,6,'NO. MESIN',1,0,'C');
		// $pdf->Cell(27,6,'NO. POLISI',1,0,'C');
		$pdf->Cell(35,6,'NO. MESIN',1,0,'C');
		$pdf->Cell(35,6,'NO. POLISI',1,0,'C');
		$pdf->Cell(33,6,'NO. FAKTUR',1,1,'C');
        $pdf->SetFont('TIMES','',11);
		
		if ($detail->num_rows()>0) {
			$no=1;
			foreach ($detail->result() as $key => $rs) {
				 $rt = $this->db->query("SELECT tr_entry_stnk.*,tr_pengajuan_bbn_detail.id_tipe_kendaraan,
                            tr_pengajuan_bbn_detail.tahun,tr_pengajuan_bbn.id_dealer,ms_tipe_kendaraan.tipe_ahm,tr_pengajuan_bbn_detail.no_faktur FROM tr_entry_stnk 
                            INNER JOIN tr_pengajuan_bbn_detail ON tr_entry_stnk.no_mesin = tr_pengajuan_bbn_detail.no_mesin
                            INNER JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd
                            INNER JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                            WHERE tr_entry_stnk.no_mesin = '$rs->no_mesin'")->row();
				$pdf->Cell(10,6,$no,1,0,'C');
				$pdf->Cell(80,6,' '.$rt->nama_konsumen,1,0,'L');
				// $pdf->Cell(28,6,$rs->no_mesin,1,0,'C');
				// $pdf->Cell(27,6,$rt->no_plat,1,0,'C');
				$pdf->Cell(35,6,$rs->no_mesin,1,0,'C');
				$pdf->Cell(35,6,$rt->no_plat,1,0,'C');
				$pdf->Cell(33,6,$rt->no_faktur,1,1,'C');
				$no++;
			}
		}
		$pdf->Ln(4);
		$pdf->setX(140);
		$tgl = date('d-m-Y', strtotime(date('y-m-d'))); 
		$pdf->Cell(130,6,'Jambi, '.$tgl,0,1,'L');
		$pdf->setX(10);
		// $pdf->Cell(130,6,'YANG MENERIMA',0,0,'L');
		// $pdf->Cell(97.5,6,'YANG MENGIRIM',0,0,'L');
		$pdf->Ln(4);
		
		$pdf->Cell(63.3,6,'YANG MENYETUJUI',0,0,'C');
		$pdf->Cell(63.3,6,'YANG MENGIRIM',0,0,'C');
		$pdf->Cell(63.3,6,'YANG MENERIMA',0,0,'C');
		$pdf->Ln(30);
		$pdf->Cell(63.3,6,'(Drs. Tony Attan, SH)',0,0,'C');
		$pdf->Cell(63.3,6,'(Admin Plat)',0,0,'C');
		$pdf->Cell(63.3,6,"($dealer->nama_dealer)",0,0,'C');
		$pdf->Output(); 
	}

}