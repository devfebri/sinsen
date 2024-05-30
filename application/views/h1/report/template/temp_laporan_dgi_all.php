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
 		<td align="center" rowspan="2">No</td> 
 		<td align="center" rowspan="2">Kode Dealer</td> 		 		 		 		 		
 		<td align="center" rowspan="2">Nama Dealer</td> 		 		 		 		 		
 		<td align="center" colspan ="2">H1</td> 		 		 		 		 		
 		<td align="center" colspan ="2">H23</td>	
 	</tr>
	<tr>
		<td>Hit</td>
		<td>n Data</td>
		<td>Hit</td>
		<td>n Data</td>
	</tr>

 	<?php 
 	$urut=1;
    	if($list_data!='false'){
     		foreach ($list_data as $isi) {
			$hit_h1 = number_format($isi->h1_hit,0,",",".");
			$data_h1 = number_format($isi->h1_data,0,",",".");
			$hit_h23 = number_format($isi->h23_hit,0,",",".");
			$data_h23 = number_format($isi->h23_data,0,",",".");

     			echo "
         		<tr>
				<td>$urut</td>
				<td>$isi->kode_dealer_md</td>
				<td>$isi->nama_dealer</td>
				<td>$hit_h1</td>
				<td>$data_h1</td>
				<td>$hit_h23</td>
				<td>$data_h23</td>
         		</tr>
     			";
     			$urut++;
     		}
 	}else{
		echo "<tr><td>Tidak ada data.</td></tr>";
	}
?> 
</table>