<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Alert Growth_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : Alert Growth <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Nama Dealer</b></td>
 		<td align="center"><b>M-1</b></td>
 		<td align="center"><b>M</b></td>
 		<td align="center"><b>%M vs M-1</b></td>
 		<td align="center"><b>YTD</b></td>
 	</tr>
	 <?php 
	$sum_m_seb=0;
	$sum_m = 0;
	$sum_persentase = 0;
	$sum_ytd = 0;
	if($ps_alert_growth->num_rows()>0){
		foreach ($ps_alert_growth->result() as $row) { 
			if($row->Msebelum != 0){
				$persentase = number_format((($row->M-$row->Msebelum)/$row->Msebelum)*100,2,",",".");
			}else{
				$persentase = number_format(0,2,",",".");
			}
			
		
		echo "
 			<tr>
 				<td>$row->nama_dealer</td> "; ?>
 				<td><?php echo number_format($row->Msebelum,2,",",".")?></td>
 				<td><?php echo number_format($row->M,2,",",".")?></td>
 				<td><?php echo $persentase?> %</td>
				<td><?php echo number_format($row->ytd,2,",",".")?></td>
 			</tr>
	<?php 
		 $sum_m_seb		 += $row->Msebelum;	
		 $sum_m 		 += $row->M;	
		 $sum_persentase += $persentase;	
		 $sum_ytd 		 += $row->ytd;
 		}
	echo "
		<tr>
 				<td style='text-align:center'><b>Total</b></td>";
	?>
				<td><b><?php echo number_format($sum_m_seb,2,",",".") ?></b></td>
 				<td><b><?php echo number_format($sum_m,2,",",".")?></b></td>
 				<td><b><?php echo number_format($sum_persentase,2,",",".")?></b></td>
 				<td><b><?php echo number_format($sum_ytd,2,",",".")?></b></td>
 		</tr>
	<?php 
	}else{
		echo "<td colspan='5' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>