<table id="example" class="table table-bordered table-hover">
  <thead>
    <tr>              
      <th width="5%">No</th>
      <th>Kode Part</th>              
      <th>Nama Part</th>
      <th>Qty PO</th>
      <th>Harga</th>      
      <th>Total Harga</th>        
    </tr>
  </thead>
  <tbody>            
    <?php 
    $no=1;
    foreach ($dt_paket->result() as $row) {
      $harga = $row->harga_md_dealer;
      $q = $qty * $row->qty_part;
      $tot = $q * $harga;
      $jum = $dt_paket->num_rows();
      echo "
      <tr>
        <td>$no</td>
        <td>$row->id_part</td>
        <td>$row->nama_part</td>
        <td>$q</td>
        <td>".number_format($harga, 0, ',', '.')."</td>
        <td>".number_format($tot, 0, ',', '.')."</td>
        <input type='hidden' name='jum' value='$jum'>
        <input type='hidden' name='harga_$no' value='$harga'>
        <input type='hidden' name='qty_$no' value='$q'>
        <input type='hidden' name='id_part_$no' value='$row->id_part'>
      </tr>
      ";
      $no++;
    }
    ?>
  </tbody>
</table>
