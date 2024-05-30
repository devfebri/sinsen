<table id="example2" class="table table-bordered table-hovered myTable1" width="100%">
  <thead>
    <tr>
      <th>Tipe Kendaraan</th>
      <th>Warna</th>
      <th width="10%">Qty</th>      
      <th width="15%">Tahun</th>      
      <th width="10%">Aksi</th>
    </tr>
  </thead>
  <tbody>    
    <tr>
      <td>                                                            
        <select class="form-control select3" name="id_tipe_kendaraan" id="id_tipe_kendaraan_edit" onchange="getWarna_gc_edit()">
            <?php 
            if ($dt_tipe->num_rows()>0){ 
              echo "<option value=''>- choose -</option>";                            
              foreach ($dt_tipe->result() as $rs){ 
                if($rs->id_tipe_kendaraan == $dt_gc->id_tipe_kendaraan){
                  $sr = 'selected';
                }else{
                  $sr = '';
                } 
                echo "<option value='$rs->id_tipe_kendaraan' $sr>$rs->id_tipe_kendaraan | $rs->tipe_ahm</option>";
              }
            }
            ?>
        </select>
      </td>
      <td>
        <input type="hidden" id="id_warna_edit2" value="<?php echo $dt_gc->id_warna ?>">
        <select class="form-control select2" name="id_warna" id="id_warna_edit">
          <option><?php echo $dt_gc->id_warna." | ".$dt_gc->warna ?></option>  
        </select>                                                             
      </td>
      <td>
        <input type="text" value="<?php echo $dt_gc->qty ?>" autocomplete="off" class="form-control" id="qty_edit" placeholder="QTY">
      </td>      
      <td>
        <input type="text" value="<?php echo $dt_gc->tahun ?>" autocomplete="off" class="form-control" id="tahun_edit" placeholder="Tahun">
      </td>      
      <td>
        <button type="button" class="btn btn-primary btn-xs btn-flat" title="Save" onclick="saveEdit(<?php echo $dt_gc->id_prospek_gc_kendaraan ?>)"><i class="fa fa-save"></i></button>        
      </td>
    </tr>
  </tbody>
</table>                 
                        
                        