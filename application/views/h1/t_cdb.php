<?php 
function mata_uang2($a){
    	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
    return number_format($a, 0, ',', '.');
}
?>
<table id="example2" class="table table-hover table-bordered myTable1" width="100%">
  <tr>
    <th>No</th>
    <th>No Mesin</th>
    <th>No Rangka</th>                    
    <th>Nama Konsumen</th>
    <th>No KTP</th>
    <th>Alamat</th>    
  </tr>
  <?php 
  $no=1;
  foreach ($dt_detail->result() as $isi) {
    echo "
    <tr>
      <td>$no</td>
      <td>$isi->no_mesin</td>
      <td>$isi->no_rangka</td>
      <td>$isi->nama_konsumen</td>    
      <td>$isi->no_ktp</td>
      <td>$isi->alamat</td>
    </tr>
    ";
    $no++;
  }

  foreach ($dt_detail_gc->result() as $isi) {
    echo "
    <tr>
      <td>$no</td>
      <td>$isi->no_mesin</td>
      <td>$isi->no_rangka</td>
      <td>$isi->nama_konsumen</td>    
      <td>$isi->no_ktp</td>
      <td>$isi->alamat</td>
    </tr>
    ";
    $no++;
  }
  ?>
  <?php if ($dt_detail_gc->num_rows()==0 AND $dt_detail->num_rows()==0): ?>
    <tr>
      <td colspan="6" align="center">
        Data Kosong
      </td>
    </tr>
  <?php endif ?>
</table>