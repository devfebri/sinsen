<?php
$file_url = $no.".txt";
header("Content-type: application/octet-stream");
header('Content-Disposition: attachment; filename="' .basename($file_url).'"');
//header("Content-Disposition: attachment; filename=".$no.".txt");
header("Pragma: no-cache");
header("Expires: 0");

$query = $this->db->query("SELECT *,tr_pengajuan_bbn_detail.no_mesin as mesin FROM tr_pengajuan_bbn_detail 
						INNER JOIN tr_scan_barcode ON tr_pengajuan_bbn_detail.no_mesin = tr_scan_barcode.no_mesin
						INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
						INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna 						
						WHERE tr_pengajuan_bbn_detail.tgl_mohon_samsat = '$tanggal' AND (tr_pengajuan_bbn_detail.status_bbn = '' OR tr_pengajuan_bbn_detail.status_bbn IS NULL)");			

foreach ($query->result() as $isi) {				  
	$nosin_spasi = substr_replace($isi->mesin," ", 5, -strlen($isi->mesin));
	$nosin_strip = substr_replace($isi->mesin,"-", 5, -strlen($isi->mesin));	
  $rw = $this->m_admin->getByID("tr_fkb","no_mesin",$nosin_spasi);
  if($rw->num_rows() > 0){
    $ry = $rw->row();
    $no_fkb = 'FH/'.str_replace(' ', '', $ry->nomor_faktur);
    $tahun_produksi = $ry->tahun_produksi;
    $isi_silinder = $ry->isi_silinder;
  }else{
    $no_fkb = "";
    $tahun_produksi = "";
    $isi_silinder = "";    
  }
  $lu = $this->m_admin->getByID("ms_kelurahan","id_kelurahan",$isi->id_kelurahan)->row();
  //$tanggal = date("d/m/Y", strtotime($re->tgl_upload));
  $tgl_jual = $isi->tgl_mohon_samsat;
  $wil = $this->db->query("SELECT ms_kabupaten.*,kelurahan,kecamatan FROM ms_kabupaten INNER JOIN ms_kecamatan ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
		INNER JOIN ms_kelurahan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan 
		WHERE ms_kelurahan.id_kelurahan = '$lu->id_kelurahan'")->row();
	$cek_biaya_bbn_md_bj = $this->db->query("SELECT * FROM ms_bbn_biro WHERE id_tipe_kendaraan = '$isi->id_tipe_kendaraan' AND tahun_produksi = '$isi->tahun'");
	if($cek_biaya_bbn_md_bj->num_rows() > 0){
		$gt = $cek_biaya_bbn_md_bj->row();
		$cek_faktur = $this->db->query("SELECT * FROM tr_faktur_stnk INNER JOIN tr_faktur_stnk_detail ON tr_faktur_stnk.no_bastd = tr_faktur_stnk_detail.no_bastd
			WHERE tr_faktur_stnk.no_bastd = '$isi->no_bastd'")->row()->id_sales_order;
		$rt = explode("-", $cek_faktur);
		if($rt[0] == 'GC'){
			$tipe_customer = "Instansi";
			$no_ktp = $isi->npwp;
		}else{
			$tipe_customer = "Customer Umum";
			$no_ktp = $isi->no_ktp;
		}
		if ($tipe_customer =='Customer Umum') {
			$biaya_bbn_md_bj = $gt->biaya_bbn;
		}elseif ($tipe_customer == 'Instansi') {
			$biaya_bbn_md_bj = $gt->biaya_instansi;
		}
	}else{
		$biaya_bbn_md_bj = 0;		
	}
	$this->db->query("UPDATE tr_pengajuan_bbn_detail SET id_generate = '$id_generate', status_bbn = 'generated', biaya_bbn_md_bj='$biaya_bbn_md_bj' WHERE no_faktur = '$isi->no_faktur'");
	echo $no_fkb.";".$tgl_jual.";".$isi->nama_konsumen.";".$isi->pekerjaan.";".$no_ktp.";".$isi->nama_ibu.";".$isi->alamat." KEL. ".strtoupper($isi->kelurahan)." KEC. ".strtoupper($isi->kecamatan)." ".strtoupper($wil->kabupaten).";".$lu->kode_samsat.";HONDA;".$isi->deskripsi_samsat.";MH1".$isi->no_rangka.";".$nosin_strip.";1;".$isi->warna_samsat.";".$tahun_produksi.";".$isi_silinder.";2;2;0101;03;".$isi->tgl_ibu.";41;".$wil->kode_samsat."";
	//echo "<br>";	
	echo "\r\n";	
}
$sql = $this->db->query("SELECT *,tr_bantuan_bbn.no_mesin AS mesin,tr_bantuan_bbn.id_tipe_kendaraan AS tipe FROM tr_bantuan_bbn				
				INNER JOIN ms_tipe_kendaraan ON tr_bantuan_bbn.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				LEFT JOIN ms_warna ON tr_bantuan_bbn.id_warna = ms_warna.id_warna 						
			 	WHERE tr_bantuan_bbn.status = 'approved' AND tr_bantuan_bbn.tgl_samsat = '$tanggal'");

foreach ($sql->result() as $row) {				  
	$nosin_spasi = substr_replace($row->mesin," ", 5, -strlen($row->mesin));
	$nosin_strip = substr_replace($row->mesin,"-", 5, -strlen($row->mesin));	
  $rw = $this->m_admin->getByID("tr_utc","id_tipe_kendaraan",$row->id_tipe_kendaraan);
  if($rw->num_rows() > 0){
    $ry = $rw->row();  
    $isi_silinder = $ry->cc_motor;
  }else{    
    $isi_silinder = "";    
  }
  $lu = $this->m_admin->getByID("ms_kelurahan","id_kelurahan",$row->id_kelurahan)->row();
  //$tanggal = date("d/m/Y", strtotime($re->tgl_upload));
  $tgl_jual = $row->tgl_samsat;
  $wil = $this->db->query("SELECT ms_kabupaten.*,kelurahan,kecamatan FROM ms_kabupaten INNER JOIN ms_kecamatan ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
		INNER JOIN ms_kelurahan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan 
		WHERE ms_kelurahan.id_kelurahan = '$lu->id_kelurahan'")->row();
	$cek_biaya_bbn_md_bj = $this->db->query("SELECT * FROM ms_bbn_biro WHERE id_tipe_kendaraan = '$row->tipe' AND tahun_produksi = '$row->tahun_produksi'");

	if($cek_biaya_bbn_md_bj->num_rows() > 0){
		$gt = $cek_biaya_bbn_md_bj->row();
		$tipe_customer = "Customer Umum";
		if ($tipe_customer =='Customer Umum') {
			$biaya_bbn_md_bj = $gt->biaya_bbn;
		}elseif ($tipe_customer == 'Instansi') {
			$biaya_bbn_md_bj = $gt->biaya_instansi;
		}
	}else{
		$biaya_bbn_md_bj = 0;		
	}

	$cek_kerja = $this->m_admin->getByID("ms_pekerjaan","id_pekerjaan",$row->pekerjaan);
	if($cek_kerja->num_rows() > 0){
		$rt = $cek_kerja->row();
		$pekerjaan = $rt->pekerjaan;
	}else{
		$pekerjaan = "";
	}
	
	$tanggal_p = date("d/m/Y", strtotime($row->tgl_faktur));    
	$tanggal_ibu = date("d/m/Y", strtotime($row->tgl_ibu));    

	$this->db->query("UPDATE tr_bantuan_bbn SET id_generate = '$id_generate', status = 'generated', biaya_bbn_md_bj='$biaya_bbn_md_bj' WHERE no_faktur = '$row->no_faktur'");
	$no_fkb = 'FH/'.str_replace(' ', '', $row->no_faktur);
	echo $no_fkb.";".$tanggal_p.";".$row->nama_konsumen.";".$pekerjaan.";".$row->no_ktp.";".$row->nama_ibu.";".$row->alamat." KEL. ".strtoupper($wi->kelurahan)." KEC. ".strtoupper($wi->kecamatan)." ".strtoupper($wi->kabupaten).";".$lu->kode_samsat.";HONDA;".$row->deskripsi_samsat.";MH1".$row->no_rangka.";".$nosin_strip.";1;".$row->warna_samsat.";".$row->tahun_produksi.";".$isi_silinder.";2;2;0101;03;".$tanggal_ibu.";41;".$wil->kode_samsat."";
	//echo "<br>";	
	echo "\r\n";	
}	

//pekerjaan,tgl_ibu,id_kelurahan,
?>      

