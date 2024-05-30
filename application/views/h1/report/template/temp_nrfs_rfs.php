<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Nrfs_rfs_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">No</td>
 		<td align="center">Kode Item</td>
 		<td align="center">No Mesin</td> 		 		
 		<td align="center">No Rangka</td> 		 		
 		<td align="center">Lokasi Unit</td> 		
 	</tr>
 	<?php  	
 	$no=1;
 	$sql = $this->db->query("SELECT * FROM tr_wo 
 		INNER JOIN tr_checker ON tr_wo.id_checker = tr_checker.id_checker
 		WHERE tr_wo.tgl_wo BETWEEN '$tgl1' AND '$tgl2' AND tr_wo.status_wo = 'closed'");
 	foreach ($sql->result() as $isi) { 		
 		$row2 = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$isi->no_mesin'");
 		$no_rangka = ($row2->num_rows() > 0) ? $row2->row()->no_rangka:""; 		
 		$id_item = ($row2->num_rows() > 0) ? $row2->row()->id_item:""; 		
 		echo "
 		<tr>
 			<td>$no</td>
 			<td>$id_item</td> 			 			 			 			 			 			
 			<td>$isi->no_mesin</td> 			 			 			 			 			 			 			
 			<td>$no_rangka</td> 			 			 			 			 			 			 			
 			<td>$isi->lokasi_baru</td> 			 			 			 			 			 			 			
 		</tr>
 		";
 		$no++; 	
 	}
 	?>
</table>
