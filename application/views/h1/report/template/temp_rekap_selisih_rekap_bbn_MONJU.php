<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan_Rekapan_Selisih_pembayaran_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
function mata_uang($a){
	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	return number_format($a, 0, ',', '.');
} 
?>
Main Dealer: PT. Sinar Sintosa Primatama <br>
Laporan Rekapan Selisih Pembayaran <br>
dari Tanggal: <?php echo $tgl1 ?> <br>
sampai Tanggal: <?php echo $tgl2 ?>
<table border="1">  
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">Tgl Mohon Samsat</td>
 		<td align="center">Nama Dealer</td>
 		<td align="center">Nama Konsumen</td>
 		<td align="center">No Mesin</td> 		
 		<td align="center">No Plat</td> 		
 		<td align="center">Harga BBN Samsat</td> 		
 		<td align="center">Harga Notice Pajak</td> 		
 		<td align="center">Selisih Harga</td> 		
 	</tr>
 	<?php 
 	$no=1;$t_total=0;
 	$sql = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail INNER JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn.no_bastd = tr_pengajuan_bbn_detail.no_bastd
 		LEFT JOIN ms_dealer ON tr_pengajuan_bbn.id_dealer = ms_dealer.id_dealer
 		WHERE tr_pengajuan_bbn_detail.tgl_mohon_samsat BETWEEN '$tgl1' AND '$tgl2'");
 	foreach ($sql->result() as $isi) {
 		$cek = $this->db->query("SELECT * FROM tr_entry_stnk WHERE no_mesin = '$isi->no_mesin'");
 		$notice_pajak = ($cek->num_rows() > 0) ? $cek->row()->notice_pajak : 0 ; 		
 		$no_plat = ($cek->num_rows() > 0) ? $cek->row()->no_plat : "" ;
 		if($no_plat!="") $no_plat = ($cek->num_rows() > 0) ? $cek->row()->no_pol : "" ;
 		echo "
 		<tr>
 			<td>$no</td>
 			<td>$isi->tgl_mohon_samsat</td>
 			<td>$isi->nama_dealer</td>
 			<td>$isi->nama_konsumen</td>
 			<td>$isi->no_mesin</td>
 			<td>$no_plat</td>
 			<td align='right'>".mata_uang($isi->biaya_bbn_md_bj)."</td>
 			<td align='right'>".mata_uang($notice_pajak)."</td>
 			<td align='right'>".mata_uang($total = $isi->biaya_bbn_md_bj - $notice_pajak)."</td>
 		</tr>
 		";
 		$no++;
 		$t_total += $total;
 	}
 	?>
 	<tr>
 		<td colspan="7" align="right">Total</td>
 		<td align="right"><?php echo mata_uang($t_total) ?></td>
 	</tr>
</table>