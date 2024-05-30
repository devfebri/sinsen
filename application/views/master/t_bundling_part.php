<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="20%">Kode Part</th>
      <th width="20%">Nama Part</th>
      <th width="10%">Qty</th>                    
      <th width="10%">Aksi</th>                          
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_data->result() as $row) {           
    echo "   
    <tr>                    
      <td width='20%'>$row->id_part</td>
      <td width='20%'>$row->nama_part</td>      
      <td width='10%'>$row->qty_part</td>            
      <td width='10%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_part('<?php echo $row->id_paket_bundling_detail; ?>','<?php echo $row->id_part; ?>')"></button>
      </td>
    </tr>
  <?php    
    }
  ?>  
</table>




<table id="myTable" class="table myt order-list" border="0">     
  <tbody>                      
    <tr>
      <td width="20%">
        <input id="id_part" readonly type="text" data-toggle="modal" data-target="#Partmodal" name="id_part" class="form-control isi_combo" placeholder="Kode Part">
      </td>
      <td width="20%">
        <input type="text" id="nama_part" data-toggle="modal" data-target="#Partmodal" placeholder="Nama Part" class="form-control isi_combo" name="nama_part" readonly>
      </td>      
      <td width="10%">
        <input type="text" id="qty" placeholder="Qty" class="form-control isi_combo" name="qty">
      </td>      
      <td width="10%">
        <button type="button" onClick="simpan_part()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table>