<?php
$no = date("d/m/y_H:I");
$no2 = date("H:I");
header("Content-Disposition: attachment; filename=Laporan Indent AHM NMS ".$no." WIB.csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");


$content = 'Laporan Indent AHM Periode '. $start_date." s/d ".$end_date. "-" .$no2. ' WIB';
$content .= "\r\n\r\n";
$urut=0;
$tanda = ';';


	$content .= "No".$tanda;
	$content .= "No Indent".$tanda;
	$content .= "Kode Dealer".$tanda;
	$content .= "Nama Dealer".$tanda;
	$content .= "No SPK".$tanda;
	$content .= "Nama Konsumen".$tanda;
	$content .= "No KTP".$tanda;
	$content .= "No HP".$tanda;
	$content .= "Kode Tipe".$tanda;
	$content .= "Kode Warna".$tanda;
	$content .= "Tgl Konfirmasi Indent Logistik".$tanda;
	$content .= "Tgl Prospek".$tanda;
	$content .= "Tgl Deal".$tanda;
	$content .= "Tgl Sales".$tanda;
	$content .= "Status Indent MD".$tanda;
	$content .= "Tgl Cancel Indent".$tanda;
	$content .= "Status MFT \n";

	foreach ($spk->result() as $isi) {

			$urut++;
			$content .= $urut . $tanda;
			$content .= $isi->id_indent . $tanda;
			$content .= $isi->kode_dealer_md . $tanda;
			$content .= $isi->nama_dealer. $tanda;
			$content .= $isi->id_spk . $tanda;
			$content .= $isi->nama_konsumen . $tanda;
			$content .= $isi->no_ktp . $tanda;
			$content .= $isi->no_telp . $tanda;
			$content .= $isi->id_tipe_kendaraan . $tanda;
			$content .= $isi->id_warna . $tanda;
			$content .= $isi->date_konfirmasi . $tanda;
			$content .= $isi->date_prospek. $tanda;
			$content .= $isi->date_deal . $tanda;
			$content .= $isi->date_sales . $tanda;
			$content .= $isi->status . $tanda;
			$content .= $isi->date_cancel. $tanda;
			$content .= $isi->status_indent . $tanda;

			$content .= "\r\n";
		}
	echo $content;
?>