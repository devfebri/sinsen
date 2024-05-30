<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Part Consumption Based on TOBPM ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';


	$content .= "Jenis Kelompok".$tanda;
	$content .= "Total Pendapatan \n";

	$content .= "TOB" . $tanda;
	$content .= number_format($ps_tobpm->row()->tob,2,",",".");
	$content .= "\r\n";

	$content .= "PM" . $tanda;
	$content .= number_format($ps_tobpm->row()->pm,2,",",".");
	$content .= "\r\n";

	$content .= "Other" . $tanda;
	$content .= number_format($ps_tobpm->row()->other,2,",",".");
	$content .= "\r\n";
		
	echo $content;
?>