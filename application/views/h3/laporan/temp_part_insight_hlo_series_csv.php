<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=HLO_berdasarkan Series ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';


	$content .= "Tipe Motor".$tanda;
	$content .= "Kuantitas Order Parts \n";

	foreach ($hlo_series->result() as $isi) {
		$content .= $isi->deskripsi . $tanda;
		$content .= $isi->sum_qty;
		$content .= "\r\n";
	}
		
	echo $content;
?>