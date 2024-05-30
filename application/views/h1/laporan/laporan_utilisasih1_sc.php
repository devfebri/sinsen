<?php 
$no = date('dmY_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan_Utilisasi_H1-".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

Laporan Utilisasi Service Concept (Prospect) AS OF  <?php echo date('d F Y - H:i') .' WIB <br>'; ?>

<br>

<table border="1">   	

 	<tr>

 		<td align="center">No</td>		
 		<td align="center">Kode Dealer</td> 	 		 		 		 		
 		<td align="center">Nama Dealer</td> 		 		 		 		 		
 		<?php
		$awal= $start_date;
		$temp_date = $start_date;
		while($start_date <= $end_date){
			echo "<td>'". substr($start_date,-2).'<\td>';
			$start_date = date('Y-m-d', strtotime($start_date . ' +1 day'));
		}
		?>
	</tr>
	


 	<?php 

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
         			
		while($temp_date <= $end_date){
			$jumlah = $this->m_lap->getDataUtilisasi_Prospek($temp_date,$row->kode_dealer_ahm);
			if($jumlah == false){
				$jumlah = 0;
			}
			echo "<td>$jumlah</td>";
						
			$temp_date = date('Y-m-d', strtotime($temp_date . ' +1 day'));		
		}
		echo"
         		</tr>
     		";

     		$urut++;

     	}

    }

 	?> 

</table>