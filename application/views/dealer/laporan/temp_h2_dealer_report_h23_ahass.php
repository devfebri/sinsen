<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Reporting H23 Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
	<caption>Reporting H23 Dealer <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</caption>
 	<tr> 		
 		<td align="center" rowspan="2"><b>No</b></td>
 		<td align="center" style="width:380px" rowspan="2"><b>AHASS</b></td>
 		<td align="center" rowspan="2"><b>Total UE per Type Motor</b></td>
 		<td align="center" colspan="10"><b>Jenis Pekerjaan</b></td>
		<td align="center" rowspan="2"><b>Total ToJ per Type Motor</b></td>
 	</tr>
	 <tr> 		
 		<td align="center"><b>KPB 1</b></td>
 		<td align="center"><b>KPB 2</b></td>
 		<td align="center"><b>KPB 3</b></td>
 		<td align="center"><b>KPB 4</b></td>
		<td align="center"><b>Paket Lengkap</b></td>
		<td align="center"><b>Paket Ringan</b></td>
 		<td align="center"><b>Light Repair</b></td>
 		<td align="center"><b>Heavy Repair</b></td>
 		<td align="center"><b>Ganti Oli</b></td>
		<td align="center"><b>Claim</b></td>
 	</tr>

<?php 
 	$nom=1;	
	if($all_ahass->num_rows()>0){
		foreach ($all_ahass->result() as $key => $row) { 
			$data_ue2 = isset($data_ue[$key]) ? $data_ue[$key] : (object) array('ue' => '');

			$total_job = $row->total_ass1+$row->total_ass2+$row->total_ass3+$row->total_ass4+$row->total_cs+$row->total_ls+$row->total_lr+$row->total_hr+$row->total_or+$row->total_claim;
		
 		echo "
 			<tr>
 				<td>$nom</td>
 				<td> $row->nama_dealer</td>
				<td><b>$data_ue2->ue</b></td>
 				<td>$row->total_ass1</td>
 				<td>$row->total_ass2</td>
 				<td>$row->total_ass3</td>
				<td>$row->total_ass4</td>
 				<td>$row->total_cs</td>
				<td>$row->total_ls</td>
 				<td>$row->total_lr</td>
				<td>$row->total_hr</td>
				<td>$row->total_or</td>
				<td>$row->total_claim</td>
				<td><b>$total_job</b></td>

 			</tr>
	 	";
 		$nom++;
 		}
	}else{
		echo "<td colspan='14' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>


