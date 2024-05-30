<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'/libraries/fpdf/fpdf.php';

class CustomFPDF extends FPDF{



function __construct($orientation='P', $unit='mm', $size='Legal')

{

    parent::__construct($orientation,$unit,$size);

}

public function Header()

{

    global $no_surat_jalan,$tgl_surat,$no_do,$tgl_do,$gudang,$source,$driver,$nama_dealer,$no_plat,$alamat,$ket,$no_faktur;    

    $this->SetFont('TIMES', '', 20);

    $this->Cell(190, 20, '', 0, 1, 'C');    

    $this->Cell(190, 15, 'SURAT JALAN', 0, 1, 'C');    

    $this->SetFont('TIMES', '', 12);

    $this->Cell(50, 5, 'Main Dealer: PT.Sinar Sentosa Primatama', 0, 1, 'L');

    $this->Cell(50, 5, 'Jl.Kolonel Abunjani No.09 Jambi', 0, 1, 'L');

    $this->Cell(50, 5, 'Telp: 0741-61551', 0, 1, 'L');

    $this->Line(11, 61, 200, 61);



    //$this->Image(base_url().'/assets/panel/images/logo_sinsen.jpg', 150, 15, 50);



    $this->SetFont('TIMES', '', 12);

    $this->Cell(1, 2, '', 0, 1);

    $this->Cell(30, 5, 'No SJ ', 0, 0);

    $this->Cell(70, 5, ': ' . $no_surat_jalan . '', 0, 0);



    $this->Cell(30, 5, 'No DO ', 0, 0);

    $this->Cell(10, 5, ': ' . $no_do . '', 0, 1);



    $this->Cell(30, 5, 'Tgl SJ', 0, 0);

    $this->Cell(70, 5, ': ' . $tgl_surat . '', 0, 0);



    $this->Cell(30, 5, 'Tgl DO ', 0, 0);

    $this->Cell(10, 5, ': ' . $tgl_do . '', 0, 1);



    $this->Cell(30, 5, 'Gudang ', 0, 0);

    $this->Cell(70, 5, ': ' . $gudang . '', 0, 0);



    $this->Cell(30, 5, 'Tipe PO ', 0, 0);

    $this->Cell(10, 5, ': ' . strtoupper(str_replace("_", " ", $source)) . '', 0, 1);



    $this->Cell(30, 5, 'No Faktur ', 0, 0);

    $this->Cell(70, 5, ': ' . $no_faktur . '', 0, 0);

    $this->Cell(30, 5, 'Nama Driver ', 0, 0);

    $this->Cell(20, 5, ': ' . $driver, 0, 1);



    $this->Cell(100, 5, '', 0, 0);



    $this->Cell(30, 5, 'Penerima ', 0, 0);

    $this->MultiCell(70, 5, ': ' . $nama_dealer . '', 0, "L");





    $this->Cell(30, 5, 'No Polisi ', 0, 0);

    $this->Cell(10, 5, ': ' . $no_plat, 0, 1);



    $this->Cell(100, 5, 'Alamat Penerima :' . $alamat . '', 0, 1);



    $this->Cell(30, 5, 'Keterangan ', 0, 0);

    $this->Cell(100, 5, ': ' . $ket . '', 0, 1);

    $this->Cell(190, 2, '', 'B', 1);

    $this->Ln(5);

}



// Page footer

function Footer()

{

    // Position at 1.5 cm from bottom

    $this->SetY(-85);    

    // tanda tangan

    $this->Cell(9, 3, '', 5, 10);

    $this->SetFont('TIMES', '', 12);

    $this->Cell(10, 5, '', 0, 1);

    $this->Cell(70, 5, 'Diserahkan Oleh', 0, 0, 'C');

    $this->Cell(40, 5, 'Driver', 0, 0, 'C');

    $this->Cell(40, 5, 'Diperiksa Oleh', 0, 0, 'C');

    $this->Cell(40, 5, 'Diterima Oleh', 0, 1, 'C');

    $this->Cell(10, 15, '', 0, 1);

    $this->Cell(35, 5, '(Kepala Logistik)', 0, 0, 'C');

    $this->Cell(35, 5, '(Admin Warehouse)', 0, 0, 'C');

    $this->Cell(40, 5, '(                            )', 0, 0, 'C');

    $this->Cell(40, 5, '(      Security      )', 0, 0, 'C');

    $this->Cell(40, 5, '(                            )', 0, 1, 'C');

    // $this->Cell(35, 5, 'Kepala Gudang', 0, 0,'C');      

    // $this->Cell(35, 5, 'Admin', 0, 0,'C');      

    // $this->Cell(40, 5, 'Driver', 0, 0,'C');     

    // $this->Cell(40, 5, 'Security', 0, 0,'C');       

    // $this->Cell(40, 5, '', 0, 1,'C');





    $this->Cell(10, 5, '', 0, 1);



    $this->SetFont('TIMES', '', 9);

    $this->Cell(10, 3, 'Catatan', 0, 1, 'L');

    $this->Cell(10, 3, '* Bubuhkan Nama dan Tanda Tangan yang jelas', 0, 1, 'L');

    $this->Cell(10, 3, '* Dikirim dalam keadaan baik, lengkap, dan baru', 0, 1, 'L');

    $this->Cell(10, 3, '* Barang yang telah diperiksa dan diterima, menjadi tanggung jawab Penerima apabila ada kerusakan atau kehilangan.', 0, 1, 'L');

    $this->Cell(10, 3, '* Driver wajib memeriksa dan menerima barang dalam kondisi baik dan lengkap.', 0, 1, 'L');

    // Page number

    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');

}

function tes($value){

    return $value;

}              

public function getInstance(){

    return new CustomFPDF();

}

}