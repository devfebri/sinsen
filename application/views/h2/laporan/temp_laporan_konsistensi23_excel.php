<?php 
$no = date('dmY_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan_Konsistensi_H23-".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<br>
<table border="1">   	
	<tr>
		<td colspan="3"><b><?php echo $subjudul; ?></b></td>
		<?php
		$awal= $start_date;
		$temp_date = $start_date;
		//Variabel Hari Konsistensi
		$hari21 = 21;
		$hari14 = 14;
		while($start_date <= $end_date){
			echo "<td colspan='5' align='center'>". substr($start_date,-2).'<\td>';
			$start_date = date('Y-m-d', strtotime($start_date . ' +1 day'));	
		}
		?>	 		
 		<td align="center" colspan="5">Total</td> 
		<td align="center" colspan="5">Konsistensi (n hari)</td> 
		<td align="center" colspan="5">Konsistensi(%)</td> 
	</tr>
 	<tr>
 		<td align="center">No</td>		
 		<td align="center">Kode Dealer</td> 	 		 		 		 		
 		<td align="center" style="width:380px">Nama Dealer</td> 
		<?php 	
		$start_date = $awal;
		while($start_date <= $end_date){
			echo "	<td>Apps(WO)</td>
					<td>WO</td>
					<td>Billing Process</td>
					<td>Part Sales</td>
					<td>Part Inbound</td>";	
			$start_date = date('Y-m-d', strtotime($start_date . ' +1 day'));		
		}
			echo "	<td>Apps(WO)</td>
					<td>WO</td>
					<td>Billing Process</td>
					<td>Part Sales</td>
					<td>Part Inbound</td>
					<td>Apps(WO)</td>
					<td>WO</td>
					<td>Billing Process</td>
					<td>Part Sales</td>
					<td>Part Inbound</td>
					<td>Apps(WO)</td>
					<td>WO</td>
					<td>Billing Process</td>
					<td>Part Sales</td>
					<td>Part Inbound</td>";	
			$urut=1;
		?> 		 		 		
 	</tr>
	<?php 	
    if($list_dealer!='false'){
     	foreach ($list_dealer as $row) {
		$temp_date = $awal;
     		echo "
         		<tr>
					<td>$urut</td>
         			<td>$row->kode_dealer_ahm</td>
         			<td>$row->nama_dealer</td>
				";
         	
		$total_wosc = 0;
		$total_wo = 0;
		$total_bill = 0;
		$total_part = 0;
		$total_inbound = 0;
		$count_wosc = 0;
		$count_wo = 0;
		$count_bill = 0;
		$count_part = 0;
		$count_inbound = 0;
		$persen_wosc=0;
		$persen_wo=0;
		$persen_bill=0;
		$persen_part=0;
		$persen_inbound=0;
		
		while($temp_date <= $end_date){
			$jumlah = 0;
			$row_data = $this->m_lap->getDataKonsistensi_excel($temp_date,$row->id_dealer);
			if($row_data == false){
				echo "	<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>";
			}else{
				echo "	<td>$row_data->wosc</td>
						<td>$row_data->wo</td>
						<td>$row_data->bill</td>
						<td>$row_data->part</td>
						<td>$row_data->inbound </td>";

				$total_wosc += $row_data->wosc;
				$total_wo += $row_data->wo;
				$total_bill += $row_data->bill;
				$total_part += $row_data->part;
				$total_inbound += $row_data->inbound;
				if($row_data->wosc != 0){
					$count_wosc += count($row_data->wosc);
				}
				if($row_data->wo != 0){
					$count_wo += count($row_data->wo);
				}
				if($row_data->bill != 0){
					$count_bill += count($row_data->bill);
				}
				if($row_data->part != 0){
					$count_part += count($row_data->part);
				}
				if($row_data->inbound != 0){
					$count_inbound += count($row_data->inbound);
				}
			}		
			$temp_date = date('Y-m-d', strtotime($temp_date . ' +1 day'));		
		}		
			// Ini Kodingan Jika Pembagi Harinya dinamis mengikuti berapa hari yg dipilih 
			// $tgl_awal = new DateTime($awal);
			// $akhir_tgl = new DateTime($end_date);
			// $diff = $akhir_tgl->diff($tgl_awal);
			// $persen_wosc = round($count_wosc*100/(($diff->days)+1),2);
			// $persen_wo = round($count_wo*100/(($diff->days)+1),2);
			// $persen_bill = round($count_bill*100/(($diff->days)+1),2);
			// $persen_part = round($count_part*100/(($diff->days)+1),2);
			// $persen_inbound = round($count_inbound*100/(($diff->days)+1),2);

			$persen_wosc = round($count_wosc*100/$hari21,2);
			$persen_wo = round($count_wo*100/$hari21,2);
			$persen_bill = round($count_bill*100/$hari21,2);
			$persen_part = round($count_part*100/$hari21,2);
			$persen_inbound = round($count_inbound*100/$hari14,2);
			
		echo"	<td>$total_wosc</td>
				<td>$total_wo</td>
				<td>$total_bill</td>
				<td>$total_part</td>
				<td>$total_inbound</td>
				<td>$count_wosc</td>
				<td>$count_wo</td>
				<td>$count_bill</td>
				<td>$count_part</td>
				<td>$count_inbound </td>
				<td style='text-align:right'>$persen_wosc%</td>
				<td style='text-align:right'>$persen_wo%</td>
				<td style='text-align:right'>$persen_bill%</td>
				<td style='text-align:right'>$persen_part%</td>
				<td style='text-align:right'>$persen_inbound%</td>
         		</tr>";
     		$urut++;
		}
    }
 	?> 
</table>