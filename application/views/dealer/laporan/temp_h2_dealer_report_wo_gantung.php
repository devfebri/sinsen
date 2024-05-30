<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Report WO Gantung_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
	<caption>Report WO Gantung </caption>
 	<tr> 		
 		<td align="center"><b>No</b></td>
 		<td align="center"><b>Nama Customer</b></td>
 		<td align="center"><b>No Polisi</b></td>
 		<td align="center"><b>No Mesin</b></td>
 		<td align="center"><b>No Rangka</b></td>
 		<td align="center"><b>No.SA Form</b></td>
 		<td align="center"><b>Tgl SA Form</b></td>
 		<td align="center"><b>No.WO</b></td>
 		<td align="center"><b>Tgl WO</b></td>
 		<td align="center"><b>Status WO</b></td>
		<td align="center"><b>Status Mechanic Scheduling</b></td>
		<td align="center" ><b>Deskripsi Jasa</b></td>
		<td align="center" ><b>Type Jasa</b></td>
		<td align="center" ><b>Kode Part</b></td>
		<td align="center" ><b>Nama Part</b></td>
		<td align="center" ><b>qty</b></td>
 	</tr>

<?php 
 	$nom=1;	
	if($report_wo_gantung->num_rows()>0){
		foreach ($report_wo_gantung->result() as $row) {
		
 		echo "
 			<tr>
 				<td>$nom</td>
 				<td>$row->nama_customer</td>
				<td>$row->no_polisi</td>
 				<td>$row->no_mesin</td>
 				<td>$row->no_rangka</td>
 				<td>$row->id_sa_form</td>
				<td>$row->tgl_servis</td>
 				<td>$row->id_work_order</td>
				<td>$row->tgl_wo</td>
 				<td>$row->status_wo</td>
				<td>$row->last_stats</td>
				<td>$row->nama_jasa</td>
				<td>$row->id_type</td>
				<td>'$row->id_part</td>
				<td>$row->nama_part</td>
				<td>$row->qty</td>

 			</tr>
	 	";
 		$nom++;
 		}
	}else{
		echo "<td colspan='13' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>


