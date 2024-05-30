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
  <body onload="takes()">
<?php }else{ ?>
  <body onload="auto()">
<?php } ?>
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
          <a href="dealer/spk">
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
            <form class="form-horizontal" action="dealer/spk/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <button class="btn btn-block btn-primary btn-flat" disabled> SPK </button> <br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No SPK *</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" id="id_spk" readonly placeholder="No SPK" name="no_spk" required>                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" id="tanggal" readonly placeholder="Tanggal" value="<?php echo date("Y-m-d") ?>" name="tgl_spk" required>                    
                  </div>
                </div>
                <button class="btn btn-block btn-success btn-flat" disabled> DATA KONSUMEN </button> <br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Customer</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="tipe_customer" id="tipe_customer" onchange="take_harga()" required>
                      <option value="">- choose -</option>
                      <option value="Customer Umum">Customer Umum</option>
                      <option value="Instansi">Instansi (Plat merah)</option>
                      <option value="Perusahaan">Perusahaan dg TOP</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" name="id_customer" readonly id="id_customer" placeholder="ID Customer" required>                                                    
                  </div>
                  <div class="col-sm-4">                                        
                    <a class="btn btn-primary btn-flat btn-sm"  data-toggle="modal" data-target="#Customermodal" type="button"><i class="fa fa-search"></i> Browse</a>
                  </div>  
                </div>

                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Type *</label>
                  <div class="col-sm-4">                    
                    <!-- <input type="text" class="form-control" name="id_tipe_kendaraan" readonly id="id_tipe_kendaraan" placeholder="Type">                                                     -->
                    <select class="form-control" name="id_tipe_kendaraan" id="id_tipe_kendaraan" onchange="take_harga()" required>
                      <?php 
                      foreach($dt_tipe->result() as $val) {
                        echo "
                        <option value='$val->id_tipe_kendaraan'>$val->id_tipe_kendaraan | $val->tipe_ahm</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <!-- <input type="text" class="form-control" name="id_warna" readonly id="id_warna" placeholder="Warna">                                                     -->
                    <select class="form-control select2" name="id_warna" id="id_warna" required>
                      <?php 
                      foreach($dt_warna->result() as $val) {
                        echo "
                        <option value='$val->id_warna'>$val->id_warna | $val->warna</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                    <?php /*
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun Rakitan</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="tahun_rakitan">
                      <option value="">- choose -</option>
                      <?php               
                      $rt = date("Y");        
                      for($a = $rt - 10;$a <= $rt + 10;$a++){
                        echo "
                        <option value='$a'>$a</option>;
                        ";
                      }
                      ?>
                    </select>                                                    
                  </div>
                </div>  
                */?>                                                          
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Sesuai Identitas</label>
                  <div class="col-sm-10">
                    <input type="text"  class="form-control"  id="nama_konsumen" placeholder="Nama Sesuai Identitas" name="nama_konsumen" required>                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tempat/Tgl.Lahir</label>
                  <div class="col-sm-4">                    
                    <input type="text" id="tempat_lahir" class="form-control"  placeholder="Tempat Lahir" name="tempat_lahir" required>                                                            
                  </div>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" id="tanggal2"  placeholder="Tgl Lahir" name="tgl_lahir" required>                                                            
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembelian</label>
                  <div class="col-sm-4">
                    <select class="form-control"  name="jenis_pembelian" required>
                      <option value="">- choose -</option>
                      <option value="I">Reguler</option>
                      <option value="C">Kolektif</option>
                      <option value="J">Joint Promo</oEption>
                      <option value="G">Grup Perusahaan</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-laSSbel">Jenis Kewarganegaraan</label>
                  <div class="col-sm-4">
                    <select class="form-control" id="jenis_wn"  name="jenis_wn" required>
                      <option>WNA</option>
                      <option selected>WNI</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No KTP/KITAS</label>
                  <div class="col-sm-4">
                    <input type="text" id="no_ktp" class="form-control"  onkeypress="return number_only(event)" placeholder="No KTP/KITAS" name="no_ktp" required>                    
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">No KK</label>
                  <div class="col-sm-4">
                    <input type="text" id="no_kk" class="form-control"  onkeypress="return number_only(event)" placeholder="No KK" name="no_kk" required>                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Upload Foto KTP</label>
                  <div class="col-sm-4">
                    <input type="file" class="form-control" placeholder="Upload Foto" name="file_foto" required>                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Upload KK</label>
                  <div class="col-sm-4">
                    <input type="file" class="form-control" placeholder="Upload KK" name="file_kk" required>                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Domisili</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required name="id_kelurahan" id="id_kelurahan" onchange="take_kec()" required>
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_kelurahan->result() as $val) {
                        echo "
                        <option value='$val->id_kelurahan'>$val->kelurahan</option>;
                        ";
                      }
                      ?>
                    </select>
                    <!-- <input type="hidden" name="id_kelurahan" id="id_kelurahan">
                    <input type="text" class="form-control"  id="kelurahan" placeholder="Kelurahan Domisili"  name="kelurahan">                                         -->
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Domisili</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control"  placeholder="Alamat Domisili"  name="alamat" id="alamat" required>                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Apakah alamat domisili sama dengan alamat di KTP?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="tanya" id="tanya" onchange="cek_tanya()" required>
                      <option value="">- choose -</option>
                      <option>Ya</option>                      
                      <option>Tidak</option>
                    </select>
                  </div>                  
                </div>

                <span id="tampil_alamat">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Sesuai KTP</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" id="id_kelurahan2" name="id_kelurahan2" onchange="take_kec2()" required>
                        <option value="">- choose -</option>
                        <?php 
                        foreach ($dt_kelurahan->result() as $isi) {
                          echo "<option value='$isi->id_kelurahan'>$isi->kelurahan</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_kecamatan" id="id_kecamatan2">
                      <input type="text" class="form-control"  id="kecamatan2" placeholder="Kecamatan Sesuai KTP"  name="kecamatan2" required>                                        
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_kabupaten" id="id_kabupaten2">
                      <input type="text" class="form-control"  placeholder="Kota/Kabupaten Sesuai KTP" id="kabupaten2" name="kabupaten2" required>                                        
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_provinsi" id="id_provinsi2">
                      <input type="text" class="form-control"  placeholder="Provinsi Sesuai KTP" id="provinsi2" name="provinsi2" required>                                        
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kodepos Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" placeholder="Kodepos Sesuai KTP"  name="kodepos2" required>                                        
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat Sesuai KTP</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" placeholder="Alamat Sesuai KTP"  name="alamat2" required>                                        
                    </div>
                  </div>
                </span>


                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pendidikan</label>
                  <div class="col-sm-4">
                    <select class="form-control" id="pendidikan" name="pendidikan" required>
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_pendidikan->result() as $val) {
                        echo "
                        <option value='$val->pendidikan'>$val->pendidikan</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kelamin</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_kelamin" id="jenis_kelamin" required>
                      <option value="">- choose -</option>
                      <option>Pria</option>
                      <option>Wanita</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kodepos</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Kodepos" id="kodepos" name="kodepos" required>                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="status_nohp" id="status_nohp" required>
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_status_hp->result() as $val) {
                        echo "
                        <option value='$val->status_hp'>$val->status_hp</option>;
                        ";
                      }
                      ?>
                    </select>                                                     
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Bersedia dikirimi informasi terbaru program honda</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="sedia_hub" id="sedia_hub" required>
                      <option value="">- choose -</option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>                                                     
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Merk Motor yg dimiliki sekarang</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="merk_sebelumnya" id="merk_sebelumnya" required>
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_merk_sebelumnya->result() as $val) {
                        echo "
                        <option value='$val->merk_sebelumnya'>$val->merk_sebelumnya</option>;
                        ";
                      }
                      ?>
                    </select>                                                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Motor yg dimiliki sekarang</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="jenis_sebelumnya" id="jenis_sebelumnya" required>
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_jenis_sebelumnya->result() as $val) {
                        echo "
                        <option value='$val->jenis_sebelumnya'>$val->jenis_sebelumnya</option>;
                        ";
                      }
                      ?>
                    </select>                                                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Sepeda motor digunakan untuk</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="digunakan" id="digunakan" required>
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_digunakan->result() as $val) {
                        echo "
                        <option value='$val->digunakan'>$val->digunakan</option>;
                        ";
                      }
                      ?>
                    </select>                                                     
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Yang menggunakan sepeda motor</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="pemakai_motor" id="pemakai_motor" required>
                      <option value="">- choose -</option>
                      <option>Sendiri</option>
                      <option>Anak</option>
                      <option>Suami/Istri</option>
                    </select>                                                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Agama</label>
                  <div class="col-sm-4">
                    <select class="form-control"  name="agama" id="agama" required>
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_agama->result() as $val) {
                        echo "
                        <option value='$val->agama'>$val->agama</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control"  placeholder="No HP" id="no_hp"  name="no_hp" required>                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-4">
                    <input type="email" class="form-control"  placeholder="Email" id="email" name="email" required>                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No NPWP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No NPWP" id="no_npwp" name="no_npwp" required>                                        
                  </div>
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">Nama pada BPKB</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama pada BPKB" name="nama_bpkb">                                        
                  </div> -->
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Denah Lokasi</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Latitude,Longitude"  name="denah_lokasi" required>                                        
                  </div>                  
                </div>
                <div class="form-group">
                  <div class="col-sm-6">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" id="pekerjaan"  name="pekerjaan" required>
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_pekerjaan->result() as $val) {
                        echo "
                        <option value='$val->pekerjaan'>$val->pekerjaan</option>;
                        ";
                      }
                      ?>
                    </select>                                                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Grup Astra</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="grup_astra" required>
                      <option value="">- choose -</option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>                                                     
                  </div>
                 <label for="inputEmail3" class="col-sm-2 control-label">Jabatan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Jabatan" name="jabatan" required>                    
                  </div>                 
                </div>
                <div class="form-group">
                               
                  <label for="inputEmail3" class="col-sm-2 control-label">Pengeluaran Perbulan</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="penghasilan" required>
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_pengeluaran->result() as $val) {
                        echo "
                        <option value='$val->pengeluaran'>$val->pengeluaran</option>;
                        ";
                      }
                      ?>
                    </select>                                                    
                  </div>
                   <div class="col-sm-6"></div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Refferal ID</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Refferal ID"  name="refferal_id" required>                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">RO BD ID</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Ro BD ID"  name="robd_id" required>                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Facebook</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Facebook" name="facebook" required>                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Twitter</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Twitter"  name="twitter" required>                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Instagram</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Instagram" name="instagram" required>                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Youtube</label>
                  <div class="col-sm-4">                                       
                    <input type="text" class="form-control" placeholder="Youtube" name="youtube">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Hobi</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="hobi" required>
                      <option value="">- choose -</option>
                      <?php                       
                        foreach ($dt_hobi->result() as $isi) {
                          echo "<option value='$isi->hobi'>$isi->hobi</option>";
                        }
                       ?>                                                                       
                    </select>
                  </div>
                  <div class="col-sm-6"></div>
                  <?php /*
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Korespondensi</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="alamat_korespondensi " required>
                      <option value="">- choose -</option>
                      <option>Alamat Domisili</option>
                      <option>Alamat sesuai KTP</option>
                    </select>
                  </div>*/?>
                </div> 
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Keterangan" name="keterangan" required>                    
                  </div>
                </div>

                <button class="btn btn-block btn-warning btn-flat" disabled> SISTEM PEMBELIAN TUNAI </button> <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Harga Tunai</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Harga Tunai" readonly name="harga_tunai" id="harga_tunai" required>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">On/Off The Road</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="the_road" id="the_road" onchange="get_on()" required>
                      <option>Off The Road</option>
                      <option selected>On The Road</option>
                    </select>                                                     
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya Surat (BBN)</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Biaya BBN" id="biaya_bbn" readonly name="biaya_bbn" required>
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Program</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="program_umum" id="program_umum" required>
                      <?php 
                      $tgl = date("Y-m-d");
                      $cek = $this->db->query("SELECT * FROM tr_sales_program WHERE '$tgl' BETWEEN periode_awal AND periode_akhir AND (jenis_bayar = 'Cash' OR jenis_bayar = 'Cash & Kredit')");
                      foreach ($cek->result() as $isi) {
                        echo "<option value='$isi->id_sales_program'>$isi->id_program_md</option>";
                      }
                      ?>                      
                    </select>                                                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Program Tambahan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="program_khusus" placeholder="Program Tambahan" name="program_khusus" required>
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Bayar</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" id="total_bayar" readonly placeholder="Total Bayar" name="total_bayar" required>
                  </div>                  
                </div>

                <button class="btn btn-block btn-warning btn-flat" disabled> SISTEM PEMBELIAN KREDIT </button> <br>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Finance Company</label>
                  <div class="col-sm-4">                    
                    <select class="form-control select2" name="id_finance_company" required>
                      <option value="">- choose -</option>                      
                      <?php 
                      foreach ($dt_finance->result() as $isi) {
                        echo "<option value='$isi->id_finance_company'>$isi->finance_company</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Down Payment Gross</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Down Payment Gross" name="uang_muka">
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Voucher</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="voucher" id="voucher">
                      <option value="">- choose -</option>
                      <?php 
                      $tgl = date("Y-m-d");
                      $cek = $this->db->query("SELECT * FROM tr_sales_program WHERE '$tgl' BETWEEN periode_awal AND periode_akhir AND (jenis_bayar = 'Kredit' OR jenis_bayar = 'Cash & Kredit')");
                      foreach ($cek->result() as $isi) {
                        echo "<option value='$isi->id_sales_program'>$isi->id_program_md</option>";
                      }
                      ?>                      
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Angsuran/Bulan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Angsuran/Bulan" name="angsuran">
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Rumah</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="status_rumah">
                      <option value="">- choose -</option>
                      <option>Milik Sendiri</option>
                      <option>Sewa</option>
                    </select>
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Kawin</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="status_kawin">
                      <option value="">- choose -</option>
                      <option>Sudah Kawin</option>
                      <option>Belum Kawin</option>
                    </select>
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pasangan</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Nama Pasangan" name="nama_pasangan">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Tanggungan</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Jumlah Tanggungan" name="jumlah_tanggungan">
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Lama Tinggal</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Lama Tinggal" name="lama_tinggal">
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tenor</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Tenor" name="tenor">
                  </div>                                    
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Lama Kerja</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Lama Kerja" name="lama_kerja">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Penghasilan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Total Penghasilan" name="total_penghasilan">                    
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
      $row = $dt_spk->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/spk">
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
            <form class="form-horizontal" action="dealer/spk/update" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <button class="btn btn-block btn-primary btn-flat" disabled> SPK </button> <br>
                <div class="form-group">
                  <input type="hidden" name="id" value="<?php echo $row->no_spk ?>">
                  <label for="inputEmail3" class="col-sm-2 control-label">No SPK *</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" id="id_spk" value="<?php echo $row->no_spk ?>" readonly placeholder="No SPK" name="no_spk">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" id="tanggal" readonly placeholder="Tanggal" value="<?php echo $row->tgl_spk ?>" name="tgl_spk">                    
                  </div>
                </div>
                <button class="btn btn-block btn-success btn-flat" disabled> DATA KONSUMEN </button> <br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" value="<?php echo $row->id_customer ?>" name="id_customer" readonly id="id_customer" placeholder="ID Customer">                                                    
                  </div>
                  <div class="col-sm-4">                                        
                    <a class="btn btn-primary btn-flat btn-sm"  data-toggle="modal" data-target="#Customermodal" type="button"><i class="fa fa-search"></i> Browse</a>
                  </div>  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Type *</label>
                  <div class="col-sm-4">                    
                    <!-- <input type="text" class="form-control" name="id_tipe_kendaraan" value="<?php echo $row->id_tipe_kendaraan ?>" readonly id="id_tipe_kendaraan" placeholder="Type">                                                     -->
                    <select class="form-control select2" name="id_tipe_kendaraan" id="id_tipe_kendaraan" required onchange="take_harga()">
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
                      $dt_tipe = $this->m_admin->kondisi("ms_tipe_kendaraan","id_tipe_kendaraan != '$row->id_tipe_kendaraan'");                                                
                      foreach($dt_tipe->result() as $val) {
                        echo "
                        <option value='$val->id_tipe_kendaraan'>$val->id_tipe_kendaraan | $val->tipe_ahm</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <!-- <input type="text" class="form-control" value="<?php echo $row->id_warna ?>" name="id_warna" readonly id="id_warna" placeholder="Warna"> -->
                    <select class="form-control select2" name="id_warna" id="id_warna">
                      <option value="<?php echo $row->id_warna ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_warna","id_warna",$row->id_warna)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->id_warna | $dt_cust->warna";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_warna = $this->m_admin->kondisi("ms_warna","id_warna != '$row->id_warna'");                                                
                      foreach($dt_warna->result() as $val) {
                        echo "
                        <option value='$val->id_warna'>$val->id_warna | $val->warna</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun Rakitan</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="tahun_rakitan">
                      <option value="<?php echo $row->tahun_rakitan ?>"><?php echo $row->tahun_rakitan ?></option>
                      <?php               
                      $rt = date("Y");        
                      for($a = $rt - 10;$a <= $rt + 10;$a++){
                        echo "
                        <option value='$a'>$a</option>;
                        ";
                      }
                      ?>
                    </select>                                                    
                  </div>
                </div>                                                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-10">
                    <input type="text"  class="form-control" readonly id="nama_konsumen" placeholder="Nama Konsumen" name="nama_konsumen">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tempat/Tgl.Lahir</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" value="<?php echo $row->tempat_lahir ?>" placeholder="Tempat Lahir" name="tempat_lahir" required>                                                            
                  </div>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" value="<?php echo $row->tgl_lahir ?>" id="tanggal2" placeholder="Tgl Lahir" name="tgl_lahir" required>                                                            
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembelian</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_pembelian">
                      <option value="<?php echo $row->jenis_pembelian ?>">
                        <?php 
                        $tr = $row->jenis_pembelian;
                        if($tr == 'I'){
                          echo "Reguler";
                        }elseif($tr == 'C'){
                          echo "Kolektif";
                        }elseif($tr == 'J'){
                          echo "Joint Promo";
                        }elseif($tr == 'G'){
                          echo "Grup Perusahaan";
                        }
                        ?>
                      </option>
                      <option value="I">Reguler</option>
                      <option value="C">Kolektif</option>
                      <option value="J">Joint Promo</option>
                      <option value="G">Grup Perusahaan</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kewarganegaraan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_wn">
                      <option value="<?php echo $row->jenis_wn ?>"><?php echo $row->jenis_wn ?></option>
                      <option>WNA</option>
                      <option>WNI</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No KTP/KITAS</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" onkeypress="return number_only(event)" placeholder="No KTP/KITAS" name="no_ktp" value="<?php  echo $row->no_ktp ?>">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No KK</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" onkeypress="return number_only(event)" placeholder="No KK" name="no_kk" value="<?php  echo $row->no_kk ?>">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Upload Foto KTP</label>
                  <div class="col-sm-10">
                    <input type="file" class="form-control" placeholder="Upload Foto" name="file_foto">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Domisili</label>
                  <div class="col-sm-4">
                    <!-- <input type="hidden" name="id_kelurahan" id="id_kelurahan">
                    <input type="text" class="form-control" readonly id="kelurahan" placeholder="Kelurahan Domisili"  name="kelurahan">                                         -->
                    <select class="form-control select2" required name="id_kelurahan" id="id_kelurahan" onchange="take_kec()">
                      <option value="<?php echo $row->id_kelurahan   ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_kelurahan","id_kelurahan",$row->id_kelurahan)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->kelurahan;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_kelurahan = $this->m_admin->kondisi("ms_kelurahan","id_kelurahan != ".$row->id_kelurahan);                                                
                      foreach($dt_kelurahan->result() as $val) {
                        echo "
                        <option value='$val->id_kelurahan'>$val->kelurahan</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                    <input type="text" class="form-control" readonly id="kecamatan" placeholder="Kecamatan Domisili"  name="kecamatan">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kabupaten" id="id_kabupaten">
                    <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten Domisili" id="kabupaten" name="kabupaten">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Domisili</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_provinsi" id="id_provinsi">
                    <input type="text" class="form-control" readonly placeholder="Provinsi Domisili" id="provinsi" name="provinsi">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Domisili</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?php echo $row->alamat ?>" readonly placeholder="Alamat Domisili"  name="alamat" id="alamat">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Apakah alamat domisili sama dengan alamat di KTP?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="tanya" id="tanya" onchange="cek_tanya()">
                      <option value="<?php echo $row->alamat_sama ?>"><?php echo $row->alamat_sama ?></option>
                      <option>Ya</option>                      
                      <option>Tidak</option>
                    </select>
                  </div>                  
                </div>

                <span id="tampil_alamat">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Sesuai KTP</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" id="id_kelurahan2" name="id_kelurahan2" onchange="take_kec2()">
                        <option value="<?php echo $row->id_kelurahan2 ?>"><?php echo $row->id_kelurahan2 ?></option>
                        <?php 
                        foreach ($dt_kelurahan->result() as $isi) {
                          echo "<option value='$isi->id_kelurahan'>$isi->kelurahan</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_kecamatan" id="id_kecamatan2">
                      <input type="text" class="form-control" readonly id="kecamatan2" placeholder="Kecamatan Sesuai KTP"  name="kecamatan2">                                        
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_kabupaten" id="id_kabupaten2">
                      <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten Sesuai KTP" id="kabupaten2" name="kabupaten2">                                        
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_provinsi" id="id_provinsi2">
                      <input type="text" class="form-control" readonly placeholder="Provinsi Sesuai KTP" id="provinsi2" name="provinsi2">                                        
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Pos Sesuai KTP</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" placeholder="Kode Pos Sesuai KTP"  name="kodepos2">                                        
                    </div>
                  </div>                  
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat Sesuai KTP</label>
                    <div class="col-sm-10">
                      <input type="text" value="<?php echo $row->alamat2 ?>" class="form-control" placeholder="Alamat Sesuai KTP"  name="alamat2">                                        
                    </div>
                  </div>
                </span>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pendidikan</label>
                  <div class="col-sm-4">
                    <select class="form-control" id="pendidikan" name="pendidikan">
                      <option><?php echo $row->pendidikan ?></option>
                      <?php 
                      foreach($dt_pendidikan->result() as $val) {
                        echo "
                        <option value='$val->pendidikan'>$val->pendidikan</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kelamin</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                      <option><?php echo $row->jenis_kelamin ?></option>
                      <option>Pria</option>
                      <option>Wanita</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kodepos</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Kodepos" value="<?php echo $row->kodepos ?>" id="kodepos" name="kodepos">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="status_nohp" id="status_nohp">
                      <option><?php echo $row->status_nohp ?></option>
                      <?php 
                      foreach($dt_status_hp->result() as $val) {
                        echo "
                        <option value='$val->status_hp'>$val->status_hp</option>;
                        ";
                      }
                      ?>
                    </select>                                                     
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">bersedia dikirimi informasi terbaru program honda</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="sedia_hub">
                      <option><?php echo $row->sedia_hub ?></option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>                                                     
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Merk Motor yg dimiliki sekarang</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="merk_sebelumnya">
                      <option><?php echo $row->merk_sebelumnya ?></option>
                      <?php 
                      foreach($dt_merk_sebelumnya->result() as $val) {
                        echo "
                        <option value='$val->merk_sebelumnya'>$val->merk_sebelumnya</option>;
                        ";
                      }
                      ?>
                    </select>                                                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Motor yg dimiliki sekarang</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="jenis_sebelumnya">
                      <option><?php echo $row->jenis_sebelumnya ?></option>
                      <?php 
                      foreach($dt_jenis_sebelumnya->result() as $val) {
                        echo "
                        <option value='$val->jenis_sebelumnya'>$val->jenis_sebelumnya</option>;
                        ";
                      }
                      ?>
                    </select>                                                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Sepeda motor digunakan untuk</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="digunakan">
                      <option><?php echo $row->digunakan ?></option>
                      <?php 
                      foreach($dt_digunakan->result() as $val) {
                        echo "
                        <option value='$val->digunakan'>$val->digunakan</option>;
                        ";
                      }
                      ?>
                    </select>                                                     
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Yang menggunakan sepeda motor</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="pemakai_motor">
                      <option><?php echo $row->pemakai_motor ?></option>
                      <option>Sendiri</option>
                      <option>Anak</option>
                      <option>Suami/Istri</option>
                    </select>                                                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Agama</label>
                  <div class="col-sm-4">
                    <select class="form-control"  name="agama" id="agama">
                      <option><?php echo $row->agama ?></option>
                      <?php 
                      foreach($dt_agama->result() as $val) {
                        echo "
                        <option value='$val->agama'>$val->agama</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>


                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->no_hp ?>" class="form-control" placeholder="No HP"  name="no_hp">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-4">
                    <input type="email" value="<?php echo $row->email ?>" class="form-control" placeholder="Email"  name="email">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No NPWP</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->npwp ?>" id="no_npwp" class="form-control" placeholder="No NPWP"  name="no_npwp">                                        
                  </div>
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">Nama pada BPKB</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->nama_bpkb ?>" class="form-control" placeholder="Nama pada BPKB"  name="nama_bpkb">                                        
                  </div> -->
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Denah Lokasi</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?php echo $row->denah_lokasi ?>" placeholder="Latitude,Longitude"  name="denah_lokasi">                                        
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Customer</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="tipe_customer" id="tipe_customer" onchange="take_harga()">
                      <option value="<?php echo $row->tipe_customer ?>"><?php echo $row->tipe_customer ?></option>
                      <option>Customer Umum</option>
                      <option>Instansi (Plat merah)</option>
                      <option>Perusahaan dg TOP</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="pekerjaan">
                      <option value="<?php echo $row->pekerjaan ?>"><?php echo $row->pekerjaan ?></option>
                      <?php 
                      foreach($dt_pekerjaan->result() as $val) {
                        echo "
                        <option value='$val->pekerjaan'>$val->pekerjaan</option>;
                        ";
                      }
                      ?>
                    </select>                                                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Grup Astra</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="grup_astra">
                      <option value="<?php echo $row->grup_astra ?>"><?php echo $row->grup_astra ?></option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>                                                     
                  </div>
                 <label for="inputEmail3" class="col-sm-2 control-label">Jabatan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->jabatan ?>" placeholder="Jabatan" name="jabatan">                    
                  </div>                 
                </div>
                <div class="form-group">
                               
                  <label for="inputEmail3" class="col-sm-2 control-label">Pengeluaran Perbulan</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="penghasilan">
                      <option value="<?php echo $row->penghasilan ?>"><?php echo $row->penghasilan ?></option>
                      <?php 
                      foreach($dt_pengeluaran->result() as $val) {
                        echo "
                        <option value='$val->pengeluaran'>$val->pengeluaran</option>;
                        ";
                      }
                      ?>
                    </select>                                                    
                  </div>
                   <div class="col-sm-6"></div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Refferal ID</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->refferal_id ?>" placeholder="Refferal ID"  name="refferal_id">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">RO BD ID</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->robd_id ?>" placeholder="Ro BD ID"  name="robd_id">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Facebook</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->facebook ?>" placeholder="Facebook" name="facebook">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Twitter</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->twitter ?>" placeholder="Twitter"  name="twitter">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Instagram</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->instagram ?>" placeholder="Instagram" name="instagram">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Youtube</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->youtube ?>" placeholder="Youtube" name="youtube">                                        
                  </div>
                </div>
                <div class="form-group">
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Korespondensi</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="alamat_korespondensi">
                      <option><?php echo $row->alamat_korespondensi ?></option>
                      <option>Alamat Domisili</option>
                      <option>Alamat sesuai KTP</option>
                    </select>
                  </div>
                </div>
               
                <button class="btn btn-block btn-warning btn-flat" disabled> SISTEM PEMBELIAN TUNAI </button> <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Harga Tunai</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Harga Tunai" value="<?php echo $row->harga_tunai ?>" name="harga_tunai" id="harga_tunai" readonly>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">On/Off The Road</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="the_road" id="the_road" onchange="get_on()">
                      <option value="<?php echo $row->the_road ?>"><?php echo $row->the_road ?></option>
                      <option>Off The Road</option>
                      <option>On The Road</option>
                    </select>                                                     
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya Surat (BBN)</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->biaya_bbn ?>" placeholder="Biaya BBN" name="biaya_bbn" id="biaya_bbn" readonly>
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Program Umum</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="program_umum" id="program_umum">
                      <option value="<?php echo $row->program_umum ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("tr_sales_program","id_sales_program",$row->program_umum)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->id_program_md;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php    
                      $tgl = date("Y-m-d");                   
                      $cek = $this->db->query("SELECT * FROM tr_sales_program WHERE '$tgl' BETWEEN periode_awal AND periode_akhir AND periode_akhir AND (jenis_bayar = 'Cash' OR jenis_bayar = 'Cash & Kredit')");
                      foreach ($cek->result() as $isi) {
                        echo "<option value='$isi->id_sales_program'>$isi->id_program_md</option>";
                      }
                      ?>                      
                    </select>                                                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Program Khusus</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->program_khusus ?>" placeholder="Program Khusus" name="program_khusus">
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Bayar</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Total Bayar" id="total_bayar" readonly value="<?php echo $row->total_bayar ?>" name="total_bayar">
                  </div>                  
                </div>

                <button class="btn btn-block btn-warning btn-flat" disabled> SISTEM PEMBELIAN KREDIT </button> <br>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Finance Company</label>
                  <div class="col-sm-4">                    
                    <select class="form-control select2" name="id_finance_company">
                      <option value="<?php echo $row->id_finance_company ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_finance_company","id_finance_company",$row->id_finance_company)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->finance_company;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_finance = $this->m_admin->kondisi("ms_finance_company","id_finance_company != '$row->id_finance_company'");                                                
                      foreach ($dt_finance->result() as $isi) {
                        echo "<option value='$isi->id_finance_company'>$isi->finance_company</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Down Payment Gross</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Down Payment Gross" value="<?php echo $row->uang_muka ?>" name="uang_muka">
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Voucher</label>
                  <div class="col-sm-4">     
                    <select class="form-control" name="voucher">
                      <option value="<?php echo $row->voucher ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("tr_sales_program","id_sales_program",$row->voucher)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->id_program_md;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $tgl = date("Y-m-d");
                      $cek = $this->db->query("SELECT * FROM tr_sales_program WHERE '$tgl' BETWEEN periode_awal AND periode_akhir AND (jenis_bayar = 'Kredit' OR jenis_bayar = 'Cash & Kredit')");
                      foreach ($cek->result() as $isi) {
                        echo "<option value='$isi->id_sales_program'>$isi->id_program_md</option>";
                      }
                      ?>                      
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Angsuran/Bulan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Angsuran/Bulan" value="<?php echo $row->angsuran ?>" name="angsuran">
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Rumah</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="status_rumah">
                      <option value="<?php echo $row->status_rumah ?>"><?php echo $row->status_rumah ?></option>                      
                      <option>Milik Sendiri</option>
                      <option>Sewa</option>
                    </select>
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Kawin</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="status_kawin">
                      <option value="<?php echo $row->status_kawin ?>"><?php echo $row->status_kawin ?></option>
                      <option>Sudah Kawin</option>
                      <option>Belum Kawin</option>
                    </select>
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pasangan</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" value="<?php echo $row->nama_pasangan ?>" placeholder="Nama Pasangan" name="nama_pasangan">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Tanggungan</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" value="<?php echo $row->jumlah_tanggungan ?>" placeholder="Jumlah Tanggungan" name="jumlah_tanggungan">
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Lama Tinggal</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" value="<?php echo $row->lama_tinggal ?>" placeholder="Lama Tinggal" name="lama_tinggal">
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tenor</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" value="<?php echo $row->tenor ?>" placeholder="Tenor" name="tenor">
                  </div>                                    
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Lama Kerja</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->lama_kerja ?>" placeholder="Lama Kerja" name="lama_kerja">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Penghasilan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->total_penghasilan ?>" placeholder="Total Penghasilan" name="total_penghasilan">                    
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
          <a href="dealer/spk/add">
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
              <th>No SPK</th>              
              <th>Nama Konsumen</th>              
              <th>Alamat</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th>No KTP</th>              
              <th>Status</th>
              <th width="15%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_spk->result() as $row) {                 
            if($row->status_spk =='input'){
              $status = "<span class='label label-warning'>$row->status_spk</span>";              
              $tombol = "<a data-toggle='tooltip' title='Approve' class='btn btn-success btn-xs btn-flat' href='dealer/spk/approve?id=$row->no_spk'><i class='fa fa-check'></i> Approve</a>";
              $tombol2 = "<a data-toggle='tooltip' title='Reject' class='btn btn-danger btn-xs btn-flat' href='dealer/spk/reject?id=$row->no_spk'><i class='fa fa-close'></i> Reject</a>";
              $tombol3 = "<a data-toggle='tooltip' title='Edit' href='dealer/spk/edit?id=$row->no_spk'><button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>";
              $tombol4='';
            }elseif($row->status_spk =='rejected'){
              $status = "<span class='label label-danger'>$row->status_spk</span>";
              $tombol = "";$tombol2 = "";$tombol3 = "";$tombol4 = "";
            }elseif($row->status_spk =='approved'){
              $status = "<span class='label label-success'>$row->status_spk</span>";
              $tombol = "";$tombol2 = "";$tombol3 = "";$tombol4 = "";
            }

            $cek_s = $this->db->query("SELECT * FROM tr_hasil_survey WHERE no_spk = '$row->no_spk'");
            if($cek_s->num_rows() > 0){
              $rr = $cek_s->row();
              $status = $rr->status_approval;
              if($status=='rejected'){
                $tombol4 = "<a data-toggle='tooltip' title='Edit' href='dealer/spk/edit?id=$row->no_spk'><button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>";  
              }
            }
            
            $prospek = $this->m_admin->getByID("tr_prospek","id_customer",$row->id_customer);
            if($prospek->num_rows() > 0){
              $rt = $prospek->row();
              $nama = $rt->nama_konsumen;
            }else{
              $nama = "";
            }

            $tipe = $this->m_admin->getByID("ms_tipe_kendaraan","id_tipe_kendaraan",$row->id_tipe_kendaraan);
            if($tipe->num_rows() > 0){
              $rs = $tipe->row();
              $ahm = $rs->tipe_ahm;
            }else{
              $ahm = "";
            }

            $warna = $this->m_admin->getByID("ms_warna","id_warna",$row->id_warna);
            if($warna->num_rows() > 0){
              $rw = $warna->row();
              $war = $rw->warna;
            }else{
              $war = "";
            }
            echo "
            <tr>
              <td>$no</td>
              <td>$row->no_spk</td>
              <td>$nama</td>
              <td>$row->alamat</td>              
              <td>$ahm</td>
              <td>$war</td>            
              <td>$row->no_ktp</td>         
              <td>$status</td>         
              <td>                                
                <a data-toggle='tooltip' title='Cetak' href='dealer/spk/cetak?id=$row->no_spk'>
                  <button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak SPK</button>
                </a>"; 
                echo $tombol3;
                echo $tombol;
                echo $tombol2;
                echo $tombol4;
                ?>                              
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


<div class="modal fade" id="Customermodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search and Filter Customer
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="10%"></th>              
              <th>ID Customer</th>
              <th>Nama Customer</th>                                    
              <th>No HP</th>                                                             
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          foreach ($dt_customer->result() as $ve2) {
            echo "
            <tr>"; ?>
              <td class="center">
                <button title="Choose" onClick="Chooseitem('<?php echo $ve2->id_customer; ?>')" type="button" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
              </td>
              <?php echo "
              <td>$ve2->id_customer</td>
              <td>$ve2->nama_konsumen</td>
              <td>$ve2->no_hp</td>";
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
function auto(){
  var tgl_js = "1";
  $.ajax({
      url : "<?php echo site_url('dealer/spk/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_spk").val(data[0]);                
        //$("#id_customer").val(data[1]);           
        $("#tampil_alamat").hide();
      }        
  })
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

function Chooseitem(id_customer){
  document.getElementById("id_customer").value = id_customer; 
  cek_customer();
  $("#Customermodal").modal("hide");
}

function cek_customer(){
  var id_customer=$("#id_customer").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/spk/cek_customer')?>",
      type:"POST",
      data:"id_customer="+id_customer,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");
          if(data[0]=="ok"){          
            $("#nama_konsumen").val(data[1]);                
            //$("#id_kelurahan").val(data[2]);
            $("#id_kelurahan").select2().val(data[2]).trigger('change.select2');
            $("#alamat").val(data[3]);                            
            $("#tanggal2").val(data[4]);                            
            $("#jenis_pembelian").val(data[5]);                            
            $("#jenis_wn").val(data[6]);                            
            $("#no_ktp").val(data[7]);                            
            $("#no_kk").val(data[8]);                            
            $("#no_hp").val(data[9]);                            
            $("#email").val(data[10]);                            
            $("#pekerjaan").val(data[11]);                            
            $("#id_tipe_kendaraan").val(data[12]);  
            $("#id_warna").select2().val(data[13]).trigger('change.select2');     
            $("#tempat_lahir").val(data[14]);                            
            $("#no_ktp").val(data[15]);                            
            $("#no_npwp").val(data[16]);                                                    
            $("#pendidikan").val(data[17]);                                                    
            $("#jenis_kelamin").val(data[18]);                                                    
            $("#kodepos").val(data[19]);                                                    
            $("#status_nohp").val(data[20]);                                                    
            $("#sedia_hub").val(data[21]);                                                    
            $("#merk_sebelumnya").val(data[22]);                                                    
            $("#jenis_sebelumnya").val(data[23]);                                                    
            $("#digunakan").val(data[24]);                                                    
            $("#pemakai_motor").val(data[25]);                                                    
            $("#agama").val(data[26]);                                                    
            cek_tanya();
            take_kec();            
          }else{
            alert(data[0]);
          }
      } 
  })
}
function take_harga(){
  var tipe_customer=$("#tipe_customer").val();                       
  var id_tipe_kendaraan=$("#id_tipe_kendaraan").val();                       
  var id_warna=$("#id_warna").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/spk/cek_bbn')?>",
      type:"POST",
      data:"id_warna="+id_warna+"&id_tipe_kendaraan="+id_tipe_kendaraan+"&tipe_customer="+tipe_customer,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");          
          $("#biaya_bbn").val(data[0]);                                        
          $("#harga_tunai").val(data[1]);                
          get_total();
      }
  })
}
function get_total(){
  var biaya_bbn = $("#biaya_bbn").val();                       
  var harga_tunai = $("#harga_tunai").val();                       
  var program_umum = $("#program_umum").val();                       
  var program_khusus = $("#program_khusus").val();                       
  var total = parseInt(biaya_bbn) + parseInt(harga_tunai);
  $("#total_bayar").val(total);
}
function get_on(){
  var the_road = $("#the_road").val();
  var biaya_bbn = $("#biaya_bbn").val();                       
  var harga_tunai = $("#harga_tunai").val();                       
  var program_umum = $("#program_umum").val();                       
  var program_khusus = $("#program_khusus").val();                       
  $("#total_bayar").val(total);
  if(the_road == 'On The Road'){
    var total = parseInt(harga_tunai);
  }else{    
    var total = parseInt(biaya_bbn) + parseInt(harga_tunai);
  }
  $("#total_bayar").val(total);
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
function take_kec2(){
  var id_kelurahan = $("#id_kelurahan2").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/spk/take_kec')?>",
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
  take_kec();
  take_kec2();
  cek_customer();
  $("#tampil_alamat").hide();
}
</script>