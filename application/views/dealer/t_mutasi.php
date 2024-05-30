<?php 
if($mode != 'detail'){
?>
<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="15%">No Mesin</th>
      <th width="15%">No Rangka</th>
      <th width="10%">Kode Item</th>      
      <th width="15%">Tipe Kendaraan</th>
      <th width="10%">Warna</th>            
      <th width="10%">
        Action
      </th>                      
    </tr>
  </thead> 
</table>
<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_data->result() as $row) {           
    echo "   
    <tr>                    
      <td width='15%'>$row->no_mesin</td>
      <td width='15%'>$row->no_rangka</td>
      <td width='10%'>$row->id_item</td>
      <td width='15%'>$row->tipe_ahm</td>      
      <td width='10%'>$row->warna</td>                                                            
      <td width='10%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_data('<?php echo $row->id_mutasi_detail; ?>','<?php echo $row->id_item; ?>')"></button>
      </td>
    </tr>
  <?php    
    }
  ?>  
</table>


<table id="myTable" class="table myt order-list" border="0">     
  <tbody>                      
    <tr>
      <td width="15%">
        <input id="no_mesin" readonly type="text" data-toggle="modal" data-target="#Nosinmodal" name="no_mesin" class="form-control isi" placeholder="No Mesin">
      </td>
      <td width="15%">
        <input type="text" id="no_rangka" data-toggle="modal" data-target="#Nosinmodal" placeholder="No Rangka" class="form-control isi" name="no_rangka" readonly>
      </td>
      <td width="10%">
        <input type="text" id="id_item" data-toggle="modal" data-target="#Nosinmodal" placeholder="Kode Item" class="form-control isi" name="id_item" readonly>
      </td>
      <td width="15%">
        <input type="text" id="tipe_ahm" readonly class="form-control isi" placeholder="Tipe Kendaraan" name="tipe_ahm">
      </td>      
       <td width="10%">
        <input type="text" id="warna" readonly class="form-control isi" placeholder="Warna" name="warna">
      </td>             
      <td width="10%">
        <button type="button" onClick="simpan_data()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table>

<?php }else{ ?>

<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="7%">ID Item</th>
      <th width="15%">Tipe</th>
      <th width="10%">Warna</th>
      <th width="10%">Qty PO Fix</th>      
      <th width="10%">Qty PO T1</th>        
      <th width="10%">Qty PO T2</th>                          
    </tr>
  </thead> 
</table>
<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_po_reg->result() as $row) {           
    echo "   
    <tr>                    
      <td width='7%'>$row->id_item</td>
      <td width='15%'>$row->tipe_ahm</td>
      <td width='10%'>$row->warna</td>
      <td width='10%'>$row->qty_po_fix</td>      
      <td width='10%'>$row->qty_po_t1</td>                                                      
      <td width='10%'>$row->qty_po_t2</td>                                                            
    </tr>";  
    }
  ?>  
</table>


<?php } ?>