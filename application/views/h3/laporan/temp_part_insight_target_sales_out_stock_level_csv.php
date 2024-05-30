<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Master Target Sales Out dan Stock Level Dealer ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';
$start_date_2 = date("d-m-Y", strtotime($start_date));
$end_date_2 = date("d-m-Y", strtotime($end_date));

$target = ['Parts','Oil','Acc','Apparel'];

	$content .= "Tanggal Awal Tarikan".$tanda;
	$content .= "Tanggal Akhir Tarikan".$tanda;
	$content .= "Kode Dealer".$tanda;
	$content .= "Nama Dealer".$tanda;
	$content .= "Jenis Target Sales Out".$tanda;
	$content .= "Target Sales Out".$tanda;
	$content .= "Jenis Target Stock Level".$tanda;
	$content .= "Target Stock Level \n";
	foreach ($getDataDealer as $isi) {
		foreach($target as $t){
			$content .= $start_date_2 . $tanda;
			$content .= $end_date_2 . $tanda;
			$content .= "'" . $isi->kode_dealer_ahm . $tanda;
			$content .= $isi->nama_dealer . $tanda;
			$content .= $t . $tanda;
			$content .= '' . $tanda;
			$content .= $t . $tanda;
			$content .= '' ;
			$content .= "\r\n";
		}
	}
		
	echo $content;
?>