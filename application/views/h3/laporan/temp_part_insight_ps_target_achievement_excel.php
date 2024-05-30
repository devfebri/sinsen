<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Part Consumption Amount by Target Achievement_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : Part Consumption Amount by Target Achievement <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Nama Dealer</b></td>
 		<td align="center"><b>Total Sales</b></td>
 	</tr>
	 <?php 
	$sum_ytd = 0;
	if($ps_target_achievement->num_rows()>0){
		foreach ($ps_target_achievement->result() as $row) { 
		
		echo "
 			<tr>
 				<td>$row->nama_dealer</td> ";?>
				<td><?php echo number_format($row->ytd,2,",",".") ?></td>
 			</tr>
		<?php
		 $sum_ytd 		 += $row->ytd;
 		}
	echo "
		<tr>
 				<td style='text-align:center'><b>Total</b></td> ";?>
 				<td><b><?php echo number_format($sum_ytd,2,",",".") ?></b></td>
 			</tr>
	<?php 
	}else{
		echo "<td colspan='2' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>