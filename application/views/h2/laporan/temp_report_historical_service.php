<?php 
$bulan = date('m');
$nama_bulan = array(
	'01' => 'Januari',
	'02' => 'Februari',
	'03' => 'Maret',
	'04' => 'April',
	'05' => 'Mei',
	'06' => 'Juni',
	'07' => 'Juli',
	'08' => 'Agustus',
	'09' => 'September',
	'10' => 'Oktober',
	'11' => 'November',
	'12' => 'Desember');
$nama_bulan_indo = $nama_bulan[$bulan];
$tahun = date('Y');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=SINSEN Historical Service_".$nama_bulan_indo.$tahun.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		
        <td style="background-color:#002060; color:white" align="center"><b>No</b></td>
        <td style="background-color:#002060; color:white" align="center"><b>Kode MD</b></td>
        <td style="background-color:#002060; color:white" align="center"><b>Kode AHASS</b></td>
 		<td style="background-color:#002060; color:white" align="center"><b>No Rangka</b></td>
 		<td style="background-color:#002060; color:white" align="center"><b>No Mesin</b></td>
 		<td style="background-color:#002060; color:white" align="center"><b>No Polisi</b></td>
        <td style="background-color:#002060; color:white" align="center"><b>No WO/PKB</b></td>
        <td style="background-color:#002060; color:white" align="center"><b>Tanggal Wo/PKB</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>No NJB</b></td>
        <td style="background-color:#002060; color:white" align="center"><b>Tanggal NJB</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>No NSC</b></td>
        <td style="background-color:#002060; color:white" align="center"><b>Tanggal NSC</b></td>
        <td style="background-color:#002060; color:white" align="center"><b>Kode Type Motor (3 Digit)</b></td>
        <td style="background-color:#002060; color:white" align="center"><b>Waktu Pekerjaan Mulai</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>Waktu Pekerjaan Selesai</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>Kode Jenis Bayar</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>Flag Pembawa</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>Honda ID Mekanik</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>Nama Mekanik</b></td>
        <td style="background-color:#002060; color:white" align="center"><b>Honda ID SA</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>Nama SA</b></td>
        <td style="background-color:#002060; color:white" align="center"><b>Kode Alasan Datang ke AHASS</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>Kode Sumber Activity Promotion</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>Nama Sumber Activity Promotion</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>Kode Sumber Activity Capacity</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>Kilometer Motor</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>Keluhan Konsumen</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>Kesediaan Customer LCR</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>Alasan Tidak Bersedia LCR</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>Hasil Pengecekan</b></td>
        <td style="background-color:#002060; color:white" align="center"><b>Kode Jenis Pekerjaan</b></td>
        <td style="background-color:#002060; color:white" align="center"><b>No Claim</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>Detail Biaya Jasa</b></td>
        <td style="background-color:#002060; color:white" align="center"><b>Part Number</b></td>
        <td style="background-color:#002060; color:white" align="center"><b>Part Group</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>Deskripsi Part</b></td>
        <td style="background-color:#002060; color:white" align="center"><b>Quantity Part</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>Detail Biaya Parts Satuan</b></td>
        <td style="background-color:#002060; color:white" align="center"><b>Battery Serial Number</b></td>
		<td style="background-color:#002060; color:white" align="center"><b>Battery Percentage</b></td>
 	</tr>

<?php 
 	$nom=1;	
	if($report->num_rows()>0){
		foreach ($report->result() as $row) { 		
		$rowColor = (stripos(strtolower($row->keluhan_konsumen), 'fed') !== false || stripos(strtolower($row->keluhan_konsumen), 'federal') !== false) ? 'style="background-color: red;"' : '';

		if(stripos(strtolower($row->keluhan_konsumen), 'fed') !== false || stripos(strtolower($row->keluhan_konsumen), 'federal') !== false){
			$row->keluhan_konsumen='-';
		}


 		echo "
 			<tr>
 				<td>$nom</td>
 				<td>E20</td>
 				<td>'$row->kode_dealer_ahm</td>
				<td>$row->no_rangka</td>
				<td>$row->no_mesin</td>
 				<td>$row->no_polisi</td>
 				<td>$row->id_work_order</td>
				<td>$row->tgl_wo</td>
				<td>$row->no_njb</td>
 				<td>$row->tgl_njb</td>
				<td>$row->no_nsc</td>
 				<td>$row->tgl_nsc</td>
				<td>$row->id_tipe_kendaraan</td>
				<td>$row->waktu_mulai</td>
				<td>$row->waktu_selesai</td>
 				<td>$row->tipe_pembayaran</td>
				<td>$row->flag_pembawa</td>
 				<td>$row->honda_id_mekanik</td>
				<td>$row->nama_mekanik</td>
				<td>$row->honda_id_sa</td>
                <td>$row->nama_sa</td>
				<td>$row->kode_alasan_datang_ke_ahass</td>
 				<td>$row->activity_promotion</td>
				<td>$row->nama_activity_promotion</td>
 				<td>$row->activity_capacity</td>
				<td>$row->km_terakhir</td>
				<td>$row->keluhan_konsumen</td>
				<td>$row->deskripsi_kesediaan_customer</td>
				<td>$row->deskripsi_alasan_tidak_bersedia</td>
				<td>$row->deskripsi_hasil_pengecekan</td>
				<td>$row->kode_jenis_pekerjaan</td>
				<td>$row->no_claim_c2</td>
				<td>$row->subtotal_jasa</td>
                <td>'$row->id_part</td>
				<td>$row->kelompok_part</td>
                <td>$row->nama_part</td>
				<td>$row->qty</td>
                <td>$row->subtotal</td>
				<td>$row->serial_number_battery</td>
                <td>$row->soc</td>
			
 			</tr>
	 	";
 		$nom++;
 		}
	}else{
		echo "<td colspan='35' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>


