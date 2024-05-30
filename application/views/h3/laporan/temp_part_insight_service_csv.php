<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Master Data Service ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';
	$content .= "Jenis Service";
	$content .= "\r\n";
	$content .= "Direct Sales";
	$content .= "\r\n";
	$content .= "WO";
	$content .= "\r\n";
		
	echo $content;
?>