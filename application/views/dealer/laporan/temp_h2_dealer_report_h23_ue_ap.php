<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Reporting H23 Data UE per Sumber Activity Promotion Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
	<caption>Reporting H23 Data UE per Sumber Activity Promotion Dealer <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</caption>
 	<tr> 		
 		<td align="center" rowspan="2"><b>No</b></td>
 		<td align="center" style="width:380px" rowspan="2"><b>AHASS</b></td>
 		<td align="center" colspan="13"><b>Sumber Activity Promotion</b></td>
		<td align="center" rowspan="2"><b>Total UE</b></td>
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
 	$nom=1;	
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
	if($ue_ap->num_rows()>0){
		foreach ($ue_ap->result() as $row) {
            $total_ass1_2 = $row->pos_service;
			$total_ass2_2 = $row->join_dealer_activity;
			$total_ass3_2 = $row->group_customer;
			$total_ass4_2 = $row->public_area;
			$total_cs_2   = $row->emergency_id;
			$total_ls_2   = $row->pit_express;
			$total_lr_2   = $row->reminder;
			$total_kell   = 0;
			$total_visit  = 0;
			$total_hr_2   = $row->ahass_event_ahm;
			$total_or_2   = $row->ahass_event_md;
			$total_claim_2= $row->ahass_event_ahass;
            $total_jr_2   = $row->non_promotion;
            $total_revenue_2 = $row->pos_service+$row->join_dealer_activity+$row->group_customer+$row->public_area+$row->emergency_id+$row->pit_express+$row->reminder+$row->ahass_event_ahm+$row->ahass_event_md+$row->ahass_event_ahass+$row->non_promotion;
 		echo "
 			<tr>
 				<td>$nom</td>
 				<td>$row->nama_dealer</td>
				<td>$row->pos_service</td>
 				<td>$row->join_dealer_activity</td>
                <td>$row->group_customer</td>
 				<td>$row->public_area</td>
                <td>$row->emergency_id</td>
                <td>$row->pit_express</td>
                <td>$row->reminder</td>
				<td>0</td>
                <td>0</td>
                <td>$row->ahass_event_ahm</td>
                <td>$row->ahass_event_md</td>
				<td>$row->ahass_event_ahass</td>
 				<td>$row->non_promotion</td>
				<td><b>$total_revenue_2 </b></td>

 			</tr>
	 	";
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

        echo "
            <tr>
                <td style='text-align:center' colspan='2'><b>Total UE</b></td>
 				<td><b>$sum_ass1</b></td>
 				<td><b>$sum_ass2</b></td>
 				<td><b>$sum_ass3</b></td>
				<td><b>$sum_ass4</b></td>
				<td><b>$sum_cs</b></td>
				<td><b>$sum_ls</b></td>
				<td><b>$sum_lr</b></td>
				<td><b>$sum_kell</b></td>
				<td><b>$sum_visit</b></td>
				<td><b>$sum_hr</td>
				<td><b>$sum_or</td>
                <td><b>$sum_claim</b></td>
                <td><b>$sum_jr</b></td>
				<td><b>$sum_rev</b></td>
            </tr>
        ";
	}else{
		echo "<td colspan='19' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>


