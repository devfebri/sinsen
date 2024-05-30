<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Master SIM Part.csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';

	$content .= "Kode Dealer AHM 1".$tanda;
	$content .= "Nama Dealer 1".$tanda;
	$content .= "Kode Part".$tanda;
	$content .= "Nama Part".$tanda;
	$content .= "Kelompok Part".$tanda;
	$content .= "Minimum Qty SIM Part".$tanda;
	$content .= "Avail Item".$tanda;
	$content .= "Qty Stock On Hand".$tanda;
	$content .= "Bulan".$tanda;
	$content .= "Channel".$tanda;
	$content .= "Target".$tanda;
	$content .= "Oil/Non Oil".$tanda;
	$content .= "Validasi Grouping".$tanda;
	$content .= "Repair Mode".$tanda;
	$content .= "Count Avail Item".$tanda;
	$content .= "Target".$tanda;
	$content .= "Stock vs Qty".$tanda;
	$content .= "Harga Jual".$tanda;
	$content .= "HET".$tanda;
	$content .= "Jumlah UE".$tanda;
	$content .= "Kategori AHASS".$tanda;
	$content .= "Kategori Detail".$tanda;
	$content .= "Tanggal \n";
	foreach ($master_sim_part as $isi) {
		$content .= "'".$isi->kode_dealer_ahm.$tanda;
		$content .= $isi->nama_dealer.$tanda;
		$content .= $isi->id_part.$tanda;
		$content .= $isi->nama_part.$tanda;
		$content .= $isi->kelompok_part.$tanda;
		$content .= $isi->qty_sim_part.$tanda;
		$content .= $isi->AvailItem.$tanda;
		$content .= $isi->stock_on_hand.$tanda;
		$content .= $isi->bulan.$tanda;
		$content .= $isi->channel.$tanda;
		$content .= $isi->target.$tanda;
		$content .= $isi->tipe_oli.$tanda;
		$content .= $isi->validasiGrouping.$tanda;
		$content .= $isi->RepairMode.$tanda;
		$content .= $isi->countAvailItem.$tanda;
		$content .= $isi->target.$tanda;
		if($isi->stock_on_hand >= $isi->qty_sim_part){
			$content .= "Above Min".$tanda;
		}else{
			$content .= "Below Min".$tanda;
		}
		$content .= $isi->harga_jual.$tanda;
		$content .= $isi->het.$tanda;
		$content .= $isi->jumlah_ue.$tanda;
		$content .= $isi->kategori_ahass.$tanda;
		$content .= $isi->unit_entry.$tanda;
		$content .= $isi->tanggal;
		$content .= "\r\n";
	}
		
	echo $content;
?>