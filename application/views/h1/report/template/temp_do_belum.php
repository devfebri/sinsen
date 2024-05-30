<?php 
$no = date("dmyHis");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$nama_file.'_'.$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">No SIPB</td>
 		<td align="center">Tgl SIPB</td> 			 		
 		<td align="center">No SL</td> 		
 		<td align="center">Tgl SL</td> 			
 		<td align="center">Kode Item</td>		
 		<td align="center">Deskripsi</td> 		
 		<td align="center">Warna</td> 	
 		<td align="center">Qty SIPB</td> 	
 		<td align="center">Qty Terima (SL)</td> 		
 		<td align="center">Qty Sisa DO</td> 		 		
 	</tr>
 	<?php 
 	$no=1; 	
 	$sql = $this->db->query("SELECT no_sipb, tgl_sipb, jumlah,id_tipe_kendaraan, id_warna FROM tr_sipb WHERE concat(right(tgl_sipb,4),'-',mid(tgl_sipb,3,2),'-',left(tgl_sipb,2)) BETWEEN '$tgl1' AND '$tgl2'"); 	 	
 	foreach ($sql->result() as $row) { 		 		 		 		
 		$row3 = $this->db->query("SELECT ms_tipe_kendaraan.tipe_ahm, ms_item.id_item, ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
 			INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna 
 			WHERE ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND ms_item.id_warna = '$row->id_warna'");
 		$tipe_ahm = ($row3->num_rows() > 0) ? $row3->row()->tipe_ahm:""; 		 		
 		$warna = ($row3->num_rows() > 0) ? $row3->row()->warna:""; 		 		
 		$id_item = ($row3->num_rows() > 0) ? $row3->row()->id_item:""; 		 		

 		$row4 = $this->db->query("SELECT no_shipping_list,tgl_sl FROM tr_shipping_list WHERE no_sipb = '$row->no_sipb' AND id_modell = '$row->id_tipe_kendaraan' AND id_warna = '$row->id_warna'");
 		$no_sl = ($row4->num_rows() > 0) ? $row4->row()->no_shipping_list:"";
 		$tgl_sl = ($row4->num_rows() > 0) ? $row4->row()->tgl_sl:"";
 		// $qty_sipb = $this->db->query("SELECT jumlah FROM tr_sipb WHERE no_sipb = '$row->no_sipb' AND id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND id_warna = '$row->id_warna'")->row()->jumlah;
 		$qty_terima = $this->db->query("SELECT count(no_mesin) AS jum FROM tr_shipping_list WHERE no_sipb = '$row->no_sipb' AND id_modell = '$row->id_tipe_kendaraan' AND id_warna = '$row->id_warna'")->row()->jum; 		 		
 		$qty_sisa = $row->jumlah - $qty_terima;

		$tgl_sipb_format = substr($row->tgl_sipb,0,2).'-'.substr($row->tgl_sipb,2,2).'-'.substr($row->tgl_sipb,4,4);
		$tgl_sl_format = substr($tgl_sl ,0,2).'-'.substr($tgl_sl ,2,2).'-'.substr($tgl_sl ,4,4);

		if($all_sipb !== 'sipb_only' && $all_sipb !=='sipb_sl'){
			if($qty_sisa > 0){
				echo "
					<tr>
						<td>$no</td>
						<td>$row->no_sipb</td>
						<td>$tgl_sipb_format</td> 		 				
						<td>$no_sl</td> 			 				
						<td>$tgl_sl_format</td> 		
						<td>$id_item</td> 			
						<td>$tipe_ahm</td> 			
						<td>$warna</td> 						
						<td>$row->jumlah</td> 			 				 				
						<td>$qty_terima</td> 			 				
						<td>$qty_sisa</td> 			 				 				
					</tr>
				";
			}
		}else{
			echo "
				<tr>
					<td>$no</td>
					<td>$row->no_sipb</td>
					<td>$tgl_sipb_format</td> 		 				
					<td>$no_sl</td> 			 				
					<td>$tgl_sl_format</td> 		
					<td>$id_item</td> 			
					<td>$tipe_ahm</td> 			
					<td>$warna</td> 						
					<td>$row->jumlah</td> 			 				 				
					<td>$qty_terima</td> 			 				
					<td>$qty_sisa</td> 			 				 				
				</tr>
			";
		}
 		$no++;	 	
 	}
 	?>
</table>
