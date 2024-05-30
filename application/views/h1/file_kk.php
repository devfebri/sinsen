<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=" . $nama_file . ".KK");
header("Pragma: no-cache");
header("Expires: 0");

$no = 0;

if($no_mesin!=''){
	$filter = "AND tr_sales_order.no_mesin = '$no_mesin'";
}else{
	$filter ="";
}

$get_spk = $this->db->query("SELECT tr_spk.no_spk FROM tr_sales_order 
			JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
			WHERE tr_sales_order.tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' $filter");

if($get_spk->num_rows() > 0){	
	foreach ($get_spk->result() as $isi) {
		$get_kk = $this->db->query("SELECT tr_cdb_kk.*,no_kk FROM tr_cdb_kk 
			JOIN tr_spk ON tr_cdb_kk.no_spk=tr_spk.no_spk
			WHERE tr_cdb_kk.no_spk='$isi->no_spk'
			ORDER BY tr_spk.no_spk ASC
		");

		if($get_kk->num_rows() > 0){	
			foreach ($get_kk->result() as $rs) {
				$tgl_lahir = date('dmY', strtotime($rs->tgl_lahir));
				echo $rs->no_kk . ';' . $rs->nik . ';' . $rs->nama_lengkap . ';' . $rs->jk . ';' . $rs->tempat_lahir . ';' . $tgl_lahir . ';' . $rs->id_agama . ';' . $rs->id_pendidikan . ';' . $rs->id_pekerjaan . ';' . $rs->id_status_pernikahan . ';' . $rs->id_hub_keluarga . ';' . $rs->jenis_wn . ';';
				echo "\r\n";
			}
		}
	}
}
