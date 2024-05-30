<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Reporting H23 Data UE per Alasan Datang ke AHASS Main Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
	<caption>Reporting H23 Data UE per Alasan Datang ke AHASS Main Dealer <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</caption>
 	<tr> 		
 		<td align="center" rowspan="2"><b>No</b></td>
 		<td align="center" style="width:380px" rowspan="2"><b>AHASS</b></td>
 		<td align="center" colspan="3"><b>Alasan Datang ke AHASS</b></td>
		<td align="center" rowspan="2"><b>Total UE</b></td>
 	</tr>
	 <tr> 		
 		<td align="center"><b>Inisiatif Sendiri</b></td>
 		<td align="center"><b>Reminder</b></td>
 		<td align="center"><b>Lainnya</b></td>
 	</tr>

<?php 
 	$nom=1;	
    $sum_inisiatif_sendiri=0;
    $sum_stiker_reminder=0;
    $sum_lainnya=0;
    $sum_total_ue=0;
	if($alasankeAHASS->num_rows()>0){
		foreach ($alasankeAHASS->result() as $row) {
        $total_inisiatif_sendiri = $row->inisiatif_sendiri;
        $total_stiker_reminder = $row->stiker_reminder;
        $total_lainnya = $row->lainnya;
        $total_ue=$row->inisiatif_sendiri+$row->stiker_reminder+$row->lainnya;
 		echo "
 			<tr>
 				<td>$nom</td>
 				<td>$row->nama_dealer</td>
				<td>$row->inisiatif_sendiri</td>
 				<td>$row->stiker_reminder</td>
 				<td>$row->lainnya</td>
				<td><b>$total_ue</b></td>

 			</tr>
	 	";
 		$nom++;
        $sum_inisiatif_sendiri+=$total_inisiatif_sendiri;
        $sum_stiker_reminder  +=$total_stiker_reminder;
        $sum_lainnya          +=$total_lainnya;
        $sum_total_ue         +=$total_ue;
 		}

        echo "
            <tr>
                <td style='text-align:center' colspan='2'><b>Total UE</b></td>
                <td><b>$sum_inisiatif_sendiri</b></td>
                <td><b>$sum_stiker_reminder</b></td>
                <td><b>$sum_lainnya</b></td>
                <td><b>$sum_total_ue</b></td>
            </tr>
        ";
	}else{
		echo "<td colspan='6' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>


