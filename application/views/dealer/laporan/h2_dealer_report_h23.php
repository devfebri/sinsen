<?php 

function bln($a){
  $bulan=$bl=$month=$a;
  switch($bulan)
  {
    case"1":$bulan="Januari"; break;
    case"2":$bulan="Februari"; break;
    case"3":$bulan="Maret"; break;
    case"4":$bulan="April"; break;
    case"5":$bulan="Mei"; break;
    case"6":$bulan="Juni"; break;
    case"7":$bulan="Juli"; break;
    case"8":$bulan="Agustus"; break;
    case"9":$bulan="September"; break;
    case"10":$bulan="Oktober"; break;
    case"11":$bulan="November"; break;
    case"12":$bulan="Desember"; break;
  }
  $bln = $bulan;
  return $bln;
}

function mata_uang3($a){
     return number_format($a, 0, ',', '.');       
}

?>

<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 25px;
  padding-left: 4px;
  padding-right: 4px;  
}
.vertical-text{
  writing-mode: lr-tb;
  text-orientation: mixed;
}
.rotate {
  -webkit-transform: rotate(-90deg);
  -moz-transform: rotate(-90deg);
}
#mySpan{
  writing-mode: vertical-lr; 
  transform: rotate(180deg);
}
</style>

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
            <form class="form-horizontal" id="frm" method="post" action= "dealer/h2_dealer_report_h23/downloadReport" enctype="multipart/form-data">
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Pilihan Format</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="type" id="type">
                      <option value="general_report">General Report</option>
                      <option value="trx">Data Transaksional</option>
                      <optgroup label="Data Unit Entry">
                        <option value="all">All Format</option>
                        <option value="all_ahass">Total UE dan ToJ per AHASS</option>
                        <option value="type_motor">Total UE dan ToJ per Type Motor</option>
                        <option value="tgl_trx">Total UE dan ToJ per Tanggal & Jam Transaksi</option>
                        <!-- <option value="ue_jam">Total UE Jam Transaksi</option> -->
                        <option value="mekanik">Total UE dan ToJ per Mekanik</option>
                        <option value="sa">Total UE dan ToJ per SA</option>
                      </optgroup>
                      <optgroup label="Data Analisa ToJ">
                        <option value="toj">Data Analisa ToJ>1</option>
                        <option value="toj11">Data Analisa ToJ 1:1</option>
                      </optgroup>
                      <optgroup label="Data Revenue">
                        <!-- <option value="all_rev">Data Revenue All</option> -->
                        <option value="jasa_rev">Data Detail Revenue Jasa</option>
                        <option value="part_rev">Data Detail Revenue Parts dan Oli</option>
                        <option value="pos_rev">Data Detail Revenue Pos Service</option>
                        <option value="ahass_rev">Data Detail Revenue AHASS</option>
                        <option value="ap_rev">Data Detail Revenue per Sumber Activity Promotion AHASS</option>
                      </optgroup>
                      <optgroup label="Data Mekanik">
                        <option value="mekanik_detail">Data Mekanik</option>
                      </optgroup>
                      <optgroup label="Data UE">
                        <option value="ue_alasan">Data UE per Alasan ke AHASS </option>
                        <option value="ue_motor">Data UE per Segment Type Motor </option>
                        <option value="ue_ap">Data UE per Sumber Activity Promotion </option>
                        <option value="ue_ac">Data UE per Sumber Activity Capacity </option>
                      </optgroup>
                      <optgroup label="Data WO dan Direct Sales">
                        <option value="report_picking_slip">Report Picking Slip</option>
                        <option value="report_wo_gantung">Report WO Gantung </option>
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
                  <button type="submit" name="process" value="excel" class="btn btn-info btn-flat"><i class="fa fa-download"></i> Download .xls</button>
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