<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 30px;  
  padding-left: 5px;
  padding-right: 5px;  
  margin-right: 0px; 
}
.isi_combo{   
  height: 30px;
  border:1px solid #ccc;
  padding-left:1.5px;
}
</style>
<base href="<?php echo base_url(); ?>" />
<?php 
if(isset($_GET['id'])){
?>
<body onload="take_kec()">
<?php }else{ ?>
<body onload="kirim_data_pl()">
<?php } ?>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Faktur STNK</li>
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
          <a href="h1/bantuan_bbn">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
        <div class="row">
          <div class="col-md-12">            
            <form class="form-horizontal" action="h1/bantuan_bbn/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor Faktur</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_faktur" placeholder="Nomor Faktur" class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Faktur</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_faktur" placeholder="Tgl Faktur" id="tanggal" class="form-control">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Pemohon</label>
                  <div class="col-sm-4">
                    <input type="text" name="pemohon" placeholder="Pemohon"  class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="No Mesin" class="form-control">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_rangka" placeholder="No Rangka" class="form-control">
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_tipe_kendaraan" required onchange="cek_bbn()" id="id_tipe_kendaraan">
                      <option>- choose -</option>
                      <?php 
                      $tipe = $this->m_admin->getSortCond("ms_tipe_kendaraan","id_tipe_kendaraan","ASC");
                      foreach ($tipe->result() as $isi) {
                        echo "<option value='$isi->id_tipe_kendaraan'>$isi->id_tipe_kendaraan | $isi->tipe_ahm</option>";
                      }
                      ?>
                    </select>
                  </div>                                    
                </div>
                <div class="form-group">  
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required name="id_warna">
                      <option>- choose -</option>
                      <?php 
                      $warna = $this->m_admin->getSortCond("ms_warna","warna","ASC");
                      foreach ($warna->result() as $isi) {
                        echo "<option value='$isi->id_warna'>$isi->id_warna | $isi->warna</option>";
                      }
                      ?>
                    </select>
                  </div>                                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun Produksi</label>
                  <div class="col-sm-4">
                    <input type="text" name="tahun_produksi" onkeypress="return number_only(event)" placeholder="Tahun Produksi" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">  
                  <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_ktp" placeholder="No KTP" onkeypress="return number_only(event)" class="form-control">
                  </div>                                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-4">
                    <input type="text" name="nama_konsumen" placeholder="Nama Konsumen" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">  
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-4">
                    <input type="text" name="alamat" placeholder="Alamat" class="form-control">
                  </div>                                                
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_telp" onkeypress="return number_only(event)" placeholder="No Telp" class="form-control">
                  </div>                  
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                  <div class="col-sm-4">
                    <input type="hidden" readonly name="id_kelurahan" id="id_kelurahan">                      
                    <input type="text" readonly required name="kelurahan" data-toggle="modal" placeholder="Kelurahan" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()">                               
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                    <input type="text" class="form-control" id="kecamatan" placeholder="Kecamatan"  name="kecamatan">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kabupaten" id="id_kabupaten">
                    <input type="text" class="form-control" placeholder="Kota/Kabupaten" id="kabupaten" name="kabupaten">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_provinsi" id="id_provinsi">
                    <input type="text" class="form-control" placeholder="Provinsi" id="provinsi" name="provinsi">                                        
                  </div>
                </div>
                <div class="form-group">  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Gadis Ibu Kandung</label>
                  <div class="col-sm-4">
                    <input type="text" name="nama_ibu" placeholder="Nama Gadis Ibu Kandung" class="form-control">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir Ibu Kandung</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal3" autocomplete="off" name="tgl_ibu" placeholder="Tgl Lahir Ibu Kandung" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">  
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required name="pekerjaan">
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Apakah ini dari Pemenang atau bukan?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pemenang" id="pemenang" onchange="cek_pemenang()">
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>                  
                </div>
                <div class="form-group">  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tagih ke</label>
                  <div class="col-sm-4">
                    <input type="text" name="tagih_ke" placeholder="Tagih ke" class="form-control">
                  </div>                  
                  <label for="inputEmail3" id="pemenang_lbl" class="col-sm-2 control-label">Pemenang dari</label>
                  <div class="col-sm-4">
                    <input type="text" name="pemenang_dari" placeholder="Pemenang dari" class="form-control" id="pemenang_dari">
                  </div>                                                                  
                </div>
                <div class="form-group">  
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya Administrasi</label>
                  <div class="col-sm-4">
                    <input type="text" name="biaya_adm" id="biaya_adm" onchange="hitung()" placeholder="Biaya Administrasi" class="form-control">
                  </div>                                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya BBN</label>
                  <div class="col-sm-4">
                    <input type="text" name="biaya_bbn" id="biaya_bbn" readonly placeholder="Biaya BBN" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">  
                  <label for="inputEmail3" class="col-sm-2 control-label">Total</label>
                  <div class="col-sm-4">
                    <input type="text" name="total" readonly placeholder="Total" id="total" class="form-control">
                  </div>                                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Mohon Samsat</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_samsat" id="tanggal2" placeholder="Tgl Mohon Samsat" class="form-control">
                  </div>                  
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <?php 
    }elseif($set=="edit"){
      $row = $dt_bantuan->row();
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/bantuan_bbn">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
        <div class="row">
          <div class="col-md-12">            
            <form class="form-horizontal" action="h1/bantuan_bbn/update" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <input type="hidden" name="id" value="<?php echo $row->id_bantuan_bbn ?>">
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor Faktur</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_faktur" value="<?php echo $row->no_faktur ?>" placeholder="Nomor Faktur" class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Faktur</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_faktur" value="<?php echo $row->tgl_faktur ?>" placeholder="Tgl Faktur" id="tanggal" class="form-control">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Pemohon</label>
                  <div class="col-sm-4">
                    <input type="text" name="pemohon" value="<?php echo $row->pemohon ?>" placeholder="Pemohon"  class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" value="<?php echo $row->no_mesin ?>" placeholder="No Mesin" class="form-control">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_rangka" value="<?php echo $row->no_rangka ?>" placeholder="No Rangka" class="form-control">
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_tipe_kendaraan" required>
                     <option value="<?php echo $row->id_tipe_kendaraan ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_tipe_kendaraan","id_tipe_kendaraan",$row->id_tipe_kendaraan)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->id_tipe_kendaraan | $dt_cust->tipe_ahm";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $tipe = $this->m_admin->kondisiCond("ms_tipe_kendaraan","id_tipe_kendaraan != '$row->id_tipe_kendaraan'");                                                
                      foreach ($tipe->result() as $isi) {
                        echo "<option value='$isi->id_tipe_kendaraan'>$isi->id_tipe_kendaraan | $isi->tipe_ahm</option>";
                      }
                      ?>
                    </select>
                  </div>                                    
                </div>
                <div class="form-group">  
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_warna" required>
                      <option value="<?php echo $row->id_warna ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_warna","id_warna",$row->id_warna)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->warna;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $warna = $this->m_admin->kondisiCond("ms_warna","id_warna != '$row->id_warna'");                                                
                      foreach ($warna->result() as $isi) {
                        echo "<option value='$isi->id_warna'>$isi->id_warna | $isi->warna</option>";
                      }
                      ?>
                    </select>
                  </div>                                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun Produksi</label>
                  <div class="col-sm-4">
                    <input type="text" name="tahun_produksi" value="<?php echo $row->tahun_produksi ?>" onkeypress="return number_only(event)" placeholder="Tahun Produksi" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">  
                  <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_ktp" value="<?php echo $row->no_ktp ?>" placeholder="No KTP" onkeypress="return number_only(event)" class="form-control">
                  </div>                                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-4">
                    <input type="text" name="nama_konsumen" value="<?php echo $row->nama_konsumen ?>" placeholder="Nama Konsumen" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">  
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-4">
                    <input type="text" name="alamat" value="<?php echo $row->alamat ?>" placeholder="Alamat" class="form-control">
                  </div>                                                
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_telp" value="<?php echo $row->no_telp ?>" onkeypress="return number_only(event)" placeholder="No Telp" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                  <div class="col-sm-4">
                    <?php 
                    $dt_cust    = $this->m_admin->getByID("ms_kelurahan","id_kelurahan",$row->id_kelurahan)->row();                                 
                    if(isset($dt_cust)){
                      $kel = $dt_cust->kelurahan;
                    }else{
                      $kel = "";
                    }
                    ?>
                    <input type="hidden" value="<?php echo $row->id_kelurahan ?>" readonly name="id_kelurahan" id="id_kelurahan">                      
                    <input type="text" value="<?php echo $kel ?>" required readonly name="kelurahan" data-toggle="modal" placeholder="Kelurahan" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="cek_kecamatan()">                               
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                    <input type="text" class="form-control" readonly id="kecamatan" placeholder="Kecamatan"  name="kecamatan">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kabupaten" id="id_kabupaten">
                    <input type="text" class="form-control"  readonly placeholder="Kota/Kabupaten" id="kabupaten" name="kabupaten">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_provinsi" id="id_provinsi">
                    <input type="text" class="form-control" readonly placeholder="Provinsi" id="provinsi" name="provinsi">                                        
                  </div>
                </div>
                <div class="form-group">  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Gadis Ibu Kandung</label>
                  <div class="col-sm-4">
                    <input type="text" name="nama_ibu" value="<?php echo $row->nama_ibu ?>" placeholder="Nama Gadis Ibu Kandung" class="form-control">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir Ibu Kandung</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal3" <?php echo $row->tgl_ibu ?> autocomplete="off" name="tgl_ibu" placeholder="Tgl Lahir Ibu Kandung" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">  
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required name="pekerjaan">
                      <option value="<?php echo $row->pekerjaan ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_pekerjaan","id_pekerjaan",$row->pekerjaan)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->pekerjaan;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt = $this->m_admin->kondisiCond("ms_pekerjaan","id_pekerjaan != '$row->pekerjaan'");                                                
                      foreach($dt->result() as $val) {
                        echo "
                        <option value='$val->id_pekerjaan'>$val->pekerjaan</option>;
                        ";
                      }
                      ?>
                    </select>                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Apakah ini dari Pemenang atau bukan?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pemenang" id="pemenang" onchange="cek_pemenang()">
                      <option><?php echo $row->pemenang ?></option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>                  
                </div>
                <div class="form-group">                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tagih ke</label>
                  <div class="col-sm-4">
                    <input type="text" name="tagih_ke" value="<?php echo $row->tagih_ke ?>" placeholder="Tagih ke" class="form-control">
                  </div>                  
                  <label for="inputEmail3" id="pemenang_lbl" class="col-sm-2 control-label">Pemenang dari</label>
                  <div class="col-sm-4">
                    <input type="text" id="pemenang_dari" name="pemenang_dari" value="<?php echo $row->pemenang_dari ?>" placeholder="Pemenang dari" class="form-control">
                  </div>                                                
                </div>
                <div class="form-group">  
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya Administrasi</label>
                  <div class="col-sm-4">
                    <input type="text" name="biaya_adm" value="<?php echo $row->biaya_adm ?>" onkeypress="return number_only(event)" placeholder="Biaya Administrasi" class="form-control">
                  </div>                                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya BBN</label>
                  <div class="col-sm-4">
                    <input type="text" name="biaya_bbn" value="<?php echo $row->biaya_bbn ?>" onkeypress="return number_only(event)" placeholder="Biaya BBN" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">  
                  <label for="inputEmail3" class="col-sm-2 control-label">Total</label>
                  <div class="col-sm-4">
                    <input type="text" name="total" value="<?php echo $row->total ?>" onkeypress="return number_only(event)" placeholder="Total" class="form-control">
                  </div>                                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Mohon Samsat</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_samsat" value="<?php echo $row->tgl_samsat ?>" id="tanggal2" placeholder="Tgl Mohon Samsat" class="form-control">
                  </div>                  
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <?php
    }elseif($set=="view"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/bantuan_bbn/add">            
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
              <th>No Faktur</th>
              <th>No Mesin</th>
              <th>No Rangka</th>              
              <th>Pemohon</th>
              <th>Nama Konsumen</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th>Status</th>
              <th width="15%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_bantuan->result() as $row) {                                    
            $edit = $this->m_admin->set_tombol($id_menu,$group,'edit');
            $delete = $this->m_admin->set_tombol($id_menu,$group,'delete');
            $print = $this->m_admin->set_tombol($id_menu,$group,'print');
            $approval = $this->m_admin->set_tombol($id_menu,$group,'approval');                                
                
            if($row->status=='input'){
              $status = "<span class='label label-warning'>$row->status</span>";
              $tombol = "<a $approval href='h1/bantuan_bbn/approve?id=$row->id_bantuan_bbn' class='btn btn-success btn-flat btn-xs'>Approve</a>
                        <a $edit href='h1/bantuan_bbn/edit?id=$row->id_bantuan_bbn' class='btn btn-primary btn-flat btn-xs'>Edit</a>
                        <a $approval href='h1/bantuan_bbn/reject?id=$row->id_bantuan_bbn' class='btn btn-danger btn-flat btn-xs'>Reject</a>";
            }elseif($row->status=='approved'){ 
              $status = "<span class='label label-success'>$row->status</span>";                 
              $tombol = "<a $print href='h1/bantuan_bbn/cetak_syarat_bpkb?id=$row->id_bantuan_bbn' target='_blank' class='btn btn-warning btn-flat btn-xs'>Cetak Persyaratan BPKB</a>                                                
                        <a $print href='h1/bantuan_bbn/cetak_syarat_stnk?id=$row->id_bantuan_bbn' target='_blank' class='btn btn-info btn-flat btn-xs'>Cetak Persyaratan STNK</a>
                        <a $print href='h1/bantuan_bbn/cetak_faktur?id=$row->id_bantuan_bbn' target='_blank' class='btn bg-maroon btn-flat btn-xs'>Cetak Faktur</a>
                        <a $print href='h1/bantuan_bbn/cetak_tagihan?id=$row->id_bantuan_bbn' target='_blank' class='btn btn-success btn-flat btn-xs'>Cetak Tagihan</a>";                            
              $cek_bpkb = $this->db->query("SELECT * FROM tr_penyerahan_bpkb_detail INNER JOIN tr_penyerahan_bpkb ON tr_penyerahan_bpkb_detail.no_serah_bpkb = tr_penyerahan_bpkb.no_serah_bpkb
                WHERE tr_penyerahan_bpkb_detail.no_mesin = '$row->no_mesin' AND tr_penyerahan_bpkb_detail.status_nosin = 'terima'");
              if($cek_bpkb->num_rows() > 0){
                $tombol .= "<a $print href='h1/bantuan_bbn/cetak_st_bpkb?id=$row->id_bantuan_bbn' class='btn btn-primary btn-flat btn-xs' target='_blank'>Cetak Serah Terima BPKB</a>";
              }
              $cek_stnk = $this->db->query("SELECT * FROM tr_penyerahan_stnk_detail INNER JOIN tr_penyerahan_stnk ON tr_penyerahan_stnk_detail.no_serah_stnk = tr_penyerahan_stnk.no_serah_stnk
                WHERE tr_penyerahan_stnk_detail.no_mesin = '$row->no_mesin' AND tr_penyerahan_stnk_detail.status_nosin = 'terima'");
              if($cek_stnk->num_rows() > 0){
                $tombol .= "<a $print href='h1/bantuan_bbn/cetak_st_stnk?id=$row->id_bantuan_bbn' class='btn btn-primary btn-flat btn-xs' target='_blank'>Cetak Serah Terima STNK</a>";
              }
              $cek_plat = $this->db->query("SELECT * FROM tr_penyerahan_plat_detail INNER JOIN tr_penyerahan_plat ON tr_penyerahan_plat_detail.no_serah_plat = tr_penyerahan_plat.no_serah_plat
                WHERE tr_penyerahan_plat_detail.no_mesin = '$row->no_mesin' AND tr_penyerahan_plat_detail.status_nosin = 'terima'");
              if($cek_plat->num_rows() > 0){
                $tombol .= "<a $print href='h1/bantuan_bbn/cetak_st_plat?id=$row->id_bantuan_bbn' class='btn btn-primary btn-flat btn-xs' target='_blank'>Cetak Serah Terima Plat</a>";
              }
            }else{
              $status = "<span class='label label-danger'>$row->status</span>";                 
              $tombol = "";
            }
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->no_faktur</td>                           
              <td>$row->no_mesin</td>                           
              <td>$row->no_rangka</td>                           
              <td>$row->pemohon</td>              
              <td>$row->nama_konsumen</td>                            
              <td>$row->id_tipe_kendaraan</td>                            
              <td>$row->id_warna</td>                            
              <td>$status</td>                            
              <td>$tombol</td>";                                      
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
<div class="modal fade" id="Kelurahanmodal">      
  <div class="modal-dialog" role="document">
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
function cek_pemenang(){
  var pemenang = document.getElementById("pemenang").value;
  if(pemenang == 'Ya'){
    $("#pemenang_dari").show();
    $("#pemenang_lbl").show();
  }else{
    $("#pemenang_lbl").hide();
    $("#pemenang_dari").hide();
  }
}
function cek_bbn(){
  var id_tipe_kendaraan = document.getElementById("id_tipe_kendaraan").value;
  $.ajax({
      url : "<?php echo site_url('h1/bantuan_bbn/cari_bbn')?>",
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
  
function take_kec(){
  var id_kelurahan = $("#id_kelurahan").val();                       
  $.ajax({
      url: "<?php echo site_url('h1/bantuan_bbn/take_kec')?>",
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
function chooseitem(id_kelurahan){
  document.getElementById("id_kelurahan").value = id_kelurahan; 
  take_kec();
  $("#Kelurahanmodal").modal("hide");
}
</script>
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
            "url": "<?php echo site_url('h1/bantuan_bbn/ajax_list')?>",
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