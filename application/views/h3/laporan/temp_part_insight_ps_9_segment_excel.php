<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Part Consumption Amount by 9 Segment_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : Part Consumption Amount by 9 Segment <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Segment</b></td>
		<td align="center"><b>Pendapatan</b></td>
 	</tr>
	 <?php 
	$sum_ytd = 0;
	if($ps_9_segment->num_rows()>0){
	?>
		<tr>
 	    	<td>AT High</td>
	    	<td><b><?php echo number_format($ps_9_segment->row()->at_high,2,",",".") ?></b></td>
 	    </tr>
         <tr>
            <td>AT Low</td>
            <td><b><?php echo number_format($ps_9_segment->row()->at_low,2,",",".") ?></b></td>
        </tr>
		<tr>
 	    	<td>AT Medium</td>
	    	<td><b><?php echo number_format($ps_9_segment->row()->at_medium,2,",",".") ?></b></td>
 	    </tr>
         <tr>
            <td>Cub High</td>
            <td><b><?php echo number_format($ps_9_segment->row()->cub_high,2,",",".") ?></b></td>
        </tr>
		<tr>
 	    	<td>Cub Low</td>
	    	<td><b><?php echo number_format($ps_9_segment->row()->cub_low,2,",",".") ?></b></td>
 	    </tr>
         <tr>
            <td>Cub Medium</td>
            <td><b><?php echo number_format($ps_9_segment->row()->cub_medium,2,",",".") ?></b></td>
        </tr>
		<tr>
 	    	<td>Sport High</td>
	    	<td><b><?php echo number_format($ps_9_segment->row()->sport_high,2,",",".") ?></b></td>
 	    </tr>
         <tr>
            <td>Sport Low</td>
            <td><b><?php echo number_format($ps_9_segment->row()->sport_low,2,",",".") ?></b></td>
        </tr>
		<tr>
            <td>Sport Medium</td>
            <td><b><?php echo number_format($ps_9_segment->row()->sport_medium,2,",",".") ?></b></td>
        </tr>
        <?php $sum_entri= $ps_9_segment->row()->sport_medium+$ps_9_segment->row()->sport_low+$ps_9_segment->row()->sport_high+$ps_9_segment->row()->cub_medium+$ps_9_segment->row()->cub_low+$ps_9_segment->row()->cub_high+$ps_9_segment->row()->at_medium+$ps_9_segment->row()->at_low+$ps_9_segment->row()->at_high;	?>
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