<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Report Tarikan Data NMS V2_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
	<caption>Reporting Tarikan Data NMS V2 <?php echo $tgl1." s/d ".$tgl2?> - <?php echo date('H:i')?> WIB</caption>
 	<tr> 		
        <td align="center">No AHASS</td>
 		<td align="center">Nama AHASS</td>
 		<td align="center">No WO/PKB</td>
        <td align="center">Tanggal Service</td>
		<td align="center">No Mesin</td>
		<td align="center">No Rangka</td>
        <td align="center">Tanggal Pembelian H1</td>
		<td align="center">Jenis Pekerjaan</td>
        <td align="center">Tipe Kedatangan/Aktivitas Promosi</td>
		<td align="center">Biaya Jasa</td>
		<td align="center">Biaya Parts</td>
 	</tr>

<?php 
 	$nom=1;	
	if($v2->num_rows()>0){
		foreach ($v2->result() as $row) { 		
 		echo "
 			<tr>
 				<td>'$row->kode_dealer_ahm</td>
 				<td>$row->nama_dealer</td>
				<td>$row->id_work_order</td>
 				<td>$row->created_at</td>
 				<td>$row->no_mesin</td>
				<td>$row->no_rangka</td>
 				<td>$row->tgl_pembelian</td>
				<td>$row->deskripsi</td>
 				<td>$row->activity_promotion</td>
				<td>$row->biaya_jasa</td>
				<td>$row->biaya_part</td>
 			</tr>
	 	";
 		$nom++;
 		}
	}else{
		echo "<td colspan='12' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>


