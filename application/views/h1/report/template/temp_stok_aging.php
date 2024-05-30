<?php 
$no = $tgl1;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Stok_aging_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">No</td>
 		<td align="center">Kode Item</td>
    <td align="center">No Mesin</td>
 		<td align="center">No Rangka</td>
    <td align="center">Status Sales</td>         
    <td align="center">Lokasi</td>        
 		<td align="center">Aging</td> 		 		
 	</tr>
 	<?php  	
 	$no=1;
  $tgl = date("Y-m-d");
 	$sql = $this->db->query("SELECT tr_scan_barcode.id_item,tr_scan_barcode.no_mesin,tr_scan_barcode.no_rangka,tr_scan_barcode.tipe,tr_scan_barcode.lokasi,tr_scan_barcode.slot,TIMESTAMPDIFF(DAY, tr_scan_barcode.tgl_penerimaan, CURDATE()) AS lama FROM tr_scan_barcode INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan WHERE (tr_scan_barcode.tipe = 'RFS' OR tr_scan_barcode.tipe = 'NRFS') AND STATUS = 1 ORDER BY lama DESC");
 	foreach ($sql->result() as $isi) {    
 		echo "
 		<tr>
 			<td>$no</td> 			
      <td>$isi->id_item</td>                
      <td>$isi->no_mesin</td>                
      <td>$isi->no_rangka</td>                
      <td>$isi->tipe</td>                
      <td>$isi->lokasi - $isi->slot</td>                
      <td>$isi->lama</td>                
 		</tr>
 		";
 		$no++; 	
 	}
 	?>
</table>
