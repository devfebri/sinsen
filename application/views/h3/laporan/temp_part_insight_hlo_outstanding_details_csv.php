<?php
$no = $start_date." sd ".$end_date;
header("Content-Disposition: attachment; filename=HLO_Outstanding Details ".$no.".csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = '';
// $content .= "\r\n\r\n";
$urut=0;
$tanda = ';';


	$content .= "Nama Dealer".$tanda;
	$content .= "Outstanding Days".$tanda;
	$content .= "Outstanding".$tanda;
	$content .= "Deskripsi Parts".$tanda;
	$content .= "Parts Number".$tanda;
	$content .= "Kuantitas".$tanda;
	$content .= "Tanggal Order Customer".$tanda;
	$content .= "Series".$tanda;
	$content .= "Tanggal Pemenuhan PO".$tanda;
	$content .= "Note \n";

	foreach ($hlo_outstanding_details->result() as $isi) {
		$content .= $isi->nama_dealer . $tanda;
		$content .= $isi->outstanding_days . $tanda;
		$content .= $isi->outstanding . $tanda;
		$content .= $isi->nama_part . $tanda;
		$content .= $isi->id_part . $tanda;
		$content .= $isi->kuantitas . $tanda;
		$content .= $isi->created_at . $tanda;
		$content .= $isi->tipe_ahm . $tanda;
		$content .= $isi->tgl_pemenuhan . $tanda;
		$content .= "";
		$content .= "\r\n";
	}
		
	echo $content;
?>