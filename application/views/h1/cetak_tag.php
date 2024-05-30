<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->
	<?php 
		function mata_uang($a){
		if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
		if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
			return number_format($a, 0, ',', '.');
		} ?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Cetak Kwitansi</title>
	<style>
		@media print {
			@page {
				sheet-size: 74mm 105mm;
				margin-left: 0.8mm;
				margin-right: 0.5mm;
				margin-bottom: 0.5mm;
				margin-top: 0.5mm;
			}
			.text-center{text-align: center;}
			.table {
					width: 100%;
					max-width: 100%;
					border-collapse: collapse;
				   /*border-collapse: separate;*/
				}
			.table-bordered tr td {
					border: 1px solid black;
					padding-left: 6px;
					padding-right: 6px;
				}
			body{
				font-family: "Times";
				font-size: 8pt;
			}
		}
	</style>
</head>
<body>
	<table border="0" width="100%">
		<tr>
			<td align="center">REPAIR TAG</td>
		</tr>
	</table>
	<hr>
	<table width="90%" border="0">
		<tr>
			<td width="30%">Tgl Masuk</td>
			<td><?php echo $tgl_checker ?></td>
		</tr>
		<tr>
			<td>No.Pol</td>
			<td><?php echo $header->no_polisi ?></td>
		</tr>
		<tr>
			<td>Ekspedisi</td>
			<td><?php echo $header->ekspedisi ?></td>
		</tr>
		<tr>
			<td>No Urut</td>
			<td><?php echo $header->id_checker ?></td>
		</tr>
		<tr>
			<td>No Mesin</td>
			<td><?php echo $header->no_mesin ?></td>
		</tr>
	</table>
	<br>
	Kerusakan
	<table width="90%" align="center">
		<tr>
			<td align="center">Kode Part</td>
			<td align="center">Deskripsi</td>
			<td align="center">Gejala</td>
		</tr>
		<?php 
		$sql = $this->m_admin->getByID("tr_checker_detail","id_checker",$id_checker);
		foreach ($sql->result() as $isi){ 
			echo "
			<tr>
				<td>$isi->id_part</td>
				<td>$isi->deskripsi</td>
				<td>$isi->gejala</td>
			</tr>
			";
		}
		?>
	</table>
	
</body>
</html>