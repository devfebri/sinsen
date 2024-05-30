<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>                      
      <th>Kode Model</th>                    
      <th width='5%'>Aksi</th>                      
    </tr>
  </thead>
  <?php   
  foreach($dt_polreg->result() as $row) {               
    echo "   
    <tr>                    
      <td>$row->id_tipe_kendaraan</td>      
      <td width='5%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat" type="button" 
            onClick="hapus_polreg('<?php echo $row->id_pol; ?>')"><i class="fa fa-trash-o"></i></button>        
      </td>
    </tr>
  <?php    
    }
  ?>  
  <tbody>                    
    <tr>            
      <td>
        <select class="form-control select2 isi_combo" id="id_tipe_kendaraan" name="id_tipe_kendaraan">
          <option value="">- choose -</option>
          <?php 
          foreach ($dt_tipe->result() as $isi) {
            echo "<option value='$isi->id_tipe_kendaraan'>$isi->id_tipe_kendaraan - $isi->tipe_ahm</option>";
          }
          ?>
          
        </select>
      </td>      
      <td width="5%">
        <button onclick="simpan_polreg()" type="button" class="btn btn-xs btn-flat btn-primary">Add</button>                              
      </td>                        
    </tr>                       
  </tbody>
</table>