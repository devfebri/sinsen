<?php 
// $no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan EV_".$start_date." s/d ".$end_date.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Report Penerimaan Barang H3 MD <?php echo $start_date." s/d ".$end_date?></b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Kode PN</b></td>
 		<td align="center"><b>Nama Part</b></td>
 		<td align="center"><b>Kelompok Part</b></td>
 		<td align="center"><b>Type Acc</b></td>
 		<td align="center"><b>Serial Number</b></td>
 		<td align="center"><b>Status Barang</b></td>
 		<td align="center"><b>No SL AHM</b></td>
 		<td align="center"><b>Tgl SL AHM</b></td>
 		<td align="center"><b>No Penerimaan Barang MD</b></td>
 		<td align="center"><b>Tgl Penerimaan Barang MD</b></td>
 		<td align="center"><b>Kode Lokasi Rak MD</b></td>
 		<td align="center"><b>FIFO</b></td>
 		<td align="center"><b>No Booking DO</b></td>
 		<td align="center"><b>No Faktur</b></td>
 		<td align="center"><b>Tgl Faktur</b></td>
 		<td align="center"><b>No Packing Sheet</b></td>
 		<td align="center"><b>Tgl Packing Sheet</b></td>
 		<td align="center"><b>No Surat Pengantar</b></td>
 		<td align="center"><b>Tgl Surat Pengantar</b></td>
 		<td align="center"><b>Nama Dealer</b></td>
 		<td align="center"><b>No Penerimaan Dealer</b></td>
 		<td align="center"><b>Tgl Penerimaan Dealer</b></td>
 		<td align="center"><b>Kode Lokasi Rak Dealer</b></td>
 		<td align="center"><b>No NSC</b></td>
 		<td align="center"><b>Tgl NSC</b></td>
 		<td align="center"><b>Nama Customer</b></td>
 		<td align="center"><b>No Mesin</b></td>
 		<td align="center"><b>Tgl Rangka</b></td>
 		<td align="center"><b>Tgl Hp</b></td>
 	</tr>
<?php 
	if($report->num_rows()>0){
    foreach($report->result_array() as $row){
?>
    <tr> 		
 		<td><?php echo $row['id_part'] ?></td>
 		<td><?php echo $row['nama_part'] ?></td>
 		<td><?php echo $row['kelompok_part'] ?></td>
 		<td><?php echo $row['type_acc'] ?></td>
 		<td><?php echo $row['serial_number'] ?></td>
 		<td><?php echo $row['accStatus'] ?></td>
 		<td><?php echo $row['no_shipping_list'] ?></td>
 		<td><?php echo $row['tgl_sl'] ?></td>
 		<td><?php echo $row['no_penerimaan_barang_md'] ?></td>
 		<td><?php echo $row['tgl_penerimaan_md'] ?></td>
 		<td><?php echo $row['kode_lokasi_rak_md'] ?></td>
 		<td><?php echo $row['fifo'] ?></td>
 		<td><?php echo $row['id_do_sales_order'] ?></td>
 		<td><?php echo $row['no_faktur'] ?></td>
 		<td><?php echo $row['tgl_faktur'] ?></td>
 		<td><?php echo $row['id_packing_sheet'] ?></td>
 		<td><?php echo $row['tgl_packing_sheet'] ?></td>
 		<td><?php echo $row['id_surat_pengantar_md'] ?></td>
 		<td><?php echo $row['tgl_surat_pengantar'] ?></td>
 		<td><?php echo $row['nama_dealer'] ?></td>
 		<td><?php echo $row['id_penerimaan_dealer'] ?></td>
 		<td><?php echo $row['tgl_penerimaan_dealer'] ?></td>
 		<td><?php echo $row['id_lokasi_rak_dealer'] ?></td>
 		<td><?php echo $row['no_nsc'] ?></td>
 		<td><?php echo $row['tgl_nsc'] ?></td>
 		<td><?php echo $row['nama_customer'] ?></td>
 		<td><?php echo $row['no_mesin'] ?></td>
 		<td><?php echo $row['no_rangka'] ?></td>
 		<td><?php echo $row['no_hp'] ?></td>
 	</tr>
<?php }?>
 	    
<?php 
	}else{
		echo "<td colspan='29' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>