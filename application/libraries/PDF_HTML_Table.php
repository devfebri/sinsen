<?php
//Based on HTML2PDF by Cl�ment Lavoillotte
require_once APPPATH.'/libraries/fpdf/fpdf.php';
//function hex2dec
//returns an associative array (keys: R,G,B) from a hex html code (e.g. #3FE5AA)
function hex2dec($couleur = "#000000"){
	$R = substr($couleur, 1, 2);
	$rouge = hexdec($R);
	$V = substr($couleur, 3, 2);
	$vert = hexdec($V);
	$B = substr($couleur, 5, 2);
	$bleu = hexdec($B);
	$tbl_couleur = array();
	$tbl_couleur['R']=$rouge;
	$tbl_couleur['G']=$vert;
	$tbl_couleur['B']=$bleu;
	return $tbl_couleur;
}

//conversion pixel -> millimeter in 72 dpi
function px2mm($px){
	return $px*25.4/72;
}

function txtentities($html){
	$trans = get_html_translation_table(HTML_ENTITIES);
	$trans = array_flip($trans);
	return strtr($html, $trans);
}
////////////////////////////////////

class PDF_HTML_Table extends FPDF
{
//variables of html parser
protected $B;
protected $I;
protected $U;
protected $HREF;
protected $fontList;
protected $issetfont;
protected $issetcolor;


protected $T128;                                         // Tableau des codes 128
    protected $ABCset = "";                                  // jeu des caract�res �ligibles au C128
    protected $Aset = "";                                    // Set A du jeu des caract�res �ligibles
    protected $Bset = "";                                    // Set B du jeu des caract�res �ligibles
    protected $Cset = "";                                    // Set C du jeu des caract�res �ligibles
    protected $SetFrom;                                      // Convertisseur source des jeux vers le tableau
    protected $SetTo;                                        // Convertisseur destination des jeux vers le tableau
    protected $JStart = array("A"=>103, "B"=>104, "C"=>105); // Caract�res de s�lection de jeu au d�but du C128
    protected $JSwap = array("A"=>101, "B"=>100, "C"=>99);   // Caract�res de changement de jeu
    

function __construct($orientation='P', $unit='mm', $format='A4')
{
	//Call parent constructor
	parent::__construct($orientation,$unit,$format);

	//Initialization
	$this->B=0;
	$this->I=0;
	$this->U=0;
	$this->HREF='';

	$this->tableborder=0;
	$this->tdbegin=false;
	$this->tdwidth=0;
	$this->tdheight=0;
	$this->tdalign="L";
	$this->tdbgcolor=false;

	$this->oldx=0;
	$this->oldy=0;

	$this->fontlist=array("arial","times","courier","helvetica","symbol");
	$this->issetfont=false;
	$this->issetcolor=false;
}

//////////////////////////////////////
//html parser

function WriteHTML($html)
{
	$html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote><hr><td><tr><table><sup>"); //remove all unsupported tags
	$html=str_replace("\n",'',$html); //replace carriage returns with spaces
	$html=str_replace("\t",'',$html); //replace carriage returns with spaces
	$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //explode the string
	foreach($a as $i=>$e)
	{
		if($i%2==0)
		{
			//Text
			if($this->HREF)
				$this->PutLink($this->HREF,$e);
			elseif($this->tdbegin) {
				if(trim($e)!='' && $e!="&nbsp;") {
					$this->Cell($this->tdwidth,$this->tdheight,$e,$this->tableborder,'',$this->tdalign,$this->tdbgcolor);
				}
				elseif($e=="&nbsp;") {
					$this->Cell($this->tdwidth,$this->tdheight,'',$this->tableborder,'',$this->tdalign,$this->tdbgcolor);
				}
			}
			else
				$this->Write(5,stripslashes(txtentities($e)));
		}
		else
		{
			//Tag
			if($e[0]=='/')
				$this->CloseTag(strtoupper(substr($e,1)));
			else
			{
				//Extract attributes
				$a2=explode(' ',$e);
				$tag=strtoupper(array_shift($a2));
				$attr=array();
				foreach($a2 as $v)
				{
					if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
						$attr[strtoupper($a3[1])]=$a3[2];
				}
				$this->OpenTag($tag,$attr);
			}
		}
	}
}

function OpenTag($tag, $attr)
{
	//Opening tag
	switch($tag){

		case 'SUP':
			if( !empty($attr['SUP']) ) {	
				//Set current font to 6pt 	
				$this->SetFont('','',6);
				//Start 125cm plus width of cell to the right of left margin 		
				//Superscript "1" 
				$this->Cell(2,2,$attr['SUP'],0,0,'L');
			}
			break;

		case 'TABLE': // TABLE-BEGIN
			if( !empty($attr['BORDER']) ) $this->tableborder=$attr['BORDER'];
			else $this->tableborder=0;
			break;
		case 'TR': //TR-BEGIN
			break;
		case 'TD': // TD-BEGIN
			if( !empty($attr['WIDTH']) ) $this->tdwidth=($attr['WIDTH']/4);
			else $this->tdwidth=40; // Set to your own width if you need bigger fixed cells
			if( !empty($attr['HEIGHT']) ) $this->tdheight=($attr['HEIGHT']/6);
			else $this->tdheight=6; // Set to your own height if you need bigger fixed cells
			if( !empty($attr['ALIGN']) ) {
				$align=$attr['ALIGN'];		
				if($align=='LEFT') $this->tdalign='L';
				if($align=='CENTER') $this->tdalign='C';
				if($align=='RIGHT') $this->tdalign='R';
			}
			else $this->tdalign='L'; // Set to your own
			if( !empty($attr['BGCOLOR']) ) {
				$coul=hex2dec($attr['BGCOLOR']);
					$this->SetFillColor($coul['R'],$coul['G'],$coul['B']);
					$this->tdbgcolor=true;
				}
			$this->tdbegin=true;
			break;

		case 'HR':
			if( !empty($attr['WIDTH']) )
				$Width = $attr['WIDTH'];
			else
				$Width = $this->w - $this->lMargin-$this->rMargin;
			$x = $this->GetX();
			$y = $this->GetY();
			$this->SetLineWidth(0.2);
			$this->Line($x,$y,$x+$Width,$y);
			$this->SetLineWidth(0.2);
			$this->Ln(1);
			break;
		case 'STRONG':
			$this->SetStyle('B',true);
			break;
		case 'EM':
			$this->SetStyle('I',true);
			break;
		case 'B':
		case 'I':
		case 'U':
			$this->SetStyle($tag,true);
			break;
		case 'A':
			$this->HREF=$attr['HREF'];
			break;
		case 'IMG':
			if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
				if(!isset($attr['WIDTH']))
					$attr['WIDTH'] = 0;
				if(!isset($attr['HEIGHT']))
					$attr['HEIGHT'] = 0;
				$this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
			}
			break;
		case 'BLOCKQUOTE':
		case 'BR':
			$this->Ln(5);
			break;
		case 'P':
			$this->Ln(10);
			break;
		case 'FONT':
			if (isset($attr['COLOR']) && $attr['COLOR']!='') {
				$coul=hex2dec($attr['COLOR']);
				$this->SetTextColor($coul['R'],$coul['G'],$coul['B']);
				$this->issetcolor=true;
			}
			if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
				$this->SetFont(strtolower($attr['FACE']));
				$this->issetfont=true;
			}
			if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist) && isset($attr['SIZE']) && $attr['SIZE']!='') {
				$this->SetFont(strtolower($attr['FACE']),'',$attr['SIZE']);
				$this->issetfont=true;
			}
			break;
	}
}

function CloseTag($tag)
{
	//Closing tag
	if($tag=='SUP') {
	}

	if($tag=='TD') { // TD-END
		$this->tdbegin=false;
		$this->tdwidth=0;
		$this->tdheight=0;
		$this->tdalign="L";
		$this->tdbgcolor=false;
	}
	if($tag=='TR') { // TR-END
		$this->Ln();
	}
	if($tag=='TABLE') { // TABLE-END
		$this->tableborder=0;
	}

	if($tag=='STRONG')
		$tag='B';
	if($tag=='EM')
		$tag='I';
	if($tag=='B' || $tag=='I' || $tag=='U')
		$this->SetStyle($tag,false);
	if($tag=='A')
		$this->HREF='';
	if($tag=='FONT'){
		if ($this->issetcolor==true) {
			$this->SetTextColor(0);
		}
		if ($this->issetfont) {
			$this->SetFont('arial');
			$this->issetfont=false;
		}
	}
}

function SetStyle($tag, $enable)
{
	//Modify style and select corresponding font
	$this->$tag+=($enable ? 1 : -1);
	$style='';
	foreach(array('B','I','U') as $s) {
		if($this->$s>0)
			$style.=$s;
	}
	$this->SetFont('',$style);
}

function PutLink($URL, $txt)
{
	//Put a hyperlink
	$this->SetTextColor(0,0,255);
	$this->SetStyle('U',true);
	$this->Write(5,$txt,$URL);
	$this->SetStyle('U',false);
	$this->SetTextColor(0);
}

//________________ Fonction encodage et dessin du code 128 _____________________
function Code128($x, $y, $code, $w, $h) {
    $Aguid = "";                                                                      // Cr�ation des guides de choix ABC
    $Bguid = "";
    $Cguid = "";
    for ($i=0; $i < strlen($code); $i++) {
        $needle = substr($code,$i,1);
        $Aguid .= ((strpos($this->Aset,$needle)===false) ? "N" : "O"); 
        $Bguid .= ((strpos($this->Bset,$needle)===false) ? "N" : "O"); 
        $Cguid .= ((strpos($this->Cset,$needle)===false) ? "N" : "O");
    }

    $SminiC = "OOOO";
    $IminiC = 4;

    $crypt = "";
    while ($code > "") {
                                                                                    // BOUCLE PRINCIPALE DE CODAGE
        $i = strpos($Cguid,$SminiC);                                                // for�age du jeu C, si possible
        if ($i!==false) {
            $Aguid [$i] = "N";
            $Bguid [$i] = "N";
        }

        if (substr($Cguid,0,$IminiC) == $SminiC) {                                  // jeu C
            $crypt .= chr(($crypt > "") ? $this->JSwap["C"] : $this->JStart["C"]);  // d�but Cstart, sinon Cswap
            $made = strpos($Cguid,"N");                                             // �tendu du set C
            if ($made === false) {
                $made = strlen($Cguid);
            }
            if (fmod($made,2)==1) {
                $made--;                                                            // seulement un nombre pair
            }
            for ($i=0; $i < $made; $i += 2) {
                $crypt .= chr(strval(substr($code,$i,2)));                          // conversion 2 par 2
            }
            $jeu = "C";
        } else {
            $madeA = strpos($Aguid,"N");                                            // �tendu du set A
            if ($madeA === false) {
                $madeA = strlen($Aguid);
            }
            $madeB = strpos($Bguid,"N");                                            // �tendu du set B
            if ($madeB === false) {
                $madeB = strlen($Bguid);
            }
            $made = (($madeA < $madeB) ? $madeB : $madeA );                         // �tendu trait�e
            $jeu = (($madeA < $madeB) ? "B" : "A" );                                // Jeu en cours

            $crypt .= chr(($crypt > "") ? $this->JSwap[$jeu] : $this->JStart[$jeu]); // d�but start, sinon swap

            $crypt .= strtr(substr($code, 0,$made), $this->SetFrom[$jeu], $this->SetTo[$jeu]); // conversion selon jeu

        }
        $code = substr($code,$made);                                           // raccourcir l�gende et guides de la zone trait�e
        $Aguid = substr($Aguid,$made);
        $Bguid = substr($Bguid,$made);
        $Cguid = substr($Cguid,$made);
    }                                                                          // FIN BOUCLE PRINCIPALE

    $check = ord($crypt[0]);                                                   // calcul de la somme de contr�le
    for ($i=0; $i<strlen($crypt); $i++) {
        $check += (ord($crypt[$i]) * $i);
    }
    $check %= 103;

    $crypt .= chr($check) . chr(106) . chr(107);                               // Chaine crypt�e compl�te

    $i = (strlen($crypt) * 11) - 8;                                            // calcul de la largeur du module
    $modul = $w/$i;

    for ($i=0; $i<strlen($crypt); $i++) {                                      // BOUCLE D'IMPRESSION
        $c = $this->T128[ord($crypt[$i])];
        for ($j=0; $j<count($c); $j++) {
            $this->Rect($x,$y,$c[$j]*$modul,$h,"F");
            $x += ($c[$j++]+$c[$j])*$modul;
        }
    }
}
}//end of class

?>
