<?php 
//$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=LokasiUnit.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
    <td align="center">Lokasi</td>
 		<td align="center">Kode Tipe Kendaraan</td>
 		<td align="center">Qty Kapasitas</td>
    <td align="center">Qty</td> 		
 	</tr>
 	<?php  	
 	$no=1;  
  $sql = $this->db->query("SELECT * FROM ms_lokasi_unit");
  foreach ($sql->result() as $isi) {    
    echo "
    <tr>
      <td>$isi->id_lokasi_unit</td>
      <td>$isi->tipe_dedicated</td>
      <td>$isi->qty</td>
      <td>$isi->isi</td>      
    </tr>
    ";
    $no++;
  }
 	?>
</table>
 