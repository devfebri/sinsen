<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$no.".txt");
header("Pragma: no-cache");
header("Expires: 0");

$query = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail INNER JOIN tr_scan_barcode ON tr_pengajuan_bbn_detail.no_mesin = tr_scan_barcode.no_mesin
						INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
						INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna 						
						WHERE tr_pengajuan_bbn_detail.tgl_mohon_samsat = '$tanggal' AND tr_pengajuan_bbn_detail.cetak = 'ya' AND tr_pengajuan_bbn_detail.status_bbn = ''");			

foreach ($query->result() as $isi) {				
  $rw = $this->m_admin->getByID("tr_faktur_stnk_detail","no_bastd",$isi->no_bastd)->row();
	$nosin_spasi = substr_replace($rw->no_mesin," ", 5, -strlen($rw->no_mesin));
	$nosin_strip = substr_replace($rw->no_mesin,"-", 5, -strlen($rw->no_mesin));
  $re = $this->m_admin->getByID("tr_fkb","no_mesin",$nosin_spasi)->row();
  $lu = $this->m_admin->getByID("ms_kelurahan","id_kelurahan",$isi->id_kelurahan)->row();
  $tanggal = date("d/m/Y", strtotime($re->tgl_upload));
  $wil = $this->db->query("SELECT ms_kabupaten.* FROM ms_kabupaten INNER JOIN ms_kecamatan ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
		INNER JOIN ms_kelurahan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan 
		WHERE ms_kelurahan.id_kelurahan = '$lu->id_kelurahan'")->row();
  //echo $re->tgl_upload;
	
	$this->db->query("UPDATE tr_pengajuan_bbn_detail SET id_generate = '$id_generate', status_bbn = 'generated' WHERE no_bastd = '$isi->no_bastd'");
	echo $isi->no_faktur.";".$isi->tgl_jual.";".$isi->nama_konsumen.";".$isi->pekerjaan.";".$isi->no_ktp.";".$isi->nama_ibu.";".$isi->alamat.";".$lu->kode_samsat.";HONDA;".$isi->tipe_ahm.";".$isi->no_rangka.";".$nosin_strip.";1;".$isi->warna_samsat.";".$isi->tahun.";".$re->isi_silinder.";2;2;0101;03;0000-00-00;41;".$wil->kode_samsat."";
	//echo "<br>";	
	echo "\r\n";	
}
//echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po'>";			  

?>      

