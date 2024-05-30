<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="50%">Leasing</th>            
      <th width="10%">Aksi</th>
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_data->result() as $row) {           
    echo "   
    <tr>                    
      <td width='50%'>$row->finance_company</td>                  
      <td width='10%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_leasing('<?php echo $row->id_promosi_leasing; ?>','<?php echo $row->id_promosi; ?>')"></button>
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
        <select class="form-control isi_combo" id="id_finance_company">
          <option value="">- choose -</option>
          <?php 
          $finance = $this->m_admin->getSortCond("ms_finance_company","finance_company","ASC");
          foreach ($finance->result() as $isi) {
            echo "<option value='$isi->id_finance_company'>$isi->id_finance_company | $isi->finance_company</option>";
          }
          ?>
        </select>
      </td>                  
      <td width="10%">
        <button type="button" onClick="simpan_leasing()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table>
