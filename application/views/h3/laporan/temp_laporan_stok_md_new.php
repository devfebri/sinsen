<?php
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Report Stock Main Dealer_" . $no . " WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">
	<tr>
		<td style="vertical-align : middle;text-align:center; background-color: #98f72a;"><b>No</b></td>
		<td style="vertical-align : middle;text-align:center; background-color: #98f72a;"><b>Kode Part</b></td>
		<td style="vertical-align : middle;text-align:center; background-color: #98f72a;"><b>Deskripsi Part</b></td>
		<td style="vertical-align : middle;text-align:center; background-color: #98f72a;"><b>HET</b></td>
		<td style="vertical-align : middle;text-align:center; background-color: #98f72a;"><b>Harga Beli</b></td>
		<td style="vertical-align : middle;text-align:center; background-color: #98f72a;"><b>Kel.Barang</b></td>
		<td style="vertical-align : middle;text-align:center; background-color: #98f72a;"><b>Status</b></td>
		<td style="vertical-align : middle;text-align:center; background-color: #98f72a;"><b>Stok</b></td>
		<td style="vertical-align : middle;text-align:center; background-color: #98f72a;"><b>Rak Default</b></td>
	</tr>
	
	<?php
	$nom = 1;
	if ($report->num_rows() > 0) {
		foreach ($report->result() as $row) {
		
			echo "
				<tr>
					<td>$nom</td>
					<td>'$row->id_part</td>
					<td>$row->nama_part</td>
					<td>$row->harga_dealer_user</td>
					<td>$row->harga_md_dealer</td>
					<td>$row->kelompok_part</td>
					<td>$row->status</td>
					<td>$row->qty</td>
					<td style='text-align:right;'>$row->kode_lokasi_rak</td>
				</tr>
			";
			$nom++;
		}
	} else {
		echo "<td colspan='9' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>