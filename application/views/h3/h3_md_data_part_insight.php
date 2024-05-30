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
            <form class="form-horizontal" id="frm" method="post" action= "h3/h3_md_data_part_insight/downloadExcel" enctype="multipart/form-data">
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
                        <option value="dealer">Dealer</option>
                        <!-- <option value="tanggal_bulan">Tanggal-Bulan</option> -->
                        <option value="grouping_parts">Grouping Parts</option>
                        <option value="service">Service</option>
                        <option value="hlo_fulfilled">HLO-Fulfilled/Unfilfilled</option>
                        <option value="ms_target">Format Target Sales Out dan Stock Level Dealer</option>
                      </optgroup>
                      <optgroup label="Part Consumption">
                        <option value="ps_channel">Part Consumption Amount based on Channel, Amount by Service, by Jenis Kelompok, Amount by TOBPM, Amount by Grouping Parts, Amount by Target Achievement, Alert Growth</option>
                        <!-- <option value="ps_service">Part Consumption Amount by Service</option>
                        <option value="ps_jenis_kelompok">Part Consumption Amount by Jenis Kelompok</option>
                        <option value="ps_tobpm">Part Consumption Amount by TOBPM</option> -->
                        <!-- <option value="ps_production_year">Part Consumption Amount by Production Year</option> -->
                        <!-- <option value="ps_alert_growth">Alert Growth</option> -->
                        <!-- <option value="ps_target_achievement">Part Consumption Amount by Target Achievement</option> -->
                        <!-- <option value="ps_grouping_part">Part Consumption Amount by Grouping Parts</option> -->
                        <option value="ps_avg_grouping_part">Average Grouping Parts</option>
                        <!-- <option value="ps_9_segment">Part Consumption Amount by 9 Segment</option> -->
                        <option value="ps_details">Part Consumption Details, Amount by 9 Segment, Amount by Production Year</option>
                      </optgroup>
                      <optgroup label="Stock Level">
                        <!-- <option value="sl_penjualan_dealer">Total Penjualan berdasarkan Dealer</option> -->
                        <!-- <option value="sl_penjualan_grouping_parts">Top 10 Total Penjualan berdasarkan Grouping Parts</option> -->
                        <option value="sl_details">Total Penjualan Dealer, Top 10 Penjualan berdasarkan Grouping Parts, Details</option>
                        <option value="sl_dealer">Stock Level berdasarkan Dealer</option> 
                        <!-- <option value="sl_grouping_parts">Top 10 Stock Level berdasarkan Grouping Parts</option> Belum DUlu karena masih lambat -->
                      </optgroup>
                      <optgroup label="Hotline">
                        <!-- <option value="hlo_service_rate">Hotline Order Service Rate by QTY</option> -->
                        <!-- <option value="hlo_kota">Hotline Order berdasarkan Kota</option> -->
                        <option value="hlo_dealer">Hotline Order berdasarkan Nama Dealer, Kota, Status Order, Series, Tahun Perakitan, Grouping Parts, Lead Time Fulfillment,Outstanding, Outstanding Hotline Details, Service Rate by QTY</option>
                        <!-- <option value="hlo_status_order">Status Order</option>
                        <option value="hlo_series">Hotline Order berdasarkan Series</option> -->
                        <!-- <option value="hlo_production_year">Hotline Order berdasarkan Tahun Perakitan</option> -->
                        <!-- <option value="hlo_grouping_parts">Top 10 Hotline Order berdasarkan Grouping Parts</option> -->
                        <!-- <option value="hlo_lead_time_fulfillment">Lead Time Fulfillment</option> -->
                        <!-- <option value="hlo_outstanding">Outstanding</option> -->
                        <!-- <option value="hlo_outstanding_details">Outstanding Hotline Details</option> -->
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
                  <button type="submit" name="process" value="excel" class="btn btn-success btn-flat" disabled><i class="fa fa-download"></i> Download .xls</button>
				  <button type="submit" name="process" value="csv" class="btn btn-primary btn-flat"><i class="fa fa-download"></i> Download .csv</button>
                  <!-- <a type="button" href="https://app.powerbi.com/view?r=eyJrIjoiYjIwNTJjZmMtZGYyNS00NmI2LTllYTQtOTEwYjJkNjkyNDA5IiwidCI6IjBkMjQ0Mzc1LTJlMTktNDdlZi05YjNjLWVjOWE0MmM2MTMzNyIsImMiOjEwfQ%3D%3D" class="btn btn-warning btn-flat"><i class="fa-solid fa-gauge"></i></i> Akses Dashboard</a> -->
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