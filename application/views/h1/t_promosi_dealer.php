<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="20%">Dealer</th>      
      <th width="10%">Aksi</th>
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_data->result() as $row) {           
    echo "   
    <tr>                    
      <td width='20%'>$row->kode_dealer_md | $row->nama_dealer</td>            
      <td width='10%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_dealer('<?php echo $row->id_promosi_dealer; ?>','<?php echo $row->id_promosi; ?>')"></button>
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
        <select class="form-control isi_combo" id="id_dealer" onchange="ambil_dealer()">
          <option value="">- choose -</option>
          <?php 
          $dealer = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");
          foreach ($dealer->result() as $isi) {
            echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md | $isi->nama_dealer</option>";
          }
          ?>
        </select>
      </td>            
      <td width="10%">
        <button type="button" onClick="simpan_dealer()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table>
