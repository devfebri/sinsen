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
    <?php 
    }elseif($set=="insert2"){
    ?>
    <div class="box box-default">
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
            <form class="form-horizontal" action="dealer/bantuan_bbn_d/save" method="post" enctype="multipart/form-data">
              <div class="box-body">        
		<div class="form-group">
		  <label for="inputEmail3" class="col-sm-2 control-label">Apakah dari pemenang? *</label>
                  <div class="col-sm-4">
                    <select id="pemenang" onchange="on_pemenang()" required class="form-control" name="pemenang">
                      <option>Tidak</option>
                      <option>Ya</option>
                    </select>
                  </div>
		  <div id="det_pemenang">   
                  <label for="inputEmail3" class="col-sm-2 control-label">Pemenang Dari *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="nama_pemenang" placeholder="Pemenang Dari" name="pemenang_dari" autocomplete="off">                    
                  </div>
		  </div> 
		</div>
	          
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pemohon</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Pemohon" name="pemohon" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tagih Ke</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Tagih Ke" name="tagih_ke" autocomplete="off">                    
                  </div>
                </div>    
                
		<div class="form-group">
                  <button class="btn btn-primary btn-block btn-flat" disabled="">Data Konsumen</button>   
                </div>
    
		 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen * (BBN)</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="Nama Konsumen" name="nama_konsumen" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">NPWP *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="NPWP" name="npwp" autocomplete="off">                    
                  </div>
		</div> 
		<div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No KK *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="No KK" name="no_kk" onkeypress="return number_only(event)" maxlength="16" minlength="16" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No KTP *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="No KTP" onkeypress="return number_only(event)" maxlength="16" minlength="16" name="no_ktp" autocomplete="off">                    
                  </div>
		</div> 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tempat Lahir *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="Tempat Lahir" name="tempat_lahir" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No TDP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No TDP" name="no_tdp" autocomplete="off">                    
                  </div>
                </div>    
		<div class="form-group">
 		  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="Tgl Lahir" name="tgl_lahir" id='tanggal4' autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="No HP" name="no_hp" onkeypress="return number_only(event)" autocomplete="off">                    
                  </div>
                </div>  
		<div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="Alamat" name="alamat" autocomplete="off">                    
                  </div>
		  <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No Telp" name="no_telp" autocomplete="off">                    
                  </div>
		</div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan *</label>
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan *</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="pekerjaan" required name="pekerjaan">
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
		</div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Gadis Ibu Kandung *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="Nama Gadis Ibu Kandung" name="nama_gadis_ibu" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir Ibu *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="Tgl Lahir Ibu" name="tgl_lahir_ibu" id='tanggal3' autocomplete="off">                    
                  </div>
                </div>   

		<div class="form-group">
                  <button class="btn btn-primary btn-block btn-flat" disabled="">Data Kendaraan</button>   
                </div>

		<div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Faktur AHM *</label>
                  <div class="col-sm-4">                    
                    <input type="text" required class="form-control" placeholder="No Faktur" name="no_faktur" autocomplete="off">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Jual/ Faktur *</label>
                  <div class="col-sm-4">                    
                    <input type="text" required id="tanggal2" class="form-control" placeholder="Tgl Faktur" name="tgl_faktur" autocomplete="off">
                  </div>                  
                </div>
                                
		<div class="form-group">   
		<label for="inputEmail3" class="col-sm-2 control-label">No Mesin *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="No Mesin" name="no_mesin" autocomplete="off">                    
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="No Rangka" name="no_rangka" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan *</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="id_tipe_kendaraan" required name="id_tipe_kendaraan" onchange="cek_bbn()">
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun Produksi *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="Tahun Produksi" onkeypress="return number_only(event)" maxlength="4" minlength="4" name="tahun_produksi" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna *</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="id_warna" required name="id_warna">
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
                </div>  

		<div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya Adm *</label>
                  <div class="col-sm-4">
                    <input type="text" onchange="hitung()" required id="biaya_adm" class="form-control" onkeypress="return number_only(event)" placeholder="Biaya Adm" name="biaya_adm" autocomplete="off">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya BBN *</label>
                  <div class="col-sm-4">
                    <input type="text" onchange="hitung()" readonly class="form-control" placeholder="Biaya BBN" id="biaya_bbn" name="biaya_bbn" autocomplete="off">                    
                  <input type="hidden" readonly class="form-control" placeholder="Biaya BBN BJ" id="biaya_bbn_bj" name="biaya_bbn_bj" autocomplete="off">                    
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
            <form class="form-horizontal" action="dealer/bantuan_bbn_d/update" method="post" enctype="multipart/form-data">
              <div class="box-body">

                <div class="form-group">
 		  <label for="inputEmail3" class="col-sm-2 control-label">Apakah dari pemenang? *</label>
                  <div class="col-sm-4">
                    <select id="pemenang" onchange="on_pemenang()" class="form-control" required name="pemenang">
                      <option <?php if($row->pemenang == "Tidak") echo "selected"; ?>>Tidak</option>
                      <option <?php if($row->pemenang == "Ya") echo "selected"; ?>>Ya</option>
                    </select>
                  </div>
		  <div id="det_pemenang">   
                  <label for="inputEmail3" class="col-sm-2 control-label">Pemenang Dari *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Pemenang Dari" name="pemenang_dari" value="<?php  echo $row->pemenang_dari  ?>" autocomplete="off">                    
                  </div>   
		  </div>
                </div>                                
                <div class="form-group">
		  <label for="inputEmail3" class="col-sm-2 control-label">Pemohon</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Pemohon" name="pemohon" autocomplete="off" value="<?php  echo $row->pemohon   ?>">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tagih Ke</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Tagih Ke" name="tagih_ke" value="<?php  echo $row->tagih_ke   ?>" autocomplete="off">                    
                  </div>                 
                </div>    

		<div class="form-group">
                  <button class="btn btn-primary btn-block btn-flat" disabled="">Data Konsumen</button>   
                </div>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen * (BBN)</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="Nama Konsumen" name="nama_konsumen" autocomplete="off" value="<?php  echo $row->nama_konsumen   ?>">                    
                  </div>
		<label for="inputEmail3" class="col-sm-2 control-label">NPWP *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="NPWP" name="no_npwp" autocomplete="off" value="<?php  echo $row->no_npwp ?>">                    
                  </div>
                </div>    
                <div class="form-group">
		<label for="inputEmail3" class="col-sm-2 control-label">No KK *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="No KK" maxlength="16" minlength="16" onkeypress="return number_only(event)" name="no_kk" autocomplete="off" value="<?php  echo $row->no_kk ?>">                    
                  </div>
		<label for="inputEmail3" class="col-sm-2 control-label">No KTP *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="No KTP" maxlength="16" minlength="16" onkeypress="return number_only(event)" name="no_ktp" autocomplete="off" value="<?php  echo $row->no_ktp ?>">                    
                  </div>
		</div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tempat Lahir *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="Tempat Lahir" name="tempat_lahir" autocomplete="off" value="<?php  echo $row->tempat_lahir ?>">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No TDP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No TDP" name="no_tdp" autocomplete="off"  value="<?php  echo $row->no_tdp ?>">                    
                  </div>
                </div>    
                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir *</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->tgl_lahir?>" class="form-control" required placeholder="Tgl Lahir" name="tgl_lahir" id='tanggal4' autocomplete="off">                    
                  </div> 
		  <label for="inputEmail3" class="col-sm-2 control-label">No HP *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="No HP" name="no_hp" autocomplete="off" onkeypress="return number_only(event)" value="<?php  echo $row->no_hp ?>">                    
                  </div>
                </div>      

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="Alamat" name="alamat" autocomplete="off" value="<?php  echo $row->alamat ?>">                    
                  </div>
 		  <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No Telp" name="no_telp" autocomplete="off" value="<?php  echo $row->no_telp ?>">                    
                  </div>
		</div>
            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan *</label>
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan *</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required id="pekerjaan"  name="pekerjaan">
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
		</div>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Gadis Ibu Kandung *</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php  echo $row->nama_gadis_ibu ?>" class="form-control" required placeholder="Nama Gadis Ibu Kandung" name="nama_gadis_ibu" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir Ibu *</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php  echo $row->tgl_lahir_ibu ?>" class="form-control" required placeholder="Tgl Lahir" name="tgl_lahir_ibu" id='tanggal3' autocomplete="off">                    
                  </div>
                </div>                  

		<div class="form-group">
                  <button class="btn btn-primary btn-block btn-flat" disabled="">Data Kendaraan</button>   
                </div>

		<div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Faktur AHM *</label>
                  <div class="col-sm-4">                    
                    <input type="hidden" name="id_bantuan_bbn_luar" value="<?php  echo $row->id_bantuan_bbn_luar ?>">
                    <input type="text" required class="form-control" placeholder="No Faktur" name="no_faktur" value="<?php  echo $row->no_faktur  ?>" autocomplete="off">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Jual/ Faktur *</label>
                  <div class="col-sm-4">                    
                    <input type="text" required id="tanggal2" class="form-control" placeholder="Tgl Faktur" name="tgl_faktur" value="<?php  echo $row->tgl_faktur  ?>" autocomplete="off">
                  </div>                  
                </div>

		<div class="form-group">
		<label for="inputEmail3" class="col-sm-2 control-label">No Mesin *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="No Mesin" name="no_mesin" autocomplete="off" value="<?php  echo $row->no_mesin  ?>">                    
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="No Rangka" name="no_rangka" autocomplete="off" value="<?php  echo $row->no_rangka   ?>">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan *</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="id_tipe_kendaraan" required onchange="cek_bbn()" name="id_tipe_kendaraan">
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun Produksi *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="Tahun Produksi" value="<?php  echo $row->tahun_produksi ?>" name="tahun_produksi" onkeypress="return number_only(event)" maxlength="4" minlength="4" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna *</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required id="id_warna"  name="id_warna">
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
                </div> 
 		<div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya Adm *</label>
                  <div class="col-sm-4">
                    <input type="text" onchange="hitung()" required id="biaya_adm" class="form-control" required onkeypress="return number_only(event)" placeholder="Biaya Adm" name="biaya_adm" value="<?php echo $row->biaya_adm ?>" autocomplete="off">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya BBN *</label>
                  <div class="col-sm-4">
                    <input type="text" onchange="hitung()" readonly class="form-control" placeholder="Biaya BBN" required id="biaya_bbn" value="<?php  echo $row->biaya_bbn  ?>" name="biaya_bbn" autocomplete="off">                    
                  <input type="hidden" readonly class="form-control" placeholder="Biaya BBN BJ" id="biaya_bbn_bj" name="biaya_bbn_bj" value="<?php  echo $row->biaya_bbn_bj ?>" autocomplete="off"> 	
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
          <a href="dealer/bantuan_bbn_d/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>     
              <th width="5%">No</th>               
              <th>No Bantuan BBN</th>            
              <th>No Faktur AHM</th>                
              <th>Nama Konsumen</th>  
              <th>No Mesin</th>           
              <th>Tipe</th>              
              <th>Warna</th>       
              <th>Tahun</th>
              <th>Biaya BBN</th>
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
                $tom = "<a data-toggle='tooltip' title='Edit' href='dealer/bantuan_bbn_d/edit?id=$row->id_bantuan_bbn_luar'><button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>
                        <a data-toggle='tooltip' onclick=\"return confirm('Are you sure to approve this data?')\" title='Send' href='dealer/bantuan_bbn_d/send?id=$row->id_bantuan_bbn_luar&s=1'><button class='btn btn-flat btn-xs btn-success'><i class='fa fa-send'></i> Approve</button></a>
			<a data-toggle='tooltip' onclick=\"return confirm('Are you sure to cancel this data?')\" title='Send' href='dealer/bantuan_bbn_d/send?id=$row->id_bantuan_bbn_luar&s=2'><button class='btn btn-flat btn-xs btn-danger'><i class='fa fa-close'></i> Cancel</button></a>";
            }elseif($row->status == 'proses'){
                $status = "<span class='label label-warning'>$row->status</span>";              
                $tom = "<a data-toggle='tooltip' title='View' href='dealer/bantuan_bbn_d/view?id=$row->id_bantuan_bbn_luar'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-eye'></i> View</button></a>";
            }elseif($row->status == 'approved'){
                $status = "<span class='label label-success'>$row->status</span>";              
                $tom = "<a data-toggle='tooltip' title='View' href='dealer/bantuan_bbn_d/view?id=$row->id_bantuan_bbn_luar'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-eye'></i> View</button></a>";
            }elseif($row->status == 'cancel'){
                $status = "<span class='label label-danger'>$row->status</span>";              
             	$tom = "<a data-toggle='tooltip' title='View' href='dealer/bantuan_bbn_d/view?id=$row->id_bantuan_bbn_luar'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-eye'></i> View</button></a>";
            }
	    $row->biaya_bbn = 	number_format($row->biaya_bbn, 0, ',', '.');
            echo "
            <tr>
              <td>$no</td>
              <td>$row->id_bantuan_bbn_luar</td>
              <td>$row->no_faktur</td>             
              <td>$row->nama_konsumen</td>         
              <td>$row->no_mesin</td>                    
              <td>$row->tipe_ahm</td>                                                                      
              <td>$row->warna</td>                                                                      
              <td>$row->tahun_produksi</td>                                                                       
              <td>$row->biaya_bbn</td>                                                                      
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
    <?php
    }else if($set=="detail"){
      $row = $dt_bantuan->row();
    ?>
    <div class="box box-default">
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
            <form class="form-horizontal" action="dealer/bantuan_bbn_d/" method="post" enctype="multipart/form-data">
              <div class="box-body">

                <div class="form-group">
 		  <label for="inputEmail3" class="col-sm-2 control-label">Apakah dari pemenang? *</label>
                  <div class="col-sm-4">
                    <select id="pemenang" disabled onchange="on_pemenang()" class="form-control" required name="pemenang">
                      <option <?php if($row->pemenang == "Tidak") echo "selected"; ?>>Tidak</option>
                      <option <?php if($row->pemenang == "Ya") echo "selected"; ?>>Ya</option>
                    </select>
                  </div>
		  <div id="det_pemenang">   
                  <label for="inputEmail3" class="col-sm-2 control-label">Pemenang Dari *</label>
                  <div class="col-sm-4">
                    <input type="text" disabled class="form-control" placeholder="Pemenang Dari" name="pemenang_dari" value="<?php  echo $row->pemenang_dari  ?>" autocomplete="off">                    
                  </div>   
		  </div>
                </div>                                
                <div class="form-group">
		  <label for="inputEmail3" class="col-sm-2 control-label">Pemohon</label>
                  <div class="col-sm-4">
                    <input type="text" disabled class="form-control" placeholder="Pemohon" name="pemohon" autocomplete="off" value="<?php  echo $row->pemohon   ?>">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tagih Ke</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" disabled placeholder="Tagih Ke" name="tagih_ke" value="<?php  echo $row->tagih_ke   ?>" autocomplete="off">                    
                  </div>                 
                </div>    

		<div class="form-group">
                  <button class="btn btn-primary btn-block btn-flat" disabled="">Data Konsumen</button>   
                </div>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen * (BBN)</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" disabled placeholder="Nama Konsumen" name="nama_konsumen" autocomplete="off" value="<?php  echo $row->nama_konsumen   ?>">                    
                  </div>
		<label for="inputEmail3" class="col-sm-2 control-label">NPWP *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" disabled placeholder="NPWP" name="no_npwp" autocomplete="off" value="<?php  echo $row->no_npwp ?>">                    
                  </div>
                </div>    
                <div class="form-group">
		<label for="inputEmail3" class="col-sm-2 control-label">No KK *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" disabled placeholder="No KK" maxlength="16" minlength="16" onkeypress="return number_only(event)" name="no_kk" autocomplete="off" value="<?php  echo $row->no_kk ?>">                    
                  </div>
		<label for="inputEmail3" class="col-sm-2 control-label">No KTP *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" disabled placeholder="No KTP" maxlength="16" minlength="16" onkeypress="return number_only(event)" name="no_ktp" autocomplete="off" value="<?php  echo $row->no_ktp ?>">                    
                  </div>
		</div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tempat Lahir *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" disabled placeholder="Tempat Lahir" name="tempat_lahir" autocomplete="off" value="<?php  echo $row->tempat_lahir ?>">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No TDP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" disabled placeholder="No TDP" name="no_tdp" autocomplete="off"  value="<?php  echo $row->no_tdp ?>">                    
                  </div>
                </div>    
                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir *</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->tgl_lahir?>" class="form-control" disabled placeholder="Tgl Lahir" name="tgl_lahir" id='tanggal4' autocomplete="off">                    
                  </div> 
		  <label for="inputEmail3" class="col-sm-2 control-label">No HP *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" disabled placeholder="No HP" name="no_hp" autocomplete="off" onkeypress="return number_only(event)" value="<?php  echo $row->no_hp ?>">                    
                  </div>
                </div>      

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" disabled placeholder="Alamat" name="alamat" autocomplete="off" value="<?php  echo $row->alamat ?>">                    
                  </div>
 		  <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" disabled placeholder="No Telp" name="no_telp" autocomplete="off" value="<?php  echo $row->no_telp ?>">                    
                  </div>
		</div>
            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan *</label>
                  <div class="col-sm-4">
                    <input type="hidden" readonly name="id_kelurahan" value="<?php  echo $row->id_kelurahan ?>"  id="id_kelurahan">                      
                    <input disabled type="text" onpaste="return false" onkeypress="return nihil(event)"  name="kelurahan" data-toggle="modal" placeholder="Kelurahan" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()">                                          
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan *</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" disabled id="pekerjaan"  name="pekerjaan">
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
		</div>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Gadis Ibu Kandung *</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php  echo $row->nama_gadis_ibu ?>" class="form-control" disabled placeholder="Nama Gadis Ibu Kandung" name="nama_gadis_ibu" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir Ibu *</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php  echo $row->tgl_lahir_ibu ?>" class="form-control" disabled placeholder="Tgl Lahir" name="tgl_lahir_ibu" id='tanggal3' autocomplete="off">                    
                  </div>
                </div>                  

		<div class="form-group">
                  <button class="btn btn-primary btn-block btn-flat" disabled="">Data Kendaraan</button>   
                </div>

		<div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Faktur AHM *</label>
                  <div class="col-sm-4">                    
                    <input type="hidden" name="id_bantuan_bbn_luar" value="<?php  echo $row->id_bantuan_bbn_luar ?>">
                    <input type="text" disabled class="form-control" placeholder="No Faktur" name="no_faktur" value="<?php  echo $row->no_faktur  ?>" autocomplete="off">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Jual/ Faktur *</label>
                  <div class="col-sm-4">                    
                    <input type="text" disabled id="tanggal2" class="form-control" placeholder="Tgl Faktur" name="tgl_faktur" value="<?php  echo $row->tgl_faktur  ?>" autocomplete="off">
                  </div>                  
                </div>

		<div class="form-group">
		<label for="inputEmail3" class="col-sm-2 control-label">No Mesin *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" disabled placeholder="No Mesin" name="no_mesin" autocomplete="off" value="<?php  echo $row->no_mesin  ?>">                    
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" disabled placeholder="No Rangka" name="no_rangka" autocomplete="off" value="<?php  echo $row->no_rangka   ?>">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan *</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="id_tipe_kendaraan" disabled onchange="cek_bbn()" name="id_tipe_kendaraan">
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun Produksi *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" disabled placeholder="Tahun Produksi" value="<?php  echo $row->tahun_produksi ?>" name="tahun_produksi" onkeypress="return number_only(event)" maxlength="4" minlength="4" autocomplete="off">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna *</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" disabled id="id_warna"  name="id_warna">
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
                </div> 
 		<div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya Adm *</label>
                  <div class="col-sm-4">
                    <input type="text" onchange="hitung()" disabled id="biaya_adm" class="form-control" required placeholder="Biaya Adm" name="biaya_adm" value="<?php echo $row->biaya_adm ?>" autocomplete="off">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya BBN *</label>
                  <div class="col-sm-4">
                    <input type="text" onchange="hitung()" readonly class="form-control" placeholder="Biaya BBN" required id="biaya_bbn" value="<?php  echo $row->biaya_bbn  ?>" name="biaya_bbn" autocomplete="off">                    
                  <input type="hidden" readonly class="form-control" placeholder="Biaya BBN BJ" id="biaya_bbn_bj" name="biaya_bbn_bj" value="<?php  echo $row->biaya_bbn_bj ?>" autocomplete="off"> 	
		  </div>             
		</div>

              </div>
            </div>
          </div>        
        </div><!-- /.box-body -->
     </form>
    </div><!-- /.box -->
    <?php
    }
    ?>
  </section>
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
    on_pemenang();
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

function on_pemenang(){
  var val= $("#pemenang").val();
  if(val == 'Ya'){
	$("#det_pemenang").show();
	$("#nama_pemenang").attr("required",true);
  }else{
	$("#det_pemenang").hide();
	$("#nama_pemenang").removeAttr("required");
	$("#nama_pemenang").val("");
  }	
}

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
        $("#biaya_bbn_bj").val(data[1]);           
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