<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Part Consumption Based on Jenis Kelompok ".$no.".csv");
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

	$content .= "HGP" . $tanda;
	$content .= number_format($ps_jenis_kelompok->row()->hgp,2,",",".");
	$content .= "\r\n";

	$content .= "Acc&HGA" . $tanda;
	$content .= number_format($ps_jenis_kelompok->row()->hga,2,",",".");
	$content .= "\r\n";

	$content .= "Oil" . $tanda;
	$content .= number_format($ps_jenis_kelompok->row()->hgo,2,",",".");
	$content .= "\r\n";
	
		
	echo $content;
?>