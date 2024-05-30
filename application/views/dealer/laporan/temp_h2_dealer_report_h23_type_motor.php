<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Reporting H23 Per Type Motor Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<h4><b>Reporting H23 Per Type Motor Dealer <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center" rowspan="2"><b>No</b></td>
 		<td align="center" style="width:380px" rowspan="2"><b>Type Motor</b></td>
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
 	$nom=1;	
	if($type_motor->num_rows()>0){
		foreach ($type_motor->result() as $row) { 
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = "and c.id_dealer='$id_dealer'";
         		}
			$nomesin= $this->db->query("select count(c.id_work_order) as total_wo from ms_customer_h23 a 
										join tr_h2_sa_form b on a.id_customer=b.id_customer join tr_h2_wo_dealer c on b.id_sa_form=c.id_sa_form where 
										c.status='closed' and a.id_tipe_kendaraan='$row->id_tipe_kendaraan' and left(c.created_njb_at,10) between '$start_date' and '$end_date' $filter_dealer")->row(); 

			$total_entri2 = $nomesin->total_wo;
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
			$total_job_2  = $row->total_job;
		
 		echo "
 			<tr>
 				<td>$nom</td>
 				<td>$row->id_tipe_kendaraan - $row->tipe_ahm</td>
				<td><b>$nomesin->total_wo</b></td>
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
				<td><b>$row->total_job</b></td>
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
 		}
	echo "
		<tr>
 				<td style='text-align:center' colspan='2'><b>Total UE dan ToJ</b></td>
				<td><b>$sum_entri</b></td>
 				<td><b>$sum_ass1</b></td>
 				<td><b>$sum_ass2</b></td>
 				<td><b>$sum_ass3</b></td>
				<td><b>$sum_ass4</b></td>
 				<td><b>$sum_cs</b></td>
				<td><b>$sum_ls</b></td>
 				<td><b>$sum_lr</b></td>
				<td><b>$sum_hr</b></td>
				<td><b>$sum_or</b></td>
				<td><b>$sum_claim</b></td>
				<td><b>$sum_job</b></td>
 			</tr>
	";
	}else{
		echo "<td colspan='14' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>