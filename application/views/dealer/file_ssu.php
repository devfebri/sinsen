<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$no.".SSU");
header("Pragma: no-cache");
header("Expires: 0");

$sql = $this->db->query("SELECT * FROM tr_ssu INNER JOIN tr_ssu_detail ON tr_ssu.id_ssu = tr_ssu_detail.id_ssu 
		INNER JOIN tr_sales_order ON tr_ssu_detail.no_mesin = tr_sales_order.no_mesin
		INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk WHERE tr_ssu.id_ssu = '$id_ssu'");
foreach ($sql->result() as $isi) {	
	$scan = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$isi->no_mesin);	
	if($scan->num_rows() > 0){
		$r = $scan->row();
		$tipe = $r->tipe;
		$tgl_penerimaan = $r->tgl_penerimaan;
	}else{	
		$tipe = "";
		$tgl_penerimaan = "";
	}
	$dealer = $this->m_admin->getByID("ms_dealer","id_dealer",$isi->id_dealer);	
	if($dealer->num_rows() > 0){
		$d = $dealer->row();
		$kode_dealer_md = $d->kode_dealer_md;
	}else{
		$kode_dealer_md = "";
	}

	$cek_md = $this->db->query("SELECT * FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
					INNER JOIN tr_invoice_dealer ON tr_picking_list.no_do = tr_invoice_dealer.no_do
					WHERE tr_picking_list_view.no_mesin = '$isi->no_mesin'");
	if($cek_md->num_rows() > 0){
		$y = $cek_md->row();
		$tgl_md = $y->tgl_faktur;
	}else{
		$tgl_md = "";
	}
	

	echo "E20;".$isi->no_mesin.";".$isi->no_rangka.";".$tipe.";".$tgl_penerimaan.";".$isi->tgl_cetak_invoice.";".$kode_dealer_md.";;".$tgl_md.";".$isi->tgl_create_ssu.";".$isi->tgl_cetak_invoice.";".$isi->jenis_beli.";".$isi->id_finance_company.";;".$isi->dp_stor.";".$isi->tenor.";".$isi->angsuran.";;".$isi->id_customer;
	echo "\r\n";		
}
$tr_scan = $this->db->query("SELECT * FROM tr_scan_barcode WHERE status BETWEEN 1 AND 4");
foreach ($tr_scan->result() as $isi) {
	echo "E20;".$isi->no_mesin.";".$isi->no_rangka.";".$isi->tipe.";".$isi->tgl_penerimaan.";;".$kode_dealer_md.";;;;;;;;;;;;";
	echo "\r\n";		
}
?>      

