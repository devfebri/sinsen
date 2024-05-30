<table id="example" class="table table-bordered table-hover">
  <thead>
    <tr>              
      <th width="5%">No</th>
      <th>No Mesin</th>              
      <th>Status Baru</th>
      <th>Lokasi Awal</th>
      <th>Lokasi Tujuan (Suggest)</th>            
      <th></th>      
    </tr>
  </thead>
  <tbody>            
  <?php 
  $no = 1;
  foreach ($dt_rfs->result() as $row) {
    echo "
    <tr>
      <td>$no</td>
      <td>$row->no_mesin</td>
      <td>$jenis</td>
      <td>$row->lokasi_awal-$row->slot_awal</td>
      <td>$row->lokasi_tujuan-$row->slot_tujuan</td>                    
      <td width='5%'>"; ?>
      <button title="Hapus Data"
          class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
          onClick="hapus_scan('<?php echo $row->id_scan_ubah; ?>','<?php echo $jenis; ?>','<?php echo $row->no_mesin; ?>')"></button>
      </td>
    </tr>
    <?php
    $no++;
  }
  ?>
  </tbody>
</table>