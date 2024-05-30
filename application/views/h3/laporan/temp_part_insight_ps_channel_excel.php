<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Part Consumption Based on Channel_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : Part Consumption Based on Channel <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Jenis Channel</b></td>
 		<td align="center"><b>Total Penjualan</b></td>
 	</tr>
<?php 
	$sum_entri=0;
	if($ps_channel->num_rows()>0){
?>
 	    <tr>
 	    	<td>H123</td>
	    	<td><b><?php echo number_format($ps_channel->row()->h123,2,",",".") ?></b></td>
 	    </tr>
         <tr>
            <td>H23</td>
            <td><b><?php echo number_format($ps_channel->row()->h23,2,",",".") ?></b></td>
        </tr>

        <?php $sum_entri= $ps_channel->row()->h123+$ps_channel->row()->h23;	?>
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