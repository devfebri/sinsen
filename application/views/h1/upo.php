<?php
//$d = date('dms'); 
//header("Content-Disposition: attachment; filename=UPO-FILE-".$d.".UPO");
header("Content-type: application/octet-stream");
header("Pragma: no-cache");
header("Expires: 0");

$id = $_GET['id'];
$sql = $this->db->query("SELECT tr_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_po_detail INNER JOIN ms_item 
						ON tr_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan						
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE tr_po_detail.id_po = '$id'");
$id_karyawan = $this->session->userdata('id_karyawan_dealer');
$sql2 = $this->db->query("SELECT ms_karyawan_dealer.*,ms_dealer.kode_dealer_md FROM ms_karyawan_dealer INNER JOIN
					ON ms_karyawan_dealer.id_deler = ms_dealer.id_deler WHERE ms_karyawan_dealer.id_karyawan_dealer = '$id_karyawan'")->row();
$mo = date("m")
foreach ($sql->result() as $k) {

	echo "$sql2->kode_dealer_md;$mo";
}
?>      
Hello

