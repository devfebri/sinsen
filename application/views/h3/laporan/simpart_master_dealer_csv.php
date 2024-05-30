<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Master AHASS.csv");
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
	$content .= "Kode Dealer AHM".$tanda;
	$content .= "Channel".$tanda;
	$content .= "Grouping AHASS 2".$tanda;
	$content .= "Jenis AHASS \n";
	foreach ($master_ahass as $isi) {
		$content .= $isi->nama_dealer.$tanda;
		$content .= "'".$isi->kode_dealer_ahm.$tanda;
		$content .= $isi->channel.$tanda;
		$content .= $isi->grouping_dealer.$tanda;
		$content .= $isi->jenis_dealer;
		$content .= "\r\n";
	}
		
	echo $content;
?>