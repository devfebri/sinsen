<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=RepaintingJasaLuar_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">Nama Vendor</td>
 		<td align="center">Deskripsi</td>
 		<td align="center">Qty</td>
 		<td align="center">No Rekap</td>
 		<td align="center">No Ekspedisi</td> 		
 	</tr>
 	<?php
 	$no=1; 
 	$sql = $this->db->query("SELECT * FROM tr_checker INNER JOIN tr_checker_detail ON tr_checker.id_checker = tr_checker_detail.id_checker
 		LEFT JOIN tr_scan_barcode ON tr_checker_detail.no_mesin = tr_scan_barcode.no_mesin
 		WHERE tr_checker.tgl_checker BETWEEN '$tgl1' AND '$tgl2' AND tr_checker_detail.pengatasan = 'Repainting'");
 	foreach ($sql->result() as $isi) {
 		echo "
 		<tr>
 			<td>$no</td>
 			<td></td>
 			<td>$isi->deskripsi ($isi->id_item)</td>
 			<td>$isi->qty_order</td>
 			<td>$isi->id_checker</td>
 			<td>$isi->ekspedisi</td>
 		</tr>
 		";
 		$no++;
 	}
 	?>
</table>
