<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=PolregSamsat_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">Kode Item</td>
 		<?php 
 		$sql = $this->m_admin->getByID("ms_kabupaten","id_provinsi","1500");
 		foreach ($sql->result() as $isi) {
 			echo "<td align='center'>$isi->kabupaten</td>";
 		}
 		?>
 		<td align="center">Grand Total</td> 		
 	</tr>
 	<?php 
 	$no=1; 	
 	$id_user = $this->session->userdata("id_user");
 	$sql3 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.id_item) FROM tr_pengajuan_bbn_detail
 		LEFT JOIN tr_scan_barcode ON tr_pengajuan_bbn_detail.no_mesin = tr_scan_barcode.no_mesin 		
 		LEFT JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd
 		LEFT JOIN ms_dealer ON tr_pengajuan_bbn.id_dealer = ms_dealer.id_dealer
 		INNER JOIN tr_lap_polreg ON tr_scan_barcode.tipe_motor = tr_lap_polreg.id_tipe_kendaraan
 		WHERE tr_pengajuan_bbn_detail.tgl_mohon_samsat BETWEEN '$tgl1' AND '$tgl2' AND tr_lap_polreg.created_by = '$id_user'"); 	 	
 	foreach ($sql3->result() as $row) { 		 		 	 		
 		echo "
 			<tr>
 				<td>$no</td>
 				<td>$row->id_item</td>";
 				$g = 0;
 				$sql2 = $this->m_admin->getByID("ms_kabupaten","id_provinsi","1500");
		 		foreach ($sql2->result() as $isi) {
		 			$sql5 = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_pengajuan_bbn_detail
				 		LEFT JOIN tr_scan_barcode ON tr_pengajuan_bbn_detail.no_mesin = tr_scan_barcode.no_mesin 		
				 		LEFT JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd				 		
				 		INNER JOIN ms_kelurahan ON tr_pengajuan_bbn_detail.id_kelurahan = ms_kelurahan.id_kelurahan
				 		INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
				 		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
				 		INNER JOIN tr_lap_polreg ON tr_scan_barcode.tipe_motor = tr_lap_polreg.id_tipe_kendaraan
				 		WHERE tr_pengajuan_bbn_detail.tgl_mohon_samsat BETWEEN '$tgl1' AND '$tgl2' 
				 		AND ms_kabupaten.id_kabupaten = '$isi->id_kabupaten' AND tr_scan_barcode.id_item = '$row->id_item'
				 		AND tr_lap_polreg.created_by = '$id_user'")->row(); 	 	
		 			echo "<td align='center'>$sql5->jum</td>";
		 			$g += $sql5->jum;
		 		}
		 		echo "
 				<td align='center'>$g</td> 			 				
 			</tr>
 		";
 		$no++;	 	
 	}
 	?>
 	<tr>
 		<td colspan="2">Grand Total</td>
 		<?php 
 		$gt = 0;
 		$sql = $this->m_admin->getByID("ms_kabupaten","id_provinsi","1500");
 		foreach ($sql->result() as $isi) {
 			$sql6 = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_pengajuan_bbn_detail
		 		LEFT JOIN tr_scan_barcode ON tr_pengajuan_bbn_detail.no_mesin = tr_scan_barcode.no_mesin 		
		 		LEFT JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd				 		
		 		INNER JOIN ms_kelurahan ON tr_pengajuan_bbn_detail.id_kelurahan = ms_kelurahan.id_kelurahan
		 		INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
		 		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
		 		INNER JOIN tr_lap_polreg ON tr_scan_barcode.tipe_motor = tr_lap_polreg.id_tipe_kendaraan
		 		WHERE tr_pengajuan_bbn_detail.tgl_mohon_samsat BETWEEN '$tgl1' AND '$tgl2' 
		 		AND ms_kabupaten.id_kabupaten = '$isi->id_kabupaten'  AND tr_lap_polreg.created_by = '$id_user'")->row(); 	 	
 			echo "<td align='center'>$sql6->jum</td>";
 			$gt += $sql6->jum;
 		}
 		?>
 		<td align="center"><?php echo $gt ?></td>
 	</tr>
</table>
