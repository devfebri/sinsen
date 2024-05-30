<?php 
$no = date("dmyhis");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=data_intransit_qty_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">No SIPB</td>
 		<td align="center">Tgl SIPB</td>
 		<td align="center">No SL</td>
 		<td align="center">Tgl Sl</td>
 		<td align="center">Ekspedisi</td> 		
 		<td align="center">No Polisi</td> 		 		
 		<td align="center">Qty</td> 		
 	</tr>
 	<?php 
 	$no=1;
 	$where = "";
 	if($id_vendor!=''){
		$where .= "AND tr_scan_barcode.nama_ekspedisi = '$id_vendor'";
	}		 

	$tgl_a = substr($tgl1, 8,2);
	$tgl_b = substr($tgl1, 5,2);
	$tgl_c = substr($tgl1, 0,4);
  $tanggal_a = $tgl_c.$tgl_b.$tgl_a;

  $tgl_d = substr($tgl2, 8,2);
	$tgl_e = substr($tgl2, 5,2);
	$tgl_f = substr($tgl2, 0,4);
  $tanggal_b = $tgl_f.$tgl_e.$tgl_d;
 	$sql = $this->db->query("SELECT tr_shipping_list.no_sipb,tr_shipping_list.no_shipping_list,
 		tr_shipping_list.tgl_sl,tr_penerimaan_unit.ekspedisi,tr_penerimaan_unit.no_polisi,tr_scan_barcode.id_item,
 		tr_scan_barcode.no_mesin,tr_scan_barcode.no_rangka
 		FROM tr_penerimaan_unit 
 		LEFT JOIN tr_penerimaan_unit_detail ON tr_penerimaan_unit.id_penerimaan_unit = tr_penerimaan_unit_detail.id_penerimaan_unit 
 		LEFT JOIN tr_shipping_list ON tr_penerimaan_unit_detail.no_shipping_list = tr_shipping_list.no_shipping_list
 		LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_detail.no_shipping_list = tr_scan_barcode.no_shipping_list 		
 		WHERE tr_shipping_list.tgl_sl BETWEEN '$tanggal_a' AND '$tanggal_b'
 		$where"); 	 	
 	foreach ($sql->result() as $row) { 		 		 		
 		$row2 = $this->m_admin->getByID("tr_sipb","no_sipb",$row->no_sipb);
 		$tgl_sipb = ($row2->num_rows() > 0) ? $row2->row()->tgl_sipb:""; 		

 		$row3 = $this->m_admin->getByID("ms_vendor","id_vendor",$row->ekspedisi);
 		$nama_vendor = ($row3->num_rows() > 0) ? $row3->row()->vendor_name:""; 		 		

 		$qty = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE no_shipping_list = '$row->no_shipping_list'")->row()->jum;
 		echo "
 			<tr>
 				<td>$no</td>
 				<td>$row->no_sipb</td>
 				<td>$tgl_sipb</td>
 				<td>$row->no_shipping_list</td>
 				<td>$row->tgl_sl</td>
 				<td>$row->ekspedisi</td> 			 				
 				<td>$row->no_polisi</td> 			 				
 				<td>$qty</td> 			
 			</tr>
 		";
 		$no++;	 	
 	}
 	?>
</table>
