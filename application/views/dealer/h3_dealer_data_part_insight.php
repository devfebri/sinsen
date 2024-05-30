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
    <li class="">H2</li>
    <li class="">Laporan</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <div class="box box-default">
      <div class="box-header with-border">        
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" id="frm" method="post" action= "dealer/h3_dealer_data_part_insight/downloadExcel" enctype="multipart/form-data">
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
                  <label for="inputEmail3" class="col-sm-1 control-label">Pilihan Format</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="type" id="type">
                      <optgroup label="Part Consumption">
                        <option value="ps_channel">Part Consumption Amount based on Channel, Amount by Service, by Jenis Kelompok, Amount by TOBPM, Amount by Grouping Parts, Amount by Target Achievement, Alert Growth</option>
                        <option value="ps_avg_grouping_part">Average Grouping Parts</option>
                        <option value="ps_details">Part Consumption Details, Amount by 9 Segment, Amount by Production Year</option>
                      </optgroup>
                      <optgroup label="Stock Level">
                        <option value="sl_details">Total Penjualan Dealer, Top 10 Penjualan berdasarkan Grouping Parts, Details</option>
                        <option value="sl_dealer">Stock Level berdasarkan Dealer</option> 
                      </optgroup>
                      <optgroup label="Hotline">
                        <option value="hlo_dealer">Hotline Order berdasarkan Nama Dealer, Kota, Status Order, Series, Tahun Perakitan, Grouping Parts, Lead Time Fulfillment,Outstanding, Outstanding Hotline Details, Service Rate by QTY</option>
                      </optgroup>
                    </select>
                  </div>                  
                </div>  
              </div><!-- /.box-body -->              
              <div class="modal-footer">
                <div class="col-sm-12" align="center">
                  <button type="submit" name="process" value="excel" class="btn btn-success btn-flat" disabled><i class="fa fa-download"></i> Download .xls</button>
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