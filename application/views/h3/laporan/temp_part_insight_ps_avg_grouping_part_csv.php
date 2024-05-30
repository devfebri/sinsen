<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Part Consumption Amount by AVG Grouping Parts ".$no.".csv");
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

	$content .= "Tanggal Awal Tarikan".$tanda;
	$content .= "Tanggal Akhir Tarikan".$tanda;
	$content .= "Nama Dealer".$tanda;
	$content .= "Channel".$tanda;
	$content .= "Produk".$tanda;
	$content .= "Referensi".$tanda;
	$content .= "Kelompok Part".$tanda;
	$content .= "Bulan".$tanda;
	$content .= "Pendapatan".$tanda;
	$content .= "Avg Pendapatan \n";

	if($start_date == $end_date){
		$selisih = 1;
	}else{
		$end_date_format = new DateTime($end_date);
		$start_date_format = new DateTime($start_date);
		$selisih= $start_date_format->diff($end_date_format);
		$selisih = ($selisih->days)+1;
	}

	foreach ($ps_avg_grouping_part->result() as $isi) {
		$avg_pendapatan = round(substr($isi->pendapatan,0,-5)/$selisih);
		$content .= $start_date_2 . $tanda;
		$content .= $end_date_2 . $tanda;
		$content .= $isi->nama_dealer . $tanda;
		$content .= $isi->status . $tanda;
		$content .= $isi->produk . $tanda;
		$content .= $isi->referensi . $tanda;
		$content .= $isi->kelompok_part . $tanda;
		$content .= $isi->bulantahun . $tanda;
		$content .= substr($isi->pendapatan,0,-5) . $tanda;
		$content .= $avg_pendapatan;
		$content .= "\r\n";
	}
		
	echo $content;
?>