<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$nama_file.".USTK");
header("Pragma: no-cache");
header("Expires: 0");

// $sql = $this->db->query("SELECT * FROM tr_ustk INNER JOIN tr_ustk_detail ON tr_ustk.id_ustk = tr_ustk_detail.id_ustk
// 		INNER JOIN tr_sales_order ON tr_ustk_detail.no_mesin = tr_sales_order.no_mesin			
// 		INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk		
// 		WHERE tr_ustk.id_ustk = '$id_ustk'");
$isi_data_fix_3 = "";
$tot_so_in = $data_generate['so_in']->num_rows();
$no=0;
foreach ($data_generate['so_in']->result() as $isi) {	
$no++;
	$nosin5 = substr($isi->no_mesin, 0,5);    
	$nosin7 = substr($isi->no_mesin, 5,7);
	
	if($isi->tgl_cetak_invoice != "" OR $isi->tgl_cetak_invoice != NULL){		
		$tgl_ 			= $isi->tgl_cetak_invoice;					
		$tgl_mohon 	= date("dmY", strtotime($tgl_));		
		$tgl_up  		= date('dmY', strtotime('+7 days', strtotime($tgl_)));
	}else{
		$tgl_mohon = "";$tgl_up = "";
	}    
	$asal = $this->db->query("SELECT * FROM tr_spk INNER JOIN tr_sales_order ON tr_spk.no_spk = tr_sales_order.no_spk
		WHERE tr_sales_order.no_mesin = '$isi->no_mesin'");
	if($asal->num_rows() > 0){
		$a = $asal->row();
		$kelurahan = $a->id_kelurahan2;
		$region = explode("-",$this->m_admin->getRegion($kelurahan));             			

		$kecamatan = $region[1];
		$kabupaten = $region[2];
		$provinsi  = $region[3];
		$kodepos 	 = $a->kodepos;
		$jenis_beli 	 = $a->jenis_beli;		
		if($jenis_beli == 'Cash'){
			$jenis_beli = 1;
		}else{
			$jenis_beli = 2;
		}
		$no_ktp 	 	= $a->no_ktp;		
	}else{
		$kelurahan = "";
		$kecamatan = "";
		$kabupaten = "";
		$provinsi  = "";
		$kodepos 	 = "";
		$jenis_beli = "";		
		$no_ktp = "";		
	}
	$dealer = $this->m_admin->getByID("ms_dealer","id_dealer",$isi->id_dealer);	
	if($dealer->num_rows() > 0){
		$d = $dealer->row();
		$kode_dealer_md = $d->kode_dealer_md;
		if ($d->id_dealer_induk!=0) {
			$kode_dealer_md = $d->kode_dealer_ahm;
		}
		if($kode_dealer_md=='PSB'){
			$kode_dealer_md = '13384';
		}
	}else{
		$kode_dealer_md = "";
	}

	$fm = $this->m_admin->getByID("tr_fkb","no_mesin_spasi",$isi->no_mesin);	
	if($fm->num_rows() > 0){
		$f = $fm->row();
		$no_faktur = $f->nomor_faktur;
		if (strlen($no_faktur)<13) {
			$no_faktur_exp = explode(' ', $no_faktur);
			$tot       = strlen($no_faktur_exp[0]) + strlen($no_faktur_exp[1]);
			$kurang    = strlen($no_faktur)-$tot;
			$tbh_spasi = '';
			for ($i = 0; $i <=$kurang; $i++) {
				$tbh_spasi .=" ";
			}
			$no_faktur = $no_faktur_exp[0].$tbh_spasi.$no_faktur_exp[1];
		}
	}else{
		$no_faktur = "";
	}

	$no_ktp = $isi->no_ktp;
	echo $no_faktur.";".$isi->no_rangka.";".$nosin5." ;".$nosin7.";".$tgl_up.";".$tgl_mohon.";".$isi->nama_konsumen.";".$isi->alamat.";".$kelurahan.";".$kecamatan.";".$kabupaten.";".$kodepos.";".$provinsi.";".$jenis_beli.";".$kode_dealer_md.";".$no_ktp.";";
	if ($no<$tot_so_in) {
		echo "\r\n";
	}		
	//echo "<br>";		
}

// $sql_gc = $this->db->query("SELECT * FROM tr_ustk INNER JOIN tr_ustk_detail ON tr_ustk.id_ustk = tr_ustk_detail.id_ustk
// 		INNER JOIN tr_sales_order_gc_nosin ON tr_ustk_detail.no_mesin = tr_sales_order_gc_nosin.no_mesin			
// 		INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
// 		INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
// 		INNER JOIN tr_spk_gc ON tr_sales_order_gc_nosin.no_spk_gc = tr_spk_gc.no_spk_gc		
// 		WHERE tr_ustk.id_ustk = '$id_ustk'");
$isi_data_fix_3 = "";
$tot_so_gc = $data_generate['so_gc']->num_rows();
$no=0;
if ($data_generate['so_gc']->num_rows()>0) {
	echo "\r\n";
}
foreach ($data_generate['so_gc']->result() as $isi) {	
$no++;
	$nosin5 = substr($isi->no_mesin, 0,5);    
	$nosin7 = substr($isi->no_mesin, 5,7);
	
	if($isi->tgl_cetak_invoice != "" OR $isi->tgl_cetak_invoice != NULL){		
		$tgl_ 			= $isi->tgl_cetak_invoice;					
		$tgl_mohon 	= date("dmY", strtotime($tgl_));		
		$tgl_up  		= date('dmY', strtotime('+7 days', strtotime($tgl_)));
	}else{
		$tgl_mohon = "";$tgl_up = "";
	}    
	
		$kelurahan = $isi->id_kelurahan2;
		$region = explode("-",$this->m_admin->getRegion($kelurahan));             			

		$kecamatan = $region[1];
		$kabupaten = $region[2];
		$provinsi  = $region[3];
		$kodepos 	 = $isi->kodepos;
		$jenis_beli 	 = $isi->jenis_beli;		
		if($jenis_beli == 'Cash'){
			$jenis_beli = 1;
		}else{
			$jenis_beli = 2;
		}
		$no_ktp 	 	= "";		
	
	$dealer = $this->m_admin->getByID("ms_dealer","id_dealer",$isi->id_dealer);	
	if($dealer->num_rows() > 0){
		$d = $dealer->row();
		$kode_dealer_md = $d->kode_dealer_md;
		if ($d->id_dealer_induk!=0) {
			$kode_dealer_md = $d->kode_dealer_ahm;
		}
		if($kode_dealer_md=='PSB'){
			$kode_dealer_md = '13384';
		}
	}else{
		$kode_dealer_md = "";
	}

	$fm = $this->m_admin->getByID("tr_fkb","no_mesin_spasi",$isi->no_mesin);	
	if($fm->num_rows() > 0){
		$f = $fm->row();
		$no_faktur = $f->nomor_faktur;
	}else{
		$no_faktur = "";
	}

	$no_ktp = "";
	echo $no_faktur.";".$isi->no_rangka.";".$nosin5." ;".$nosin7.";".$tgl_up.";".$tgl_mohon.";".$isi->nama_npwp.";".$isi->alamat.";".$kelurahan.";".$kecamatan.";".$kabupaten.";".$kodepos.";".$provinsi.";".$jenis_beli.";".$kode_dealer_md.";".$no_ktp.";";
	if ($no<$tot_so_gc) {
		echo "\r\n";
	}		
}			
?>