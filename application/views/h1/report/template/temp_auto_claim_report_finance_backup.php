<?php 
// $no = $start_periode." - ".$end_periode;
// header("Content-type: application/octet-stream");
// header("Content-type: application/vnd-ms-excel");
// header("Content-Disposition: attachment; filename=Auto Claim - Report Dealer" .$no.".xls");
?>

<!DOCTYPE html>
<html lang="en">

<style>

	
.hidden {
  display: none;
}

.text-bold{
	font-weight: bold;
}


.batas {
	height: 20px;
	background-color: green;
}


	th {
		font-weight: normal;
	}

	#width {
		width: 75px;
	}





.width_dealer{
		width: 480px;
	}
</style>


<body>
<table>  
	<tr>
		<td> <b>Summary LPJ Klaim - Report Finance</b></td>
	</tr>

	<tr>
		<td >Periode </td>
		<td ><?php //$start_periode." - ".$end_periode?></td>
	</tr>

</table>

	<table border="1">  
	<tr>
		<td>Segmen</td>
		<td>Type</td>
		<td>Approved by PIM</td>
		<td>Approved to Dlr</td>
		<td>Kontribusi AHM:</td>
		<td>Tagih ke AHM:</td>
		<td>No. Surat</td>
		<td>Total Unit Approved by PIM</td>
		<td>Total Tagih ke AHM</td>
		<td>Total Tagih ke AHM (Cr+Cs)inc PPN 10%</td>
		<td>Tgl Cair</td>
		<td>Nilai Cair</td>
	</tr>

	<?php  	

	foreach ($table as $row => $field) { 

		$data['program'] = $this->db->query("SELECT COUNT(1) as jumlah, spk.jenis_beli , spk.program_umum ,spk.program_gabungan ,
		tk.id_kategori  from tr_claim_dealer cd join tr_sales_order so on so.id_sales_order = cd.id_sales_order 
		join tr_spk spk on spk.no_spk = so.no_spk 
		join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = spk.id_tipe_kendaraan 
		WHERE cd.status  ='approved'
		AND spk.tgl_spk BETWEEN '2023-03-01' AND '2023-03-30'
		AND tk.id_kategori ='T'
		GROUP by 
		spk.program_umum,
		spk.program_gabungan,
		tk.id_kategori,
		spk.jenis_beli
		")->result();

		$row_span=count($data['program']);

		foreach ($data['program'] as $key => $value) { 
			?>
			<tr>
				<?php ?>
				<th rowspan="2">Program <?=$value->$jenis_beli ?> </th>
				<td>Cash</td>
				<td><?=$value->jumlah?></td>
			</tr>

			<tr>
				<td>Kredit</td>
				<td><?=$value->jumlah?></td>
			</tr>	
		

		<?}
		
		
		?>

		

		<?/*

		<tr>
			<td rowspan="3"><?= $field->kategori?></td>
			<?php ?>
			<th rowspan="2">Program <?= $key?></th>
			<td>Cash</td>
		</tr>

		<tr>
			<td>Kredit</td>
		</tr>	
		
		<tr>
			<td class="text-bold">Total <?= $field->kategori?></td>
			<td></td>
		</tr>	

		<tr>
			<td class="batas" colspan="12"></td>
		</tr>

		*/?>


		

	<?php 
}?>


</body>

