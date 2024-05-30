<table id="example" class="table table-bordered table-hover">
  <thead>
    <tr>                      
      <th width='20%'>No Mesin</th>              
      <th width="20%">No Rangka</th>              
      <th width="20%">Kode Item</th>
      <th width="8%">Aksi</th>                      
    </tr>
  </thead>
</table>
<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_kendaraan->result() as $row) {           
    $r = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$row->no_mesin'")->row();    
    echo "   
    <tr>                    
      <td width='20%'>$row->no_mesin</td>
      <td width='20%'>$r->no_rangka</td>      
      <td width='20%'>$r->id_item</td>            
      <td width='8%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat" type="button" 
            onClick="hapus_kendaraan('<?php echo $row->id_list_appointment; ?>','<?php echo $row->id_ssu_kendaraan; ?>')"><i class="fa fa-trash-o"></i></button>        
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
        <input type="text" readonly data-toggle="modal" data-target="#Nosinmodal" id="no_mesin" onchange="cek_to()" placeholder="No Mesin" class="form-control isi" name="jumlah">
      </td>      
      <td width="20%">
        <input type="text" id="no_rangka" data-toggle="modal" data-target="#Nosinmodal" readonly placeholder="No Rangka" class="form-control isi" name="no_rangka">
      </td>      
      <td width="20%">
        <input type="text" id="id_item" data-toggle="modal" data-target="#Nosinmodal" readonly placeholder="ID Item" class="form-control isi" name="id_item">
      </td>      
      <td width="8%">
        <button onclick="simpan_kendaraan()" type="button" class="btn btn-xs btn-flat btn-primary">Add</button>                              
      </td>                        
    </tr>                       
  </tbody>
</table>