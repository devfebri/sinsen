<?php if ($dt_wo->num_rows() >0): ?>
  <?php $row = $dt_wo->row(); ?>
            <p align="right"><button class="btn bg-primary btn-flat margin-right" id="print_repair_tag" id_checker="<?php echo $row->id_checker ?>" tgl="<?php echo date("Y-m-d") ?>"><i class="fa fa-print"></i> Print</button></p>
          <hr>
<form class="form-horizontal" action="h1/wo/save" method="post" enctype="multipart/form-data">                  
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal WO</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_checker" value="<?php echo $row->tgl_wo ?>" placeholder="Tanggal wo" class="form-control" readonly>
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" value="<?php echo $row->no_mesin ?>" placeholder="No Mesin" readonly class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Item</label>
                  <div class="col-sm-4">
                    <input type="text" name="kode_item" value="<?php echo $row->id_item ?>" placeholder="Kode Item" readonly class="form-control">
                  </div>
                </div>                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Sumber Kerusakan</label>
                  <div class="col-sm-4">
                    <input type="text" name="sumber_kerusakan"  value="<?php echo $row->sumber_kerusakan ?>" placeholder="Sumber Kerusakan" readonly class="form-control">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                  <div class="col-sm-4">
                    <input type="text" name="tipe_ahm" value="<?php echo $row->tipe_ahm ?>" readonly placeholder="Tipe Kendaraan" class="form-control">
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-4">
                    <input type="text" name="keterangan" value="<?php echo $row->keterangan ?>" readonly placeholder="Keterangan" class="form-control">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" name="warna" value="<?php echo $row->warna ?>" readonly placeholder="Warna" class="form-control">
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Ekspedisi</label>
                  <div class="col-sm-4">
                    <input type="text" readonly name="ekspedisi" placeholder="Ekspedisi" value="<?php echo $row->ekspedisi ?>" class="form-control">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Polisi</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_polisi" readonly placeholder="No Polisi" value="<?php echo $row->no_polisi ?>" class="form-control">
                  </div>
                </div>             

                <button type="reset" class="btn btn-warning btn-flat btn-block" disabled>Detail Part</button>                                             
                <br>

                <table id="example2" class="table table-bordered table-hovered myTable1" width="100%">
                  <thead>
                    <tr>
                      <th width='10%'>Part</th>
                      <th width='10%'>Deskripsi</th>
                      <th>Pengatasan</th>
                      <th>Qty Order</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php   
                    $dt_check = $this->m_admin->getByID("tr_checker_detail","id_checker",$row->id_checker);
                    foreach($dt_check->result() as $row) {           
                      echo "   
                      <tr>                    
                        <td width='10%'>$row->id_part</td>
                        <td width='20%'>$row->deskripsi</td>
                        <td width='15%''>$row->pengatasan</td>
                        <td width='15%'>$row->qty_order</td>                        
                      </tr>";                    
                    }
                    ?>  
                  </tbody>
                </table>                 
              
            </form>
<?php endif ?>

<script type="text/javascript">
  $(document).on("click","#print_repair_tag",function(){ 
      var id_checker=$(this).attr('id_checker');
      var tgl=$(this).attr('tgl');
       var h=470;
       var w=470;
       var left = (screen.width/2)-(w/2);
      var top = (screen.height/2)-(h/2);
      //var targetWin = window.open ('h1/wo/print_repair_tag_pdf?id='+id_checker+'&tgl='+tgl, "Cetak", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
      var targetWin = window.open ('h1/wo/print_tag?id='+id_checker+'&tgl='+tgl, "Cetak", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
        })
</script>