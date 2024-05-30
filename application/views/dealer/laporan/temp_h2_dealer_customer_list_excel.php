<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Customer List Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
	<caption>Data Customer List - <?php echo $start_date ." s/d ". $end_date ?></caption>
 	<tr> 		
 		<td align="center"><b>No</b></td>
		<td align="center"><b>No Mesin</b></td>
		<td align="center"><b>Tipe Motor</b></td>
 		<td align="center"><b>ID Customer</b></td>
		<td align="center"><b>Nama Customer</b></td>
 		<td align="center"><b>No Hp</b></td>
		<td align="center"><b>Pekerjaan</b></td>
		<td align="center"><b>Service Terakhir</b></td>
	</tr>

<?php 
 	$nom=1;	
	if($downloadExcel->num_rows()>0){
		
		foreach ($downloadExcel->result() as $row) {
			echo "
				<tr>
					<td>$nom</td>
					<td>$row->no_mesin</td>
					<td>$row->tipe_ahm</td>
					<td>$row->id_customer</td>
					<td>$row->nama_pembawa</td>
					<td>'$row->no_hp_pembawa</td>
					<td>$row->pekerjaan</td>
					<td>$row->closed_at</td>
				</tr>
			";
 		$nom++;
 		}
	}else{
		echo "<td colspan='8' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>


