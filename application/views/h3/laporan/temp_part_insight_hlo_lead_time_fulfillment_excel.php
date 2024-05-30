<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=HLO_Lead Time Fulfillment_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : HLO_Lead Time Fulfillment <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>CSL Hotline</b></td>
 		<td align="center"><b>Total</b></td>
 	</tr>
	 <tr> 		
 		<td align="center">Kurang dari 1 Minggu</td>
 		<td align="center"><?php echo $hlo_lead_time_fulfillment->row()->kurang_dr_1 ?></td>
 	</tr>
	 <tr> 		
 		<td align="center">1-2 Minggu</td>
 		<td align="center"><?php echo $hlo_lead_time_fulfillment->row()->minggu_1_2 ?></td>
 	</tr>
	 <tr> 		
 		<td align="center">3-4 Minggu</td>
 		<td align="center"><?php echo $hlo_lead_time_fulfillment->row()->minggu_3_4 ?></td>
 	</tr>
	 <tr> 		
 		<td align="center">5-6 Minggu</td>
 		<td align="center"><?php echo $hlo_lead_time_fulfillment->row()->minggu_5_6 ?></td>
 	</tr>
	 <tr> 		
 		<td align="center">Lebih dari 8 Minggu</td>
 		<td align="center"><?php echo $hlo_lead_time_fulfillment->row()->lebih_dr_8 ?></td>
 	</tr>

</table>