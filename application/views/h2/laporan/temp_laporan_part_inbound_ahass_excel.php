<?php 
$no = date('dmY_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan_Penerimaan_Part_AHASS-".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<br>
<table border="1">   	
	<tr>
		<td colspan="5"><b><?php echo $subjudul; ?></b></td>
		<?php
		$awal= $start_date;
		$temp_date = $start_date;
		?>
	</tr>
	<tr>
		<td>No</td>
		<td>Kode Dealer</td>
		<td>Nama Dealer</td>
		<td>No Faktur</td>
		<td>Tanggal Input</td>
	</tr>
	<?php
		$i = 0;
		foreach ($data as $row){
			$i++;
			?>	
			<tr>
				<td><?php echo $i;?></td>
				<td>'<?php echo $row->kode_dealer_md;?></td>
				<td><?php echo $row->nama_dealer;?></td>
				<td><?php echo $row->id_reference;?></td>
				<td><?php echo $row->tgl;?></td>
			</tr>
			<?php
		}
	?>
</table>