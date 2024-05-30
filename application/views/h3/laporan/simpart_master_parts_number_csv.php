<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Master Data Parts Number ".$no.".csv");
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
	$content .= "Part Number".$tanda;
	$content .= "Nama Part".$tanda;
	$content .= "Kelompok Barang \n";
	foreach ($parts_number as $isi) {
		$content .= $isi->urut.$tanda;
		$content .= $isi->id_part.$tanda;
		$content .= $isi->nama_part.$tanda;
		$content .= $isi->kelompok_part;
		$content .= "\r\n";

	$urut++;
	}
		
	echo $content;
?>