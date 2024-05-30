<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=HLO_Outstanding_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : HLO_Outstanding- <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Waktu Outstanding HLO Order</b></td>
 		<td align="center"><b>Total</b></td>
 	</tr>
	 <tr> 		
 		<td align="center">Kurang dari 1 Minggu</td>
 		<td align="center"><?php echo $hlo_outstanding_belum_dipenuhi->row()->kurang_dr_1 + $hlo_outstanding_belum_dipenuhi_semua->row()->kurang_dr_1 ?></td>
 	</tr>
	 <tr> 		
 		<td align="center">1-2 Minggu</td>
 		<td align="center"><?php echo $hlo_outstanding_belum_dipenuhi->row()->minggu_1_2 + $hlo_outstanding_belum_dipenuhi_semua->row()->minggu_1_2 ?></td>
 	</tr>
	 <tr> 		
 		<td align="center">3-4 Minggu</td>
 		<td align="center"><?php echo $hlo_outstanding_belum_dipenuhi->row()->minggu_3_4 + $hlo_outstanding_belum_dipenuhi_semua->row()->minggu_3_4 ?></td>
 	</tr>
	 <tr> 		
 		<td align="center">5-6 Minggu</td>
 		<td align="center"><?php echo $hlo_outstanding_belum_dipenuhi->row()->minggu_5_6 + $hlo_outstanding_belum_dipenuhi_semua->row()->minggu_5_6  ?></td>
 	</tr>
	 <tr> 		
 		<td align="center">Lebih dari 8 Minggu</td>
 		<td align="center"><?php echo $hlo_outstanding_belum_dipenuhi->row()->lebih_dr_8 + $hlo_outstanding_belum_dipenuhi_semua->row()->lebih_dr_8 ?></td>
 	</tr>

</table>