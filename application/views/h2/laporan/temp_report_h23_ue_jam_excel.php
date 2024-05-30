<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Reporting H23 Per Tanggal Transaksi Main Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>


<table border="1">  
	<?php 
		$total_ue = $ue_jam->jam_7+$ue_jam->jam_8+$ue_jam->jam_9+$ue_jam->jam_10+$ue_jam->jam_11+$ue_jam->jam_12+$ue_jam->jam_13+$ue_jam->jam_14+$ue_jam->jam_15+$ue_jam->jam_16;
	?>

 	<tr> 		
 		<td align="center"><b>No</b></td>
 		<td align="center"><b>Jam Kerja</b></td>
 		<td align="center"><b>Total UE</b></td>
 	</tr>
	 <tr> 		
 		<td align="center">1</td>
 		<td align="center">07:00-08:00</td>
 		<td align="center"><?php echo $ue_jam->jam_7 ?></td>
 	</tr>
	 <tr> 		
 		<td align="center">2</td>
 		<td align="center">08:00-09:00</td>
 		<td align="center"><?php echo $ue_jam->jam_8?></td>
 	</tr>
	 <tr> 		
 		<td align="center">3</td>
 		<td align="center">09:00-10:00</td>
 		<td align="center"><?php echo $ue_jam->jam_9?></td>
 	</tr>
	 <tr> 		
 		<td align="center">4</td>
 		<td align="center">10:00-11:00</td>
 		<td align="center"><?php echo $ue_jam->jam_10?></td>
 	</tr>
	 <tr> 		
 		<td align="center">5</td>
 		<td align="center">11:00-12:00</td>
 		<td align="center"><?php echo $ue_jam->jam_11?></td>
 	</tr>
	 <tr> 		
 		<td align="center">6</td>
 		<td align="center">12:00-13:00</td>
 		<td align="center"><?php echo $ue_jam->jam_12?></td>
 	</tr>
	 <tr> 		
 		<td align="center">7</td>
 		<td align="center">13:00-14:00</td>
 		<td align="center"><?php echo $ue_jam->jam_13?></td>
 	</tr>
	 <tr> 		
 		<td align="center">8</td>
 		<td align="center">10:00-11:00</td>
 		<td align="center"><?php echo $ue_jam->jam_10?></td>
 	</tr>
	 <tr> 		
 		<td align="center">9</td>
 		<td align="center">11:00-12:00</td>
 		<td align="center"><?php echo $ue_jam->jam_11?></td>
 	</tr>
	 <tr> 		
 		<td align="center">10</td>
 		<td align="center">12:00-13:00</td>
 		<td align="center"><?php echo $ue_jam->jam_12?></td>
 	</tr>
	 <tr> 		
 		<td align="center">11</td>
 		<td align="center">13:00-14:00</td>
 		<td align="center"><?php echo $ue_jam->jam_13?></td>
 	</tr>
	 <tr> 		
 		<td align="center">12</td>
 		<td align="center">14:00-15:00</td>
 		<td align="center"><?php echo $ue_jam->jam_14?></td>
	</tr>
	<tr> 		
 		<td align="center">13</td>
 		<td align="center">15:00-16:00</td>
 		<td align="center"><?php echo $ue_jam->jam_15?></td>
 	</tr>
	 <tr> 		
 		<td align="center">11</td>
 		<td align="center">16:00-17:00</td>
 		<td align="center"><?php echo $ue_jam->jam_16?></td>
 	</tr>
	 <tr> 		
 		<td align="center" colspan="2"><b>Total UE</b></td>
 		<td align="center"><b><?php echo $total_ue?></b></td>
 	</tr>
</table>

