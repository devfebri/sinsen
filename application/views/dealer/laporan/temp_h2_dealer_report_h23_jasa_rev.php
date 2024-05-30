<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Reporting H23 Detail Revenue Jasa Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<h4><b>Reporting H23 Detail Revenue Jasa Dealer <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>


<table border="1">  
 	<tr> 		
 		<td align="center" rowspan="2"><b>No</b></td>
 		<td align="center" rowspan="2"><b>AHASS</b></td>
 		<td align="center" colspan="15"><b>Revenue per Jenis Pekerjaan/ToJ</b></td>
		<td align="center" rowspan="2"><b>Total Revenue</b></td>
 	</tr>

	 <tr>
        <td align="center"><b>Revenue KPB 1-4</b></td> 		
 		<td align="center"><b>Revenue KPB 1</b></td>
 		<td align="center"><b>Revenue KPB 2</b></td>
 		<td align="center"><b>Revenue KPB 3</b></td>
 		<td align="center"><b>Revenue KPB 4</b></td>
        <td align="center"><b>Revenue Claim C2</b></td>
		<td align="center"><b>Revenue Paket Lengkap</b></td>
		<td align="center"><b>Revenue Paket Ringan</b></td>
 		<td align="center"><b>Revenue Light Repair</b></td>
 		<td align="center"><b>Revenue Heavy Repair</b></td>
        <td align="center"><b>Revenue Job Return</b></td>
 		<td align="center"><b>Revenue Ganti Oli Plus</b></td>
		<td align="center"><b>Revenue Paket Lain</b></td>
        <td align="center"><b>Revenue Pekerjaan Luar</b></td>
        <td align="center"><b>Revenue PUD</b></td>
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
 	$nom=1;	
	if($jasaRev->num_rows()>0){
		foreach ($jasaRev->result() as $row) { 
			$total_all_ass = $row->ass;
			$total_ass1_2 = $row->ass1;
			$total_ass2_2 = $row->ass2;
			$total_ass3_2 = $row->ass3;
			$total_ass4_2 = $row->ass4;
			$total_cs_2   = $row->cs;
			$total_ls_2   = $row->ls;
			$total_lr_2   = $row->lr;
			$total_hr_2   = $row->hr;
			$total_or_2   = $row->or_plus;
			$total_claim_2= $row->claim;
            $total_jr_2   = $row->jr;
            $total_pud_2  = $row->pud;
			$total_pl_2   = $row->pl;
            $total_other_2 = $row->other;
            $total_revenue_2 = $row->ass+$row->cs+$row->ls+$row->lr+$row->hr+$row->or_plus+$row->claim+$row->pl+$row->pud+$row->other;
        ?>
 		
 			<tr>
 				<td><?php echo $nom?></td>
 				<td><?php echo $row->nama_dealer?></td>
                <td>Rp. <?php echo number_format($row->ass,0,',','.')?></td>
                <td>Rp. <?php echo number_format($row->ass1,0,',','.')?></td>
 				<td>Rp. <?php echo number_format($row->ass2,0,',','.')?></td>
 				<td>Rp. <?php echo number_format($row->ass3,0,',','.')?></td>
				<td>Rp. <?php echo number_format($row->ass4,0,',','.')?></td>
				<td>Rp. <?php echo number_format($row->claim,0,',','.')?></td>
                <td>Rp. <?php echo number_format($row->cs,0,',','.')?></td>
                <td>Rp. <?php echo number_format($row->ls,0,',','.')?></td>
                <td>Rp. <?php echo number_format($row->lr,0,',','.')?></td>
				<td>Rp. <?php echo number_format($row->hr,0,',','.')?></td>
 				<td>Rp. <?php echo number_format($row->jr,0,',','.')?></td>
 				<td>Rp. <?php echo number_format($row->or_plus,0,',','.')?></td>
 				<td>Rp. <?php echo number_format($row->other,0,',','.')?></td>
				<td>Rp. <?php echo number_format($row->pl,0,',','.')?></td>
                <td>Rp. <?php echo number_format($row->pud,0,',','.')?></td>	
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
         $sum_pud  += $total_pud_2;
         $sum_pl   += $total_pl_2;
         $sum_other+= $total_other_2;
         $sum_all  += $total_all_ass;
         $sum_rev  += $total_revenue_2;
 		}
    ?>

		<tr>
 				<td style='text-align:center' colspan='2'><b>Total UE dan ToJ</b></td>
				<td><b>Rp. <?php echo number_format($sum_all,0,',','.')?></b></td>
 				<td><b>Rp. <?php echo number_format($sum_ass1,0,',','.')?></b></td>
 				<td><b>Rp. <?php echo number_format($sum_ass2,0,',','.')?></b></td>
 				<td><b>Rp. <?php echo number_format($sum_ass3,0,',','.')?></b></td>
				<td><b>Rp. <?php echo number_format($sum_ass4,0,',','.')?></b></td>
                <td><b>Rp. <?php echo number_format($sum_claim,0,',','.')?></b></td>
 				<td><b>Rp. <?php echo number_format($sum_cs,0,',','.')?></b></td>
				<td><b>Rp. <?php echo number_format($sum_ls,0,',','.')?></b></td>
 				<td><b>Rp. <?php echo number_format($sum_lr,0,',','.')?></b></td>
				<td><b>Rp. <?php echo number_format($sum_hr,0,',','.')?></b></td>
                <td><b>Rp. <?php echo number_format($sum_jr,0,',','.')?></b></td>
                <td><b>Rp. <?php echo number_format($sum_or,0,',','.')?></b></td>
                <td><b>Rp. <?php echo number_format($sum_other,0,',','.')?></b></td>
				<td><b>Rp. <?php echo number_format($sum_pl,0,',','.')?></b></td>
				<td><b>Rp. <?php echo number_format($sum_pud,0,',','.')?></b></td>
				<td><b>Rp. <?php echo number_format($sum_rev,0,',','.')?></b></td>
 			</tr>
	<?php
	}else{
		echo "<td colspan='18' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>