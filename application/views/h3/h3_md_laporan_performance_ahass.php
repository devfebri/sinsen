<style type="text/css">
  .myTable1 {
    margin-bottom: 0px;
  }

  .myt {
    margin-top: 0px;
  }

  .isi {
    height: 25px;
    padding-left: 4px;
    padding-right: 4px;
  }

  .vertical-text {
    writing-mode: lr-tb;
    text-orientation: mixed;
  }

  .rotate {
    -webkit-transform: rotate(-90deg);
    -moz-transform: rotate(-90deg);
  }

  #mySpan {
    writing-mode: vertical-lr;
    transform: rotate(180deg);
  }
</style>

<base href="<?php echo base_url(); ?>" />
<?php
if ($set == "view") {
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
        <li class="">Laporan Performance AHASS</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      </ol>
    </section>
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" id="frm" method="post" action="h3/h3_md_laporan_performance_ahass/downloadReport" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control datepicker" name="tgl1" value="<?= date('Y-m-d') ?>" id="tanggal1">
                    </div>
                    <label for="inputEmail3" class="col-sm-1 control-label">End Date</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control datepicker" name="tgl2" value="<?= date('Y-m-d') ?>" id="tanggal2">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" aria-label="Default select example" name="dealer" id="dealer">
                        <!-- <option selected disabled>Pilih Dealer</option> -->
                        <option value="all">All Dealers</option>
                        <?php foreach ($dealer as $row2) : ?>
                          <option value="<?php echo $row2->id_dealer ?>"><?php echo $row2->nama_dealer ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    
                    <label for="inputEmail3" class="col-sm-1 control-label">Pilihan Format</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" name="type" id="type">
                        <option value="laporan_performance_ahass">Laporan Performance AHASS</option>
                        <option value="laporan_product_value">Laporan Product Value</option>
                        <option value="laporan_penjualan_part_selling">Laporan Penjualan Part Selling</option>
                        <option value="laporan_penjualan_oil_amount">Laporan Penjualan AHM Oil by Amount</option>
                        <option value="laporan_penjualan_oil_botol">Laporan Penjualan AHM Oil by Botol</option>
                        <option value="laporan_penjualan_hga">Laporan Penjualan HGA dan Apparel</option>
                        <option value="laporan_performance_sales_parts">Performance Salesman Parts</option>
                        <option value="laporan_performance_sales_oil">Performance Salesman Oil</option>
                        <option value="laporan_sales_by_channel">Sales by Channel</option>
                        <option value="laporan_hlo_dealer">Report & Monitoring HLO Dealer</option>
                      </select>
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" align="center">
                    <button type="submit" id="submitBtn" name="process" value="excel" class="btn btn-info btn-flat"><i class="fa fa-download"></i> Download .xls</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      </body>

      </html>
    </section>
  </div>
  <script>
    $(function() {
      $("#tanggal1").datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy'
      }).on('changeDate', function(selected) {
        var minDate = new Date(selected.date);
        var maxDate = new Date(selected.date);
        minDate.setDate(minDate.getDate());
        maxDate.setDate(maxDate.getDate() + 30);
        $('#tanggal2').datepicker('setStartDate', minDate);
        $('#tanggal2').datepicker('setEndDate', maxDate);
      });

      $("#tanggal2").datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
      }).on('changeDate', function(selected) {
        var minDate = new Date(selected.date);
        // var maxDate = new Date(selected.date+30);
        minDate.setDate(minDate.getDate());
        // maxDate.setDate(maxDate.getDate());
        $('#tanggal1').datepicker('setEndDate', minDate);
      });
    });
  </script>
<?php } ?>