<?php $row = $dt_sql->row(); ?>
<form class="form-horizontal" action="h1/wo/save" method="post" enctype="multipart/form-data">                  
  <div class="form-group">                  
    <label for="inputEmail3" class="col-sm-2 control-label">No SO</label>
    <div class="col-sm-4">
      <input type="text" name="tgl_checker" value="<?php echo $row->no_so_part ?>" placeholder="No SO" class="form-control" readonly>
    </div>                  
    <label for="inputEmail3" class="col-sm-2 control-label">Tgl SO</label>
    <div class="col-sm-4">
      <input type="text" name="tgl_checker" value="<?php echo $row->tgl_so ?>" placeholder="No SO" class="form-control" readonly>
    </div>                  
  </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
    <div class="col-sm-10">
      <input type="text" name="no_mesin" value="<?php echo $row->nama_dealer ?>" placeholder="No Mesin" readonly class="form-control">
    </div>                      
  </div>                   
</form>
<table id="example2" class="table table-bordered table-hovered myTable1" width="100%">
  <thead>
    <th>Kode Part</th>
    <th>Nama Part</th>
    <th>Qty Order</th>
  </thead>
  <tbody>
    <?php 
    $dt = $this->db->query("SELECT * FROM tr_so_part_detail LEFT JOIN ms_part ON tr_so_part_detail.id_part = ms_part.id_part
        WHERE tr_so_part_detail.no_so_part = '$no_so_part'");
    foreach ($dt->result() as $isi) {
      echo "
      <tr>
        <td>$isi->id_part</td>
        <td>$isi->nama_part</td>
        <td>$isi->qty_order</td>
      </tr>
      ";
    }
    ?>
  </tbody>
</table>