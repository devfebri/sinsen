<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Master Kelompok Parts_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : Master Kelompok Parts <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Kelompok Parts</b></td>
		 <td align="center"><b>Major Group</b></td>
 	</tr>
	<?php 
		foreach ($grouping_parts as $row) { 
	?>	
		<tr>
			<td><?php echo $row->kelompok_part ?></td>
			<td><?php echo $row->major_group ?></td>
		</tr>
		
	<?php	}
	?>
</table>