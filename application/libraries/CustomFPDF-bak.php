<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH.'/libraries/fpdf/fpdf.php';
class CustomFPDF2 extends FPDF{
    var $widths;
    var $aligns;
function __construct($orientation='L', $unit='mm', $size=array(165,210))
{
    parent::__construct($orientation,$unit,$size);
}
public function mata_uang2($a){
if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
    return number_format($a, 0, ',', '.');
}
public function Header()
{
    global $tgl_entry,$bank,$no_bg,$tgl_bayar,$dealer;
    $this->SetFont('TIMES', '', 11);    
    $this->Cell(118, 9, '', 0, 1);    
    $this->Cell(110, 5, '', 0, 0);    
    $this->Cell(25, 5, 'Tgl Entry ', 0, 0);
    $this->Cell(30, 5, ': ' . $tgl_entry . '', 0, 1);
    $this->Cell(110, 5, '', 0, 0);    
    $this->Cell(25, 5, 'Bank ', 0, 0);
    $this->MultiCell(40, 5, ': ' . $bank . '', 0, 1);
    $this->Cell(110, 5, '', 0, 0);    
    $this->Cell(25, 5, 'No BG ', 0, 0);
    $this->Cell(35, 5, ': ' . $no_bg . '', 0, 1);
    $this->Cell(110, 5, '', 0, 0);    
    $this->Cell(25, 5, 'Tgl Bayar ', 0, 0);
    $this->Cell(30, 5, ': ' . $tgl_bayar . '', 0, 1);


    $this->SetFont('TIMES', 'B', 12);
    $this->Cell(10, 3, '', 0, 1);
    $this->Cell(190, 17, $dealer, 0, 1, 'C');        
    $this->Ln(5);
}

// Page footer
function Footer()
{
    global $terbilang,$nom,$coa,$bank2,$jum,$qq;    
    // Position at 1.5 cm from bottom
    $this->SetY(-49);        

    $x = $this->x;
    $y = $this->y;
    $push_right = 0;
    $this->SetFont('TIMES', '', 9);    
    $this->Cell(20, 5, '', 0, 0);        
    $this->MultiCell($w = 125, 5, ucwords($terbilang).' Rupiah', 0,'L', 0);    
    $push_right += $w;$this->SetXY($x + $push_right, $y);
    $this->SetFont('TIMES', '', 10);        
    $this->MultiCell($w = 60,3,"Rp.".$nom,0,'R',0);    
    $this->Cell(0, 10, '', 0, 1);            
    // for ($i=1; $i <= $jum; $i++) {         
    // }
    foreach ($qq as $key) {        
        $this->Cell(55, 5, $key->coa, 0, 0);
        $this->Cell(30, 5, ': Rp.'.$this->mata_uang2($key->nominal), 0, 1);
    }
    $this->Cell(55, 5, $bank2, 0, 0);
    $this->Cell(30, 5, ': Rp.'.$nom, 0, 1);
    // Page number
    $this->Cell(0, 5, '', 0, 1);        
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
function tes($value){
    return $value;
}              
public function getInstance(){
    return new CustomFPDF2();
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
    $h=5*$nb;
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
        //$this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,5,$data[$i],0,$a);
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