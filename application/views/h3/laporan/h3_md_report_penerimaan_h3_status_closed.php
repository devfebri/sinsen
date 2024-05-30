<?php 
// $no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Report Penerimaan Barang H3 MD_".$start_date." s/d ".$end_date.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Report Penerimaan Barang H3 MD <?php echo $start_date." s/d ".$end_date?></b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>No Penerimaan</b></td>
 		<td align="center"><b>Tgl Penerimaan</b></td>
 		<td align="center"><b>No Resi</b></td>
 		<td align="center"><b>No PS</b></td>
 		<td align="center"><b>No Karton</b></td>
 		<td align="center"><b>No PO</b></td>
 		<!-- <td align="center"><b>Tipe PO</b></td> -->
 		<td align="center"><b>Part Number</b></td>
 		<td align="center"><b>Part Desc</b></td>
 		<td align="center"><b>Kelompok Barang</b></td>
 		<td align="center"><b>Qty Packing Sheet</b></td>
 		<td align="center"><b>Qty Penerimaan</b></td>
 	</tr>
<?php 
	if($status_closed->num_rows()>0){
    foreach($status_closed->result_array() as $row){
?>
    <tr> 		
 		<td><?php echo $row['no_penerimaan_barang'] ?></td>
 		<td><?php echo $row['tanggal_penerimaan'] ?></td>
 		<td><?php echo $row['no_resi'] ?></td>
 		<td><?php echo $row['packing_sheet_number'] ?></td>
 		<td><?php echo $row['nomor_karton'] ?></td>
 		<td><?php echo $row['no_po'] ?></td>
 		<td><?php echo $row['id_part'] ?></td>
 		<td><?php echo $row['nama_part'] ?></td>
 		<td><?php echo $row['kelompok_part'] ?></td>
 		<td><?php echo $row['qty_packing_sheet'] ?></td>
 		<td><?php echo $row['qty_diterima'] ?></td>
 	</tr>
<?php }?>
 	    
<?php 
	}else{
		echo "<td colspan='12' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>