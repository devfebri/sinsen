<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Penerimaan Barang By Packing Sheet_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Laporan Penerimaan Barang - <?php echo $this->input->get('no_penerimaan_barang') ?></b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>No</b></td>
 		<td align="center"><b>Tgl. Penerimaan</b></td>
 		<td align="center"><b>Tgl. PS</b></td>
 		<td align="center"><b>No. Packing Sheet</b></td>
 		<td align="center"><b>No. Karton</b></td>
 		<td align="center"><b>No. PO MD</b></td>
 		<td align="center"><b>Kode Part</b></td>
 		<td align="center"><b>Nama Part</b></td>
 		<td align="center"><b>Qty</b></td>
 		<td align="center"><b>Lokasi</b></td>
 		<td align="center"><b>Kelompok Produk</b></td>
 	</tr>
	<?php 
		$no= 1;
		foreach ($report->result_array() as $row) { 
	?>	
		<tr>
			<td><?php echo $no ?></td>
			<td><?php echo $row['tanggal_penerimaan'] ?></td>
			<td><?php echo $row['packing_sheet_date'] ?></td>
			<td><?php echo $row['packing_sheet_number'] ?></td>
			<td><?php echo $row['nomor_karton'] ?></td>
			<td><?php echo $row['no_po'] ?></td>
			<td><?php echo $row['id_part'] ?></td>
			<td><?php echo $row['nama_part'] ?></td>
			<td><?php echo $row['qty_diterima'] ?></td>
			<td><?php echo $row['kode_lokasi_rak'] ?></td>
			<td><?php echo $row['kelompok_part'] ?></td>
		</tr>
		
	<?php	$no++; }
	?>
</table>