<?php
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan Penjualan Part Selling_" . $no . " WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php $start_date_2 = date("d-m-Y", strtotime($start_date));
		$end_date_2 = date("d-m-Y", strtotime($end_date)); ?>
<table border="1">
	<?php if ($id_dealer != 'all') { ?>
		<caption><b>Laporan Penjualan Parts berdasarkan Selling	Price
				<br> <?php echo $laporan_penjualan_part_selling->row()->nama_dealer ?> </b> <br><br></caption>
	<?php } else { ?>
		<caption><b>Laporan Penjualan Parts berdasarkan Selling	Price
				<br> Periode <?php echo $start_date_2 . " s/d " . $end_date_2 ?>
			</b><br><br></caption>
	<?php } ?>



	<tr>
		<td style="vertical-align : middle;text-align:center;"><b>No</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Nama Customer </b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Status</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Kabupaten</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Salesman</b></td>
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
		<td align="center"><b><?php echo $bulan ?></b></td>
	</tr>
	<?php
	$nom = 1;
	$sum_total =0;
	if ($laporan_penjualan_part_selling->num_rows() > 0) {
		foreach ($laporan_penjualan_part_selling->result() as $row) {
			echo "
				<tr>
					<td>$nom</td>
					<td>$row->nama_dealer</td>
					<td>$row->status</td>
					<td>$row->kabupaten</td>
					<td>$row->nama_lengkap</td>
					<td align='right'>" . number_format($row->total, 0, ',', '.') ."</td>
				</tr>
			";
			$nom++;
			$sum_total += $row->total;
		}
		echo "
				<tr>
					<td align='center' colspan='5'><b>Total</b></td>
					<td align='right'><b>" . number_format($sum_total, 0, ',', '.') ."</b></td>
				</tr>
			";
	} else {
		echo "<td colspan='5' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>