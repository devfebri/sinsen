<?php 
$no = date("dmyhis");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=penjualan_unit_type_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">No Invoice</td>
 		<td align="center">Tgl Invoice</td>
 		<td align="center">No DO</td>
 		<td align="center">Tgl DO</td> 		
 		<td align="center">Dealer</td> 		
 		<td align="center">Kode Tipe Kendaraan</td> 		
 		<td align="center">Qty</td>
 	</tr>
 	<?php 
 	$no=1; 	
 	$where = "";
 	if($id_dealer!=''){
		$where .= "AND tr_do_po.id_dealer = '$id_dealer'";
	}	
	if($id_tipe_kendaraan!=''){
		$where .= "AND ms_item.id_tipe_kendaraan = '$id_tipe_kendaraan'";
	}
 	$sql = $this->db->query("SELECT tr_invoice_dealer.no_faktur,tr_invoice_dealer.tgl_faktur,tr_do_po.no_do,tr_do_po.tgl_do,ms_dealer.nama_dealer,
 			ms_item.id_tipe_kendaraan,SUM(tr_do_po_detail.qty_do) AS jum FROM tr_invoice_dealer 
 			INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do  			
			INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do	
			LEFT JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer			
			LEFT JOIN ms_item ON tr_do_po_detail.id_item = ms_item.id_item
 			WHERE tr_invoice_dealer.tgl_faktur BETWEEN '$tgl1' AND '$tgl2' $where
 			GROUP BY tr_invoice_dealer.no_faktur,ms_item.id_tipe_kendaraan"); 	 	
 	foreach ($sql->result() as $row) { 		
 		echo "
 			<tr>
 				<td>$no</td>
 				<td>$row->no_faktur</td>
 				<td>$row->tgl_faktur</td>
 				<td>$row->no_do</td>
 				<td>$row->tgl_do</td> 				
 				<td>$row->nama_dealer</td> 				
 				<td>$row->id_tipe_kendaraan</td> 			
 				<td>$row->jum</td>
 			</tr>
 		";
 		$no++;
 	}
 	?>
</table>


