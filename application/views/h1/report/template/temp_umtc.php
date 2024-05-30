<?php

	$no = date('dmYHi');

	header("Content-Disposition: attachment; filename=E20-".$no.".UMTC");

	header("Content-Type: application/force-download");

	header('Expires: 0');

	header('Cache-Control: must-revalidate');

	header('Pragma: public');

	header("Content-Type: text/plain");

	$content = '';
	$urut=0;

	$tanda = ';';


	if($list_data !=false){

		foreach ($list_data as $isi) {

			$urut++;

			$content .= $isi->id_tipe_kendaraan. $tanda;

			$content .= $isi->deskripsi_ahm . $tanda;

			$content .= $isi->id_warna . $tanda;

			$content .= $isi->warna . $tanda;

			$content .= $isi->tipe_ahm. $tanda;

			$content .= $isi->cc_motor. $tanda;

			$content .= $isi->id_item . $tanda;

			$content .= $isi->item . $tanda;

			$content .= $isi->id_kategori . $tanda;

			$content .= $isi->tgl_awal . $tanda;

			$content .= $isi->tgl_akhir . $tanda;
		
			$content .= $isi->status . $tanda;

			$content .= "\r\n";

		}

	}



	echo $content;

?>