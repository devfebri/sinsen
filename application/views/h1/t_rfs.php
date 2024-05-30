<table id="example" class="table table-bordered table-hover">
  <thead>
    <tr>              
      <th width="5%">No</th>
      <th>No Mesin</th>              
      <th>No Rangka</th>
      <th>No SL</th>
      <th>Nama Ekspeisi</th>      
      <th>Tipe</th>  
      <th>Warna</th>        
      <th>Kode Item</th>    
      <th>Lokasi</th>
      <th>FIFO</th>        
      <!-- <th></th -->      
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
        <td>$row->no_rangka</td>
        <td>$row->no_shipping_list</td>
        <td>$row->nama_ekspedisi</td>
        <td>$row->tipe_ahm</td>
        <td>$row->warna</td>
        <td>$row->id_item</td>
        <td>$row->lokasi ($row->slot)</td>
        <td>$row->fifo</td>        
      </tr>";      
      $no++;
    }
    ?>
  </tbody>
</table>

<?php /*
<!-- // <td width='5%'>"; ?>
//         <button title="Hapus Data"
//             class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
//             onClick="hapus_scan('<?php echo $row->id_scan_barcode; ?>','<?php echo $jenis; ?>')"></button>
//         </td> -->
*/?>