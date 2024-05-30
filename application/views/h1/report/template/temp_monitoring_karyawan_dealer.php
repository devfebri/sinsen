<?php 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Monitoring_karyawan_dealer.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
Main Dealer: PT. Sinar Sentosa Primatama <br>
Tanggal Cetak: <?php echo date("d/m/Y") ?> <br>
Report Monitoring Karyawan Dealer <br>
<table border="1">  
 	<tr> 		 		
 		<td align="center">Honda ID</td>
 		<td align="center">Nama</td>
 		<td align="center">Kode Dealer</td> 		 		
 		<td align="center">Nama Dealer</td> 		 		
 		<td align="center">Jabatan</td> 		 		 		
 		<td align="center">Tempat Lahir</td> 		 		 		
 		<td align="center">Tgl Lahir</td> 		 		 		
 		<td align="center">Jenis Kelamin</td> 		 		 		
 		<td align="center">Pendidikan</td> 		 		 		
 		<td align="center">Agama</td> 		 		 		
 		<td align="center">No HP</td> 		 		 		
 		<td align="center">Email</td> 		 		 		
 		<td align="center">Tanggal Masuk</td> 		 		 		
 		<td align="center">Status</td> 		 		 		
 	</tr>
 	<?php  	
 	$no=1;
 	$sql = $this->db->query("SELECT * FROM ms_karyawan_dealer 
 		LEFT JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer
 		LEFT JOIN ms_jabatan ON ms_karyawan_dealer.id_jabatan = ms_jabatan.id_jabatan
 		LEFT JOIN ms_agama ON ms_karyawan_dealer.id_agama = ms_agama.id_agama");
 	foreach ($sql->result() as $isi) { 		 		
 		$active = ($isi->active == 1) ? "Aktif" : "Tidak Aktif" ;
 		echo "
 		<tr>
 			<td>$isi->id_flp_md</td>
 			<td>$isi->nama_lengkap</td> 			 			 			
 			<td>$isi->kode_dealer_md</td> 			 			 			
 			<td>$isi->nama_dealer</td> 			 			 			
 			<td>$isi->jabatan</td> 			 			 			
 			<td>$isi->tempat_lahir</td> 			 			 			
 			<td>$isi->tgl_lahir</td> 			 			 			
 			<td>$isi->jk</td> 			 			 			
 			<td></td> 			 			 			
 			<td>$isi->agama</td> 			 			 			
 			<td>$isi->no_hp</td> 			 			 			
 			<td>$isi->email</td> 			 			 			
 			<td>$isi->tgl_masuk</td> 			 			 			
 			<td>$active</td> 			 			 			
 		</tr>
 		";
 		$no++; 	
 	}
 	?>
</table>
