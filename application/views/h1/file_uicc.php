<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$no.".UICC");
header("Pragma: no-cache");
header("Expires: 0");

$sql = $this->db->query("SELECT tr_po_dealer_indent.*,ms_dealer.kode_dealer_md,ms_dealer.nama_dealer FROM tr_po_dealer_indent INNER JOIN ms_dealer ON tr_po_dealer_indent.id_dealer = ms_dealer.id_dealer 
		WHERE tr_po_dealer_indent.status = 'sent'");
foreach ($sql->result() as $isi) {		
	$qty_cash = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum FROM tr_spk INNER JOIN tr_po_dealer_indent ON tr_spk.no_spk = tr_po_dealer_indent.id_spk
		WHERE tr_spk.jenis_beli = 'Cash'")->row();
	$qty_credit = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum FROM tr_spk INNER JOIN tr_po_dealer_indent ON tr_spk.no_spk = tr_po_dealer_indent.id_spk
		WHERE tr_spk.jenis_beli = 'Kredit'")->row();
	$qty_finco = $this->db->query("SELECT ms_finance_company.finance_company FROM tr_spk INNER JOIN tr_po_dealer_indent ON tr_spk.no_spk = tr_po_dealer_indent.id_spk
		INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
		WHERE tr_spk.jenis_beli = 'Kredit' AND tr_spk.no_spk = '$isi->id_spk'");
	if($qty_finco->num_rows() > 0){
		$r = $qty_finco->row();
		$fin = $r->finance_company;
	}else{
		$fin = "";
	}


	echo $isi->tgl.";E20;".$isi->kode_dealer_md.";".$isi->id_tipe_kendaraan.";".$isi->id_warna.";".$qty_cash->jum.";".$qty_credit->jum.";".$fin;
	//echo "<br>";		
	echo "\r\n";		
}
?>      

