<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Rekap_surat_jalan_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">No</td>
 		<td align="center">No Surat Jalan</td>
 		<td align="center">Tgl Surat Jalan</td> 		 		
 		<td align="center">Kode Dealer</td>
 		<td align="center">Nama Dealer</td> 		 		 		
 		<td align="center">Kode Tipe Kendaraan</td> 		 		 		
 		<td align="center">Tipe Kendaraan</td> 		 		 		
 		<td align="center">Warna</td> 		 		 		
 		<td align="center">Qty Surat Jalan</td> 
 		<td align="center">No Mesin</td> 		
 		<td align="center">Tgl Penerimaan Dealer</td> 			 		
 		<td align="center">Keterangan</td> 	 		 		
 	</tr>
 	<?php  	
 	$no=1;
 	$sql = $this->db->query("SELECT tr_surat_jalan.no_surat_jalan,tr_surat_jalan.tgl_surat,ms_dealer.kode_dealer_md,ms_dealer.nama_dealer,
 		tr_surat_jalan.ket,ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,count(tr_surat_jalan_detail.no_mesin) as jum , tr_surat_jalan_detail.no_mesin, tpud.id_penerimaan_unit_dealer, tpud.tgl_penerimaan 
 		FROM tr_surat_jalan_detail 
 		INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
 		left join tr_penerimaan_unit_dealer tpud on tpud.no_surat_jalan = tr_surat_jalan.no_surat_jalan 
 		left join tr_penerimaan_unit_dealer_detail tpudd on tpudd.id_penerimaan_unit_dealer  = tpud.id_penerimaan_unit_dealer and tr_surat_jalan_detail.no_mesin = tpudd.no_mesin 	
		LEFT JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
 		LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
 		LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
 		LEFT JOIN ms_dealer ON tr_surat_jalan.id_dealer = ms_dealer.id_dealer
 		WHERE tr_surat_jalan.tgl_surat BETWEEN '$tgl1' AND '$tgl2' GROUP BY tr_surat_jalan.no_surat_jalan, tr_surat_jalan_detail.id_item");
 	foreach ($sql->result() as $isi) { 		
 		// $row2 = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$isi->no_mesin'");
 		// $no_rangka = ($row2->num_rows() > 0) ? $row2->row()->no_rangka:""; 		
 		echo "
 		<tr>
 			<td>$no</td>
 			<td>$isi->no_surat_jalan</td> 			 			 			
 			<td>$isi->tgl_surat</td> 			 			 			
 			<td>$isi->kode_dealer_md</td> 			 			 			
 			<td>$isi->nama_dealer</td> 		 			 			
 			<td>$isi->id_tipe_kendaraan</td> 			 			 			
 			<td>$isi->tipe_ahm</td> 			 			 			
 			<td>$isi->warna</td> 			 			 			
 			<td>$isi->jum</td> 			 			 			
 			<td>$isi->no_mesin</td> 		 			 			
 			<td>$isi->tgl_penerimaan</td> 			 			 			
 			<td>$isi->ket</td> 				 			 			
 		</tr>
 		";
 		$no++; 	
 	}
 	?>
</table>
