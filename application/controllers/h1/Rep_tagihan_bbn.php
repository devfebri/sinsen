<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_tagihan_bbn extends CI_Controller {
	
	var $folder =   "h1/report";
	var $page		=		"rep_tagihan_bbn";	
	var $isi		=		"laporan_6";	
	var $title  =   "Report Tagihan BBN";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====		
		$this->load->library('PDF_HTML');
		$this->load->library('PDF_HTML_Table');
		$this->load->library('cfpdf');
		$this->load->helper('terbilang');
		$this->load->library('mpdf_l');
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
    function mata_uang3($a){
      if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
        if(is_numeric($a) AND $a != 0 AND $a != ""){
          return number_format($a, 0, ',', '.');
        }else{
          return $a;
        }        
    }
	public function index()
	{						
		$data['isi']    = $this->isi;		
		$data['title']	= $this->title;												
		$data['dt_dealer'] = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1");
		$data['set']		= "view";						
		$this->template($data);		    	    
	}
	public function filter()
	{						
		$data['isi']    = $this->isi;		
		$data['title']	= $this->title;	
		$data['id_dealer'] = $id_dealer = $this->input->post("id_dealer");
		$data['tgl1'] = $tgl1 = $this->input->post("tgl1");
		$data['tgl2'] = $tgl2 = $this->input->post("tgl2");
		$data['dt_dealer'] = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1");
		$data['dt_bastd'] = $this->db->query("SELECT tr_pengajuan_bbn_detail.no_bastd,tr_faktur_stnk.tgl_bastd,count(tr_pengajuan_bbn_detail.no_mesin) as jum,sum(tr_pengajuan_bbn_detail.biaya_bbn) as nominal 
		    FROM tr_pengajuan_bbn_detail 
		    INNER JOIN tr_faktur_stnk ON tr_pengajuan_bbn_detail.no_bastd = tr_faktur_stnk.no_bastd 
		    WHERE tr_pengajuan_bbn_detail.tgl_mohon_samsat BETWEEN '$tgl1' AND '$tgl2' 
		    AND tr_faktur_stnk.id_dealer = '$id_dealer'
		    GROUP BY tr_pengajuan_bbn_detail.no_bastd");
		$data['set']		= "filter";						
		$this->template($data);		    	    
	}
	public function download(){
	    $jml 			= $this->input->post('jml');	
		for ($i=1; $i <= $jml; $i++) { 									
			$data2["id_instrumen"] = $id_instrumen = $_POST["id_instrumen_".$i];									
			if(isset($_POST["check_".$i])){
				$data2["check"] = $_POST["check_".$i];									
			}else{
				$data2["check"] = 0;
			}
			$cek = $this->db->query("SELECT * FROM bn_spt_detail WHERE no_spt = '$no_spt' AND id_instrumen = '$id_instrumen'");
			if($cek->num_rows() > 0){						
				$this->m_admin->update("bn_spt_detail",$data2,"id_spt_detail",$cek->row()->id_spt_detail);								
			}else{
				$this->m_admin->insert("bn_spt_detail",$data2);												
			}		
		}
	}
	public function cetak_kuitansi()
	{
		$pdf = new PDF_HTML('L','mm',array('160','200'));
		$pdf->SetLeftMargin(7);
        $pdf->AddPage();
        
        $pdf->SetFont('TIMES','',12);
		$pdf->Ln(4);
		$pdf->Cell(15,10,'No.',0,0,'L');
		$pdf->Cell(35,10,': ___________________________',0,1,'L');
		$pdf->Cell(35,10,'',0,1,'L');
		$pdf->Cell(35,10,'Telah Diterima dari',0,0,'L');
		$pdf->Cell(35,10,': ______________________________________________________',0,1,'L');
		$pdf->Cell(35,10,'Uang Sejumlah',0,0,'L');
		$pdf->Cell(35,10,': ______________________________________________________',0,1,'L');
		$pdf->Cell(35,10,'Untuk Pembayaran',0,0,'L');
		$pdf->Cell(35,10,': ______________________________________________________',0,1,'L');
		$pdf->Cell(35,10,'',0,0,'L');
		$pdf->Cell(35,10,' ______________________________________________________',0,1,'L');
		$pdf->Cell(35,10,'',0,0,'L');
		$pdf->Cell(35,10,' ______________________________________________________',0,1,'L');
		$pdf->Cell(35,10,'',0,1,'L');
		$pdf->Cell(100,10,'',0,0,'L');
		$pdf->Cell(85,10,'_____________________',0,1,'L');
		$pdf->Cell(35,10,'',0,1,'L');
		$pdf->Cell(35,5,'_____________________',0,1,'L');
		$pdf->Cell(35,10,'',0,1,'L');
		$pdf->Cell(35,10,'_____________________',0,0,'L');
		$pdf->Cell(65,6,'',0,0,'L');
		$pdf->Cell(85,6,'Finance Head',0,1,'L');
		$pdf->Output(); 
	}



	public function aksi()
	{
	    $tgl = date('d F Y', strtotime(date('y-m-d'))); 
		$id_dealer = $this->input->post('id_dealer');
		$tgl1 = $this->input->post('tgl1');
		$tgl2 = $this->input->post('tgl2');

		$pdf = new PDF_HTML('P','mm','A4');
		$pdf->SetLeftMargin(7);
        $pdf->AddPage();
        
        $dealer = $this->db->query("SELECT ms_dealer.nama_dealer,ms_kabupaten.kabupaten FROM ms_dealer INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan 
            INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
            INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
            WHERE ms_dealer.id_dealer = '$id_dealer'")->row();
        
        
        $pdf->SetFont('TIMES','',12);
		$pdf->Cell(35,6,'Jambi, '.$tgl,0,1,'L');
		$pdf->Cell(35,6,'',0,1,'L');
		$pdf->Cell(35,6,'Kepada Yth,',0,1,'L');
		$pdf->Cell(35,6,$dealer->nama_dealer,0,1,'L');
		$pdf->Cell(65,6,$dealer->kabupaten,0,1,'L');
		$pdf->Cell(35,6,"Perihal :",0,0,'L');
		$pdf->Cell(100,6,'Tagihan BBN',0,1,'L');
		$pdf->Cell(35,6,'',0,1,'L');
		$pdf->Multicell(190,6,"Melalui surat ini kami kirimkan tagihan BBN tanggal $tgl1 s/d $tgl2 surat permohonan Bapak/Ibu dengan perincian sebagai berikut:",0,1);
		
		$pdf->Ln(3);$tot=0;
		$jml = $this->input->post('jml');	
		for ($i=1; $i <= $jml; $i++) { 									
			$no_bastd = $_POST["no_bastd_".$i];									
			$dt_bastd = $this->db->query("SELECT tr_pengajuan_bbn_detail.no_bastd,tr_faktur_stnk.tgl_bastd,count(tr_pengajuan_bbn_detail.no_mesin) as jum,sum(tr_pengajuan_bbn_detail.biaya_bbn) as nominal 
    		    FROM tr_pengajuan_bbn_detail 
    		    INNER JOIN tr_faktur_stnk ON tr_pengajuan_bbn_detail.no_bastd = tr_faktur_stnk.no_bastd 
    		    WHERE tr_pengajuan_bbn_detail.no_bastd = '$no_bastd'");
		    $isi_bastd = ($dt_bastd->num_rows() > 0) ? $dt_bastd->row()->no_bastd : "";
		    $nominal = ($dt_bastd->num_rows() > 0) ? $dt_bastd->row()->nominal : "";
		    $jum = ($dt_bastd->num_rows() > 0) ? $dt_bastd->row()->jum : "";
			if(isset($_POST["check_".$i])){
				$pdf->Cell(20,6,'',0,0,'L');
				$pdf->Cell(100,6,$i.'. '.$isi_bastd,0,0,'L');
				$pdf->Cell(30,6,'Rp. '.$this->mata_uang3($nominal),0,0,'R');
				$pdf->Cell(10,6,$jum,0,1,'R');
				$tot += $nominal;
			}
		}
		$pdf->Cell(120,6,'',0,0,'L');
		$pdf->Cell(30,6,'___________________',0,1,'L');
		$pdf->Cell(20,6,'',0,0,'L');
	    $pdf->Cell(100,6,'Total',0,0,'L');
		$pdf->Cell(30,6,'Rp. '.$this->mata_uang3($tot),0,1,'R');
		$pdf->Cell(20,6,'',0,0,'L');
	    $pdf->Cell(100,6,'Biaya Materi',0,0,'L');
		$pdf->Cell(30,6,'Rp. '.$this->mata_uang3(6000),0,1,'R');
		$pdf->Cell(20,6,'',0,0,'L');
	    $pdf->Cell(100,6,'Total Bayar',0,0,'L');
		$pdf->Cell(30,6,'Rp. '.$this->mata_uang3($tot + 6000),0,1,'R');
		
		
		
		$pdf->Cell(35,6,'',0,1,'L');
		$pdf->Multicell(190,6,"Sebagai bahan pertimbangan maka kami lampirkan:",0,1);
		$pdf->Cell(100,6,'1. Kwitansi Bermaterai 1 Lembar',0,1,'L');
		$pdf->Cell(100,6,'2. Fotocopy surat permohonan dari TDM',0,1,'L');
		$pdf->Cell(35,6,'',0,1,'L');
		$pdf->Multicell(190,6,"Besar harapan kami tagihan BBN  tersebut dapat segera diproses dan ditransfer secepatnya ke rekening kami sbb :",0,1);
		$pdf->Cell(15,6,'',0,0,'L');
		$pdf->Cell(20,6,'Atas Nama',0,0,'L');
		$pdf->Cell(20,6,': PT. SINAR SENTOSA PRIMATAMA',0,1,'L');
		$pdf->Cell(15,6,'',0,0,'L');
		$pdf->Cell(20,6,'Bank',0,0,'L');
		$pdf->Cell(20,6,': BCA Cab. Jambi',0,1,'L');
		$pdf->Cell(15,6,'',0,0,'L');
		$pdf->Cell(20,6,'A/C',0,0,'L');
		$pdf->Cell(20,6,': 7870900800',0,1,'L');
		$pdf->Cell(35,6,'',0,1,'L');
		$pdf->Cell(15,6,'NB : ',0,0,'L');
		$pdf->Cell(100,6,'Tagihan ini mohon dibantu proses pembayarannya paling lambat satu minggu ',0,1,'L');
		$pdf->Cell(15,6,'',0,0,'L');
		$pdf->Cell(100,6,'setelah tanggal tagihan ini, apabila dalam jangka waktu tersebut pembayaran  ',0,1,'L');
		$pdf->Cell(15,6,'',0,0,'L');
		$pdf->Cell(100,6,'belum kami terima maka kami tidak bisa memproses pengajuan BBN berikutnya.',0,1,'L');
		$pdf->Ln(4);
		
		$pdf->setX(10);
		// $pdf->Cell(130,6,'YANG MENERIMA',0,0,'L');
		// $pdf->Cell(97.5,6,'YANG MENGIRIM',0,0,'L');
		$pdf->Ln(4);
		
		$pdf->Cell(63.3,6,'Hormat Kami',0,0,'C');
		$pdf->Ln(30);
		$pdf->Cell(63.3,6,'FEBRIANA',0,1,'C');
		$pdf->Cell(63.3,6,'Finance Head',0,0,'C');
		$pdf->Output(); 
	}
}