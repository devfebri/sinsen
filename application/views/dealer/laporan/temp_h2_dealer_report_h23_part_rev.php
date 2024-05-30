<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Reporting H23 Detail Revenue Parts dan Oli Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table>
	<tr>
		<caption><b>Reporting H23 Data Detail Revenue Parts dan Oli AHASS <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB<b></caption>
		<td colspan="17"></td>
	</tr>
</table>

<table border="1">  
	<caption style="text-align:left"><b>Reporting Pendapatan Parts dan Oli</b></caption>
 	<tr> 		
 		<td align="center" rowspan="2"><b>No</b></td>
 		<td align="center" rowspan="2"><b>AHASS</b></td>
        <td align="center" rowspan="2"><b>Total Revenue Parts dan Oli</b></td>
        <td align="center" colspan="3"><b>Summary Revenue</b></td>
 		<td align="center" colspan="9"><b>Revenue per Kelompok Barang</b></td>
		<?php if(!$this->config->item('ahm_d_only')){ ?>
		<td align="center" rowspan="2"><b>Federal Oli</b></td>
		<?php }?>
 	</tr>

	 <tr>
        <td align="center"><b>Revenue Spare Parts (HGP)</b></td> 		
 		<td align="center"><b>Revenue Oli (HGO)</b></td>
 		<td align="center"><b>Revenue Accessoris (HGA)</b></td>
 		<td align="center"><b>Revenue Tire/Ban</b></td>
 		<td align="center"><b>Revenue Oli Direct Sales</b></td>
        <td align="center"><b>Revenue Oli PKB</b></td>
		<td align="center"><b>Revenue Battery/Aki</b></td>
		<td align="center"><b>Revenue Busi</b></td>
 		<td align="center"><b>Revenue CVT Belt</b></td>
 		<td align="center"><b>Revenue Brake</b></td>
        <td align="center"><b>Revenue Revenue Element Cleaner</b></td>
 		<td align="center"><b>Revenue Parts Lain Lain</b></td>
 	</tr>
<?php 
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
	$sum_other=0;
	$sum_all=0;
    $sum_rev=0;
    $sum_oli_ds=0;
 	$nom=1;	
	if($partRevSales->num_rows()>0){
		foreach ($partRevSales->result() as $row) { 
			// $total_all_ass = $row->ass;
			// $total_spart_2 = $row->spart;
			$total_oil_2 = $row->oil_tanpa_wo;
			$total_tire_2 = $row->ban;
			$total_oil_kpb_2 = $row->oil_wo;
			$total_battery_2   = $row->aki;
			$total_busi_2   = $row->busi;
			$total_belt_2   = $row->belt;
			$total_brake_2   = $row->brake;
			$total_acc_2   = $row->accessories;
			$total_element_cleaner_2= $row->element_cleaner;
            $total_other_2   = $row->other;
            $total_fed   = $row->fed;
            // $total_other_2 = $row->other;
			$total_hgp = $row->spart_tanpa_wo + $row->spart_wo;
			$total_hgo = $row->oil_tanpa_wo + $row->oil_wo; 
            $total_revenue_2 = $total_hgp+$total_hgo+$row->accessories;
        ?>
 		
 			<tr>
 				<td><?php echo $nom?></td>
 				<td><?php echo $row->nama_dealer?></td>
                <td><?php echo number_format($total_revenue_2,0,',','.')?></td>
                <td><?php echo number_format($total_hgp,0,',','.')?></td>
 				<td><?php echo number_format($total_hgo,0,',','.')?></td>
 				<td><?php echo number_format($row->accessories,0,',','.')?></td>
				<td><?php echo number_format($row->ban,0,',','.')?></td>
				<td><?php echo number_format($row->oil_tanpa_wo,0,',','.')?></td>
                <td><?php echo number_format($row->oil_wo,0,',','.')?></td>
                <td><?php echo number_format($row->aki,0,',','.')?></td>
                <td><?php echo number_format($row->busi,0,',','.')?></td>
				<td><?php echo number_format($row->belt,0,',','.')?></td>
 				<td><?php echo number_format($row->brake,0,',','.')?></td>
 				<td><?php echo number_format($row->element_cleaner,0,',','.')?></td>
 				<td><?php echo number_format($row->other,0,',','.')?></td>
				<?php if(!$this->config->item('ahm_d_only')){ ?>
				<td><?php echo number_format($row->fed,0,',','.')?></td>
				<?php }?>
 			</tr>

<?php 
 		 $nom++;	
		 $sum_ass1 += $total_hgp;	
		 $sum_ass2 += $total_hgo;	
		 $sum_ass3 += $total_tire_2;
		 $sum_oli_ds += $row->oil_tanpa_wo;	
		 $sum_ass4 += $total_oil_kpb_2;	
		 $sum_cs   += $total_battery_2;
		 $sum_ls   += $total_busi_2;	
		 $sum_lr   += $total_belt_2;	
		 $sum_hr   += $total_brake_2;	
		 $sum_or   += $total_acc_2;	
		 $sum_claim+= $total_element_cleaner_2;	
		 $sum_other+= $total_other_2;
        //  $sum_jr   += $total_jr_2;
        
        //  $sum_all  += $total_all_ass;
        $sum_rev  += $total_revenue_2;
        $sum_fed  += $total_fed;
 		}
    ?>

		<tr>
 				<td style='text-align:center' colspan='2'><b>Total Revenue Parts dan Oli</b></td>
				<td><b><?php echo number_format($sum_rev,0,',','.')?></b></td>
 				<td><b><?php echo number_format($sum_ass1,0,',','.')?></b></td>
 				<td><b><?php echo number_format($sum_ass2,0,',','.')?></b></td>
				<td><b><?php echo number_format($sum_or,0,',','.')?></b></td>
 				<td><b><?php echo number_format($sum_ass3,0,',','.')?></b></td>
				<td><b><?php echo number_format($sum_oli_ds,0,',','.')?></b></td>
				<td><b><?php echo number_format($sum_ass4,0,',','.')?></b></td>
 				<td><b><?php echo number_format($sum_cs,0,',','.')?></b></td>
				<td><b><?php echo number_format($sum_ls,0,',','.')?></b></td>
 				<td><b><?php echo number_format($sum_lr,0,',','.')?></b></td>
				<td><b><?php echo number_format($sum_hr,0,',','.')?></b></td>
                <td><b><?php echo number_format($sum_claim,0,',','.')?></b></td>
                <td><b><?php echo number_format($sum_other,0,',','.')?></b></td>
				<?php if(!$this->config->item('ahm_d_only')){ ?>
				<td><b><?php echo number_format($sum_fed,0,',','.')?></b></td>
				<?php }?>
 			</tr>
	<?php
	}else{
		echo "<td colspan='15' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>

<br>

<table border="1">  
	<caption style="text-align:left"><b>Reporting Sales In Parts dan Oli</b></caption>
 	<tr> 		
 		<td align="center" rowspan="2"><b>No</b></td>
 		<td align="center" rowspan="2"><b>AHASS</b></td>
		<td align="center" colspan="2"><b>Sales In</b></td>
 	</tr>

	 <tr>
		<td align="center"><b>Sales In Parts</b></td>
        <td align="center"><b>Sales In Oli</b></td>
 	</tr>
<?php 
	
    $sum_pud=0;
	$sum_pl=0;
 	$nom=1;	
	if($salesIn->num_rows()>0){
		foreach ($salesIn->result() as $row) { 
            $total_pud_2  = $row->part;
			$total_pl_2   = $row->oli;
        ?>
 		
 			<tr>
 				<td><?php echo $nom?></td>
 				<td><?php echo $row->nama_dealer?></td>
				<td><?php echo number_format($row->part,0,',','.')?></td>
                <td><?php echo number_format($row->oli,0,',','.')?></td>	
 			</tr>

<?php 
 		 $nom++;
         $sum_pud  += $total_pud_2;
         $sum_pl   += $total_pl_2;
 		}
    ?>

		<tr>
 				<td style='text-align:center' colspan='2'><b>Total Revenue Parts dan Oli</b></td>
                <td><b><?php echo number_format($sum_pud,0,',','.')?></b></td>
				<td><b><?php echo number_format($sum_pl,0,',','.')?></b></td>
 			</tr>
	<?php
	}else{
		echo "<td colspan='4' style='text-align:center'> Maaf, Tidak Ada Data Sales In Parts dan Oli</td>";
	}
	?>
</table>

<br>

<table border="1">
	<caption style="text-align:left"><b>Detail Parts dan Oli<b></caption>  
 	<tr> 		
 		<td align="center" rowspan="2"><b>No</b></td>
 		<td align="center" rowspan="2"><b>AHASS</b></td>
        <td align="center" rowspan="2"><b>Total Parts dan Oli</b></td>
        <td align="center" colspan="3"><b>Summary Parts dan Oli</b></td>
 		<td align="center" colspan="9"><b>Detail per Kelompok Barang</b></td>
		 <?php if(!$this->config->item('ahm_d_only')){ ?>
		<td align="center" rowspan="2"><b>Federal Oli</b></td>
		<?php }?>
 	</tr>

	 <tr>
        <td align="center"><b>Spare Parts (HGP)</b></td> 		
 		<td align="center"><b>Oli (HGO)</b></td>
 		<td align="center"><b>Accessoris (HGA)</b></td>
 		<td align="center"><b>Tire/Ban</b></td>
 		<td align="center"><b>Oli Direct Sales</b></td>
        <td align="center"><b>Oli PKB</b></td>
		<td align="center"><b>Battery/Aki</b></td>
		<td align="center"><b>Busi</b></td>
 		<td align="center"><b>CVT Belt</b></td>
 		<td align="center"><b>Brake</b></td>
        <td align="center"><b>Element Cleaner</b></td>
 		<td align="center"><b>Parts Lain Lain</b></td>
 	</tr>
<?php 
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
    $sum_pud=0;
	$sum_pl=0;
    $sum_jr=0;
	$sum_other=0;
	$sum_all=0;
    $sum_rev=0;
	$sum_oli_ds=0;
    $sum_oli_pkb=0;
    $sum_fed=0;
 	$nom=1;	
	if($partCount->num_rows()>0){
		foreach ($partCount->result() as $row) { 
			// $total_all_ass = $row->ass;
			// $total_spart_2 = $row->spart;
			$total_oil_2 = $row->oil_tanpa_wo;
			$total_tire_2 = $row->ban;
			$total_oil_kpb_2 = $row->oil_wo;
			$total_battery_2   = $row->aki;
			$total_busi_2   = $row->busi;
			$total_belt_2   = $row->belt;
			$total_brake_2   = $row->brake;
			$total_acc_2   = $row->accessories;
			$total_element_cleaner_2= $row->element_cleaner;
            $total_other_2   = $row->other;
            $total_revenue_2 = $row->spart_tanpa_wo+$row->spart_wo+$row->oil_wo+$row->oil_tanpa_wo+$row->accessories;
			$total_hgp = $row->spart_tanpa_wo + $row->spart_wo;
			$total_hgo = $row->oil_tanpa_wo + $row->oil_wo;
        ?>
 		
 			<tr>
 				<td><?php echo $nom?></td>
 				<td><?php echo $row->nama_dealer?></td>
                <td><?php echo $total_revenue_2?></td>
                <td><?php echo $total_hgp?></td>
 				<td><?php echo $total_hgo?></td>
 				<td><?php echo $row->accessories?></td>
				<td><?php echo $row->ban?></td>
				<td><?php echo $row->oil_tanpa_wo?></td>
                <td><?php echo $row->oil_wo?></td>
                <td><?php echo $row->aki?></td>
                <td><?php echo $row->busi?></td>
				<td><?php echo $row->belt?></td>
 				<td><?php echo $row->brake?></td>
 				<td><?php echo $row->element_cleaner?></td>
 				<td><?php echo $row->other?></td>
				<?php if(!$this->config->item('ahm_d_only')){ ?>
				<td><?php echo $row->fed?></td>
				<?php }?>
 			</tr>

<?php 
 		 $nom++;	
		 $sum_ass1 += $total_hgp;	
		 $sum_ass2 += $total_hgo;	
		 $sum_ass3 += $total_tire_2;	
		 $sum_cs   += $total_battery_2;
		 $sum_ls   += $total_busi_2;	
		 $sum_lr   += $total_belt_2;	
		 $sum_hr   += $total_brake_2;	
		 $sum_or   += $total_acc_2;	
		 $sum_claim+= $total_element_cleaner_2;	
		 $sum_other+= $total_other_2;
        	$sum_rev  += $total_revenue_2;
        	$sum_oli_ds  += $row->oil_tanpa_wo;
        	$sum_oli_pkb  += $row->oil_wo;
        	$sum_fed  += $row->fed;
 		}
    ?>

		<tr>
 				<td style='text-align:center' colspan='2'><b>Total Parts dan Oli</b></td>
                <td><b><?php echo $sum_rev?></b></td>
                <td><b><?php echo $sum_ass1?></b></td>
 				<td><b><?php echo $sum_ass2?></b></td>
 				<td><b><?php echo $sum_or?></b></td>
				<td><b><?php echo $sum_ass3?></b></td>
				<td><b><?php echo $sum_oli_ds?></b></td>
                <td><b><?php echo $sum_oli_pkb?></b></td>
                <td><b><?php echo $sum_cs?></b></td>
                <td><b><?php echo $sum_ls?></b></td>
				<td><b><?php echo $sum_lr?></b></td>
 				<td><b><?php echo $sum_hr?></b></td>
 				<td><b><?php echo $sum_claim?></b></td>
 				<td><b><?php echo $sum_other?></b></td>
				<?php if(!$this->config->item('ahm_d_only')){ ?>
				<td><b><?php echo $sum_fed?></b></td>
				<?php }?>
 			</tr>
	<?php
	}else{
		echo "<td colspan='18' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>