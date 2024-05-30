<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Master Data HLO-FULFILLED/UNFULFILLED ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';

	$content .= "Fulfilled";
	$content .= "\r\n";
	$content .= "Unfulfilled";
	$content .= "\r\n";
		
	echo $content;
?>