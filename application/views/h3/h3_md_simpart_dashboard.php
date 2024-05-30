<base href="<?php echo base_url(); ?>" />
    <?php 
    if($set=="view"){
    ?>

<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>

  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H3</li>
    <li class="">Laporan</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <div class="box box-default">
      <div class="box-header with-border">        
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" id="frm" method="post" action= "h3/h3_md_simpart_dashboard/downloadExcel" enctype="multipart/form-data">
              <div class="box-body">                                                              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-1 control-label">Start Date</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control datepicker" name="tgl1" value="<?= date('Y-m-d') ?>" id="tanggal1">
                  </div>  
                  <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control datepicker" name="tgl2" value="<?= date('Y-m-d') ?>" id="tanggal2">
                  </div>                                     
                </div>             
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-1 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer" id="id_dealer">
                      <option value="all">All Dealers</option>
                      <?php 
                       foreach ($dealer as $isi) {
                        echo "<option value='$isi->id_dealer'>$isi->kode_dealer_ahm - $isi->nama_dealer</option>";
                      }
                       ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Pilihan Format</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="type" id="type">
                    <optgroup label="Data Master">
                        <option value="master_ahass">Master AHASS</option>
                        <option value="master_sim_part">Master SIM Parts</option>
                        <option value="master_kelompok_part">Master Kelompok Barang</option>
                        <option value="parts_number">Parts Number</option>
                        <option value="master_by_qty">Master by Qty</option>
                        <option value="master_by_item">Master by Item</option>
                        <option value="master_status_by_qty">Master Status by Qty</option>
                      </optgroup>
                    </select>
                  </div>                  
                </div>  
                <!-- <div class="form-group">
                <div class="col-sm-2">
                    <button type="submit" name="process" value="excel" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-download"></i> Download .xls</button>                                                      
                  </div>   
  		            <div class="col-sm-2">
                    <button type="submit" name="process" value="csv" class="btn bg-blue btn-block btn-flat"><i class="fa fa-download"></i> Download .csv</button>                                                      
                  </div>
                </div>              -->
              </div><!-- /.box-body -->              
              <div class="modal-footer">
                <div class="col-sm-12" align="center">
                  <button type="submit" name="process" value="excel" class="btn btn-success btn-flat"><i class="fa fa-download"></i> Download .xls</button>
				  <button type="submit" name="process" value="csv" class="btn btn-primary btn-flat"><i class="fa fa-download"></i> Download .csv</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
      </table>
    </body>
  </html>
  </section>
</div>

<script>
  $(function () {
    $("#tanggal1").datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy'
    }).on('changeDate', function (selected) {
      var minDate = new Date(selected.date);
      minDate.setDate(minDate.getDate());
      $('#tanggal2').datepicker('setStartDate', minDate);
    });
 
    $("#tanggal2").datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy'
    }).on('changeDate', function (selected) {
      var minDate = new Date(selected.date);
      minDate.setDate(minDate.getDate());
      $('#tanggal1').datepicker('setEndDate', minDate);
    });
  });
</script>
<?php }?>