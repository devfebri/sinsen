<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Part Consumption Based on Service_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : Part Consumption Based on Service <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Jenis Penjualan</b></td>
 		<td align="center"><b>Total Pendapatan</b></td>
 	</tr>
<?php 
	$sum_entri=0;
	if($ps_service->num_rows()>0){
?>
 	    <tr>
 	    	<td>Work Order</td>
	    	<td><b><?php echo number_format($ps_service->row()->wo,2,",",".") ?></b></td>
 	    </tr>
         <tr>
            <td>Direct Sales</td>
            <td><b><?php echo number_format($ps_service->row()->sales,2,",",".") ?></b></td>
        </tr>

        <?php $sum_entri= $ps_service->row()->wo+$ps_service->row()->sales;	?>
        <tr>
 			<td style='text-align:center'><b>Total Pendapatan</b></td>
			<td><b><?php echo number_format($sum_entri,2,",",".") ?></b></td>
 		</tr>
<?php 
	}else{
		echo "<td colspan='2' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>