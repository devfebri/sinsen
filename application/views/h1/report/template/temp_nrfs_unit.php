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
header("Content-Disposition: attachment; filename=NRFS_per_Part".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">No</td>
 		<td align="center">Nama Ekspedisi</td>
 		<td align="center">Part Masalah</td>
 		<td align="center">%</td> 		 		
 	</tr>
 	<?php  	
 	$no=1;$tot=0;
 	$sql = $this->db->query("SELECT * FROM tr_checker  		
 		LEFT JOIN ms_vendor ON tr_checker.ekspedisi = ms_vendor.id_vendor
        INNER JOIN tr_checker_detail ON tr_checker.id_checker = tr_checker_detail.id_checker 
        INNER JOIN ms_part ON tr_checker_detail.id_part = ms_part.id_part
 		WHERE tr_checker.tgl_checker BETWEEN '$tgl1' AND '$tgl2'
    GROUP BY tr_checker_detail.id_part ORDER BY ms_vendor.vendor_name ASC");
 	foreach ($sql->result() as $isi) {
 		echo "
 		<tr>
 			<td>$no</td>
 			<td>$isi->vendor_name</td>
 			<td>$isi->nama_part</td>";
 			$sq = $this->db->query("SELECT count(tr_checker.id_checker) AS jum FROM tr_checker_detail 
                INNER JOIN tr_checker ON tr_checker_detail.id_checker = tr_checker.id_checker
                LEFT JOIN ms_part ON tr_checker_detail.id_part = ms_part.id_part AND tr_checker.ekspedisi = '$isi->ekspedisi'
 			    WHERE tr_checker_detail.id_part = '$isi->id_part' AND tr_checker.tgl_checker BETWEEN '$tgl1' AND '$tgl2'"); 			 			
            $jumlah = ($sq->num_rows() > 0) ? $sq->row()->jum : 0 ;
 			$sr = $this->db->query("SELECT count(tr_checker.id_checker) AS jum FROM tr_checker_detail 
                INNER JOIN tr_checker ON tr_checker_detail.id_checker = tr_checker.id_checker
                LEFT JOIN ms_part ON tr_checker_detail.id_part = ms_part.id_part WHERE tr_checker.ekspedisi = '$isi->ekspedisi'
                AND tr_checker.tgl_checker BETWEEN '$tgl1' AND '$tgl2'");       
 			$hasil = ($sr->num_rows() > 0) ? $sr->row()->jum : 0 ;
 			$persen = @($jumlah/$hasil)*100;
 			echo"
 			<td>$persen</td>
 		</tr>
 		";
 		$tot += $persen;
 		$no++; 	
 	}
 	echo $tot;
 	?>
</table>
