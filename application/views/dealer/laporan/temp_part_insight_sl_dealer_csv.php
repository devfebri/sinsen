<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=Stock Level_Dealer ".$no.".csv");
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
	$content .= "Status".$tanda;
	$content .= "Produk".$tanda;
	$content .= "Kelompok Part".$tanda;
	$content .= "Kode Part".$tanda;
	$content .= "Jumlah Stock".$tanda;
	$content .= "Harga Beli Part".$tanda;
	$content .= "Amount Stock \n";

	foreach ($sl_dealer->result() as $isi) {

        // $start_date_interval = date('Y-m-d', strtotime($end_date . ' - 1 day'));
        // $sl_dealer2 = $this->db->query("SELECT IFNULL(SUM(CASE WHEN nscp.tipe_diskon='Percentage' 
        //     then ((nscp.harga_beli*nscp.qty)-(nscp.harga_beli*nscp.diskon_value/100))
        //     WHEN nscp.tipe_diskon='Value' then ((nscp.harga_beli*nscp.qty)-nscp.diskon_value) ELSE nscp.harga_beli*nscp.qty END),0) as total_penjualan
        //     FROM tr_h23_nsc nsc
        //     JOIN tr_h23_nsc_parts nscp on nscp.no_nsc=nsc.no_nsc 
        //     -- where nsc.created_at >= date_sub('$end_date 00:00:00', interval 2 month) and nsc.created_at<='$end_date 23:59:59'
        //     where nsc.created_at >= '$start_date_interval 00:00:00' and nsc.created_at<='$end_date 23:59:59'
        //     and nsc.id_dealer = $isi->id_dealer and nscp.id_part_int='$isi->id_part_int'")->row();

        $amount_stock = $isi->stock*$isi->harga_md_dealer;
			
        $content .= $start_date_2 . $tanda;
		$content .= $end_date_2 . $tanda;
		$content .= $isi->nama_dealer . $tanda;
		$content .= $isi->status . $tanda;
		$content .= $isi->produk . $tanda;
		$content .= $isi->kelompok_part . $tanda;
		$content .= "'" . $isi->id_part . $tanda;
		$content .= $isi->stock.$tanda;
		$content .= $isi->harga_md_dealer.$tanda;
		$content .= $amount_stock;
		// $content .= substr($sl_dealer2->total_penjualan,0,-5). $tanda;
		// $content .= $stock_level;
		$content .= "\r\n";
	}
		
	echo $content;
?>