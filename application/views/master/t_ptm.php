<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="15%">Kode Tipe Besar</th>
      <th width="10%">Description</th>      
      <th width="5%">Action</th>                      
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  
  foreach($dt_ptm->result() as $row) {                 
    $sql = $this->db->query("SELECT * FROM ms_ptm WHERE tipe_motor = '$row->tipe_marketing'");
    if($sql->num_rows() > 0){
      $isi = $sql->row();
      $tipe = $isi->tipe_motor;
      $desk = $isi->deskripsi;
    }else{
      $tipe = "";
      $desk = "";
    }
    echo "   
    <tr>                    
      <td width='15%'>$tipe</td>
      <td width='10%'>$desk</td>      
      <td width='5%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_ptm('<?php echo $row->id_part_detail; ?>')"></button>
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
        <input type="hidden" id="id_pvtm">
        <input id="tipe_motor" readonly type="text" data-toggle="modal" data-target="#Ptmmodal" name="tipe_motor" class="form-control isi_combo" placeholder="Kode Tipe Besar">
      </td>
      <td width="10%">
        <input type="text" id="deskripsi" data-toggle="modal" data-target="#Ptmmodal" placeholder="Description" class="form-control isi_combo" name="description" readonly>
      </td>      
      <td width="5%">
        <button type="button" onClick="simpan_ptm()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table>
