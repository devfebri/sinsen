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
            <form class="form-horizontal" id="frm" method="post" action= "dealer/h2_dealer_laporan_promo/downloadReport" enctype="multipart/form-data">
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
                  <label for="promoOption" class="col-sm-2 control-label">Type Promo</label>
                  <div class="col-sm-4">
                      <select class="form-control" id="promoOption" name="promoOption">
                          <option disabled value="">--Pilih Promo--</option>
                          <option value="promo_servis">Promo Servis</option>
                          <option value="promo_part">Promo Part</option>
                      </select>
                  </div> 
                  <label for="inputEmail3" id="option_promo_servis" class="col-sm-1 control-label">Promo Servis</label>
                  <div class="col-sm-4" id="nama_promo_servis">
                    <select class="form-control select2" name="type_jasa" id="type_jasa">
                        <?php foreach($promo_servis->result() as $row){?>
                          <option value="<?php echo $row->id_promo ?>"> <?php echo $row->id_promo ?> - <?php echo $row->nama_promo ?> </option>
                        <?php }?>
                    </select>
                  </div>
                  <label for="inputEmail3" id="option_promo_part" class="col-sm-1 control-label">Promo Part</label>
                  <div class="col-sm-4" id="nama_promo_part">
                    <select style="width: 300px;"class="form-control select2" name="type_part" id="type_part">
                        <?php foreach($promo_part->result() as $row){?>
                          <option  value="<?php echo $row->id_promo ?>"> <?php echo $row->id_promo ?> - <?php echo $row->nama ?> </option>
                        <?php }?>
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
  
  $('#option_promo_part').hide();
  // $('#nama_promo_servis').hide();
  $('#nama_promo_part').hide();
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

  $(document).ready(function() {
    $('#promoOption').change(function () {
      var optionSelected = $(this).find("option:selected");
      var valueSelected  = optionSelected.val();
      var textSelected   = optionSelected.text();
      if(valueSelected == 'promo_servis'){
        $('#option_promo_servis').show();
        $('#nama_promo_servis').show();
        $('#option_promo_part').hide();
        $('#nama_promo_part').hide();
      }
      if(valueSelected == 'promo_part'){
        $('#nama_promo_servis').hide();
        $('#option_promo_part').show();
        $('#option_promo_servis').hide();
        $('#nama_promo_part').show();
      }
    });
  });
</script>
<?php }?>