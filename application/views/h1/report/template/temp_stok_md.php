<?php 
//$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=StokMD.xls");
header("Pragma: no-cache");
header("Expires: 0");
function mata_uang($a){
	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	return number_format($a, 0, ',', '.');
} 
?>
<table border="1">  
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">No Mesin</td>
 		<td align="center">No Rangka</td>
 		<td align="center">Tipe Motor</td>
 		<td align="center">Kode Item</td>
 		<td align="center">Deskripsi Type</td> 	
 		<td align="center">Tahun</td> 		
 		<td align="center">Status Lokasi</td> 		
 		<td align="center">Lokasi</td> 		
 		<td align="center">Status</td> 		 		
 	</tr>
 	<?php 
 	$no=1; 
 	$sql = $this->db->query("
		SELECT tr_scan_barcode.no_mesin AS nosin, tr_scan_barcode.no_rangka, ms_tipe_kendaraan.tipe_ahm, tr_scan_barcode.id_item, ms_tipe_kendaraan.deskripsi_ahm, tr_scan_barcode.lokasi, tr_scan_barcode.slot, tr_scan_barcode.tipe AS statuss, tr_fkb.tahun_produksi
		FROM tr_scan_barcode 
		INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
		join tr_fkb on tr_fkb.no_mesin_spasi = tr_scan_barcode.no_mesin
 		WHERE tr_scan_barcode.status = 1

	");
 	foreach ($sql->result() as $isi) { 		
 		echo "
 		<tr>
 			<td>$no</td>
 			<td>$isi->nosin</td>
 			<td>$isi->no_rangka</td>
 			<td>$isi->tipe_ahm</td>
 			<td>$isi->id_item</td>
 			<td>$isi->deskripsi_ahm</td>
 			<td>$isi->tahun_produksi</td>
 			<td>$isi->lokasi</td>
 			<td>$isi->slot</td>
 			<td>$isi->statuss</td> 			
 		</tr>
 		";
 		$no++;
 	}
 	?>
</table>