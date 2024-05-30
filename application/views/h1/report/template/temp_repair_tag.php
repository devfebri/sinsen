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
				sheet-size: 210mm 148mm;
				margin-left: 0cm;
				margin-right: 0cm;
				margin-bottom: 0cm;
				margin-top: 0cm;
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
<table border='1' class="table" width="100%">  
 	<tr>
 		<td align="center" width="50%"></td> 		
 		<td align="center" width="50%"></td> 		
 	</tr>
 	<?php
 	$sql = $this->db->query("SELECT * FROM tr_wo INNER JOIN tr_checker ON tr_wo.id_checker = tr_checker.id_checker
 	 WHERE tr_wo.tgl_wo = '$tgl1'");
 	$kolom 	= 2;
	$chunks = array_chunk($sql->result(), $kolom);
 	//foreach ($sql->result() as $isi) {
 	foreach ($chunks as $row) {
 		echo "<tr>";
 		foreach ($row as $isi) {
	 		$cek = $this->db->query("SELECT * FROM tr_scan_barcode 
	 			INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.no_shipping_list = tr_penerimaan_unit_detail.no_shipping_list 
	 			INNER JOIN tr_penerimaan_unit ON tr_penerimaan_unit_detail.id_penerimaan_unit = tr_penerimaan_unit.id_penerimaan_unit 			
	 			INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
	 			WHERE tr_scan_barcode.no_mesin = '$isi->no_mesin'");
	 		$no_mesin = $isi->no_mesin;
	 		$no_urut = ($cek->num_rows() > 0) ? $cek->row()->no_antrian : "" ;
	 		$tipe_motor = ($cek->num_rows() > 0) ? $cek->row()->tipe_ahm : "" ;
	 		$id_item = ($cek->num_rows() > 0) ? $cek->row()->id_item : "" ;
 			echo "
 			<td valign='top'>
 				<table class='table table-bordered' width='100%'>
 					<tr>
 						<td colspan='2' align='center'><h3><b>REPAIR TAG <hr></td>
 					</tr>
 					<tr>
 						<td width='30%'><h3><b>Tgl.Masuk</b></h3></td>
 						<td><h3><b>: $isi->tgl_wo</td>
 					</tr>
 					<tr>
 						<td><h3><b>No Polisi</td>
 						<td><h3><b>: $isi->no_polisi</td>
 					</tr>
 					<tr>
 						<td><h3><b>Ekspedisi</td>
 						<td><h3><b>: $isi->ekspedisi</td>
 					</tr>
 					<tr>
 						<td><h3><b>No Urut</td>
 						<td><h3><b>: $no_urut</td>
 					</tr>
 					<tr>
 						<td><h3><b>No Mesin</td>
 						<td><h3><b>: $isi->no_mesin</td>
 					</tr>
 					<tr>
 						<td valign='top'><h3><b>Kerusakan</td> 						
 						<td colspan='2'>: ";
 						$cek2 = $this->db->query("SELECT * FROM tr_checker_detail INNER JOIN ms_part ON tr_checker_detail.id_part = ms_part.id_part
 							WHERE tr_checker_detail.id_checker = '$isi->id_checker'");
 						foreach ($cek2->result() as $row) {
 							echo "$row->id_part $row->nama_part $tipe_motor ($id_item) <br>
 										$row->gejala <br>";
 						}
 						echo "
 						<br>
 						<br> 						 						 				
 						<br> 						 						 				
 						<br> 						 						 				 						 						 						 			
 						<br>
 						<br>
 						<br>
 						<br>
 						<br>
 						<br>
 						<br>
 						<br>
 						<br>
 						<br> 						
 						</td>
 					</tr>
 				</table>
 			</td>";
 		}
 		echo "
 		</tr>
 		"; 	
 	} 
 	?> 	
</table>
</body>
</html>