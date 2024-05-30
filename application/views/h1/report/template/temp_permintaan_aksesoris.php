<?php 
$no = $tgl1."-".$tgl2;
// header("Content-type: application/octet-stream");
// header("Content-Disposition: attachment; filename=Monitoring_stnk_".$no.".xls");
// header("Pragma: no-cache");
// header("Expires: 0");
function mata_uang($a){
	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	return number_format($a, 0, ',', '.');
}
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">Kode Part Bundling</td>
 		<td align="center">Nama Part Bundling</td> 		 		
 		<td align="center">Qty Pesan</td>
 		<td align="center">Qty Pemenuhan</td> 		 		
 		<td align="center">Dealer</td> 		 		 		
 		<td align="center">No Mesin</td> 		 		 		
 		<td align="center">No Rangka</td> 		 		 		
 		<td align="center">No DO</td> 		 		 		 		
 		<td align="center">Tgl DO</td> 		 		 		 		
 		<td align="center">No Faktur</td> 		 		 		 		
 		<td align="center">No Surat Jalan</td> 		 		 		 		
 		<td align="center">Tgl Surat Jalan</td> 		 		 		 		
 	</tr>
 	<?php  	
 	$no=1;
 	$sql = $this->db->query("SELECT * FROM tr_po_aksesoris INNER JOIN tr_po_aksesoris_detail ON tr_po_aksesoris.no_po_aksesoris = tr_po_aksesoris_detail.no_po_aksesoris
 		LEFT JOIN ms_part ON tr_po_aksesoris_detail.id_part = ms_part.id_part
 		WHERE tr_po_aksesoris.tgl_po BETWEEN '$tgl1' AND '$tgl2'");
 	// $sql = $this->db->query("SELECT * FROM tr_po_checker INNER JOIN tr_po_checker_detail ON tr_po_checker.no_po = tr_po_checker_detail.no_po
 	//  	LEFT JOIN ms_part ON tr_po_checker_detail.id_part = ms_part.id_part
 	//  	WHERE tr_po_checker.tgl_po BETWEEN '$tgl1' AND '$tgl2'");
	foreach ($sql->result() as $isi) {
		echo "
		<tr>
			<td>$isi->id_part</td>
			<td>$isi->nama_part</td>
			<td>$isi->qty</td>
			<td>$isi->pemenuhan</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		";
	}	
 		$no++; 	
 	
 	?>
</table>
