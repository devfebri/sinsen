<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Master Kelompok Barang.csv");
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
	$content .= "Kelompok Part".$tanda;
	$content .= "Part Number".$tanda;
	$content .= "Part Number Setelah Supersede".$tanda;
	$content .= "Deskripsi".$tanda;
	$content .= "UE < 500".$tanda;
	$content .= "UE 500-1000".$tanda;
	$content .= "UE > 1000 \n";
	foreach ($master_kelompok_part as $isi) {
		$content .= $urut.$tanda;
		$content .= $isi->kelompok_part.$tanda;
		$content .= $isi->id_part.$tanda;
		$content .= $isi->supersede.$tanda;
		$content .= $isi->nama_part.$tanda;
		$content .= $isi->ue_500.$tanda;
		$content .= $isi->ue_500_1000.$tanda;
		$content .= $isi->ue_1000.$tanda;
		$content .= "\r\n";
		$urut++;
	}
		
	echo $content;
?>