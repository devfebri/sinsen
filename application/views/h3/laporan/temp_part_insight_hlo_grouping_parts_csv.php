<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=HLO_berdasarkan Grouping Parts ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';


	$content .= "Nama Parts".$tanda;
	$content .= "Kuantitas Parts \n";

	foreach ($hlo_grouping_parts->result() as $isi) {
		$content .= $isi->nama_part . $tanda;
		$content .= $isi->sum_qty;
		$content .= "\r\n";
	}
		
	echo $content;
?>