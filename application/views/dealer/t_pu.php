<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="15%">No Shipping List</th>
      <th width="10%">Jumlah Unit</th>      
      <th width="5%">Action</th>                      
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_pu->result() as $row) {           
      $r = $this->db->query("SELECT COUNT(no_rangka) AS jum FROM tr_shipping_list WHERE no_shipping_list = '$row->no_shipping_list'")->row();    
    echo "   
    <tr>                    
      <td width='15%'>$row->no_shipping_list</td>
      <td width='10%'>$r->jum</td>      
      <td width='5%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_pu('<?php echo $row->id_penerimaan_unit_detail; ?>')"></button>
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
        <input id="no_shipping_list" readonly type="text" data-toggle="modal" data-target="#Itemmodal" name="no_shipping_list" class="form-control isi" placeholder="No Shipping List">
      </td>
      <td width="10%">
        <input type="text" id="jumlah" data-toggle="modal" data-target="#Itemmodal" placeholder="Jumlah Unit" class="form-control isi" name="jumlah" readonly>
      </td>      
      <td width="5%">
        <button type="button" onClick="simpan_pu()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table>
