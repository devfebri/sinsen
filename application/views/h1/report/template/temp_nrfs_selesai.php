<?php 
function bln($a){
  $bulan=$bl=$month=$a;
  switch($bulan)
  {
    case"1":$bulan="Januari"; break;
    case"2":$bulan="Februari"; break;
    case"3":$bulan="Maret"; break;
    case"4":$bulan="April"; break;
    case"5":$bulan="Mei"; break;
    case"6":$bulan="Juni"; break;
    case"7":$bulan="Juli"; break;
    case"8":$bulan="Agustus"; break;
    case"9":$bulan="September"; break;
    case"10":$bulan="Oktober"; break;
    case"11":$bulan="November"; break;
    case"12":$bulan="Desember"; break;
  }
  $bln = $bulan;
  return $bln;
}
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Nrfs_selesai_repair_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">No</td>
 		<td align="center">Ekspedisi</td> 		 		
 		<td align="center">No Polisi</td>
 		<td align="center">No Penerimaan</td>
 		<td align="center">Tgl Penerimaan</td> 		 		
 		<td align="center">Kode Item</td> 		 		
 		<td align="center">No Mesin</td> 		 		
 		<td align="center">No Rangka</td> 		 		
 		<td align="center">Part Masalah</td> 		 		
 		<td align="center">Tgl Selesai Repair</td> 		 		
 	</tr>
 	<?php  	
 	$no=1;
 	$sql = $this->db->query("SELECT *,LEFT(tr_wo.updated_at,10) AS tgl_update FROM tr_wo 
 		INNER JOIN tr_checker_detail ON tr_wo.id_checker = tr_checker_detail.id_checker
 		LEFT JOIN ms_part ON tr_checker_detail.id_part = ms_part.id_part
 		INNER JOIN tr_checker ON tr_checker_detail.id_checker = tr_checker.id_checker
 		LEFT JOIN ms_vendor ON tr_checker.ekspedisi = ms_vendor.id_vendor 		
 		WHERE LEFT(tr_wo.updated_at,10) BETWEEN '$tgl1' AND '$tgl2' AND tr_wo.status_wo = 'closed'");
 	foreach ($sql->result() as $isi) {
 		$row2 = $this->db->query("SELECT tr_penerimaan_unit.*,tr_scan_barcode.no_rangka FROM tr_scan_barcode 
 			INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.no_shipping_list = tr_penerimaan_unit_detail.no_shipping_list 
 			INNER JOIN tr_penerimaan_unit ON tr_penerimaan_unit_detail.id_penerimaan_unit = tr_penerimaan_unit.id_penerimaan_unit
 			WHERE tr_scan_barcode.no_mesin = '$isi->no_mesin'");
 		$tgl_penerimaan = ($row2->num_rows() > 0) ? $row2->row()->tgl_penerimaan:""; 		
 		$no_penerimaan = ($row2->num_rows() > 0) ? $row2->row()->id_penerimaan_unit:""; 		
 		$no_rangka = ($row2->num_rows() > 0) ? $row2->row()->no_rangka:""; 		
 		echo "
 		<tr>
 			<td>$no</td>
 			<td>$isi->vendor_name</td> 			
 			<td>$isi->no_polisi</td> 			
 			<td>$no_penerimaan</td> 			
 			<td>$tgl_penerimaan</td> 			
 			<td>$isi->no_polisi</td> 			
 			<td>$isi->no_mesin</td> 			
 			<td>$no_rangka</td> 			
 			<td>$isi->id_part</td> 			
 			<td>$isi->tgl_update</td> 			
 		</tr>
 		";
 		$no++; 	
 	}
 	?>
</table>
