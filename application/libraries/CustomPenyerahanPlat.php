<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'/libraries/fpdf/fpdf.php';

class CustomPenyerahanPlat extends FPDF{



    var $widths;

    var $aligns;



function __construct($orientation='P', $unit='mm', $size='A4')

{

    parent::__construct($orientation,$unit,$size);

}

public function Header()

{

    global $nomor,$lamp,$nama_dealer,$alamat,$tanggal;

    $this->SetFont('TIMES', '', 11); 

    // 

    $this->Cell(25, 5, '', 0, 0);

    $this->Cell(60, 5, '', 0, 0);    

    $this->Cell(60, 5, '', 0, 0);

    $this->Cell(60, 5, 'Tanggal :  ' .$tanggal . '', 0, 1);

    // 

    $this->Cell(25, 5, 'Nomor ', 0, 0);

    $this->Cell(60, 5, ': ' . $nomor . '', 0, 0);    

    $this->Cell(10, 5, '', 0, 0);

    $this->Cell(60, 5, 'JAMBI ', 0, 1);

    $this->Cell(25, 5, 'Lampiran ', 0, 0);

    $this->Cell(60, 5, ': ' . $lamp . ' Buku', 0, 0);    

    $this->Cell(10, 5, '', 0, 0);

    $this->Cell(60, 5, 'Kepada Yth, ', 0, 1);

    $this->Cell(25, 5, 'Perihal ', 0, 0);

    $this->Cell(60, 5, ': PENYERAHAN PLAT HONDA', 0, 0);

    $this->Cell(10, 5, ' ', 0, 0);   

    $this->Cell(60, 5, $nama_dealer,0,1,'L');

    $this->Cell(95,5, '',0,0,'L');

    $this->Multicell(95,6,$alamat,0,1); 

    $this->Cell(25, 5, 'Dengan Hormat, ', 0, 1);    

    $this->Multicell(190,6,'Dengan ini kami serahkan kepada Bapak/Ibu, PLAT HONDA penjualan '.$nama_dealer.' sebanyak '.$lamp.' buku dengan keterangan sbb :',0);

    $this->Ln(5);

    $this->Cell(10,6,'NO.',1,0,'C');

    $this->Cell(80,6,'NAMA KONSUMEN',1,0,'C');

    $this->Cell(35,6,'NO. MESIN',1,0,'C');

    $this->Cell(35,6,'NO. POLISI',1,0,'C');    

    $this->Cell(30,6,'NO. FAKTUR',1,1,'C');

}



// Page footer

function Footer()

{    

    global $nama_dealer,$ambilY;

    $this->SetY(-54);            

    $this->Cell(65,6,'YANG MENYETUJUI',0,0,'C');

    $this->Cell(65,6,'YANG MENGIRIM',0,0,'C');

    $this->Cell(65,6,'YANG MENERIMA',0,1,'C');

    $this->Cell(30,18,'',0,1,'C');

    $this->Cell(65,6,'(Fermawati)',0,0,'C');

    $this->Cell(65,6,'(Admin Plat)',0,0,'C');

    $this->Cell(65,6,'('.$nama_dealer.')',0,0,'C');

    // Page number

    $this->Cell(0, 5, '', 0, 1);        

    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');

}            

public function getInstance(){

    return new CustomPenyerahanPlat();

}

function SetWidths($w)

{

    //Set the array of column widths

    $this->widths=$w;

}



function SetAligns($a)

{

    //Set the array of column alignments

    $this->aligns=$a;

}



function Row($data)

{

    //Calculate the height of the row

    $nb=0;

    for($i=0;$i<count($data);$i++)

        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));

    $h=6*$nb;

    //Issue a page break first if needed

    $this->CheckPageBreak($h);

    //Draw the cells of the row

    for($i=0;$i<count($data);$i++)

    {

        $w=$this->widths[$i];

        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';

        //Save the current position

        $x=$this->GetX();

        $y=$this->GetY();

        //Draw the border

        $this->Rect($x,$y,$w,$h);

        //Print the text

        $this->MultiCell($w,6,$data[$i],0,$a);

        //Put the position to the right of the cell

        $this->SetXY($x+$w,$y);

    }

    //Go to the next line

    $this->Ln($h);

}



function CheckPageBreak($h)

{

    //If the height h would cause an overflow, add a new page immediately

    if($this->GetY()+$h>$this->PageBreakTrigger)

        $this->AddPage($this->CurOrientation);

}



function NbLines($w,$txt)

{

    //Computes the number of lines a MultiCell of width w will take

    $cw=&$this->CurrentFont['cw'];

    if($w==0)

        $w=$this->w-$this->rMargin-$this->x;

    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;

    $s=str_replace("\r",'',$txt);

    $nb=strlen($s);

    if($nb>0 and $s[$nb-1]=="\n")

        $nb--;

    $sep=-1;

    $i=0;

    $j=0;

    $l=0;

    $nl=1;

    while($i<$nb)

    {

        $c=$s[$i];

        if($c=="\n")

        {

            $i++;

            $sep=-1;

            $j=$i;

            $l=0;

            $nl++;

            continue;

        }

        if($c==' ')

            $sep=$i;

        $l+=$cw[$c];

        if($l>$wmax)

        {

            if($sep==-1)

            {

                if($i==$j)

                    $i++;

            }

            else

                $i=$sep+1;

            $sep=-1;

            $j=$i;

            $l=0;

            $nl++;

        }

        else

            $i++;

    }

    return $nl;

}

}