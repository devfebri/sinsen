<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$nama_file."_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
    <td align="center">No</td>
    <td align="center">Tgl Shipping List</td>
    <td align="center">No Shipping List</td>
 		<td align="center">Kode Item</td>
    <td align="center">Ekspedisi</td>
    <td align="center">No Polisi</td>      
    <?php
      if($count_unit == 1){
    ?>
      <td align="center">Jumlah Unit</td>      
    <?php
      }
    ?>  
 	</tr>
 	<?php  	 	 
  $no=1;   
  
  foreach ($sql->result() as $isi) {            
    $bulan_s = substr($isi->tgl_sl, 2,2);
    $tahun_s = substr($isi->tgl_sl, 4,4);
    $tgl_s = substr($isi->tgl_sl, 0,2);
    $tanggal_sl = $tgl_s."/".$bulan_s."/".$tahun_s;
    echo "
    <tr>
      <td>$no</td>                        
      <td>$tanggal_sl</td>
      <td>$isi->no_shipping_list</td>
      <td>$isi->id_modell</td>
      <td>$isi->ekspedisi</td>
      <td>$isi->no_polisi</td>
      ";
      if($count_unit == 1){
      echo "<td>".$isi->jumlah."</td>";      
  
      }

    echo "
    </tr>
    ";
    $no++;    
  }
 	?>
</table>
 