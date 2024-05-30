<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$no.".UIPW");
header("Pragma: no-cache");
header("Expires: 0");

$sql = $this->db->query("SELECT tr_po_dealer_indent.*,ms_dealer.kode_dealer_md,ms_dealer.nama_dealer FROM tr_po_dealer_indent INNER JOIN ms_dealer ON tr_po_dealer_indent.id_dealer = ms_dealer.id_dealer 
		WHERE tr_po_dealer_indent.status = 'sent'");
foreach ($sql->result() as $isi) {		
	$qty_warna = $this->db->query("SELECT COUNT(tr_po_dealer_indent.id_spk) AS jum FROM tr_po_dealer_indent 
		WHERE tr_po_dealer_indent.id_warna = '$isi->id_warna'")->row();	


	echo $isi->tgl.";E20;".$isi->kode_dealer_md.";".$isi->id_tipe_kendaraan.";".$isi->id_warna.";".$qty_warna->jum.";0;0";
	//echo "<br>";		
	echo "\r\n";		
}
?>      

