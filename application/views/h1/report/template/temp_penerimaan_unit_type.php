<?php 
$no = date("dmyhis");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=penerimaan_unit_type_".$no.".xls");
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
 		<td align="center">Ekspedisi</td>
 		<td align="center">No Polisi</td>
 		<td align="center">Kode Tipe</td>
 		<td align="center">Qty</td> 		
 	</tr>
 	<?php 
 	$no=1;
 	$sql = $this->db->query("SELECT *,tr_scan_barcode.no_mesin AS nosin, tr_shipping_list.tgl_sl, tr_sipb.tgl_sipb, COUNT(tr_scan_barcode.no_mesin) AS qty FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.no_shipping_list = tr_penerimaan_unit_detail.no_shipping_list
 		INNER JOIN tr_penerimaan_unit ON tr_penerimaan_unit_detail.id_penerimaan_unit = tr_penerimaan_unit.id_penerimaan_unit 		
 		LEFT JOIN tr_shipping_list ON tr_scan_barcode.no_shipping_list = tr_shipping_list.no_shipping_list
 		LEFT JOIN tr_sipb ON tr_shipping_list.no_sipb = tr_sipb.no_sipb
 		WHERE tr_penerimaan_unit.ekspedisi = '$ekspedisi' AND tr_penerimaan_unit.tgl_penerimaan BETWEEN '$tgl1' AND '$tgl2'
 		AND tr_scan_barcode.tipe_motor = '$id_tipe_kendaraan'");
 	foreach ($sql->result() as $row) {
 		$bulan = substr($row->tgl_sl, 2,2);
    $tahun = substr($row->tgl_sl, 4,4);
    $tgl = substr($row->tgl_sl, 0,2);
    $tanggal_sl = $tgl."-".$bulan."-".$tahun;

    $bulan_s = substr($row->tgl_sipb, 2,2);
    $tahun_s = substr($row->tgl_sipb, 4,4);
    $tgl_s = substr($row->tgl_sipb, 0,2);
    $tanggal_sipb = $tgl_s."-".$bulan_s."-".$tahun_s;

 		echo "
 			<tr>
 				<td>$no</td>
 				<td>$row->no_sipb</td>
 				<td>$tanggal_sipb</td>
 				<td>$row->no_shipping_list</td>
 				<td>$tanggal_sl</td>
 				<td>$row->ekspedisi</td>
 				<td>$row->no_polisi</td>
 				<td>$row->id_tipe_kendaraan</td>
 				<td>$row->qty</td> 				
 			</tr>
 		";
 		$no++;
 	}
 	?>
</table>


