<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data Prospek.xls");
?>
<h2>Data Prospek</h2>

<p>Dari tanggal : <?php echo $tgl1 ?> sampai tanggal : <?php echo $tgl2 ?></p>

<table border="1">
	<tr>
		<td rowspan="2">No</td>
		<td rowspan="2">Tgl Prospek</td>
		<td rowspan="2">Nama Konsumen</td>
		<td rowspan="2">Alamat</td>
		<td rowspan="2">No Hp</td>
		<td rowspan="2">Jenis Kelamin</td>
		<td rowspan="2">Sumber Prospek</td>
		<td rowspan="2">Tipe Motor</td>
		<td>Status</td>
		<td rowspan="2">Sales People</td>
		<td colspan="2">Follow Up Ke 1</td>
		<td colspan="2">Follow Up Ke 2</td>
		<td colspan="2">Follow Up Ke 3</td>
	</tr>

	<tr>
		<td>Hot/Med/Low/Deal/No Deal</td>
		<td>Tgl Follow Up</td>
		<td>Hasil</td>
		<td>Tgl Follow Up</td>
		<td>Hasil</td>
		<td>Tgl Follow Up</td>
		<td>Hasil</td>
	</tr>

	<?php 
	$no = 1;
	// $sql = "SELECT a.*, b.description as sumber_prospek_desc FROM tr_prospek a join ms_sumber_prospek b on a.sumber_prospek = b.id_dms WHERE a.id_dealer='$id_dealer' and a.created_at BETWEEN '$tgl1 00:59:59' AND '$tgl2 23:59:59'";
	$sql = "SELECT a.created_at, a.nama_konsumen, a.alamat, a.no_hp, a.jenis_kelamin, a.id_karyawan_dealer, a.id_prospek, a.status_prospek, b.description as sumber_prospek_desc , c.tipe_ahm FROM tr_prospek a join ms_sumber_prospek b on a.sumber_prospek = b.id_dms left join ms_tipe_kendaraan c on a.id_tipe_kendaraan = c.id_tipe_kendaraan WHERE a.id_dealer='$id_dealer' and a.created_at BETWEEN '$tgl1 00:59:59' AND '$tgl2 23:59:59'";
	
	foreach ($this->db->query($sql)->result() as $rw): 
		//log_r($this->db->last_query());
		?>
		<tr>
			<td><?php echo $no; ?></td>
			<td><?php echo $rw->created_at ?></td>
			<td><?php echo $rw->nama_konsumen ?></td>
			<td><?php echo $rw->alamat ?></td>
			<td><?php echo $rw->no_hp ?></td>
			<td><?php echo $rw->jenis_kelamin ?></td>
			<td><?php echo $rw->sumber_prospek_desc ?></td>
			<td><?php echo $rw->tipe_ahm ?></td>
			<td><?php echo $rw->status_prospek ?></td>
			<td><?php echo get_data('ms_karyawan_dealer','id_karyawan_dealer',$rw->id_karyawan_dealer,'nama_lengkap') ?></td>
			<?php foreach ($this->db->get_where('tr_prospek_fol_up', ['id_prospek'=>$rw->id_prospek])->result() as $key => $value): ?>
				<td><?php echo $value->tgl_fol_up ?></td>
				<td><?php echo $value->keterangan ?></td>
			<?php endforeach ?>
			

		</tr>
	<?php $no++; endforeach ?>
</table>