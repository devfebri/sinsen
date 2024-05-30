
<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>      
      <th width="15%">Tgl Follow Up</th>
      <th width="15%">Hasil Follow Up</th>
      <th width="10%">Status</th>      
      <th width="15%">Next Follow Up</th>    
      <th width="10%">
        Action
      </th>                      
    </tr>
  </thead> 
</table>
<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  $next='';
  foreach($dt_data->result() as $row) {           
    $next = $row->next_fu;
    echo "   
    <tr>                    
      <td width='15%'>$row->tgl_fu</td>
      <td width='15%'>$row->hasil_fu</td>
      <td width='10%'>$row->status_fu</td>
      <td width='15%'>$row->next_fu</td>            
      <td width='10%'>"; ?>
      <?php /*
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_data('<?php echo $row->id_guest_book_detail; ?>')"></button>
      */ ?>
      </td>
    </tr>
  <?php    
    }
  ?>  
  <input type="hidden" id="last_next_fu" value="<?= $next ?>">
</table>


<table id="myTable" class="table myt order-list" border="0">     
  <tbody>                      
    <tr>
      <td width="15%">
        <input id="tanggal19" type="text" name="tgl_fu" class="form-control isi tanggal" placeholder="yyyy-mm-dd">
      </td>
      <td width="15%">
        <input type="text" id="hasil_fu" placeholder="Hasil Fol Up" class="form-control isi" name="hasil_fu">
      </td>
      <td width="10%">
        <select class="form-control isian" name="status_fu" id="status_fu">
          <option value="">- choose -</option>      
          <!-- <option>Hot</option>
          <option>Low</option>
          <option>Medium</option>
          <option>Deal</option>
          <option>Not Deal</option>     -->
                          <option>Cold Prospect</option>
                          <option>Medium Prospect</option>
                          <option>Hot Prospect</option>
                          <option>Deal</option>
                          <option>Closing</option>
                          <option>Loss</option>                      
        </select>
      </td>
      <td width="15%">
        <input type="text" id="tanggal_today" class="form-control isi tanggal" placeholder="yyyy-mm-dd" name="next_fu">
      </td>                        
      <td width="10%">
        <button type="button" onClick="simpan_data()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table>
