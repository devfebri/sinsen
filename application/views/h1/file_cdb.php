<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=" . $nama_file . ".CDB");
header("Pragma: no-cache");
header("Expires: 0");

$tot_so_in = $data_generate['so_in']->num_rows();
$no = 0;
foreach ($data_generate['so_in']->result() as $isi) {
	$no++;
	$nosin5 = substr($isi->no_mesin, 0, 5);
	$nosin7 = substr($isi->no_mesin, 5, 7);
	$tgl = $this->db->query("SELECT * FROM tr_permohonan_stnk WHERE no_mesin = '$isi->no_mesin'");
	if ($tgl->num_rows() > 0) {
		$r = $tgl->row();
		$tgl_mohon = $r->tgl_permohonan;
	} else {
		$tgl_mohon = "";
	}
	$asal = $this->db->query("SELECT * FROM tr_spk LEFT JOIN ms_kelurahan ON tr_spk.id_kelurahan = ms_kelurahan.id_kelurahan
		LEFT JOIN ms_kecamatan ON tr_spk.id_kecamatan = ms_kecamatan.id_kecamatan
		LEFT JOIN ms_kabupaten ON tr_spk.id_kabupaten = ms_kabupaten.id_kabupaten
		LEFT JOIN ms_provinsi ON tr_spk.id_provinsi = ms_provinsi.id_provinsi
		INNER JOIN tr_sales_order ON tr_spk.no_spk = tr_sales_order.no_spk
		WHERE tr_sales_order.no_mesin = '$isi->no_mesin'");
	if ($asal->num_rows() > 0) {
		$a = $asal->row();
		$kelurahan = $a->id_kelurahan;
		$region = explode("-", $this->m_admin->getRegion($kelurahan));

		$kecamatan = $region[1];
		$kabupaten = $region[2];
		$provinsi  = $region[3];
		$kodepos 	 = $a->kodepos;
		$jenis_beli 	 = $a->jenis_beli;
		$no_ktp 	 = $a->no_ktp;
		$id_customer = $a->id_customer;
		$alamat = $a->alamat;
		$tgl_lahir = $a->tgl_lahir;
		$tempat_lahir = $isi->tempat_lahir;

		$bulan = substr($tgl_lahir, 5, 2);
		$tahun = substr($tgl_lahir, 0, 4);
		$tgl = substr($tgl_lahir, 8, 2);
		$tanggal = $tgl . $bulan . $tahun;
		$pk = $a->pekerjaan;
		$pengeluaran = $a->pengeluaran_bulan;
		$no_hp = $a->no_hp;
		$no_telp = ($a->no_telp != '') ? $a->no_telp : "0";
		if ($no_telp == '.' || $no_telp == '"' || $no_telp == '-' || $no_telp == '.0' || $no_telp == null) {
			$no_telp = "0";
		}
		$email = $a->email;
		$status_rumah = $a->status_rumah;
		if ($status_rumah == 'Rumah Sendiri') {
			$status_rumah = "1";
		} elseif ($status_rumah == 'Rumah Orang Tua') {
			$status_rumah = "2";
		} elseif ($status_rumah == 'Rumah Sewa') {
			$status_rumah = "3";
		}
		$penanggung = "N";
		$status_hp = $a->status_hp;
		$ket = $a->keterangan;
		$ref = $a->refferal_id;
		$robd = $a->robd_id;
		$jenis_wn = $a->jenis_wn;
		if ($jenis_wn == 'WNI') {
			$jenis_wn = "1";
		} else {
			$jenis_wn = "2";
		}
		$no_kk = $a->no_kk;
	} else {
		$kelurahan = "";
		$kecamatan = "";
		$kabupaten = "";
		$provinsi = "";
		$kodepos = "";
		$jenis_beli = "";
		$no_ktp = "";
		$id_customer = "";
		$tgl_lahir = "";
		$alamat = "";
		$pk = "";
		$pengeluaran = "";
		$no_hp = 0;
		$no_telp = 0;
		$status_hp = "";
		$status_rumah = "";
		$email = "";
		$ket = "N";
		$ref = "";
		$jenis_wn = "";
		$no_kk = "";
		$robd = "";
	}


	$cdb = $this->db->query("SELECT * FROM tr_cdb WHERE no_spk = '$isi->no_spk'");
	if ($cdb->num_rows() > 0) {
		$am = $cdb->row();
		$ag = $am->agama;
		$pendidikan = $am->pendidikan;
		$sedia_hub 	= $am->sedia_hub;
		if ($sedia_hub == 'Ya') {
			$sedia_hub = "Y";
		} else {
			$sedia_hub = "N";
		}
		$merk_sebelumnya = $am->merk_sebelumnya;
		$jenis_sebelumnya = $am->jenis_sebelumnya;
		$digunakan = $am->digunakan;
		$pemakai_motor = $am->menggunakan;
		if ($pemakai_motor == 'Saya Sendiri') {
			$pemakai_motor = "1";
		} elseif ($pemakai_motor == 'Anak') {
			$pemakai_motor = "2";
		} elseif ($pemakai_motor == 'Pasangan Suami/Istri') {
			$pemakai_motor = "3";
		}
		$facebook  = ($am->facebook != '') ? $am->facebook : "N";
		$twitter   = ($am->twitter != '') ? $am->twitter : "N";
		$instagram = ($am->instagram != '') ? $am->instagram : "N";
		$youtube   = ($am->youtube != '') ? $am->youtube : "N";
		$hobi      = $am->hobi;
		if ($youtube == '-' || $youtube == 0) $youtube = 'N';
		if ($twitter == '--' || $twitter == '-' || $twitter == 0) $twitter = 'N';
		if ($facebook == '--' || $facebook == '-' || $facebook == 0) $facebook = 'N';
		if ($instagram == '--' || $instagram == '-' || $instagram == 0) $instagram = 'N';
		$id_kelurahan_instansi = ($isi->id_kelurahan_kantor != '') ? $isi->id_kelurahan_kantor : "";
		$kec_instansi  = '';							
		$kab_instansi = '';
		$prov_instansi ='';
		$nama_instansi  ='';
		$alamat_instansi ='';
		$aktivitas_penjualan = ($am->aktivitas_penjualan != '') ? $am->aktivitas_penjualan : "";
	} else {
		$ag = "";
		$pendidikan = "";
		$sedia_hub = "";
		$merk_sebelumnya = "";
		$jenis_sebelumnya = "";
		$digunakan = "";
		$pemakai_motor = "";
		$facebook = "N";
		$twitter = "N";
		$youtube = "N";
		$instagram = "N";
		$hobi = "";
	}
	$jk = $this->db->query("SELECT * FROM tr_prospek WHERE id_customer = '$id_customer'");
	$sub_pekerjaan = '';
	if ($jk->num_rows() > 0) {
		$j = $jk->row();
		$jn = $j->jenis_kelamin;
		$id_karyawan_dealer = $j->id_karyawan_dealer;
		$is_required_instansi ='';
		if($j->sub_pekerjaan == '101'){
			$pk = '11';
			$sub_pekerjaan = $j->pekerjaan_lain;
		}else{
			$get_sub_pekerjaan = $this->db->query("SELECT id_pekerjaan, sub_pekerjaan, required_instansi FROM ms_sub_pekerjaan WHERE id_sub_pekerjaan = '$j->sub_pekerjaan'");
			if($get_sub_pekerjaan->num_rows()>0){
				$pk= $get_sub_pekerjaan->row()->id_pekerjaan;
				$is_required_instansi = $get_sub_pekerjaan->row()->required_instansi;
				
				if($j->sub_pekerjaan == '102'){
					$pk = '11';
					$sub_pekerjaan =  $get_sub_pekerjaan->row()->sub_pekerjaan;
				}
			}
		}
			
		if($is_required_instansi == '1'){
			$temp_instansi = str_replace(" ","",$j->nama_tempat_usaha);
			$temp_alamat_instansi = str_replace(" ","",$j->alamat_kantor);

			$nama_instansi       = ($temp_instansi != '') ? $j->nama_tempat_usaha : "-";
			$alamat_instansi     = ($temp_alamat_instansi != '') ? $j->alamat_kantor : "-";
			$dmg_instansi = $this->db->query("SELECT ms_kelurahan.id_kecamatan, ms_kecamatan.id_kabupaten, ms_kabupaten.id_provinsi
				FROM  ms_kelurahan
				LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
				LEFT JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
				LEFT JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
				WHERE ms_kelurahan.id_kelurahan='$j->id_kelurahan_kantor'");
			if($dmg_instansi->num_rows() > 0){
				$kec_instansi  = $dmg_instansi->row()->id_kecamatan;
				$kab_instansi  = $dmg_instansi->row()->id_kabupaten;
				$prov_instansi = $dmg_instansi->row()->id_provinsi;
			}
		}		
	} else {
		$jn = "";
		$id_karyawan_dealer = "";
	}

	if ($jn == 'Pria') {
		$jn = "1";
	} elseif ($jn == 'Wanita') {
		$jn = "2";
	}
	$dealer = $this->m_admin->getByID("ms_dealer", "id_dealer", $isi->id_dealer);
	if ($dealer->num_rows() > 0) {
		$d = $dealer->row();
		$kode_dealer_md = $d->kode_dealer_md;
		if ($d->id_dealer_induk != 0) {
			$kode_dealer_md = $d->kode_dealer_ahm;
		}
		if ($kode_dealer_md == 'PSB') {
			$kode_dealer_md = '13384';
		}
	} else {
		$kode_dealer_md = "";
	}

	$sales = $this->m_admin->getByID("ms_karyawan_dealer", "id_karyawan_dealer", $id_karyawan_dealer);
	if ($sales->num_rows() > 0) {
		$s = $sales->row();
		$kode_sales = $s->id_flp_md;
	} else {
		$kode_sales = "";
	}


	//echo $nosin5 . " ;" . $nosin7 . ";" . $no_ktp . ";I;" . $jn . ";" . $tanggal . ";" . $alamat . ";" . $kelurahan . ";" . $kecamatan . ";" . $kabupaten . ";" . $kodepos . ";" . $provinsi . ";" . $ag . ";" . $pk . ";" . $pengeluaran . ";" . $pendidikan . ";" . $penanggung . ";" . $no_hp . ";" . $no_telp . ";" . $sedia_hub . ";" . $merk_sebelumnya . ";" . $jenis_sebelumnya . ";" . $digunakan . ";" . $pemakai_motor . ";" . $kode_sales . ";" . $email . ";" . $status_rumah . ";" . $status_hp . ";1;" . $facebook . ";" . $twitter . ";" . $instagram . ";" . $youtube . ";" . $hobi . ";" . $ket . ";" . $jenis_wn . ";" . $no_kk . ";" . $ref . ";" . $robd . ";";
	
	echo $nosin5 . " ;" . $nosin7 . ";" . $no_ktp . ";I;" . $jn . ";" . $tanggal . ";" . $alamat . ";" . $kelurahan . ";" . $kecamatan . ";" . $kabupaten . ";" . $kodepos . ";" . $provinsi . ";" . $ag . ";" . $pk . ";" . $pengeluaran . ";" . $pendidikan . ";" . $penanggung . ";" . $no_hp . ";" . $no_telp . ";" . $sedia_hub . ";" . $merk_sebelumnya . ";" . $jenis_sebelumnya . ";" . $digunakan . ";" . $pemakai_motor . ";" . $kode_sales . ";" . $email . ";" . $status_rumah . ";" . $status_hp . ";1;" . $facebook . ";" . $twitter . ";" . $instagram . ";" . $youtube . ";" . $hobi . ";" . $ket . ";" . $jenis_wn . ";" . $no_kk . ";" . $ref . ";" . $robd . ";" . $tempat_lahir . ";" . $nama_instansi . ";" . $alamat_instansi . ";" . $kec_instansi . ";" . $kab_instansi . ";" . $prov_instansi . ";" . $aktivitas_penjualan . ";" . $sub_pekerjaan .";";
			
	if ($no < $tot_so_in) {
		echo "\r\n";
	}
}

// $sql = $this->db->query("SELECT * FROM tr_cdb_generate INNER JOIN tr_cdb_generate_detail ON tr_cdb_generate.id_cdb_generate = tr_cdb_generate_detail.id_cdb_generate
// 		INNER JOIN tr_sales_order_gc_nosin ON tr_cdb_generate_detail.no_mesin = tr_sales_order_gc_nosin.no_mesin					
// 		INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc				
// 		INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
// 		WHERE tr_cdb_generate.id_cdb_generate = '$id_cdb_generate'");
$tot_so_gc = $data_generate['so_gc']->num_rows();
$no = 0;
if ($data_generate['so_gc']->num_rows() > 0) {
	echo "\r\n";
}
foreach ($data_generate['so_gc']->result() as $isi) {
	$no++;
	$nosin5 = substr($isi->no_mesin, 0, 5);
	$nosin7 = substr($isi->no_mesin, 5, 7);
	$tgl = $this->db->query("SELECT * FROM tr_permohonan_stnk WHERE no_mesin = '$isi->no_mesin'");
	if ($tgl->num_rows() > 0) {
		$r = $tgl->row();
		$tgl_mohon = $r->tgl_permohonan;
	} else {
		$tgl_mohon = "";
	}

	$kelurahan = $isi->id_kelurahan;
	$region = explode("-", $this->m_admin->getRegion($kelurahan));

	$kecamatan = $region[1];
	$kabupaten = $region[2];
	$provinsi  = $region[3];
	$kodepos 	 = $isi->kodepos;
	$jenis_beli 	 = $isi->jenis_beli;
	$no_ktp 	 = $isi->no_npwp;
	$id_prospek_gc = $isi->id_prospek_gc;
	$alamat = $isi->alamat;
	$tgl_lahir = $isi->tgl_berdiri;

	$bulan = substr($tgl_lahir, 5, 2);
	$tahun = substr($tgl_lahir, 0, 4);
	$tgl = substr($tgl_lahir, 8, 2);
	$tanggal = $tgl . $bulan . $tahun;
	// if(isset($isi->pekerjaan)){
	// 	$pk = $isi->pekerjaan;
	// }else{
	$pk = "N";
	//}
	//  if(isset($isi->pengeluaran_bulan)){
	// $pengeluaran = $isi->pengeluaran_bulan;
	//  }else{	
	$pengeluaran = "N";
	//}
	$no_hp = $isi->no_hp;
	$no_telp = ($isi->no_telp != '') ? $isi->no_telp : "0";
	$email = $isi->email;
	// if(isset($isi->status_rumah)){
	// 	$status_rumah = $isi->status_rumah;
	// 	if($status_rumah == 'Rumah Sendiri'){
	// 		$status_rumah = "1";
	// 	}elseif($status_rumah == 'Rumah Orang Tua'){
	// 		$status_rumah = "2";
	// 	}elseif($status_rumah == 'Rumah Sewa'){
	// 		$status_rumah = "3";
	// 	}
	// }else{
	$status_rumah = "N";
	//}
	$penanggung = $isi->nama_penanggung_jawab;
	if (isset($isi->status_nohp)) {
		$status_hp = $isi->status_nohp;
	} else {
		$status_hp = "";
	}
	$ket = "N";
	if (isset($isi->refferal_id)) {
		$ref = $isi->refferal_id;
	} else {
		$ref = "";
	}
	if (isset($isi->robd_id)) {
		$robd = $isi->robd_id;
	} else {
		$robd = "";
	}
	// $jenis_wn = $isi->jenis_wn;
	// if($jenis_wn == 'WNI'){
	// 	$jenis_wn = "1";
	// }else{
	$jenis_wn = "1";
	//}
	$no_kk = "";
	//}else{
	// $kelurahan="";$kecamatan="";$kabupaten="";$provinsi="";$kodepos="";$jenis_beli="";$no_ktp="";$id_customer="";$tgl_lahir="";$tanggal="";
	// $alamat="";$pk="";$pengeluaran="";$no_hp=0;$no_telp=0;$status_hp="";$status_rumah="";$email="";$ket="N";$ref="";$jenis_wn="";$no_kk="";$robd="";
	//}


	$cdb = $this->db->query("SELECT * FROM tr_cdb_gc WHERE no_spk_gc = '$isi->no_spk_gc'");
	if ($cdb->num_rows() > 0) {
		$am = $cdb->row();
		$ag = "N";
		$pendidikan = "N";
		$sedia_hub 	= $am->sedia_hub;
		if ($sedia_hub == 'Ya') {
			$sedia_hub = "Y";
		} else {
			$sedia_hub = "N";
		}
		$merk_sebelumnya = "N";
		$jenis_sebelumnya = 'N';
		$digunakan = "N";
		$pemakai_motor = "N";
		$facebook = ($am->facebook != '') ? $am->facebook : "N";
		$twitter = ($am->twitter != '') ? $am->twitter : "N";
		$instagram = ($am->instagram != '') ? $am->instagram : "N";
		$youtube = ($am->youtube != '') ? $am->youtube : "N";
		$hobi = "N";
		if ($youtube == '-' || $youtube == 0) $youtube = 'N';
		if ($twitter == '--' || $twitter == '-' || $twitter == 0) $twitter = 'N';
		if ($facebook == '--' || $facebook == '-' || $facebook == 0) $facebook = 'N';
		if ($instagram == '--' || $instagram == '-' || $instagram == 0) $instagram = 'N';
	} else {
		$ag = "N";
		$pendidikan = "N";
		$sedia_hub = "";
		$merk_sebelumnya = "N";
		$jenis_sebelumnya = "N";
		$digunakan = "N";
		$pemakai_motor = "N";
		$facebook = "N";
		$twitter = "N";
		$youtube = "N";
		$instagram = "N";
		$hobi = "N";
	}
	$akt ='N';
	$jk = $this->db->query("SELECT * FROM tr_prospek_gc WHERE id_prospek_gc = '$isi->id_prospek_gc'");
	if ($jk->num_rows() > 0) {
		$j = $jk->row();
		$jn = "";
		$id_karyawan_dealer = $j->id_karyawan_dealer;
	
		if($j->sumber_prospek !=''){
			$akt = $this->m_admin->getByID("ms_sumber_prospek", "id_dms", $j->sumber_prospek);
			if ($akt->num_rows() > 0) {
				$akt = $akt->row()->id_cdb;
			}
		}
	} else {
		$jn = "";
		$id_karyawan_dealer = "";
	}



	// if($jn == 'Pria'){
	// 	$jn = "1";
	// }elseif($jn == 'Wanita'){
	$jn = "";
	//}
	$dealer = $this->m_admin->getByID("ms_dealer", "id_dealer", $isi->id_dealer);
	if ($dealer->num_rows() > 0) {
		$d = $dealer->row();
		$kode_dealer_md = $d->kode_dealer_md;
		if ($d->id_dealer_induk != 0) {
			$kode_dealer_md = $d->kode_dealer_ahm;
		}
		if ($kode_dealer_md == 'PSB') {
			$kode_dealer_md = '13384';
		}
	} else {
		$kode_dealer_md = "";
	}

	$sales = $this->m_admin->getByID("ms_karyawan_dealer", "id_karyawan_dealer", $id_karyawan_dealer);
	if ($sales->num_rows() > 0) {
		$s = $sales->row();
		$kode_sales = $s->id_flp_md;
	} else {
		$kode_sales = "";
	}


	echo $nosin5 . " ;" . $nosin7 . ";" . $no_ktp . ";G;N;" . $tanggal . ";" . $alamat . ";" . $kelurahan . ";" . $kecamatan . ";" . $kabupaten . ";" . $kodepos . ";" . $provinsi . ";" . $ag . ";" . $pk . ";" . $pengeluaran . ";" . $pendidikan . ";" . $penanggung . ";" . $no_hp . ";" . $no_telp . ";" . $sedia_hub . ";" . $merk_sebelumnya . ";" . $jenis_sebelumnya . ";" . $digunakan . ";" . $pemakai_motor . ";" . $kode_sales . ";" . $email . ";" . $status_rumah . ";" . $status_hp . ";1;" . $facebook . ";" . $twitter . ";" . $instagram . ";" . $youtube . ";" . $hobi . ";" . $ket . ";" . $jenis_wn . ";" . $no_kk . ";" . $ref . ";" . $robd . ";N;;;;;;".$akt.";";
	if ($no < $tot_so_gc) {
		echo "\r\n";
	}
}
