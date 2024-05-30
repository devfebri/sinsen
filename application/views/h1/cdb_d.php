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
<?php if(isset($_GET['id'])){ ?>
<body onload="cek_customer()">
<?php } ?>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Penjualan Unit</li>
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
          <a href="dealer/cdb_d">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
            <form class="form-horizontal" action="dealer/cdb_d/save" method="post" enctype="multipart/form-data">
              <div class="box-body">                              
                <button class="btn btn-block btn-success btn-flat" disabled> CDB </button> <br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No SPK</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" name="no_spk" readonly id="no_spk" placeholder="No SPK" required="required">                                                    
                  </div>
                  <div class="col-sm-4">                                        
                    <a class="btn btn-primary btn-flat btn-sm" data-toggle="modal" data-target="#Spkmodal" type="button"><i class="fa fa-search"></i> Browse</a>
                  </div>  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Sesuai Identitas</label>
                  <div class="col-sm-10">
                    <input type="text" readonly class="form-control"  id="nama_konsumen" placeholder="Nama Sesuai Identitas" name="nama_konsumen" required="required">                                        
                  </div>
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tempat/Tgl.Lahir</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly id="tempat_lahir" class="form-control"  placeholder="Tempat Lahir" name="tempat_lahir" required="required">                                                            
                  </div>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control tgl_lahir" id="tgl_lahir"  placeholder="Tgl Lahir" name="tgl_lahir" required="required">                                                            
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kewarganegaraan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly id="jenis_wn" class="form-control"  placeholder="Jenis Kewarganegaraan" name="jenis_wn" required="required">                                                            
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No KTP/KITAS</label>
                  <div class="col-sm-4">
                    <input type="text" readonly id="no_ktp" readonly class="form-control"  onkeypress="return number_only(event)" placeholder="No KTP/KITAS" name="no_ktp" required="required">                    
                  </div>                
                </div>

                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No KK</label>
                  <div class="col-sm-4">
                    <input type="text" readonly id="no_kk" class="form-control"  onkeypress="return number_only(event)" placeholder="No KK" name="no_kk" required="required">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No NPWP</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="No NPWP" id="no_npwp" name="npwp" required="required">                                        
                  </div>
                </div>
                <!-- <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Upload Foto KTP (Maks 100Kb)</label>
                  <div class="col-sm-4"> 
                    <button data-toggle="modal" type="button" data-target="#Ktpmodal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-eye"></i> Lihat Gambar</button>                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Upload KK (Maks 1 Mb)</label>
                  <div class="col-sm-4">
                    <button data-toggle="modal" type="button" data-target="#Kkmodal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-eye"></i> Lihat Gambar</button>                    
                  </div>
                </div> -->
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" id="id_kelurahan">                                                        
                    <input type="text" readonly class="form-control" placeholder="Kelurahan Domisili" id="kelurahan" name="kelurahan" required="required">                                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                    <input type="text" class="form-control" readonly id="kecamatan" placeholder="Kecamatan Domisili"  name="kecamatan" required="required">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kabupaten" id="id_kabupaten">
                    <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten Domisili" id="kabupaten" name="kabupaten" required="required">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_provinsi" id="id_provinsi">
                    <input type="text" class="form-control" readonly placeholder="Provinsi Domisili" id="provinsi" name="provinsi" required="required">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Domisili</label>
                  <div class="col-sm-10">
                    <input type="text" readonly class="form-control"  placeholder="Alamat Domisili"  name="alamat" id="alamat" required="required">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kodepos</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" placeholder="Kodepos" id="kodepos" name="kodepos" required="required">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Denah Lokasi</label>
                  <div class="col-sm-10">
                    <input type="text" readonly class="form-control" placeholder="Latitude,Longitude"  name="denah_lokasi" id="denah_lokasi" required="required">                                        
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Apakah alamat domisili sama dengan alamat di KTP?</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" id="tanya" name="tanya" required="required">                                        
                  </div>                  
                </div>

                <span id="tampil_alamat">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_kelurahan" id="id_kelurahan2">
                      <input type="text" readonly class="form-control" placeholder="Kelurahan Sesuai KTP"  name="kelurahan2" required="required">                                        
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_kecamatan" id="id_kecamatan2">
                      <input type="text" readonly class="form-control"  id="kecamatan2" placeholder="Kecamatan Sesuai KTP"  name="kecamatan2">                                        
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_kabupaten" id="id_kabupaten2">
                      <input type="text" readonly class="form-control"  placeholder="Kota/Kabupaten Sesuai KTP" id="kabupaten2" name="kabupaten2">                                        
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_provinsi" id="id_provinsi2">
                      <input type="text" readonly class="form-control"  placeholder="Provinsi Sesuai KTP" id="provinsi2" name="provinsi2">                                        
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kodepos Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="text" readonly class="form-control" placeholder="Kodepos Sesuai KTP"  name="kodepos2">                                        
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat Sesuai KTP</label>
                    <div class="col-sm-10">
                      <input type="text" readonly class="form-control" placeholder="Alamat Sesuai KTP"  name="alamat2">                                        
                    </div>
                  </div>
                </span>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Rumah</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" placeholder="Status Rumah" id="status_rumah" name="status_rumah">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Lama Tinggal</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" placeholder="Lama Tinggal" id="lama_tinggal" name="lama_tinggal">                                        
                  </div>  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" placeholder="Pekerjaan" id="pekerjaan" name="pekerjaan">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Lama Kerja</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Lama Kerja" id="lama_kerja" name="lama_kerja">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jabatan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Jabatan" id="jabatan" name="jabatan">                                        
                  </div>                 
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Penghasilan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Total Penghasilan" id="total_penghasilan" name="total_penghasilan">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Pengeluaran Perbulan</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" placeholder="Pengeluaran Perbulan" id="pengeluaran_bulan" name="pengeluaran_bulan">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP #1</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control"  placeholder="No HP #1" id="no_hp"  name="no_hp" required="required">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp #1</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control"  placeholder="Status No HP #1" id="status_hp"  name="status_hp" required="required">                                                                                                            
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP #2</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control"  placeholder="No HP #2" id="no_hp_2"  name="no_hp_2">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp #2</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control"  placeholder="Status No HP #2" id="status_hp_2"  name="status_hp_2">                                        
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly placeholder="No Telp" id="no_telp" name="no_telp" required="required">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-4">
                    <input type="email" class="form-control" readonly placeholder="Email" id="email" name="email" required="required">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Refferal ID</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Refferal ID"  name="refferal_id" id="refferal_id">                                        
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">RO BD ID</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Ro BD ID"  name="robd_id" id="robd_id">                                        
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Refferal ID</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama Refferal ID" name="nama_refferal_id" id="nama_refferal_id" readonly>                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama RO BD ID</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama RO BD ID" name="nama_robd_id" readonly id="nama_robd_id">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Gadis Ibu Kandung</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly placeholder="Nama Gadis Ibu Kandung" id="nama_ibu" name="nama_ibu">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir Ibu Kandung</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Tgl Lahir Ibu Kandung" id="tgl_ibu" name="tgl_ibu">                    
                  </div>
                <div class="form-group">
                </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly placeholder="Keterangan" id="keterangan" name="keterangan">                    
                  </div>
                </div>

                <button class="btn btn-block btn-danger btn-flat" disabled> DATA PENDUKUNG </button> <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembelian</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="jenis_beli">
                      <option value="">- choose -</option>
                      <option>Reguler</option>
                      <option>Kolektif</option>
                      <option>Joint Promo</option>
                    </select>
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Agama</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="agama">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_agama->result() as $isi) {
                        echo "<option value='$isi->agama'>$isi->agama</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Hobi</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="hobi">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_hobi->result() as $isi) {
                        echo "<option value='$isi->hobi'>$isi->hobi</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Pendidikan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pendidikan" required="required">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_pendidikan->result() as $isi) {
                        echo "<option value='$isi->pendidikan'>$isi->pendidikan</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Bersedia dikirimkan informasi terbaru dari program Honda?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="sedia_hub" required="required"="">
                      <option value="">- choose -</option>                      
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Motor yg Dimiliki Sebelumnya?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_sebelumnya" required="required">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_jenis_sebelumnya->result() as $isi) {
                        echo "<option value='$isi->jenis_sebelumnya'>$isi->jenis_sebelumnya</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Merk motor yg Dimiliki Sebelumnya?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="merk_sebelumnya" required="required">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_merk_sebelumnya->result() as $isi) {
                        echo "<option value='$isi->merk_sebelumnya'>$isi->merk_sebelumnya</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Sepeda Motor digunakan untuk?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="digunakan" required="required">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_digunakan->result() as $isi) {
                        echo "<option value='$isi->digunakan'>$isi->digunakan</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Yang Menggunakan Sepeda Motor?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="menggunakan" required="required">
                      <option value="">- choose -</option>
                      <option>Sendiri</option>
                      <option>Anak</option>
                      <option>Suami/Istri</option>
                    </select>
                  </div>
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Facebook</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Facebook" name="facebook">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Twitter</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Twitter" name="twitter">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Instagram</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Instagram" name="instagram">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Youtube</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Youtube" name="youtube">                    
                  </div>
                </div>                                    
                      
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="button" onClick="cancel_tr()" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="edit"){
      $row = $dt_cdb->row();
    ?>
    
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/cdb_d">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
            <form class="form-horizontal" action="dealer/cdb_d/update" method="post" enctype="multipart/form-data">
              <div class="box-body">                              
                <button class="btn btn-block btn-success btn-flat" disabled> CDB </button> <br>
                <div class="form-group">
                  <input type="hidden" name="id_cdb" value="<?php echo $row->id_cdb ?>">
                  <label for="inputEmail3" class="col-sm-2 control-label">No SPK</label>
                  <div class="col-sm-4">                    
                    <input onchange="cek_customer()" type="text" value="<?php echo $row->no_spk ?>" class="form-control" name="no_spk" readonly id="no_spk" placeholder="No SPK" required="required">                                                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Sesuai Identitas</label>
                  <div class="col-sm-10">
                    <input type="text" readonly class="form-control"  id="nama_konsumen" placeholder="Nama Sesuai Identitas" name="nama_konsumen" required="required">                                        
                  </div>
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tempat/Tgl.Lahir</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly id="tempat_lahir" class="form-control"  placeholder="Tempat Lahir" name="tempat_lahir" required="required">                                                            
                  </div>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control tgl_lahir" id="tgl_lahir"  placeholder="Tgl Lahir" name="tgl_lahir" required="required">                                                            
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kewarganegaraan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly id="jenis_wn" class="form-control"  placeholder="Jenis Kewarganegaraan" name="jenis_wn" required="required">                                                            
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No KTP/KITAS</label>
                  <div class="col-sm-4">
                    <input type="text" readonly id="no_ktp" readonly class="form-control"  onkeypress="return number_only(event)" placeholder="No KTP/KITAS" name="no_ktp" required="required">                    
                  </div>                
                </div>

                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No KK</label>
                  <div class="col-sm-4">
                    <input type="text" readonly id="no_kk" class="form-control"  onkeypress="return number_only(event)" placeholder="No KK" name="no_kk" required="required">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No NPWP</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="No NPWP" id="no_npwp" name="npwp" required="required">                                        
                  </div>
                </div>
                <!-- <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Upload Foto KTP (Maks 100Kb)</label>
                  <div class="col-sm-4"> 
                    <button data-toggle="modal" type="button" data-target="#Ktpmodal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-eye"></i> Lihat Gambar</button>                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Upload KK (Maks 1 Mb)</label>
                  <div class="col-sm-4">
                    <button data-toggle="modal" type="button" data-target="#Kkmodal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-eye"></i> Lihat Gambar</button>                    
                  </div>
                </div> -->
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" id="id_kelurahan">                                                        
                    <input type="text" readonly class="form-control" placeholder="Kelurahan Domisili" id="kelurahan" name="kelurahan" required="required">                                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                    <input type="text" class="form-control" readonly id="kecamatan" placeholder="Kecamatan Domisili"  name="kecamatan" required="required">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kabupaten" id="id_kabupaten">
                    <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten Domisili" id="kabupaten" name="kabupaten" required="required">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_provinsi" id="id_provinsi">
                    <input type="text" class="form-control" readonly placeholder="Provinsi Domisili" id="provinsi" name="provinsi" required="required">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Domisili</label>
                  <div class="col-sm-10">
                    <input type="text" readonly class="form-control"  placeholder="Alamat Domisili"  name="alamat" id="alamat" required="required">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kodepos</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" placeholder="Kodepos" id="kodepos" name="kodepos" required="required">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Denah Lokasi</label>
                  <div class="col-sm-10">
                    <input type="text" readonly class="form-control" placeholder="Latitude,Longitude"  name="denah_lokasi" id="denah_lokasi" required="required">                                        
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Apakah alamat domisili sama dengan alamat di KTP?</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" id="tanya" name="tanya" required="required">                                        
                  </div>                  
                </div>

                <span id="tampil_alamat">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_kelurahan" id="id_kelurahan2">
                      <input type="text" readonly class="form-control" placeholder="Kelurahan Sesuai KTP"  name="kelurahan2" required="required">                                        
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_kecamatan" id="id_kecamatan2">
                      <input type="text" readonly class="form-control"  id="kecamatan2" placeholder="Kecamatan Sesuai KTP"  name="kecamatan2">                                        
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_kabupaten" id="id_kabupaten2">
                      <input type="text" readonly class="form-control"  placeholder="Kota/Kabupaten Sesuai KTP" id="kabupaten2" name="kabupaten2">                                        
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_provinsi" id="id_provinsi2">
                      <input type="text" readonly class="form-control"  placeholder="Provinsi Sesuai KTP" id="provinsi2" name="provinsi2">                                        
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kodepos Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="text" readonly class="form-control" placeholder="Kodepos Sesuai KTP"  name="kodepos2">                                        
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat Sesuai KTP</label>
                    <div class="col-sm-10">
                      <input type="text" readonly class="form-control" placeholder="Alamat Sesuai KTP"  name="alamat2">                                        
                    </div>
                  </div>
                </span>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Rumah</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" placeholder="Status Rumah" id="status_rumah" name="status_rumah">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Lama Tinggal</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" placeholder="Lama Tinggal" id="lama_tinggal" name="lama_tinggal">                                        
                  </div>  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" placeholder="Pekerjaan" id="pekerjaan" name="pekerjaan">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Lama Kerja</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Lama Kerja" id="lama_kerja" name="lama_kerja">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jabatan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Jabatan" id="jabatan" name="jabatan">                                        
                  </div>                 
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Penghasilan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Total Penghasilan" id="total_penghasilan" name="total_penghasilan">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Pengeluaran Perbulan</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" placeholder="Pengeluaran Perbulan" id="pengeluaran_bulan" name="pengeluaran_bulan">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP #1</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control"  placeholder="No HP #1" id="no_hp"  name="no_hp" required="required">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp #1</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control"  placeholder="Status No HP #1" id="status_hp"  name="status_hp" required="required">                                                                                                            
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP #2</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control"  placeholder="No HP #2" id="no_hp_2"  name="no_hp_2">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp #2</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control"  placeholder="Status No HP #2" id="status_hp_2"  name="status_hp_2">                                        
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly placeholder="No Telp" id="no_telp" name="no_telp" required="required">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-4">
                    <input type="email" class="form-control" readonly placeholder="Email" id="email" name="email" required="required">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Refferal ID</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Refferal ID"  name="refferal_id" id="refferal_id">                                        
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">RO BD ID</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Ro BD ID"  name="robd_id" id="robd_id">                                        
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Refferal ID</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama Refferal ID" name="nama_refferal_id" id="nama_refferal_id" readonly>                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama RO BD ID</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama RO BD ID" name="nama_robd_id" readonly id="nama_robd_id">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Gadis Ibu Kandung</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly placeholder="Nama Gadis Ibu Kandung" id="nama_ibu" name="nama_ibu">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir Ibu Kandung</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Tgl Lahir Ibu Kandung" id="tgl_ibu" name="tgl_ibu">                    
                  </div>
                <div class="form-group">
                </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly placeholder="Keterangan" id="keterangan" name="keterangan">                    
                  </div>
                </div>

                <button class="btn btn-block btn-danger btn-flat" disabled> DATA PENDUKUNG </button> <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembelian</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="jenis_beli">
                      <option><?php echo $row->jenis_beli ?></option>
                      <option>Reguler</option>
                      <option>Kolektif</option>
                      <option>Joint Promo</option>
                    </select>
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Agama</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="agama">
                      <option><?php echo $row->agama ?></option>
                      <?php 
                      foreach ($dt_agama->result() as $isi) {
                        echo "<option value='$isi->agama'>$isi->agama</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Hobi</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="hobi">
                      <option><?php echo $row->hobi ?></option>
                      <?php 
                      foreach ($dt_hobi->result() as $isi) {
                        echo "<option value='$isi->hobi'>$isi->hobi</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Pendidikan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pendidikan" required="required">
                      <option><?php echo $row->pendidikan ?></option>
                      <?php 
                      foreach ($dt_pendidikan->result() as $isi) {
                        echo "<option value='$isi->pendidikan'>$isi->pendidikan</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Bersedia dikirimkan informasi terbaru dari program Honda?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="sedia_hub" required="required"="">
                      <option><?php echo $row->sedia_hub ?></option>                      
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Motor yg Dimiliki Sebelumnya?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_sebelumnya" required="required">
                      <option><?php echo $row->jenis_sebelumnya ?></option>
                      <?php 
                      foreach ($dt_jenis_sebelumnya->result() as $isi) {
                        echo "<option value='$isi->jenis_sebelumnya'>$isi->jenis_sebelumnya</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Merk motor yg Dimiliki Sebelumnya?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="merk_sebelumnya" required="required">
                      <option><?php echo $row->merk_sebelumnya ?></option>
                      <?php 
                      foreach ($dt_merk_sebelumnya->result() as $isi) {
                        echo "<option value='$isi->merk_sebelumnya'>$isi->merk_sebelumnya</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Sepeda Motor digunakan untuk?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="digunakan" required="required">
                      <option><?php echo $row->digunakan ?></option>
                      <?php 
                      foreach ($dt_digunakan->result() as $isi) {
                        echo "<option value='$isi->digunakan'>$isi->digunakan</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Yang Menggunakan Sepeda Motor?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="menggunakan" required="required">
                      <option><?php echo $row->menggunakan ?></option>
                      <option>Sendiri</option>
                      <option>Anak</option>
                      <option>Suami/Istri</option>
                    </select>
                  </div>
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Facebook</label>
                  <div class="col-sm-4">
                    <input value="<?php echo $row->facebook ?>" type="text" class="form-control" placeholder="Facebook" name="facebook">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Twitter</label>
                  <div class="col-sm-4">
                    <input value="<?php echo $row->twitter ?>" type="text" class="form-control" placeholder="Twitter" name="twitter">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Instagram</label>
                  <div class="col-sm-4">
                    <input value="<?php echo $row->instagram ?>" type="text" class="form-control" placeholder="Instagram" name="instagram">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Youtube</label>
                  <div class="col-sm-4">
                    <input value="<?php echo $row->youtube ?>" type="text" class="form-control" placeholder="Youtube" name="youtube">                    
                  </div>
                </div>                                    
                      
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
                  <button type="button" onClick="cancel_tr()" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
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
          <a href="dealer/cdb_d/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
              <th width="5%">No</th>
              <th>Nama Customer</th>              
              <th>Alamat</th>              
              <th>No HP</th>
              <th>No KTP</th>  
              <th>Action</th>            
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_cdb->result() as $row) {     
            echo "
            <tr>
              <td>$no</td>
              <td>$row->nama_konsumen</td>
              <td>$row->alamat</td>
              <td>$row->no_hp</td>
              <td>$row->no_ktp</td>
              <td>"; ?>
                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='dealer/cdb_d/edit?id=<?php echo $row->id_cdb ?>'><i class='fa fa-edit'></i></a>
              </td>                                                        
            </tr>
          <?php
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


<div class="modal fade" id="Spkmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search and Filter No SPK
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="10%"></th>              
              <th>No SPK</th>
              <th>Tgl SPK</th>
              <th>ID Customer</th>              
              <th>Nama Customer</th>                                    
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          foreach ($dt_spk->result() as $ve2) {
            echo "
            <tr>"; ?>
              <td class="center">
                <button title="Choose" onClick="Chooseitem('<?php echo $ve2->no_spk; ?>')" type="button" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
              </td>
              <?php echo "
              <td>$ve2->no_spk</td>
              <td>$ve2->tgl_spk</td>
              <td>$ve2->id_customer</td>
              <td>$ve2->nama_konsumen</td>";
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


<script type="text/javascript">
function samakan(){
  document.getElementById("alamat_penjamin").value = $("#alamat").val();                                                    ;   
}
function hide(){
  $("#lbl_kredit").hide();
  $("#lbl_cash").hide();
  $("#myTable1").hide();
  $("#myTable2").hide();
  $("#nilai_voucher").hide();
  $("#nilai_voucher_lbl").hide();
  $("#nilai_voucher2").hide();
  $("#nilai_voucher2_lbl").hide();
}
function cek_tanya(){
  var tanya = $("#tanya").val();
  if(tanya == 'Tidak'){
    $("#tampil_alamat").show();
    $("#id_kecamatan2").val("");    
    $("#id_kabupaten2").val("");
    $("#id_kelurahan2").val("");
    $("#id_provinsi2").val("");
  }else{
    $("#tampil_alamat").hide();
    document.getElementById("id_kecamatan2").value = $("#id_kecamatan").val();    
    document.getElementById("id_kabupaten2").value = $("#id_kabupaten").val();
    document.getElementById("id_kelurahan2").value = $("#id_kelurahan").val();
    document.getElementById("id_provinsi2").value  = $("#id_provinsi").val();    
  }
}

function Chooseitem(no_spk){
  document.getElementById("no_spk").value = no_spk; 
  cek_customer();
  $("#Spkmodal").modal("hide");
}

function cek_customer(){
  var no_spk=$("#no_spk").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/cdb_d/take_spk')?>",
      type:"GET",
      data:"no_spk="+no_spk,            
      cache:false,
      success:function(msg){ 

          data=msg.split("|");          
          $("#no_spk").val(data[0]);                
          $("#nama_konsumen").val(data[1]);                
          $("#tempat_lahir").val(data[2]);          
          $("#tgl_lahir").val(data[3]);          
          $("#jenis_wn").val(data[4]);                            
          $("#no_kk").val(data[5]);                                      
          $("#no_npwp").val(data[6]);                            
          $("#id_kelurahan").val(data[7]);                            
          $("#id_kelurahan2").val(data[8]);                            
          $("#alamat").val(data[9]);                            
          $("#alamat2").val(data[10]);                            
          $("#kodepos").val(data[11]);                            
          $("#denah_lokasi").val(data[12]);            
          $("#tanya").val(data[13]);            
          $("#status_rumah").val(data[14]);                            
          $("#lama_tinggal").val(data[15]);                            
          $("#pekerjaan").val(data[16]);                                                    
          $("#lama_kerja").val(data[17]);                                                    
          $("#jabatan").val(data[18]);                                                    
          $("#total_penghasilan").val(data[19]);                                                    
          $("#pengeluaran_bulan").val(data[20]);                                                    
          $("#no_hp").val(data[21]);                                                    
          $("#no_hp_2").val(data[22]);                                                    
          $("#status_hp").val(data[23]);                                                    
          $("#status_hp_2").val(data[24]);                                                    
          $("#no_telp").val(data[25]);                                                    
          $("#email").val(data[26]);                                                    
          $("#refferal_id").val(data[27]);                                                    
          $("#robd_id").val(data[28]);                                                    
          $("#nama_ibu").val(data[29]);                                                    
          $("#tgl_ibu").val(data[30]);                                                     
          $("#keterangan").val(data[31]);                                                    
          $("#no_ktp").val(data[32]);                                                    
          cek_tanya();
          take_kec();                      
      } 
  })
}
function take_harga(){
  var tipe_customer=$("#tipe_customer").val();                       
  var id_tipe_kendaraan=$("#id_tipe_kendaraan").val();                       
  var id_warna=$("#id_warna").val();             
  //alert(id_warna+id_tipe_kendaraan);
  $.ajax({
      url: "<?php echo site_url('dealer/cdb_d/cek_bbn')?>",
      type:"POST",
      data:"id_warna="+id_warna+"&id_tipe_kendaraan="+id_tipe_kendaraan+"&tipe_customer="+tipe_customer,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");          
          $("#biaya_bbn").val(data[0]);                                        
          $("#harga_on").val(data[1]);                
          $("#harga_off").val(data[2]);                
          $("#ppn").val(data[3]);                
          $("#harga").val(data[4]);                
          $("#harga_tunai").val(data[5]);                
          $("#biaya_bbn_r").val(convertToRupiah(data[0]));                                        
          $("#harga_off_r").val(convertToRupiah(data[2]));                
          $("#harga_on_r").val(convertToRupiah(data[1]));                
          $("#ppn_r").val(convertToRupiah(data[3]));                
          $("#harga_r").val(convertToRupiah(data[4]));                
          $("#harga_tunai_r").val(convertToRupiah(data[5]));                
          get_total();
      }
  })
}
function get_total(){
  var biaya_bbn = $("#biaya_bbn").val();                       
  var harga_tunai = $("#harga_tunai").val();                       
  var program_umum = $("#program_umum").val();                       
  var voucher_tambahan = $("#voucher_tambahan_2").val();                       
  var total = parseInt(harga_tunai) - parseInt(voucher_tambahan);
  var ubah_total = convertToRupiah(total);
  $("#total_bayar_r").val(ubah_total);
  $("#total_bayar").val(total);
}
function get_on(){
  var the_road = $("#the_road").val();
  var biaya_bbn = $("#biaya_bbn").val();                       
  var harga_tunai = $("#harga_tunai").val();                       
  var harga_off = $("#harga_off").val();                       
  var program_umum = $("#program_umum").val();                       
  var program_khusus = $("#program_khusus").val();                       
  $("#total_bayar").val(total);
  if(the_road == 'On The Road'){
    var total = parseInt(harga_tunai);
  }else{    
    var total = parseInt(harga_off);
  }
  $("#total_bayar").val(total);
}
function take_kec(){
  var id_kelurahan = $("#id_kelurahan").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/cdb_d/take_kec')?>",
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
function take_kec2(){
  var id_kelurahan = $("#id_kelurahan2").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/cdb_d/take_kec')?>",
      type:"POST",
      data:"id_kelurahan="+id_kelurahan,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");                    
          $("#id_kecamatan2").val(data[0]);                                                    
          $("#kecamatan2").val(data[1]);                                                    
          $("#id_kabupaten2").val(data[2]);                                                    
          $("#kabupaten2").val(data[3]);                                                    
          $("#id_provinsi2").val(data[4]);                                                    
          $("#provinsi2").val(data[5]);                                                    
          $("#kelurahan2").val(data[6]);                                                    
      } 
  })
}

function takes(){
  hide();
  take_kec();
  take_kec2();
  get_beli();
  //take_harga();
  get_total();
  $("#tampil_alamat").hide();
}

function cek_umur(){
     var today = new Date();
      var birthDate = new Date($('.tgl_lahir').val());
      var age = today.getFullYear() - birthDate.getFullYear();
      var m = today.getMonth() - birthDate.getMonth();
      if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
          age--;
      }
     if (age < 17) {
      alert('Usia Kurang Dari 17 Tahun')
      $('.tgl_lahir').val('');
     }
  }
</script>