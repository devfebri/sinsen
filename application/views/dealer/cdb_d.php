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
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script>
  $(document).ready(function(){
    // form_.addFile();
  })
</script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/cdb_d">
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
            <form id="form_" class="form-horizontal" action="dealer/cdb_d/save" method="post" enctype="multipart/form-data">
              <div class="box-body">                              
                <button class="btn btn-block btn-success btn-flat" disabled> CDB </button> <br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No SPK</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" name="no_spk" id="no_spk" placeholder="No SPK" required="required">                                                    
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
                    <input type="text" readonly id="no_kk" class="form-control" maxlength="16"  onkeypress="return number_only(event)" placeholder="No KK" name="no_kk" required="required">                    
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Domisili</label>
                  <div class="col-sm-10">
                    <input type="text" readonly class="form-control" maxlength="100" placeholder="Alamat Domisili"  name="alamat" id="alamat" required="required">                                        
                  </div>
                </div>
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
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat Sesuai KTP</label>
                    <div class="col-sm-10">
                      <input type="text" readonly class="form-control" id="alamat2" maxlength="100" placeholder="Alamat Sesuai KTP"  name="alamat2">                                        
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_kelurahan" id="id_kelurahan2">
                      <input type="text" readonly class="form-control" placeholder="Kelurahan Sesuai KTP" id="kelurahan2" name="kelurahan2" required="required">                                        
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
                      <input type="text" readonly class="form-control" id="kodepos2" placeholder="Kodepos Sesuai KTP"  name="kodepos2">                                        
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan (KTP)</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" placeholder="Pekerjaan (KTP)" id="pekerjaan" name="pekerjaan">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Lama Kerja</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Lama Kerja" id="lama_kerja" name="lama_kerja">
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan Saat Ini</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" placeholder="Pekerjaan saat ini" id="sub_pekerjaan" name="sub_pekerjaan">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jabatan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Jabatan" id="jabatan" name="jabatan">
                  </div>     
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" placeholder="Lain-Lain" id="lain" name="lain">
                  </div>            
                </div>
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Instansi / Usaha</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Nama Instansi / Usaha" id="nama_instansi" name="nama_instansi">
                  </div>                 
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Instansi / Usaha</label>
                  <div class="col-sm-10">
                    <input type="text" readonly class="form-control" placeholder="Alamat Instansi / Usaha" id="alamat_instansi" name="alamat_instansi">
                  </div>                 
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Instansi / Usaha</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Kelurahan Instansi / Usaha" id="kelurahan_instansi" name="kelurahan_instansi" readonly>
                    <input type="hidden" id="id_kelurahan_instansi" name="_id_kelurahan_instansi">
                  </div>                 
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Instansi / Usaha</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Kecamatan Instansi / Usaha" id="kecamatan_instansi" name="kecamatan_instansi" readonly> <!-- onclick="getKecInstansi()" -->
                    <input type="hidden" id="id_kecamatan_instansi" name="id_kecamatan_instansi">
                  </div>                 
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kota Instansi / Usaha</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Kota Instansi / Usaha" id="kabupaten_instansi" name="kabupaten_instansi" readonly >
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Instansi / Usaha</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Provinsi Instansi / Usaha" id="provinsi_instansi" name="provinsi_instansi" readonly >
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
                    <input readonly type="text" class="form-control" maxlength="15" placeholder="No HP #1" id="no_hp"  name="no_hp" required="required">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp #1</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control"  placeholder="Status No HP #1" id="status_hp"  name="status_hp" required="required">                                                                                                            
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP #2</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" maxlength="15" placeholder="No HP #2" id="no_hp_2"  name="no_hp_2">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp #2</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control"  placeholder="Status No HP #2" id="status_hp_2"  name="status_hp_2">                                        
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" maxlength="15" readonly placeholder="No Telp" id="no_telp" name="no_telp">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" maxlength="100" readonly placeholder="Email" id="email" name="email" required="required">                                        
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
                    <input type="text" class="form-control" readonly maxlength="200" placeholder="Keterangan" id="keterangan" name="keterangan">                    
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Aktivitas Penjualan</label>
                  <div class="col-sm-4"> 
                    <input type="text" class="form-control" id="aktivitas_penjualan_value" readonly>            
                    <input type="hidden" class="form-control" id="aktivitas_penjualan" name="aktivitas_penjualan">            
                    
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Agama</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="agama" id="agama">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_agama->result() as $isi) {
                        echo "<option value='$isi->id_agama'>$isi->agama</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Hobi</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="hobi" required="required">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_hobi->result() as $isi) {
                        echo "<option value='$isi->id_hobi'>$isi->hobi</option>";
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
                        echo "<option value='$isi->id_pendidikan'>$isi->pendidikan</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Bersedia dikirimkan informasi terbaru dari program Honda?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="sedia_hub" required="required">
                      <option value="">- choose -</option>                      
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Motor yg Dimiliki Sebelumnya?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_sebelumnya" id="jenis_sebelumnya" required="required">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_jenis_sebelumnya->result() as $isi) {
                        echo "<option value='$isi->id_jenis_sebelumnya'>$isi->jenis_sebelumnya</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Merk motor yg Dimiliki Sebelumnya?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="merk_sebelumnya" id="merk_sebelumnya" required="required">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_merk_sebelumnya->result() as $isi) {
                        echo "<option value='$isi->id_merk_sebelumnya'>$isi->merk_sebelumnya</option>";
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
                        echo "<option value='$isi->id_digunakan'>$isi->digunakan</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Yang Menggunakan Sepeda Motor?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="menggunakan" id="menggunakan" required="required">
                      <option value="">- choose -</option>
                      <option>Saya Sendiri</option>
                      <option>Anak</option>
                      <option>Pasangan Suami/Istri</option>                      
                    </select>
                  </div>
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Facebook</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Facebook" maxlength="43" name="facebook">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Twitter</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Twitter" maxlength="43" name="twitter">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Instagram</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Instagram" maxlength="43" name="instagram">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Youtube</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Youtube" maxlength="43" name="youtube">                    
                  </div>
                </div>                                    
              <button class="btn btn-block btn-warning btn-flat" disabled> DATA KARTU KELUARGA </button> <br>
              <div class="form-group">
                <div class="col-md-3">
                  NIK
                  <input type="text" class="form-control" minlength="16" maxlength="16" id="kk_nik" onkeypress="return number_only(event)" v-model="kk.nik" >
                </div>
                <div class="col-md-3">
                  Nama Lengkap (Sesuai KK)
                  <input type="text" class="form-control" id="kk_nama" v-model="kk.nama_lengkap">
                </div>
                <div class="col-md-3">
                  Jenis Kelamin
                  <select v-model="kk.id_jk" class="form-control" id="kk_jk">
                          <option value="">-choose-</option>
                          <option value="1">Laki-laki</option>
                          <option value="2">Perempuan</option>
                        </select>
                </div>
                 <div class="col-md-3">
                  Tempat Lahir
                  <input type="text" class="form-control" id="kk_tempat_lahir" v-model="kk.tempat_lahir">
                </div>
                <div class="col-md-3">
                  Tanggal Lahir
                  <input type="text" class="form-control datepicker tgl_lahir_kk" id="kk_tanggal_lahir" placeholder="yyyy-mm-dd" v-model="kk.tgl_lahir" onchange="setTglLahirKK(this)">
                </div>
                <div class="col-md-3">
                  Agama
                  <select v-model="kk.id_agama" class="form-control" id="kk_agama">
                          <?php 
                            $this->db->order_by('id_agama','ASC');
                            $agama = $this->db->get('ms_agama');
                            if ($agama->num_rows()>0) {
                              echo "<option value=''>-choose-</option>";
                              foreach ($agama->result() as $agm) {
                                echo "<option value='$agm->id_agama'>$agm->agama</option>";
                              }
                            }
                          ?>
                        </select>
                </div>
                <div class="col-md-3">
                  Pendidikan
                  <select v-model="kk.id_pendidikan" class="form-control" id="kk_pendidikan">
                          <?php 
			    $this->db->where('active','1');
                            $this->db->order_by('id_pendidikan','ASC');
                            $pendidikan = $this->db->get('ms_pendidikan');
                            if ($pendidikan->num_rows()>0) {
                              echo "<option value=''>-choose-</option>";
                              foreach ($pendidikan->result() as $rs) {
                                echo "<option value='$rs->id_pendidikan'>$rs->pendidikan</option>";
                              }
                            }
                          ?>
                        </select>
                </div>
                <div class="col-md-3">
                  Pekerjaan
                  <select v-model="kk.id_pekerjaan" class="form-control" id="kk_pekerjaan" onchange="pekerjaan_lain2()">
                          <?php 
                            $this->db->where('active','1');
                            $this->db->order_by('pekerjaan','ASC');
                            if(date('Y-m-d')<'2020-09-07'){
                              $pekerjaan = $this->db->get('ms_pekerjaan');
                            }else{
                              $pekerjaan = $this->db->get('ms_pekerjaan_kk');
                            }
                            if ($pekerjaan->num_rows()>0) {
                              echo "<option value=''>-choose-</option>";
                              foreach ($pekerjaan->result() as $rs) {
                                echo "<option value='$rs->id_pekerjaan'>$rs->pekerjaan</option>";
                              }
                            }
                          ?>
                        </select>
                </div>
                <div class="col-md-3">
                  Status Pernikahan
                  <select v-model="kk.id_status_pernikahan" class="form-control" id="kk_status_pernikahan">
                          <?php 
                            $this->db->order_by('id_status_pernikahan','ASC');
                            $status_pernikahan = $this->db->get('ms_status_pernikahan');
                            if ($status_pernikahan->num_rows()>0) {
                              echo "<option value=''>-choose-</option>";
                              foreach ($status_pernikahan->result() as $rs) {
                                echo "<option value='$rs->id_status_pernikahan'>$rs->status_pernikahan</option>";
                              }
                            }
                          ?>
                        </select>
                </div>
                <div class="col-md-3">
                  Status Hubungan Dalam Keluarga
                   <select v-model="kk.id_hub_keluarga" class="form-control" id="kk_hub_keluarga">
                          <?php 
                            $this->db->order_by('id_hub_keluarga','ASC');
                            $pekerjaan = $this->db->get_where('ms_hub_keluarga');
                            if ($pekerjaan->num_rows()>0) {
                              echo "<option value=''>-choose-</option>";
                              foreach ($pekerjaan->result() as $rs) {
                                echo "<option value='$rs->id_hub_keluarga'>$rs->hub_keluarga</option>";
                              }
                            }
                          ?>
                        </select>
                </div>
                <div class="col-md-3">
                  Kewarganegaraan
                  <select v-model="kk.jenis_wn" class="form-control" id="kk_wn">
                          <option value="">-choose-</option>
                          <option value="1">WNI</option>
                          <option value="2">WNA</option>
                        </select>
                </div>
                <div class="col-md-3" id="sebutkan_pekerjaan">
                  Sebutkan Pekerjaan Lainnya
                  <input type="text" class="form-control" v-model="kk.pekerjaan_lain" id="lain2nya">
                </div>
                <div class="col-md-12" align="center" style="margin-bottom: 10pt"><br>
                  <button type="button" onclick="form_.addDetails()" class="btn btn-primary btn-flat"> 
                          <i class="fa fa-plus"></i>  Tambah Anggota
                        </button>
                </div>
                <div class="col-md-12">
                  <table class="table table-bordered">
                  <thead>
                    <th>NIK</th>
                    <th>Nama Lengkap</th>
                    <th>Jenis Kelamin</th>
                    <th>Tempat Lahir</th>
                    <th>Tgl Lahir</th>
                    <th>Agama</th>
                    <th>Pendidikan</th>
                    <th>Pekerjaan</th>
                    <th>Pekerjaan lain-lain</th>
                    <th>Status Pernikahan</th>
                    <th>Status Hubungan Dalam Keluarga</th>
                    <th>Kewarganegaraan</th>
                    <th>Aksi</th>
                  </thead>
                  <tbody>
                    <tr v-for="(dtl, index) of kk_">
                      <td>{{dtl.nik}}
                        <input type="hidden" name="kk_nik[]" v-model="dtl.nik">
                      </td>
                      <td>{{dtl.nama_lengkap}}
                        <input type="hidden" name="kk_nama_lengkap[]" v-model="dtl.nama_lengkap">
                      </td>
                      <td>{{dtl.jk}}
                        <input type="hidden" name="kk_id_jk[]" v-model="dtl.id_jk">
                      </td>
                      <td>{{dtl.tempat_lahir}}
                        <input type="hidden" name="kk_tempat_lahir[]" v-model="dtl.tempat_lahir">
                      </td>
                      <td>{{dtl.tgl_lahir}}
                        <input type="hidden" name="kk_tgl_lahir[]" v-model="dtl.tgl_lahir">
                      </td>
                      <td>{{dtl.agama}}
                        <input type="hidden" name="kk_id_agama[]" v-model="dtl.id_agama">
                      </td>
                      <td>{{dtl.pendidikan}}
                        <input type="hidden" name="kk_id_pendidikan[]" v-model="dtl.id_pendidikan">
                      </td>
                      <td>{{dtl.pekerjaan}}
                        <input type="hidden" name="kk_id_pekerjaan[]" v-model="dtl.id_pekerjaan">
                      </td>
                      <td>{{dtl.pekerjaan_lain}}
                        <input type="hidden" name="kk_pekerjaan_lain[]" v-model="dtl.pekerjaan_lain">
                      </td>
                      <td>{{dtl.status_pernikahan}}
                        <input type="hidden" name="kk_id_status_pernikahan[]" v-model="dtl.id_status_pernikahan">
                      </td>
                      <td>{{dtl.hub_keluarga}}
                        <input type="hidden" name="kk_id_hub_keluarga[]" v-model="dtl.id_hub_keluarga">
                      </td>
                      <td>{{dtl.wn}}
                        <input type="hidden" name="kk_jenis_wn[]" v-model="dtl.jenis_wn">
                      </td>
                      <td>
                        <button class="btn btn-flat btn-danger btn-xs" @click.prevent="form_.delKK_(index)"><i class="fa fa-trash"></i></button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                </div>               
              </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<div class="modal fade modalKecamatan" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Data Kecamatan</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="no_mesin_part">
        <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_kecamatan" style="width: 100%">
                  <thead>
                  <tr>
                      <th>Kode Kecamatan</th>
                      <th>Nama Kecamatan</th>
                      <th>Kabupaten</th>
                      <th>Provinsi</th>
                      <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
              <script>
                  $(document).ready(function(){
                      $('#tbl_kecamatan').DataTable({
                          processing: true,
                          serverSide: true,
                          "language": {                
                                  "infoFiltered": ""
                              },
                          order: [],
                          ajax: {
                              url: "<?= base_url('dealer/cdb_d/fetch_kecamatan') ?>",
                              dataSrc: "data",
                              data: function ( d ) {
                                    return d;
                                },
                              type: "POST"
                          },
                          "columnDefs":[  
                      // { "targets":[4],"orderable":false},
                      { "targets":[2],"className":'text-center'}, 
                      // { "targets":[4], "searchable": false } 
                 ]
                      });
                  });
              </script>
      </div>
    </div>
  </div>
</div>
<script>
  var form_ = new Vue({
      el: '#form_',
      data: {
        kk:{
          nik:'',
          nama_lengkap:'',
          id_jk:'',
          jk:'',
          tempat_lahir:'',
          tgl_lahir:'',
          id_agama:'',
          agama:'',
          id_pendidikan:'',
          pendidikan:'',
          id_pekerjaan:'',
          pekerjaan:'',
          id_status_pernikahan:'',
          status_pernikahan:'',
          id_hub_keluarga:'',
          hub_keluarga:'',
          jenis_wn:'',
          wn:'',
          pekerjaan_lain:''
        },
        kk_ :[]
      },
      methods: {
        setJK:function (el) {
          var text = el.options[el.selectedIndex].text;
          this.kk.jk = text;
        },
        
        // getWarna: function() {
        //   var element   = $('#id_tipe_kendaraan').find('option:selected'); 
        //   var id_tipe_kendaraan = $('#id_tipe_kendaraan').val();
        //   if (id_tipe_kendaraan=='' || id_tipe_kendaraan==null) {
        //     $('#id_warna').html('');
        //     return false;
        //   }
        //   var warnas    = JSON.parse(element.attr("data-warna")); 
        //   var tipe_unit = element.attr("data-tipe_unit");
        //   form_.detail.tipe_unit = tipe_unit; 
        //   form_.detail.id_tipe_kendaraan = $('#id_tipe_kendaraan').val(); 
        //   $('#id_warna').html('');
        //     if (warnas.length>0) {
        //       $('#id_warna').append($('<option>').text('--choose--').attr('value', ''));
        //     }
        //   $.each(warnas, function(i, value) {
        //     $('#id_warna').append($('<option>').text(warnas[i].id_warna+' | '+warnas[i].warna).attr({'value':warnas[i].id_warna,'warna':warnas[i].warna}));
        //   });
        // },
        // getDetail: function() {
        //   var element           = $('#id_warna').find('option:selected'); 
        //   var warna             = element.attr("warna");
        //   form_.detail.warna    = warna; 
        //   form_.detail.id_warna = $('#id_warna').val(); 
        //   values = {id_tipe_kendaraan:$('#id_tipe_kendaraan').val(),
        //             id_warna:$('#id_warna').val()
        //            }
        //   $.ajax({
        //     url:"<?php echo site_url('dealer/po_dealer_new/getDetail');?>",
        //     type:"POST",
        //     data:values,
        //     cache:false,
        //     dataType:'JSON',
        //     success:function(response){
        //       form_.detail.current_stock = response.current_stock;
        //       form_.detail.monthly_sale  = response.monthly_sale;
        //       form_.detail.po_t1_last    = response.po_t1_last;
        //       form_.detail.po_t2_last    = response.po_t2_last;
        //       form_.detail.qty_indent    = response.qty_indent;
        //       form_.detail.harga         = response.harga;
        //       // console.log(form_.detail)
        //     }
        //   });
        // },
        clearDetail: function(){
          $('#id_tipe_kendaraan').val('').trigger('change');
          // $('#id_warna').html('');
             this.kk={
          nik:'',
          nama_lengkap:'',
          id_jk:'',
          jk:'',
          tempat_lahir:'',
          tgl_lahir:'',
          id_agama:'',
          agama:'',
          id_pendidikan:'',
          pendidikan:'',
          id_pekerjaan:'',
          pekerjaan:'',
          id_status_pernikahan:'',
          status_pernikahan:'',
          id_hub_keluarga:'',
          hub_keluarga:'',
          jenis_wn:'',
          wn:'',
          pekerjaan_lain:''
        }
        },
        // showModalItem : function() {
        //   // $('#tbl_part').DataTable().ajax.reload();
        //   $('.modalItem').modal('show');
        // },
        addDetails : function(){
          if (this.kk.nik=='' ||
              this.kk.nama_lengkap==''||
              this.kk.id_jk==''||
              this.kk.tempat_lahir==''||
              this.kk.tgl_lahir==''||
              this.kk.id_agama==''||
              this.kk.id_pendidikan==''||
              this.kk.id_pekerjaan==''||
              this.kk.id_status_pernikahan==''||
              this.kk.id_hub_keluarga==''||
              this.kk.jenis_wn==''
              ) 
          {
            alert('Isi data dengan lengkap !');
            return false;
          }else{
            if(this.kk.id_pekerjaan==89){
              if(this.kk.pekerjaan_lain==''){
                alert('Isi data dengan lengkap !');
                return false;
              }
            }else{
              this.kk.pekerjaan_lain = '';
            }
          }

          this.kk.jk = $('#kk_jk option:selected').text();
          this.kk.agama = $('#kk_agama option:selected').text();
          this.kk.pendidikan = $('#kk_pendidikan option:selected').text();
          this.kk.pekerjaan = $('#kk_pekerjaan option:selected').text();
          this.kk.status_pernikahan = $('#kk_status_pernikahan option:selected').text();
          this.kk.hub_keluarga = $('#kk_hub_keluarga option:selected').text();
          this.kk.wn = $('#kk_wn option:selected').text();
          this.kk_.push(this.kk);
          this.clearDetail();
        },
  
        delKK_: function(index){
            this.kk_.splice(index, 1);
        }
      },
      watch:{
        detail:function () {
          // alert('dd');
        }
      },
      computed: {
        // totDetail:function(detail) {
        //   po_fix     = detail.po_fix==''?0:detail.po_fix;
        //   qty_indent = detail.qty_indent==''?0:detail.qty_indent;
        //   total      = detail.harga * (parseInt(po_fix)+parseInt(qty_indent));
        //   ppn = total *(10/100);
        //   this.detail.total_harga = total+ppn;
        //   return total;
        // },
      },
  });

  function pekerjaan_lain2(){
    var pekerjaan = $('#kk_pekerjaan').val();
    if(pekerjaan == '89'){
      $('#sebutkan_pekerjaan').show();
    }else{
      $('#sebutkan_pekerjaan').hide(); 
    }
  }

  function setTglLahirKK(el) {
    form_.kk.tgl_lahir = $(el).val();
    // console.log(form_.kk)
    var today = new Date();
    var birthDate = new Date($(el).val());
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
      age--;
    }
    // if (age < 17) {
    //   alert('Usia Kurang Dari 17 Tahun')
    //   $('.tgl_lahir_kk').val('');
    // }
  }
  function getKecInstansi() {
    $('.modalKecamatan').modal('show');
  }
  function pilihKecamatan(kec) {
    $('#id_kecamatan_instansi').val(kec.id_kecamatan);
    $('#kecamatan_instansi').val(kec.kecamatan);
    $('#kabupaten_instansi').val(kec.kabupaten);
    $('#provinsi_instansi').val(kec.provinsi);
  }
$('#submitBtn').click(function(){
  $('#form_').validate({
      rules: {
          'checkbox': {
              required: true
          }
      },
      highlight: function (input) {
          $(input).parents('.form-group').addClass('has-error');
      },
      unhighlight: function (input) {
          $(input).parents('.form-group').removeClass('has-error');
      }
  })
  if ($('#form_').valid()) // check if form is valid
  {
    if (form_.kk_.length==0) {
      alert('Silahkan isi data Kartu Keluarga terlebih dahulu !');
      return false;
    }
    $('#form_').submit();
  }else{
    alert('Silahkan isi field required !')
  }
})  
</script>
    <?php 
    }elseif($set=="edit"){
      $row = $dt_cdb->row();
    ?>
    
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/cdb_d">
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
            <form id="form_" class="form-horizontal" action="dealer/cdb_d/update" method="post" enctype="multipart/form-data">
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
                    <input type="text" readonly id="no_kk" class="form-control" maxlength="16" onkeypress="return number_only(event)" placeholder="No KK" name="no_kk" required="required">                    
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Domisili</label>
                  <div class="col-sm-10">
                    <input type="text" readonly class="form-control" maxlength="100" placeholder="Alamat Domisili"  name="alamat" id="alamat" required="required">                                        
                  </div>
                </div>
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
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat Sesuai KTP</label>
                    <div class="col-sm-10">
                      <input type="text" readonly class="form-control" id="alamat2" maxlength="100" placeholder="Alamat Sesuai KTP"  name="alamat2">                                        
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_kelurahan" id="id_kelurahan2">
                      <input type="text" readonly class="form-control" id="kelurahan2" placeholder="Kelurahan Sesuai KTP"  name="kelurahan2">                                   
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
                      <input type="text" readonly class="form-control" id="kodepos2" placeholder="Kodepos Sesuai KTP"  name="kodepos2">
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan (KTP)</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" placeholder="Pekerjaan (KTP)" id="pekerjaan" name="pekerjaan">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Lama Kerja</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Lama Kerja" id="lama_kerja" name="lama_kerja">
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan Saat Ini</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" placeholder="Pekerjaan saat ini" id="sub_pekerjaan" name="sub_pekerjaan">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jabatan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Jabatan" id="jabatan" name="jabatan">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" placeholder="Lain-Lain" id="lain" name="lain">
                  </div>                 
                </div>
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Instansi / Usaha</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" placeholder="Nama Instansi / Usaha" id="nama_instansi" name="nama_instansi">
                  </div>                 
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Instansi / Usaha</label>
                  <div class="col-sm-10">
                    <input type="text" readonly class="form-control" placeholder="Alamat Instansi / Usaha" id="alamat_instansi" name="alamat_instansi">
                  </div>                 
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Instansi / Usaha</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Kelurahan Instansi / Usaha" id="kelurahan_instansi" name="kelurahan_instansi" readonly>
                    <input type="hidden" id="id_kelurahan_instansi" name="id_kelurahan_instansi">
                  </div>  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Instansi / Usaha</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Kecamatan Instansi / Usaha" id="kecamatan_instansi" name="kecamatan_instansi" readonly onclick="getKecInstansi()">
                    <input type="hidden" id="id_kecamatan_instansi" name="id_kecamatan_instansi" value="<?= $row->id_kecamatan_instansi ?>">
                  </div>                 
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kota Instansi / Usaha</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Kota Instansi / Usaha" id="kabupaten_instansi" name="kabupaten_instansi" readonly >
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Instansi / Usaha</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Provinsi Instansi / Usaha" id="provinsi_instansi" name="provinsi_instansi" readonly >
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
                    <input readonly type="text" class="form-control" maxlength="15" placeholder="No HP #1" id="no_hp"  name="no_hp" required="required">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp #1</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control"  placeholder="Status No HP #1" id="status_hp"  name="status_hp" required="required">                                                                                                            
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP #2</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" maxlength="15" placeholder="No HP #2" id="no_hp_2"  name="no_hp_2">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp #2</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control"  placeholder="Status No HP #2" id="status_hp_2"  name="status_hp_2">                                        
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly maxlength="15" placeholder="No Telp" id="no_telp" name="no_telp">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly placeholder="Email" maxlength="100" id="email" name="email" required="required">                                        
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Aktivitas Penjualan</label>
                  <div class="col-sm-4"> 
                    <input type="text" class="form-control" id="aktivitas_penjualan_value" value="<?= $row->aktivitas_penjualan ?>" readonly>            
                    <input type="hidden" class="form-control" id="aktivitas_penjualan" name="aktivitas_penjualan" value="<?php echo $row->aktivitas_penjualan ?>">            
                    
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Agama</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="agama">
                      <option value="<?php echo $row->agama ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_agama","id_agama",$row->agama)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->agama;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt = $this->m_admin->kondisiCond("ms_agama","id_agama != '$row->agama'");                                                
                      foreach($dt->result() as $val) {
                        echo "
                        <option value='$val->id_agama'>$val->agama</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Hobi</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="hobi" required="required">
                      <option value="<?php echo $row->hobi ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_hobi","id_hobi",$row->hobi)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->hobi;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt = $this->m_admin->kondisiCond("ms_hobi","id_hobi != '$row->hobi'");                                                
                      foreach($dt->result() as $val) {
                        echo "
                        <option value='$val->id_hobi'>$val->hobi</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Pendidikan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pendidikan" required="required">
                      <option value="<?php echo $row->pendidikan ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_pendidikan","id_pendidikan",$row->pendidikan)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->pendidikan;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
          $array = array("id_pendidikan !=" => $row->pendidikan,"active"=>1);
                      $dt = $this->m_admin->kondisiCond("ms_pendidikan",$array);                                                
                      foreach($dt->result() as $val) {
                        echo "
                        <option value='$val->id_pendidikan'>$val->pendidikan</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Bersedia dikirimkan informasi terbaru dari program Honda?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="sedia_hub" required="required">
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
                      <option value="<?php echo $row->jenis_sebelumnya ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_jenis_sebelumnya","id_jenis_sebelumnya",$row->jenis_sebelumnya)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->jenis_sebelumnya;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt = $this->m_admin->kondisiCond("ms_jenis_sebelumnya","id_jenis_sebelumnya != '$row->jenis_sebelumnya'");                                                
                      foreach($dt->result() as $val) {
                        echo "
                        <option value='$val->id_jenis_sebelumnya'>$val->jenis_sebelumnya</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Merk motor yg Dimiliki Sebelumnya?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="merk_sebelumnya" required="required">
                      <option value="<?php echo $row->merk_sebelumnya ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_merk_sebelumnya","id_merk_sebelumnya",$row->merk_sebelumnya)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->merk_sebelumnya;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt = $this->m_admin->kondisiCond("ms_merk_sebelumnya","id_merk_sebelumnya != '$row->merk_sebelumnya'");                                                
                      foreach($dt->result() as $val) {
                        echo "
                        <option value='$val->id_merk_sebelumnya'>$val->merk_sebelumnya</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Sepeda Motor digunakan untuk?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="digunakan" required="required">
                      <option value="<?php echo $row->digunakan ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_digunakan","id_digunakan",$row->digunakan)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->digunakan;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt = $this->m_admin->kondisiCond("ms_digunakan","id_digunakan != '$row->digunakan'");                                                
                      foreach($dt->result() as $val) {
                        echo "
                        <option value='$val->id_digunakan'>$val->digunakan</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Yang Menggunakan Sepeda Motor?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="menggunakan" required="required">
                      <option><?php echo $row->menggunakan ?></option>
                      <option>Saya Sendiri</option>
                      <option>Anak</option>
                      <option>Pasangan Suami/Istri</option>
                    </select>
                  </div>
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Facebook</label>
                  <div class="col-sm-4">
                    <input value="<?php echo $row->facebook ?>" type="text" maxlength="43" class="form-control" placeholder="Facebook" name="facebook">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Twitter</label>
                  <div class="col-sm-4">
                    <input value="<?php echo $row->twitter ?>" type="text" maxlength="43" class="form-control" placeholder="Twitter" name="twitter">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Instagram</label>
                  <div class="col-sm-4">
                    <input value="<?php echo $row->instagram ?>" type="text" maxlength="43" class="form-control" placeholder="Instagram" name="instagram">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Youtube</label>
                  <div class="col-sm-4">
                    <input value="<?php echo $row->youtube ?>" type="text" maxlength="43" class="form-control" placeholder="Youtube" name="youtube">                    
                  </div>
                </div>                                    
        <button class="btn btn-block btn-warning btn-flat" disabled> DATA KARTU KELUARGA</button> <br>
              <div class="form-group">
                <div class="col-md-3">
                  NIK
                  <input type="text" class="form-control" minlength="16" maxlength="16" onkeypress="return number_only(event)"  v-model="kk.nik">
                </div>
                <div class="col-md-3">
                  Nama Lengkap (Sesuai KK)
                  <input type="text" class="form-control" v-model="kk.nama_lengkap">
                </div>
                <div class="col-md-3">
                  Jenis Kelamin
                  <select v-model="kk.id_jk" class="form-control" id="kk_jk">
                          <option value="">-choose-</option>
                          <option value="1">Laki-laki</option>
                          <option value="2">Perempuan</option>
                        </select>
                </div>
                 <div class="col-md-3">
                  Tempat Lahir
                  <input type="text" class="form-control" v-model="kk.tempat_lahir">
                </div>
                <div class="col-md-3">
                  Tanggal Lahir
                  <input type="text" class="form-control datepicker tgl_lahir_kk" placeholder="yyyy-mm-dd" v-model="kk.tgl_lahir" onchange="setTglLahirKK(this)">
                </div>
                <div class="col-md-3">
                  Agama
                  <select v-model="kk.id_agama" class="form-control" id="kk_agama">
                          <?php 
                            $this->db->order_by('id_agama','ASC');
                            $agama = $this->db->get('ms_agama');
                            if ($agama->num_rows()>0) {
                              echo "<option value=''>-choose-</option>";
                              foreach ($agama->result() as $agm) {
                                echo "<option value='$agm->id_agama'>$agm->agama</option>";
                              }
                            }
                          ?>
                        </select>
                </div>
                <div class="col-md-3">
                  Pendidikan
                  <select v-model="kk.id_pendidikan" class="form-control" id="kk_pendidikan">
                          <?php 
          $this->db->where("active",1);
                            $this->db->order_by('id_pendidikan','ASC');
                            $pendidikan = $this->db->get('ms_pendidikan');
                            if ($pendidikan->num_rows()>0) {
                              echo "<option value=''>-choose-</option>";
                              foreach ($pendidikan->result() as $rs) {
                                echo "<option value='$rs->id_pendidikan'>$rs->pendidikan</option>";
                              }
                            }
                          ?>
                        </select>
                </div>
                <div class="col-md-3">
                  Pekerjaan
                  <select v-model="kk.id_pekerjaan" class="form-control" id="kk_pekerjaan" onchange="pekerjaan_lain2()">
                          <?php 
                            $this->db->where('active','1');
                            $this->db->order_by('pekerjaan','ASC');
                            if(date('Y-m-d')<'2020-09-07'){
                              $pekerjaan = $this->db->get('ms_pekerjaan');
                            }else{
                              $pekerjaan = $this->db->get('ms_pekerjaan_kk');
                            }
                            if ($pekerjaan->num_rows()>0) {
                              echo "<option value=''>-choose-</option>";
                              foreach ($pekerjaan->result() as $rs) {
                                echo "<option value='$rs->id_pekerjaan'>$rs->pekerjaan</option>";
                              }
                            }
                          ?>
                        </select>
                </div>
                <div class="col-md-3">
                  Status Pernikahan
                  <select v-model="kk.id_status_pernikahan" class="form-control" id="kk_status_pernikahan">
                          <?php 
                            $this->db->order_by('id_status_pernikahan','ASC');
                            $status_pernikahan = $this->db->get('ms_status_pernikahan');
                            if ($status_pernikahan->num_rows()>0) {
                              echo "<option value=''>-choose-</option>";
                              foreach ($status_pernikahan->result() as $rs) {
                                echo "<option value='$rs->id_status_pernikahan'>$rs->status_pernikahan</option>";
                              }
                            }
                          ?>
                        </select>
                </div>
                <div class="col-md-3">
                  Status Hubungan Dalam Keluarga
                   <select v-model="kk.id_hub_keluarga" class="form-control" id="kk_hub_keluarga">
                          <?php 
                            $this->db->order_by('id_hub_keluarga','ASC');
                            $pekerjaan = $this->db->get_where('ms_hub_keluarga');
                            if ($pekerjaan->num_rows()>0) {
                              echo "<option value=''>-choose-</option>";
                              foreach ($pekerjaan->result() as $rs) {
                                echo "<option value='$rs->id_hub_keluarga'>$rs->hub_keluarga</option>";
                              }
                            }
                          ?>
                        </select>
                </div>
                <div class="col-md-3">
                  Kewarganegaraan
                  <select v-model="kk.jenis_wn" class="form-control" id="kk_wn">
                          <option value="">-choose-</option>
                          <option value="1">WNI</option>
                          <option value="2">WNA</option>
                        </select>
                </div>
                 <div class="col-md-3" id="sebutkan_pekerjaan">
                  Sebutkan Pekerjaan Lainnya
                  <input type="text" class="form-control" v-model="kk.pekerjaan_lain" id="lain2nya">
                </div>
                <div class="col-md-12" align="center" style="margin-bottom: 10pt"><br>
                  <button type="button" onclick="form_.addDetails()" class="btn btn-primary btn-flat">
                          <i class="fa fa-plus"></i>  Tambah Anggota
                        </button>
                </div>
                <div class="col-md-12">
                  <table class="table table-bordered">
                  <thead>
                    <th>NIK</th>
                    <th>Nama Lengkap</th>
                    <th>Jenis Kelamin</th>
                    <th>Tempat Lahir</th>
                    <th>Tgl Lahir</th>
                    <th>Agama</th>
                    <th>Pendidikan</th>
                    <th>Pekerjaan</th>
                    <th>Pekerjaan lain-lain</th>
                    <th>Status Pernikahan</th>
                    <th>Status Hubungan Dalam Keluarga</th>
                    <th>Kewarganegaraan</th>
                    <th>Aksi</th>
                  </thead>
                  <tbody>
                    <tr v-for="(dtl, index) of kk_">
                      <td>{{dtl.nik}}
                        <input type="hidden" name="kk_nik[]" v-model="dtl.nik">
                      </td>
                      <td>{{dtl.nama_lengkap}}
                        <input type="hidden" name="kk_nama_lengkap[]" v-model="dtl.nama_lengkap">
                      </td>
                      <td>{{dtl.jk}}
                        <input type="hidden" name="kk_id_jk[]" v-model="dtl.id_jk">
                      </td>
                      <td>{{dtl.tempat_lahir}}
                        <input type="hidden" name="kk_tempat_lahir[]" v-model="dtl.tempat_lahir">
                      </td>
                      <td>{{dtl.tgl_lahir}}
                        <input type="hidden" name="kk_tgl_lahir[]" v-model="dtl.tgl_lahir">
                      </td>
                      <td>{{dtl.agama}}
                        <input type="hidden" name="kk_id_agama[]" v-model="dtl.id_agama">
                      </td>
                      <td>{{dtl.pendidikan}}
                        <input type="hidden" name="kk_id_pendidikan[]" v-model="dtl.id_pendidikan">
                      </td>
                      <td>{{dtl.pekerjaan}}
                        <input type="hidden" name="kk_id_pekerjaan[]" v-model="dtl.id_pekerjaan">
                      </td>
                      <td>{{dtl.pekerjaan_lain}}
                        <input type="hidden" name="kk_pekerjaan_lain[]" v-model="dtl.pekerjaan_lain">
                      </td>
                      <td>{{dtl.status_pernikahan}}
                        <input type="hidden" name="kk_id_status_pernikahan[]" v-model="dtl.id_status_pernikahan">
                      </td>
                      <td>{{dtl.hub_keluarga}}
                        <input type="hidden" name="kk_id_hub_keluarga[]" v-model="dtl.id_hub_keluarga">
                      </td>
                      <td>{{dtl.wn}}
                        <input type="hidden" name="kk_jenis_wn[]" v-model="dtl.jenis_wn">
                      </td>
                      <td>
                        <button class="btn btn-flat btn-danger btn-xs" @click.prevent="form_.delKK_(index)"><i class="fa fa-trash"></i></button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                </div>               
              </div>
                      
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<div class="modal fade modalKecamatan" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Data Kecamatan</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="no_mesin_part">
        <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_kecamatan" style="width: 100%">
                  <thead>
                  <tr>
                      <th>Kode Kecamatan</th>
                      <th>Nama Kecamatan</th>
                      <th>Kabupaten</th>
                      <th>Provinsi</th>
                      <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
              <script>
                  $(document).ready(function(){
                      $('#tbl_kecamatan').DataTable({
                          processing: true,
                          serverSide: true,
                          "language": {                
                                  "infoFiltered": ""
                              },
                          order: [],
                          ajax: {
                              url: "<?= base_url('dealer/cdb_d/fetch_kecamatan') ?>",
                              dataSrc: "data",
                              data: function ( d ) {
                                    return d;
                                },
                              type: "POST"
                          },
                          "columnDefs":[  
                      // { "targets":[4],"orderable":false},
                      { "targets":[2],"className":'text-center'}, 
                      // { "targets":[4], "searchable": false } 
                 ]
                      });
                  });
              </script>
      </div>
    </div>
  </div>
</div>
<script>
var form_ = new Vue({
      el: '#form_',
      data: { 
        seen: false,
        kk:{
          nik:'',
          nama_lengkap:'',
          id_jk:'',
          jk:'',
          tempat_lahir:'',
          tgl_lahir:'',
          id_agama:'',
          agama:'',
          id_pendidikan:'',
          pendidikan:'',
          id_pekerjaan:'',
          pekerjaan:'',
          id_status_pernikahan:'',
          status_pernikahan:'',
          id_hub_keluarga:'',
          hub_keluarga:'',
          jenis_wn:'',
          wn:'',
          pekerjaan_lain:''
        },
        kk_ :<?php 
                if(date('Y-m-d')>'2020-09-06'){
                  $cdb_kk = $this->db->query("SELECT nik,nama_lengkap,jk AS id_jk,tempat_lahir,tgl_lahir,tr_cdb_kk.id_agama,tr_cdb_kk.id_pendidikan,tr_cdb_kk.id_pekerjaan,tr_cdb_kk.id_status_pernikahan,tr_cdb_kk.id_hub_keluarga,jenis_wn,CASE WHEN jenis_wn=1 THEN 'WNI' ELSE 'WNA' END AS wn, CASE WHEN jk=1 THEN 'Laki-laki' ELSE 'Perempuan' END AS jk,agama,pendidikan,(SELECT pekerjaan FROM ms_pekerjaan WHERE id_pekerjaan=tr_cdb_kk.id_pekerjaan LIMIT 1) AS pekerjaan, (SELECT status_pernikahan FROM ms_status_pernikahan WHERE id_status_pernikahan=tr_cdb_kk.id_status_pernikahan LIMIT 1) AS status_pernikahan,(SELECT hub_keluarga FROM ms_hub_keluarga WHERE id_hub_keluarga=tr_cdb_kk.id_hub_keluarga LIMIT 1) AS hub_keluarga,tr_cdb_kk.pekerjaan_lain
                    FROM tr_cdb_kk 
                    JOIN ms_agama ON tr_cdb_kk.id_agama=ms_agama.id_agama
                    JOIN ms_pendidikan ON tr_cdb_kk.id_pendidikan=ms_pendidikan.id_pendidikan
                    WHERE no_spk='$row->no_spk'")->result();
                }else{
                  $cdb_kk = $this->db->query("SELECT nik,nama_lengkap,jk AS id_jk,tempat_lahir,tgl_lahir,tr_cdb_kk.id_agama,tr_cdb_kk.id_pendidikan,tr_cdb_kk.id_pekerjaan,tr_cdb_kk.id_status_pernikahan,tr_cdb_kk.id_hub_keluarga,jenis_wn,CASE WHEN jenis_wn=1 THEN 'WNI' ELSE 'WNA' END AS wn, CASE WHEN jk=1 THEN 'Laki-laki' ELSE 'Perempuan' END AS jk,agama,pendidikan,(SELECT pekerjaan FROM ms_pekerjaan_kk WHERE id_pekerjaan=tr_cdb_kk.id_pekerjaan LIMIT 1) AS pekerjaan, (SELECT status_pernikahan FROM ms_status_pernikahan WHERE id_status_pernikahan=tr_cdb_kk.id_status_pernikahan LIMIT 1) AS status_pernikahan,(SELECT hub_keluarga FROM ms_hub_keluarga WHERE id_hub_keluarga=tr_cdb_kk.id_hub_keluarga LIMIT 1) AS hub_keluarga,tr_cdb_kk.pekerjaan_lain
                  FROM tr_cdb_kk 
                  JOIN ms_agama ON tr_cdb_kk.id_agama=ms_agama.id_agama
                  JOIN ms_pendidikan ON tr_cdb_kk.id_pendidikan=ms_pendidikan.id_pendidikan
                  WHERE no_spk='$row->no_spk'")->result();
                } echo json_encode($cdb_kk); ?>
      },
      methods: {
        setJK:function (el) {
          var text = el.options[el.selectedIndex].text;
          this.kk.jk = text;
        },
        
        // getWarna: function() {
        //   var element   = $('#id_tipe_kendaraan').find('option:selected'); 
        //   var id_tipe_kendaraan = $('#id_tipe_kendaraan').val();
        //   if (id_tipe_kendaraan=='' || id_tipe_kendaraan==null) {
        //     $('#id_warna').html('');
        //     return false;
        //   }
        //   var warnas    = JSON.parse(element.attr("data-warna")); 
        //   var tipe_unit = element.attr("data-tipe_unit");
        //   form_.detail.tipe_unit = tipe_unit; 
        //   form_.detail.id_tipe_kendaraan = $('#id_tipe_kendaraan').val(); 
        //   $('#id_warna').html('');
        //     if (warnas.length>0) {
        //       $('#id_warna').append($('<option>').text('--choose--').attr('value', ''));
        //     }
        //   $.each(warnas, function(i, value) {
        //     $('#id_warna').append($('<option>').text(warnas[i].id_warna+' | '+warnas[i].warna).attr({'value':warnas[i].id_warna,'warna':warnas[i].warna}));
        //   });
        // },
        // getDetail: function() {
        //   var element           = $('#id_warna').find('option:selected'); 
        //   var warna             = element.attr("warna");
        //   form_.detail.warna    = warna; 
        //   form_.detail.id_warna = $('#id_warna').val(); 
        //   values = {id_tipe_kendaraan:$('#id_tipe_kendaraan').val(),
        //             id_warna:$('#id_warna').val()
        //            }
        //   $.ajax({
        //     url:"<?php echo site_url('dealer/po_dealer_new/getDetail');?>",
        //     type:"POST",
        //     data:values,
        //     cache:false,
        //     dataType:'JSON',
        //     success:function(response){
        //       form_.detail.current_stock = response.current_stock;
        //       form_.detail.monthly_sale  = response.monthly_sale;
        //       form_.detail.po_t1_last    = response.po_t1_last;
        //       form_.detail.po_t2_last    = response.po_t2_last;
        //       form_.detail.qty_indent    = response.qty_indent;
        //       form_.detail.harga         = response.harga;
        //       // console.log(form_.detail)
        //     }
        //   });
        // },
        clearDetail: function(){
          $('#id_tipe_kendaraan').val('').trigger('change');
          // $('#id_warna').html('');
             this.kk={
          nik:'',
          nama_lengkap:'',
          id_jk:'',
          jk:'',
          tempat_lahir:'',
          tgl_lahir:'',
          id_agama:'',
          agama:'',
          id_pendidikan:'',
          pendidikan:'',
          id_pekerjaan:'',
          pekerjaan:'',
          id_status_pernikahan:'',
          status_pernikahan:'',
          id_hub_keluarga:'',
          hub_keluarga:'',
          jenis_wn:'',
          wn:'',
          pekerjaan_lain:''
        }
        },
        // showModalItem : function() {
        //   // $('#tbl_part').DataTable().ajax.reload();
        //   $('.modalItem').modal('show');
        // },
        addDetails : function(){
          if (this.kk.nik=='' ||
              this.kk.nama_lengkap==''||
              this.kk.id_jk==''||
              this.kk.tempat_lahir==''||
              this.kk.tgl_lahir==''||
              this.kk.id_agama==''||
              this.kk.id_pendidikan==''||
              this.kk.id_pekerjaan==''||
              this.kk.id_status_pernikahan==''||
              this.kk.id_hub_keluarga==''||
              this.kk.jenis_wn==''
              ) 
          {
              alert('Isi data dengan lengkap !');
              return false;
          }else{
            if(this.kk.id_pekerjaan==89){
              if(this.kk.pekerjaan_lain==''){
                alert('Isi data dengan lengkap !');
                return false;
              }
            }else{
              this.kk.pekerjaan_lain = '';
            }
          }

          this.kk.jk = $('#kk_jk option:selected').text();
          this.kk.agama = $('#kk_agama option:selected').text();
          this.kk.pendidikan = $('#kk_pendidikan option:selected').text();
          this.kk.pekerjaan = $('#kk_pekerjaan option:selected').text();
          this.kk.status_pernikahan = $('#kk_status_pernikahan option:selected').text();
          this.kk.hub_keluarga = $('#kk_hub_keluarga option:selected').text();
          this.kk.wn = $('#kk_wn option:selected').text();
          this.kk_.push(this.kk);
          this.clearDetail();
        },
  
        delKK_: function(index){
            this.kk_.splice(index, 1);
        }
      },
      watch:{
        detail:function () {
          // alert('dd');
        }
      },
      computed: {
        // totDetail:function(detail) {
        //   po_fix     = detail.po_fix==''?0:detail.po_fix;
        //   qty_indent = detail.qty_indent==''?0:detail.qty_indent;
        //   total      = detail.harga * (parseInt(po_fix)+parseInt(qty_indent));
        //   ppn = total *(10/100);
        //   this.detail.total_harga = total+ppn;
        //   return total;
        // },
      },
  });

  function pekerjaan_lain2(){
    var pekerjaan = $('#kk_pekerjaan').val();
    if(pekerjaan == '89'){
      $('#sebutkan_pekerjaan').show();
    }else{
      $('#sebutkan_pekerjaan').hide(); 
    }
  }

  function setTglLahirKK(el) {
    form_.kk.tgl_lahir = $(el).val();
    // console.log(form_.kk)
    var today = new Date();
    var birthDate = new Date($(el).val());
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
      age--;
    }
    // if (age < 17) {
    //   alert('Usia Kurang Dari 17 Tahun')
    //   $('.tgl_lahir_kk').val('');
    // }
  }

  $(document).ready(function(){
  // $('#aktivitas_penjualan').val('<?= $row->aktivitas_penjualan ?>');
  getKecInstansi(id_kecamatan='<?= $row->id_kecamatan_instansi ?>');
})
  function getKecInstansi(id_kecamatan=null) {
    values = {id_kecamatan:id_kecamatan}
    if (id_kecamatan!=null) { 
      $.ajax({
        beforeSend: function() {},
        url:'<?= base_url('dealer/cdb_d/getKecamatanInstansi') ?>',
        type:"POST",
        data: values,
        cache:false,
        dataType:'JSON',
        success:function(response){
         //pilihKecamatan(response); gak terpakai
        },
        error:function(){
          alert("failure");
        },
        statusCode: {
          500: function() { 
            alert('fail');
          }
        }
      });
    }else{
      $('.modalKecamatan').modal('show');
    }
  }
  function pilihKecamatan(kec) {
    $('#id_kecamatan_instansi').val(kec.id_kecamatan);
    $('#kecamatan_instansi').val(kec.kecamatan);
    $('#kabupaten_instansi').val(kec.kabupaten);
    $('#provinsi_instansi').val(kec.provinsi);
  }
  $('#submitBtn').click(function(){
  $('#form_').validate({
      rules: {
          'checkbox': {
              required: true
          }
      },
      highlight: function (input) {
          $(input).parents('.form-group').addClass('has-error');
      },
      unhighlight: function (input) {
          $(input).parents('.form-group').removeClass('has-error');
      }
  })
  if ($('#form_').valid()) // check if form is valid
  {
    if (form_.kk_.length==0) {
      alert('Silahkan isi data Kartu Keluarga terlebih dahulu !');
      return false;
    }
    $('#form_').submit();
  }else{
    alert('Silahkan isi field required !')
  }
})  
</script>
    <?php
    }elseif($set=="view"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/cdb_d/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
          <a href="dealer/cdb_d/gc">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"view"); ?> class="btn btn-warning btn-flat margin"><i class="fa fa-users"></i> Group Customer</button>
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
        <table id="datatable" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>No SPK</th>  
              <th>Nama Customer</th>              
              <th>Alamat</th>              
              <th>No HP</th>
              <th>No KTP</th>  
              <th>Action</th>            
            </tr>
          </thead>
          <tbody>            
          
          </tbody>
        </table>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>


    <script type="text/javascript">
      $(document).ready(function(e){
        $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
        {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        };

        var base_url = "<?php echo base_url() ?>"; // You can use full url here but I prefer like this
        $('#datatable').DataTable({
           "pageLength" : 10,
           "serverSide": true,
           "ordering": true, // Set true agar bisa di sorting
            "processing": true,
            "language": {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
              searchPlaceholder: "Pencarian..."
            },

           "order": [[1, "desc" ]],
           "rowCallback": function (row, data, iDisplayIndex) {
                var info = this.fnPagingInfo();
                var page = info.iPage;
                var length = info.iLength;
                var index = page * length + (iDisplayIndex + 1);
                $('td:eq(0)', row).html(index);
            },
           "ajax":{
                    url :  base_url+'dealer/cdb_d/getData',
                    type : 'POST'
                  },
        }); // End of DataTable


      }); 

    </script>

      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php 
    }elseif($set=="insert_gc"){
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/cdb_d/gc">
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
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="dealer/cdb_d/save_gc" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <button class="btn btn-block btn-primary btn-flat" disabled> CDB </button> <br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama NPWP *</label>
                  <div class="col-sm-4">                    
                    <input type="hidden" id="no_spk_gc" name="no_spk_gc">
                    <input type="text" onchange="cek_tanya3()" readonly class="form-control" name="nama_npwp" onpaste="return false;" onkeypress="return false;" id="nama_npwp" placeholder="Nama NPWP" required>                                                    
                  </div>
                  <div class="col-sm-4">                                        
                    <a class="btn btn-primary btn-flat btn-sm" data-toggle="modal" data-target="#Npwpmodal" type="button"><i class="fa fa-search"></i> Browse</a>
                  </div>  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No NPWP *</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control" readonly id="no_npwp" placeholder="No NPWP" name="no_npwp" required>                                                            
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis GC</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" placeholder="Jenis GC" name="jenis_gc" id="jenis_gc">
                  </div>
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telp Perusahaan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly id="no_telp" class="form-control" placeholder="No Telp Perusahaan" name="no_telp">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Berdiri Perusahaan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly id="tanggal4" class="form-control" placeholder="Tgl Berdiri Perusahaan" name="tgl_berdiri">                    
                  </div>                
                </div>
                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Domisili *</label>
                  <div class="col-sm-4">
                    <input type="hidden" readonly name="id_kelurahan" id="id_kelurahan">                      
                    <input required type="text" onpaste="return false" onkeypress="return nihil(event)"  name="kelurahan" data-toggle="modal" placeholder="Kelurahan Domisili" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()">                                          
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                    <input type="text" class="form-control" readonly id="kecamatan" placeholder="Kecamatan Domisili"  name="kecamatan" required>                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kabupaten" id="id_kabupaten">
                    <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten Domisili" id="kabupaten" name="kabupaten" required>                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_provinsi" id="id_provinsi">
                    <input type="text" class="form-control" readonly placeholder="Provinsi Domisili" id="provinsi" name="provinsi" required>                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Domisili *</label>
                  <div class="col-sm-10">
                    <input type="text" readonly class="form-control" maxlength="100" placeholder="Alamat Domisili"  name="alamat" id="alamat" required>                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kodepos *</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" placeholder="Kodepos" id="kodepos" name="kodepos" required>                                        
                  </div>
                </div>
                <button class="btn btn-block btn-warning btn-flat" disabled> Data Pendukung </button> <br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-8 control-label">Bersediakan dikirimkan informasi terbaru dari program Honda?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="sedia_hub" required>
                      <option value="">- choose -</option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Facebook</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Facebook" maxlength="43" name="facebook">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Twitter</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Twitter" maxlength="43" name="twitter">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Instagram</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Instagram" maxlength="43" name="instagram">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Youtube</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Youtube" maxlength="43" name="youtube">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Ref ID</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Ref ID" maxlength="43" name="refferal_id">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">RO BD ID</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="RO BD ID" maxlength="43" name="robd_id">                    
                  </div>
                </div>
                
                
              </div>
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save?')" name="save" value="save" class="btn btn-success btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <?php 
    }elseif($set=='edit_gc'){
      $row = $dt_cdb->row();
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/cdb_d/gc">
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
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="dealer/cdb_d/update_gc" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <button class="btn btn-block btn-primary btn-flat" disabled> CDB </button> <br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama NPWP *</label>
                  <div class="col-sm-4">                    
                    <input value="<?php echo $row->id_cdb_gc ?>" type="hidden" id="id_cdb_gc" name="id_cdb_gc">
                    <input value="<?php echo $row->nama_npwp ?>" type="text" onchange="cek_tanya3()" readonly class="form-control" name="nama_npwp" onpaste="return false;" onkeypress="return false;" id="nama_npwp" placeholder="Nama NPWP" required>                                                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No NPWP *</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control" value="<?php echo $row->no_npwp ?>" readonly id="no_npwp" placeholder="No NPWP" name="no_npwp" required>                                                            
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis GC</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" value="<?php echo $row->jenis_gc ?>" placeholder="Jenis GC" name="jenis_gc" id="jenis_gc">
                  </div>
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telp Perusahaan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly id="no_telp" class="form-control" value="<?php echo $row->no_telp ?>" placeholder="No Telp Perusahaan" name="no_telp">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Berdiri Perusahaan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly id="tanggal4" class="form-control" value="<?php echo $row->tgl_berdiri ?>" placeholder="Tgl Berdiri Perusahaan" name="tgl_berdiri">                    
                  </div>                
                </div>
                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Domisili *</label>
                  <div class="col-sm-4">
                    <?php 
                    $id_kelurahan = $row->id_kelurahan; 
                    $dt_kel       = $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$id_kelurahan'")->row();
                    $kelurahan    = $dt_kel->kelurahan;
                    $kode_pos     = $dt_kel->kode_pos;
                    $id_kecamatan = $dt_kel->id_kecamatan;
                    $dt_kec       = $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan'")->row();
                    $kecamatan    = $dt_kec->kecamatan;
                    $id_kabupaten = $dt_kec->id_kabupaten;
                    $dt_kab       = $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten'")->row();
                    $kabupaten    = $dt_kab->kabupaten;
                    $id_provinsi  = $dt_kab->id_provinsi;
                    $dt_pro       = $this->db->query("SELECT * FROM ms_provinsi WHERE id_provinsi = '$id_provinsi'")->row();
                    $provinsi     = $dt_pro->provinsi;
                                                    
                    ?>
                    <input type="hidden" readonly name="id_kelurahan" id="id_kelurahan" value="<?php echo $row->id_kelurahan ?>">                      
                    <input required type="text" onpaste="return false" readonly onkeypress="return nihil(event)" value="<?php echo $kelurahan ?>" name="kelurahan" data-toggle="modal" placeholder="Kelurahan Domisili" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()">                                          
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kecamatan" id="id_kecamatan" value="<?php echo $id_kecamatan ?>">
                    <input type="text" class="form-control" readonly id="kecamatan" placeholder="Kecamatan Domisili" value="<?php echo $kecamatan ?>" name="kecamatan" required>                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kabupaten" id="id_kabupaten" value="<?php echo $id_kabupaten ?>">
                    <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten Domisili" id="kabupaten" name="kabupaten" value="<?php echo $kabupaten ?>" required>                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_provinsi" id="id_provinsi" value="<?php echo $id_provinsi ?>">
                    <input type="text" class="form-control" readonly placeholder="Provinsi Domisili" id="provinsi" name="provinsi" value="<?php echo $provinsi ?>" required>                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Domisili *</label>
                  <div class="col-sm-10">
                    <input type="text" readonly class="form-control" maxlength="100" placeholder="Alamat Domisili" value="<?php echo $row->alamat ?>"  name="alamat" id="alamat" required>                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kodepos *</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" placeholder="Kodepos" id="kodepos" name="kodepos" value="<?php echo $row->kodepos ?>" required>                                        
                  </div>
                </div>
                <button class="btn btn-block btn-warning btn-flat" disabled> Data Pendukung </button> <br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-8 control-label">Bersediakan dikirimkan informasi terbaru dari program Honda?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="sedia_hub" required>
                      <option value="<?php echo $row->sedia_hub ?>"><?php echo $row->sedia_hub ?></option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Facebook</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->facebook ?>" class="form-control" placeholder="Facebook" maxlength="43" name="facebook">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Twitter</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->twitter ?>" placeholder="Twitter" maxlength="43" name="twitter">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Instagram</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Instagram" value="<?php echo $row->instagram ?>" maxlength="43" name="instagram">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Youtube</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Youtube" maxlength="43" value="<?php echo $row->youtube ?>" name="youtube">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Ref ID</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Ref ID" maxlength="43" value="<?php echo $row->refferal_id ?>" name="refferal_id">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">RO BD ID</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="RO BD ID" maxlength="43" value="<?php echo $row->robd_id ?>" name="robd_id">                    
                  </div>
                </div>
                
                
              </div>
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to update?')" name="save" value="save" class="btn btn-success btn-flat"><i class="fa fa-save"></i> Update All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <?php 
    }elseif($set=="view_gc"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/cdb_d/add_gc">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
          <a href="dealer/cdb_d">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"view"); ?> class="btn btn-warning btn-flat margin"><i class="fa fa-user"></i> Individu</button>
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
        <table id="datatable" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>No SPK</th>              
              <th>Nama NPWP</th>              
              <th>No NPWP</th>
              <th>Alamat</th>  
              <th>Action</th>            
            </tr>
          </thead>
          <tbody>            
          
          </tbody>
        </table>


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
      <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>


      <script type="text/javascript">
        $(document).ready(function(e){
          $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
          {
              return {
                  "iStart": oSettings._iDisplayStart,
                  "iEnd": oSettings.fnDisplayEnd(),
                  "iLength": oSettings._iDisplayLength,
                  "iTotal": oSettings.fnRecordsTotal(),
                  "iFilteredTotal": oSettings.fnRecordsDisplay(),
                  "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                  "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
              };
          };

          var base_url = "<?php echo base_url() ?>"; // You can use full url here but I prefer like this
          $('#datatable').DataTable({
             "pageLength" : 10,
             "serverSide": true,
             "ordering": true, // Set true agar bisa di sorting
              "processing": true,
              "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                searchPlaceholder: "Pencarian..."
              },

             "order": [[1, "desc" ]],
             "rowCallback": function (row, data, iDisplayIndex) {
                  var info = this.fnPagingInfo();
                  var page = info.iPage;
                  var length = info.iLength;
                  var index = page * length + (iDisplayIndex + 1);
                  $('td:eq(0)', row).html(index);
              },
             "ajax":{
                      url :  base_url+'dealer/cdb_d/getDataGc',
                      type : 'POST'
                    },
          }); // End of DataTable


        }); 

      </script>

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
              <th>No SPK</th>
              <th>Tgl SPK</th>
              <th>ID Customer</th>              
              <th>Nama Customer</th>    
              <th width="10%">Aksi</th>                                     
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          foreach ($dt_spk->result() as $ve2) {
            echo "
            <tr>"; ?>
              <?php echo "
              <td>$ve2->no_spk</td>
              <td>$ve2->tgl_spk</td>
              <td>$ve2->id_customer</td>
              <td>$ve2->nama_konsumen</td>";
              ?>      
              <td class="center">
                <button title="Choose" onClick="Chooseitem('<?php echo $ve2->no_spk; ?>')" type="button" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
              </td>                   
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
<div class="modal fade" id="Npwpmodal">      
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search and Filter Group Customer
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example3" class="table table-bordered table-hover">        
          <thead>
            <tr>
              <th width="10%"></th>              
              <th>No SPK</th>
              <th>No NPWP</th>
              <th>Nama NPWP</th>                                    
              <th>Nama Penanggung Jawab</th>                                                             
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1;
          $id_dealer = $this->m_admin->cari_dealer(); 
          $dt_npwp = $this->db->query("SELECT * FROM tr_spk_gc LEFT JOIN ms_kelurahan ON tr_spk_gc.id_kelurahan=ms_kelurahan.id_kelurahan 
              WHERE tr_spk_gc.id_dealer = '$id_dealer'
              ORDER BY tr_spk_gc.no_spk_gc desc");   
          foreach ($dt_npwp->result() as $ve2) {
            echo "
            <tr>"; ?>
              <td class="center">
                <button title="Choose" onClick="Choosenpwp('<?php echo $ve2->no_spk_gc; ?>')" type="button" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
              </td>
              <?php echo "
              <td>$ve2->no_spk_gc</td>
              <td>$ve2->no_npwp</td>
              <td>$ve2->nama_npwp</td>
              <td>$ve2->nama_penanggung_jawab</td>";
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
    take_kec2();
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
      dataType:'JSON',
      success:function(msg){ 
          // data=msg.split("|");          
          $("#no_spk").val(msg.no_spk);                
          $("#nama_konsumen").val(msg.nama_konsumen);                
          $("#tgl_lahir").val(msg.tgl_lahir);                
          $("#tempat_lahir").val(msg.tempat_lahir);                
          $("#jenis_wn").val(msg.jenis_wn);                
          $("#no_kk").val(msg.no_kk);                
          $("#no_npwp").val(msg.npwp);                
          $("#id_kelurahan").val(msg.id_kelurahan);  
          $("#id_kelurahan2").val(msg.id_kelurahan2);          
          $("#alamat").val(msg.alamat);                
          $("#alamat2").val(msg.alamat2);                
          $("#kodepos").val(msg.kodepos);                
          $("#kodepos2").val(msg.kodepos2);                
          $("#denah_lokasi").val(msg.denah_lokasi);                                                           
          $("#tanya").val(msg.tanya);                                                           
          $("#status_rumah").val(msg.status_rumah);                                                           
          $("#lama_tinggal").val(msg.lama_tinggal);                                                           
          $("#pekerjaan").val(msg.pekerjaan);                                                              
          $("#sub_pekerjaan").val(msg.sub_pekerjaan);     

          if(msg.pekerjaan_lain==''){
            $("#lain").hide();
          }else{
            $("#lain").val(msg.pekerjaan_lain);
            $("#lain").show();
          }                                                        
          
          $("#nama_instansi").val(msg.nama_tempat_usaha);
          $("#alamat_instansi").val(msg.alamat_instansi);
          $("#kelurahan_instansi").val(msg.kelurahan_instansi);
          $("#kecamatan_instansi").val(msg.kecamatan_instansi);
          $("#kabupaten_instansi").val(msg.kabupaten_instansi);
          $("#provinsi_instansi").val(msg.provinsi_instansi);

          $("#lama_kerja").val(msg.lama_kerja);                          
          $("#jabatan").val(msg.jabatan);                          
          $("#total_penghasilan").val(msg.total_penghasilan);                          
          $("#pengeluaran_bulan").val(msg.pengeluaran_bulan);                          
          $("#no_hp").val(msg.no_hp);                                                                   
          $("#no_hp_2").val(msg.no_hp_2);                                                                   
          $("#status_hp").val(msg.status_hp);                                                                   
          $("#status_hp_2").val(msg.status_hp_2);                                                        
          $("#no_telp").val(msg.no_telp);                                                                   
          $("#email").val(msg.email);                                                                   
          $("#refferal_id").val(msg.refferal_id);
          $("#robd_id").val(msg.robd_id);
          $("#nama_ibu").val(msg.nama_ibu);
          $("#tgl_ibu").val(msg.tgl_ibu);
          $("#keterangan").val(msg.keterangan);
          $("#no_ktp").val(msg.no_ktp);
          $("#agama").val(msg.agama);
          $("#menggunakan").val(msg.menggunakan);
          $("#jenis_sebelumnya").val(msg.jenis_sebelumnya);
          $("#merk_sebelumnya").val(msg.merk_sebelumnya);
          $("#total_penghasilan").val(msg.penghasilan);
          $("#aktivitas_penjualan").val(msg.sumber_prospek);
          $("#aktivitas_penjualan_value").val(msg.sumber_prospek_value);

          /* SET KK */
          <?php if ($set == 'insert') { ?>
              form_.kk.nik = msg.no_ktp;
              form_.kk.nama_lengkap = msg.nama_konsumen;
              form_.kk.id_jk = msg.jenis_kelamin_kk;
              form_.kk.tempat_lahir = msg.tempat_lahir;
              form_.kk.tgl_lahir = msg.tgl_lahir;
              form_.kk.id_agama = msg.agama;
              form_.kk.id_pekerjaan = msg.id_pekerjaan;
              pekerjaan_lain2();
              form_.kk.jenis_wn = msg.jenis_wn_kk;
          <?php } ?>
          


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
  function Choosenpwp(no_spk_gc){
  document.getElementById("no_spk_gc").value = no_spk_gc; 
  cek_spk();
  $("#Npwpmodal").modal("hide");
}
function cek_spk(){
  var no_spk_gc = $("#no_spk_gc").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/cdb_d/cek_spk')?>",
      type:"POST",
      data:"no_spk_gc="+no_spk_gc,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");
          if(data[0]=="ok"){          
            $("#nama_npwp").val(data[1]);                          
            $("#no_npwp").val(data[2]);                            
            $("#alamat").val(data[3]);                            
            $("#id_kelurahan").val(data[4]);
            $("#jenis_gc").val(data[5]);                            
            $("#no_telp").val(data[6]);                            
            $("#tanggal4").val(data[7]);                            
            $("#nama_penanggung_jawab").val(data[8]);                            
            $("#email").val(data[9]);                            
            $("#no_hp").val(data[10]);                            
            $("#status_hp").val(data[11]);                                        
            $("#kodepos").val(data[12]);                                                    
            
            take_kec();                                 
          }else{
            alert(data[0]);
          }
      } 
  })
}
</script>