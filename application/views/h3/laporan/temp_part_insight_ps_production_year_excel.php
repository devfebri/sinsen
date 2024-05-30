<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Part Consumption by Production Year_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : Part Consumption by Production Year <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Tahun Produksi</b></td>
 		<td align="center"><b>Kuantitas</b></td>
 	</tr>
	 <?php 
	$sum_ytd = 0;
	if($ps_production_year->num_rows()>0){
		foreach ($ps_production_year->result() as $row) { 
		
		echo "
 			<tr>
 				<td>$row->tahun_produksi</td> "; ?>
				<td><?php echo $row->qty?></td>
 			</tr>
		
	<?php }	
	}else{
		echo "<td colspan='2' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>