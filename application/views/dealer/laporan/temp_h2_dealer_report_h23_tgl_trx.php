<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Reporting H23 Per Mekanik Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<h4><b>Reporting H23 Per Mekanik Dealer <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center" rowspan="2"><b>No</b></td>
 		<td align="center" rowspan="2"><b>Tanggal</b></td>
 		<td align="center" rowspan="2"><b>Total UE per Tanggal Transaksi</b></td>
 		<td align="center" colspan="10"><b>Jenis Pekerjaan</b></td>
		<td align="center" rowspan="2"><b>Total ToJ per Tanggal Transaksi</b></td>
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
	if($tgl_trx->num_rows()>0){
		foreach ($tgl_trx->result() as $row) { 
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = "and c.id_dealer='$id_dealer'";
         		}

			$nomesin= $this->db->query("select count(c.id_work_order) as total_wo from ms_customer_h23 a 
										join tr_h2_sa_form b on a.id_customer=b.id_customer 
										join tr_h2_wo_dealer c on b.id_sa_form=c.id_sa_form 
										where c.status='closed' and DATE_FORMAT(c.created_njb_at,'%d') ='$row->tgl' and left(c.created_njb_at,10) between '$start_date' and '$end_date' $filter_dealer")->row();
			
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
 				<td>$row->tgl</td>
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

<br>

<table border="1">  
	<?php 
		$total_ue = $ue_jam->jam_7+$ue_jam->jam_8+$ue_jam->jam_9+$ue_jam->jam_10+$ue_jam->jam_11+$ue_jam->jam_12+$ue_jam->jam_13+$ue_jam->jam_14+$ue_jam->jam_15+$ue_jam->jam_16;
	?>

 	<tr> 		
 		<td align="center"><b>No</b></td>
 		<td align="center"><b>Jam Kerja</b></td>
 		<td align="center"><b>Total UE</b></td>
 	</tr>
	 <tr> 		
 		<td align="center">1</td>
 		<td align="center">07:00-08:00</td>
 		<td align="center"><?php echo $ue_jam->jam_7?></td>
 	</tr>
	 <tr> 		
 		<td align="center">2</td>
 		<td align="center">08:00-09:00</td>
 		<td align="center"><?php echo $ue_jam->jam_8?></td>
 	</tr>
	 <tr> 		
 		<td align="center">3</td>
 		<td align="center">09:00-10:00</td>
 		<td align="center"><?php echo $ue_jam->jam_9?></td>
 	</tr>
	 <tr> 		
 		<td align="center">4</td>
 		<td align="center">10:00-11:00</td>
 		<td align="center"><?php echo $ue_jam->jam_10?></td>
 	</tr>
	 <tr> 		
 		<td align="center">5</td>
 		<td align="center">11:00-12:00</td>
 		<td align="center"><?php echo $ue_jam->jam_11?></td>
 	</tr>
	 <tr> 		
 		<td align="center">6</td>
 		<td align="center">12:00-13:00</td>
 		<td align="center"><?php echo $ue_jam->jam_12?></td>
 	</tr>
	 <tr> 		
 		<td align="center">7</td>
 		<td align="center">13:00-14:00</td>
 		<td align="center"><?php echo $ue_jam->jam_13?></td>
 	</tr>
	 <tr> 		
 		<td align="center">8</td>
 		<td align="center">10:00-11:00</td>
 		<td align="center"><?php echo $ue_jam->jam_10?></td>
 	</tr>
	 <tr> 		
 		<td align="center">9</td>
 		<td align="center">11:00-12:00</td>
 		<td align="center"><?php echo $ue_jam->jam_11?></td>
 	</tr>
	 <tr> 		
 		<td align="center">10</td>
 		<td align="center">12:00-13:00</td>
 		<td align="center"><?php echo $ue_jam->jam_12?></td>
 	</tr>
	 <tr> 		
 		<td align="center">11</td>
 		<td align="center">13:00-14:00</td>
 		<td align="center"><?php echo $ue_jam->jam_13?></td>
 	</tr>
	 <tr> 		
 		<td align="center">12</td>
 		<td align="center">14:00-15:00</td>
 		<td align="center"><?php echo $ue_jam->jam_14?></td>
	</tr>
	<tr> 		
 		<td align="center">13</td>
 		<td align="center">15:00-16:00</td>
 		<td align="center"><?php echo $ue_jam->jam_15?></td>
 	</tr>
	 <tr> 		
 		<td align="center">11</td>
 		<td align="center">16:00-17:00</td>
 		<td align="center"><?php echo $ue_jam->jam_16?></td>
 	</tr>
	 <tr> 		
 		<td align="center" colspan="2"><b>Total UE</b></td>
 		<td align="center"><b><?php echo $total_ue?></b></td>
 	</tr>
</table>

