<?php
$no = date("d/m/y_H:i");
$no2 = date("H:i");
header("Content-Disposition: attachment; filename=monitor_orderin_".$no." WIB.csv");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");

$content = 'Monitor Orderin Periode '. $tgl1." s/d ".$tgl2. "-" .$no2. ' WIB';
$content .= "\r\n\r\n";
$urut=0;
$tanda = ';';

	$content .= "No".$tanda;
	$content .= "Kode Dealer".$tanda;
	$content .= "Nama Dealer".$tanda;
	$content .= "No SPK".$tanda;
	$content .= "Tgl SPK".$tanda;
	$content .= "No KTP".$tanda;
	$content .= "Nama Konsumen".$tanda;
	$content .= "Pekerjaan".$tanda;
	$content .= "Alamat".$tanda;
	$content .= "Kelurahan".$tanda;
	$content .= "Kecamatan".$tanda;
	$content .= "Kabupaten/ Kota".$tanda;
	$content .= "Item".$tanda;
	$content .= "Deskripsi Motor".$tanda;
	$content .= "Warna".$tanda;
	$content .= "Harga OTR".$tanda;
	$content .= "Cash/ Kredit".$tanda;
	$content .= "Nama Finco".$tanda;
	$content .= "DP Gross".$tanda;
	$content .= "Voucer".$tanda;
	$content .= "DP Stor".$tanda;
	$content .= "Angsuran".$tanda;
	$content .= "TOP".$tanda;
	$content .= "Sales People".$tanda;
	$content .= "Jabatan".$tanda;
	$content .= "Status \n";

	foreach($sql->result() as $isi){
			$item =$isi->id_tipe_kendaraan ."-".$isi->id_warna;
			$status = ucfirst($isi->status);
			if($status == 'Close'){ $status = 'Approved'; }

			$urut++;
			$content .= $urut . $tanda;
			$content .= $isi->kode_dealer_md . $tanda;
			$content .= $isi->nama_dealer . $tanda;
			$content .= $isi->no_spk . $tanda;
			$content .= $isi->tgl_spk . $tanda;
			$content .= $isi->no_ktp . $tanda;
			$content .= $isi->nama_konsumen . $tanda;
			$content .= $isi->pekerjaan . $tanda;
			$content .= $isi->alamat. $tanda;
			$content .= $isi->kelurahan . $tanda;
			$content .= $isi->kecamatan . $tanda;
			$content .= $isi->kabupaten . $tanda;
			$content .= $item. $tanda;
			$content .= $isi->tipe_ahm . $tanda;
			$content .= $isi->warna . $tanda;
			$content .= $isi->harga_on_road . $tanda;
			$content .= $isi->jenis_beli. $tanda;
			$content .= $isi->finance_company . $tanda;
			$content .= $isi->uang_muka. $tanda;
			$content .= $isi->voucer. $tanda;
			$content .= $isi->dp_stor. $tanda;
			$content .= $isi->angsuran. $tanda;
			$content .= $isi->tenor . $tanda;
			$content .= $isi->nama_lengkap. $tanda;
			$content .= $isi->jabatan . $tanda;
			$content .= $status. $tanda;
			$content .= "\r\n";
	}
	echo $content;
?>