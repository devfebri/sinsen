<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$no.".UIN");
header("Pragma: no-cache");
header("Expires: 0");

$sql = $this->db->query("SELECT tr_po_dealer_indent.*,ms_dealer.kode_dealer_md,ms_dealer.nama_dealer FROM tr_po_dealer_indent INNER JOIN ms_dealer ON tr_po_dealer_indent.id_dealer = ms_dealer.id_dealer 
		WHERE tr_po_dealer_indent.status = 'sent'");
foreach ($sql->result() as $isi) {	
	$qty_unpaid = $this->db->query("SELECT COUNT(id_spk) AS jum FROM tr_po_dealer_indent WHERE status = 'sent' AND nilai_dp = 0")->row();
	$qty_paid = $this->db->query("SELECT COUNT(id_spk) AS jum FROM tr_po_dealer_indent WHERE status = 'sent' AND nilai_dp <> 0")->row();
	$qty_full = $this->db->query("SELECT COUNT(tr_sales_order.no_spk) AS jum FROM tr_sales_order INNER JOIN tr_po_dealer_indent ON tr_sales_order.no_spk = tr_po_dealer_indent.id_spk
		WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL")->row();


	echo $isi->tgl.";E20;".$isi->kode_dealer_md.";".$isi->id_tipe_kendaraan.";".$isi->id_warna.";".$qty_unpaid->jum.";".$qty_paid->jum.";".$qty_full->jum.";0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0";
	//echo "<br>";		
	echo "\r\n";		
}
?>      

