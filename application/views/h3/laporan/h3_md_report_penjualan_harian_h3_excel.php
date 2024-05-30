<?php 
// $no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Report Penjualan H3 MD_".$start_date." s/d ".$end_date.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Report Penjualan H3 MD <?php echo $start_date." s/d ".$end_date?></b></h4>

<table border="1">  
 	<tr>
 		<td align="center"><b>No</b></td>
 		<td align="center"><b>Tanggal Faktur</b></td>
 		<td align="center"><b>No Faktur</b></td>
 		<td align="center"><b>Nama Konsumen</b></td>
 		<td align="center"><b>Part Number</b></td>
 		<td align="center"><b>Part Desc</b></td>
 		<td align="center"><b>Qty Order</b></td>
 		<td align="center"><b>Qty Supply</b></td>
 		<td align="center"><b>Harga Beli</b></td>
 		<td align="center"><b>HET</b></td>
 		<td align="center"><b>Discount</b></td>
 		<td align="center"><b>Tipe Discount</b></td>
 		<td align="center"><b>Total</b></td>
 		<td align="center"><b>Kelompok Barang</b></td>
 		<td align="center"><b>Salesman</b></td>
 		<td align="center"><b>H1</b></td>
 		<td align="center"><b>H2</b></td>
 		<td align="center"><b>H3</b></td>
 		<td align="center"><b>Barang Hadiah</b></td>
 	</tr>
<?php 
	$no = 1;	
	if($report->num_rows()>0){	
    foreach($report->result_array() as $row){
		$filter_tipe_kendaraan = '';
		$filter_tipe_kendaraan_so = '';
            if ($row['id_tipe_kendaraan'] != NULL) {
                 $filter_tipe_kendaraan = " and dsop.id_tipe_kendaraan= '" .$row['id_tipe_kendaraan'] ."'";
                 $filter_tipe_kendaraan_so = " and sop.id_tipe_kendaraan= '" .$row['id_tipe_kendaraan']."'";
            }

		$dsop = $this->db->query("SELECT dsop.harga_beli, dsop.harga_jual as het, dsop.diskon_satuan_dealer as diskon ,
		dsop.tipe_diskon_satuan_dealer as tipe_diskon, dsop.harga_setelah_diskon as total FROM tr_h3_md_do_sales_order_parts dsop WHERE dsop.id_part_int = " .$row['id_part_int'] ." and dsop.id_do_sales_order_int = ".$row['id_do_sales_order_int']." $filter_tipe_kendaraan ")->row_array();

		$sop = $this->db->query("SELECT sop.qty_order as qty_order FROM  tr_h3_md_sales_order_parts sop WHERE sop.id_part_int = " .$row['id_part_int'] ." and sop.id_sales_order_int = ".$row['id_sales_order_int']." $filter_tipe_kendaraan_so ")->row_array();

?>
	<?php 
	// if($dsop['tipe_diskon'] == 'Rupiah') {
	// 	// $total = ($row['qty_supply']*$dsop['het'])-($dsop['qty_supply']*$dsop['diskon']);	
	// }elseif($dsop['tipe_diskon'] == 'Persen'){
	// 	$diskon_nominal = ($row['qty_supply']*$dsop['het']*$dsop['diskon'])/100;
	// 	$total = ($row['qty_supply']*$dsop['het'])-$diskon_nominal;
	// }else{
	// 	$total = $row['qty_supply']*$dsop['het'];
	// } 
	$total = $row['qty_supply']*$dsop['total'];
	?>
    <tr> 	
 		<td><?php echo $no ?></td>	
 		<td><?php echo $row['tgl_faktur'] ?></td>
 		<td><?php echo $row['no_faktur'] ?></td>
 		<td><?php echo $row['nama_dealer'] ?></td>
 		<td><?php echo $row['id_part'] ?></td>
 		<td><?php echo $row['nama_part'] ?></td>
 		<td><?php echo $sop['qty_order'] ?></td>
 		<td><?php echo $row['qty_supply'] ?></td>
 		<td><?php echo number_format($dsop['harga_beli'],0,',','.') ?></td>
 		<td><?php echo number_format($dsop['het'],0,',','.')?></td>
 		<td><?php echo $dsop['diskon'] ?></td>
 		<td><?php echo $dsop['tipe_diskon'] ?></td>
 		<!-- <td><?php //echo number_format($row['total'],0,',','.') ?></td> -->
 		<td><?php echo number_format($total,0,',','.') ?></td>
 		<td><?php echo $row['kelompok_part'] ?></td>
 		<td><?php echo $row['salesman'] ?></td>
 		<td><?php echo $row['h1'] ?></td>
 		<td><?php echo $row['h2'] ?></td>
 		<td><?php echo $row['h3'] ?></td>
 		<td><?php echo $row['is_hadiah'] ?></td>
 	</tr>

<?php $no++; }?>
 	    
<?php 
	}else{
		echo "<td colspan='18' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>