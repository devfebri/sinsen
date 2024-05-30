<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Total Penjualan Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : Total Penjualan Dealer <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Nama Dealer</b></td>
 		<td align="center"><b>Total Pendapatan</b></td>
 	</tr>
	 <?php 
	if($sl_penjualan_dealer->num_rows()>0){
		foreach ($sl_penjualan_dealer->result() as $row) { 

		echo "
 			<tr>
 				<td>$row->nama_dealer</td>";?>

				<td><?php echo number_format($row->ytd,2,",",".")?></td>
 			</tr>
		<?php 
 		}
	}else{
		echo "<td colspan='2' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>