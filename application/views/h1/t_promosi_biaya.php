<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="20%">Vendor</th>
      <th width="20%">Item</th>
      <th width="5%">Qty</th>                    
      <th width="10%">Harga</th>                    
      <th width="10%">PPN</th>                    
      <th width="15%">Keterangan</th>                    
      <th width="10%">Aksi</th>
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_data->result() as $row) {           
    echo "   
    <tr>                    
      <td width='20%'>$row->vendor_name</td>
      <td width='20%'>$row->item_promosi</td>      
      <td width='5%'>$row->qty</td>      
      <td width='10%'>$row->harga_beli</td>      
      <td width='10%'>$row->ppn</td>      
      <td width='15%'>$row->keterangan</td>            
      <td width='10%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_biaya('<?php echo $row->id_promosi_biaya; ?>','<?php echo $row->id_promosi; ?>')"></button>
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
        <select class="form-control select2 isi_combo" id="id_vendor">
          <option value="">- choose -</option>
          <?php 
          $vendor = $this->m_admin->getSortCond("ms_vendor","vendor_name","ASC");
          foreach ($vendor->result() as $isi) {
            echo "<option value='$isi->id_vendor'>$isi->vendor_name</option>";
          }
          ?>
        </select>
      </td>
      <td width="20%">
        <select class="form-control isi_combo select2" id="id_item_promosi" onchange="ambil_harga()">
          <option value="">- choose -</option>
          <?php 
          $item = $this->m_admin->getSortCond("ms_item_promosi","item_promosi","ASC");
          foreach ($item->result() as $isi) {
            echo "<option value='$isi->id_item_promosi'>$isi->item_promosi</option>";
          }
          ?>
        </select>
      </td>
      <td width="5%">
        <input type="text" class="form-control isi_combo" id="qty" placeholder="Qty">
      </td>                          
      <td width="10%">
        <input type="text" class="form-control isi_combo" id="harga" placeholder="Harga" readonly>
      </td>
      <td width="10%">
        <select class="form-control isi_combo select2" id="ppn">
          <option>Ya</option>
          <option>Tidak</option>
        </select>
      </td>
      <td width="15%">
        <input type="text" class="form-control isi_combo" id="keterangan" placeholder="Keterangan">
      </td>
      <td width="10%">
        <button type="button" onClick="simpan_biaya()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table>
