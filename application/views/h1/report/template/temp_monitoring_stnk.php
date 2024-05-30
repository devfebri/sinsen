<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Monitoring_stnk_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
function mata_uang($a){
	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	return number_format($a, 0, ',', '.');
}
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">No</td>
 		<td align="center">Nama Dealer</td> 		 		
 		<td align="center">Nama Customer</td>
 		<td align="center">Tgl Mohon Samsat</td> 		 		
 		<td align="center">Tgl Terima STNK</td> 		 		 		
 		<td align="center">No Mesin</td> 		 		 		
 		<td align="center">Tipe Motor</td> 		 		 		
 		<td align="center">No Polisi</td> 		 		 		 		
 	</tr>
 	<?php  	
 	$no=1;
 	$sql = $this->db->query("SELECT ms_dealer.nama_dealer,tr_pengajuan_bbn_detail.*,ms_tipe_kendaraan.tipe_ahm,tr_entry_stnk.* FROM tr_pengajuan_bbn_detail 
 		LEFT JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn.no_bastd = tr_pengajuan_bbn_detail.no_bastd
 		LEFT JOIN ms_dealer ON tr_pengajuan_bbn.id_dealer = ms_dealer.id_dealer
 		LEFT JOIN tr_scan_barcode ON tr_pengajuan_bbn_detail.no_mesin = tr_scan_barcode.no_mesin
 		LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
 		INNER JOIN tr_entry_stnk ON tr_pengajuan_bbn_detail.no_mesin = tr_entry_stnk.no_mesin
 		WHERE tr_pengajuan_bbn_detail.tgl_mohon_samsat BETWEEN '$tgl1' AND '$tgl2'");
 	foreach ($sql->result() as $isi) { 		 		 		
 		$cek = $this->db->query("SELECT * FROM tr_penyerahan_stnk_detail INNER JOIN tr_penyerahan_stnk ON tr_penyerahan_stnk.no_serah_stnk = tr_penyerahan_stnk_detail.no_serah_stnk
 			WHERE tr_penyerahan_stnk_detail.no_mesin = '$isi->no_mesin' AND tr_penyerahan_stnk.status_stnk = 'terima'");
 		$tgl_terima = ($cek->num_rows() > 0) ? $cek->row()->tgl_serah_terima : "" ;
 		echo "
 		<tr>
 			<td>$no</td> 			
 			<td>$isi->nama_dealer</td> 			
 			<td>$isi->nama_konsumen</td> 			
 			<td>$isi->tgl_mohon_samsat</td> 			
 			<td>$tgl_terima</td> 			
 			<td>$isi->no_mesin</td> 			
 			<td>$isi->tipe_ahm</td> 			
 			<td>$isi->no_pol</td> 			 			
 		</tr>
 		";
 		$no++; 	
 	}
 	?>
</table>
