<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Stock Level_Grouping Parts ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';


	$content .= "Kelompok Parts".$tanda;
	$content .= "Total Parts \n";

	foreach ($sl_grouping_parts->result() as $isi) {
		$content .= $isi->kelompok_part . $tanda;
		$content .= $isi->total_part;
		$content .= "\r\n";
	}
		
	echo $content;
?>