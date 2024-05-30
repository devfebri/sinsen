<?php
//$d = date('dms'); 
//header("Content-Disposition: attachment; filename=UPO-FILE-".$d.".UPO");
include "connect.php";
$id = $_GET['id'];
$id_karyawan = $_GET['k'];
$no = $_GET['n'];
$glob_string =  realpath('/downloads/po');

session_start();
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$no.".UPO");
header("Pragma: no-cache");
header("Expires: 0");
//header($headerstring);
//readfile($glob_string);

$sql = mysqli_query($conn,"SELECT tr_po_detail.*,tr_po.jenis_po,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.warna FROM tr_po_detail INNER JOIN ms_item 
						ON tr_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan						
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna INNER JOIN tr_po
						On tr_po_detail.id_po=tr_po.id_po
						WHERE tr_po_detail.id_po = '$id'");

$sql2 = mysqli_query($conn,"SELECT ms_karyawan_dealer.*,ms_dealer.kode_dealer_md as kode FROM ms_karyawan_dealer INNER JOIN ms_dealer
					ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer WHERE ms_karyawan_dealer.id_karyawan_dealer = '$id_karyawan'");
$isi2 = mysqli_fetch_array($sql2,MYSQLI_ASSOC);
$mo = date("m");
$ye = date("Y");
while ($isi=mysqli_fetch_array($sql,MYSQLI_ASSOC)){
	if($isi['jenis_po'] == 'PO Reguler'){
		$id_jenis_po = "R";
	}else{
		$id_jenis_po = "A";
	}
	$hari_ini 	= date("Y-m-d"); 
	$tgl 				= date('t', strtotime($hari_ini));
	$tgl_awal 	= "01".$mo.$ye;
	$tgl_akhir 	= $tgl.$mo.$ye;
	$kode 			= (int)$isi2['kode'];
	echo $kode.";".$mo.";".$ye.";".$isi['id_tipe_kendaraan'].";".$isi['qty_po_fix'].";".$isi['qty_po_t1'].";".$isi['qty_po_t2'].";".$isi['id_po'].";".$id_jenis_po.";".$tgl_awal.";".$tgl_akhir;
	echo "\r\n";	
}

echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po'>";			  

?>      

