<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Reporting H23 Data UE per Sumber Activity Capacity Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
	<caption>Reporting H23 Data UE per Sumber Activity Capacity Dealer <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</caption>
 	<tr> 		
 		<td align="center" rowspan="2"><b>No</b></td>
 		<td align="center" style="width:380px" rowspan="2"><b>AHASS</b></td>
 		<td align="center" colspan="3"><b>Sumber Activity Capacity</b></td>
		<td align="center" rowspan="2"><b>Total UE</b></td>
 	</tr>
     <tr>
        <td align="center"><b>Booking Service</b></td> 		
 		<td align="center"><b>Happy Hours</b></td>
 		<td align="center"><b>Lainnya</b></td>
 	</tr>

<?php 
 	$nom=1;	
    $sum_ass1=0;
    $sum_ass2=0;
    $sum_ass3=0;
    $sum_rev=0;
	if($ue_ac->num_rows()>0){
		foreach ($ue_ac->result() as $row) {
            $total_ass1_2 = $row->booking_service;
			$total_ass2_2 = $row->happy_hours;
			$total_ass3_2 = $row->lainnya;
            $total_revenue_2 = $row->booking_service+$row->happy_hours+$row->lainnya;
 		echo "
 			<tr>
 				<td>$nom</td>
 				<td>$row->nama_dealer</td>
                <td>$row->booking_service</td>
				<td>$row->happy_hours</td>
 				<td>$row->lainnya</td>
				<td><b>$total_revenue_2 </b></td>

 			</tr>
	 	";
 		 $nom++;
         $sum_ass1 += $total_ass1_2;	
		 $sum_ass2 += $total_ass2_2;	
		 $sum_ass3 += $total_ass3_2;	
         $sum_rev  += $total_revenue_2;
 		}

        echo "
            <tr>
                <td style='text-align:center' colspan='2'><b>Total UE</b></td>
 				<td><b>$sum_ass1</b></td>
 				<td><b>$sum_ass2</b></td>
 				<td><b>$sum_ass3</b></td>
				<td><b>$sum_rev</b></td>
            </tr>
        ";
	}else{
		echo "<td colspan='6' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>


