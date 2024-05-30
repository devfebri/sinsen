<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan Indent AHM_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
	<caption>Laporan Indent AHM Periode <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</caption>
 	<tr> 		
 		<td align="center" style="background-color:#d6d6c2"><b>No</b></td>
 		<td align="center" style="background-color:#d6d6c2"><b>No Indent</b></td>
 		<td align="center" style="background-color:#d6d6c2"><b>Kode Dealer</b></td>
 		<td align="center" style="background-color:#d6d6c2;width:380px"><b>Nama Dealer</b></td>
		<td align="center" style="background-color:#d6d6c2"><b>No SPK</b></td>
		<td align="center" style="background-color:#d6d6c2"><b>Nama Konsumen</b></td>
		<td align="center" style="background-color:#d6d6c2"><b>No KTP</b></td>
		<td align="center" style="background-color:#d6d6c2"><b>No HP</b></td>
		<td align="center" style="background-color:#d6d6c2"><b>Kode Tipe</b></td>
		<td align="center" style="background-color:#d6d6c2"><b>Kode Warna</b></td>
		<td align="center" style="background-color:#d6d6c2"><b>Tgl Konfirmasi Indent Logistik</b></td>
		<td align="center" style="background-color:#d6d6c2"><b>Tgl Prospek</b></td>
		<td align="center" style="background-color:#d6d6c2"><b>Tgl Deal</b></td>
		<td align="center" style="background-color:#d6d6c2"><b>Tgl Sales</b></td>
		<td align="center" style="background-color:#d6d6c2"><b>Status Indent MD</b></td>
		<td align="center" style="background-color:#d6d6c2"><b>Tgl Cancel Indent</b></td>
		<td align="center" style="background-color:#d6d6c2"><b>Status MFT</b></td>
 	</tr>
	<?php 
 	$no=1;	
	if($spk->num_rows()>0){
		foreach ($spk->result() as $row) { 		
 		echo "
 			<tr>
 				<td>$no</td>
 				<td>$row->id_indent</td>
 				<td>$row->kode_dealer_md</td>
 				<td>$row->nama_dealer</td>
				<td>$row->id_spk</td>
				<td>$row->nama_konsumen</td>
				<td>'$row->no_ktp</td>
				<td>$row->no_telp</td>
				<td>$row->id_tipe_kendaraan</td>
				<td>$row->id_warna</td>
				<td>$row->date_konfirmasi</td>
				<td>$row->date_prospek</td>
				<td>$row->date_deal</td>
				<td>$row->date_sales</td>
				<td>$row->status</td>
				<td>$row->date_cancel</td>
				<td>$row->status_indent</td>

 			</tr>
	 	";
 		$no++;
 		}
	}else{
		echo "<td colspan='17' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>


