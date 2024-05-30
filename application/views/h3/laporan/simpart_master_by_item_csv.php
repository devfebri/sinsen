<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Master By Item.csv");
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
	$content .= "Target".$tanda;
	$content .= "Item".$tanda;
	$content .= "Grouping".$tanda;
	$content .= "Kode Part".$tanda;
	$content .= "Nama Part".$tanda;
	$content .= "Kelompok Barang".$tanda;
	$content .= "Nama Dealer".$tanda;
	$content .= "Tanggal \n";
	foreach ($master_by_item->result() as $isi) {
		$content .= $urut.$tanda;
		$content .= "'".$isi->kode_dealer_ahm.$tanda;
		$content .= $isi->bulan.$tanda;
		$content .= $isi->target.$tanda;
		$content .= number_format($isi->grouping/$master_by_item->num_rows(), 2, '.', ',').$tanda;
		$content .= $isi->grouping.$tanda;
		$content .= $isi->id_part.$tanda;
		$content .= $isi->nama_part.$tanda;
		$content .= $isi->kelompok_part.$tanda;
		$content .= $isi->nama_dealer.$tanda;
		$content .= $isi->tanggal.$tanda;
		$content .= "\r\n";
		
	$urut++;
	}
		
	echo $content;
?>