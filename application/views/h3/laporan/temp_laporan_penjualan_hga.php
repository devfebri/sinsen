<?php
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan Penjualan HGA dan Apparel_" . $no . " WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php $start_date_2 = date("d-m-Y", strtotime($start_date));
		$end_date_2 = date("d-m-Y", strtotime($end_date)); ?>
<table border="1">
	<?php if ($id_dealer != 'all') { ?>
		<caption><b>Laporan Penjualan HGA dan Apparel
				<br> <?php echo $laporan_penjualan_hga->row()->nama_dealer ?> </b> <br><br></caption>
	<?php } else { ?>
		<caption><b>Laporan Penjualan HGA dan Apparel
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
		<td style="vertical-align : middle;text-align:center;"><b>PACC</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>ACCEC </b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Helm</b></td>
	</tr>
	<?php
	$nom = 1;
	$sum_pacc = 0;
	$sum_accec = 0;
	$sum_helm = 0;
	if ($laporan_penjualan_hga->num_rows() > 0) {
		foreach ($laporan_penjualan_hga->result() as $row) {
			echo "
				<tr>
					<td>$nom</td>
					<td>$row->nama_dealer</td>
					<td>$row->status</td>
					<td>$row->kabupaten</td>
					<td>$row->nama_lengkap</td>
					<td>". number_format($row->pacc, 0, ',', '.') ."</td>
					<td>". number_format($row->accec, 0, ',', '.') ."</td>
					<td>". number_format($row->helm, 0, ',', '.') ."</td>
				</tr>
			";
			$nom++;
			$sum_pacc += $row->pacc;
			$sum_accec += $row->accec;
			$sum_helm += $row->helm;
		}
		echo "
				<tr>
					<td style='vertical-align : middle;text-align:center;' colspan='5'><b>Total</b></td>
					<td><b>". number_format($sum_pacc, 0, ',', '.') ."</b></td>
					<td><b>". number_format($sum_accec, 0, ',', '.') ."</b></td>
					<td><b>". number_format($sum_helm, 0, ',', '.') ."</b></td>
				</tr>
			";
	} else {
		echo "<td colspan='8' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>