<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$no.".SSU");
header("Pragma: no-cache");
header("Expires: 0");

// $sql = $this->db->query("SELECT * FROM tr_ssu INNER JOIN tr_ssu_detail ON tr_ssu.id_ssu = tr_ssu_detail.id_ssu 
// 		INNER JOIN tr_sales_order ON tr_ssu_detail.no_mesin = tr_sales_order.no_mesin
// 		INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk WHERE tr_ssu.id_ssu = '$id_ssu'");
$sql = $this->db->query("SELECT *,tr_sales_order.tgl_create_ssu FROM tr_ssu_detail
JOIN tr_ssu ON tr_ssu.id_ssu=tr_ssu_detail.id_ssu
JOIN tr_sales_order ON tr_sales_order.no_mesin=tr_ssu_detail.no_mesin
JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
WHERE '$hari_ini' BETWEEN start_date AND end_date
GROUP BY tr_ssu_detail.no_mesin");

foreach ($sql->result() as $isi) {	
	$scan = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$isi->no_mesin);	
	if($scan->num_rows() > 0){
		$r = $scan->row();
		$tipe = $r->tipe;
		if($tipe == 'PINJAMAN') $tipe = 'NRFS';
		$tgl_penerimaan = $r->tgl_penerimaan;
		$tanggal_p = date("dmY", strtotime($tgl_penerimaan));    
	}else{	
		$tipe = "";	
		$tanggal_p = "";
	}
	$dealer = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
					INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
					INNER JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer
					WHERE tr_penerimaan_unit_dealer_detail.no_mesin = '$isi->no_mesin'");	
	if($dealer->num_rows() > 0){
		$d = $dealer->row();
		$kode_dealer_md = $d->kode_dealer_md;
		if($kode_dealer_md=='PSB'){
			$kode_dealer_md = '13384';
		}		
		$tgl_terima = date("dmY", strtotime($d->tgl_penerimaan));    
	}else{
		$kode_dealer_md = "";
		$tgl_terima = "";
	}

	$cek_md = $this->db->query("SELECT * FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
					INNER JOIN tr_invoice_dealer ON tr_picking_list.no_do = tr_invoice_dealer.no_do
					WHERE tr_picking_list_view.no_mesin = '$isi->no_mesin'");
	if($cek_md->num_rows() > 0){
		$y = $cek_md->row();		
		$tgl_md = date("dmY", strtotime($y->tgl_faktur));
	}else{
		$tgl_md = "";
	}

	if($tgl_md==""){
		$tgl_md = date("dmY", strtotime($scan->row()->tgl_faktur_invoice));
	}

	$waktu								= gmdate("Y-m-d h:i:s", time()+60*60*7);    
	$login_id							= $this->session->userdata('id_user');
	
	$dat['generate_ssu']	= $login_id;
	$dat['generate_date']	= $waktu;
	//$cek3 = $this->m_admin->update("tr_sales_order",$dat,"no_mesin",$isi->no_mesin);											

	$tgl_cetak_invoice = date("dmY", strtotime($isi->tgl_cetak_invoice));		
	$tgl_create_ssu = date("dmY", strtotime($isi->tgl_create_ssu));
	$id_kelurahan = $isi->id_kelurahan2;
	$prov = $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
		WHERE ms_kelurahan.id_kelurahan = '$id_kelurahan'");
	if($prov->num_rows() > 0){
		$pro = $prov->row();
		$id_provinsi = $pro->id_provinsi;
		$id_kabupaten = $pro->id_kabupaten;
		$id_kecamatan = $pro->id_kecamatan;
		$id_kelurahan = $pro->id_kelurahan;
	}else{
		$id_provinsi = "";$id_kelurahan="";$id_kecamatan="";$id_provinsi="";
	}

	$tr_prospek = $this->m_admin->getByID("tr_prospek","id_customer",$isi->id_customer);
	if($tr_prospek->num_rows() > 0){
		$r = $tr_prospek->row();
		$id_flp = $r->id_flp_md;
	}else{
		$id_flp = "";
	}

	if($isi->jenis_beli == 'Cash'){
		$jenis_beli = 1;
		$dp_stor = "";
		$tenor = "";
		$angsuran = "";
		$id_finance_company = '';
	}else{
		$dp_stor = $isi->dp_stor;
		$tenor = $isi->tenor;
		if($isi->id_finance_company != '' OR $isi->id_finance_company != '- Choose -' OR $isi->id_finance_company != ' - Choose - '){
			$id_finance_company = $isi->id_finance_company;
		}else{
			$id_finance_company = '';
		}
		$angsuran = $isi->angsuran;
		$jenis_beli = 2;
	}

	$sj = $this->db->query("SELECT * FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
		WHERE tr_surat_jalan_detail.no_mesin = '$isi->no_mesin'");
	$tgl_sj = "";
	if($sj->num_rows() > 0){
		$tgl_sj = date("dmY", strtotime($sj->row()->tgl_surat));				
	}

	$tgl_spes_md = "";
	$tgl_sp = $this->db->query("SELECT DISTINCT(tr_sipb.no_sipb),tgl_spes FROM tr_shipping_list INNER JOIN tr_sipb ON tr_shipping_list.no_sipb = tr_sipb.no_sipb 
		WHERE tr_shipping_list.no_mesin = '$isi->no_mesin'");
	if($tgl_sp->num_rows() > 0){
		$tgl_spes_md = $tgl_sp->row()->tgl_spes;				
	}

	echo "E20;".$isi->no_mesin.";".$isi->no_rangka.";".$tipe.";".$tanggal_p.";".$tgl_sj.";".$kode_dealer_md.";".$tgl_spes_md.";".$tgl_md.";".$tgl_create_ssu.";".$tgl_cetak_invoice.";".$tgl_cetak_invoice.";".$jenis_beli.";".$id_finance_company.";".$dp_stor.";".$tenor.";".$angsuran.";".$tgl_terima.";I;".$id_provinsi.";".$id_kabupaten.";".$id_kecamatan.";".$id_kelurahan.";".$id_flp.";;";
	echo "\r\n";		
	//echo "<br>";
}


// $sql = $this->db->query("SELECT * FROM tr_ssu INNER JOIN tr_ssu_detail ON tr_ssu.id_ssu = tr_ssu_detail.id_ssu 
// 		INNER JOIN tr_sales_order_gc_nosin ON tr_ssu_detail.no_mesin = tr_sales_order_gc_nosin.no_mesin
// 		INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
// 		INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
// 		INNER JOIN tr_spk_gc ON tr_sales_order_gc_nosin.no_spk_gc = tr_spk_gc.no_spk_gc 
// 		INNER JOIN tr_spk_gc_detail ON tr_spk_gc.no_spk_gc = tr_spk_gc_detail.no_spk_gc
// 		WHERE tr_ssu.id_ssu = '$id_ssu'");
$sql = $this->db->query("SELECT *,tr_sales_order_gc.tgl_create_ssu FROM tr_ssu_detail
				JOIN tr_ssu ON tr_ssu.id_ssu=tr_ssu_detail.id_ssu
				INNER JOIN tr_sales_order_gc_nosin ON tr_ssu_detail.no_mesin = tr_sales_order_gc_nosin.no_mesin
				INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
				INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
				INNER JOIN tr_spk_gc ON tr_sales_order_gc_nosin.no_spk_gc = tr_spk_gc.no_spk_gc 
				INNER JOIN tr_spk_gc_detail ON tr_spk_gc.no_spk_gc = tr_spk_gc_detail.no_spk_gc
				WHERE '$hari_ini' BETWEEN start_date AND end_date
				GROUP BY tr_ssu_detail.no_mesin");
foreach ($sql->result() as $isi) {	
	$scan = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$isi->no_mesin);	
	if($scan->num_rows() > 0){
		$r = $scan->row();
		$tipe = $r->tipe;
		if($tipe == 'PINJAMAN') $tipe = 'NRFS';
		$tgl_penerimaan = $r->tgl_penerimaan;
		$tanggal_p = date("dmY", strtotime($tgl_penerimaan));    
	}else{	
		$tipe = "";	
		$tanggal_p = "";
	}
	$dealer = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
					INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
					INNER JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer
					WHERE tr_penerimaan_unit_dealer_detail.no_mesin = '$isi->no_mesin'");	
	if($dealer->num_rows() > 0){
		$d = $dealer->row();
		$kode_dealer_md = $d->kode_dealer_md;	
		if($kode_dealer_md=='PSB'){
			$kode_dealer_md = '13384';
		}	
		$tgl_terima = date("dmY", strtotime($d->tgl_penerimaan));    
	}else{
		$kode_dealer_md = "";
		$tgl_terima = "";
	}

	$cek_md = $this->db->query("SELECT * FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
					INNER JOIN tr_invoice_dealer ON tr_picking_list.no_do = tr_invoice_dealer.no_do
					WHERE tr_picking_list_view.no_mesin = '$isi->no_mesin'");
	if($cek_md->num_rows() > 0){
		$y = $cek_md->row();		
		$tgl_md = date("dmY", strtotime($y->tgl_faktur));
	}else{
		$tgl_md = "";
	}

	if($tgl_md==""){
		$tgl_md = date("dmY", strtotime($scan->row()->tgl_faktur_invoice));
	}

	$waktu								= gmdate("Y-m-d h:i:s", time()+60*60*7);    
	$login_id							= $this->session->userdata('id_user');
	
	$dat['generate_ssu']	= $login_id;
	$dat['generate_date']	= $waktu;
	//$cek3 = $this->m_admin->update("tr_sales_order_gc",$dat,"id_sales_order_gc",$isi->id_sales_order_gc);											

	$tgl_cetak_invoice = date("dmY", strtotime($isi->tgl_cetak_invoice));		
	$tgl_create_ssu = date("dmY", strtotime($isi->tgl_create_ssu));
	$id_kelurahan = $isi->id_kelurahan2;
	$prov = $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
		WHERE ms_kelurahan.id_kelurahan = '$id_kelurahan'");
	if($prov->num_rows() > 0){
		$pro = $prov->row();
		$id_provinsi = $pro->id_provinsi;
		$id_kabupaten = $pro->id_kabupaten;
		$id_kecamatan = $pro->id_kecamatan;
		$id_kelurahan = $pro->id_kelurahan;
	}else{
		$id_provinsi = "";$id_kelurahan="";$id_kecamatan="";$id_provinsi="";
	}

	$tr_prospek = $this->m_admin->getByID("tr_prospek_gc","id_prospek_gc",$isi->id_prospek_gc);
	if($tr_prospek->num_rows() > 0){
		$r = $tr_prospek->row();
		$id_flp = $r->id_flp_md;
	}else{
		$id_flp = "";
	}

	if($isi->jenis_beli == 'Cash'){
		$jenis_beli = 1;
		$dp_stor = "";
		$tenor = "";
		$angsuran = "";
		$id_finance_company = '';
	}else{
		$dp_stor = $isi->dp_stor;
		$tenor = $isi->tenor;
		if($isi->id_finance_company != '' OR $isi->id_finance_company != '- Choose -' OR $isi->id_finance_company != ' - Choose - '){
			$id_finance_company = $isi->id_finance_company;
		}else{
			$id_finance_company = '';
		}
		$angsuran = $isi->angsuran;
		$jenis_beli = 2;
	}

	$sj = $this->db->query("SELECT * FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.id_surat_jalan = tr_surat_jalan.id_surat_jalan
		WHERE tr_surat_jalan_detail.no_mesin = '$isi->no_mesin'");
	$tgl_sj = "";
	if($sj->num_rows() > 0){
		$tgl_sj = date("dmY", strtotime($sj->row()->tgl_surat));				
	}

	$tgl_spes_md = "";
	$tgl_sp = $this->db->query("SELECT DISTINCT(tr_sipb.no_sipb),tgl_spes FROM tr_shipping_list INNER JOIN tr_sipb ON tr_shipping_list.no_sipb = tr_sipb.no_sipb 
		WHERE tr_shipping_list.no_mesin = '$isi->no_mesin'");
	if($tgl_sp->num_rows() > 0){
		$tgl_spes_md = $tgl_sp->row()->tgl_spes;				
	}

	echo "E20;".$isi->no_mesin.";".$isi->no_rangka.";".$tipe.";".$tanggal_p.";".$tgl_sj.";".$kode_dealer_md.";".$tgl_spes_md.";".$tgl_md.";".$tgl_create_ssu.";".$tgl_cetak_invoice.";".$tgl_cetak_invoice.";".$jenis_beli.";".$id_finance_company.";".$dp_stor.";".$tenor.";".$angsuran.";".$tgl_terima.";G;".$id_provinsi.";".$id_kabupaten.";".$id_kecamatan.";".$id_kelurahan.";".$id_flp.";;";
	echo "\r\n";		
	//echo "<br>";
}


$tr_scan = $this->db->query("SELECT * FROM tr_scan_barcode WHERE status BETWEEN 1 AND 3");
foreach ($tr_scan->result() as $isi) {	
	$md = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$isi->no_mesin'");	
	if($md->num_rows() > 0){
		$d = $md->row();
		$tipe = $d->tipe;
		if($tipe == 'PINJAMAN') $tipe = 'NRFS';
		$tgl_penerimaan = $d->tgl_penerimaan;
		$tanggal_p = date("dmY", strtotime($tgl_penerimaan));		
	}else{
		$tipe = "";
		$tgl_penerimaan = "";
		$tanggal_p = "";
	}
	$tgl_spes_md = "";
	$tgl_sp = $this->db->query("SELECT DISTINCT(tr_sipb.no_sipb),tgl_spes FROM tr_shipping_list INNER JOIN tr_sipb ON tr_shipping_list.no_sipb = tr_sipb.no_sipb 
		WHERE tr_shipping_list.no_mesin = '$isi->no_mesin'");
	if($tgl_sp->num_rows() > 0){
		$tgl_spes_md = $tgl_sp->row()->tgl_spes;				
	}
	
	echo "E20;".$isi->no_mesin.";".$isi->no_rangka.";".$tipe.";".$tanggal_p.";;;;".$tgl_spes_md.";;;;;;;;;;;;;;;;;";
	echo "\r\n";		
	//echo "<br>";		
}
$tr_scan = $this->db->query("SELECT * FROM tr_scan_barcode WHERE status = 4");
foreach ($tr_scan->result() as $isi) {	
	$dealer = $this->db->query("SELECT tr_penerimaan_unit_dealer.*,ms_dealer.*,tr_scan_barcode.tipe FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
					INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
					INNER JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer
					WHERE tr_penerimaan_unit_dealer_detail.no_mesin = '$isi->no_mesin'");	
	if($dealer->num_rows() > 0){
		$d = $dealer->row();
		$kode_dealer_md = $d->kode_dealer_md;
		if($kode_dealer_md=='PSB'){
			$kode_dealer_md = '13384';
		}		
		$tgl_surat = $d->tgl_surat_jalan;
		$tgl_md_out = date("dmY", strtotime($tgl_surat));		
		$tgl_pm = $d->tgl_penerimaan;
		$tgl_dealer = date("dmY", strtotime($tgl_pm));		
	}else{
		$kode_dealer_md = "";				
		$tanggal_p = "";
		$tgl_md_out = "";
		$tgl_dealer = "";
	}	
	
	$tipe = $isi->tipe;
	if($tipe == 'PINJAMAN') $tipe = 'NRFS';		
	$tgl_penerimaan = $isi->tgl_penerimaan;
	$tanggal_p = date("dmY", strtotime($tgl_penerimaan));		
	
	$cek_sj = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
		WHERE tr_surat_jalan_detail.no_mesin = '$isi->no_mesin'");
	if($cek_sj->num_rows() > 0){
		$t = $cek_sj->row();		
	}else{		
	}
	
	if($tgl_md==""){
		$tgl_md = date("dmY", strtotime($scan->row()->tgl_faktur_invoice));
	}
	$tgl_spes_md = "";
	$tgl_sp = $this->db->query("SELECT DISTINCT(tr_sipb.no_sipb),tgl_spes FROM tr_shipping_list INNER JOIN tr_sipb ON tr_shipping_list.no_sipb = tr_sipb.no_sipb 
		WHERE tr_shipping_list.no_mesin = '$isi->no_mesin'");
	if($tgl_sp->num_rows() > 0){
		$tgl_spes_md = $tgl_sp->row()->tgl_spes;				
	}

	echo "E20;".$isi->no_mesin.";".$isi->no_rangka.";".$tipe.";".$tanggal_p.";".$tgl_md_out.";".$kode_dealer_md.";".$tgl_spes_md.";".$tgl_md.";;;;;;;;;".$tgl_dealer.";;;;;;;;";
	echo "\r\n";		
	//echo "<br>";		
}
?>      

