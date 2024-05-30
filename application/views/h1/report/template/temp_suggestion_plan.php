<?php 
$bln = sprintf("%'.02d",$bulan);
$no = $bln."-".$tahun;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=ReportSuggestionPlan_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
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
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center" rowspan="2">Tipe</td>
 		<td align="center" rowspan="2">Stock on Hand</td> 		 		
 		<td align="center" rowspan="2">Intarnsit</td>
 		<td align="center" colspan="3">Displan AHM</td> 		 		
 		<td align="center" colspan="2">Distribusi Dealer</td> 		 		 		
 	</tr>
 	<tr>
 		<td align="center">Jumlah</td> 		 		 		 		
 		<td align="center">%</td> 		 		 		 		
 		<td align="center">Unit</td> 		 		 		 		
 		<td align="center">%</td> 		 		 		 		
 		<td align="center">Total</td> 		 		 		 		
 	</tr>
 	<?php 
 	$no=1; 
 	$sql = $this->db->query("SELECT * FROM tr_suggestion_plan 
 		INNER JOIN tr_suggestion_plan_detail ON tr_suggestion_plan.id_suggestion_plan = tr_suggestion_plan_detail.id_suggestion_plan
 		WHERE tr_suggestion_plan.bulan = '$bulan' AND tr_suggestion_plan.tahun = '$tahun'");
 	foreach ($sql->result() as $isi) {
 		echo "
 		<tr>
 			<td>$isi->id_tipe_kendaraan</td>
 			<td>$isi->stock_md</td>
 			<td>$isi->intarnsit</td>
 			<td>$isi->ahm</td>
 			<td></td>
 			<td></td>
 			<td>$isi->md</td>
 			<td></td>
 		</tr>
 		";
 		$no++;
 	}
 	
 	?>
</table>
