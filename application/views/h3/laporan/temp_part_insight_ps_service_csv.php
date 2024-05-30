<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Part Consumption Based on Service ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';


	$content .= "Jenis Penjualan".$tanda;
	$content .= "Total Pendapatan \n";

	$content .= "WO" . $tanda;
	$content .= number_format($ps_service->row()->wo,2,",",".");
	$content .= "\r\n";

	$content .= "Direct Sales" . $tanda;
	$content .= number_format($ps_service->row()->sales,2,",",".");
	$content .= "\r\n";
	
		
	echo $content;
?>