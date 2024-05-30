<center><h2>Anda yakin ingin Me-reject DO <?php echo $no_do_sim_part ?></h2></center>
<form class="form-horizontal" action="h3/do_sim_part/save" method="post" enctype="multipart/form-data">                  
  <div class="box-body">                
    <div class="form-group">                  
      <label for="inputEmail3" class="col-sm-2 control-label">Alasan Reject *</label>
      <div class="col-sm-10">
        <input type="hidden" name="no_do_sim_part" value="<?php echo $no_do_sim_part ?>">
        <input type="text" name="alasan_reject" placeholder="Alasan Reject" class="form-control" required>
      </div>                      
    </div>
  </div>
  <div class="box-footer">                
    <div class="form-group">
      <div class="col-sm-4"></div>
      <div class="col-sm-10">      
        <button type="submit" name="save" value="reject" class="btn btn-flat btn-primary">Ya</button>        
        <button type="button" class="btn btn-flat btn-danger" data-dismiss="modal">Tidak</button>
      </div>
    </div>                   
  </div>
</form>