<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Reporting Follow Up H23 Dealer".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
	<caption>Reporting Follow Up H23 Dealer <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</caption>
 	<tr> 		
 		<td align="center"><b>No</b></td>
 		<td align="center" style="width:180px" ><b>No.Mesin</b></td>
 		<td align="center"><b>Nama Customer</b></td>
 		<td align="center"><b>M/C Type</b></td>
		<td align="center"><b>Tahun Motor</b></td>
		<td align="center" ><b>Frekuensi Service</b></td>
		<td align="center" ><b>Waktu Service Terakhir</b></td>
		<td align="center" ><b>Average Rp/UE</b></td>
		<td align="center" ><b>Profesi</b></td>
		<td align="center" ><b>ID Status FU</b></td>
		<td align="center" ><b>Status FU</b></td>
		<td align="center" ><b>Waktu FU Terakhir</b></td>
		<td align="center" ><b>Nama PIC Follow Up</b></td>
		<td align="center" ><b>Media Follow Up</b></td>
		<td align="center" ><b>Tanggal Next Follow Up</b></td>
		<td align="center" ><b>Tanggal Booking Service</b></td>
		<td align="center" ><b>Tanggal Actual Service</b></td>
		<td align="center" ><b>Biaya Actual Service</b></td>
 	</tr>

<?php 
 	$nom=1;	
	if($report->num_rows()>0){
		foreach ($report->result() as $row) {
			$data_update = $this->db->query("SELECT (case when a.id_kategori_status_komunikasi='1' then 'Unreachable' when a.id_kategori_status_komunikasi='2' then 'Failed'
			when a.id_kategori_status_komunikasi='3' then 'Rejected'
			when a.id_kategori_status_komunikasi='4' then 'Contacted' END) as status_komunikasi, b.nama_lengkap,
			(case when a.id_media_kontak_fol_up='1' THEN 'Telepon'
			when a.id_media_kontak_fol_up='2' THEN 'Telepon/WA Call'
			when a.id_media_kontak_fol_up='3' THEN 'WA'
			when a.id_media_kontak_fol_up='4' THEN 'SMS'
			when a.id_media_kontak_fol_up='5' THEN 'Visit'
			when a.id_media_kontak_fol_up='6' THEN 'Facebook'
			when a.id_media_kontak_fol_up='7' THEN 'Instagram'
			when a.id_media_kontak_fol_up='8' THEN 'Telegram'
			when a.id_media_kontak_fol_up='9' THEN 'Twitter'
			when a.id_media_kontak_fol_up='10' THEN 'Email' END) as media_kontak 
			FROM tr_h2_fol_up_detail a
			LEFT JOIN ms_karyawan_dealer b on a.id_karyawan_dealer=b.id_karyawan_dealer
			WHERE a.id_follow_up = '$row->id_follow_up' and a.id_kategori_status_komunikasi='$row->id_kategori_status_komunikasi'")->row(); 
 		echo "
 			<tr>
 				<td>$nom</td>
 				<td>$row->no_mesin</td>
				<td>$row->nama_customer</td>
 				<td>$row->tipe_ahm</td>
 				<td>$row->tahun_produksi</td>
 				<td>$row->frekuensi_servis</td>
				<td>$row->months</td>
 				<td>$row->total_jasa</td>
				<td>$row->pekerjaan</td>
 				<td>$row->id_kategori_status_komunikasi</td>
				<td>$data_update->status_komunikasi</td>
				<td>$row->tgl_fol_up</td>
				<td>$data_update->nama_lengkap</td>
				<td>$data_update->media_kontak</td>
				<td>$row->tgl_next_fol_up</td>
				<td>$row->tgl_booking_service</td>
				<td>$row->tgl_actual_service</td>
				<td>$row->biaya_actual_service</td>

 			</tr>
	 	";
 		$nom++;
 		}
	}else{
		echo "<td colspan='17' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>


