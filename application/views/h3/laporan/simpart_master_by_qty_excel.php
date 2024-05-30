<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Master by Qty.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>SIM Part : Master by Qty </b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Kode Dealer AHM</b></td>
		<td align="center"><b>Nama Dealer</b></td>
 		<td align="center"><b>Bulan</b></td>
		<td align="center"><b>Minimum Qty</b></td>
 		<td align="center"><b>Qty</b></td>
		<td align="center"><b>Available Qty</b></td>
 		<td align="center"><b>Grouping</b></td>
		<td align="center"><b>Oil/Non Oil</b></td>
 		<td align="center"><b>Target Qty</b></td>
	    <td align="center"><b>Kelompok Barang</b></td>
 	</tr>
	<?php 
		foreach ($master_by_qty as $row) { 
	?>	
		<tr>
			<td><?php echo $row->kode_dealer_ahm ?></td>
			<td><?php echo $row->nama_dealer ?></td>
			<td><?php echo $row->bulan ?></td>
			<td><?php echo $row->minimum_qty ?></td>
			<td><?php echo $row->qty ?></td>
			<?php if($row->qty > 0){?>
				<td>Tersedia</td>
			<?php }else{?>
				<td>Tidak Tersedia</td>
			<?php }?>
			<?php if($row->qty > 0){?>
				<td>1</td>
			<?php }else{?>
				<td>0</td>
			<?php }?>
			<td><?php echo $row->tipe_oli ?></td>
			<td>0,95</td>
			<td><?php echo $row->kelompok_part ?></td>
		</tr>
		
	<?php	}
	?>
</table>