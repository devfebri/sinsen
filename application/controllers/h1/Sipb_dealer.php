<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sipb_dealer extends CI_Controller {

    var $tables =   "tr_sipb_dealer";	
		var $folder =   "h1";
		var $page		=		"sipb_dealer";
    var $pk     =   "id_sipb_dealer";
    var $title  =   "Surat Izin Pengeluaran Barang";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('csvimport');
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
		$data['dt_sipb'] = $this->db->query("SELECT * FROM tr_sipb_dealer ORDER BY id_sipb_dealer DESC"); 
		$this->template($data);	
	}

	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";		
		$this->template($data);		
	}
	
	public function t_sipb(){
		$no_sj 	= $this->input->post('no_sj');		
		$dq 		= $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan 						
						WHERE tr_surat_jalan.no_surat_jalan = '$no_sj' AND tr_surat_jalan_detail.ceklist = 'ya'");
		$data['dt_sj'] 	= $dq;
		$data['no_sj']	= $this->input->post('no_sj');								
		$this->load->view('h1/t_sipb',$data);			
	}
	public function cari_lain(){
		$no_sj = $this->input->post('no_sj');
		$dq = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN ms_dealer ON tr_surat_jalan.id_dealer=ms_dealer.id_dealer						
						WHERE tr_surat_jalan.no_surat_jalan = '$no_sj'")->row();
		echo "ok|".$dq->tgl_surat."|".$dq->id_dealer."|".$dq->nama_dealer."|".$dq->alamat;
	}
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$no_sj			= $this->input->post("no_sj");
		
		$data['no_surat_jalan'] 	= $no_sj;
		$data['tgl_surat_izin']  	= $this->input->post('tgl_sipb_dealer');		
		$data['id_dealer'] 				= $this->input->post('id_dealer');
		$data['no_polisi'] 				= $this->input->post('no_polisi');
		$data['warehouse_head'] 	= $this->input->post('warehouse_head');	
		$data['security']	 				= $this->input->post('security');	
		$data['nama_supir'] 			= $this->input->post('nama_supir');	
		$data['status'] 					= "input"; 			  	
		$data['created_at']				= $waktu;		
		$data['created_by']				= $login_id;	
		$this->m_admin->insert("tr_sipb_dealer",$data);

		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/sipb_dealer'>";		
		
	}

	public function cetak()
	{
		$id = $this->input->get('id');
		$data['isi']    = $this->page;	
		//$data['dt_sipb']	= $this->db->query("SELECT * from ");

		$pdf = new FPDF('p','mm','A5');
    	$pdf->AddPage();
		$pdf->SetMargins(4,1);
		  $pdf->SetFont('ARIAL','B',11);
		  $pdf->Cell(50, 4, '', 0, 1, 'C');
		  $pdf->SetXY(5,5); 
 
			$pdf->MultiCell(139, 50, '', 1,0);
		  $pdf->SetXY(5,9); 
		  $pdf->Cell(139, 2, 'FORM CEKLIST PERLENGKAPAN EKSPEDISI / PICK UP', 0, 1, 'C');
		  $pdf->SetFont('ARIAL','',11);

		  $pdf->SetXY(8,15); 
		  $pdf->Cell(25, 5, 'Tangal', 0, 0, 'L');
		  $pdf->Cell(20, 5, ': ', 0, 1, 'L'); 
		  $pdf->SetX(8); 
		  $pdf->Cell(25, 5, 'Dealer', 0, 0, 'L');
		  $pdf->Cell(20, 5, ': ', 0, 1, 'L');
		  $pdf->SetX(8); 

		   $pdf->Cell(25, 5, 'No. Pol', 0, 0, 'L');

		  $pdf->Cell(20, 5, ': ', 0, 1, 'L');
		  $pdf->SetX(8); 

		   $pdf->Cell(25, 5, 'Jumlah Unit', 0, 0, 'L');
		  $pdf->Cell(20, 5, ': ', 0, 1, 'L');

		/*  $pdf->Cell(50, 1, '', 0, 1, 'C');
		  $pdf->SetFont('ARIAL','B',11);
		  $pdf->Cell(140, 2, 'FORM CEKLIST PERLENGKAPAN EKSPEDISI / PICK UP', 0, 1, 'C');
		  $pdf->Cell(50, 4, '', 0, 1, 'C');
		   
		  $pdf->SetFont('ARIAL','',10);
		  $pdf->Cell(50, 5, 'Tanggal', 0, 0);
		  $pdf->Cell(65, 5, ':  No-sj', 0, 0);

		  $pdf->Cell(25, 5, 'No DO', 0, 0);
		  $pdf->SetFont('ARIAL','',11);
		  $pdf->Cell(50, 5, ':  ', 0, 1);
		  $pdf->SetFont('ARIAL','B',11);
	   // buat tabel disini

	  
	   
	   // kasi jarak
	  $pdf->SetFont('ARIAL','B',14);
	  $pdf->Cell(1,1,'',0,1);
	  $pdf->Cell(190, 9, 'Detail KSU', 0, 1,'C');

	  $pdf->SetFont('ARIAL','B',10);
	  $pdf->Cell(20, 7, 'No.', 1, 0,'C');
	  $pdf->Cell(130, 7, 'Nama Aksesoris', 1, 0,'C');
	  $pdf->Cell(40, 7, 'Qty', 1, 1 ,'C');
	  $pdf->SetFont('ARIAL','',10);
	  $pdf->SetFont('ARIAL','B',11);
	  $pdf->Cell(150, 8, 'Total : ', 0, 1, 'R');	  
	   // tanda tangan
	  $pdf->Cell(10, 3, '', 0, 1);

	  $pdf->SetFont('ARIAL','B',11);	  
	  $pdf->Cell(110, 5, 'Pengirim', 0, 0);
	  $pdf->Cell(35, 5, 'Ekspedisi', 0, 0,'C');
	  $pdf->Cell(35, 5, 'Diterima', 0, 1,'C');
	  $pdf->Cell(10, 16, '', 0, 1);
	  $pdf->SetFont('ARIAL','',11);	   
	  $pdf->Cell(55, 5, '(Kepala Gudang)', 0, 0);
	  $pdf->Cell(55, 5, '(                         )', 0, 0);
	  $pdf->Cell(35, 5, '(                         )', 0, 0,'C');
	  $pdf->Cell(35, 5, '(                         )', 0, 1,'C');

	  $pdf->SetFont('ARIAL','',10);	  
	  $pdf->Cell(10, 7, '', 0, 1,'L');
	  $pdf->Cell(10, 5, 'Note', 0, 1,'L');
	  $pdf->Cell(10, 5, '* Bubuhkan Nama dan Tanda Tangan yang jelas', 0, 1,'L');
	  $pdf->Cell(10, 5, '* Dikirim dalam keadaan baik, lengkap dan baru', 0, 1,'L');
	  //$pdf->Multicell(10, 5, '* Klaim kerusakan / kehilangan barang kiriman dibenarkan bila ada laporan dari petugas kami \n yang ikut menyerahkan barang', 0, 1,'L');
	   $pdf->Multicell(190,5,"* Klaim kerusakan / kehilangan barang kiriman dibenarkan bila ada laporan dari petugas kami yang ikut \n  menyerahkan barang",0,"L"); */
	  $pdf->Output(); 
	}
}