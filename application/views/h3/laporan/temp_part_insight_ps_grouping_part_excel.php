<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Part Consumption Amount by Grouping Parts_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : Part Consumption Amount by Grouping Parts <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Kelompok Part</b></td>
 		<td align="center"><b>Total Sales</b></td>
 	</tr>
	 <?php 
	$sum_ytd = 0;
	if($ps_grouping_part->num_rows()>0){
		foreach ($ps_grouping_part->result() as $row) { 
		
		echo "
 			<tr>
 				<td>$row->kelompok_part</td> "; ?>
				<td><?php echo number_format($row->pendapatan,2,",",".")?></td>
 			</tr>
	<?php	
		 $sum_ytd 		 += $row->pendapatan;
 		}
	echo "
		<tr>
 				<td style='text-align:center'><b>Total</b></td>";?>
 				<td><b><?php echo number_format($sum_ytd,2,",",".")?></b></td>
 			</tr>
	<?php
	}else{
		echo "<td colspan='2' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>