<?php 
function mata_uang2($a){
    return number_format($a, 0, ',', '.');
}
?>
<table id="example2" class="table table-hover table-bordered myTable1" width="100%">
  <tr>
    <th>No Mesin</th>
    <th>No Rangka</th>                    
    <th>Nama Konsumen</th>
    <th>No KTP</th>
    <th>Alamat</th>    
  </tr>
  <?php 
  
  foreach ($dt_detail->result() as $isi) {
    echo "
    <tr>
      <td>$isi->no_mesin</td>
      <td>$isi->no_rangka</td>
      <td>$isi->nama_konsumen</td>    
      <td>$isi->no_ktp</td>
      <td>$isi->alamat</td>
    </tr>
    ";
  }
  ?>
</table>