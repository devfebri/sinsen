<?php
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan Penjualan Oil Botol_" . $no . " WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php $start_date_2 = date("d-m-Y", strtotime($start_date));
		$end_date_2 = date("d-m-Y", strtotime($end_date)); ?>
<table border="1">
	<?php if ($id_dealer != 'all') { ?>
		<caption><b>Laporan Penjualan AHM Oil (Botol)
				<br> <?php echo $laporan_penjualan_oil_botol->row()->nama_dealer ?> </b> <br><br></caption>
	<?php } else { ?>
		<caption><b>Laporan Penjualan AHM Oil (Botol)
				<br> Periode <?php echo $start_date_2 . " s/d " . $end_date_2 ?>
			</b><br><br></caption>
	<?php } ?>



	<tr>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>No</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>Nama Customer </b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>Status</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>Kabupaten</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>Salesman</b></td>
		<?php
		$bulan = '';
		$start_date_bulan = date("m", strtotime($start_date));
		$end_date_bulan = date("m", strtotime($end_date));

		if ($start_date_bulan == $end_date_bulan) {
			setlocale(LC_TIME, 'id_ID');
			$bulan = date('F-y', strtotime($start_date));
		} else {
			$bulan = $start_date_2 . " s/d " . $end_date_2;
		}
		?>
		<td align="center" colspan="3"><b><?php echo $bulan ?></b></td>
	</tr>
	<tr>
		<td align="center"><b>Oli</b></td>
		<td align="center"><b>GMO</b></td>
		<td align="center"><b>Total</b></td>
	</tr>
	<?php
	$nom = 1;
	$sum_oil = 0;
	$sum_gmo = 0;
	$sum_total = 0;
	if ($laporan_penjualan_oil_botol->num_rows() > 0) {
		foreach ($laporan_penjualan_oil_botol->result() as $row) {
			$total = 0;
			$total = $row->oil+$row->gmo;
			echo "
				<tr>
					<td>$nom</td>
					<td>$row->nama_dealer</td>
					<td>$row->status</td>
					<td>$row->kabupaten</td>
					<td>$row->nama_lengkap</td>
					<td align='right'>$row->oil</td>
					<td align='right'>$row->gmo</td>
					<td align='right'>$total </td>
				</tr>
			";
			$nom++;
			$sum_oil += $row->oil;
			$sum_gmo += $row->gmo;
			$sum_total += $total;
		}
		echo "
				<tr>
					<td align='center' colspan='5'><b>Total</b></td>
					<td align='right'><b>$sum_oil</b></td>
					<td align='right'><b>$sum_gmo</b></td>
					<td align='right'><b>$sum_total</b></td>
				</tr>
			";
	} else {
		echo "<td colspan='8' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>