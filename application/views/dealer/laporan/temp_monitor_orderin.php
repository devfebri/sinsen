<?php 
$no = date("dmyHi");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=monitor_orderin_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">No SPK</td>
 		<td align="center">Tgl SPK</td>
 		<td align="center">Tgl Hasil Survey</td>
 		<td align="center">No KTP</td>
 		<td align="center">Nama Konsumen</td>
 		<td align="center">Pekerjaan</td>
 		<td align="center">Alamat</td>
 		<td align="center">Kelurahan</td>
 		<td align="center">Kecamatan</td>
 		<td align="center">Kabupaten/ Kota</td>
 		<td align="center">Item</td>
 		<td align="center">Deskripsi Motor</td>
 		<td align="center">Warna</td>
 		<td align="center">Harga OTR</td>
 		<td align="center">Cash/ Kredit</td>
 		<td align="center">Nama Finco</td>
 		<td align="center">DP Gross</td>
 		<td align="center">Voucer</td>
 		<td align="center">DP Stor</td>
 		<td align="center">Angsuran</td>
 		<td align="center">TOP</td>
 		<td align="center">Sales People</td>
 		<td align="center">Jabatan</td>
 		<td align="center">Status SPK</td>
 		<td align="center">Status Credit</td>
 		<td align="center">Alasan</td>
 		<td align="center">Time Service Ratio</td>
 	</tr>
 	<?php 	
		$no = 1;
		if($sql->num_rows()>0){	
			foreach($sql->result() as $isi){
			$item =$isi->id_tipe_kendaraan ."-".$isi->id_warna;
			$status_spk = ucfirst($isi->status);
			$status_credit = ucfirst($isi->status_credit);
			// if($status =='Close'){ $status ='Approved'; }
			
			$TSR = $isi->times;
			
			echo "
 				<tr>
 					<td>$no</td>
 					<td>$isi->no_spk</td>
 					<td>$isi->tgl_spk</td>
 					<td>$isi->tgl_hasil</td>
 					<td>'$isi->no_ktp</td>
 					<td>$isi->nama_konsumen</td>
 					<td>$isi->pekerjaan</td>
 					<td>$isi->alamat</td>
 					<td>$isi->kelurahan</td>
 					<td>$isi->kecamatan</td>
 					<td>$isi->kabupaten</td>
 					<td>$item</td>
 					<td>$isi->tipe_ahm</td>
 					<td>$isi->warna</td>
 					<td>$isi->harga_on_road</td>
 					<td>$isi->jenis_beli</td>
 					<td>$isi->finance_company</td>
 					<td>$isi->uang_muka</td>
 					<td>$isi->voucer</td>
 					<td>$isi->dp_stor</td>
 					<td>$isi->angsuran</td>
 					<td>$isi->tenor</td>
 					<td>$isi->nama_lengkap</td>
 					<td>$isi->jabatan</td>
 					<td>$status_spk</td>
 					<td>$status_credit</td>
 					<td>$isi->alasan</td>
 					<td>$TSR</td>
 				</tr>
 				";
 				$no++;
			}
		}
 	
 	?>
</table>


