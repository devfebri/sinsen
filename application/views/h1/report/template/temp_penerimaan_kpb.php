<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=ReportPenerimaanKPB".$no.".xls");
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
 		<td align="center">Tgl Input</td>
 		<td align="center">Kode MD</td>
 		<td align="center">Kode AHASS</td>
 		<td align="center">No Mesin</td> 		
 		<td align="center">No KPB</td> 		
 		<td align="center">Tgl Beli</td> 		
 		<td align="center">Service Ke</td> 		
 		<td align="center">KM</td> 		
 		<td align="center">Tgl Service</td> 		
 	</tr>
 	<?php 
 	$no=1;
 	$where="";
 	if($status!="") $where = "AND tr_claim_kpb.status = '$status'";
 	$sql = $this->db->query("SELECT * FROM tr_claim_kpb INNER JOIN ms_dealer ON tr_claim_kpb.id_dealer = ms_dealer.id_dealer 
 		WHERE tr_claim_kpb.tgl_beli_smh BETWEEN '$tgl1' AND '$tgl2' $where");
 	foreach ($sql->result() as $isi) {
 		echo "
 		<tr>
 			<td>$no</td>
 			<td>$isi->tgl_beli_smh</td>
 			<td>E20</td>
 			<td>$isi->kode_dealer_md</td>
 			<td>$isi->no_mesin</td>
 			<td>$isi->no_kpb</td>
 			<td>$isi->tgl_beli_smh</td>
 			<td>$isi->kpb_ke</td>
 			<td>$isi->km_service</td>
 			<td>$isi->tgl_service</td>
 		</tr>
 		"; 	
 		$no++;
 	}
 	?>
</table>