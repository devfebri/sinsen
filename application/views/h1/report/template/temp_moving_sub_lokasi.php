<?php 
//$bln = sprintf("%'.02d",$bulan);
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=MovingSubLokasi_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
Report Moving Sub Lokasi <br>
Per <?php echo $tgl1  ?> - <?php echo $tgl2 ?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">No</td>
 		<td align="center">Kode Item</td>
 		<td align="center">No Mesin</td> 		 		
 		<td align="center">No Rangka</td> 		 		
 		<td align="center">Lokasi Awal Receiving</td> 		 		 		
 		<td align="center">Lokasi NRFS</td> 		 		 		
 		<td align="center">Lokasi Setelah NRFS</td> 		 		 		
 		<td align="center">Lokasi Moving</td> 		 		 		
 	</tr> 	
 	<?php 
 	$no=1; 
 	$sql = $this->db->query("SELECT *,count(tr_log.no_mesin) AS jum FROM tr_log INNER JOIN tr_scan_barcode ON tr_log.no_mesin = tr_scan_barcode.no_mesin
 		WHERE LEFT(tr_log.waktu,10) BETWEEN '$tgl1' AND '$tgl2'
 		GROUP BY tr_log.no_mesin
 		HAVING jum > 1");
 	foreach ($sql->result() as $isi) { 	
 		$cek = $this->db->query("SELECT * FROM tr_log WHERE no_mesin = '$isi->no_mesin' ORDER BY id_log ASC LIMIT 1,1");
 		$nrfs = "";
 		if($cek->num_rows()>0){
 			if($cek->row()->status == 'NRFS'){
 				$nrfs = $cek->row()->keterangan;
 			}
 		}

 		$cek2 = $this->db->query("SELECT * FROM tr_log WHERE no_mesin = '$isi->no_mesin' ORDER BY id_log ASC LIMIT 2,1");
 		$nrfs2 = "";
 		if($cek2->num_rows()>0){
 			if($cek2->row()->status == 'RFS'){
 				$nrfs2 = $cek2->row()->keterangan;
 			}
 		}
 		echo "
 		<tr>
 			<td>$no</td>
 			<td>$isi->id_item</td>
 			<td>$isi->no_mesin</td>
 			<td>$isi->no_rangka</td>
 			<td>$isi->lokasi - $isi->slot ($isi->tipe)</td> 			
 			<td>$nrfs</td> 		
 			<td>$nrfs2</td>
 			<td></td>
 		</tr>
 		";
 		$no++;
 	}
 	
 	?>
</table>
