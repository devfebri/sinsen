<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Reporting H23  Data Analisa ToJ 1 Banding 1 Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>


<h4><b>Reporting H23 Data Analisa ToJ 1 banding 1 Dealer <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>


<table border="1">  
 	<tr> 		
 		<td align="center" rowspan="2"><b>No</b></td>
 		<td align="center" rowspan="2"><b>AHASS</b></td>
 		<td align="center" colspan="12"><b>Jenis Pekerjaan/ToJ</b></td>
 		<td align="center" rowspan="2"><b>Total ToJ</b></td>
		<td align="center" rowspan="2"><b>Total UE</b></td>
 	</tr>

	 <tr>
        <td align="center"><b>KPB 1-4</b></td> 		
 		<td align="center"><b>KPB 1</b></td>
 		<td align="center"><b>KPB 2</b></td>
 		<td align="center"><b>KPB 3</b></td>
 		<td align="center"><b>KPB 4</b></td>
        <td align="center"><b>Claim C2</b></td>
		<td align="center"><b>Paket Lengkap</b></td>
		<td align="center"><b>Paket Ringan</b></td>
 		<td align="center"><b>Light Repair</b></td>
 		<td align="center"><b>Heavy Repair</b></td>
        <td align="center"><b>Job Return</b></td>
 		<td align="center"><b>Ganti Oli Plus</b></td>
 	</tr>

     <?php
     $nom=0;
     $sum_entri=0;
	$sum_ass1=0;
	$sum_ass2=0;
	$sum_ass3=0;
	$sum_ass4=0;
	$sum_cs=0;
	$sum_ls=0;
	$sum_lr=0;
	$sum_hr=0;
	$sum_or=0;
	$sum_claim=0;
	$sum_job=0;
    $sum_jr=0;
	$sum_all=0;
 	$nom=1;	
     if($toj11->num_rows()>0){
		foreach ($toj11->result() as $row) { 
			// $data_ue2 = isset($data_ue[$key]) ? $data_ue[$key] : (object) array('ue' => '');

			$total_job = $row->total_ass1+$row->total_ass2+$row->total_ass3+$row->total_ass4+$row->total_cs+$row->total_ls+$row->total_lr+$row->total_hr+$row->total_or+$row->total_claim;

			$total_entri2 = $total_job;
			$total_ass1_2 = $row->total_ass1;
			$total_ass2_2 = $row->total_ass2;
			$total_ass3_2 = $row->total_ass3;
			$total_ass4_2 = $row->total_ass4;
			$total_cs_2   = $row->total_cs;
			$total_ls_2   = $row->total_ls;
			$total_lr_2   = $row->total_lr;
			$total_hr_2   = $row->total_hr;
			$total_or_2   = $row->total_or;
			$total_claim_2= $row->total_claim;
            $total_jr_2   = $row->total_jr;
            $total_job_2 = $total_job;
            $total_all_ass = $row->total_ass1+$row->total_ass2 +$row->total_ass3 +$row->total_ass4;

 		echo "
 			<tr>
 				<td>$nom</td>
 				<td>$row->nama_dealer</td>
                <td>$total_all_ass</td>
                <td>$row->total_ass1</td>
 				<td>$row->total_ass2</td>
 				<td>$row->total_ass3</td>
				<td>$row->total_ass4</td>
				<td>$row->total_claim</td>
                <td>$row->total_cs</td>
                <td>$row->total_ls</td>
                <td>$row->total_lr</td>
				<td>$row->total_hr</td>
 				<td>$row->total_jr</td>
 				<td>$row->total_or</td>	
				<td><b>$total_job</b></td>
                <td><b>$total_job</b></td>
 			</tr>
	 	";

 		 $nom++;
		 $sum_entri+= $total_entri2;	
		 $sum_ass1 += $total_ass1_2;	
		 $sum_ass2 += $total_ass2_2;	
		 $sum_ass3 += $total_ass3_2;	
		 $sum_ass4 += $total_ass4_2;	
		 $sum_cs   += $total_cs_2;
		 $sum_ls   += $total_ls_2;	
		 $sum_lr   += $total_lr_2;	
		 $sum_hr   += $total_hr_2;	
		 $sum_or   += $total_or_2;	
		 $sum_claim+= $total_claim_2;	
		 $sum_job  += $total_job_2;
         $sum_jr   += $total_jr_2;
         $sum_all  += $total_all_ass;
 		}
	echo "
		<tr>
 				<td style='text-align:center' colspan='2'><b>Total UE dan ToJ</b></td>
				<td><b>$sum_all</b></td>
 				<td><b>$sum_ass1</b></td>
 				<td><b>$sum_ass2</b></td>
 				<td><b>$sum_ass3</b></td>
				<td><b>$sum_ass4</b></td>
                <td><b>$sum_claim</b></td>
 				<td><b>$sum_cs</b></td>
				<td><b>$sum_ls</b></td>
 				<td><b>$sum_lr</b></td>
				<td><b>$sum_hr</b></td>
                <td><b>$sum_jr</b></td>
                <td><b>$sum_or</b></td>
				<td><b>$sum_job</b></td>
                <td><b>$sum_entri</b></td>
 			</tr>
	";
    
}else{
		echo "<td colspan='19' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>