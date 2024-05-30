<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembayaran_claim extends CI_Controller
{
  var $folder = "dealer";
  var $page   = "pembayaran_claim_dealer";
  var $title  = "Pembayaran Claim Dealer";
  var $tables = "tr_claim_sales_program_payment_generate";	

  public function __construct()
  {
    parent::__construct();
    //---- cek session -------//		
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    }
    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
  }
  
  protected function template($data)
  {
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    } else {
      $this->load->view('template/header', $data);
      $this->load->view('template/aside');
      $page = $this->page;
      $this->load->view($this->folder . "/" . $page);
      $this->load->view('template/footer');
    }
  }

  public function index()
  {
    // $id_group    = $this->m_admin->cari_dealer_group();
    $id_dealer    = $this->m_admin->cari_dealer();
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $data['folder'] = $this->folder;
    $data['set']    = "view";
    $data['container']   = "index";

    $data['pembayaran_claim_dealer'] =  $this->db->query("SELECT a.*, b.nama_dealer from tr_claim_sales_program_payment_generate a
		join ms_dealer b on a.id_dealer = b.id_dealer 
		where  a.id_dealer='$id_dealer' and  a.status in ('input','approved','send')  AND a.approve_payment_dealer_created is NULL
    ORDER by created_at DESC 
    ")->result();

	// $data['pembayaran_claim_dealer'] =  $this->db->query("SELECT a.*,b.nama_dealer FROM tr_claim_sales_program_payment_generate a join ms_dealer b on a.id_dealer =b.id_dealer where b.id_dealer='$id_dealer'  and a.send_payment_created is not null ")->result();
	// $data['pembayaran_claim_dealer'] =  $this->db->query("SELECT a.id_claim_generate_payment, a.tgl_transaksi_claim,a.id_dealer, a.priode_program,b.nama_dealer, 
	// sum(c.tot_approve)  AS total_approve, sum(c.tot_reject) as total_reject ,
	// sum(c.total_kontribusi_ahm) as total_kontribusi_ahm , 
	// sum(c.total_kontribusi_md) as total_kontribusi_md, 
	// sum(c.total_kontribusi_d) total_kontribusi_d, 
	// ((c.total_kontribusi_d +  c.total_kontribusi_md +  c.total_kontribusi_ahm) * c.tot_approve) as total_pembayaran , 
	// ((c.total_kontribusi_d +  c.total_kontribusi_md +  c.total_kontribusi_ahm) * c.tot_reject  ) as total_full_reject,
	// c.jenis_pembayaran,
	// sum(c.total_pembayaran) as total_pembayaran, 
	// a.status, a.tgl_pencairan_md_ke_d, c.include_ppn 
	// FROM tr_claim_sales_program_payment_generate a join ms_dealer b on a.id_dealer = b.id_dealer
	// left join tr_claim_sales_program_payment_generate_detail c on a.id_claim_generate_payment = c.id_claim_generate_payment 
	// WHERE a.status in ('input','approved') group by c.id_claim_generate_payment  order by a.created_at DESC")->result();
   
    $this->template($data);
  }

  
  public function history()
  {
    // $id_dealer    = $this->m_admin->cari_dealer();
    $id_dealer    = $this->m_admin->cari_dealer();
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['folder'] = $this->folder;
    $data['set']   = "view";
    $data['container']   = "history";
    // $data['pembayaran_claim_dealer'] =  $this->db->query("SELECT a.*, b.nama_dealer  from tr_claim_sales_program_payment_generate a
		// join ms_dealer b on a.id_dealer = b.id_dealer where a.id_dealer='$id_dealer' AND  a.status = 'paid' ORDER by created_at DESC ")->result();

      $data['pembayaran_claim_dealer'] =  $this->db->query("SELECT a.*, b.nama_dealer from tr_claim_sales_program_payment_generate a
      join ms_dealer b on a.id_dealer = b.id_dealer 
      where  a.id_dealer='$id_dealer' and  a.status in ('input','approved','send')  AND a.approve_payment_dealer_created is NOT NULL
      ORDER by created_at DESC 
      ")->result();
    $this->template($data);
  }


  public function detail_claim()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail_claim";
		$id = $this->input->get('id');

    $detail_claim = $this->db->query("SELECT csp.*,
		case when csp.jenis_pembayaran  = 'scp' then csp.total_pembayaran  else  0  end as total_pembayaran_md_ke_d,
		case when csp.jenis_pembayaran  = 'dg'  then csp.total_pembayaran  else  0  end as total_pembayaran_d_ke_md,
		md.nama_dealer  from tr_claim_sales_program_payment_generate_detail csp
		left join ms_dealer md on md.id_dealer = csp.id_dealer   where csp.id_claim_generate_payment ='$id'");
        
		$data['header_claim'] = $this->db->query("SELECT a.tgl_transaksi_claim ,b.nama_dealer,a.priode_program,a.id_dealer, a.status, a.send_payment_created ,a.approve_payment_dealer_created ,a.reject_payment_dealer_created,a.include_ppn ,a.id_bank, c.bank, a.tipe_program
		FROM tr_claim_sales_program_payment_generate a 
		join ms_dealer b on a.id_dealer =b.id_dealer 
		left join ms_bank c on a.id_bank = c.id_bank 
		where a.id_claim_generate_payment ='$id' group by a.id_claim_generate_payment  order by a.created_at DESC")->row();
		
		if ($detail_claim->num_rows() > 0) {
			$data['detail_claim'] = $detail_claim->result();
		}else{
			$data['detail_claim'] = '';
		}
		$this->template($data);			
	}



  public function claim_approve()
	{		
		$generate= $this->input->get('id_generate');

    $tabel	= $this->tables;	
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view";
		$pk     = "id_claim_generate_payment";
		$update['status'] = 'approved';
		$update['approve_payment_dealer_created'] = gmdate("y-m-d h:i:s", time()+60*60*7);
		$this->m_admin->update($tabel,$update,$pk,$generate);
    echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/pembayaran_claim'>";
		// $this->template($data);			
	}

  
  public function payment()
	{		
		$generate= $this->input->get('id_generate');
    $tabel	= $this->tables;	
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view";
		$pk     = "id_claim_generate_payment";
		$update['status'] = 'paid';
		$update['tgl_pencairan_md_ke_d'] = gmdate("y-m-d h:i:s", time()+60*60*7);
		$this->m_admin->update($tabel,$update,$pk,$generate);
    echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/pembayaran_claim'>";
	}

  

	public function cetak_kwitansi()
	{
		$this->load->helper("terbilang");
	    $tgl = date('d F Y', strtotime(date('y-m-d'))); 
		$terbilang = number_to_words("10080000");
		$id_dealer = '103';
		$tgl1 ='2023-03-01';
		$tgl2 ='2023-03-31';

		$pdf = new PDF_HTML('P','mm','A4');
		$pdf->SetLeftMargin(7);
		$pdf->AddPage();
			
		$dealer = $this->db->query("SELECT ms_dealer.nama_dealer,ms_kabupaten.kabupaten FROM ms_dealer INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan 
		INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
		WHERE ms_dealer.id_dealer = '$id_dealer'")->row();
        
    	$pdf->SetFont('TIMES','',12);
		$pdf->Cell(35,6,'Tanggal : '.$tgl,0,1,'L');
		$pdf->Cell(35,6,'',0,1,'L');
		
		$pdf->Cell(35,6,'Tanggal Entry : '.$tgl,0,1,'L');
		$pdf->Cell(35,6,'Bank : ' ,0,1,'L');
		$pdf->Cell(35,6,'Tanggal Bayar :',0,1,'L');


		$pdf->Cell(35,6,'',0,1,'L');
		$pdf->Cell(35,6,'Bukti Pengeluran Kas/Bank :',0,1,'');

		$pdf->Cell(196,6,'Dibayarkan Kepada :','LTRB',1,'C');
		$pdf->Cell(196,6,'Penggantian Claim Program Penjualan Periode :','LTRB',1,'L');

		$pdf->Cell(40,6,'No. Juklak ','LTRB',0,'L');
		$pdf->Cell(30,6,'Type ','LTRB',0,'L');
		$pdf->Cell(30,6,'Unit Apporove ','LTRB',0,'L');
		$pdf->Cell(30,6,'Kontribusi Unit ','LTRB',0,'L');
		$pdf->Cell(30,6,'Nilai Kontribusi','LTRB',0,'L');
		$pdf->Cell(36,6,'Total','LTRB',1,'L');

		$pdf->Cell(196,10,'Terbilang : '.$terbilang.' Rupiah','LTR',1,'');

		$pdf->Cell(61,6,'Keterangan ','LTR',0,'C');
		$pdf->Cell(45,6,'Disetujui ','LTR',0,'C');
		$pdf->Cell(45,6,'Dibayar ','LTR',0,'C');
		$pdf->Cell(45,6,'Diterima ','LTR',1,'C');

		$pdf->Cell(61,30,' ','LRB',0,'C');
		$pdf->Cell(45,30,' ','LRB',0,'C');
		$pdf->Cell(45,30,' ','LRB',0,'C');
		$pdf->Cell(45,30,' ','LRB',0,'C');

		$pdf->Cell(35,30,'',0,1,'L');
		$pdf->Ln(4);
		
		$pdf->setX(10);
		$pdf->Ln(4);
		
		$pdf->Cell(63.3,6,'Hormat Kami',0,0,'C');
		$pdf->Ln(30);
		$pdf->Cell(63.3,6,'FEBRIANA',0,1,'C');
		$pdf->Cell(63.3,6,'Finance Head',0,0,'C');
		$pdf->Output(); 
	}







}
