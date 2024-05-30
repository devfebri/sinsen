<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$no.".txt");
header("Pragma: no-cache");
header("Expires: 0");

$mo = date("m");
$ye = date("Y");
// $get_nosin 	= $this->db->query("SELECT *,tr_picking_list_view.no_mesin AS nosin FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list
// 			  INNER JOIN tr_scan_barcode ON tr_picking_list_view.no_mesin = tr_scan_barcode.no_mesin
// 			  INNER JOIN tr_invoice_dealer ON tr_invoice_dealer.no_do = tr_picking_list.no_do
// 			  INNER JOIN ms_item ON tr_picking_list_view.id_item = ms_item.id_item
// 	  		INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
// 	  		INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
// 	  		INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
// 	  		INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
// 			  INNER JOIN ms_gudang ON tr_do_po.id_gudang = ms_gudang.id_gudang				
// 	  		WHERE tr_invoice_dealer.tgl_cair='$tgl_cair'");

$get_nosin 	= $this->db->query("
	select tr_invoice_dealer.no_faktur , tr_invoice_dealer.tgl_faktur , tr_invoice_dealer.tgl_cair , tr_invoice_dealer.tgl_overdue ,  tr_scan_barcode.id_item,
	tr_invoice_dealer.no_do,tr_invoice_dealer.bunga_bank , ms_dealer.top_unit , tr_scan_barcode.no_mesin as nosin, tr_scan_barcode.no_rangka , ms_warna.warna , ms_tipe_kendaraan.deskripsi_ahm, ms_dealer.nama_dealer , ms_dealer.kode_dealer_md
	from tr_invoice_dealer 
	join tr_do_po on tr_invoice_dealer.no_do = tr_do_po.no_do 
	join tr_picking_list on tr_picking_list.no_do  = tr_do_po.no_do 
	join tr_picking_list_view on tr_picking_list_view.no_picking_list  = tr_picking_list.no_picking_list 
	join tr_scan_barcode on tr_scan_barcode.no_mesin = tr_picking_list_view.no_mesin
	join ms_warna on tr_scan_barcode.warna = ms_warna.id_warna 
	join ms_tipe_kendaraan on ms_tipe_kendaraan.id_tipe_kendaraan  = tr_scan_barcode.tipe_motor 
	join ms_dealer on ms_dealer.id_dealer = tr_do_po.id_dealer 
	WHERE tr_invoice_dealer.tgl_cair='$tgl_cair'");

foreach ($get_nosin->result() as $isi) {				
	// $inv = $this->db->query("SELECT * FROM tr_invoice_dealer WHERE no_do = '$isi->no_do'")->row();
	if($isi->tgl_faktur != "0000-00-00"){
	  $tgl1 = $isi->tgl_faktur;// pendefinisian tanggal awal
	  $tgl1_fix = date("mdY", strtotime($tgl1));
	  $top 	= $isi->top_unit;
	  $tgl2 = date("mdY", strtotime("+".$top." days", strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari                    
	}else{
	  $tgl2 = "";
	}

	$no_do=21;$tgl_faktur=8;$no_faktur=18;$tgl_cair=8;$kode_dealer_md=10;$nama_dealer=30;$tipe_barang=30;$no_rangka=17;$no_mesin=13;$warna=20;$no_po=10;	

	$r = "";
	$isi_no_do = $isi->no_do;
	$do_lth = strlen($isi_no_do);
	if($do_lth < 21){
		$jum = 21 - $do_lth;
		for ($i=1; $i <= $jum; $i++) { 
			$r = $r." ";
		}
		$do_fix = $isi_no_do.$r;
	}else{
		$do_fix = $isi_no_do;
	}

	$r = "";
	$isi_no_faktur = $isi->no_faktur;
	$faktur_lth = strlen($isi_no_faktur);
	if($faktur_lth < 18){
		$jum = 18 - $faktur_lth;
		for ($i=1; $i <= $jum; $i++) { 
			$r = $r." ";
		}
		$faktur_fix = $isi_no_faktur.$r;
	}else{
		$faktur_fix = $isi_no_faktur;
	}

	$r = "";
	$isi_tgl_faktur = $tgl1_fix;
	$tgl_faktur_lth = strlen($isi_tgl_faktur);
	if($tgl_faktur_lth < 8){
		$jum = 8 - $tgl_faktur_lth;
		for ($i=1; $i <= $jum; $i++) { 
			$r = $r." ";
		}
		$tgl_faktur_fix = $isi_tgl_faktur.$r;
	}else{
		$tgl_faktur_fix = $isi_tgl_faktur;
	}

	$r = "";
	$isi_tgl_cair = date("mdY", strtotime($isi->tgl_cair));
	$tgl_cair_lth = strlen($isi_tgl_cair);
	if($tgl_cair_lth < 8){
		$jum = 8 - $tgl_cair_lth;
		for ($i=1; $i <= $jum; $i++) { 
			$r = $r." ";
		}
		$tgl_cair_fix = $isi_tgl_cair.$r;
	}else{
		$tgl_cair_fix = $isi_tgl_cair;
	}
	

	$r = "";
	$isi_tgl_tempo = $tgl2;
	$tgl_tempo_lth = strlen($isi_tgl_tempo);
	if($tgl_tempo_lth < 8){
		$jum = 8 - $tgl_tempo_lth;
		for ($i=1; $i <= $jum; $i++) { 
			$r = $r." ";
		}
		$tgl_tempo_fix = $isi_tgl_tempo.$r;
	}else{
		$tgl_tempo_fix = $isi_tgl_tempo;
	}

	$r = "";
	$isi_kode_dealer = $isi->kode_dealer_md;
	$kode_dealer_lth = strlen($isi_kode_dealer);
	if($kode_dealer_lth < 10){
		$jum = 10 - $kode_dealer_lth;
		for ($i=1; $i <= $jum; $i++) { 
			$r = $r." ";
		}
		$kode_dealer_fix = $isi_kode_dealer.$r;
	}else{
		$kode_dealer_fix = $isi_kode_dealer;
	}

	$r = "";
	$isi_nama_dealer = $isi->nama_dealer;
	$nama_dealer_lth = strlen($isi_nama_dealer);
	if($nama_dealer_lth < 30){
		$jum = 30 - $nama_dealer_lth;
		for ($i=1; $i <= $jum; $i++) { 
			$r = $r." ";
		}
		$nama_dealer_fix = $isi_nama_dealer.$r;
	}else{
		$nama_dealer_fix = substr($isi_nama_dealer, 0, 30);
	}

	$r = "";
	$isi_tipe_ahm = $isi->deskripsi_ahm;
	$tipe_lth = strlen($isi_tipe_ahm);
	if($tipe_lth < 50){
		$jum = 50 - $tipe_lth;
		for ($i=1; $i <= $jum; $i++) { 
			$r = $r." ";
		}
		$tipe_fix = $isi_tipe_ahm.$r;
	}else{
		$tipe_fix = $isi_tipe_ahm;
	}

	$r = "";
	$isi_no_rangka = $isi->no_rangka;
	$rangka_lth = strlen($isi_no_rangka);
	if($rangka_lth < 17){
		$jum = 17 - $rangka_lth;
		for ($i=1; $i <= $jum; $i++) { 
			$r = $r." ";
		}
		$rangka_fix = $isi_no_rangka.$r;
	}else{
		$rangka_fix = $isi_no_rangka;
	}

	$r = "";
	$isi_no_mesin = $isi->nosin;
	$mesin_lth = strlen($isi_no_mesin);
	if($mesin_lth < 13){
		$jum = 13 - $mesin_lth;
		for ($i=1; $i <= $jum; $i++) { 
			$r = $r." ";
		}
		$mesin_fix = $isi_no_mesin.$r;
	}else{
		$mesin_fix = $isi_no_mesin;
	}

	$r = "";
	$isi_warna = $isi->warna;
	$warna_lth = strlen($isi_warna);
	if($warna_lth < 22){
		$jum = 22 - $warna_lth;
		for ($i=1; $i <= $jum; $i++) { 
			$r = $r." ";
		}
		$warna_fix = $isi_warna.$r;
	}else{
		$warna_fix = $isi_warna;
	}

  	$get_inv = $this->m_admin->get_detail_inv_dealer_dpp($isi->no_do,$isi->id_item); // bisa disederhanakan di top, bunga, dan df
	$qty_do         = $get_inv['detail'][$isi->id_item]['qty_do'];
	$harga         = $get_inv['detail'][$isi->id_item]['harga'];
	$diskon_top    = $get_inv['detail'][$isi->id_item]['diskon_top']/$qty_do;
	$subtotal      = $get_inv['detail'][$isi->id_item]['subtotal'];
	$ppn           = $get_inv['detail'][$isi->id_item]['ppn']/$qty_do;
	$diskon_satuan = $get_inv['detail'][$isi->id_item]['diskon_satuan'];
	$rumus_baru = ($harga-($diskon_top+$diskon_satuan))+$ppn;

	$tgl 						= date("Y-m-d");
	$pr_num 				= $this->db->query("SELECT * FROM tr_invoice_cek WHERE tgl = '$tgl' ORDER BY cast(id as SIGNED) desc LIMIT 0,1");						
	if($pr_num->num_rows()>0){
		$row 	= $pr_num->row();	
		$id 	= (int)$row->id + 1;
		$isi 	= sprintf("%'.03d",$id);
	}else{
	 	$isi 	= "001";
		$this->db->query("TRUNCATE TABLE tr_invoice_cek");
	} 			
	$this->db->query("INSERT INTO tr_invoice_cek VALUES ('$isi','$tgl')");

	echo "40  ".$faktur_fix."".$isi."".$tgl_faktur_fix."                    ".$faktur_fix."".$tgl_cair_fix."            ".$tgl_tempo_fix."".$kode_dealer_fix."".$nama_dealer_fix."".$tipe_fix."".$rangka_fix."".$mesin_fix."".$warna_fix."".round($rumus_baru);
	//echo "<br>";	
	echo "\r\n";	
}
//echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po'>";			  

?>      

