<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Stock Level_Details_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : Stock Level_Details <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Kode Dealer</b></td>
 		<td align="center"><b>Nama Dealer</b></td>
		<td align="center"><b>Channel</b></td>
 		<td align="center"><b>Jenis Service</b></td>
		<td align="center"><b>Jenis Kelompok</b></td>
 		<td align="center"><b>Grup Parts</b></td>
		<td align="center"><b>Deskripsi Parts</b></td>
 		<td align="center"><b>Kuantitas</b></td>
 	</tr>
	 <?php 
	if($sl_details->num_rows()>0){
		foreach ($sl_details->result() as $row) { 

		echo "
 			<tr>
 				<td>$row->kode_dealer_md</td>
				<td>$row->nama_dealer</td>
				<td>$row->channel_h123</td>
				<td>$row->referensi</td>
				<td>$row->klp_part</td>
				<td>$row->kelompok_part</td>
				<td>$row->nama_part</td>
				<td>$row->qty</td>
 			</tr>
	 	";	
 		}
	}else{
		echo "<td colspan='8' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>