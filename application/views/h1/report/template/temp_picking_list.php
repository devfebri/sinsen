<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Picking_list_unit_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">No</td>
 		<td align="center">No PL</td>
 		<td align="center">Tgl PL</td> 		 		
 		<td align="center">No DO</td>
 		<td align="center">Nama Dealer</td> 		 		
 		<td align="center">Kode Item</td> 			 		
 		<td align="center">Tipe Motor</td> 			 		
 		<td align="center">Warna</td> 			 		
 		<td align="center">Deskripsi</td> 		 		
 		<td align="center">No Mesin</td> 		 		 		
 		<td align="center">No Rangka</td> 		 		 		
 	</tr>
 	<?php  	
 	$no=1;
 	$sql = $this->db->query("SELECT tr_picking_list.no_picking_list, tr_picking_list.tgl_pl, tr_picking_list.no_do, ms_dealer.nama_dealer,
		tr_scan_barcode.id_item, tr_scan_barcode.no_mesin, tr_scan_barcode.no_rangka , tr_scan_barcode.tipe_motor, tr_scan_barcode.warna, ms_tipe_kendaraan.tipe_ahm
		FROM tr_picking_list_view
		INNER JOIN tr_scan_barcode on tr_picking_list_view.no_mesin = tr_scan_barcode.no_mesin
		INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = tr_scan_barcode.tipe_motor
 		INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
 		INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
 		INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
 		WHERE tr_picking_list.tgl_pl BETWEEN '$tgl1' AND '$tgl2' AND tr_picking_list.status = 'close'");
 	foreach ($sql->result() as $isi) { 		
 		// $row2 = $this->db->query("SELECT tipe_ahm FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan = '$isi->tipe_motor'");
 		// $tipe_motor = ($row2->num_rows() > 0) ? $row2->row()->tipe_ahm:""; 		
 		echo "
 		<tr>
 			<td>$no</td>
 			<td>$isi->no_picking_list</td> 			 			
 			<td>$isi->tgl_pl</td> 			 			
 			<td>$isi->no_do</td> 			 			
 			<td>$isi->nama_dealer</td> 			 			
 			<td>$isi->id_item</td> 			 			
 			<td>$isi->tipe_motor</td> 			 			
 			<td>$isi->warna</td> 			 			
 			<td>$isi->tipe_ahm</td> 			 			
 			<td>$isi->no_mesin</td> 			 			
 			<td>$isi->no_rangka</td> 			 			
 		</tr>
 		";
 		$no++; 	
 	}
 	?>
</table>
