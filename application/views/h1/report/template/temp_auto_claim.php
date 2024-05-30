<?php 

// $no = $start_periode."-".$end_periode;
$no = NULL;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Autoclaim - Prospek_followup" .$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

 <table border="1">  
 	<tr> 		 		
		<td align="center"  rowspan="6">No</td> 
		<td align="center"  rowspan="6">Dealer</td> 
		<td align="center"  rowspan="6">Kode Dealer</td> 
		<td align="center"  rowspan="1"  colspan="10">Sales Program</td> 
 	</tr>
	 <tr> 		 		
		<td align="center"  rowspan="4">SSU</td> 
		<td align="center"  rowspan="4">Total Nilai Klaim Dealer kepada MD based on klaim yg di approve</td> 
		<td align="center"  rowspan="4">Total Unit Klaim D yang di Approve</td> 
		<td align="center" ></td> 
		<td align="center"  colspan="6">Kontribusi Credit (inc PPN)</td> 	
		<td align="center"  colspan="6"></td> 	
		<td align="center"  colspan="6">Kontribusi Cash (inc PPN) :</td> 	
		<td align="center"  colspan="6"></td> 	
		<td></td>
 	</tr>
	<tr>
		<td align="center"  rowspan="3">SSU</td> 

		<td align="center"  >AHM :</td> 
		<td align="center"   colspan="2" >RP. 001 </td> 
		<td align="center"   rowspan="2"   colspan="2" >AHM+MD : </td> 
		<td align="center"   rowspan="2" >RP. 099 </td> 
		<td align="center"   rowspan="3" colspan="6">Syarat yang di Reject :</td> 
		<td align="center"   rowspan="3"> SSU</td>
		<!-- cash -->
		<td>AHM</td>
		<td  colspan="2">RP. 1111</td>
		<td  rowspan="2" colspan="2"  >AHM+MD :</td>
		<td  rowspan="2">Hasil 1</td>
		<td  rowspan="3" colspan="6" >	Syarat yang di Reject :	</td>
		<tr>
			<td>MD :</td>
			<td align="center"  colspan="2"  >RP. 002 </td> 
			<td>MD :</td>
			<td  colspan="2">RP. 2222</td>
		</tr>
		<tr>
				<!-- ddealer Kredit -->
			<td>D :</td>
			<td align="center"   colspan="2"  >RP. 003 </td> 
			<td align="center"   colspan="2" >AHM+MD+D :</td> 
			<td align="center"   >RP. 088 </td> 
			<!-- ddealer Cash -->
			<td>D :</td>
			<td  colspan="2">RP. 333</td>
			<td  colspan="2">AHM+MD+D :</td>
			<td  >Hasil 2</td>

		</tr>
		<td align="center"  rowspan="1">Total</td> 
		<td align="center"  colspan="2">ID Sales Program : <?= $sales_program?></td> 
		<td align="center"  rowspan="1">Credit</td> 
		<td align="center"  rowspan="1">Claim by Dealer</td> 
		<td align="center"  rowspan="1">Approved by AHM</td> 
		<td align="center"  rowspan="1">Approved to Dlr</td> 
		<td align="center"  rowspan="1">Reject to Dlr</td>
		<td align="center"  rowspan="1">Reason for Reject / selisih klaim dg penj</td> 
		<td align="center"  rowspan="1">Nilai Klaim yang di Approve to Dlr utk Penj Cr</td> 
		<td>INVOICE</td>
		<td>BASTK</td>
		<td>PO</td>
		<td>KUITANSI</td>
		<td></td>
		<td></td>
		<td>Cash</td>
		<td>Claim by Dealer</td>
		<td>Approved by AHM</td>
		<td>Approved to Dlr</td>
		<td>Reject to Dlr</td>
		<td>Reason for Reject / selisih klaim dg penj</td>
		<td>Nilai Klaim yang di Approve to Dlr utk Penj Cs</td>
		<td>INVOICE</td>
		<td>BASTK</td>
		<td>KUITANSI</td>
		<td></td>
		<td></td>
		<td></td>
	</tr>

	<?php 	foreach ($auto_claim as $field) {?>
	<tr>
		<td></td>
		<td><?= $field->nama_dealer?></td>
		<td><?= $field->kode_dealer_ahm?></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td><?= $field->status_approved?></td>
		<td><?= $field->status_approved?></td>
		<td><?= $field->status_approved?></td>
	</tr>
	<?php } ?>

</table>
