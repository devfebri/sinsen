<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$no.".UDCP");
header("Pragma: no-cache");
header("Expires: 0");

$sql = $this->db->query("SELECT *,ms_karyawan_dealer.nama_lengkap FROM tr_guest_book INNER JOIN tr_prospek ON tr_guest_book.id_list_appointment = tr_prospek.id_list_appointment 
		LEFT JOIN ms_jenis_customer ON tr_guest_book.id_jenis_customer = ms_jenis_customer.id_jenis_customer
		LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer
		WHERE tr_guest_book.generate IS NULL OR tr_guest_book.generate = ''");
foreach ($sql->result() as $isi) {			
	$tgl 		= gmdate("Ymd", time()+60*60*7);				
	$this->db->query("UPDATE tr_guest_book SET generate = 'ya' WHERE id_guest_book = '$isi->id_guest_book'");
	echo $isi->id_guest_book.";".$isi->id_customer.";".$isi->id_flp_md.";".$isi->id_warna.";".$isi->id_tipe_kendaraan.";".$tgl.";".$isi->nama_konsumen.";".$isi->alamat.";".$isi->no_telp.";".$isi->rencana_bayar.";".$isi->jenis_customer.";".$isi->status_prospek.";".$isi->keterangan_fol.";".$isi->nama_lengkap;
	//echo "<br>";		
	echo "\r\n";		
}
?>      

