<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$no.".UPO");
header("Pragma: no-cache");
header("Expires: 0");

$sql = $this->db->query("SELECT tr_po_detail.*,tr_po.jenis_po,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.warna,ms_warna.id_warna,bulan,tahun FROM tr_po_detail INNER JOIN ms_item 
						ON tr_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan						
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna INNER JOIN tr_po
						On tr_po_detail.id_po=tr_po.id_po
						WHERE tr_po_detail.id_po = '$id'");

$isi2 = $this->db->query("SELECT ms_karyawan_dealer.*,ms_dealer.kode_dealer_md as kode FROM ms_karyawan_dealer INNER JOIN ms_dealer
					ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer WHERE ms_karyawan_dealer.id_karyawan_dealer = '$k'")->row();
// $mo = date("m")+1;
// $ye = date("Y");
foreach ($sql->result() as $isi) {
	if($isi->jenis_po == 'PO Reguler'){
		$id_jenis_po = "F";
	}else{
		$id_jenis_po = "A";
	}
	$hari_ini 	= date("d"); 
	// $tgl 		= date('t', strtotime($hari_ini));
	// $tgl 		= date('t', strtotime($hari_ini));
	// $my  = tambah_dmy('bulan', 1, "$isi->tahun-$isi->bulan-$hari_ini");
	// $mo = $my['bulan'];
	// $ye = $my['tahun'];
	$mo = sprintf("%'.02d",$isi->bulan);
	$ye = sprintf("%'.02d",$isi->tahun);
	$tgl_awal 	= "01".$mo.$ye;
	$tgl_akhir 	= days_in_month($mo,$ye).$mo.$ye;
	if(isset($kode)){
		$kode_r 			= (int)$isi2->kode;
	}else{
		$kode_r = "E20";
	}
	if($isi->jenis_po == 'PO Reguler'){
		echo $kode_r.";".$mo.";".$ye.";".$isi->id_tipe_kendaraan.";".$isi->id_warna.";".$isi->qty_po_fix.";".$isi->qty_po_t1.";".$isi->qty_po_t2.";".$isi->id_po.";".$id_jenis_po.";".$tgl_awal.";".$tgl_akhir;
		echo "\r\n";	
	}else{
		$tgl = days_in_month($isi->bulan,$isi->tahun);
		$tgl_awal 	= "01".sprintf("%'.02d",$isi->bulan).$isi->tahun;
		$tgl_akhir 	= $tgl.sprintf("%'.02d",$isi->bulan).$isi->tahun;
		// echo $kode_r.";".$mo.";".$ye.";".$isi->id_tipe_kendaraan.";".$isi->id_warna.";".$isi->qty_order.";".$isi->id_po.";".$id_jenis_po.";".$tgl_awal.";".$tgl_akhir;
		echo $kode_r.";".sprintf("%'.02d",$isi->bulan).";".$ye.";".$isi->id_tipe_kendaraan.";".$isi->id_warna.";".$isi->qty_order.";0;0;".$isi->id_po.";".$id_jenis_po.";".$tgl_awal.";".$tgl_akhir;
		echo "\r\n";	
	}	
}
//echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po'>";			  

?>      

