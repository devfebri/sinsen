<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Part Consumption Details ".$no.".csv");
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
	$content .= "Nomor Parts".$tanda;
	$content .= "Deskripsi Parts".$tanda;
	$content .= "Deskripsi Unit".$tanda;
	$content .= "Grup Parts".$tanda;
	$content .= "Jenis Kelompok".$tanda;
	$content .= "9 Segment".$tanda;
	$content .= "Tahun Produksi".$tanda;
	$content .= "Kuantitas".$tanda;
	$content .= "Harga".$tanda;
	$content .= "Tipe Diskon".$tanda;
	$content .= "Diskon".$tanda;
	$content .= "Total".$tanda;
	$content .= "Referensi".$tanda;
	$content .= "No.ID \n";

	foreach ($ps_details->result() as $key => $isi) {
		$data_cust = isset($data_customer[$key]) ? $data_customer[$key] : (object) array('segment' => '', 'production_year' => '', 'deskripsi_ahm' => '');
	

		$content .= $start_date_2 . $tanda;
		$content .= $end_date_2 . $tanda;
		$content .= $isi->nama_dealer . $tanda;
		$content .= $isi->channel_h123 . $tanda;
		$content .= $isi->id_part . $tanda;
		$content .= $isi->nama_part . $tanda;
		$content .= $data_cust->deskripsi_ahm . $tanda;
		$content .= $isi->kelompok_part . $tanda;
		$content .= $isi->klp_part . $tanda;
		$content .= $data_cust->segment . $tanda;
		$content .= $data_cust->production_year . $tanda;
		$content .= $isi->qty . $tanda;
		$content .= $isi->harga_beli . $tanda;
		$content .= $isi->tipe_diskon . $tanda;
		$content .= $isi->diskon_value . $tanda;
		$content .= $isi->total . $tanda;
		$content .= $isi->referensi . $tanda;
		$content .= $isi->no_nsc;
		$content .= "\r\n";
	}
		
	echo $content;
?>