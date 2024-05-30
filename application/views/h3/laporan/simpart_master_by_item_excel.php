<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Master by Item.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>SIM Part : Master by Qty </b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Kode Dealer AHM</b></td>
		<td align="center"><b>Nama Dealer</b></td>
 		<td align="center"><b>Bulan</b></td>
		<td align="center"><b>Target</b></td>
 		<td align="center"><b>Item</b></td>
 		<td align="center"><b>Grouping</b></td>
 		<td align="center"><b>Kode Part</b></td>
 		<td align="center"><b>Nama Part</b></td>
	    <td align="center"><b>Kelompok Barang</b></td>
 	</tr>
	<?php 
		foreach ($master_by_qty->result() as $row) { 
	?>	
		<tr>
			<td><?php echo $row->kode_dealer_ahm ?></td>
			<td><?php echo $row->nama_dealer ?></td>
			<td><?php echo $row->bulan ?></td>
			<td><?php echo $row->target ?></td>
			<td><?php echo number_format($row->grouping/$master_by_qty->num_rows(), 2, '.', ',') ?></td>
			<td><?php echo $row->grouping ?></td>
			<td><?php echo $row->id_part ?></td>
			<td><?php echo $row->nama_part ?></td>
			<td><?php echo $row->kelompok_part ?></td>
		</tr>
		
	<?php	}
	?>
</table>