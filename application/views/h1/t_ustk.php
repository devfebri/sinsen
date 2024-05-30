<?php 
function mata_uang2($a){
    return number_format($a, 0, ',', '.');
}
?>
<table id="example2" class="table table-hover table-bordered myTable1" width="100%">
  <tr>
    <th>No Faktur</th>
    <th>No Rangka</th>                    
    <th>No Mesin</th>
    <th>Nama Pemilik</th>
    <th>No KTP</th>
    <th>Kode Dealer</th>    
  </tr>
  <?php 
  foreach ($dt_detail->result() as $isi) {
    $nosin5 = substr($isi->no_mesin, 0,5);    
    echo "
    <tr>
      <td>$isi->no_bastd</td>
      <td>$isi->no_rangka</td>
      <td>$isi->no_mesin</td>    
      <td>$isi->nama_konsumen</td>
      <td>$isi->no_ktp</td>
      <td>$isi->kode_dealer_md</td>
    </tr>
    ";
  }
  ?>
</table>