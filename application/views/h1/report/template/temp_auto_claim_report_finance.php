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

	foreach ($table as $row => $field) { ?>
		<tr rowspan="2">
			<td><?=$field->kategori?></td>
		</tr>	

<?php }?>


</body>

