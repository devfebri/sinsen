<button class="btn btn-primary btn-block btn-flat" disabled>Detail Unit</button>
<table id="" class="table table-bordered table-hover">
  <thead>
    <tr>                    
      <th width="15%">No Mesin</th>              
      <th width="15%">No Rangka</th>                                      
      <th width="15%">Tipe</th>              
      <th width="10%">Warna</th>
      <th width="10%">Lokasi</th>                                                    
    </tr>
  </thead>
  <tbody>
    <?php 
    foreach ($dt_scan->result() as $isi) {    
      echo "
      <tr>
        <td>$isi->no_mesin</td>
        <td>$isi->no_rangka</td>
        <td>$isi->tipe_ahm</td>
        <td>$isi->warna</td>
        <td>$isi->lokasi - $isi->slot</td>
      </tr>
      ";  
    }
    ?>
  </tbody>                                  
</table>