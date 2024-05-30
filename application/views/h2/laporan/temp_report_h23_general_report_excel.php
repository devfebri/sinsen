<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=General Report ".$dealer->nama_dealer." Main Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php if($id_dealer != 'all'){ ?>
<table border="1">
		<caption>General Report <?php echo $dealer->nama_dealer ." ". $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</caption>
		<tr>
			<td><b>Nama AHASS</b></td>
			<td><?php echo $dealer->nama_dealer ?></td>
			<td><b>Periode Pelaporan</b></td>
			<td><?php echo $start_date ." s/d ".$end_date?></td>
		</tr>

		<tr>
			<td><b>Kode AHASS</b></td>
			<td>'<?php echo $dealer->kode_dealer_ahm ?></td>
			<td><b>Tanggal Pembuatan</b></td>
			<td><?php echo date('d-M-Y H:i')?> WIB</td>
		</tr>

		<tr>
			<td><b>Klasifikasi Channel</b></td>
			<td><?php echo $dealer->jenis_channel ?></td>
			<td><b>Jumlah Pit</b></td>
			<td>'<?php echo $dealer->jumlah_pit ?></td>
		</tr>

		<tr>
			<td><b>Alamat AHASS</b></td>
			<td><?php echo $dealer->alamat ?></td>
			<td><b>Jumlah Hari Kerja</b></td>
			<td>'<?php echo $hariKerja->row()->tgl ?></td>

		</tr>

		<tr>
			<td><b>Kota/Kabupaten</b></td>
			<td><?php echo $dealer->kabupaten ?></td>
			<td><b>Rata-Rata UE per Hari</b></td>
			<?php $rata2 = round($jumlahUE->row()->total_ue/$hariKerja->row()->tgl,2)?> 
       		<td>'<?php echo $rata2 ?></td>
		</tr>

        <tr>
			<td><b>Kecamatan</b></td>
			<td><?php echo $dealer->kecamatan ?></td>
			<td><b>Tahun Pengangkatan</b></td>
            <td>'<?php echo $dealer->tanggal_kerjasama ?></td>
		</tr>

        <tr>
			<td><b>No Telpon</b></td>
			<td>'<?php echo $dealer->no_telp ?></td>
			<td><b>Status Layanan AHASS</b></td>
			<td>Belum</td>
		</tr>

        <tr>
			<td><b>Jenis Channel</b></td>
			<td>Belum</td>
			<td><b>Jam Operasional</b></td>
			<td>Belum</td>
		</tr>

        <tr>
			<td><b>Struktur Organisasi</b></td>
			<td>Belum</td>
		</tr>

</table>
<?php }else{ ?>
	<table>
		<tr>
			<td colspan="4">Data All Dealer Tidak Tersedia</td>
		</tr>
	</table>
<?php }?>