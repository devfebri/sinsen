<?php 
if($mode == "edit" || $mode == "new"){
?>
<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="40%">Nama Item</th>
      <th width="40%">Kategori Item</th>
      <th width="10%">Qty Terima</th>                    
      <th width="10%">Aksi</th>
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_data->result() as $row) {           
    echo "   
    <tr>                    
      <td width='40%'>$row->id_item_promosi</td>
      <td width='40%'>$row->id_kategori_item</td>      
      <td width='10%'>$row->qty_terima</td>      
      <td width='10%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_data('<?php echo $row->no_penerimaan_promosi; ?>','<?php echo $row->id_penerimaan_promosi_detail; ?>')"></button>
      </td>
    </tr>
  <?php    
    }
  ?>  
</table>


<table id="myTable" class="table myt order-list" border="0">     
  <tbody>                      
    <tr>
      <td width="40%">
        <select class="form-control select2 isi_combo" id="id_item_promosi" onchange="cek_kategori()">
          <option value="">- choose -</option>
          <?php 
          $promosi = $this->m_admin->getSortCond("ms_item_promosi","item_promosi","ASC");
          foreach ($promosi->result() as $isi) {
            echo "<option value='$isi->id_item_promosi'>$isi->item_promosi</option>";
          }
          ?>
        </select>
      </td>
      <td width="40%">
        <input type="text" class="form-control isi_combo" readonly id="id_kategori_item" placeholder="Kategori Item">
      </td>
      <td width="10%">
        <input type="text" class="form-control isi_combo" id="qty_terima" placeholder="Qty Terima">
      </td>                          
      <td width="10%">
        <button type="button" onClick="simpan_data()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table>
<?php 
}else{
?>
<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="40%">Nama Item</th>
      <th width="40%">Kategori Item</th>
      <th width="10%">Qty Terima</th>                          
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_data->result() as $row) {           
    echo "   
    <tr>                    
      <td width='40%'>$row->id_item_promosi</td>
      <td width='40%'>$row->id_kategori_item</td>      
      <td width='10%'>$row->qty_terima</td>            
    </tr>";  
    }
  ?>  
</table>                        
</table>
<?php 
}
?>