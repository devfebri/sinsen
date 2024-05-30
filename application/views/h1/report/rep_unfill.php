<?php 
function mata_uang3($a){
  if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
    if(is_numeric($a) AND $a != 0 AND $a != ""){
      return number_format($a, 0, ',', '.');
    }else{
      return $a;
    }        
}
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
?>

<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Laporan</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    
    

    <div class="box box-default">
      <div class="box-header with-border">        
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h1/rep_unfill/download" id="frm" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                                              
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Periode Shipping List</label>
                  <div class="col-sm-2">
                    <input type="text" autocomplete="off" required placeholder="Tgl Awal" id="tanggal1" name="tgl1" class="form-control">
                  </div>
                  <div class="col-sm-2">
                    <input type="text" autocomplete="off" required placeholder="Tgl Akhir" id="tanggal2" name="tgl2" class="form-control">
                  </div>                              
                
                  <div class="col-sm-2">
                    <button type="submit" name="process" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-download"></i> Download</button>                                                      
                  </div>                             
              </div><!-- /.box-body -->                           
            </form>
            <!-- <div id="imgContainer"></div> -->
          </div>
        </div>
      </div>
    </div><!-- /.box -->
</section>
</div>
    
    