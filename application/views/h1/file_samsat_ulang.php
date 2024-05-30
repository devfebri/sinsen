<?php
$file_url = $nama_file.".txt";
header("Content-type: application/octet-stream");
header('Content-Disposition: attachment; filename="' .basename($file_url).'"');
header("Pragma: no-cache");
header("Expires: 0");

foreach ($get_data->result() as $rs) {
	// $tgl_jual = $rs->tgl_mohon_samsat;
	$wil = $this->db->query("SELECT ms_kabupaten.*,kelurahan,kecamatan,ms_kelurahan.kode_samsat FROM ms_kabupaten INNER JOIN ms_kecamatan ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
		INNER JOIN ms_kelurahan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan 
		WHERE ms_kelurahan.id_kelurahan = '$rs->id_kelurahan'")->row();
	$tp = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan='$rs->id_tipe_kendaraan'")->row();
	$wr = $this->db->query("SELECT * FROM ms_warna WHERE id_warna='$rs->id_warna'")->row();
	$fkb = $this->db->query("SELECT * FROM tr_fkb WHERE no_mesin_spasi='$rs->no_mesin'");
	if ($tp->cc_motor!='') {
		$rs_silinder = $tp->cc_motor;
	}else{
		$rs_silinder = '';
	}

	if($rs->tahun_produksi==''){
		$dt_bantuan_bbn = $this->db->query("SELECT tahun_produksi FROM tr_bantuan_bbn_luar WHERE no_mesin ='$rs->no_mesin'");
		if ($dt_bantuan_bbn->num_rows()>0) {
			$rs->tahun_produksi= $dt_bantuan_bbn->row()->tahun_produksi;
		}
	}


	$no_mesin = substr($rs->no_mesin, 0,5).'-'.substr($rs->no_mesin, 5);
	// $rs_silinder = str_replace('.',',',$rs_silinder);
	$no_faktur   = str_replace(' ','','FH/'.$rs->no_faktur);
	$tgl_ibu     = date('d/m/Y',strtotime($rs->tgl_ibu));
	$tgl_jual    = date('d/m/Y',strtotime($rs->tgl_mohon_samsat));
	$cek_faktur = $this->db->query("SELECT * FROM tr_faktur_stnk INNER JOIN tr_faktur_stnk_detail ON tr_faktur_stnk.no_bastd = tr_faktur_stnk_detail.no_bastd
		WHERE tr_faktur_stnk.no_bastd = '$rs->no_bastd'")->row()->id_sales_order;
	$rt = explode("-", $cek_faktur);
	if($rt[0] == 'GC'){
		$tipe_customer = "Instansi";
		$no_ktp = $rs->npwp;
	}else{
		$tipe_customer = "Customer Umum";
		$no_ktp = $rs->no_ktp;
	}

	echo $no_faktur.";".$tgl_jual.";".$rs->nama_konsumen.";".$rs->pekerjaan.";".$no_ktp.";".$rs->nama_ibu.";".$rs->alamat." KEL. ".strtoupper($rs->kelurahan)." KEC. ".strtoupper($rs->kecamatan)." ".strtoupper($wil->kabupaten).";".$wil->kode_samsat.";HONDA;".strip_tags($tp->deskripsi_samsat).";MH1".$rs->no_rangka.";".$no_mesin.";1;".$wr->warna_samsat.";".$rs->tahun_produksi.";".$rs_silinder.";2;2;0101;3;".$tgl_ibu.";41;".$wil->kode_samsat."003";
	echo "\r\n";
}

//pekerjaan,tgl_ibu,id_kelurahan,
?>      

