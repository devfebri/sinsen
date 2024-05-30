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
header("Content-Disposition: attachment; filename=Order_part_nrfs".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">No</td>
 		<td align="center">Kode Part</td>
 		<td align="center">Part</td>
 		<td align="center">Qty</td> 		 		
 		<td align="center">Ekspedisi</td> 		 		
 		<td align="center">No. SPG</td> 		 		
 		<td align="center">Nama Ekspedisi</td> 		 		
 	</tr>
 	<?php  	
 	$no=1;
 	$sql = $this->db->query("SELECT tr_checker.ekspedisi, ms_vendor.vendor_name, ms_part.nama_part,tr_checker_detail.*,SUM(tr_checker_detail.qty_order) AS jum FROM tr_wo 
 		INNER JOIN tr_checker_detail ON tr_wo.id_checker = tr_checker_detail.id_checker
 		LEFT JOIN ms_part ON tr_checker_detail.id_part = ms_part.id_part
 		INNER JOIN tr_checker ON tr_checker_detail.id_checker = tr_checker.id_checker
 		LEFT JOIN ms_vendor ON tr_checker.ekspedisi = ms_vendor.id_vendor 		
 		WHERE tr_wo.tgl_wo BETWEEN '$tgl1' AND '$tgl2' AND tr_wo.status_wo <> 'closed' GROUP BY tr_checker_detail.id_part");
 	foreach ($sql->result() as $isi) {
 		echo "
 		<tr>
 			<td>$no</td>
 			<td>$isi->id_part</td> 			
 			<td>$isi->nama_part</td> 			
 			<td>$isi->jum</td> 			
 			<td>$isi->ekspedisi</td> 			
 			<td>-</td> 			
 			<td>$isi->vendor_name</td> 			
 		</tr>
 		";
 		$no++; 	
 	}
 	?>
</table>
