<?php 
$no = date('dmY_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan_KPB_MD-".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

Laporan KPB MD AS OF <?php echo date('d F Y - H:i') .' WIB <br>'; ?>

<br>

<table border="1">   	

 	<tr>
 		<td align="center">No</td>		
 		<td align="center">Tgl. Input</td> 	 		 		 		 		
 		<td align="center">Kode MD</td>
 		<td align="center">Kode AHASS</td> 
 		<td align="center">No Mesin</td> 
 		<td align="center">5 Digit</td> 
 		<td align="center">No KPB</td> 
 		<td align="center">Tgl Beli</td> 
 		<td align="center">Service ke</td> 
 		<td align="center">Km</td> 
 		<td align="center">Tgl Service</td>
	</tr>
	
 	<?php 	

 	$urut=1;

    if($list_dealer!='false'){
     	foreach ($list_dealer as $row) {
     		echo "
         		<tr>
         			<td align='center'>$urut</td>
         			<td align='center'>$row->tgl_input</td>
         			<td align='center'>$row->kode_md</td>
         			<td align='center'>'$row->kode_dealer_ahm</td>
         			<td align='center'>$row->no_mesin</td>
         			<td align='center'>$row->digit</td>
         			<td align='center'>$row->no_kpb</td>
         			<td align='center'>$row->tgl_beli_smh</td>
         			<td align='center'>$row->kpb_ke</td>
         			<td align='center'>$row->km_service</td>
         			<td align='center'>$row->tgl_service</td>
				</tr>
		";
		$urut++;
     	}

    }

 	?> 

</table>