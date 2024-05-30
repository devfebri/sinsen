<table class="table table-bordered table-hover myTable1">
  <thead>
    <tr>
      <th>Tipe Kendaraan</th>
      <th>Warna</th>
      <th>Qty</th>      
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    foreach ($detail->result() as $rs): ?>
      <tr>
        <td><?= $rs->id_tipe_kendaraan." | ".$rs->tipe_ahm?></td>
        <td><?= $rs->id_warna." | ".$rs->warna?></td>
        <td><?= $rs->qty?></td>        
        <td>
          <button type="button" class="btn btn-danger btn-xs btn-flat" title="Add" onclick="delDetail(<?= $rs->id_prospek_gc_kendaraan?>)"><i class="fa fa-trash"></i></button>
          <button type="button" class="btn btn-warning btn-flat btn-xs" data-toggle="modal" data-target=".modal_edit" id="<?php echo $rs->id_prospek_gc_kendaraan ?>" onclick="edit_popup('<?php echo $rs->id_prospek_gc_kendaraan ?>')"><i class="fa fa-edit"></i></button>
        </td>
      </tr>
    <?php endforeach ?>
  </tbody>
  <tfoot>
    <tr>
      <td>                                                            

        <select class="form-control select3" name="id_tipe_kendaraan" id="id_tipe_kendaraan_gc" onchange="getWarna_gc()">
            <?php if ($dt_tipe->num_rows()>0): ?>
              <option value="">- choose -</option>
              <?php foreach ($dt_tipe->result() as $rs): ?>
                  <option value="<?=$rs->id_tipe_kendaraan?>"><?=$rs->id_tipe_kendaraan?> | <?=$rs->tipe_ahm?></option>
              <?php endforeach ?>
            <?php endif ?>
        </select>
      </td>
      <td>
        <select class="form-control select2" name="id_warna" id="id_warna_gc"></select>                                                             
      </td>
      <td>
        <input type="text" autocomplete="off" class="form-control" id="qty_gc" placeholder="QTY">
      </td>      
      <td>
        <button type="button" class="btn btn-primary btn-xs btn-flat" title="Add" onclick="addDetail()"><i class="fa fa-plus"></i></button>
        
      </td>
    </tr>
  </tfoot>
</table>   

