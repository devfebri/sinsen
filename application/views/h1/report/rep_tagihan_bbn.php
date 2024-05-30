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
    
    <?php
    if($set=="view"){
    ?>
    

    <div class="box box-default">
      <div class="box-header with-border">        
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h1/rep_tagihan_bbn/filter" id="frm" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                                                                              
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Awal</label>
                  <div class="col-sm-2">
                    <input type="text" autocomplete="off" required placeholder="Tanggal Awal" name="tgl1" id="tanggal" class="form-control">
                  </div>
                  <div class="col-sm-2">
                    <input type="text" autocomplete="off" required placeholder="Tanggal Akhir" name="tgl2" id="tanggal2" class="form-control">
                  </div>                                                                                                             
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">                    
                    <select class="form-control select2" required name="id_dealer">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_dealer->result() as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->kode_dealer_md - $val->nama_dealer</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>                                                                           
                  <div class="col-sm-2">
                    <button type="submit" name="process" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-search"></i> Cari</button>                                                      
                  </div>                             
                </div>
              </div><!-- /.box-body -->                                         
            </form>
            <!-- <div id="imgContainer"></div> -->
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    
    <?php }else{ ?>
    
    
    <div class="box box-default">
      <div class="box-header with-border">        
        <div class="row">
          <div class="col-md-12">
             <form class="form-horizontal" action="h1/rep_tagihan_bbn/filter" id="frm" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                                                                              
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Awal</label>
                  <div class="col-sm-2">
                    <input type="text" autocomplete="off" value="<?php echo $tgl1 ?>" required placeholder="Tanggal Awal" name="tgl1" id="tanggal" class="form-control">
                  </div>
                  <div class="col-sm-2">
                    <input type="text" autocomplete="off" value="<?php echo $tgl2 ?>" required placeholder="Tanggal Akhir" name="tgl2" id="tanggal2" class="form-control">
                  </div>                                                                                                             
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">                    
                    <select class="form-control select2" required name="id_dealer">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_dealer->result() as $val) {
                        if($id_dealer==$val->id_dealer) $r = "selected";
                            else $r = "";
                        echo "
                        <option $r value='$val->id_dealer'>$val->kode_dealer_md - $val->nama_dealer</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>                                                                           
                  <div class="col-sm-2">
                    <button type="submit" name="process" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-search"></i> Cari</button>                                                      
                  </div>                             
                </div>
              </div><!-- /.box-body -->                                         
            </form>
            <form action='h1/rep_tagihan_bbn/aksi' method="POST">
                <input type='hidden' name="id_dealer" value="<?php echo $id_dealer ?>">
                <input type='hidden' name="tgl1" value="<?php echo $tgl1 ?>">
                <input type='hidden' name="tgl2" value="<?php echo $tgl2 ?>">
                
                <table id="" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                          <th width="5%">No</th>
                          <th>Tgl BASTD</th>              
                          <th>No BASTD</th>              
                          <th width="5%">Qty</th>
                          <th width="10%">Nominal</th>
                          <th width="10%">Checklist</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no=1;
                    $t_qty=0;$t_tot=0;
                    foreach($dt_bastd->result() as $isi){
                        $jum = $dt_bastd->num_rows();
                        echo "
                        <tr>
                            <td>$no</td>
                            <td>$isi->tgl_bastd</td>
                            <td>$isi->no_bastd</td>
                            <td align='right'>$isi->jum</td>
                            <td align='right'>".mata_uang3($isi->nominal)."</td>
                            <td>
                                <input type='hidden' value='$isi->no_bastd' name='no_bastd_$no'>
                                <input type='hidden' name='jml' value='$jum'>
                                <input type='checkbox' name='check_$no' value='1'>
                            </td>
                        </tr>
                        ";
                        $no++;
                        $t_qty += $isi->jum;
                        $t_tot += $isi->nominal;
                    }
                    ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan='3'>Total</th>
                            <td align='right'><b><?php echo mata_uang3($t_qty) ?></b></td>
                            <td align='right'><b><?php echo mata_uang3($t_tot) ?></b></td>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
                <!--<div class="col-sm-2">-->
                <!--    <button type="submit" name="rekap" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-file"></i> Rekap</button>                                                      -->
                <!--</div>-->
                <div class="col-sm-2">
                    <button type="submit" name="surat" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-print"></i> Cetak Surat</button>                                                      
                </div>
                <div class="col-sm-2">
                    <a href='h1/rep_tagihan_bbn/cetak_kuitansi' class="btn bg-maroon btn-block btn-flat"><i class="fa fa-print"></i> Cetak Kuitansi</a>
                </div>                             
            </form>
                
            <!-- <div id="imgContainer"></div> -->
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    
    <?php } ?>
    
    
</section>
</div>
    
    