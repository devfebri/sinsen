<?php 
$no = date('dmY_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan_Unit_Entry_H23-".$no.".xls");
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
		
		while($start_date <= $end_date){
			echo "<td colspan='2' align='center'>". substr($start_date,-2).'<\td>';
			$start_date = date('Y-m-d', strtotime($start_date . ' +1 day'));	
		}
		?>	 		
 		<td align="center" colspan="2">Total</td> 
	</tr>
 	<tr>
 		<td align="center">No</td>		
 		<td align="center">Kode Dealer</td> 	 		 		 		 		
 		<td align="center" style="width:380px">Nama Dealer</td> 
		<?php 	
		$start_date = $awal;
		while($start_date <= $end_date){
			echo "	<td>WO</td>
					<td>Billing Process</td>
					";	
			$start_date = date('Y-m-d', strtotime($start_date . ' +1 day'));		
		}
			echo "	
					<td>WO</td>
					<td>Billing Process</td>
					";	
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
         	
		$total_wo = 0;
		$total_bill = 0;
		$count_wo = 0;
		$count_bill = 0;
		
		while($temp_date <= $end_date){
			$jumlah = 0;
			$row_data = $this->m_lap->getDataUE_excel($temp_date,$row->id_dealer);
			if($row_data == false){
				echo "	<td>0</td>
						<td>0</td>";
			}else{
				echo "	
						<td>$row_data->wo</td>
						<td>$row_data->bill</td>";

				$total_wo += $row_data->wo;
				$total_bill += $row_data->bill;
				
				if($row_data->wo != 0){
					$count_wo += count($row_data->wo);
				}
				if($row_data->bill != 0){
					$count_bill += count($row_data->bill);
				}
			}		
			$temp_date = date('Y-m-d', strtotime($temp_date . ' +1 day'));		
		}		
			
		echo"	
				<td>$total_wo</td>
				<td>$total_bill</td>
         		</tr>";
     		$urut++;
		}
    }
 	?> 
</table>