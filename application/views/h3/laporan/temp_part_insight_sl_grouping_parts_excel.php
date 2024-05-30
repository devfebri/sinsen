<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Stock Level_Grouping Parts_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : Stock Level_Grouping Parts <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Kelompok Parts</b></td>
		<td align="center"><b>Total Parts</b></td>
 	</tr>
	 <?php 
	if($sl_grouping_parts->num_rows()>0){
		foreach ($sl_grouping_parts->result() as $row) { 
		echo "
 			<tr>
				<td>$row->kelompok_part</td>
				<td>$row->total_part</td>
 			</tr>
	 	";	
 		}
	}else{
		echo "<td colspan='2' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>