<table id="myTable" class="table myTable1 order-list" border="0">
 	<tr> 		
 		<td align="center">Tgl Penerimaan</td>
 		<td align="center">Lokasi Awal Receiving</td>
 		<td align="center">Lokasi Setelah NRFS</td>
 		<td align="center">Lokasi Setelah RFS</td> 		
 	</tr>
 	<?php 
 	$sql = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin='$no_mesin'");		
 	$tgl_penerimaan = ($sql->num_rows()>0) ? $sql->row()->tgl_penerimaan : "" ;
 	$lokasi = ($sql->num_rows()>0) ? $sql->row()->lokasi : "" ;
 	$slot = ($sql->num_rows()>0) ? $sql->row()->slot : "" ;
 	$tipe = ($sql->num_rows()>0) ? $sql->row()->tipe : "" ;

 	if($tipe=='NRFS'){
 		$sql2 = $this->db->query("SELECT * FROM tr_log WHERE no_mesin='$no_mesin' AND status = 'NRFS' ORDER BY id_log DESC LIMIT 1,1");		
 		$keterangan = ($sql2->num_rows()>1) ? $sql2->row()->keterangan : "" ;
 	}else{
 		$sql2 = $this->db->query("SELECT * FROM tr_log WHERE no_mesin='$no_mesin' AND status = 'NRFS'");		
 		$keterangan = ($sql2->num_rows()>0) ? $sql2->row()->keterangan : "" ;
 	}

 	if($tipe=='RFS'){
	 	$sql3 = $this->db->query("SELECT * FROM tr_log WHERE no_mesin='$no_mesin' AND status = 'RFS' ORDER BY id_log DESC LIMIT 1,1");		
	 	$keterangan2 = ($sql3->num_rows()>1) ? $sql3->row()->keterangan : "" ;
	}else{
		$sql3 = $this->db->query("SELECT * FROM tr_log WHERE no_mesin='$no_mesin' AND status = 'RFS'");		
	 	$keterangan2 = ($sql3->num_rows()>0) ? $sql3->row()->keterangan : "" ;
	}
 	echo "
 		<tr>
 			<td align='center'>$tgl_penerimaan</td>
 			<td align='center'>$lokasi-$slot ($tipe)</td>
 			<td align='center'>$keterangan</td>
 			<td align='center'>$keterangan2</td>
 		</tr>
 	";
 	?>
</table>
