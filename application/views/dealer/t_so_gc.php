<table class="table table-bordered table-hover myTable1">
  <thead>
    <tr>
      <th>Tipe Kendaraan</th>
      <th>Warna</th>
      <th>Qty</th>            
    </tr>
  </thead>
  <tbody>
    <?php     
    foreach ($detail->result() as $isi) {
      echo "
      <tr>
        <td>$isi->id_tipe_kendaraan | $isi->tipe_ahm</td>
        <td>$isi->id_warna | $isi->warna</td>
        <td>$isi->qty</td>        
      </tr>";      
    }
    ?>    
  </tbody>
</table>