<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Part Consumption Based on Channel ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';


	$content .= "Jenis Channel".$tanda;
	$content .= "Total Pendapatan \n";

	$content .= "H123" . $tanda;
	$content .= number_format($ps_channel->row()->h123,2,",",".");
	$content .= "\r\n";

	$content .= "H23" . $tanda;
	$content .= number_format($ps_channel->row()->h23,2,",",".");
	$content .= "\r\n";
	
		
	echo $content;
?>