<button type="reset" class="btn btn-success btn-flat btn-block" disabled>Detail No Mesin</button>                                             
<br>

<table class="table table-bordered table-hovered myTable1" width="100%">
  <thead>
    <tr>
      <th width='10%'>No Mesin</th>
      <th width='10%'>No Rangka</th>                    
      <th width='15%'>Lokasi Unit</th>                    
    </tr>
  </thead>  
  <tbody>
  <?php 
  $no=1;
  foreach ($dt_data->result() as $isi) {    
    $jum = $dt_data->num_rows();
    echo "    
    <tr>
      <td width='10%'>
        <input type='hidden' name='no_mesin_$no' value='$isi->no_mesin'>
        <input type='hidden' name='jum_nosin' value='$jum'>
        $isi->no_mesin
      </td>
      <td width='10%'>$isi->no_rangka</td>             
      <td width='20%'>$isi->lokasi - $isi->slot</td>             
    </tr>";
    $no++;
  } 
  ?>
  </tbody>  
</table>  

<!-- <table id="myTable" class="table myt order-list" border="0">     
<td width='5%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_nosin()"></button>
      </td>     
  <tbody>                      
    <tr>
      <td width="10%">
        <input id="no_mesin" readonly type="text" data-toggle="modal" data-target="#Nosinmodal" name="no_mesin" class="form-control isi_combo" placeholder="No Mesin">
      </td>
      <td width="10%">
        <input type="text" id="no_rangka" data-toggle="modal" data-target="#Nosinmodal" placeholder="No Rangka" class="form-control isi_combo" name="no_rangka" readonly>
      </td>            
      <td width="5%">
        <button type="button" onClick="simpan_nosin()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table> -->