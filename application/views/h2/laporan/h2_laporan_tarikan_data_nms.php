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
<?php if($set=="view"){?>
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
            <form class="form-horizontal" id="frm" method="post" action= "h2/h2_laporan_tarikan_data_nms/download" enctype="multipart/form-data">
              <div class="box-body">                                                              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-1 control-label">Start Date</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control datepicker" name="tgl1" value="<?= date('Y-m-d') ?>" id="tanggal1">
                  </div>  

                  <label for="inputEmail3" class="col-sm-1 control-label">End Date</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control datepicker" name="tgl2" value="<?= date('Y-m-d') ?>" id="tanggal2">
                  </div>                                     
                </div>             

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-1 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer" id="id_dealer">
                      <option value="all">All Dealers</option>
                      <?php 
                      $sql_dealer = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1 ORDER BY ms_dealer.id_dealer ASC");
                      foreach ($sql_dealer->result() as $isi) {
                        echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md - $isi->nama_dealer</option>";
                      }?>
                    </select>
                  </div>
                      <br><br>

                  <?php if(!$this->config->item('ahm_only')){ ?>
                    <div class="col-sm-2">
                      <button type="submit" name="process" value="excel" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-download"></i> Download .xls</button>                                                      
                    </div>   
                    <div class="col-sm-2">
                      <button type="submit" name="process" value="excel_v2" class="btn bg-green btn-block btn-flat"><i class="fa fa-download"></i> Download V2 .xls</button>                                                      
                    </div>
                  <?php } ?>
  		            <div class="col-sm-3">
                    <button type="submit"  name="process" value="csv" class="btn bg-blue btn-block btn-flat"><i class="fa fa-download"></i> Download CSV (AHM)</button>                                                      
                  </div>                  
                </div>     
                
                <div class="form-group">
                  <div class="col-sm-3">
                    <button type="submit" name="process" value="excel_v3" class="btn bg-white btn-block btn-flat"><i class="fa fa-download"></i> Download New Version (AHM).xls</button>                                                      
                  </div>
                  
                  <div class="col-sm-3">
                    <button type="submit" name="process" value="excel_nota" class="btn bg-yellow btn-block btn-flat"><i class="fa fa-download"></i> Download NSC (Tanpa PKB).xls</button>                                                      
                  </div>   
                </div>
              </div><!-- /.box-body -->              

              <div class="box-footer">
                <div class="loader" style="display: none;">
                  <center>
                    <img src="assets/loader-new.gif" width="200">
                  </center>
                </div>                                                              

                <div style="min-height: 600px">                 
                  <iframe style="overflow: auto; border: 0px solid #fff; width: 100%; height: 602px;margin-bottom: -5px;" id="showReport"></iframe>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
      </table>
    </body>
  </html>
  </section>
</div>
        <?php }?>