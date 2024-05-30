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

	foreach ($ps_details->result() as $isi) {
		// $desk_unit = $this->db->query("SELECT mtk.tipe_ahm, ms.segment   
		// 	from tr_h23_nsc C 
		// 	join tr_h2_wo_dealer wo on C.id_referensi=wo.id_work_order 
		// 	join tr_h2_sa_form sa on wo.id_sa_form=sa.id_sa_form
		// 	join ms_customer_h23 cust on cust.id_customer = sa.id_customer 
		// 	left join ms_tipe_kendaraan mtk on mtk.id_tipe_kendaraan = cust.id_tipe_kendaraan
		// 	join ms_segment ms on mtk.id_segment=ms.id_segment  
		// 	WHERE C.no_nsc='$isi->no_nsc'")->row_array();

		$content .= $start_date_2 . $tanda;
		$content .= $end_date_2 . $tanda;
		$content .= $isi->nama_dealer . $tanda;
		$content .= $isi->channel_h123 . $tanda;
		$content .= $isi->id_part . $tanda;
		$content .= $isi->nama_part . $tanda;
		$content .= $isi->deskripsi_ahm . $tanda;
		$content .= $isi->kelompok_part . $tanda;
		$content .= $isi->klp_part . $tanda;
		$content .= $isi->segment . $tanda;
		$content .= $isi->production_year . $tanda;
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