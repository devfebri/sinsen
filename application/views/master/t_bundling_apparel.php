<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="20%">Kode Apparel</th>
      <th width="20%">Nama Apparel</th>
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
      <td width='20%'>$row->id_apparel</td>
      <td width='20%'>$row->apparel</td>      
      <td width='10%'>$row->qty_apparel</td>            
      <td width='10%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_apparel('<?php echo $row->id_paket_bundling_app; ?>','<?php echo $row->id_apparel; ?>')"></button>
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
        <input id="id_apparel" readonly type="text" data-toggle="modal" data-target="#Apparelmodal" name="id_apparel" class="form-control isi_combo" placeholder="Kode Apparel">
      </td>
      <td width="20%">
        <input type="text" id="apparel" data-toggle="modal" data-target="#Apparelmodal" placeholder="Nama Apparel" class="form-control isi_combo" name="apparel" readonly>
      </td>      
      <td width="10%">
        <input type="text" id="qty_apparel" placeholder="Qty" class="form-control isi_combo" name="qty">
      </td>      
      <td width="10%">
        <button type="button" onClick="simpan_apparel()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table>