<?php 
function bln(){
  $bulan=$bl=$month=date("m");
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
</style>
<base href="<?php echo base_url(); ?>" />
<body onload="take_kec()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Customer</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="insert"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <ul class="nav nav-pills">
            <?php 
            $isi = $this->input->get('p');
            if($isi=="" OR $isi=='joint_promo'){
              $c1 = "class='active'";
              $c2 = "";
            }else{
              $c2 = "class='active'";
              $c1 = "";
            }
            ?>
            <li <?php echo $c1 ?> role="presentation"><a href="dealer/bantuan_bbn_d">Joint Promo</a></li>
            <li <?php echo $c2 ?> role="presentation"><a href="dealer/bantuan_bbn_d/index?p=luar_provinsi">Luar Provinsi</a></li>
          </ul>          
        </h3>        
      </div><!-- /.box-header -->
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/bantuan_bbn_d">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                        
                
        ?>
        <div id="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="dealer/bantuan_bbn_d/save?p=joint_promo" method="post" enctype="multipart/form-data">
              <div class="box-body">                              
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="hidden" id="no_mesin" required class="form-control" placeholder="No mesin" name="no_mesin" autocomplete="off">
                    <input type="text" id="no_mesin2" required readonly class="form-control" placeholder="No mesin" name="no_mesin" autocomplete="off">
                  </div>
                  <div class="col-sm-1">
                    <button class="btn btn-primary btn-flat" type="button" data-toggle="modal" data-target="#Npwpmodal"><i class="fa fa-browse"></i> Browse</button>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="No Rangka" id="no_rangka" name="no_rangka" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Alamat" id="alamat" readonly name="alamat" autocomplete="off">                    
                  </div>
                </div>                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama STNK/BPKB</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama STNK/BKPB" id="nama_stnk" name="nama_stnk" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Kelurahan" id="kelurahan" readonly name="kelurahan" autocomplete="off">                    
                  </div>
                </div>                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Tipe Kendaraan" id="tipe_ahm" name="tipe_ahm" readonly autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Kecamatan" id="kecamatan" readonly name="kecamatan" autocomplete="off">                    
                  </div>
                </div>                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Warna" readonly id="warna" name="warna" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kabupaten</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Kabupaten" id="kabupaten" readonly name="kabupaten" autocomplete="off">                    
                  </div>
                </div>                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No KTP" readonly id="no_ktp" name="no_ktp" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Provinsi" id="provinsi" readonly name="provinsi" autocomplete="off">                    
                  </div>
                </div>                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No HP" readonly id="no_hp" name="no_hp" autocomplete="off">                    
                  </div>                  
                </div>                                
              </div>
            </div>
          </div>        
        </div><!-- /.box-body -->
        <div class="box-footer">
          <div class="col-sm-2">
          </div>
          <div class="col-sm-10">
            <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
            <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
          </div>
        </div><!-- /.box-footer -->
      </form>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="insert2"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <ul class="nav nav-pills">
            <?php 
            $isi = $this->input->get('p');
            if($isi=="" OR $isi=='joint_promo'){
              $c1 = "class='active'";
              $c2 = "";
            }else{
              $c2 = "class='active'";
              $c1 = "";
            }
            ?>
            <li <?php echo $c1 ?> role="presentation"><a href="dealer/bantuan_bbn_d">Joint Promo</a></li>
            <li <?php echo $c2 ?> role="presentation"><a href="dealer/bantuan_bbn_d/index?p=luar_provinsi">Luar Provinsi</a></li>
          </ul>          
        </h3>        
      </div><!-- /.box-header -->
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/bantuan_bbn_d/index?p=luar_provinsi">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                        
                
        ?>
        <div id="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="dealer/bantuan_bbn_d/save?p=luar_provinsi" method="post" enctype="multipart/form-data">
              <div class="box-body">                              
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Faktur</label>
                  <div class="col-sm-4">                    
                    <input type="text" required class="form-control" placeholder="No Faktur" name="no_faktur" autocomplete="off">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Faktur</label>
                  <div class="col-sm-4">                    
                    <input type="text" required id="tanggal2" class="form-control" placeholder="Tgl Faktur" name="tgl_faktur" autocomplete="off">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pemohon</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Pemohon" name="pemohon" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No Mesin" name="no_mesin" autocomplete="off">                    
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No Rangka" name="no_rangka" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="id_tipe_kendaraan"  name="id_tipe_kendaraan" onchange="cek_bbn()" required>
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_tipe->result() as $val) {
                        echo "
                        <option value='$val->id_tipe_kendaraan'>$val->id_tipe_kendaraan - $val->tipe_ahm</option>;
                        ";
                      }
                      ?>
                    </select> 
                  </div>
                </div>                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="id_warna"  name="id_warna">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_warna->result() as $val) {
                        echo "
                        <option value='$val->id_warna'>$val->id_warna - $val->warna</option>;
                        ";
                      }
                      ?>
                    </select> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun Produksi</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Tahun Produksi" name="tahun_produksi" autocomplete="off">                    
                  </div>
                </div>                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No KTP" name="no_ktp" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama Konsumen" name="nama_konsumen" autocomplete="off">                    
                  </div>
                </div>                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Alamat" name="alamat" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No Telp" name="no_telp" autocomplete="off">                    
                  </div>
                </div>                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                  <div class="col-sm-4">
                    <input type="hidden" readonly name="id_kelurahan" id="id_kelurahan">                      
                    <input required type="text" onpaste="return false" onkeypress="return nihil(event)"  name="kelurahan" data-toggle="modal" placeholder="Kelurahan" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()">                                          
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                    <input type="text" class="form-control" readonly id="kecamatan" placeholder="Kecamatan"  name="kecamatan" required>                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kabupaten" id="id_kabupaten">
                    <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten" id="kabupaten" name="kabupaten" required>                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_provinsi" id="id_provinsi">
                    <input type="text" class="form-control" readonly placeholder="Provinsi" id="provinsi" name="provinsi" required>                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Gadis Ibu Kandung</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama Gadi Ibu Kandung" name="nama_gadis_ibu" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Tgl Lahir" name="tgl_lahir_ibu" id='tanggal3' autocomplete="off">                    
                  </div>
                </div>                  

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="pekerjaan"  name="pekerjaan">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_pekerjaan->result() as $val) {
                        echo "
                        <option value='$val->id_pekerjaan'>$val->pekerjaan</option>;
                        ";
                      }
                      ?>
                    </select> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Apakah ini dari pemenang atau bukan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pemenang">
                      <option value="">- choose -</option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>
                </div>                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tagih Ke</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Tagih Ke" name="tagih_ke" autocomplete="off">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Pemenang Dari</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Pemenang Dari" name="pemenang_dari" autocomplete="off">                    
                  </div>                  
                </div>                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya Adm</label>
                  <div class="col-sm-4">
                    <input type="text" onchange="hitung()" id="biaya_adm" class="form-control" placeholder="Biaya Adm" name="biaya_adm" autocomplete="off">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya BBN</label>
                  <div class="col-sm-4">
                    <input type="text" onchange="hitung()" readonly class="form-control" placeholder="Biaya BBN" id="biaya_bbn" name="biaya_bbn" autocomplete="off">                    
                  </div>                  
                </div>                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Total</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Total" name="total" id="total" autocomplete="off">                    
                  </div>                                    
                </div>                                
              </div>
            </div>
          </div>        
        </div><!-- /.box-body -->
        <div class="box-footer">
          <div class="col-sm-2">
          </div>
          <div class="col-sm-10">
            <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
            <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
          </div>
        </div><!-- /.box-footer -->
      </form>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="edit"){
      $row = $dt_bantuan->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <ul class="nav nav-pills">
            <?php 
            $isi = $this->input->get('p');
            if($isi=="" OR $isi=='joint_promo'){
              $c1 = "class='active'";
              $c2 = "";
            }else{
              $c2 = "class='active'";
              $c1 = "";
            }
            ?>
            <li <?php echo $c1 ?> role="presentation"><a href="dealer/bantuan_bbn_d">Joint Promo</a></li>
            <li <?php echo $c2 ?> role="presentation"><a href="dealer/bantuan_bbn_d/index?p=luar_provinsi">Luar Provinsi</a></li>
          </ul>          
        </h3>        
      </div><!-- /.box-header -->
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/bantuan_bbn_d/index?p=luar_provinsi">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                        
                
        ?>
        <div id="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="dealer/bantuan_bbn_d/update?p=luar_provinsi" method="post" enctype="multipart/form-data">
              <div class="box-body">                              
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Faktur</label>
                  <div class="col-sm-4">                    
                    <input type="hidden" name="id_bantuan_bbn_luar" value="<?php  echo $row->id_bantuan_bbn_luar ?>">
                    <input type="text" required class="form-control" placeholder="No Faktur" name="no_faktur" value="<?php  echo $row->no_faktur  ?>" autocomplete="off">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Faktur</label>
                  <div class="col-sm-4">                    
                    <input type="text" required id="tanggal2" class="form-control" placeholder="Tgl Faktur" name="tgl_faktur" value="<?php  echo $row->tgl_faktur  ?>" autocomplete="off">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pemohon</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Pemohon" name="pemohon" autocomplete="off" value="<?php  echo $row->pemohon   ?>">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No Mesin" name="no_mesin" autocomplete="off" value="<?php  echo $row->no_mesin  ?>">                    
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No Rangka" name="no_rangka" autocomplete="off" value="<?php  echo $row->no_rangka   ?>">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="id_tipe_kendaraan" required onchange="cari_bbn()" name="id_tipe_kendaraan">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_tipe->result() as $val) {
                        $rs="";
                        if($val->id_tipe_kendaraan == $row->id_tipe_kendaraan) $rs = "selected";
                        echo "
                        <option $rs value='$val->id_tipe_kendaraan'>$val->id_tipe_kendaraan - $val->tipe_ahm</option>;
                        ";
                      }
                      ?>
                    </select> 
                  </div>
                </div>                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="id_warna"  name="id_warna">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_warna->result() as $val) {
                        $rt="";
                        if($val->id_warna == $row->id_warna) $rt = "selected";
                        echo "
                        <option $rt value='$val->id_warna'>$val->id_warna - $val->warna</option>;
                        ";
                      }
                      ?>
                    </select> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun Produksi</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Tahun Produksi" value="<?php  echo $row->tahun_produksi   ?>" name="tahun_produksi" autocomplete="off">                    
                  </div>
                </div>                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No KTP" name="no_ktp" autocomplete="off" value="<?php  echo $row->no_ktp ?>">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama Konsumen" name="nama_konsumen" autocomplete="off" value="<?php  echo $row->nama_konsumen   ?>">                    
                  </div>
                </div>                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Alamat" name="alamat" autocomplete="off" value="<?php  echo $row->alamat ?>">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No Telp" name="no_telp" autocomplete="off" value="<?php  echo $row->no_telp ?>">                    
                  </div>
                </div>                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                  <div class="col-sm-4">
                    <input type="hidden" readonly name="id_kelurahan" value="<?php  echo $row->id_kelurahan ?>"  id="id_kelurahan">                      
                    <input required type="text" onpaste="return false" onkeypress="return nihil(event)"  name="kelurahan" data-toggle="modal" placeholder="Kelurahan" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()">                                          
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                    <input type="text" class="form-control" readonly id="kecamatan" placeholder="Kecamatan"  name="kecamatan" required>                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kabupaten" id="id_kabupaten">
                    <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten" id="kabupaten" name="kabupaten" required>                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_provinsi" id="id_provinsi">
                    <input type="text" class="form-control" readonly placeholder="Provinsi" id="provinsi" name="provinsi" required>                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Gadis Ibu Kandung</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php  echo $row->nama_gadis_ibu   ?>" class="form-control" placeholder="Nama Gadi Ibu Kandung" name="nama_gadis_ibu" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php  echo $row->tgl_lahir_ibu  ?>" class="form-control" placeholder="Tgl Lahir" name="tgl_lahir_ibu" id='tanggal3' autocomplete="off">                    
                  </div>
                </div>                  

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="pekerjaan"  name="pekerjaan">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_pekerjaan->result() as $val) {
                        $rq="";
                        if($val->id_pekerjaan == $row->id_pekerjaan) $rq = "selected";
                        echo "
                        <option $rq value='$val->id_pekerjaan'>$val->pekerjaan</option>;
                        ";
                      }
                      ?>
                    </select> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Apakah ini dari pemenang atau bukan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pemenang">
                      <option <?php if($row->pemenang == "") echo "selected"; ?>>- choose -</option>
                      <option <?php if($row->pemenang == "Ya") echo "selected"; ?>>Ya</option>
                      <option <?php if($row->pemenang == "Tidak") echo "selected"; ?>>Tidak</option>
                    </select>
                  </div>
                </div>                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tagih Ke</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Tagih Ke" name="tagih_ke" value="<?php  echo $row->tagih_ke   ?>" autocomplete="off">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Pemenang Dari</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Pemenang Dari" name="pemenang_dari" value="<?php  echo $row->pemenang_dari  ?>" autocomplete="off">                    
                  </div>                  
                </div>                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya Adm</label>
                  <div class="col-sm-4">
                    <input type="text" onchange="hitung()" id="biaya_adm" class="form-control" placeholder="Biaya Adm" name="biaya_adm" value="<?php  echo $row->biaya_adm ?>" autocomplete="off">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya BBN</label>
                  <div class="col-sm-4">
                    <input type="text" onchange="hitung()" readonly class="form-control" placeholder="Biaya BBN" id="biaya_bbn" value="<?php  echo $row->biaya_bbn  ?>" name="biaya_bbn" autocomplete="off">                    
                  </div>                  
                </div>                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Total</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Total" value="<?php  echo $row->total ?>" name="total" id="total" autocomplete="off">                    
                  </div>                                    
                </div>                                
              </div>
            </div>
          </div>        
        </div><!-- /.box-body -->
        <div class="box-footer">
          <div class="col-sm-2">
          </div>
          <div class="col-sm-10">
            <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
            <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
          </div>
        </div><!-- /.box-footer -->
      </form>
    </div><!-- /.box -->
      
    <?php 
    }elseif($set=='view2'){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <ul class="nav nav-pills">
            <?php 
            $isi = $this->input->get('p');
            if($isi=="" OR $isi=='joint_promo'){
              $c1 = "class='active'";
              $c2 = "";
            }else{
              $c2 = "class='active'";
              $c1 = "";
            }
            ?>
            <li <?php echo $c1 ?> role="presentation"><a href="dealer/bantuan_bbn_d">Joint Promo</a></li>
            <li <?php echo $c2 ?> role="presentation"><a href="dealer/bantuan_bbn_d/index?p=luar_provinsi">Luar Provinsi</a></li>
          </ul>          
        </h3>        
      </div><!-- /.box-header -->
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/bantuan_bbn_d/add?p=luar_provinsi">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
          <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                        
                
        ?>
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>              
              <th>No Faktur</th>              
              <th>No Mesin</th>              
              <th>Pemohon</th>              
              <th>Nama Konsumen</th>
              <th>Tipe</th>              
              <th>Warna</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_luar->result() as $row) {                 
            if($row->status == 'input'){
                $status = "<span class='label label-primary'>$row->status</span>";              
                $tom = "<a data-toggle='tooltip' title='Edit' href='dealer/bantuan_bbn_d/edit?p=luar_provinsi&id=$row->id_bantuan_bbn_luar'><button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>
                        <a data-toggle='tooltip' onclick=\"return confirm('Are you sure to send this data to MD?')\" title='Send' href='dealer/bantuan_bbn_d/send?p=luar_provinsi&id=$row->id_bantuan_bbn_luar'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-send'></i> Send to MD</button></a>";
            }elseif($row->status == 'waiting_approval'){
                $status = "<span class='label label-warning'>$row->status</span>";              
                $tom = "";
            }elseif($row->status == 'approved'){
                $status = "<span class='label label-success'>$row->status</span>";              
                $tom = "";
            }elseif($row->status == 'rejected'){
                $status = "<span class='label label-danger'>$row->status</span>";              
                $tom = "";
            }
            echo "
            <tr>
              <td>$no</td>
              <td>$row->no_faktur</td>              
              <td>$row->no_mesin</td>
              <td>$row->pemohon</td>
              <td>$row->nama_konsumen</td>                            
              <td>$row->tipe_ahm</td>                                                                      
              <td>$row->warna</td>                                                                      
              <td>$status</td>                                                                      
              <td>$tom</td>
            </tr>";            
          $no++;
          }
          ?>                
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->


    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <ul class="nav nav-pills">
            <?php 
            $isi = $this->input->get('p');
            if($isi=="" OR $isi=='joint_promo'){
              $c1 = "class='active'";
              $c2 = "";
            }else{
              $c2 = "class='active'";
              $c1 = "";
            }
            ?>
            <li <?php echo $c1 ?> role="presentation"><a href="dealer/bantuan_bbn_d">Joint Promo</a></li>
            <li <?php echo $c2 ?> role="presentation"><a href="dealer/bantuan_bbn_d/index?p=luar_provinsi">Luar Provinsi</a></li>
          </ul>          
        </h3>        
      </div><!-- /.box-header -->
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/bantuan_bbn_d/add?p=joint_promo">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
          <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                        
                
        ?>
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>              
              <th>Nama Konsumen</th>              
              <th>No Mesin</th>              
              <th>No Rangka</th>              
              <th>Tipe</th>
              <th>Warna</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_joint->result() as $row) {                 
            echo "
            <tr>
              <td>$no</td>
              <td>$row->nama_stnk</td>              
              <td>$row->no_mesin</td>
              <td>$row->no_rangka</td>
              <td>$row->tipe_ahm</td>                            
              <td>$row->warna</td>                                                                      
            </tr>
            ";
          $no++;
          }
          ?>                
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }
    ?>
  </section>
</div>

<div class="modal fade" id="Npwpmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Cari No Mesin
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example3" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="10%"></th>              
              <th>No Mesin</th>
              <th>Tipe-Warna</th>                                    
              <th>Nama NPWP</th>                                                             
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          $id_dealer = $this->m_admin->cari_dealer();
          $dt_npwp = $this->db->query("SELECT *,tr_scan_barcode.no_mesin,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_sales_order_gc_nosin INNER JOIN tr_spk_gc ON tr_sales_order_gc_nosin.no_spk_gc = tr_spk_gc.no_spk_gc
            LEFT JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
            LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
            LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
            WHERE tr_spk_gc.jenis_gc = 'Joint Promo' AND tr_spk_gc.id_dealer = '$id_dealer'");
          foreach ($dt_npwp->result() as $ve2) {
            echo "
            <tr>"; ?>
              <td class="center">
                <button title="Choose" onClick="Choosenosin('<?php echo $ve2->no_mesin; ?>')" type="button" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
              </td>
              <?php echo "
              <td>$ve2->no_mesin</td>
              <td>$ve2->tipe_ahm</td>
              <td>$ve2->nama_npwp</td>";
              ?>                         
            </tr>
            <?php
            $no++;
          }
          ?>
          </tbody>
        </table>
      </div>      
    </div>
  </div>
</div>


<div class="modal fade" id="Kelurahanmodal">      
  <div class="modal-dialog" role="document" style="width: 50%">
    <div class="modal-content">
      <div class="modal-header">
        Search Kelurahan
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
       <table id="table" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>              
              <th>Kelurahan</th>
              <th>Kecamatan</th>
              <th>Kabupaten</th>
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>                     
          </tbody>
        </table>        
      </div>      
    </div>
  </div>
</div>
<script type="text/javascript">

var table;

$(document).ready(function() {
    //datatables
    table = $('#table').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('dealer/spk/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });
});

</script>
<script type="text/javascript">
function chooseitem(id_kelurahan){
  document.getElementById("id_kelurahan").value = id_kelurahan; 
  take_kec();
  $("#Kelurahanmodal").modal("hide");
}
function take_kec(){
  var id_kelurahan = $("#id_kelurahan").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/spk/take_kec')?>",
      type:"POST",
      data:"id_kelurahan="+id_kelurahan,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");                    
          $("#id_kecamatan").val(data[0]);                                                    
          $("#kecamatan").val(data[1]);                                                    
          $("#id_kabupaten").val(data[2]);                                                    
          $("#kabupaten").val(data[3]);                                                    
          $("#id_provinsi").val(data[4]);                                                    
          $("#provinsi").val(data[5]);                                                    
          $("#kelurahan").val(data[6]);                                                              
      } 
  })
}
function Choosenosin(no_mesin){
  document.getElementById("no_mesin").value = no_mesin; 
  take_nosin();
  $("#Npwpmodal").modal("hide");
}
function take_nosin(){
  var no_mesin = $("#no_mesin").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/bantuan_bbn_d/take_nosin')?>",
      type:"POST",
      data:"no_mesin="+no_mesin,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");  
          if(data[0]=='ok'){
            $("#no_mesin").val(data[1]);                                                    
            $("#no_mesin2").val(data[1]);                                                    
            $("#no_rangka").val(data[2]);                                                    
            $("#alamat").val(data[3]);                                                    
            $("#tipe_ahm").val(data[4]);                                                    
            $("#warna").val(data[5]);                                                    
            $("#no_ktp").val(data[6]);                                                    
            $("#no_hp").val(data[7]);                                                              
            $("#kelurahan").val(data[8]);                                                              
            $("#kecamatan").val(data[9]);                                                              
            $("#kabupaten").val(data[10]);                                                              
            $("#provinsi").val(data[11]);                                                              
          }else{
            alelrt(data[0]);
          }                  
      } 
  })
}

function cek_bbn(){
  var id_tipe_kendaraan = document.getElementById("id_tipe_kendaraan").value;
  $.ajax({
      url : "<?php echo site_url('dealer/bantuan_bbn_d/cari_bbn')?>",
      type:"POST",
      data:"id_tipe_kendaraan="+id_tipe_kendaraan,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#biaya_bbn").val(data[0]);                
      }        
  })
}
function hitung(){
  var biaya_bbn = document.getElementById("biaya_bbn").value;
  var biaya_adm = document.getElementById("biaya_adm").value;
  total = Number(biaya_bbn) + Number(biaya_adm);
  document.getElementById("total").value = total;
}
</script>