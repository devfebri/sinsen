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
 		<td align="center">Kode Item</td>		
 		<td align="center">Deskripsi</td> 		
 		<td align="center">Warna</td> 	
 		<td align="center">Qty SIPB</td> 		 		
 	</tr>
 	<?php 
 	$no=1; 	
 	$sql = $this->db->query("SELECT no_sipb, tgl_sipb, jumlah,id_tipe_kendaraan, id_warna FROM tr_sipb WHERE concat(right(tgl_sipb,4),'-',mid(tgl_sipb,3,2),'-',left(tgl_sipb,2)) BETWEEN '$tgl1' AND '$tgl2'"); 	 	
 	foreach ($sql->result() as $row) { 		 		 		 		
 		$row3 = $this->db->query("SELECT ms_tipe_kendaraan.tipe_ahm, ms_item.id_item, ms_warna.warna 
			FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
 			INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna 
 			WHERE ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND ms_item.id_warna = '$row->id_warna'");
 		$tipe_ahm = ($row3->num_rows() > 0) ? $row3->row()->tipe_ahm:""; 		 		
 		$warna = ($row3->num_rows() > 0) ? $row3->row()->warna:""; 		 		
 		$id_item = ($row3->num_rows() > 0) ? $row3->row()->id_item:""; 

		$tgl_sipb_format = substr($row->tgl_sipb,0,2).'-'.substr($row->tgl_sipb,2,2).'-'.substr($row->tgl_sipb,4,4);

		echo "
			<tr>
				<td>$no</td>
				<td>$row->no_sipb</td>
				<td>$tgl_sipb_format</td> 			
				<td>$id_item</td> 			
				<td>$tipe_ahm</td> 			
				<td>$warna</td> 						
				<td>$row->jumlah</td> 			 				 				
			</tr>
		";
		
 		$no++;	 	
 	}
 	?>
</table>
