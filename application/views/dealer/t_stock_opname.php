<table id="example2" class="table myTable1 table-bordered table-hover">
  <thead>
    <tr>      
      <th>No</th>
      <th>Tipe</th>              
      <th>Warna</th>              
      <th>Kode Item</th>
      <th>No Mesin</th>
      <th>No Rangka</th>
      <th>Action</th>                          
    </tr>
  </thead>
 
  <tbody>                    
    <?php   
    $no = 1;
    $x = 0;
    foreach($dt_->result() as $isi) {  ?>
      <tr>
        <td><?php echo $no ?></td>
        <td><?php echo $isi->tipe_ahm ?></td>
        <td><?php echo $isi->nama_warna ?></td>
        <td><?php echo $isi->id_item ?></td>
        <td><?php echo $isi->no_mesin ?></td>
        <td><?php echo $isi->no_rangka ?></td>
        <td>
          <input type="checkbox" name="check_<?= $x ?>" value="<?php echo $isi->no_mesin ?>">
          <input type="hidden" name="no_mesin[]" value="<?php echo $isi->no_mesin ?>">
          <input type="hidden" name="no_rangka[]" value="<?php echo $isi->no_rangka ?>">
          <input type="hidden" name="id_item[]" value="<?php echo $isi->id_item ?>">
          <input type="hidden" name="id_warna[]" value="<?php echo $isi->id_warna ?>">
          <input type="hidden" name="id_tipe_kendaraan[]" value="<?php echo $isi->id_tipe_kendaraan ?>">
        </td>
      </tr>
     <?php $no++;$x++;
      }
    ?>
    <input type="hidden" name="jum" value="<?php echo $no-=1; ?>">
  </tbody>
</table>     