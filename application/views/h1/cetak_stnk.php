
<table id="" class="table table-bordered table-hover">
  <thead>
    <tr bgcolor="red">              
      <th width="5%">No</th>                          
      <th>No Mesin</th>
      <th>No Rangka</th>
      <th>Nama Konsumen</th>
      <th>Tipe</th>                 
      <th>Warna</th>
      <th>No STNK</th>                      
    </tr>
  </thead>
  <tbody>
    <?php 
    $no=1;    
    foreach ($dt_stnk->result() as $row) {
      echo "
        <tr>
          <td>$no</td>
          <td>$row->no_mesin</td>
          <td>$row->no_rangka</td>
          <td>$row->nama_konsumen</td>
          <td>$row->id_tipe_kendaraan</td>
          <td>$row->id_warna</td>
          <td>$row->no_stnk</td>
        </tr>
      ";
      $no++;
    }
    ?>
  </tbody>
</table>