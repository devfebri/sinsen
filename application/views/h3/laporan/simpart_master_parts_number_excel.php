<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Master Parts Number.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>SIM Part : Master Parts Number</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Parts Number</b></td>
		 <td align="center"><b>Nama Part</b></td>
		 <td align="center"><b>Kelompok Barang</b></td>
 	</tr>
	<?php 
		foreach ($parts_number as $row) { 
	?>	
		<tr>
			<td><?php echo $row->id_part ?></td>
			<td><?php echo $row->nama_part ?></td>
			<td><?php echo $row->kelompok_part ?></td>
		</tr>
		
	<?php	}
	?>
</table>