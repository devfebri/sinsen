<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Reporting H23 Data Mekanik Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
	<caption>Reporting H23 Data Mekanik Dealer <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</caption>
 	<tr> 		
 		<td align="center"><b>No</b></td>
 		<td align="center" style="width:380px"><b>AHASS</b></td>
 		<!-- <td align="center"><b>Jumlah Mekanik</b></td> -->
 		<td align="center"><b>Nama Mekanik</b></td>
		<td align="center"><b>ID Mekanik</b></td>
        <td align="center"><b>Hadir(Hari)</b></td>
 		<td align="center"><b>Tidak Hadir(Hari)</b></td>
		<td align="center"><b>Jam Tersedia(Jam)</b></td>
        <td align="center"><b>Level Training Mekanik</b></td>
 	</tr>

<?php
 	$nom=1;	
	if($dataMekanik->num_rows()>0){
		foreach ($dataMekanik->result() as $row) { 
 		echo "
 			
 				<td>$nom</td>
				<td>$row->nama_dealer</td>
 				<td>$row->nama_lengkap</td>
 				<td>$row->id_flp_md</td>
 				<td>$row->kehadiran</td>
				<td>$row->tidak_hadir</td>
 				<td>$row->waktu</td>
				<td>-</td>
 			</tr>
	 	";
 		$nom++;
 		}
	}else{
		echo "<td colspan='9' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>


