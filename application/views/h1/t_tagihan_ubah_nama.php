<?php if ($dt_tagihan->num_rows() > 0): 
  $row = $dt_tagihan->row(); ?>                        
<form class="form-horizontal" action="h1/tagihan_ubah_nama/approve" method="post" enctype="multipart/form-data">                  
  <div class="form-group">                  
    <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
    <div class="col-sm-4">
      <input type="text" name="no_mesin" value="<?php echo $row->no_mesin ?>" placeholder="No Mesin" class="form-control" readonly>
    </div>                  
  </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
    <div class="col-sm-4">
      <input type="text" name="nama_konsumen" value="<?php echo $row->nama_konsumen ?>" placeholder="Nama Konsumen" readonly class="form-control">
    </div>                  
    <label for="inputEmail3" class="col-sm-2 control-label">Biaya Denda</label>
    <div class="col-sm-4">
      <input type="text" name="biaya_denda" autocomplete="off" onkeypress="return number_only(event)" placeholder="Biaya Denda" class="form-control">
    </div>
  </div>       
  <div class="form-group">                  
    <label for="inputEmail3" class="col-sm-2 control-label"></label>
    <div class="col-sm-4">
      <button type="submit" class="btn btn-primary btn-sm btn-flat"> Simpan</button>
    </div>                  
  </div>                                                                           
</form>
<?php endif ?>
