<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Part Consumption Based on Channel ".$no.".csv");
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
$content .= "Kelompok Part".$tanda;
$content .= "Kelompok TOBPM".$tanda;
$content .= "Amount by Service".$tanda;
// $content .= "Total Penjualan M".$tanda;
$content .= "Total Penjualan M \n";

foreach ($ps_channel->result() as $isi) {
	// $m_1 = $this->db->query("SELECT ifnull(SUM(CASE WHEN nscp.tipe_diskon='Percentage' 
	// 		then ((nscp.harga_beli*nscp.qty)-((nscp.harga_beli*nscp.diskon_value/100)*nscp.qty))
	// 		WHEN nscp.tipe_diskon='Value' then ((nscp.harga_beli*nscp.qty)-(nscp.diskon_value*nscp.qty)) ELSE nscp.harga_beli*nscp.qty END),0) as total_penjualan_m1
 	// 		FROM tr_h23_nsc nsc
	// 		JOIN tr_h23_nsc_parts nscp on nscp.no_nsc=nsc.no_nsc 
	// 		JOIN ms_part mp on mp.id_part_int=nscp.id_part_int 
	// 		where nsc.created_at >= DATE_ADD('$start_date 00:00:00', INTERVAL -1 MONTH) and nsc.created_at<= DATE_ADD('$end_date 23:59:59', INTERVAL -1 MONTH)
	// 		and mp.kelompok_part !='FED OIL' 
	// 		and mp.kelompok_part='$isi->kelompok_part' and nsc.referensi='$isi->referensi' and nsc.id_dealer='$isi->id_dealer'
	// 		GROUP BY nsc.id_dealer, mp.kelompok_part, nsc.referensi")->row();

	$content .= $start_date_2 . $tanda;
	$content .= $end_date_2 . $tanda;
	$content .= $isi->nama_dealer . $tanda;
	$content .= $isi->status . $tanda;
	$content .= $isi->produk . $tanda;
	$content .= $isi->kelompok_part . $tanda;
	$content .= $isi->tobpm . $tanda;
	$content .= $isi->amount_by_service . $tanda;
	$content .= substr($isi->total_penjualan,0,-5);
	// $content .= substr($m_1->total_penjualan_m1,0,-5). $tanda;
	$content .= "\r\n";
}
	
echo $content;
