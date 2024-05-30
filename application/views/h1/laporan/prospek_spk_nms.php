<?php
ini_set('date.timezone', 'Asia/Jakarta');
$date = date("dmY-Hi");
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=prospek_spk-$date.xls");
?>

<!DOCTYPE html>
<html lang="en">

<style>
	th {
		font-weight: normal;
	}

	#width {
		width: 75px;
	}

	.width_dealer{
		width: 280px;
	}

</style>


<body>
	<table id="tableID" class="display" border="1" align="center">
		<thead>
			<tr align="center">
				<th colspan="5" style="background-color: #c5e374;">Prospek & SPK Periode <?php echo tgl_indo($start_date) ." s/d ". tgl_indo($end_date) .'- '. date('H:i') . ' WIB';  ?></th>
			</tr>
			<!-- <tr>
				<th colspan="4"style="background-color: #9bc6ec;">Prospek</th>
				<th colspan="2"></th>
				<th colspan="4"style="background-color: #dbd0ab;">SPK</th>
			</tr> -->
			<tr>
				<th style="background-color: #9bc6ec;">Kode Dealer AHM</th>
				<th style="background-color: #9bc6ec;">Kode Dealer MD</th>
				<th style="background-color: #9bc6ec;">Nama Dealer</th>
				<th style="background-color: #9bc6ec;">Total Prospek</th>
				<th style="background-color: #dbd0ab;">Total SPK</th>
			</tr>
		</thead>
		<thead>
			<?php
			// kendala : buat gmana prospek dan spk menjadi 1 row -> perbaikan tarikan query yg sdh di set per nama dealer
			foreach ($temp_data as $key => $val) {
			?>
				<tr>
					<td>'<?php echo $val->kode_dealer_ahm?> </td>
					<td>'<?php echo $val->kode_dealer_md?> </td>
					<td><?php echo $val->nama_dealer?> </td>
					<td><?php echo $val->total_prospek?> </td>
					<td><?php echo $val->total_spk?> </td>
				</tr>
			<?php
			}
			?>
		</thead>
	</table>
</body>




