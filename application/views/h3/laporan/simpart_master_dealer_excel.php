<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Master AHASS.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>SIM Part : Master AHASS</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Nama Dealer</b></td>
		 <td align="center"><b>Kode Dealer AHM</b></td>
		 <td align="center"><b>Channel</b></td>
		 <td align="center"><b>Grouping Dealer</b></td>
		 <td align="center"><b>Jenis Dealer</b></td>
 	</tr>
	<?php 
		foreach ($master_ahass as $row) { 
	?>	
		<tr>
			<td>'<?php echo $row->nama_dealer ?></td>
			<td><?php echo $row->kode_dealer_ahm ?></td>
			<td><?php echo $row->channel ?></td>
			<td><?php echo $row->grouping_dealer ?></td>
			<td><?php echo $row->jenis_dealer ?></td>
		</tr>
		
	<?php	}
	?>
</table>