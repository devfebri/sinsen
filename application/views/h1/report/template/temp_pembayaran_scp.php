<?php 
//$bln = sprintf("%'.02d",$bulan);
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=PembayaranSCP_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
function mata_uang($a){
	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	return number_format($a, 0, ',', '.');
} 
?>
Main Dealer : PT Sinar Sentosa Primatama <br>
Laporan Pembayaran SCP <br>
Kode Dealer : <?php echo $this->m_admin->getByID("ms_dealer","id_dealer",$id_dealer)->row()->kode_dealer_md; ?> <br>
Nama Dealer : <?php echo $this->m_admin->getByID("ms_dealer","id_dealer",$id_dealer)->row()->nama_dealer; ?> <br>
Dari Tanggal : <?php echo $tgl1  ?> <br>
Sampai Tanggal :  <?php echo $tgl2 ?>
<table border="1">  
 	<tr> 		 		
 		<td rowspan="3" align="center">No</td>
 		<td rowspan="3" align="center">No Sales ID</td>
 		<td rowspan="3" align="center">No Juklak</td> 		 		
 		<td rowspan="3" align="center">Program</td> 		 		
 		<td rowspan="3" align="center">Type</td> 		 		 		
 		<td rowspan="2" colspan="2" align="center">Sales</td> 		 		 		
 		<td align="center" colspan="7">Claim Dealer yang Disetujui</td> 		 		 		
 		<td align="center" colspan="3">Potongan DO</td> 		 		 		
 		<td rowspan="3" align="center">Total MD Bayar Ke D</td> 		 		 		
 	</tr> 	
 	<tr>
 		<td align="center">Claim</td> 		 		 		 		
 		<td align="center" colspan="2">Kontribusi</td> 		 		 		 		
 		<td align="center"></td> 		 		 		 		
 		<td align="center">Total</td> 		 		 		 		
 		<td align="center" colspan="2">Total</td> 		 		 		 		
 		<td align="center" colspan="2">Potongan DO</td> 		 		 		 		
 		<td align="center">Total</td> 		 		 		 		
 	</tr>
 	<tr>
 		<td align="center">SSDM</td> 		 		 		 		 		
 		<td align="center">SSU</td> 		 		 		 		 		
 		<td align="center">Total</td> 		 		 		 		 		
 		<td align="center">AHM</td> 		 		 		 		 		
 		<td align="center">MD</td> 		 		 		 		 		
 		<td align="center">Ekstra Voucher</td> 		 		 		 		 		
 		<td align="center">Subsidi</td> 		 		 		 		 		
 		<td align="center">Claim SCP</td> 		 		 		 		 		
 		<td align="center">Claim Extra Voucher</td> 		 		 		 		 		
 		<td align="center">Total</td> 		 		 		 		 		
 		<td align="center">(Rupiah)</td> 		 		 		 		 		
 		<td align="center">Potongan DO</td> 		 		 		 		 		
 	</tr>
 	<?php 
 	$no = 1;$t_claim=0;$t_ahm=0;$t_md=0;$t_vo=0;$t_sub=0;$t_claim_sp=0;$t_claim_v=0;$t_pot_do=0;$t_do=0;$t_total=0;$t_rupiah=0;
 	$sql = $this->db->query("SELECT tr_sales_program.id_program_md,tr_sales_program.judul_kegiatan,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,
 		COUNT(tr_sales_order.no_mesin) AS jum,SUM(tr_spk.voucher_tambahan_1) AS voucher FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
 		LEFT JOIN tr_sales_program ON tr_spk.program_umum = tr_sales_program.id_program_md
 		LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
 		LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
 		WHERE tr_sales_order.tgl_cetak_invoice BETWEEN '$tgl1' AND '$tgl2' AND tr_spk.program_umum <> ''	
 		AND tr_sales_order.id_dealer = '$id_dealer' GROUP BY tr_scan_barcode.tipe_motor");
 	foreach ($sql->result() as $isi) {
 		$cek = $this->db->query("SELECT COUNT(tr_claim_dealer.id_claim) AS jum FROM tr_claim_dealer INNER JOIN tr_sales_order ON tr_claim_dealer.id_sales_order = tr_sales_order.id_sales_order 
 			INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
 			INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
 			WHERE tr_claim_dealer.id_program_md = '$isi->id_program_md' AND tr_claim_dealer.id_dealer = '$id_dealer'
 			AND tr_sales_order.tgl_cetak_invoice BETWEEN '$tgl1' AND '$tgl2' AND tr_spk.program_umum <> ''
 			AND tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan'")->row();
 		$cek2 = $this->db->query("SELECT * FROM tr_sales_program WHERE id_program_md = '$isi->id_program_md'");
 		$ahm = ($cek2->num_rows() == 0 OR is_null($cek2->row()->ahm)) ? 0 : $cek2->row()->ahm ;
 		$md = ($cek2->num_rows() == 0 OR is_null($cek2->row()->md)) ? 0 : $cek2->row()->md ; 	
 	

 		echo "
 		<tr>
 			<td>$no</td>
 			<td>$isi->id_program_md</td>
 			<td></td>
 			<td>$isi->judul_kegiatan</td>
 			<td>$isi->tipe_ahm ($isi->id_tipe_kendaraan)</td>
 			<td></td>
 			<td>$isi->jum</td>
 			<td>$cek->jum</td>
 			<td align='right'>".mata_uang($ahm)."</td>
 			<td align='right'>".mata_uang($md)."</td>
 			<td align='right'>".mata_uang($isi->voucher)."</td>
 			<td align='right'>".mata_uang($total_subdisi = $isi->voucher + $md + $ahm)."</td>
 			<td align='right'>".mata_uang($claim_sp = ($md + $ahm) * $cek->jum)."</td>
 			<td align='right'>".mata_uang($claim_v = $isi->voucher * $cek->jum)."</td>
 			<td align='right'>".mata_uang($pot_do = 0)."</td>
 			<td align='right'>".mata_uang($rupiah = 0)."</td>
 			<td align='right'>".mata_uang($potongan_do = $pot_do * $rupiah)."</td>
 			<td align='right'>".mata_uang($total = ($claim_v + $claim_sp) - $potongan_do)."</td> 		
 		</tr>
 		";
 		$t_claim += $cek->jum;
 		$t_ahm += $ahm;
 		$t_md += $md;
 		$t_vo += $isi->voucher;
 		$t_sub += $total_subdisi;
 		$t_claim_sp += $claim_sp;
 		$t_claim_v += $claim_v;
 		$t_pot_do += $pot_do;
 		$t_rupiah += $rupiah;
 		$t_do += $potongan_do;
 		$t_total += $total;
 		$no++;
 	}
 	?>
 	<tr>
 		<td colspan="7" align="right">Total</td>
 		<td><?php echo $t_claim ?></td>
 		<td><?php echo mata_uang($t_ahm) ?></td>
 		<td><?php echo mata_uang($t_md) ?></td>
 		<td align="right"><?php echo mata_uang($t_vo) ?></td>
 		<td align="right"><?php echo mata_uang($t_sub) ?></td>
 		<td align="right"><?php echo mata_uang($t_claim_sp) ?></td>
 		<td align="right"><?php echo mata_uang($t_claim_v) ?></td>
 		<td align="right"><?php echo mata_uang($t_pot_do) ?></td>
 		<td align="right"><?php echo mata_uang($t_rupiah) ?></td>
 		<td align="right"><?php echo mata_uang($t_do) ?></td>
 		<td align="right"><?php echo mata_uang($t_total) ?></td>
 	</tr>
</table>
