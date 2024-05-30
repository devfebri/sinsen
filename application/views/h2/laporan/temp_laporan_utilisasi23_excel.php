<?php
ini_set('date.timezone', 'Asia/Jakarta');
$date = date("dmY-Hi");
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Utilisasi_H23_-$date.xls");
?>

<!DOCTYPE html>
<html lang="en">

<style>
	th {
		font-weight: normal;
	}

	#width {
		width: 70px;
	}

</style>


<body>
	<table id="tableID" class="display" border="1" align="center">
		<thead>
			<tr align="center">
				<th colspan="8"><?= $subjudul?></th>
				
			</tr>
			<tr>		
				<th>No</th>
				<th>Kode Dealer</th>
				<th style="width:380px">Nama Dealer</th>
				<th>Apps (WO)</th>
				<th>WO</th>
				<th>Billing Process</th>
				<th>Part Sales</th>
				<th>Part Inbound</th>
			</tr>

		</thead>
		<thead>
			<?php
			$i=1;
			foreach ($util as $dd) {
			
			?>	
				<tr>
					<td><?= $i++?></td>
					<td><?= $dd->kode_dealer?></td>
					<td style="width:380px"><?= $dd->nama_dealer?></td>
					<td><?= $dd->wosc?></td>
					<td><?= $dd->wo?></td>
					<td><?= $dd->bil?></td>
					<td><?= $dd->part?></td>
					<td><?= $dd->inbound?></td>
				</tr>
			
			<?php 
			}
			?>	
		</thead>
	</table>
</body>




