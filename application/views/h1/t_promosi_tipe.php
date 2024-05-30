<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="50%">Tipe Kendaraan</th>      
      <th width="20%">Qty Target</th>      
      <th width="10%">Aksi</th>
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_data->result() as $row) {           
    echo "   
    <tr>                    
      <td width='50%'>$row->id_tipe_kendaraan | $row->tipe_ahm</td>            
      <td width='20%'>$row->qty_target</td>            
      <td width='10%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_tipe('<?php echo $row->id_promosi_tipe; ?>','<?php echo $row->id_promosi; ?>')"></button>
      </td>
    </tr>
  <?php    
    }
  ?>  
</table>


<table id="myTable" class="table myt order-list" border="0">     
  <tbody>                      
    <tr>
      <td width="50%">
        <select class="form-control isi_combo" id="id_tipe_kendaraan">
          <option value="">- choose -</option>
          <?php 
          $tipe = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");
          foreach ($tipe->result() as $isi) {
            echo "<option value='$isi->id_tipe_kendaraan'>$isi->id_tipe_kendaraan | $isi->tipe_ahm</option>";
          }
          ?>
        </select>
      </td>            
      <td width="20%">
        <input type="text" class="form-control isi_combo" id="qty_target" placeholder="Qty Target">
      </td>
      <td width="10%">
        <button type="button" onClick="simpan_tipe()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table>
