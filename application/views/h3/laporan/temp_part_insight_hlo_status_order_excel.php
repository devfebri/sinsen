<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=HLO_berdasarkan Status Order".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : HLO_berdasarkan Status Order <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
		<td align="center"><b>Fulfilled</b></td>
 		<td align="center"><b>Unfulfilled</b></td>
 	</tr>
	<tr>
		<?php $unfilfilled = $hlo_status_order_all->row()->total-$hlo_status_order_fulfilled->row()->fulfilled?>
		<td><?php echo $hlo_status_order_fulfilled->row()->fulfilled?></td>
		<td><?php echo $unfilfilled?></td>
	</tr>
</table>