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
            <form class="form-horizontal" action="h1/rep_pemenuhan_po_md/download" id="frm" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                                              
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-4">
                    <select name="bulan" class="form-control select2" required>                      
                      <?php 
                      $m = date("m"); 
                      for ($i=1; $i <= 12; $i++) { 
                        if($i==$m) $select="selected";
                          else $select = ''; 
                        $i_j = bln($i);    
                        echo "<option $select value='$i'>$i_j</option>";                                                
                      } ?> 
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>                                                  
                  <div class="col-sm-4">
                    <input type="number" class="form-control" required placeholder="Tahun" name="tahun" value="<?php echo date('Y'); ?>">
                  </div>                
                </div>                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Laporan</label>                                                  
                  <div class="col-sm-4">
                    <select required name="tipe" class="form-control">
                      <option value="">- choose -</option>
                      <option value='PO'>PO MD (.UPO)</option>
                      <option value='Penerimaan'>Pemenuhan Penerimaan</option>
                      <option value='DO'>Pemenuhan DO</option>
                    </select>
                  </div> 
                  <div class="col-sm-2">
                    <button type="submit" name="process" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-download"></i> Download</button>                                                      
                  </div>                             
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
    
    