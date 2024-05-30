<?php 
//$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=UnfilledDealer.xls");
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
 		<td align="center">No DO</td>
 		<td align="center">Tgl DO</td>
 		<td align="center">Nama Dealer</td>
 		<td align="center">Kode Item</td> 		
 		<td align="center">Deskripsi Tipe</td> 		
 		<td align="center">No Mesin</td> 		
 		<td align="center">No Rangka</td> 		
 		<td align="center">Lama Motor di Gudang</td> 		
 		<td align="center">Keterangan</td> 		
 		<td align="center">Lokasi</td> 		
 	</tr>
 	<?php 
 	$no=1;
 	// $sql = $this->db->query("SELECT * FROM tr_surat_jalan 
 	// 	INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
 	// 	LEFT JOIN ms_dealer ON tr_surat_jalan.id_dealer = ms_dealer.id_dealer
 	// 	LEFT JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
 	// 	LEFT JOIN tr_picking_list ON tr_surat_jalan.no_picking_list = tr_picking_list.no_picking_list
 	// 	LEFT JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
 	// 	LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
 	// 	WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer)");
 	$sql = $this->db->query("SELECT *,tr_picking_list_view.no_mesin AS nosin FROM tr_picking_list
 	 	INNER JOIN tr_picking_list_view ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list 		
 		LEFT JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
 		LEFT JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
 		LEFT JOIN tr_scan_barcode ON tr_picking_list_view.no_mesin = tr_scan_barcode.no_mesin 		
 		LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
 		WHERE tr_picking_list_view.no_mesin NOT IN (SELECT no_mesin FROM tr_surat_jalan_detail WHERE retur = 0 AND ceklist = 'ya')");
 	foreach ($sql->result() as $isi) {
 		$skrg = date("Y-m-d");
 		$tgl1 = strtotime($skrg);
 		$tgl2 = strtotime($isi->tgl_do); 		
    $diff = $tgl1 - $tgl2; 
    $us = floor($diff / (60 * 60 * 24));            
 		echo "
 		<tr>
 			<td>$no</td>
 			<td>$isi->no_do</td>
 			<td>$isi->tgl_do</td>
 			<td>$isi->nama_dealer</td>
 			<td>$isi->id_item</td>
 			<td>$isi->tipe_ahm</td>
 			<td>$isi->nosin</td>
 			<td>$isi->no_rangka</td>
 			<td>$us</td>
 			<td>$isi->ket</td>
 			<td>$isi->lokasi-$isi->slot</td>
 		</tr>
 		";
 		$no++;
 	}
 	?>
</table>