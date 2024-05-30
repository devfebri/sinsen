<?php 
$no = date('dmY_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan_Utilisasi-".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

Laporan Utilisasi Service Concept (Work Order) AS OF  <?php echo date('d F Y - H:i') .' WIB <br>'; ?>

<br>

<table border="1">   	

 	<tr>
 		<td align="center" rowspan="2">No</td>		
 		<td align="center" rowspan="2">Kode Dealer</td> 	 		 		 		 		
 		<td align="center" rowspan="2">Nama Dealer</td> 		 		 		 		 		
 		<?php
		$awal= $start_date;
		$temp_date = $start_date;
		while($start_date <= $end_date){
			echo "<td colspan='3' align='center'>". substr($start_date,-2).'<\td>';
			$start_date = date('Y-m-d', strtotime($start_date . ' +1 day'));
			
		}
		?>	 		
 		<td align="center" colspan="4">Total</td> 
	</tr>
	
 	<?php 	
		$start_date = $awal;
		while($start_date <= $end_date){
			echo "<td>UE</td><td>NMS</td><td>SC</td>";	
			$start_date = date('Y-m-d', strtotime($start_date . ' +1 day'));		
		}
		echo "<td>UE</td><td>NMS</td><td>SC</td><td>% SC</td>";	
 	$urut=1;

    if($list_dealer!='false'){
     	foreach ($list_dealer as $row) {
		$temp_date = $awal;
     		echo "
         		<tr>

         			<td>$urut</td>
         			<td>'$row->kode_dealer_ahm</td>
         			<td>$row->nama_dealer</td>
		";
         	
		$total_nms = 0;
		$total_sc = 0;
		$total_ue = 0;	
		$sc_ = "0%";

		while($temp_date <= $end_date){
			$jumlah = 0;
			$row_data = $this->m_lap->getDataUtilisasi_Tablet($temp_date,$row->id_dealer);
			if($row_data == false){
				echo "<td>0</td><td>0</td><td>0</td>";
			}else{
				$nms = $row_data->ue - $row_data->sc;
				//$sc_ = round(($row_data->sc * 100) / $row_data->ue,2) .' %';
				echo "<td>$row_data->ue</td><td>$nms</td><td>$row_data->sc</td>";

				$total_ue += $row_data->ue;
				$total_sc += $row_data->sc;
				$total_nms += $nms;
			}		
			$temp_date = date('Y-m-d', strtotime($temp_date . ' +1 day'));		
		}

		if($total_ue > 0){
			$sc_ = round(($total_sc *100) / $total_ue,2).' %';
		}

		echo"
				<td>$total_ue</td><td>$total_nms</td><td>$total_sc</td><td>$sc_</td>
         		</tr>
     		";

     		$urut++;

     	}

    }

 	?> 

</table>