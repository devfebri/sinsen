<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Master SIM Part.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>SIM Part : Data SIM PART</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Kode Dealer AHM</b></td>
		 <td align="center"><b>Kode Part</b></td>
		 <td align="center"><b>Nama Part</b></td>
		 <td align="center"><b>Kelompok Part</b></td>
		 <td align="center"><b>Minimum Qty SIM Part</b></td>
		 <td align="center"><b>Qty Stock On Hand</b></td>
		 <td align="center"><b>Kategori UE</b></td>
		 <td align="center"><b>UE</b></td>
		 <td align="center"><b>Tanggal</b></td>
 	</tr>
	<?php 
		foreach ($master_sim_part as $row) { 
	?>	
		<tr>
			<td>'<?php echo $row->kode_dealer_ahm ?></td>
			<td><?php echo $row->id_part ?></td>
			<td><?php echo $row->nama_part ?></td>
			<td><?php echo $row->kelompok_part ?></td>
			<td><?php echo $row->qty_sim_part ?></td>
			<td><?php echo $row->stock_on_hand ?></td>
			<td><?php echo $row->kategori_ahass ?></td>
			<td><?php echo $row->unit_entry ?></td>
			<td><?php echo date('d/m/y') ?></td>
		</tr>
		
	<?php	}
	?>
</table>