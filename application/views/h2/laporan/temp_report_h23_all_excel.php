<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Reporting H23 Main Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<h4><b>Reporting All Transaksi H23 Main Dealer <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
    <caption style="text-align:left"><b>Total UE dan ToJ per Type Motor</b></caption>
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

<br>

<table border="1"> 
<caption style="text-align:left"><b>Total UE dan ToJ per Tanggal Transaksi</b></caption> 
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
			join tr_h2_sa_form b on a.id_customer=b.id_customer join tr_h2_wo_dealer c on b.id_sa_form=c.id_sa_form 
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

<br>
<table border="1">  
<caption style="text-align:left"><b>Total UE dan ToJ per Mekanik</b></caption>
 	<tr> 		
 		<td align="center" rowspan="2"><b>No</b></td>
 		<td align="center" rowspan="2"><b>Nama Mekanik</b></td>
 		<td align="center" rowspan="2"><b>Total UE per Mekanik</b></td>
 		<td align="center" colspan="10"><b>Jenis Pekerjaan</b></td>
		<td align="center" rowspan="2"><b>Total ToJ per Mekanik</b></td>
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
	if($mekanik->num_rows()>0){
		foreach ($mekanik->result() as $row) { 
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = "and c.id_dealer='$id_dealer'";
         		}

			$nomesin= $this->db->query("select count(c.id_work_order) as total_wo 
			from ms_customer_h23 a 
			join tr_h2_sa_form b on a.id_customer=b.id_customer 
			join tr_h2_wo_dealer c on b.id_sa_form=c.id_sa_form 
			join ms_karyawan_dealer h on c.id_karyawan_dealer =h.id_karyawan_dealer
			where c.status='closed' and c.id_karyawan_dealer ='$row->id_mekanik' and left(c.created_njb_at,10) between '$start_date' and '$end_date' $filter_dealer")->row();

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
 				<td>$row->id_mekanik - $row->nama_mekanik</td>
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
<caption style="text-align:left"><b>Total UE dan ToJ per SA</b></caption> 
 	<tr> 		
 		<td align="center" rowspan="2"><b>No</b></td>
 		<td align="center" rowspan="2"><b>Nama SA</b></td>
 		<td align="center" rowspan="2"><b>Total UE per SA</b></td>
 		<td align="center" colspan="10"><b>Jenis Pekerjaan</b></td>
		<td align="center" rowspan="2"><b>Total ToJ per SA</b></td>
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
	if($sa->num_rows()>0){
		foreach ($sa->result() as $row) { 
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = "and c.id_dealer='$id_dealer'";
         		}
			$nomesin= $this->db->query("select count(c.id_work_order) as total_wo 
			from ms_customer_h23 a 
			join tr_h2_sa_form b on a.id_customer=b.id_customer 
			join tr_h2_wo_dealer c on b.id_sa_form=c.id_sa_form 
			LEFT JOIN ms_user i on c.created_by = i.id_user
			where c.status='closed' and c.created_by ='$row->id_sa' and left(c.created_njb_at,10) between '$start_date' and '$end_date' $filter_dealer")->row();

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
 				<td>$row->id_sa - $row->nama_sa</td>
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
    <caption style="text-align:left"><b>Informasi Pendapatan Jasa dan Spare Parts</b></caption>
	<?php 
		$sum_jasa = 0;
		$sum_sparepart = 0; 
		$sum_all = 0; 
		$oli2 = $partRevSales->oil_tanpa_wo+$partRevSales->oil_wo;
        $sum_jasa = $jasaRev->ass + $jasaRev->claim + $jasaRev->lr + $jasaRev->hr + $jasaRev->cs + $jasaRev->ls + $jasaRev->or_plus; 
        $sum_sparepart = $partRevSales->spart+$oli2+$partRevSales->accessories+$partRevSales->ban+$partRevSales->busi+$partRevSales->aki+$partRevSales->belt+$partRevSales->element_cleaner+$partRevSales->brake+$partRevSales->plastic_part+$partRevSales->spart+$partRevSales->oil_wo+$partRevSales->oil_tanpa_wo; 
		$sum_all = $sum_jasa+$partRevSales->spart+$oli2;
    ?>
    <tr>
        <td><b>No</b></td>
        <td><b>Pendapatan</b></td>
        <td><b>Nilai</b></td>
    </tr>
    <tr>
        <td>1</td>
        <td>Total Pendapatan Jasa dan Reparasi</td>
        <td>Rp. <?php echo number_format($sum_jasa,0,',','.') ?></td>
    </tr>
    <tr>
        <td>2</td>
        <td>Total Pendapatan Part</td>
        <td>Rp. <?php echo number_format($partRevSales->spart,0,',','.') ?></td>
    </tr>
    <tr>
        <td>3</td>
        <td>Total Pendapatan Oli</td>
        <td>Rp. <?php echo number_format($oli2,0,',','.') ?></td>
    </tr>
    <tr>
        <td colspan="2"><b>Total Pendapatan Bengkel</b></td>
        <td>Rp. <?php echo number_format($sum_all,0,',','.') ?></td>
    </tr>
</table>

<br>
<table border="1">
    <caption style="text-align:left"><b>Informasi Detail Pendapatan Jasa dan Spare Parts</b></caption>
    
    <tr>
        <td><b>No</b></td>
        <td><b>Pendapatan</b></td>
        <td><b>Nilai</b></td>
    </tr>
    <tr>
        <td>1</td>
        <td>Pendapatan Jasa KPB</td>
        <td>Rp. <?php echo number_format($jasaRev->ass,0,',','.') ?></td>
    </tr>
    <tr>
        <td>2</td>
        <td>Pendapatan Jasa Claim</td>
        <td>Rp. <?php echo number_format($jasaRev->claim,0,',','.') ?></td>
    </tr>
    <tr>
        <td>3</td>
        <td>Pendapatan Jasa Light Repair</td>
        <td>Rp. <?php echo number_format($jasaRev->lr,0,',','.') ?></td>
    </tr>
    <tr>
        <td>4</td>
        <td>Pendapatan Jasa Heavy Repair</td>
        <td>Rp. <?php echo number_format($jasaRev->hr,0,',','.') ?></td>
    </tr>
    <tr>
        <td>5</td>
        <td>Pendapatan Jasa Paket Lengkap</td>
        <td>Rp. <?php echo number_format($jasaRev->cs,0,',','.') ?></td>
    </tr>
    <tr>
        <td>6</td>
        <td>Pendapatan Jasa Paket Ringan</td>
        <td>Rp. <?php echo number_format($jasaRev->ls,0,',','.') ?></td>
    </tr>
    <tr>
        <td>7</td>
        <td>Pendapatan Jasa Ganti Oli Plus</td>
        <td>Rp. <?php echo number_format($jasaRev->or_plus,0,',','.') ?></td>
    </tr>
    <tr>
        <td colspan="2"><b>Total Pendapatan Jasa</b></td>
        <td>Rp. <?php echo number_format($sum_jasa,0,',','.') ?></td>
    </tr>
</table>

<br>

<table border="1">
    
    <tr>
        <td><b>No</b></td>
        <td><b>Pendapatan</b></td>
        <td><b>Nilai</b></td>
    </tr>
    <tr>
        <td>1</td>
        <td>Pendapatan Spare Parts</td>
        <td>Rp. <?php echo number_format($partRevSales->spart,0,',','.') ?></td>
    </tr>
    <tr>
        <td>2</td>
        <td>Pendapatan Oli</td>
        <td>Rp. <?php echo number_format($oli2,0,',','.') ?></td>
    </tr>
    <tr>
        <td>3</td>
        <td>Pendapatan Accesories</td>
        <td>Rp. <?php echo number_format($partRevSales->accessories,0,',','.') ?></td>
    </tr>
    <tr>
        <td>4</td>
        <td>Pendapatan Ban</td>
        <td>Rp. <?php echo number_format($partRevSales->ban,0,',','.') ?></td>
    </tr>
    <tr>
        <td>5</td>
        <td>Pendapatan Busi</td>
        <td>Rp. <?php echo number_format($partRevSales->busi,0,',','.') ?></td>
    </tr>
    <tr>
        <td>6</td>
        <td>Pendapatan Jasa Aki/Batere</td>
        <td>Rp. <?php echo number_format($partRevSales->aki,0,',','.') ?></td>
    </tr>
    <tr>
        <td>7</td>
        <td>Pendapatan CVT Belt</td>
        <td>Rp. <?php echo number_format($partRevSales->belt,0,',','.') ?></td>
    </tr>
    <tr>
        <td>8</td>
        <td>Pendapatan Saringan Udara</td>
        <td>Rp. <?php echo number_format($partRevSales->element_cleaner,0,',','.') ?></td>
    </tr>
    <tr>
        <td>9</td>
        <td>Pendapatan Oli KPB</td>
        <td>Rp. <?php echo number_format($partRevSales->oil_wo,0,',','.') ?></td>
    </tr>
    <tr>
        <td>10</td>
        <td>Pendapatan Brake</td>
        <td>Rp. <?php echo number_format($partRevSales->brake,0,',','.') ?></td>
    </tr>
    <tr>
        <td>11</td>
        <td>Pendapatan Plastic Part</td>
        <td>Rp. <?php echo number_format($partRevSales->plastic_part,0,',','.') ?></td>
    </tr>
    <tr>
        <td>12</td>
        <td>Pendapatan Parts Direct PKB/WO</td>
        <td>Rp. <?php echo number_format($partRevSales->spart_wo,0,',','.') ?></td>
    </tr>
    <tr>
        <td>13</td>
        <td>Pendapatan Parts Direct non PKB/WO</td>
        <td>Rp. <?php echo number_format($partRevSales->spart_tanpa_wo,0,',','.') ?></td>
    </tr>
    <tr>
        <td>14</td>
        <td>Pendapatan Oli Direct PKB/WO</td>
        <td>Rp. <?php echo number_format($partRevSales->oil_wo,0,',','.') ?></td>
    </tr>
    <tr>
        <td>14</td>
        <td>Pendapatan Oli Direct non PKB/WO</td>
        <td>Rp. <?php echo number_format($partRevSales->oil_tanpa_wo,0,',','.') ?></td>
    </tr>
    <tr>
        <td colspan="2"><b>Total Pendapatan Spare Parts</b></td>
        <td>Rp. <?php echo number_format($sum_sparepart,0,',','.') ?></td>
    </tr>
</table>

<br>

<table border="1"> 
<caption style="text-align:left"><b>Total UE per Alasan Datang ke AHASS</b></caption> 
 	<tr> 		
 		<td align="center" rowspan="2"><b>No</b></td>
 		<td align="center" rowspan="2"><b>Alasan Datang ke AHASS</b></td>
 		<td align="center" rowspan="2"><b>Total UE per Alasan Datang</b></td>
 		<td align="center" colspan="10"><b>Jenis Pekerjaan</b></td>
		<td align="center" rowspan="2"><b>Total ToJ per Alasan Datang</b></td>
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
	if($alasanDatang->num_rows()>0){
		foreach ($alasanDatang->result() as $row) { 
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = "and c.id_dealer='$id_dealer'";
         		}

			$nomesin= $this->db->query("select count(c.id_work_order) as total_wo 
			from ms_customer_h23 a 
			join tr_h2_sa_form b on a.id_customer=b.id_customer 
			join tr_h2_wo_dealer c on b.id_sa_form=c.id_sa_form 
			LEFT JOIN ms_user i on c.created_by = i.id_user
			where c.status='closed' and b.alasan_ke_ahass ='$row->alasan_ke_ahass' and left(c.created_njb_at,10) between '$start_date' and '$end_date' $filter_dealer")->row();

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
 				<td>$row->alasan_ke_ahass</td>
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
<caption style="text-align:left"><b>Total UE per Sumber Activity Promotion</b></caption> 
 	<tr> 		
 		<td align="center" rowspan="2"><b>No</b></td>
 		<td align="center" rowspan="2"><b>Sumber Activity Promotion</b></td>
 		<td align="center" rowspan="2"><b>Total UE per Activity Promotion</b></td>
 		<td align="center" colspan="10"><b>Jenis Pekerjaan</b></td>
		<td align="center" rowspan="2"><b>Total ToJ per Activity Promotion</b></td>
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
	if($activityPromotion->num_rows()>0){
		foreach ($activityPromotion->result() as $row) { 
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = "and c.id_dealer='$id_dealer'";
         		}

			$nomesin= $this->db->query("select count(c.id_work_order) as total_wo from ms_customer_h23 a 
			join tr_h2_sa_form b on a.id_customer=b.id_customer join tr_h2_wo_dealer c on b.id_sa_form=c.id_sa_form 
			join dms_ms_activity_promotion j on j.id = b.activity_promotion_id 
			where c.status='closed' and b.activity_promotion_id  ='$row->activity_promotion_id' and left(c.created_njb_at,10) between '$start_date' and '$end_date' $filter_dealer")->row();

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
 				<td>$row->activity_promotion</td>
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
<caption style="text-align:left"><b>Total UE per Sumber Activity Capacity</b></caption> 
 	<tr> 		
 		<td align="center" rowspan="2"><b>No</b></td>
 		<td align="center" rowspan="2"><b>Sumber Activity Capacity</b></td>
 		<td align="center" rowspan="2"><b>Total UE per Activity Capacity</b></td>
 		<td align="center" colspan="10"><b>Jenis Pekerjaan</b></td>
		<td align="center" rowspan="2"><b>Total ToJ per Activity Capacity</b></td>
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
	if($activityCapacity->num_rows()>0){
		foreach ($activityCapacity->result() as $row) {
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = "and c.id_dealer='$id_dealer'";
         		}

			$nomesin= $this->db->query("select count(c.id_work_order) as total_wo from ms_customer_h23 a 
			join tr_h2_sa_form b on a.id_customer=b.id_customer join tr_h2_wo_dealer c on b.id_sa_form=c.id_sa_form 
			join dms_ms_activity_capacity k on k.id = b.activity_capacity_id
			where c.status='closed' and b.activity_capacity_id  ='$row->activity_capacity_id' and left(c.created_njb_at,10) between '$start_date' and '$end_date' $filter_dealer ")->row();
 
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
 				<td>$row->activity_capacity</td>
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