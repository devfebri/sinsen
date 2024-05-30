<?php 
if($mode != "detail"){
?>
<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="10%">ID Item</th>
      <th width="15%">Tipe</th>
      <th width="10%">Warna</th>
      <th width="10%">Qty Order</th>      
      <th width="10%">
        Action
      </th>                      
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_po_add->result() as $row) {           
    echo "   
    <tr>                    
      <td width='10%'>$row->id_item</td>
      <td width='15%'>$row->tipe_ahm</td>
      <td width='10%''>$row->warna</td>
      <td width='10%'>$row->qty_order</td>      
      <td width='10%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_po('<?php echo $row->id_po_detail; ?>','<?php echo $row->id_item; ?>')"></button>
      </td>
    </tr>
  <?php    
    }
  ?>  
</table>


<table id="myTable" class="table myt order-list" border="0">     
  <tbody>                      
    <tr>
      <td width="10%">
        <input id="id_item" readonly type="text" data-toggle="modal" data-target="#Itemmodal" name="id_item" class="form-control isi" placeholder="ID Item">
      </td>
      <td width="15%">
        <input type="text" id="tipe" data-toggle="modal" data-target="#Itemmodal" placeholder="Tipe" class="form-control isi" name="tipe" readonly>
      </td>
      <td width="10%">
        <input type="text" id="warna" data-toggle="modal" data-target="#Itemmodal" placeholder="Warna" class="form-control isi" name="warna" readonly>
      </td>
      <td width="10%">
        <input type="text" id="qty_order" onkeypress="return number_only(event)" class="form-control isi" placeholder="Qty Order" name="qty_order">
      </td>      
      <td width="10%">
        <button type="button" onClick="simpan_po()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table>

<?php }else{ ?>

<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="10%">ID Item</th>
      <th width="15%">Tipe</th>
      <th width="10%">Warna</th>
      <th width="10%">Qty Order</th>                                
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_po_add->result() as $row) {           
    echo "   
    <tr>                    
      <td width='10%'>$row->id_item</td>
      <td width='15%'>$row->tipe_ahm</td>
      <td width='10%''>$row->warna</td>
      <td width='10%'>$row->qty_order</td>           
    </tr>";  
    }
  ?>  
</table>


<?php } ?>