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
<body onload="auto()">
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
          <a href="dealer/monitoring_outstanding_ksu">
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
            <form class="form-horizontal" action="dealer/monitoring_outstanding_ksu/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <button class="btn btn-block btn-primary btn-flat" disabled> DATA KONSUMEN </button> <br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Sales *</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="nama_sales" id="id_karyawan_dealer" onchange="take_sales()">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_karyawan->result() as $val) {
                        echo "
                        <option value='$val->id_karyawan_dealer'>$val->nama_lengkap</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Sales Person</label>
                  <div class="col-sm-4">
                    <input type="hidden" class="form-control" id="nama_sales" name="nama_sales">                    
                    <input type="text" readonly class="form-control" id="kode_sales" readonly placeholder="Kode Sales Person" name="kode_sales">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID monitoring_outstanding_ksu</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="id_monitoring_outstanding_ksu" readonly placeholder="ID Penerimaan Unit" name="id_monitoring_outstanding_ksu">
                    <input type="hidden" id="tgl" value="<?php echo date("d-M-y") ?>">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="id_customer" readonly placeholder="ID Customer" name="id_customer">                    
                  </div>
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen *</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" placeholder="Nama Konsumen" name="nama_konsumen">                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No KTP" name="no_ktp">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No NPWP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No NPWP" name="no_npwp">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kelamin</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_kelamin">
                      <option>- choose -</option>
                      <option>Pria</option>
                      <option>Wanita</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl.Lahir</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal4" class="form-control" placeholder="Tgl.Lahir" name="tgl_lahir">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan *</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="kelurahan" id="id_kelurahan" onchange="take_kel()">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_kelurahan->result() as $val) {
                        echo "
                        <option value='$val->id_kelurahan'>$val->kelurahan</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                  <div class="col-sm-4">
                    <input type="hidden" class="form-control" id="kelurahan" name="kelurahan">                    
                    <input type="text" readonly class="form-control" id="kecamatan" placeholder="Kecamatan" name="kecamatan">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kabupaten/Kota</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" id="kabupaten" placeholder="Kabupaten" name="kabupaten">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" id="provinsi" placeholder="Provinsi" name="provinsi">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kodepos</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control" placeholder="Kodepos" name="kodepos">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Agama</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" required name="agama">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_agama->result() as $val) {
                        echo "
                        <option value='$val->id_agama'>$val->agama</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat *</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Alamat" required name="alamat">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pekerjaan">
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Pendidikan</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="pendidikan">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_pendidikan->result() as $val) {
                        echo "
                        <option value='$val->id_pendidikan'>$val->pendidikan</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pengeluaran 1 Bulan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pengeluaran_bulan">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_pengeluaran->result() as $val) {
                        echo "
                        <option value='$val->id_pengeluaran'>$val->pengeluaran</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Penanggung Jawab</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Nama Penanggung Jawab" name="penanggung_jawab">                                                            
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No HP" name="no_hp">                                                            
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="No Telp" name="no_telp">                                                            
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kebersediaan utk dihubungi</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="sedia_hub">
                      <option value="">- choose -</option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>                                                     
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Merk Motor yg dimiliki sekarang</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="merk_sebelumnya">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_merk_sebelumnya->result() as $val) {
                        echo "
                        <option value='$val->id_merk_sebelumnya'>$val->merk_sebelumnya</option>;
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
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_jenis_sebelumnya->result() as $val) {
                        echo "
                        <option value='$val->id_jenis_sebelumnya'>$val->jenis_sebelumnya</option>;
                        ";
                      }
                      ?>
                    </select>                                                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Sepeda motor digunakan untuk</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="digunakan">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_digunakan->result() as $val) {
                        echo "
                        <option value='$val->id_digunakan'>$val->digunakan</option>;
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
                      <option value="">- choose -</option>
                      <option>Sendiri</option>
                      <option>Anak</option>
                      <option>Suami/Istri</option>
                    </select>                                                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-4">
                    <input type="email" class="form-control" placeholder="Email" name="email">
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Status No Hp</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="status_hp">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_status_hp->result() as $val) {
                        echo "
                        <option value='$val->id_status_hp'>$val->status_hp</option>;
                        ";
                      }
                      ?>
                    </select>                                                     
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Status monitoring_outstanding_ksu</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="status_monitoring_outstanding_ksu">
                      <option value="">- choose -</option>
                      <option>ACC</option>
                      <option>Pending</option>                      
                    </select>                                                    
                  </div>                  
                </div>
                <button class="btn btn-block btn-warning btn-flat" disabled> DATA KENDARAAN </button> <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Type *</label>
                  <div class="col-sm-4">                    
                    <select class="form-control select2" name="type" required>
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_tipe->result() as $val) {
                        echo "
                        <option value='$val->tipe_ahm'>$val->tipe_ahm</option>;
                        ";
                      }
                      ?>
                    </select>                                                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor Mesin</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="no_mesin">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_no_mesin->result() as $val) {
                        echo "
                        <option value='$val->no_mesin'>$val->no_mesin</option>;
                        ";
                      }
                      ?>
                    </select>                                                     
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                  <div class="col-sm-4">                    
                    <select class="form-control select2" name="no_rangka">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_no_rangka->result() as $val) {
                        echo "
                        <option value='$val->no_rangka'>$val->no_rangka</option>;
                        ";
                      }
                      ?>
                    </select>                                                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="warna">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_warna->result() as $val) {
                        echo "
                        <option value='$val->warna'>$val->warna</option>;
                        ";
                      }
                      ?>
                    </select>                                                     
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun Rakitan</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="tahun_rakit">
                      <option value="">- choose -</option>
                      <?php 
                      for($a = 2017;$a <= 2022;$a++){
                        echo "
                        <option value='$a'>$a</option>;
                        ";
                      }
                      ?>
                    </select>                                                    
                  </div>
                </div>

                <button class="btn btn-block btn-danger btn-flat" disabled> FOLLOW UP/LIST APPOINTMENT </button> <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Atur Tanggal</label>
                  <div class="col-sm-4">                    
                    <input type="text" id="tanggal2" name="atur_tanggal" class="form-control">                                                   
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Atur Jam</label>
                  <div class="col-sm-4">                    
                    <input type="text" id="jam2" name="atur_jam" class="form-control">                                                   
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
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    

    <?php
    }elseif($set=="list_ksu"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/monitoring_outstanding_ksu">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
              <th>No Surat Jalan</th>              
              <th>Tgl Surat Jalan</th>              
              <th>Kode KSU</th>              
              <th>Nama KSU</th>
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>            
          <!--?php 
          //$no=1; 
          //foreach($dt_monitoring_outstanding_ksu->result() as $row) {     
            //$s = $this->db->query("SELECT * FROM ms_vendor WHERE id_vendor = '$row->ekspedisi'")->row();          
            echo "
            <tr>
              <td>1</td>
              <td>P01/89-1/2018</td>
              <td>19-01-2018</td>
              <td>180</td>                            
              <td>                
                <a href='dealer/monitoring_outstanding_ksu/edit?id=19'>
                  <button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-download'></i> Konfirmasi Penerimaan</button>                
                </a>
                <a href='dealer/monitoring_outstanding_ksu/view?id=19'>
                  <button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-eye'></i> Detail</button>
                </a>
              </td>
            </tr>
            ";
          //$no++;
          //}
          ?-->
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"><!--
          <a href="dealer/monitoring_outstanding_ksu/list_ksu">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-list"></i> List KSU</button>
          </a>          
          button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  
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
              <th>Tgl Penerimaan</th>
              <th>No Surat Jalan Oustanding KSU</th>              
              <th>Tgl Surat Jalan Oustanding KSU</th>              
              <th>QTY Total</th>              
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_monitoring_outstanding_ksu->result() as $key) {     
            $tr = $this->m_admin->getByID("tr_mon_ksu","no_pl_ksu",$key->no_pl_ksu)->row();
            $tgl2 = date("Y-m-d", strtotime("+7 days", strtotime($key->tgl_pl_ksu))); 
            $tgl1 = date("Y-m-d");
            
            if($tgl1 > $tgl2){
              $isi = "style='background-color:#F00'";
            }else{
              $isi = "";
            } 
            if(isset($tr->no_pl_ksu)){
              $status = $tr->status_mon;
              if($status == 'close'){  
              $getQty = $this->db->query("SELECT sum(qty_konfirmasi) as qty_tot FROM tr_mon_ksu_detail WHERE no_pl_ksu = '$key->no_pl_ksu'")->row()->qty_tot;        
            echo "
            <tr $isi>
              <td>$no</td>
              <td>$key->tgl_pl_ksu</td>
              <td>$key->no_sj</td>
              <td>$key->tgl_pl_ksu</td>
              <td>$getQty</td>                            
              <td>                
                <a href='dealer/monitoring_outstanding_ksu/konfirmasi?id=$key->no_sj&pl=$key->no_pl_ksu&tgl=$key->tgl_pl_ksu'>
                  <button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-download'></i> Konfirmasi Penerimaan</button>                
                </a>
                </a>
              </td>
            </tr>
            ";
          $no++;
        }}
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<?php
    }elseif ($set=='konfirmasi') {
    ?>

        <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/monitoring_outstanding_ksu">            
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
          	<button class="btn btn-block btn-primary btn-flat" disabled=""> KONFIRMASI PENERIMAAN </button>
          </br>
            <form class="form-horizontal" action="dealer/monitoring_outstanding_ksu/save_ksu_konfirmasi_penerimaan" method="post" enctype="multipart/form-data">
          	<div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">No SJ Outstanding KSU</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" readonly="" name="no_sj" value="<?php echo $sj ?>" disabled>                              
              </div>
              <label for="inputEmail3" class="col-sm-2 control-label">Tgl SJ Outstanding KSU</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" readonly="" id="tgl_sj"name="tgl_sj" value="<?php echo $tgl_sj ?>" disabled>                                         
              </div>
            </div>
             <button class="btn btn-block btn-warning btn-flat" disabled=""> DETAIL PENERIMAAN </button>

          
              <div class="box-body">              
                <input type="hidden" name="no_pl_ksu" value="<?php echo $pl ?>">                
                <input type="hidden" name="sj" value="<?php echo $sj ?>">                
                <div class="form-group">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>                        
                        <th width="5%">No</th>
                        <th>Kode KSU</th> 
                        <th>Nama KSU</th>                          
                        <th width="2%">Qty Penerimaan</th>                            
                      </tr>
                    </thead>
                    <tbody>            
                    <?php 
                    $no=1;        
                    foreach ($dt_mo->result() as $key) {       
                      $cek = $this->db->query("SELECT * FROM tr_mon_ksu_detail WHERE id_ksu = '$key->id_ksu' AND no_pl_ksu = '$pl'");
                      if($cek->num_rows() > 0){
                        $r = $cek->row();
                        $val = "value='$r->qty_konfirmasi'";
                      }else{
                        $val = "";
                      }
                      echo "
                      <tr>
                        <td>$no</td>              
                        <td>$key->id_ksu</td>
                        <td>$key->ksu</td>
                        <td>
                          <input type='text' class='form-control isi' name='qty_konfirmasi[]' $val >
                          <input type='hidden' class='form-control isi' name='qty_do[]' value='$key->qty_do'>
                          <input type='hidden' class='form-control isi' name='qty_penuh[]' value='$key->qty_penuh'>
                          <input type='hidden' class='form-control isi' name='id_ksu[]' value='$key->id_ksu'>
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
              <div class="box-footer">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-10">
              <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
              <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
            </div>
          </div><!-- /.box-footer -->


    <?php      
    }
    ?>
  </section>
</div>



<script type="text/javascript">
function auto(){
  var tgl_js=document.getElementById("tgl").value; 
  $.ajax({
      url : "<?php echo site_url('dealer/monitoring_outstanding_ksu/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_monitoring_outstanding_ksu").val(data[0]);                
        $("#id_customer").val(data[1]);                
      }        
  })
}
function take_sales(){
  var id_karyawan_dealer = $("#id_karyawan_dealer").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/monitoring_outstanding_ksu/take_sales')?>",
      type:"POST",
      data:"id_karyawan_dealer="+id_karyawan_dealer,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");          
          //$("#no_polisi").html(msg);                                                    
          $("#kode_sales").val(data[0]);                                                    
          $("#nama_sales").val(data[1]);                                                    
      } 
  })
}


</script>