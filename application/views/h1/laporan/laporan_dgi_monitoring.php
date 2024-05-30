<?php
ini_set('date.timezone', 'Asia/Jakarta');
$date = date("dmY-Hi");
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_DGI-$date.xls");
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
				<th colspan="13" style="background-color: #c5e374;">Activity Log DGI Periode <?php echo tgl_indo($start_date) ." s/d ". tgl_indo($end_date) .'- '. date('H:i') . ' WIB';  ?></th>
			</tr>
			<tr>
				<th rowspan="2" style="background-color: #9bc6ec;">KODE DEALER</th>
				<th rowspan="2" style="background-color: #9bc6ec;">NAMA DEALER</th>
				<th colspan="7" style="background-color: #9bc6ec;" align="center">H1</th>
				<th colspan="4" style="background-color: #9bc6ec;" align="center">H23</th>
			</tr>
			
			<tr>
				<th style="background-color: #9bc6ec;">PROSPEK</th>
				<th style="background-color: #9bc6ec;">SPK</th>
				<th style="background-color: #9bc6ec;">HANDLE LEASING</th>
				<th style="background-color: #9bc6ec;">BILLING PROCESS</th>
				<th style="background-color: #9bc6ec;">DELIVERY PROCESS</th>
				<th style="background-color: #9bc6ec;">DOC. HANDLING</th>
				<th style="background-color: #9bc6ec;">UNIT INBOUND</th>
				<th style="background-color: #9bc6ec;">WO</th>
				<th style="background-color: #9bc6ec;">BILLING PROCESS</th>
				<th style="background-color: #9bc6ec;">PART SALES</th>
				<th style="background-color: #9bc6ec;">PART INBOUND</th>
			</tr>
		</thead>
		<thead>
			<?php
			foreach ($temp_data as $key => $val) {
			?>
				<tr>
					<th><?= $val->kode ?></th>
					<th align="left" class="width_dealer"><?= $val->nama ?></th>
					<th id="width" <?php if(is_null($val->prsp) && $val->kode !='13867'){ echo "style='background-color: yellow;'"; } ?>><?php if(is_null($val->prsp) && $val->kode !='13867') {echo '0';}else {echo $val->prsp;} ?></th>
					<th id="width" <?php if(is_null($val->spk) && $val->kode !='13867'){ echo "style='background-color: yellow;'"; } ?>><?php if(is_null($val->spk) && $val->kode !='13867')  {echo '0';}else {echo $val->spk;} ?></th>
					<th id="width" <?php if(is_null($val->lsng) && $val->kode !='13867'){ echo "style='background-color: yellow;'"; } ?>><?php if(is_null($val->lsng) && $val->kode !='13867') {echo '0';}else {echo $val->lsng;} ?></th>
					<th id="width" <?php if(is_null($val->inv1) && $val->kode !='13867'){ echo "style='background-color: yellow;'"; } ?>><?php if(is_null($val->inv1) && $val->kode !='13867') {echo '0';}else {echo $val->inv1;} ?></th>
					<th id="width" <?php if(is_null($val->bast) && $val->kode !='13867'){ echo "style='background-color: yellow;'"; } ?>><?php if(is_null($val->bast) && $val->kode !='13867') {echo '0';}else {echo $val->bast;} ?></th>
					<th id="width" <?php if(is_null($val->doch) && $val->kode !='13867'){ echo "style='background-color: yellow;'"; } ?>><?php if(is_null($val->doch) && $val->kode !='13867') {echo '0';}else {echo $val->doch;} ?></th>
					<th id="width" <?php if(is_null($val->uinb) && $val->kode !='13867'){ echo "style='background-color: yellow;'"; } ?>><?php if(is_null($val->uinb) && $val->kode !='13867') {echo '0';}else {echo $val->uinb;} ?></th>
					<th id="width" <?php if(is_null($val->pkb)){ echo "style='background-color: yellow;'"; } ?>><?php if(is_null($val->pkb))  {echo '0';}else {echo $val->pkb;} ?></th>
					<th id="width" <?php if(is_null($val->inv2)){ echo "style='background-color: yellow;'"; } ?>><?php if(is_null($val->inv2)) {echo '0';}else {echo $val->inv2;} ?></th>
					<th id="width" <?php if(is_null($val->prsl)){ echo "style='background-color: yellow;'"; } ?>><?php if(is_null($val->prsl)) {echo '0';}else {echo $val->prsl;} ?></th>
					<th id="width" <?php if(is_null($val->pinb)){ echo "style='background-color: yellow;'"; } ?>><?php if(is_null($val->pinb)) {echo '0';}else {echo $val->pinb;} ?></th>
				</tr>
			<?php
			}
			?>
		</thead>
	</table>
</body>




