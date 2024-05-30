<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Master Service_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : Master Service <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Jenis Filter Service</b></td>
 	</tr>
	<tr>Direct Sales</tr>
	<tr>WO</tr>
</table>