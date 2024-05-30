<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Reporting H23 Data Detail Revenue Pos Service Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<h4><b>Reporting H23 Data Detail Revenue Pos Service Dealer <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB<b></h4>
	
<div class="row">
  <div class="column">
  	<table border="1" style="text-align:center;display:inline-block;width:30%;"> 
	  <caption><b>Revenue Parts dan Oli dari WO</b></caption> 
		<tr> 		
			<td align="center"><b>No</b></td>
			<td align="center"><b>Pos Service</b></td>
			<td align="center"><b>Total Revenue Parts dan Oli dari WO</b></td>
			<td align="center"><b>Total Revenue Parts dari WO</b></td>
			<td align="center"><b>Total Revenue Oli dari WO</b></td>
		</tr>

		<?php 
			$sum_ass1=0;
			$sum_cs=0;
			$sum_all=0;
			$nom=1;	
			if($posAHASSWO->num_rows()>0){
				foreach ($posAHASSWO->result() as $row) { 
					$total_ass1_2 = $row->spart_wo;
					$total_ass2_2 = $row->oil_wo;
					$total_all_ass = $row->spart_wo+$row->oil_wo;
		?>
				<tr>
					<td><?php echo $nom?></td>
					<td><?php echo $row->nama_dealer?></td>
					<td>Rp. <?php echo number_format($total_all_ass,0,',','.')?></td>
					<td>Rp. <?php echo number_format($row->spart_wo,0,',','.')?></td>
					<td>Rp. <?php echo number_format($row->oil_wo,0,',','.')?></td>
				</tr>
		<?php 
			$nom++;	
			$sum_ass1 += $total_ass1_2;
			$sum_cs   += $total_ass2_2;
			$sum_all  += $total_all_ass;
			}
		?>
			<tr>
					<td style='text-align:center' colspan='2'><b>Total Revenue Parts dan Oli WO</b></td>
					<td><b>Rp. <?php echo number_format($sum_all,0,',','.')?></b></td>
					<td><b>Rp. <?php echo number_format($sum_ass1,0,',','.')?></b></td>
					<td><b>Rp. <?php echo number_format($sum_cs,0,',','.')?></b></td>
				</tr>
		<?php
		}else{
			echo "<td colspan='5' style='text-align:center'> Maaf, Tidak Ada Data Untuk Revenue Parts dan Oli WO</td>";
		}
		?>
	</table>
  </div>
  <br>

  <div class="column">
  	<table border="1" style="text-align:center;display:inline-block;width:30%;">  
	  <caption><b>Revenue Parts dan Oli tanpa WO</b></caption>
		<tr> 		
			<td align="center"><b>No</b></td>
			<td align="center"><b>Pos Service</b></td>
			<td align="center"><b>Total Revenue Parts dan Oli tanpa WO</b></td>
			<td align="center"><b>Total Revenue Parts tanpa WO</b></td>
			<td align="center"><b>Total Revenue Oli tanpa WO</b></td>
		</tr>

		<?php 
			$sum_ass1=0;
			$sum_cs=0;
			$sum_all=0;
			$nom=1;	
			if($posAHASSTanpaWO->num_rows()>0){
				foreach ($posAHASSTanpaWO->result() as $row) { 
					
					$total_ass1_2 = $row->spart_tanpa_wo;
					$total_ass2_2 = $row->oil_tanpa_wo;
					$total_all_ass = $row->spart_tanpa_wo+$row->oil_tanpa_wo;
		?>
				<tr>
					<td><?php echo $nom?></td>
					<td><?php echo $row->nama_dealer?></td>
					<td>Rp. <?php echo number_format($total_all_ass,0,',','.')?></td>
					<td>Rp. <?php echo number_format($row->spart_tanpa_wo,0,',','.')?></td>
					<td>Rp. <?php echo number_format($row->oil_tanpa_wo,0,',','.')?></td>
				</tr>
		<?php 
			$nom++;	
			$sum_ass1 += $total_ass1_2;
			$sum_cs   += $total_ass2_2;
			$sum_all  += $total_all_ass;
			}
		?>
			<tr>
					<td style='text-align:center' colspan='2'><b>Total Revenue Parts dan Oli Tanpa WO</b></td>
					<td><b>Rp. <?php echo number_format($sum_all,0,',','.')?></b></td>
					<td><b>Rp. <?php echo number_format($sum_ass1,0,',','.')?></b></td>
					<td><b>Rp. <?php echo number_format($sum_cs,0,',','.')?></b></td>
				</tr>
		<?php
		}else{
			echo "<td colspan='5' style='text-align:center'> Maaf, Tidak Ada Data Untuk Revenue Parts dan Oli Tanpa WO</td>";
		}
		?>
	</table>
  </div>
  <br>

  <div class="column">
  	<table border="1">  
	  <caption><b>Revenue Jasa</b></caption>
		<tr> 		
			<td align="center"><b>No</b></td>
			<td align="center"><b>Pos Service</b></td>
			<td align="center"><b>Total Revenue Jasa</b></td>
		</tr>

		<?php 
			$sum_ass1=0;
			$nom=1;	
			if($posAHASSJasa->num_rows()>0){
				foreach ($posAHASSJasa->result() as $row) { 
					
					$total_ass1_2 = $row->total_jasa;
		?>
				<tr>
					<td><?php echo $nom?></td>
					<td><?php echo $row->nama_dealer?></td>
					<td>Rp. <?php echo number_format($row->total_jasa,0,',','.')?></td>
				</tr>
		<?php 
			$nom++;	
			$sum_ass1 += $total_ass1_2;
			}
		?>
			<tr>
					<td style='text-align:center' colspan='2'><b>Total Revenue Jasa</b></td>
					<td><b>Rp. <?php echo number_format($sum_ass1,0,',','.')?></b></td>
				</tr>
		<?php
		}else{
			echo "<td colspan='5' style='text-align:center'> Maaf, Tidak Ada Data Untuk Revenue Jasa</td>";
		}
		?>
	</table>
  </div>
</div>

<br>

<table border="1">  
 	<tr> 		
        <td align="center"><b>No</b></td>
 		<td align="center"><b>Pos Service</b></td>
 		<td align="center"><b>Mekanik</b></td>
		<td align="center"><b>Total Revenue</b></td>
 	</tr>

     <?php 
	$sum_ass1=0;
 	$nom=1;	
	if($mekanikposService->num_rows()>0){
		foreach ($mekanikposService->result() as $row) { 
			
			$total_ass1_2 = $row->revenue_mekanik;

    ?>
 			<tr>
 				<td><?php echo $nom?></td>
                <td><?php echo $row->nama_dealer?></td>
                <td><?php echo $row->nama_mekanik?></td>
                <td>Rp. <?php echo number_format($row->revenue_mekanik,0,',','.')?></td>
 			</tr>
	<?php 

 		 $nom++;	
		 $sum_ass1 += $total_ass1_2;	
 		}

    ?>
		<tr>
 				<td style='text-align:center' colspan='3'><b>Total Revenue</b></td>
 				<td><b>Rp. <?php echo number_format($sum_ass1,0,',','.')?></b></td>
 			</tr>
    <?php
	}else{
		echo "<td colspan='4' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>

<br>

<table border="1">  
 	<tr> 		
        <td align="center"><b>No</b></td>
 		<td align="center"><b>Pos Service</b></td>
 		<td align="center"><b>SA</b></td>
		<td align="center"><b>Total Revenue</b></td>
 	</tr>

     <?php 
	$sum_ass1=0;
 	$nom=1;	
	if($saposService->num_rows()>0){
		foreach ($saposService->result() as $row) { 
			
			$total_ass1_2 = $row->revenue_sa;

    ?>
 			<tr>
 				<td><?php echo $nom?></td>
                 <td><?php echo $row->nama_dealer?></td>
                <td><?php echo $row->nama_sa?></td>
                <td>Rp. <?php echo number_format($row->revenue_sa,0,',','.')?></td>
 			</tr>
	<?php 

 		 $nom++;	
		 $sum_ass1 += $total_ass1_2;	
 		}

    ?>
		<tr>
 				<td style='text-align:center' colspan='3'><b>Total Revenue</b></td>
 				<td><b>Rp. <?php echo number_format($sum_ass1,0,',','.')?></b></td>
 			</tr>
    <?php
	}else{
		echo "<td colspan='4' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>