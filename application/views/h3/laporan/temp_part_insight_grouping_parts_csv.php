<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Master Data Kelompok Part ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';

	$content .= "Kelompok Part".$tanda;
	$content .= "Major Group \n";
	foreach ($grouping_parts as $isi) {
		$content .= $isi->kelompok_part.$tanda;
		$content .= $isi->major_group;
		$content .= "\r\n";
	}
		
	echo $content;
?>