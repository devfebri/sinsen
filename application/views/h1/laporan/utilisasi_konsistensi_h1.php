<?php
ini_set('date.timezone', 'Asia/Jakarta');
$date = date("dmY-Hi");
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=util_konsistensi-$date.xls");
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
				<th colspan="9" style="background-color: #c5e374;">Utilisasi dan Konsistensi Periode <?php echo tgl_indo($start_date) ." s/d ". tgl_indo($end_date) .' - '. date('H:i') . ' WIB';  ?></th>
			</tr>
			<!-- <tr>
				<th colspan="4"style="background-color: #9bc6ec;">Prospek</th>
				<th colspan="2"></th>
				<th colspan="4"style="background-color: #dbd0ab;">SPK</th>
			</tr> -->
			<tr>
				<th style="background-color: #9bc6ec;">Kode Dealer MD</th>
				<th style="background-color: #9bc6ec; width:300px;">Nama Dealer</th>
				<th style="background-color: #9bc6ec;">UINB</th>
				<th style="background-color: #9bc6ec;">PRSP</th>
				<th style="background-color: #9bc6ec;">SPK</th>
				<th style="background-color: #9bc6ec;">INV</th>
				<th style="background-color: #9bc6ec;">LSNG</th>
				<th style="background-color: #9bc6ec;">BAST</th>
				<th style="background-color: #9bc6ec;">DOCH</th>
			</tr>
		</thead>
		<thead>
			<?php
			// kendala : buat gmana prospek dan spk menjadi 1 row -> perbaikan tarikan query yg sdh di set per nama dealer
			foreach ($dealer as $key => $val) {
			?>
				<tr>
					<td>'<?php echo $val->kode_dealer_md ?> </td>
					<td><?php echo $val->nama_dealer ?> </td>
					<td><?php echo $penerimaan_unit[$val->id_dealer] ?> </td>
					<td><?php echo $prospek[$val->id_dealer] ?> </td>
					<td><?php echo $spk[$val->id_dealer] ?> </td>
					<td><?php echo $billing[$val->id_dealer] ?> </td>
					<td><?php echo $leasing[$val->id_dealer] ?> </td>
					<td><?php echo $delivery[$val->id_dealer] ?> </td>
					<td><?php echo $document[$val->id_dealer] ?> </td>
					
				</tr>
			<?php
			}
			?>
		</thead>
	</table>
</body>




