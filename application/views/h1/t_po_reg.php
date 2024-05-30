<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="7%">ID Item</th>
      <th width="15%">Tipe</th>
      <th width="10%">Warna</th>
      <th width="8%">On Hand</th>
      <th width="10%">Qty Niguri Fix</th>
      <th width="10%">Qty PO Fix</th>      
      <th width="10%">Qty PO T1</th>        
      <th width="10%">Qty PO T2</th> 
      <?php if($mode != 'detail'){ ?> 
        <th width="10%">Action</th>                      
      <?php } ?>
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  $tot_onhand=0;$tot_niguri_fix=0;$tot_po_fix=0;$tot_po_t1=0;$tot_po_t2=0;
  foreach($dt_po_reg->result() as $row) {           
    echo "   
    <tr>                    
      <td width='7%'>$row->id_item</td>
      <td width='15%'>$row->tipe_ahm</td>
      <td width='10%'>$row->warna</td>
      <td width='8%'>$row->on_hand</td>
      <td width='10%'>$row->qty_niguri_fix</td>
      <td width='10%'>$row->qty_po_fix</td>      
      <td width='10%'>$row->qty_po_t1</td>                                                      
      <td width='10%'>$row->qty_po_t2</td>";                                                            
      if($mode != 'detail'){
        echo "<td width='10%'></td>";
      }                                                            
    echo "</tr>";  
    $tot_onhand+=$row->on_hand;
    $tot_niguri_fix+=$row->qty_niguri_fix;
    $tot_po_fix+=$row->qty_po_fix;
    $tot_po_t1+=$row->qty_po_t1;
    $tot_po_t2+=$row->qty_po_t2;
    }
  ?>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><b><?= $tot_onhand ?></b></td>
    <td><b><?= $tot_niguri_fix ?></b></td>
    <td><b><?= $tot_po_fix ?></b></td>
    <td><b><?= $tot_po_t1 ?></b></td>
    <td><b><?= $tot_po_t2 ?></b></td>
  </tr>
</table>

<?php if($mode != 'detail'){ ?>

<table id="myTable" class="table myt order-list" border="0">     
  <tbody>                      
    <tr>
      <td width="7%">
        <input id="id_item" readonly type="text" data-toggle="modal" data-target="#Itemmodal" class="form-control isi" placeholder="ID Item">
      </td>
      <td width="15%">
        <input type="text" id="tipe" data-toggle="modal" data-target="#Itemmodal" placeholder="Tipe" class="form-control isi" readonly>
      </td>
      <td width="10%">
        <input type="text" id="warna" data-toggle="modal" data-target="#Itemmodal" placeholder="Warna" class="form-control isi" readonly>
      </td>
      <td width="8%">
        <input type="text" id="on_hand" onkeypress="return number_only(event)" class="form-control isi" placeholder="On Hand" readonly>
      </td>
      <td width="10%">
        <input type="text" id="qty_niguri_fix" onkeypress="return number_only(event)" class="form-control isi" placeholder="Qty Niguri Fix" readonly>
      </td>
      <td width="10%">
        <input type="text" id="qty_po_fix" onkeypress="return number_only(event)" class="form-control isi" placeholder="Qty PO Fix">
      </td>      
       <td width="10%">
        <input type="text" id="qty_po_t1" onkeypress="return number_only(event)" class="form-control isi" placeholder="Qty PO T1">
      </td>      
       <td width="10%">
        <input type="text" id="qty_po_t2" onkeypress="return number_only(event)" class="form-control isi" placeholder="Qty PO T2">
      </td>      
      <td width="10%">
        <button type="button" onClick="simpan_po()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table>

<?php } ?>
<!-- <td width='10%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat" type="button" 
            onClick="hapus_po('<?php echo $row->id_po_detail; ?>','<?php echo $row->id_item; ?>')"><i class='fa fa-trash-o'></i></button>
        <a href="javascript:void(0)" title="Edit" class="btn btn-sm btn-primary btn-flat" data-toggle="tooltip modal"
          onclick="edit_po_reg(<?php echo $row->id_po_detail ?>)"><i class='fa fa-edit'></i></a>
      </td> -->