<?php 
//$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=LokasiUnitKosong.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">Kode Tipe Kendaraan</td>
    <td align="center">Lokasi</td>
 		<td align="center">Slot</td>    
 	</tr>
 	<?php  	 	
  $sql = $this->db->query("SELECT * FROM ms_lokasi_unit");
  foreach ($sql->result() as $isi) {    
    for ($i=1; $i <= $isi->qty; $i++) {       
      $cek_slot2 = $this->db->query("SELECT * FROM tr_scan_barcode WHERE lokasi = '$isi->id_lokasi_unit' AND slot = '$i' 
        AND (status = 1 OR status = 2)");
      if($cek_slot2->num_rows() == 0){
        echo "
        <tr>
          <td>$isi->tipe_dedicated</td>
          <td>$isi->id_lokasi_unit</td>
          <td>$i</td>      
        </tr>
        ";
      }
    }    
  }
 	?>
</table>
 