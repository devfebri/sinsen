<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Master Data Dealer ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';
	$content .= "Kode Dealer".$tanda;
	$content .= "Nama Dealer \n";
	foreach ($getDataDealer as $isi) {
		$content .= $isi->kode_dealer_ahm . $tanda;
		$content .= $isi->nama_dealer;
		$content .= "\r\n";
	}
		
	echo $content;
?>