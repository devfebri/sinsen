<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Reporting H23 Detail Revenue per Sumber Activity Promotion Main Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table border="1"> 
	<caption><b>Reporting H23 Revenue Parts dan Oli per Sumber Activity Promotion Main Dealer <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB<b></caption> 
 	<tr> 		
 		<td align="center" rowspan="2"><b>No</b></td>
 		<td align="center" rowspan="2"><b>AHASS</b></td>
 		<td align="center" colspan="13"><b>Revenue per Sumber Activity Promotion</b></td>
		<td align="center" rowspan="2"><b>Total Revenue</b></td>
 	</tr>

	 <tr>
        <td align="center"><b>Pos Service</b></td> 		
 		<td align="center"><b>Join Dealer Activity</b></td>
 		<td align="center"><b>Group Customer</b></td>
 		<td align="center"><b>Public Area</b></td>
 		<td align="center"><b>Emergency</b></td>
        <td align="center"><b>Pit Express</b></td>
		<td align="center"><b>Reminder</b></td>
		<td align="center"><b>AHASS Keliling</b></td>
		<td align="center"><b>Service Visit Home Service</b></td>
 		<td align="center"><b>AHASS Event(AHM)</b></td>
        <td align="center"><b>AHASS Event(MD)</b></td>
 		<td align="center"><b>AHASS Event(AHASS)</b></td>
		<td align="center"><b>Non Promotion</b></td>
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
	$sum_pl=0;
    $sum_jr=0;
    $sum_rev=0;
	$sum_kell=0;
    $sum_visit=0;
 	$nom=1;	
	if($apRev2->num_rows()>0){
		foreach ($apRev2->result() as $row) { 
			$total_ass1_2 = $row->pos_service;
			$total_ass2_2 = $row->join_dealer_activity;
			$total_ass3_2 = $row->group_customer;
			$total_ass4_2 = $row->public_area;
			$total_cs_2   = $row->emergency_id;
			$total_ls_2   = $row->pit_express;
			$total_lr_2   = $row->reminder;
			$total_kell   = $row->ahass_keliling;
			$total_visit  = $row->home_care;
			$total_hr_2   = $row->ahass_event_ahm;
			$total_or_2   = $row->ahass_event_md;
			$total_claim_2= $row->ahass_event_ahass;
            $total_jr_2   = $row->non_promotion;
            $total_revenue_2 = $row->pos_service+$row->join_dealer_activity+$row->group_customer+$row->public_area+$row->emergency_id+$row->pit_express+$row->reminder+$row->ahass_event_ahm+$row->ahass_event_md+$row->ahass_event_ahass+$row->non_promotion+$row->home_care+$row->ahass_keliling;
        ?>
 		
 			<tr>
 				<td><?php echo $nom?></td>
 				<td><?php echo $row->nama_dealer?></td>
                <td>Rp. <?php echo number_format($row->pos_service,0,',','.')?></td>
                <td>Rp. <?php echo number_format($row->join_dealer_activity,0,',','.')?></td>
 				<td>Rp. <?php echo number_format($row->group_customer,0,',','.')?></td>
 				<td>Rp. <?php echo number_format($row->public_area,0,',','.')?></td>
				<td>Rp. <?php echo number_format($row->emergency_id,0,',','.')?></td>
				<td>Rp. <?php echo number_format($row->pit_express,0,',','.')?></td>
                <td>Rp. <?php echo number_format($row->reminder,0,',','.')?></td>
				<td>Rp. <?php echo number_format($row->ahass_keliling,0,',','.')?></td>
				<td>Rp. <?php echo number_format($row->home_care,0,',','.')?></td>
                <td>Rp. <?php echo number_format($row->ahass_event_ahm,0,',','.')?></td>
                <td>Rp. <?php echo number_format($row->ahass_event_md,0,',','.')?></td>
				<td>Rp. <?php echo number_format($row->ahass_event_ahass,0,',','.')?></td>
 				<td>Rp. <?php echo number_format($row->non_promotion,0,',','.')?></td>	
                <td><b>Rp. <?php echo number_format($total_revenue_2,0,',','.')?></b></td>
 			</tr>

<?php 
 		 $nom++;	
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
         $sum_jr   += $total_jr_2;
         $sum_rev  += $total_revenue_2;
		 $sum_kell  =0;
    	 $sum_visit =0;
 		}
    ?>

		<tr>
 				<td style='text-align:center' colspan='2'><b>Revenue</b></td>
 				<td><b>Rp. <?php echo number_format($sum_ass1,0,',','.')?></b></td>
 				<td><b>Rp. <?php echo number_format($sum_ass2,0,',','.')?></b></td>
 				<td><b>Rp. <?php echo number_format($sum_ass3,0,',','.')?></b></td>
				<td><b>Rp. <?php echo number_format($sum_ass4,0,',','.')?></b></td>
				<td><b>Rp. <?php echo number_format($sum_cs,0,',','.')?></b></td>
				<td><b>Rp. <?php echo number_format($sum_ls,0,',','.')?></b></td>
				<td><b>Rp. <?php echo number_format($sum_lr,0,',','.')?></b></td>
				<td><b>Rp. <?php echo number_format($sum_kell,0,',','.')?></b></td>
				<td><b>Rp. <?php echo number_format($sum_visit,0,',','.')?></b></td>
				<td><b>Rp. <?php echo number_format($sum_hr,0,',','.')?></b></td>
				<td><b>Rp. <?php echo number_format($sum_or,0,',','.')?></b></td>
                <td><b>Rp. <?php echo number_format($sum_claim,0,',','.')?></b></td>
                <td><b>Rp. <?php echo number_format($sum_jr,0,',','.')?></b></td>
				<td><b>Rp. <?php echo number_format($sum_rev,0,',','.')?></b></td>
 			</tr>
	<?php
	}else{
		echo "<td colspan='19' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>