<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Part Consumption Based on Jenis Kelompok_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : Part Consumption Based on Jenis Kelompok <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Jenis Kelompok</b></td>
 		<td align="center"><b>Total Pendapatan</b></td>
 	</tr>
<?php 
	$sum_entri=0;
	if($ps_jenis_kelompok->num_rows()>0){
?>
 	    <tr>
 	    	<td>HGP</td>
	    	<td><b><?php echo number_format($ps_jenis_kelompok->row()->hgp,2,",",".") ?></b></td>
 	    </tr>
         <tr>
            <td>Acc&HGA</td>
            <td><b><?php echo number_format($ps_jenis_kelompok->row()->hga,2,",",".") ?></b></td>
        </tr>
		<tr>
            <td>Oil</td>
            <td><b><?php echo number_format($ps_jenis_kelompok->row()->hgo,2,",",".") ?></b></td>
        </tr>

        <?php $sum_entri= $ps_jenis_kelompok->row()->hgp+$ps_jenis_kelompok->row()->hga+$ps_jenis_kelompok->row()->hgo;	?>
        <tr>
 			<td style='text-align:center'><b>Total Pendapatan</b></td>
			<td><b><?php echo number_format($sum_entri,2,",",".") ?></b></td>
 		</tr>
<?php 
	}else{
		echo "<td colspan='3' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>