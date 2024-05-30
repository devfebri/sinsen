<?php 
//$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=KekuranganKSU.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">Tgl Masuk</td>
 		<td align="center">Nama Ekspedisi</td>
 		<td align="center">Driver</td>
 		<td align="center">No Pol</td> 		
 		<td align="center">Jenis KSU</td> 		
 		<td align="center">Qty</td> 		 		
 	</tr>
 	<?php 
 	$no=1;
 	$sql = $this->db->query("SELECT * FROM tr_kekurangan_ksu LEFT JOIN tr_penerimaan_unit ON tr_kekurangan_ksu.id_penerimaan_unit = tr_penerimaan_unit.id_penerimaan_unit
 		LEFT JOIN ms_vendor ON tr_penerimaan_unit.ekspedisi = ms_vendor.id_vendor
 		LEFT JOIN ms_ksu ON tr_kekurangan_ksu.id_ksu = ms_ksu.id_ksu WHERE tr_kekurangan_ksu.status = 1 AND tr_kekurangan_ksu.qty > 0"); 		
 	foreach ($sql->result() as $isi) { 		
 		echo "
 		<tr>
 			<td>$no</td> 			
 			<td>$isi->tgl_penerimaan</td> 			
 			<td>$isi->vendor_name</td> 			
 			<td>$isi->nama_driver</td> 			
 			<td>$isi->no_polisi</td> 			
 			<td>$isi->ksu</td> 			
 			<td>$isi->qty</td> 			
 		</tr>
 		";
 		$no++;
 	}
 	?>
</table>