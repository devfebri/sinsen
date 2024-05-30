<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->
	<?php 
		function mata_uang($a){
		if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
		if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
			return number_format($a, 0, ',', '.');
		} ?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Cetak</title>
	<style>		
		@media print {
			@page {
				sheet-size: 210mm 297mm;
				margin-left: 1cm;
				margin-right: 1cm;
				margin-bottom: 1cm;
				margin-top: 1cm;
			}
			.kertas {page-break-after: always;}
			.kertas2 {page-break-before: always;}
			.text-center{text-align: center;}
			.table {
					width: 100%;
					max-width: 100%;
					border-collapse: collapse;
				   /*border-collapse: separate;*/
				}
			.table-bordered tr td {
					border: 0px solid black;
					padding-left: 6px;
					padding-right: 6px;
				}
			body{
				font-family: "Arial";
				font-size: 11pt;
			}
		}
	</style>
</head>

<body>
<div class="kertas" style="page-break-after: always;">
	PT. Sinar Sentosa Primatama <br>
	Jl Kolonel Abunjani No. 09 <br>
	JAMBI 36129 <br>
	<table width="100%">
		<tr>
			<td align="center"><h2>BUKTI PENGELUARAN KAS/BANK</h2></td>		
		</tr>
	</table>
	Tanggal Mohon Samsat : <?php echo $tgl1 ?> <br> <br>
	<?php 
	$g_total=0;$g_unit=0;
	$cek = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail INNER JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd
		INNER JOIN ms_dealer ON tr_pengajuan_bbn.id_dealer = ms_dealer.id_dealer
		WHERE tr_pengajuan_bbn_detail.tgl_mohon_samsat = '$tgl1' GROUP BY tr_pengajuan_bbn.id_dealer");
	foreach ($cek->result() as $amb) {
		echo $amb->nama_dealer;
	?>		

		<table border='0' class="table" width="100%">  
		 	<tr>
		 		<td align="center" width="5%">No</td>
		 		<td align="center" width="30%">Nama Customer</td>
		 		<td align="center" width="20%">Tipe Motor</td> 		
		 		<td align="center" width="10%">Kode Tipe</td> 		
		 		<td align="center" width="20%">No Mesin</td> 		
		 		<td align="center" width="10%">Tahun Rakit</td> 		
		 		<td align="center" width="15%">Amount</td>
		 	</tr>
		 	<?php 
		 	$no=1;$t=1;
		 	$t_total=0;$t_unit=0;
		 	$sql = $this->db->query("SELECT *,tr_pengajuan_bbn_detail.no_mesin as nosin FROM tr_pengajuan_bbn_detail 
		 		LEFT JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd 
		 		LEFT JOIN ms_dealer ON tr_pengajuan_bbn.id_dealer = ms_dealer.id_dealer
		 		LEFT JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
		 		WHERE tr_pengajuan_bbn.id_dealer = '$amb->id_dealer' AND tr_pengajuan_bbn_detail.tgl_mohon_samsat = '$tgl1'");

		 	foreach ($sql->result() as $isi) {
		 		$biaya_bbn_md_bj = $isi->biaya_bbn_md_bj;
		 		echo " 		
		 		<tr>
		 			<td>$no</td>
		 			<td>$isi->nama_konsumen</td> 			
		 			<td>$isi->deskripsi_ahm</td>
		 			<td>$isi->id_tipe_kendaraan</td>
		 			<td>$isi->nosin</td>
		 			<td>$isi->tahun</td>
		 			<td align='right'>".mata_uang($uang = $biaya_bbn_md_bj)."</td>
		 		</tr>
		 		"; 	
		 		$t_total += $uang;
		 		$no++;
		 	} 
		 	$t_unit += $sql->num_rows();
		 	$g_total += $t_total;
		 	$g_unit += $t_unit;
		 	?>
		 	<tr>
		 		<td colspan="6" align="right">Sub Total</td>
		 		<td align="right"><?php echo mata_uang($t_total); ?></td>
		 	</tr>			 	
		</table>
		<br>
	<?php } ?>
	<table width="100%" class="table" border="0">
		<tr>
	 		<td colspan="6" align="right">Grand Total</td>
	 		<td align="right" width="15%"><?php echo mata_uang($g_total) ?></td>
	 	</tr>			 	
	 	<tr>
	 		<td colspan="6" align="right">Total Unit</td>
	 		<td align="right" width="15%"><?php echo $g_unit ?></td>
	 	</tr>			 	
	</table>
<table width="100%" border="0">
	<tr>
		<td><br></td>
	</tr>
	<tr>
		<td width='55%'></td>
		<td width='15%' align='center'>Disetujui</td>
		<td width='15%' align='center'>Dibayar</td>
		<td width='15%' align='center'>Diterima</td>
	</tr>		 	
	<tr>
		<td>BG Bank _____________________ No ____________________</td>
	</tr>
	<tr>
		<td>Tanggal ______________________ Rp ____________________</td>
	</tr>
	<tr>
		<td></td>
		<td align="center">______________</td>
		<td align="center">______________</td>
		<td align="center">______________</td>		
	</tr>
</table>
</div>
</body>
</html>