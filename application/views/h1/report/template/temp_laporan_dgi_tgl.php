<?php 
$no = date('dmY_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=LogDGI-".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

Penggunaan Fitur DGI AS OF  <?php echo date('d F Y - H:i') .' WIB <br>'; ?>
<br>

<table border="1">   	
 	<tr>
 		<td align="center" rowspan="3">No</td> 
 		<td align="center" rowspan="3">Kode Dealer</td> 		 		 		 		 		
 		<td align="center" rowspan="3">Nama Dealer</td> 
		<?php 
		$n = date("d");
		for($i=1; $i<=$n;$i++){ ?>	 		
 		<td align="center" colspan ="13"><?php echo $i . date("F Y"); ?></td>
		<?php } ?>
	</tr>
	<tr> 	
		<?php
		for($i=1; $i<=$n;$i++){ ?>			 		 		 		
 		<td align="center" colspan ="7">H1</td> 		 		 		 		 		
 		<td align="center" colspan ="6">H23</td>	
		<?php } ?>
 	</tr>
	<tr>
		<?php 
		for($i=1; $i<=$n;$i++){ 
		?>
		<td>UINB</td>
		<td>PRSP</td>
		<td>SPK</td>
		<td>LSNG</td>
		<td>INV1</td>
		<td>BAST</td>
		<td>DOCH</td>
		<td>PINB</td>
		<td>INV2</td>
		<td>PKB</td>
		<td>PRSL</td>
		<td>UNPAIDHLO</td>
		<td>DPHLO</td>
		<?php } ?>
	</tr>

 	<?php 
 	$urut=1;
    	if($list_data!='false'){
     		foreach ($list_data as $isi) {
     			echo "
         		<tr>
				<td>$urut</td>
				<td>$isi->kode_dealer_md</td>
				<td>$isi->nama_dealer</td>
				<td>$isi->tgl</td>
         		</tr>
     			";
     			$urut++;
     		}
 	}else{
		echo "<tr><td>Tidak ada data.</td></tr>";
	}
?> 
</table>