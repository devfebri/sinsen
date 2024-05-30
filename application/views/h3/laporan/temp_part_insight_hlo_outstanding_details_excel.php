<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=HLO_Outstanding Details_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : HLO_Outstanding Details <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Nama Dealer</b></td>
		<td align="center"><b>Outstanding Days</b></td>
 		<td align="center"><b>Outstanding</b></td>
		<td align="center"><b>Deskripsi Parts</b></td>
		<td align="center"><b>Parts Number</b></td>
 		<td align="center"><b>Kuantitas</b></td>
 		<td align="center"><b>Tanggal Order Customer</b></td>
 		<td align="center"><b>Series</b></td>
 		<td align="center"><b>Tanggal Pemenuhan PO</b></td>
 		<td align="center"><b>Note</b></td>
 	</tr>
	 <?php 
	if($hlo_outstanding_details->num_rows()>0){
		foreach ($hlo_outstanding_details->result() as $row) { 

		echo "
 			<tr>
				<td>$row->nama_dealer</td>
				<td>$row->outstanding_days</td>
				<td>$row->outstanding</td>
				<td>$row->nama_part</td>
				<td>$row->id_part</td>
				<td>$row->kuantitas</td>
				<td>$row->created_at</td>
				<td>$row->tipe_ahm</td>
				<td>$row->tgl_pemenuhan</td>
				<td> </td>
 			</tr>
	 	";	
 		}
	}else{
		echo "<td colspan='10' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>