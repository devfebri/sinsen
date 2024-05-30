<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Total Penjualan Dealer ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';


	$content .= "Nama Dealer".$tanda;
	$content .= "Total Pendapatan \n";

	foreach ($sl_penjualan_dealer->result() as $isi) {

		$content .= $isi->nama_dealer . $tanda;
		$content .= number_format($isi->ytd,2,",",".");
		$content .= "\r\n";
	}
		
	echo $content;
?>