<?php

	$no = date('dmY_Hi');

	header("Content-Disposition: attachment; filename=LaporanBBN-".$no.".csv");

	header("Content-Type: application/force-download");

	header('Expires: 0');

	header('Cache-Control: must-revalidate');

	header('Pragma: public');

	header("Content-Type: text/plain");



	$content = 'Laporan BBN AS OF'.date('d F Y - H:i') .' WIB';

	$content .= "\r\n\r\n";

	$urut=0;

	$tanda = ';';



	$content .= "No".$tanda;

	$content .= "Tanggal SSU".$tanda;

	$content .= "Nama Dealer".$tanda;

	$content .= "No Mesin".$tanda;

	$content .= "No Rangka".$tanda;

	$content .= "Kode Tipe".$tanda;

	$content .= "Nama Konsumen".$tanda;

	$content .= "Tgl Pengajuan Ke MD".$tanda;

	$content .= "No BASTD".$tanda;

	$content .= "Tgl Entry Penerimaan".$tanda;

	$content .= "Tgl Approve Finance ".$tanda;

	$content .= "Status Approve".$tanda;

	$content .= "Tanggal Samsat".$tanda;

	$content .= "Status BBN".$tanda;	
	$content .= "No STNK".$tanda;
	$content .= "No Polisi\n";



	if($list_data !=false){

		foreach ($list_data as $isi) {

			$urut++;

			$content .= $urut . $tanda;

			$content .= $isi->tgl_cetak_invoice . $tanda;

			$content .= $isi->nama_dealer . $tanda;

			$content .= $isi->no_mesin . $tanda;

			$content .= $isi->no_rangka . $tanda;

			$content .= $isi->tipe_motor . $tanda;

			$content .= $isi->nama_bpkb . $tanda;

			$content .= $isi->tgl_pengajuan_dealer . $tanda;

			$content .= $isi->no_bastd . $tanda;

			$content .= $isi->tgl_pembayaran . $tanda;

			$content .= $isi->tgl_approval . $tanda;

			$content .= $isi->status_approve_finance . $tanda;

			$content .= $isi->tgl_pengajuan_samsat . $tanda;

			$content .= $isi->status_bbn . $tanda;			
			$content .= $isi->no_stnk. $tanda;			
			$content .= $isi->no_pol . $tanda;

			$content .= "\r\n";

		}

	}



	echo $content;

?>