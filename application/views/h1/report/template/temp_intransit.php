<?php 
$no = date("dmyhis");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=data_intransit_".$no.".xls");
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
 		<td align="center">Kode Item Kendaraan</td> 		
 		<td align="center">Nama Item Kendaraan</td> 		
 		<td align="center">No Mesin</td> 		
 		<td align="center">No Rangka</td> 		
 	</tr>
 	<?php 
 	$no=1;
 	$where = "";
 	if($id_vendor!=''){
		$where .= "AND tr_scan_barcode.nama_ekspedisi = '$id_vendor'";
	}	
	if($id_tipe_kendaraan!=''){
		$where .= "AND tr_scan_barcode.tipe_motor = '$id_tipe_kendaraan'";
	}
	if($id_warna!=''){
		$where .= "AND tr_scan_barcode.warna = '$id_warna'";
	} 	

	$tgl_a = substr($tgl1, 8,2);
	$tgl_b = substr($tgl1, 5,2);
	$tgl_c = substr($tgl1, 0,4);
  $tanggal_a = $tgl_a.$tgl_b.$tgl_c;

  $tgl_d = substr($tgl2, 8,2);
	$tgl_e = substr($tgl2, 5,2);
	$tgl_f = substr($tgl2, 0,4);
  $tanggal_b = $tgl_d.$tgl_e.$tgl_f;
 	$sql = $this->db->query("SELECT tr_shipping_list.no_sipb,tr_shipping_list.no_shipping_list,
 		tr_shipping_list.tgl_sl, ms_vendor.vendor_name as nama_eks,tr_shipping_list.no_pol_eks,tr_shipping_list.id_modell,tr_shipping_list.id_warna,
 		tr_shipping_list.no_mesin,tr_shipping_list.no_rangka,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna
 		FROM tr_shipping_list  		
 		LEFT JOIN ms_tipe_kendaraan ON tr_shipping_list.id_modell = ms_tipe_kendaraan.id_tipe_kendaraan
 		LEFT JOIN ms_warna ON tr_shipping_list.id_warna = ms_warna.id_warna
		left join ms_unit_transporter on replace(tr_shipping_list.no_pol_eks,' ','') = replace(ms_unit_transporter.no_polisi,' ','') and ms_unit_transporter.active = 1
 		left join ms_vendor on ms_unit_transporter.id_vendor = ms_vendor.id_vendor 
 		WHERE tr_shipping_list.tgl_sl BETWEEN '$tanggal_a' AND '$tanggal_b'
 		AND tr_shipping_list.no_mesin NOT IN (SELECT no_mesin FROM tr_scan_barcode)
 		AND tr_shipping_list.no_mesin <> 'JBK1E1674784'
 		$where
 		ORDER BY tr_shipping_list.tgl_sl ASC"); 	 	
 	foreach ($sql->result() as $row) { 		 		 		
 		$row2 = $this->m_admin->getByID("tr_sipb","no_sipb",$row->no_sipb);
 		$tgl_sipb = ($row2->num_rows() > 0) ? $row2->row()->tgl_sipb:""; 		 		

 		$bulan1 = substr($tgl_sipb, 2,2);
    $tahun1 = substr($tgl_sipb, 4,4);
    $tgl1 = substr($tgl_sipb, 0,2);
    $tanggal2 = $tgl1."/".$bulan1."/".$tahun1;

    $bulan2 = substr($row->tgl_sl, 2,2);
    $tahun2 = substr($row->tgl_sl, 4,4);
    $tgl2 = substr($row->tgl_sl, 0,2);
    $tanggal3 = $tgl2."/".$bulan2."/".$tahun2;

 		echo "
 			<tr>
 				<td>$no</td>
 				<td>$row->no_sipb</td>
 				<td>$tanggal2</td>
 				<td>$row->no_shipping_list</td>
 				<td>$tanggal3</td>
 				<td>$row->nama_eks</td> 			
 				<td>$row->no_pol_eks</td> 			
 				<td>$row->id_modell - $row->id_warna</td> 			
 				<td>$row->tipe_ahm - $row->warna</td> 			
 				<td>$row->no_mesin</td> 			
 				<td>$row->no_rangka</td> 			
 			</tr>
 		";
 		$no++;	 	
 	}
 	?>
</table>
