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
<body onload="tampilkan()">
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
    if($set == 'add'){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/entry_permohonan">
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
        <div id="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="dealer/entry_permohonan/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">ID List Appointment</label>
                  <div class="col-sm-4">
                  </div> -->
                  <input type="hidden"  class="form-control" id="id_list_appointment" readonly placeholder="ID List Appointment" name="id_list_appointment">                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Customer</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="tipe_customer">
                      <option value="Individu">Individu</option>
                      <option value="Grup">Grup</option>
                    </select>                    
                    <input type="hidden" id="tgl" value="<?php echo date("Y-m-d") ?>" name="tgl">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Beli</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control" id="tanggal" placeholder="Tgl Beli" name="tgl_beli">                    
                  </div>
                </div>
                <?php /*
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembelian</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_pembelian">
                      <option value="">- choose -</option>
                      <?php                       
                      foreach ($dt_jenis->result() as $isi) {
                        echo "<option>$isi->jenis_pembelian</option>";
                      }
                      ?>

                    </select>
                  </div>                
                  
                </div> */ ?>

                <button class="btn btn-primary btn-block btn-flat" disabled>Detail Kendaraan</button>
                <br>                
                <span id="tampil_kendaraan"></span>                                                                                                                                              

                 
                
                <br>
                <button class="btn btn-primary btn-block btn-flat" disabled>Detail Data</button>
                <br>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">RO</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="ro">
                      <option value="">- choose -</option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pekerjaan">                      
                      <option value="">- choose -</option>
                      <?php                       
                      foreach ($dt_pekerjaan->result() as $isi) {
                        echo "<option value='$isi->id_pekerjaan'>$isi->pekerjaan</option>";
                      }
                      ?>
                    </select> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembelian</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_beli">
                      <option value="">- choose -</option>
                      <option value="I">Reguler</option>
                      <option value="C">Kolektif</option>
                      <option value="J">Joint Promo</option>
                      <option value="G">Grup Perusahaan</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kewarganegaraan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_wn">
                      <option value="">- choose -</option>
                      <option>WNA</option>
                      <option>WNI</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No KTP/KITAS</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" onkeypress="return number_only(event)" placeholder="No KTP/KITAS" name="id_ktp">                    
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">No KK</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" onkeypress="return number_only(event)" placeholder="No KK" name="no_kk">                    
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Pengeluaran 1 Bulan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pengeluaran_bulan">
                      <option value="">- choose -</option>
                      <?php                       
                      foreach ($dt_pengeluaran->result() as $isi) {
                        echo "<option value='$isi->id_pengeluaran_bulan'>$isi->pengeluaran</option>";
                      }
                      ?>
                    </select> 
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pemilik</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control" placeholder="Nama Pemilik" name="nama_pemilik">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Pendidikan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pendidikan">
                      <option value="">- choose -</option>
                      <?php                       
                      foreach ($dt_pendidikan->result() as $isi) {
                        echo "<option value='$isi->id_pendidikan'>$isi->pendidikan</option>";
                      }
                      ?>
                    </select> 
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kelamin</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_kelamin">
                      <option value="">- choose -</option>
                      <option value="laki-laki">Laki-laki</option>
                      <option value="perempuan">Perempuan</option>
                    </select> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control"  placeholder="No HP" name="no_hp">                    
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control"  id="tanggal2"  placeholder="Tgl Lahir" name="tgl_lahir">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control"  placeholder="No Telp" name="no_telp">                    
                  </div>
                </div>   
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                  <div class="col-sm-4">
                    <input type="hidden" readonly name="id_kelurahan" id="id_kelurahan">                      
                    <input type="text" readonly name="kelurahan" data-toggle="modal" placeholder="Kelurahan" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="cek_kecamatan()">                               
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status HP</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="status_hp">
                      <option value="">- choose -</option>
                      <?php                       
                      foreach ($dt_status_hp->result() as $isi) {
                        echo "<option value='$isi->id_status_hp'>$isi->status_hp</option>";
                      }
                      ?>
                    </select> 
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                  <div class="col-sm-4">
                    <input type="hidden" id="id_kecamatan" name="id_kecamatan">                    
                    <input type="text"  class="form-control"  id="kecamatan" readonly  placeholder="Kabupaten" name="kecamatan">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Apakah anda bersedia dikirimkan informasi terbaru dari Honda?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="bersedia_informasi">
                      <option value="">- choose -</option>                      
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select> 
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Kabupaten</label>
                  <div class="col-sm-4">
                    <input type="hidden" id="id_kabupaten" name="id_kabupaten">                    
                    <input type="text"  class="form-control" readonly id="kabupaten"  placeholder="Kabupaten" name="kabupaten">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Merk Motor yang dimiliki sebelumnya?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="merk_sebelumnya">
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                  <div class="col-sm-4">
                    <input type="hidden" id="id_provinsi" name="id_provinsi">                    
                    <input type="text"  class="form-control" readonly id="provinsi"  placeholder="Provinsi" name="provinsi">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Motor yang dimiliki sebelumnya?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_sebelumnya">
                      <option value="">- choose -</option>
                      <?php                       
                      foreach ($dt_jenis_sebelumnya->result() as $isi) {
                        echo "<option value='$isi->id_jenis_sebelumnya'>$isi->jenis_sebelumnya</option>";
                      }
                      ?>
                    </select> 
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Agama</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="id_agama">
                      <option value="">- choose -</option> 
                      <?php                       
                      foreach ($dt_agama->result() as $isi) {
                        echo "<option value='$isi->id_agama'>$isi->agama</option>";
                      }
                      ?>                             
                    </select> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Sepeda Motor digunakan untuk?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="digunakan_untuk">
                      <option value="">- choose -</option>  
                       <?php                       
                        foreach ($dt_digunakan->result() as $isi) {
                          echo "<option value='$isi->id_digunakan'>$isi->digunakan</option>";
                        }
                       ?>                                     
                    </select> 
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control"  id="id_penerimaan_unit"  placeholder="Email" name="email">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Yang menggunakan sepeda motor?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="menggunakan_motor">
                      <option value="">- choose -</option>
                      <option>Sendiri</option>
                      <option>Anak</option>
                      <option>Suami/Istri</option>                                           
                    </select> 
                  </div>
                </div>
                 <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat yg digunakan untuk korenspodensi</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="alamat_koresponden">
                      <option value="">- choose -</option>                                            
                      <option>Alamat sesuai KTP</option>
                      <option>Alamat tinggal saat ini</option> 
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Rumah</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="status_rumah">
                      <option value="">- choose -</option>
                      <option>Milik Sendiri</option>
                      <option>Sewa</option>                                            
                    </select> 
                  </div>
                </div>             
                

                <br>
                <button class="btn btn-primary btn-block btn-flat" disabled>Data Kartu Keluarga</button>
                <br>
                <span id="tampil_keluarga"></span>                          

                <br>
                <button class="btn btn-primary btn-block btn-flat" disabled>Data Dealer</button>
                <br>         

                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Penjualan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_penjualan">
                      <option value="">- choose -</option>                                            
                      <option>Cash</option>
                      <option>Kredit</option>
                    </select> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Akun Facebook</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control" placeholder="Akun Facebook" name="facebook">                    
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Sales Person</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="id_karyawan_dealer">
                      <option value="">- choose -</option>  
                       <?php                       
                        foreach ($dt_karyawan_dealer->result() as $isi) {
                          echo "<option value='$isi->id_karyawan_dealer'>$isi->id_karyawan_dealer | $isi->nama_lengkap</option>";
                        }
                       ?>                                                                  
                    </select> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Akun Instagram</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control"  id="instagram"  placeholder="Akun Instagram" name="instagram">                    
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Dokumen KTP</label>
                  <div class="col-sm-4">                    
                    <input type="checkbox" class="flat-red" name="dokumen_ktp" value="1" checked>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Akun Twitter</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control"  id="twitter"  placeholder="Akun Twitter" name="twitter">                    
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Hobi</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="hobi">
                      <option value="">- choose -</option>
                      <?php                       
                        foreach ($dt_hobi->result() as $isi) {
                          echo "<option value='$isi->id_hobi'>$isi->hobi</option>";
                        }
                       ?>                                                                       
                    </select> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Akun Youtube</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control"  id="youtube"  placeholder="Akun Youtube" name="youtube">                    
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Karakteristik</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control"  id="karakteristik"  placeholder="Karakteristik" name="karakteristik">                    
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
    </div><!-- /.box -->
    </form>


    <?php 
    }elseif($set == 'edit'){
      $row = $dt_permohonan->row();
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/entry_permohonan">
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
            <form class="form-horizontal" action="dealer/entry_permohonan/update" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID List Appointment</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id" value="<?php echo $row->id_list_appointment ?>">
                    <input type="text" class="form-control" value="<?php echo $row->id_list_appointment ?>" id="id_list_appointment" readonly placeholder="ID List Appointment" name="id_list_appointment">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Customer</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="tipe_customer">
                      <option value="<?php echo $row->tipe_customer ?>"><?php echo $row->tipe_customer ?></option>
                    </select>                    
                    <input type="hidden" id="tgl" value="<?php echo date("Y-m-d") ?>" name="tgl">
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembelian</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_pembelian">
                      <option value="<?php echo $row->jenis_pembelian ?>"><?php echo $row->jenis_pembelian ?></option>
                      <?php                       
                      foreach ($dt_jenis->result() as $isi) {
                        echo "<option>$isi->jenis_pembelian</option>";
                      }
                      ?>

                    </select>
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Beli</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control" id="tanggal" value="<?php echo $row->tgl_beli ?>" placeholder="Tgl Beli" name="tgl_beli">                    
                  </div>
                </div>

                <button class="btn btn-primary btn-block btn-flat" disabled>Detail Kendaraan</button>
                <br>                
                <span id="tampil_kendaraan"></span>                                                                                                                                              

                 
                
                <br>
                <button class="btn btn-primary btn-block btn-flat" disabled>Detail Data</button>
                <br>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">RO</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="ro">
                      <option value="<?php echo $row->ro ?>"><?php echo $row->ro ?></option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pekerjaan">                      
                      <option value="<?php echo $row->pekerjaan ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_pekerjaan","id_pekerjaan",$row->pekerjaan)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->status_hp;
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
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembelian</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_beli">
                      <option value="<?php echo $row->jenis_beli ?>">
                        <?php 
                        $tr = $row->jenis_beli;
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
                    <input type="text" class="form-control" onkeypress="return number_only(event)" placeholder="No KTP/KITAS" name="id_ktp" value="<?php  echo $row->id_ktp ?>">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No KK</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" onkeypress="return number_only(event)" placeholder="No KK" name="no_kk" value="<?php  echo $row->no_kk ?>">                    
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Pengeluaran 1 Bulan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pengeluaran_bulan">
                      <option value="<?php echo $row->pengeluaran_bulan ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_pengeluaran_bulan","id_pengeluaran_bulan",$row->pengeluaran_bulan)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->pengeluaran;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt = $this->m_admin->kondisiCond("ms_pengeluaran_bulan","id_pengeluaran_bulan != '$row->pengeluaran_bulan'");                                                
                      foreach($dt->result() as $val) {
                        echo "
                        <option value='$val->id_pengeluaran_bulan'>$val->pengeluaran</option>;
                        ";
                      }
                      ?>
                    </select> 
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pemilik</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control" value="<?php echo $row->nama_pemilik ?>" placeholder="Nama Pemilik" name="nama_pemilik">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Pendidikan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pendidikan">
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
                      $dt = $this->m_admin->kondisiCond("ms_pendidikan","id_pendidikan != '$row->pendidikan'");                                                
                      foreach($dt->result() as $val) {
                        echo "
                        <option value='$val->id_pendidikan'>$val->pendidikan</option>;
                        ";
                      }
                      ?>
                    </select> 
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kelamin</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_kelamin">
                      <option value="<?php echo $row->jenis_kelamin ?>"><?php echo $row->jenis_kelamin ?></option>
                      <option value="Laki-laki">Laki-laki</option>
                      <option value="Perempuan">Perempuan</option>
                    </select> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control" value="<?php echo $row->no_hp ?>"  placeholder="No HP" name="no_hp">                    
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control"  id="tanggal2"  value="<?php echo $row->tgl_lahir ?>"placeholder="Tgl Lahir" name="tgl_lahir">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control"  placeholder="No Telp" value="<?php echo $row->no_telp ?>" name="no_telp">                    
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
                    <input type="text" value="<?php echo $kel ?>" readonly name="kelurahan" data-toggle="modal" placeholder="Kelurahan" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="cek_kecamatan()">                               
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status HP</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="status_hp">
                      <option value="<?php echo $row->status_hp ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_status_hp","id_status_hp",$row->status_hp)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->status_hp;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt = $this->m_admin->kondisiCond("ms_status_hp","id_status_hp != '$row->status_hp'");                                                
                      foreach($dt->result() as $val) {
                        echo "
                        <option value='$val->id_status_hp'>$val->status_hp</option>;
                        ";
                      }
                      ?>
                    </select> 
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                  <div class="col-sm-4">
                    <input type="hidden" id="id_kecamatan" value="<?php echo $row->id_kecamatan ?>" name="id_kecamatan">                    
                    <input type="text"  class="form-control"  id="kecamatan" readonly  placeholder="Kabupaten" name="kecamatan">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Apakah anda bersedia dikirimkan informasi terbaru dari Honda?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="bersedia_informasi">
                      <option value="<?php echo $row->bersedia_informasi ?>"><?php echo $row->bersedia_informasi ?></option>                      
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select> 
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Kabupaten</label>
                  <div class="col-sm-4">
                    <input type="hidden" id="id_kabupaten" value="<?php echo $row->id_kabupaten ?>" name="id_kabupaten">                    
                    <input type="text"  class="form-control" readonly id="kabupaten"  placeholder="Kabupaten" name="kabupaten">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Merk Motor yang dimiliki sebelumnya?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="merk_sebelumnya">
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                  <div class="col-sm-4">
                    <input type="hidden" id="id_provinsi" value="<?php echo $row->id_provinsi ?>" name="id_provinsi">                    
                    <input type="text"  class="form-control" readonly id="provinsi"  placeholder="Provinsi" name="provinsi">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Motor yang dimiliki sebelumnya?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_sebelumnya">
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
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Agama</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="id_agama">
                      <option value="<?php echo $row->id_agama ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_agama","id_agama",$row->id_agama)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->agama";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_agama = $this->m_admin->kondisiCond("ms_agama","id_agama != '$row->id_agama'");                                                
                      foreach ($dt_agama->result() as $isi) {
                        echo "<option value='$isi->id_agama'>$isi->agama</option>";
                      }
                      ?>                             
                    </select> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Sepeda Motor digunakan untuk?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="digunakan_untuk">
                      <option value="<?php echo $row->digunakan_untuk ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_digunakan","id_digunakan",$row->digunakan_untuk)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->digunakan_untuk;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt = $this->m_admin->kondisiCond("ms_digunakan","id_digunakan != '$row->digunakan_untuk'");                                                
                      foreach($dt->result() as $val) {
                        echo "
                        <option value='$val->id_digunakan'>$val->digunakan_untuk</option>;
                        ";
                      }
                      ?>                                    
                    </select> 
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control" value="<?php echo $row->email ?>"  id="id_penerimaan_unit"  placeholder="Email" name="email">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Yang menggunakan sepeda motor?</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="menggunakan_motor">
                      <option value="<?php echo $row->menggunakan_motor ?>"><?php echo $row->menggunakan_motor ?></option>
                      <option>Sendiri</option>
                      <option>Anak</option>
                      <option>Suami/Istri</option>                                           
                    </select> 
                  </div>
                </div>
                 <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat yg digunakan untuk korenspodensi</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="alamat_koresponden">
                      <option value="<?php echo $row->alamat_koresponden ?>"><?php echo $row->alamat_koresponden ?></option>                                            
                      <option>Alamat sesuai KTP</option>
                      <option>Alamat tinggal saat ini</option> 
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Rumah</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="status_rumah">
                      <option value="<?php echo $row->status_rumah ?>"><?php echo $row->status_rumah ?></option>
                      <option>Milik Sendiri</option>
                      <option>Sewa</option>                                              
                    </select> 
                  </div>
                </div>             
                

                <br>
                <button class="btn btn-primary btn-block btn-flat" disabled>Data Kartu Keluarga</button>
                <br>
                <span id="tampil_keluarga"></span>                          

                <br>
                <button class="btn btn-primary btn-block btn-flat" disabled>Data Dealer</button>
                <br>         

                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Penjualan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_penjualan">
                      <option value="<?php echo $row->jenis_penjualan ?>"><?php echo $row->jenis_penjualan ?></option>                                            
                      <option>Cash</option>
                      <option>Kredit</option>
                    </select> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Akun Facebook</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control" value="<?php echo $row->facebook ?>" placeholder="Akun Facebook" name="facebook">                    
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Sales Person</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="id_karyawan_dealer">
                      <option value="<?php echo $row->id_karyawan_dealer ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_karyawan_dealer","id_karyawan_dealer",$row->id_karyawan_dealer)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->id_karyawan_dealer";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $id_dealer = $this->m_admin->cari_dealer();
                      $dt_karyawan_dealer = $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE id_karyawan_dealer != '$row->id_karyawan_dealer' AND id_dealer = '$id_dealer'");                                                
                      foreach ($dt_karyawan_dealer->result() as $isi) {
                          echo "<option value='$isi->id_karyawan_dealer'>$isi->id_karyawan_dealer | $isi->nama_lengkap</option>";
                        }
                       ?>                                                                  
                    </select> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Akun Instagram</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control"  id="instagram" value="<?php echo $row->instagram ?>" placeholder="Akun Instagram" name="instagram">                    
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Dokumen KTP</label>
                  <div class="col-sm-4">                    
                      <?php 
                      if($row->dokumen_ktp=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="dokumen_ktp" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="dokumen_ktp" value="1">
                      <?php } ?>                                          
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Akun Twitter</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control"  id="twitter" value="<?php echo $row->twitter ?>" placeholder="Akun Twitter" name="twitter">                    
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Hobi</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="hobi">
                      <option value="<?php echo $row->hobi ?>"><?php echo $row->hobi ?></option>
                      <?php                       
                        foreach ($dt_hobi->result() as $isi) {
                          echo "<option value='$isi->hobi'>$isi->hobi</option>";
                        }
                       ?>                                                                       
                    </select> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Akun Youtube</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control"  id="youtube" value="<?php echo $row->youtube ?>" placeholder="Akun Youtube" name="youtube">                    
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Karakteristik</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control"  id="karakteristik" value="<?php echo $row->karakteristik ?>" placeholder="Karakteristik" name="karakteristik">                    
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
    </div><!-- /.box -->
    </form>

    

    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/entry_permohonan/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
          <button class="btn bg-maroon btn-flat margin"><i class="fa fa-download"></i> Download Data Konsumen</button>                  
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>Nama</th>
              <th>No KTP</th>                            
              <th>Jenis Pembelian</th>              
              <th>Alamat</th>
              <th>Tgl Lahir</th>
              <th>Jenis Kelamin</th>
              <th>Agama</th>              
              <th>No HP</th>
              <th>No Telp</th>
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_permohonan_konsumen->result() as $row) {                 
            
            $edit = $this->m_admin->set_tombol($id_menu,$group,'edit');            

            echo "
            <tr>
              <td>$no</td>
              <td>$row->nama_pemilik</td>                                          
              <td>$row->id_ktp</td>              
              <td>$row->jenis_pembelian</td>              
              <td>$row->alamat_koresponden</td>                                          
              <td>$row->tgl_lahir</td>                                          
              <td>$row->jenis_kelamin</td>                                          
              <td>$row->agama</td>                                          
              <td>$row->no_hp</td>                                                        
              <td>$row->no_telp</td>                                                                    
              <td>                                
                <a href='dealer/entry_permohonan/edit?id=$row->id_list_appointment'>
                  <button $edit class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button>
                </a>                                
              </td>
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
<?php if ($set!='view'): ?>
  
<div class="modal fade" id="Nosinmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search Item
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>No Mesin</th>            
              <th>No Rangka</th>            
              <th>ID Item</th>
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          foreach ($dt_item->result() as $ve2) {
            echo "
            <tr>
              <td>$no</td>
              <td>$ve2->no_mesin</td>
              <td>$ve2->no_rangka</td>
              <td>$ve2->id_item</td>";
              ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="chooseitem_no_mesin('<?php echo $ve2->no_mesin; ?>','<?php echo $ve2->no_rangka; ?>','<?php echo $ve2->id_item; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
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
<?php endif ?>
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
function tampilkan(){
  kirim_data_keluarga();
  kirim_data_kendaraan();
  cek_kecamatan();
}
function auto(){
  var tgl_js=document.getElementById("tgl").value; 
  $.ajax({
      url : "<?php echo site_url('dealer/entry_permohonan/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_list_appointment").val(data[0]);                
        kirim_data_kendaraan(); 
        kirim_data_keluarga(); 
      }        
  })
}
function kirim_data_keluarga(){    
  $("#tampil_keluarga").show();
  var id_list_appointment = document.getElementById("id_list_appointment").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_list_appointment="+id_list_appointment;                           
     xhr.open("POST", "dealer/entry_permohonan/t_keluarga", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_keluarga").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}
function kirim_data_kendaraan(){    
  $("#tampil_kendaraan").show();
  var id_list_appointment = document.getElementById("id_list_appointment").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_list_appointment="+id_list_appointment;                           
     xhr.open("POST", "dealer/entry_permohonan/t_kendaraan", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_kendaraan").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}
function chooseitem_no_mesin(no_mesin,no_rangka,id_item){
  document.getElementById("no_mesin").value = no_mesin; 
  document.getElementById("no_rangka").value = no_rangka; 
  document.getElementById("id_item").value = id_item;   
  $("#Nosinmodal").modal("hide");
}
function simpan_kendaraan(){
  var no_mesin  = document.getElementById("no_mesin").value;    
  var id_list_appointment  = document.getElementById("id_list_appointment").value;    
  if (no_mesin == "" || id_list_appointment == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('dealer/entry_permohonan/save_kendaraan')?>",
          type:"POST",
          data:"no_mesin="+no_mesin+"&id_list_appointment="+id_list_appointment,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="ok"){
                  kirim_data_kendaraan();
                  kosong();                
              }else{
                  alert("Gagal Simpan, No Mesin ini sudah dipilih");
                  kosong();                  
              }                
          }
      })    
  }
}
function kosong(args){
  $("#no_mesin").val("");
  $("#no_rangka").val("");     
  $("#id_item").val("");     
}
function hapus_kendaraan(a,b){ 
    var id_list_appointment  = a;       
    var id_permohonan_kendaraan  = b;       
    $.ajax({
        url : "<?php echo site_url('dealer/entry_permohonan/delete_kendaraan')?>",
        type:"POST",
        data:"id_permohonan_kendaraan="+id_permohonan_kendaraan+"&id_list_appointment="+id_list_appointment,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_kendaraan();
            }
        }
    })
}
function chooseitem(id_kelurahan){
  document.getElementById("id_kelurahan").value = id_kelurahan; 
  cek_kecamatan();
  $("#Kelurahanmodal").modal("hide");
}
function cek_kecamatan(){
  var id_kelurahan = $("#id_kelurahan").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/entry_permohonan/cek_kecamatan')?>",
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
function simpan_keluarga(){  
  var id_list_appointment = document.getElementById("id_list_appointment").value;    
  var nik                 = document.getElementById("nik").value;    
  var nama_keluarga       = document.getElementById("nama_keluarga").value;    
  var tempat_lahir        = document.getElementById("tempat_lahir").value;    
  var tanggal1            = document.getElementById("tanggal1").value;    
  var status_kawin        = document.getElementById("status_kawin").value;    
  var posisi_keluarga     = document.getElementById("posisi_keluarga").value;    
  var pekerjaan           = document.getElementById("pekerjaan").value;    
  var pendidikan          = document.getElementById("pendidikan").value;    
  var no_hp               = document.getElementById("no_hp").value;    

  if (nik == "" || id_list_appointment == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('dealer/entry_permohonan/save_keluarga')?>",
          type:"POST",
          data:"nik="+nik+"&id_list_appointment="+id_list_appointment+"&nama_keluarga="+nama_keluarga+"&tempat_lahir="+tempat_lahir+"&tanggal1="+tanggal1+"&status_kawin="+status_kawin+"&posisi_keluarga="+posisi_keluarga+"&pekerjaan="+pekerjaan+"&pendidikan="+pendidikan+"&no_hp="+no_hp,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                  kirim_data_keluarga();
                  kosong_keluarga();                
              }else{
                  alert("Gagal Simpan, NIK ini sudah dimasukkan");
                  kosong_keluarga();                  
              }                
          }
      })    
  }
}
function kosong_keluarga(args){
  $("#nik").val("");
  $("#nama_keluarga").val("");     
  $("#tempat_lahir").val("");     
  $("#tanggal1").val("");     
  $("#status_kawin").val("");     
  $("#posisi_keluarga").val("");     
  $("#pekerjaan").val("");     
  $("#pendidikan").val("");     
  $("#no_hp").val("");     
}
function hapus_keluarga(a,b){ 
    var id_list_appointment  = a;       
    var id_permohonan_keluarga  = b;       
    $.ajax({
        url : "<?php echo site_url('dealer/entry_permohonan/delete_keluarga')?>",
        type:"POST",
        data:"id_permohonan_keluarga="+id_permohonan_keluarga+"&id_list_appointment="+id_list_appointment,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_keluarga();
            }
        }
    })
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
            "url": "<?php echo site_url('dealer/entry_permohonan/ajax_list')?>",
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
<?php echo $set ?>
