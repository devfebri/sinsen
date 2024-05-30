<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=HLO_Kota ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';


	$content .= "Kota/Kabupaten".$tanda;
	$content .= "Kuantitas Order Parts \n";

	foreach ($hlo_kota->result() as $isi) {
		$content .= $isi->kabupaten . $tanda;
		$content .= $isi->value_kota;
		$content .= "\r\n";
	}
		
	echo $content;
?>