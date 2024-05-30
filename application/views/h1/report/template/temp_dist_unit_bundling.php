<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Dist_unit_bundling_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">No</td>
 		<td align="center">No DO</td>
 		<td align="center">Tgl DO</td> 		 		
 		<td align="center">No Faktur</td> 		 		
 		<td align="center">No Surat Jalan</td>
 		<td align="center">Tgl Surat Jalan</td> 		 		 		 		
 		<td align="center">Kode Dealer</td> 		 		 		 		
 		<td align="center">Penerima</td> 		 		 		 		
 		<td align="center">Kode Item</td> 		 		 		 		
 		<td align="center">No Mesin</td> 		 		 		 		
 		<td align="center">No Rangka</td> 		 		 		 		
 	</tr>
 	<?php  	
 	$no=1;
 	$sql = $this->db->query("SELECT ms_paket_bundling.id_item_baru,tr_wo_bundling_nosin.no_mesin,tr_surat_jalan.no_picking_list,tr_surat_jalan.no_surat_jalan,
 		tr_surat_jalan.tgl_surat FROM tr_wo_bundling INNER JOIN tr_wo_bundling_nosin ON tr_wo_bundling.no_wo_bundling = tr_wo_bundling_nosin.no_wo_bundling
 		LEFT JOIN ms_paket_bundling ON tr_wo_bundling.id_paket_bundling = ms_paket_bundling.id_paket_bundling 		
 		INNER JOIN tr_surat_jalan_detail ON tr_wo_bundling_nosin.no_mesin = tr_surat_jalan_detail.no_mesin
 		INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
 		WHERE tr_wo_bundling.tgl_paket BETWEEN '$tgl1' AND '$tgl2' AND tr_wo_bundling.status_paket = 'closed'");
 	foreach ($sql->result() as $isi) { 		
 		$row2 = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$isi->no_mesin'");
 		$no_rangka = ($row2->num_rows() > 0) ? $row2->row()->no_rangka:""; 		
 		$id_item = ($row2->num_rows() > 0) ? $row2->row()->id_item:""; 		


 		$row1 = $this->db->query("SELECT * FROM tr_picking_list 
 			INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
 			INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer 			
 			WHERE tr_picking_list.no_picking_list = '$isi->no_picking_list'");
 		$no_do = ($row1->num_rows() > 0) ? $row1->row()->no_do:""; 		
 		$tgl_do = ($row1->num_rows() > 0) ? $row1->row()->tgl_do:""; 		
 		$kode_dealer = ($row1->num_rows() > 0) ? $row1->row()->kode_dealer_md:""; 		
 		$nama_dealer = ($row1->num_rows() > 0) ? $row1->row()->nama_dealer:""; 		
 		
 		$row3 = $this->db->query("SELECT * FROM tr_invoice_dealer WHERE no_do = '$no_do'");
 		$no_faktur = ($row3->num_rows() > 0) ? $row3->row()->no_faktur:""; 		 		
 		echo "
 		<tr>
 			<td>$no</td>
 			<td>$no_do</td> 			 			 			 			 			 			
 			<td>$tgl_do</td> 			 			 			 			 			 			
 			<td>$no_faktur</td> 			 			 			 			 			 			
 			<td>$isi->no_surat_jalan</td> 			 			 			 			 			 			
 			<td>$isi->tgl_surat</td> 			 			 			 			 			 			
 			<td>$kode_dealer</td> 			 			 			 			 			 			
 			<td>$nama_dealer</td> 			 			 			 			 			 			
 			<td>$id_item</td> 			 			 			 			 			 			
 			<td>$isi->no_mesin</td> 			 			 			 			 			 			
 			<td>$no_rangka</td> 			 			 			 			 			 			
 		</tr>
 		";
 		$no++; 	
 	}
 	?>
</table>
