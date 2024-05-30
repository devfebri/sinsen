<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=HLO_berdasarkan Dealer ".$no.".csv");
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
	$content .= "Kota/Kabupaten Dealer".$tanda;
	$content .= "Series Motor".$tanda;
	$content .= "Production Year".$tanda;
	$content .= "Kode Part".$tanda;
	$content .= "Deskripsi Part".$tanda;
	$content .= "Kelompok Part".$tanda;
	$content .= "Produk Part".$tanda;
	$content .= "Qty Order".$tanda;
	$content .= "Qty Terpenuhi".$tanda;
	$content .= "Qty Belum Terpenuhi".$tanda;
	$content .= "Status Pemenuhan".$tanda;
	$content .= "Tanggal Order".$tanda;
	$content .= "Tanggal Pemenuhan".$tanda;
	$content .= "Outstanding Days".$tanda;
	$content .= "Outstanding Status \n";

	foreach ($hlo_dealer->result() as $key => $isi) {
		$fulfillment_data = isset($data_fulfillment_dealer[$key]) ? $data_fulfillment_dealer[$key] : (object) array('qty_fulfil' => 0, 'tgl_pemenuhan' => '');
	
		// Mencari Kuantitas Belum Terpenuhi
		$kuantitas_belum_terpenuhi = $isi->kuantitas - $fulfillment_data->qty_fulfil;

		// Status Pemenuhan
		if($kuantitas_belum_terpenuhi == 0){
			$status_pemenuhan = 'Fulfilled';
		}else{
			$status_pemenuhan = 'Unfulfilled';
		}


		//Outstanding days 
		if($fulfillment_data->selisih != ''){
			$outstanding_days = $fulfillment_data->selisih;
		}else{
			$hari_ini = date('Y-m-d');
			$outstanding_days = round((strtotime($hari_ini)-strtotime($isi->tgl_order))/(60*60*24));
		}

		if($outstanding_days <7){
			$outstanding = 'Kurang dari 1 Minggu';
		}elseif($outstanding_days >= 7 and $outstanding_days < 15){
			$outstanding = '1-2 Minggu';
		}elseif($outstanding_days >= 15 and $outstanding_days < 29){
			$outstanding = '3-4 Minggu';
		}elseif($outstanding_days >= 29 and $outstanding_days < 43){
			$outstanding = '5-6 Minggu';
		}else{
			$outstanding = 'Lebih dari 8 Minggu';
		}
	
		$content .= $start_date_2 . $tanda;
		$content .= $end_date_2 . $tanda;
		$content .= $isi->nama_dealer . $tanda;
		$content .= $isi->kabupaten . $tanda;
		$content .= $isi->id_series . $tanda;
		$content .= $isi->production_year . $tanda;
		$content .= $isi->id_part . $tanda;
		$content .= $isi->nama_part . $tanda;
		$content .= $isi->kelompok_part . $tanda;
		$content .= $isi->produk . $tanda;
		$content .= $isi->kuantitas . $tanda;
		$content .= $fulfillment_data->qty_fulfil . $tanda;
		$content .= $kuantitas_belum_terpenuhi . $tanda;
		$content .= $status_pemenuhan . $tanda;
		$content .= $isi->tanggal_order . $tanda;
		$content .= $fulfillment_data->tgl_pemenuhan . $tanda;
		$content .= $outstanding_days . $tanda;
		$content .= $outstanding;
		$content .= "\r\n";
	}
		
	echo $content;
?>