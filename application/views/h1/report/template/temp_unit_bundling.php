<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Unit_bundling_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">No</td>
 		<td align="center">Kode Item</td>
 		<td align="center">No Mesin</td> 		 		
 		<td align="center">No Rangka</td>
 		<td align="center">No Faktur</td> 		 		
 		<td align="center">Nama Dealer</td> 		 		 		 		
 	</tr>
 	<?php  	
 	$no=1;
 	$sql = $this->db->query("SELECT ms_paket_bundling.id_item_baru,tr_wo_bundling_nosin.no_mesin FROM tr_wo_bundling 
 		INNER JOIN tr_wo_bundling_nosin ON tr_wo_bundling.no_wo_bundling = tr_wo_bundling_nosin.no_wo_bundling
 		INNER JOIN ms_paket_bundling ON tr_wo_bundling.id_paket_bundling = ms_paket_bundling.id_paket_bundling 		
 		WHERE tr_wo_bundling.tgl_paket BETWEEN '$tgl1' AND '$tgl2' and tr_wo_bundling.status_paket !='canceled'");
 	foreach ($sql->result() as $isi) { 		
 		$row2 = $this->db->query("SELECT tr_scan_barcode.no_rangka, ms_dealer.nama_dealer, tr_invoice_dealer.no_faktur FROM tr_picking_list_view 
 			INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list  			
 			INNER JOIN tr_invoice_dealer ON tr_picking_list.no_do = tr_invoice_dealer.no_do 			 			
 			INNER JOIN tr_scan_barcode ON tr_picking_list_view.no_mesin = tr_scan_barcode.no_mesin
 			INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
 			INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
 			WHERE tr_picking_list_view.no_mesin = '$isi->no_mesin'");
 		$no_rangka = ($row2->num_rows() > 0) ? $row2->row()->no_rangka:""; 		
 		$nama_dealer = ($row2->num_rows() > 0) ? $row2->row()->nama_dealer:""; 		
 		$no_faktur = ($row2->num_rows() > 0) ? $row2->row()->no_faktur:""; 		
 		echo "
 		<tr>
 			<td>$no</td>
 			<td>$isi->id_item_baru</td> 			 			 			 			
 			<td>$isi->no_mesin</td> 			 			 			 			
 			<td>$no_rangka</td> 			 			 			 			
 			<td>$no_faktur</td> 			 			 			 			
 			<td>$nama_dealer</td> 			 			 			 			
 		</tr>
 		";
 		$no++; 	
 	}
 	?>
</table>
