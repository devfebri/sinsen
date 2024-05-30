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
			<td align="center"><h2>LAPORAN PERMOHONAN BPKB</h2></td>		
		</tr>
	</table>
	Tanggal Mohon Samsat : <?php echo $tgl1 ?> <br> <br>
<table border='1' class="table" width="100%">  
 	<tr>
 		<td align="center" width="5%">No</td>
 		<td align="center">Nama Customer</td>
 		<td align="center">No Polisi</td>
 		<td align="center">Kode Dealer</td>
 		<td align="center">Amount</td>
 	</tr>
 	<?php 
 	$no=1;
 	$sql = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail 
 		LEFT JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd 
 		LEFT JOIN ms_dealer ON tr_pengajuan_bbn.id_dealer = ms_dealer.id_dealer
 		WHERE tgl_mohon_samsat = '$tgl1'");
 	foreach ($sql->result() as $isi) {
 		
 		echo " 		
 		<tr>
 			<td>$no</td>
 			<td>$isi->nama_konsumen</td>
 			<td></td>
 			<td>$isi->nama_dealer</td>
 			<td>".mata_uang($uang = 225000)."</td>
 		</tr>
 		"; 	
 		$no++;
 		$t_total += $uang;
 	} 
 	?>
 	<tr>
 		<td colspan="4" align="right">Grand Total</td>
 		<td align="right"><?php echo mata_uang($t_total) ?></td>
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