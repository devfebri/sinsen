<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Master By Qty.csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=1;
$tanda = ';';

	$content .= "No".$tanda;
	$content .= "Kode Dealer AHM".$tanda;
	$content .= "Bulan".$tanda;
	$content .= "Minimum Qty".$tanda;
	$content .= "Qty".$tanda;
	$content .= "Available Qty".$tanda;
	$content .= "Grouping".$tanda;
	$content .= "Oil/Non Oil".$tanda;
	$content .= "Target Qty".$tanda;
	$content .= "Kode Part".$tanda;
	$content .= "Nama Part".$tanda;
	$content .= "Kelompok Barang".$tanda;
	$content .= "Nama Dealer \n";
	foreach ($master_by_qty as $isi) {
		$content .= $urut.$tanda;
		$content .= "'".$isi->kode_dealer_ahm.$tanda;
		$content .= $isi->bulan.$tanda;
		$content .= $isi->minimum_qty.$tanda;
		$content .= $isi->qty.$tanda;
		if($isi->qty > 0){
			$content .= "Tersedia".$tanda;
		}else{
			$content .= "Tidak Tersedia".$tanda;
		}
		if($isi->qty > 0){
			$content .= "1".$tanda;
		}else{
			$content .= "0".$tanda;
		}
		$content .= $isi->tipe_oli.$tanda;
		$content .= "0,95".$tanda;
		$content .= $isi->id_part.$tanda;
		$content .= $isi->nama_part.$tanda;
		$content .= $isi->kelompok_part.$tanda;
		$content .= $isi->nama_dealer.$tanda;
		$content .= "\r\n";
		
	$urut++;
	}
		
	echo $content;
?>