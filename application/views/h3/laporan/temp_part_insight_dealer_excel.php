<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Master Data Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : Master Data Dealer <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Kode Dealer</b></td>
 		<td align="center"><b>Nama Dealer</b></td>
 	</tr>
	<?php 
		foreach ($getDataDealer as $row) { 
	?>	
		<tr>
			<td><?php echo $row->kode_dealer_ahm ?></td>
			<td><?php echo $row->nama_dealer ?></td>
		</tr>
		
	<?php	}
	?>
</table>