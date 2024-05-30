<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Stock Level_Details ".$no.".csv");
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
	$content .= "Kode Dealer".$tanda;
	$content .= "Nama Dealer".$tanda;
	$content .= "Channel".$tanda;
	$content .= "Jenis Service".$tanda;
	$content .= "Jenis Kelompok".$tanda;
	$content .= "Grup Parts".$tanda;
	$content .= "Kode Parts".$tanda;
	$content .= "Deskripsi Parts".$tanda;
	$content .= "Kuantitas".$tanda;
	$content .= "Harga".$tanda;
	$content .= "Total Penjualan \n";

	// if($start_date == $end_date){
	// 	$selisih = 1;
	// }else{
	// 	$end_date_format = new DateTime($end_date);
	// 	$start_date_format = new DateTime($start_date);
	// 	$selisih= $start_date_format->diff($end_date_format);
	// 	$selisih = ($selisih->days)+1;
	// }

	foreach ($sl_details->result() as $isi) {
		// $avg_penjualan = round(substr($isi->total_penjualan,0,-5)/$selisih);

		$content .= $start_date_2 . $tanda;
		$content .= $end_date_2 . $tanda;
		$content .= "'".$isi->kode_dealer_ahm . $tanda;
		$content .= $isi->nama_dealer . $tanda;
		$content .= $isi->status . $tanda;
		$content .= $isi->referensi . $tanda;
		$content .= $isi->produk . $tanda;
		$content .= $isi->kelompok_part . $tanda;
		$content .= "'".$isi->id_part . $tanda;
		$content .= $isi->nama_part . $tanda;
		$content .= $isi->kuantitas . $tanda;
		$content .= $isi->harga_beli . $tanda;
		$content .= substr($isi->total_penjualan,0,-5);
		$content .= "\r\n";
	}
		
	echo $content;
?>