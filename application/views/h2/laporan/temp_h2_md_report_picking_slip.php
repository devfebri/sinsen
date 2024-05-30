<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Report Picking Slip_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
	<caption>Report Picking Slip <?php echo date("d-m-Y", strtotime($start_date))." s/d ".date("d-m-Y", strtotime($end_date))?> - <?php echo date('H:i')?> WIB</caption>
 	<tr> 		
 		<td align="center"><b>No</b></td>
 		<td align="center"><b>Nama Dealer</b></td>
 		<td align="center"><b>Nama Customer</b></td>
 		<td align="center"><b>No.Hp</b></td>
 		<td align="center"><b>Referensi</b></td>
 		<td align="center"><b>No.Referensi</b></td>
 		<td align="center"><b>Nomor PS</b></td>
 		<td align="center"><b>Tanggal PS</b></td>
		<td align="center"><b>Cetak PS</b></td>
		<td align="center" ><b>Status PS</b></td>
		<td align="center" ><b>Nomor SO</b></td>
		<td align="center" ><b>Tanggal SO</b></td>
		<td align="center" ><b>Status SO</b></td>
 	</tr>

<?php 
 	$nom=1;	
	if($report_picking_slip->num_rows()>0){
		foreach ($report_picking_slip->result() as $row) {
		
 		echo "
 			<tr>
 				<td>$nom</td>
 				<td>$row->nama_dealer</td>
				<td>$row->nama_pembeli</td>
 				<td>'$row->no_hp_pembeli</td>
 				<td>$row->referensi</td>
 				<td>$row->id_work_order</td>
				<td>$row->nomor_ps</td>
 				<td>$row->tanggal_ps</td>
				<td>$row->sudah_cetak</td>
 				<td>$row->status_ps</td>
				<td>$row->nomor_so</td>
				<td>$row->tanggal_so</td>
				<td>$row->status_so</td>

 			</tr>
	 	";
 		$nom++;
 		}
	}else{
		echo "<td colspan='13' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>


