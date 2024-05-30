<?php 
$no = date("dmyhis");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=data_unfill_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">Kode Ekspedisi</td>
 		<td align="center">Ekspedisi</td>
 		<td align="center">Tgl SIPB</td>
 		<td align="center">No SIPB</td>
 		<td align="center">Kode Item</td> 		
 		<td align="center">Tipe Kendaraan</td> 		 		
 		<td align="center">Warna</td> 		
 		<td align="center">Qty Unfill</td> 		
 		<td align="center">Qty SIPB</td> 		
 		<td align="center">Qty SL</td> 		
 		<td align="center">Qty Cancel SIPB</td> 		
 	</tr>
 	<?php 
 	$no=1; 
	$tgl_a = substr($tgl1, 8,2);
	$tgl_b = substr($tgl1, 5,2);
	$tgl_c = substr($tgl1, 0,4);
  $tanggal_a = $tgl_a.$tgl_b.$tgl_c;

  $tgl_d = substr($tgl2, 8,2);
	$tgl_e = substr($tgl2, 5,2);
	$tgl_f = substr($tgl2, 0,4);
  $tanggal_b = $tgl_d.$tgl_e.$tgl_f;
 	$sql = $this->db->query("SELECT tr_shipping_list.nama_eks,tr_sipb.tgl_sipb,tr_sipb.no_sipb,tr_shipping_list.id_modell,tr_shipping_list.id_warna FROM tr_shipping_list INNER JOIN tr_sipb ON tr_shipping_list.no_sipb = tr_sipb.no_sipb 		
 		WHERE tr_shipping_list.tgl_sl BETWEEN '$tanggal_a' AND '$tanggal_b' GROUP BY tr_shipping_list.no_sipb"); 	 	
 	foreach ($sql->result() as $row) { 		 		 		
 		$row2 = $this->m_admin->getByID("tr_sipb","no_sipb",$row->no_sipb);
 		$tgl_sipb = ($row2->num_rows() > 0) ? $row2->row()->tgl_sipb:""; 		

 		$row3 = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
 			INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna 
 			WHERE ms_item.id_tipe_kendaraan = '$row->id_modell' AND ms_item.id_warna = '$row->id_warna'");
 		$tipe_ahm = ($row3->num_rows() > 0) ? $row3->row()->tipe_ahm:""; 		 		
 		$warna = ($row3->num_rows() > 0) ? $row3->row()->warna:""; 		 		
 		$id_item = ($row3->num_rows() > 0) ? $row3->row()->id_item:""; 		 		

 		$qty_sipb = $this->db->query("SELECT * FROM tr_sipb WHERE no_sipb = '$row->no_sipb' AND id_tipe_kendaraan = '$row->id_modell' AND id_warna = '$row->id_warna'")->row()->jumlah;
 		$qty_sl = $this->db->query("SELECT count(no_mesin) AS jum FROM tr_shipping_list WHERE no_sipb = '$row->no_sipb' AND id_modell = '$row->id_modell' AND id_warna = '$row->id_warna'")->row()->jum;
 		$qty_unfill = $qty_sipb - $qty_sl; 		
 		$qty_cancel_sipb = 0;
 		echo "
 			<tr>
 				<td>$no</td>
 				<td>$row->nama_eks</td>
 				<td>$row->nama_eks</td>
 				<td>$row->tgl_sipb</td>
 				<td>$row->no_sipb</td>
 				<td>$id_item</td> 			 				
 				<td>$tipe_ahm</td> 			 				
 				<td>$warna</td> 			 				
 				<td>$qty_unfill</td> 			 				
 				<td>$qty_sipb</td> 			 				
 				<td>$qty_sl</td> 			 				
 				<td>$qty_cancel_sipb</td> 			 				
 			</tr>
 		";
 		$no++;	 	
 	}
 	?>
</table>
