<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=HLO_berdasarkan Grouping Parts_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : HLO_berdasarkan Grouping Parts <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Nama Parts</b></td>
		<td align="center"><b>Kuantitas Parts</b></td>
 	</tr>
	 <?php 
	if($hlo_grouping_parts->num_rows()>0){
		foreach ($hlo_grouping_parts->result() as $row) { 
		echo "
 			<tr>
				<td>$row->nama_part</td>
				<td>$row->sum_qty</td>
 			</tr>
	 	";	
 		}
	}else{
		echo "<td colspan='2' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>