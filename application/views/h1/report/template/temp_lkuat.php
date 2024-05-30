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
$no = date("dmyhis");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=LKUAT_komparasi_ekspedisi".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">Bulan</td>
 		<?php 
 		$sql = $this->db->query("SELECT * FROM ms_vendor WHERE id_vendor_type = 'EKS' AND active = 1 ORDER BY vendor_name ASC");
 		foreach ($sql->result() as $isi) {
 			echo "<td align='center'>$isi->vendor_name</td>";
 			echo "<td align='center'>NRFS(%)</td>";
 		}
 		?> 		
 	</tr>
 	<?php  	
 	for ($i=1; $i <= 12; $i++) {  
	  $bln = sprintf("%'.02d",$i);                                                                     	  
	  $bulan = bln($i);
		echo "
			<tr>				
				<td>$bulan</td>";				
		 		$sql = $this->db->query("SELECT * FROM ms_vendor WHERE id_vendor_type = 'EKS' AND active = 1 ORDER BY vendor_name ASC");
		 		foreach ($sql->result() as $isi) {
		 			$thn_bln = $tahun."-".$bln;
		 			$cek = $this->db->query("SELECT count(no_mesin) AS jum FROM tr_scan_barcode WHERE LEFT(tgl_penerimaan,7) = '$thn_bln' AND nama_ekspedisi = '$isi->id_vendor'")->row()->jum;
		 			$cek2 = $this->db->query("SELECT count(no_mesin) AS jum FROM tr_scan_barcode WHERE LEFT(tgl_penerimaan,7) = '$thn_bln' AND nama_ekspedisi = '$isi->id_vendor'
		 				AND tipe = 'NRFS'")->row()->jum;
		 			if($cek2 != 0){
		 				$persen = round(($cek2 / $cek) * 100,2);
		 			}else{
		 				$persen = 0;
		 			}
		 			echo "<td align='center'>$cek</td>";
		 			echo "<td align='center'>$persen %</td>";
		 		}		 		
		 		echo "				
			</tr>
		";		
		}
 	?>
</table>
