<?php 
if(!isset($_GET['id'])){
?>
<body onload="">
<?php } ?>

<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Master Data</li>    
    <li class="">Karyawan</li>    
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
          <a href="master/karyawan">
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
            <form method="POST" role="form" enctype="multipart/form-data" action="master/karyawan/save" class="form-horizontal form-groups-bordered">
              <div class="box-body">   
                <input type="hidden" id="tgl_i" value="<?php echo date("Y-m-d"); ?>">                                                                                                                                                 
                <!-- <input type="text" id="id_karyawan" name="id_karyawan">                                                                                                                                                  -->
                <!--div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">ID Dealer</label>                              
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer">
                      <option value="">- choose -</option>   
                      <?php 
                      foreach($dt_dealer->result() as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->nama_dealer</option>;
                        ";
                      }
                      ?>                 
                    </select>                                
                  </div>
                </div> -->
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">ID Karyawan</label>            
                  <div class="col-sm-4">
                    <input type="text" id="id_karyawan" class="form-control" id="field-1" required placeholder="ID Karyawan" name="id_karyawan">
                  </div>                   
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Nama Lengkap</label>            
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="field-1" placeholder="Nama Lengkap" name="nama_lengkap" required>
                  </div>                   
                </div>                
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">No.KTP</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="field-1" placeholder="No.KTP" name="no_ktp" required>
                  </div>
                   <label for="field-1" class="col-sm-2 control-label">NPK</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="field-1" placeholder="NPK" name="npk" required>
                  </div>
                </div>                
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Divisi</label>           
                  <div class="col-sm-4">
                    <select class="form-control" name="id_divisi" id="id_divisi" onchange="get_div()">
                      <option value="">- choose -</option>   
                      <?php 
                      foreach($dt_divisi->result() as $val) {
                        echo "
                        <option value='$val->id_divisi'>$val->divisi</option>;
                        ";
                      }
                      ?>                 
                    </select>
                  </div>

                  <label for="field-1" class="col-sm-2 control-label">Jabatan</label>            
                  <div class="col-sm-4">
                    <select class="form-control" name="id_jabatan_r">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_jabatan->result() as $val) {
                        echo "
                        <option value='$val->id_jabatan'>$val->jabatan</option>;
                        ";
                      }
                      ?>                      
                    </select>
                  </div>
                </div> 
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Department</label>           
                  <div class="col-sm-4">
                    <select class="form-control" name="id_department" id="id_dep" onchange="get_dep()">
                      <option value="">- choose -</option>                         
                    </select>
                  </div>

                  <label for="field-1" class="col-sm-2 control-label">Sub Department</label>            
                  <div class="col-sm-4">
                    <select class="form-control" name="id_sub_department" id="id_sub_department">
                      <option value="">- choose -</option>
                      
                    </select>
                  </div>
                </div>    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Tempat/Tgl.Lahir</label>            
                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="field-1" placeholder="Tempat Lahir" name="tempat_lahir">
                  </div>                   
                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="tanggal" placeholder="Tgl.Lahir" name="tgl_lahir">
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Jenis Kelamin</label>            
                  <div class="col-sm-4">
                    <select class="form-control" name="jk">
                      <option value="">- choose -</option>
                      <option>Laki-laki</option>
                      <option>Perempuan</option>
                    </select>
                  </div>                   
                  <label for="field-1" class="col-sm-2 control-label">Agama</label>            
                  <div class="col-sm-4">
                    <select class="form-control" name="id_agama">
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
                  <label for="field-1" class="col-sm-2 control-label">No.Telp</label>            
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="field-1" placeholder="No.Telp" name="no_telp">
                  </div>
                   <label for="field-1" class="col-sm-2 control-label">No.HP</label>            
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="field-1" placeholder="No.HP" name="hp_gsm">
                  </div>
                </div>
                <div class="form-group">                  
                   <label for="field-1" class="col-sm-2 control-label">Email</label>            
                  <div class="col-sm-4">
                    <input type="email" class="form-control" id="field-1" placeholder="Email" name="email">
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Alamat</label>            
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="field-1" placeholder="Alamat lengkap" name="alamat">                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Tgl.Masuk</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal2" placeholder="Tgl.Masuk" name="tgl_masuk">
                  </div>                   
                  <label for="field-1" class="col-sm-2 control-label">Tgl.Keluar</label>                              
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal3" placeholder="Tgl.Keluar" name="tgl_keluar">
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Alasan Keluar</label>            
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Alasan Keluar" name="alasan_keluar">
                  </div>                                     
                </div>

                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Foto (Maks 300KB)</label>                              
                  <div class="col-sm-4">
                    <input type="file" class="form-control" id="tanggal3" name="foto_karyawan">
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="form-control flat-red" name="active" value="1" checked>
                      Active
                    </div>
                  </div>                  
                </div>
              
                <hr>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Riwayat Jabatan</label>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Jabatan</label>                              
                  <div class="col-sm-4">
                    <select class="form-control" id="id_jabatan" name="id_jabatan">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_jabatan->result() as $val) {
                        echo "
                        <option value='$val->id_jabatan'>$val->jabatan</option>;
                        ";
                      }
                      ?>                      
                    </select>
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <select id="status"  class="form-control">
                        <option>Aktif</option>
                        <option>Tidak Aktif</option>
                      </select>
                    </div>
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Dealer</label>                              
                  <div class="col-sm-4">
                    <select class="form-control select2" id="id_dealer" name="id_dealer">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_dealer->result() as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->nama_dealer</option>;
                        ";
                      }
                      ?>                      
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Tgl.Aktif</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal4" placeholder="Tgl.Aktif" name="tgl_aktif">
                  </div>                   
                  <label for="field-1" class="col-sm-2 control-label">Tgl.Nonaktif</label>                              
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal5" placeholder="Tgl.Nonaktif" name="tgl_nonaktif">
                  </div>
                </div>
                <div class="form-group">                          
                  <div class="col-sm-2">
                  </div>  
                  <div class="col-sm-8">
                   <button type="button" onClick="simpan_jabatan()" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>
                   <button type="button" onClick="kirim_data_jabatan()" class="btn btn-warning btn-flat"><i class="fa fa-eye"></i> Show</button>
                   <button type="button" onClick="hide_jabatan()" class="btn btn-warning btn-flat"><i class="fa fa-eye-slash"></i> Hide</button>
                  </div> 
                </div>
                <div class="form-group">
                  <div class="col-sm-2">
                  </div>  
                  <div class="col-sm-10">
                    <div id="tampil_jabatan"></div>
                  </div>
                </div>
              </div>
              

              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
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
      $row = $dt_karyawan->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/karyawan">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="master/karyawan/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" id="id_karyawan" value="<?php echo $row->id_karyawan ?>" />
              <div class="box-body">    
                <!-- <div class="form-group">                
                  <label for="field-1" class="col-sm-2 control-label">ID Dealer</label>                              
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer">
                      <option value="<?php echo $row->id_dealer ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->nama_dealer;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_dealer = $this->m_admin->kondisiCond("ms_dealer","id_dealer != ".$row->id_dealer);                                                
                      foreach($dt_dealer->result() as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->nama_dealer</option>;
                        ";
                      }
                      ?>                 
                    </select>                                
                  </div>                  
                </div> -->
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Nama Lengkap</label>            
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->nama_lengkap ?>" class="form-control" id="field-1" placeholder="Nama Lengkap" name="nama_lengkap" required>
                  </div>                   
                </div>                
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">No.KTP</label>            
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->no_ktp ?>" class="form-control" id="field-1" placeholder="No.KTP" name="no_ktp" required>
                  </div>
                   <label for="field-1" class="col-sm-2 control-label">NPK</label>            
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->npk ?>" class="form-control" id="field-1" placeholder="NPK" name="npk" required>
                  </div>
                </div>                
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Divisi</label>           
                  <div class="col-sm-4">
                    <select class="form-control" name="id_divisi" id="id_divisi" onchange="get_div()">
                      <option value="<?php echo $row->id_divisi ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_divisi","id_divisi",$row->id_divisi)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->divisi;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_divisi = $this->m_admin->kondisiCond("ms_divisi","id_divisi != '$row->id_divisi'");                                                
                      foreach($dt_divisi->result() as $val) {
                        echo "
                        <option value='$val->id_divisi'>$val->divisi</option>;
                        ";
                      }
                      ?>                 
                    </select>
                  </div>

                  <label for="field-1" class="col-sm-2 control-label">Jabatan</label>            
                  <div class="col-sm-4">
                    <select class="form-control" name="id_jabatan_r">
                       <option value="<?php echo $row->id_jabatan ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_jabatan","id_jabatan",$row->id_jabatan)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->jabatan;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_jabatan = $this->m_admin->kondisiCond("ms_jabatan","id_jabatan != '$row->id_jabatan'");                                                
                      foreach($dt_jabatan->result() as $val) {
                        echo "
                        <option value='$val->id_jabatan'>$val->jabatan</option>;
                        ";
                      }
                      ?>                      
                    </select>
                  </div>
                </div>    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Department</label>           
                  <div class="col-sm-4">
                    <select class="form-control" name="id_department" id="id_dep" onchange="get_dep()">
                      <option value="<?php echo $row->id_department ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_department","id_department",$row->id_department)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->department;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                    </select>
                  </div>

                  <label for="field-1" class="col-sm-2 control-label">Sub Department</label>            
                  <div class="col-sm-4">
                    <select class="form-control" name="id_sub_department" id="id_sub_department">
                      <option value="<?php echo $row->id_sub_department ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_sub_department","id_sub_department",$row->id_sub_department)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->sub_department;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>                      
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Tempat/Tgl.Lahir</label>            
                  <div class="col-sm-3">
                    <input type="text" value="<?php echo $row->tempat_lahir ?>" class="form-control" id="field-1" placeholder="Tempat Lahir" name="tempat_lahir">
                  </div>                   
                  <div class="col-sm-3">
                    <input type="text" value="<?php echo $row->tgl_lahir ?>" class="form-control" id="tanggal" placeholder="Tgl.Lahir" name="tgl_lahir">
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Jenis Kelamin</label>            
                  <div class="col-sm-4">
                    <select class="form-control" name="jk">
                      <?php
                      if($row->jk=='Laki-laki'){
                        echo "
                        <option>Laki-laki</option>
                        <option>Perempuan</option>                        
                        ";
                      }else{
                        echo "
                        <option>Perempuan</option>
                        <option>Laki-laki</option>
                        ";
                      }
                      ?>                                            
                    </select>
                  </div>                   
                  <label for="field-1" class="col-sm-2 control-label">Agama</label>            
                  <div class="col-sm-4">
                    <select class="form-control" name="id_agama">
                      <option value="<?php echo $row->id_agama ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_agama","id_agama",$row->id_agama)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->agama;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_agama = $this->m_admin->kondisiCond("ms_agama","id_agama != '$row->id_agama'");                                                
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
                  <label for="field-1" class="col-sm-2 control-label">No.Telp</label>            
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" value="<?php echo $row->no_telp ?>" class="form-control" id="field-1" placeholder="No.Telp" name="no_telp">
                  </div>
                   <label for="field-1" class="col-sm-2 control-label">No.HP</label>            
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" value="<?php echo $row->hp_gsm ?>" class="form-control" id="field-1" placeholder="No.HP" name="hp_gsm">
                  </div>
                </div>
                <div class="form-group">                  
                   <label for="field-1" class="col-sm-2 control-label">Email</label>            
                  <div class="col-sm-4">
                    <input type="email" value="<?php echo $row->email ?>" class="form-control" id="field-1" placeholder="Email" name="email">
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Alamat</label>            
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->alamat ?>" class="form-control" id="field-1" placeholder="Alamat lengkap" name="alamat">                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Tgl.Masuk</label>            
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->tgl_masuk ?>" class="form-control" id="tanggal2" placeholder="Tgl.Masuk" name="tgl_masuk">
                  </div>                   
                  <label for="field-1" class="col-sm-2 control-label">Tgl.Keluar</label>                              
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->tgl_keluar ?>" class="form-control" id="tanggal3" placeholder="Tgl.Keluar" name="tgl_keluar">
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Alasan Keluar</label>            
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->alasan_keluar ?>" class="form-control" placeholder="Alasan Keluar" name="alasan_keluar">
                  </div>                   
                  
                </div>    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Foto (Maks 300KB)</label>                              
                  <div class="col-sm-3">
                    <input type="file" class="form-control" id="tanggal3" name="foto_karyawan">
                  </div>
                  <div class="col-sm-1">            
                    <a href="#modal_foto" class="btn btn-primary" data-toggle="modal">
                        Show</button>              
                    </a>                    
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->active=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="active" value="1">                      
                      <?php } ?>
                      Active
                    </div>
                  </div>                  
                </div>
                <hr>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Riwayat Jabatan</label>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Jabatan</label>                              
                  <div class="col-sm-4">
                    <select class="form-control" id="id_jabatan" name="id_jabatan">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_jabatan2->result() as $val) {
                        echo "
                        <option value='$val->id_jabatan'>$val->jabatan</option>;
                        ";
                      }
                      ?>                      
                    </select>
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <select id="status"  class="form-control">
                        <option>Aktif</option>
                        <option>Tidak Aktif</option>
                      </select>
                    </div>
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Dealer</label>                              
                  <div class="col-sm-4">
                    <select class="form-control select2" id="id_dealer" name="id_dealer">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_dealer->result() as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->nama_dealer</option>;
                        ";
                      }
                      ?>                      
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Tgl.Aktif</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal4" placeholder="Tgl.Aktif" name="tgl_aktif">
                  </div>                   
                  <label for="field-1" class="col-sm-2 control-label">Tgl.Nonaktif</label>                              
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal5" placeholder="Tgl.Nonaktif" name="tgl_nonaktif">
                  </div>
                </div>
                <div class="form-group">                          
                  <div class="col-sm-2">
                  </div>  
                  <div class="col-sm-8">
                   <button type="button" onClick="simpan_jabatan()" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>
                   <button type="button" onClick="kirim_data_jabatan()" class="btn btn-warning btn-flat"><i class="fa fa-eye"></i> Show</button>
                   <button type="button" onClick="hide_jabatan()" class="btn btn-warning btn-flat"><i class="fa fa-eye-slash"></i> Hide</button>
                  </div> 
                </div>
                <div class="form-group">
                  <div class="col-sm-2">
                  </div>  
                  <div class="col-sm-10">
                    <div id="tampil_jabatan"></div>
                  </div>
                </div>
              </div>
              
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Update</button>                
                  <button type="button" onclick="cek()" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="detail"){
      $row = $dt_karyawan->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/karyawan">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="master/karyawan/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" id="id_karyawan" value="<?php echo $row->id_karyawan ?>" />
              <div class="box-body">                                                                                                                                                                     
                <!-- <div class="form-group">                
                  <label for="field-1" class="col-sm-2 control-label">ID Dealer</label>                              
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer" readonly>
                      <option value="<?php echo $row->id_dealer ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->nama_dealer;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_dealer = $this->m_admin->kondisiCond("ms_dealer","id_dealer != ".$row->id_dealer);                                                
                      foreach($dt_dealer->result() as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->nama_dealer</option>;
                        ";
                      }
                      ?>                 
                    </select>                                
                  </div>                  
                </div> -->
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Nama Lengkap</label>            
                  <div class="col-sm-10">
                    <input disabled type="text" value="<?php echo $row->nama_lengkap ?>" class="form-control" id="field-1" placeholder="Nama Lengkap" name="nama_lengkap" required>
                  </div>                   
                </div>                
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">No.KTP</label>            
                  <div class="col-sm-4">
                    <input disabled type="text" onkeypress="return number_only(event)" value="<?php echo $row->no_ktp ?>" class="form-control" id="field-1" placeholder="No.KTP" name="no_ktp" required>
                  </div>
                   <label for="field-1" class="col-sm-2 control-label">NPK</label>            
                  <div class="col-sm-4">
                    <input disabled type="text" onkeypress="return number_only(event)" value="<?php echo $row->npk ?>" class="form-control" id="field-1" placeholder="NPK" name="npk" required>
                  </div>
                </div>                
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Divisi</label>           
                  <div class="col-sm-4">
                    <select disabled class="form-control" name="id_divisi">
                      <option value="<?php echo $row->id_divisi ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_divisi","id_divisi",$row->id_divisi)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->divisi;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_divisi = $this->m_admin->kondisiCond("ms_divisi","id_divisi != '$row->id_divisi'");                                                
                      foreach($dt_divisi->result() as $val) {
                        echo "
                        <option value='$val->id_divisi'>$val->divisi</option>;
                        ";
                      }
                      ?>                 
                    </select>
                  </div>

                  <label for="field-1" class="col-sm-2 control-label">Jabatan</label>            
                  <div class="col-sm-4">
                    <select disabled class="form-control" name="id_jabatan_r">
                       <option value="<?php echo $row->id_jabatan ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_jabatan","id_jabatan",$row->id_jabatan)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->jabatan;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_jabatan = $this->m_admin->kondisiCond("ms_jabatan","id_jabatan != '$row->id_jabatan'");                                                
                      foreach($dt_jabatan->result() as $val) {
                        echo "
                        <option value='$val->id_jabatan'>$val->jabatan</option>;
                        ";
                      }
                      ?>                      
                    </select>
                  </div>
                </div>  
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Department</label>           
                  <div class="col-sm-4">
                    <select class="form-control" disabled name="id_department" id="id_dep" onchange="get_dep()">
                      <option value="<?php echo $row->id_department ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_department","id_department",$row->id_department)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->department;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                    </select>
                  </div>

                  <label for="field-1" class="col-sm-2 control-label">Sub Department</label>            
                  <div class="col-sm-4">
                    <select class="form-control" disabled name="id_sub_department" id="id_sub_department">
                      <option value="<?php echo $row->id_sub_department ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_sub_department","id_sub_department",$row->id_sub_department)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->sub_department;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>                      
                    </select>
                  </div>
                </div>  
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Tempat/Tgl.Lahir</label>            
                  <div class="col-sm-3">
                    <input disabled type="text" value="<?php echo $row->tempat_lahir ?>" class="form-control" id="field-1" placeholder="Tempat Lahir" name="tempat_lahir">
                  </div>                   
                  <div class="col-sm-3">
                    <input disabled type="text" value="<?php echo $row->tgl_lahir ?>" class="form-control" id="tanggal" placeholder="Tgl.Lahir" name="tgl_lahir">
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Jenis Kelamin</label>            
                  <div class="col-sm-4">
                    <select disabled class="form-control" name="jk">
                      <?php
                      if($row->jk=='Laki-laki'){
                        echo "
                        <option>Laki-laki</option>
                        <option>Perempuan</option>                        
                        ";
                      }else{
                        echo "
                        <option>Perempuan</option>
                        <option>Laki-laki</option>
                        ";
                      }
                      ?>                                            
                    </select>
                  </div>                   
                  <label for="field-1" class="col-sm-2 control-label">Agama</label>            
                  <div class="col-sm-4">
                    <select disabled class="form-control" name="id_agama">
                      <option value="<?php echo $row->id_agama ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_agama","id_agama",$row->id_agama)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->agama;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_agama = $this->m_admin->kondisiCond("ms_agama","id_agama != '$row->id_agama'");                                                
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
                  <label for="field-1" class="col-sm-2 control-label">No.Telp</label>            
                  <div class="col-sm-4">
                    <input disabled type="text" onkeypress="return number_only(event)" value="<?php echo $row->no_telp ?>" class="form-control" id="field-1" placeholder="No.Telp" name="no_telp">
                  </div>
                   <label for="field-1" class="col-sm-2 control-label">No.HP</label>            
                  <div class="col-sm-4">
                    <input disabled type="text" onkeypress="return number_only(event)" value="<?php echo $row->hp_gsm ?>" class="form-control" id="field-1" placeholder="No.HP" name="hp_gsm">
                  </div>
                </div>
                <div class="form-group">                  
                   <label for="field-1" class="col-sm-2 control-label">Email</label>            
                  <div class="col-sm-4">
                    <input disabled type="email" value="<?php echo $row->email ?>" class="form-control" id="field-1" placeholder="Email" name="email">
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Alamat</label>            
                  <div class="col-sm-10">
                    <input disabled type="text" value="<?php echo $row->alamat ?>" class="form-control" id="field-1" placeholder="Alamat lengkap" name="alamat">                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Tgl.Masuk</label>            
                  <div class="col-sm-4">
                    <input disabled type="text" value="<?php echo $row->tgl_masuk ?>" class="form-control" id="tanggal2" placeholder="Tgl.Masuk" name="tgl_masuk">
                  </div>                   
                  <label for="field-1" class="col-sm-2 control-label">Tgl.Keluar</label>                              
                  <div class="col-sm-4">
                    <input disabled type="text" value="<?php echo $row->tgl_keluar ?>" class="form-control" id="tanggal3" placeholder="Tgl.Keluar" name="tgl_keluar">
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Alasan Keluar</label>            
                  <div class="col-sm-10">
                    <input disabled type="text" value="<?php echo $row->alasan_keluar ?>" class="form-control" placeholder="Alasan Keluar" name="alasan_keluar">
                  </div>                   
                  
                </div>    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Foto (Maks 300KB)</label>                              
                  <div class="col-sm-3">
                    <input disabled type="file" class="form-control" id="tanggal3" name="foto_karyawan">
                  </div>
                  <div class="col-sm-1">            
                    <a href="#modal_foto" class="btn btn-primary" data-toggle="modal">
                        Show</button>              
                    </a>                    
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->active=='1'){
                      ?>
                      <input disabled type="checkbox" class="flat-red" name="active" value="1" checked>
                      <?php }else{ ?>
                      <input disabled type="checkbox" class="flat-red" name="active" value="1">                      
                      <?php } ?>
                      Active
                    </div>
                  </div>                  
                </div>
                <hr>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Riwayat Jabatan</label>
                </div>                
                <div class="form-group">                          
                  <div class="col-sm-2">
                  </div>  
                  <div class="col-sm-8">
                   <button type="button" onClick="kirim_data_jabatan()" class="btn btn-warning btn-flat"><i class="fa fa-eye"></i> Show</button>
                   <button type="button" onClick="hide_jabatan()" class="btn btn-warning btn-flat"><i class="fa fa-eye-slash"></i> Hide</button>
                  </div> 
                </div>
                <div class="form-group">
                  <div class="col-sm-2">
                  </div>  
                  <div class="col-sm-10">
                    <div id="tampil_jabatan"></div>
                  </div>
                </div>
              </div>
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <a href="master/karyawan/edit?id=<?php echo $row->id_karyawan ?>">
                    <button type="button" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Edit Data</button>                
                  </a>
                  <button type="button" onclick="cek()" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>

      </div>
    </div><!-- /.box -->

    <div class="modal fade" id="modal_foto">      
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
          </div>
          <div class="modal-body">
            <center>
              <img src="assets/panel/images/karyawan/<?php echo $row->foto_karyawan ?>" width='100%'>
            </center>
          </div>      
        </div>
      </div>
    </div>

    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/karyawan/add">
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
              <!-- <th>ID Dealer</th> -->
              <th>NPK</th>             
              <th>No.KTP</th>
              <th>Nama Lengkap</th>              
              <th>Divisi</th>
              <th>Jabatan</th>
              <th>No.Telp</th>
              <th>Status</th>                                        
              <th width="13%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no=1;
            foreach ($dt_karyawan->result() as $val) {    
              if($val->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";          
              echo"
              <tr>
                <td>$no</td>                
                <td>$val->npk</td>
                <td>$val->no_ktp</td>
                <td>$val->nama_lengkap</td>               
                <td>$val->divisi</td>
                <td>$val->jabatan</td>                                                          
                <td>$val->no_telp</td>                
                <td>$active</td>                               
                <td>"; ?>
                  <a href="master/karyawan/delete?id=<?php echo $val->id_karyawan ?>"><button type="button" class="btn btn-danger btn-sm btn-flat" title="Delete" onclick="return confirm('Are you sure want to delete this data?')"><i class="fa fa-trash"></i></button></a>
                  <a href="master/karyawan/edit?id=<?php echo $val->id_karyawan ?>"><button type='button' class="btn btn-primary btn-sm btn-flat" title="Edit"><i class="fa fa-edit"></i></button></a>
                  <a href="master/karyawan/view?id=<?php echo $val->id_karyawan ?>"><button type='button' class="btn btn-info btn-sm btn-flat" title="View"><i class="fa fa-eye"></i></button></a>
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



<script type="text/javascript">
function auto(){
  var po_js=document.getElementById("tgl_i").value; 
  $.ajax({
      url : "<?php echo site_url('master/karyawan/cari_id')?>",
      type:"POST",
      data:"po="+po_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_karyawan").val(data[0]);        
      }        
  })
}
function hide_jabatan(){
    $("#tampil_jabatan").hide();
}
function kirim_data_jabatan(){    
  $("#tampil_jabatan").show();
  var id_karyawan = document.getElementById("id_karyawan").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_karyawan="+id_karyawan;                           
     xhr.open("POST", "master/karyawan/t_jabatan", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_jabatan").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function simpan_jabatan(){
    var id_jabatan          = document.getElementById("id_jabatan").value;   
    var id_dealer           = document.getElementById("id_dealer").value;   
    var status              = document.getElementById("status").value;   
    var tgl_aktif           = document.getElementById("tanggal4").value;   
    var tgl_nonaktif        = document.getElementById("tanggal5").value;   
    var id_karyawan         = $("#id_karyawan").val();   
    //alert(active);
    if (id_jabatan=="" || id_karyawan=="") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
        $.ajax({
            url : "<?php echo site_url('master/karyawan/save_jabatan')?>",
            type:"POST",
            data:"id_dealer="+id_dealer+"&id_jabatan="+id_jabatan+"&id_karyawan="+id_karyawan+"&status="+status+"&tgl_aktif="+tgl_aktif+"&tgl_nonaktif="+tgl_nonaktif,
            cache:false,
            success:function(msg){            
                data=msg.split("|");
                if(data[0]=="nihil"){
                    kirim_data_jabatan();
                    kosong();                
                }else{
                    alert('Jabatan ini sudah ditambahkan');
                    kosong();                      
                }                
            }
        })    
    }
}
function kosong(args){
  $("#id_jabatan").val("");
  $("#id_dealer").val("");
  $("#tanggal4").val("");   
  $("#tanggal5").val("");   
}
function hapus_jabatan(a,b){ 
    var id_karyawan_detail  = a;   
    var id_jabatan   = b;       
    $.ajax({
        url : "<?php echo site_url('master/karyawan/delete_jabatan')?>",
        type:"POST",
        data:"id_karyawan_detail="+id_karyawan_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_jabatan();
            }
        }
    })
}

function bulk_delete(){
  var list_id = [];
  $(".data-check:checked").each(function() {
    list_id.push(this.value);
  });
  if(list_id.length > 0){
    if(confirm('Are you sure delete this '+list_id.length+' data?'))
      {
        $.ajax({
          type: "POST",
          data: {id:list_id},
          url: "<?php echo site_url('master/karyawan/ajax_bulk_delete')?>",
          dataType: "JSON",
          success: function(data)
          {
            if(data.status){
              window.location.reload();
            }else{
              alert('Failed.');
            }                  
          },
          error: function (jqXHR, textStatus, errorThrown){
            alert('Error deleting data');
          }
        });
      }
    }else{
      alert('no data selected');
  }
}
</script>
<script>
function get_div(){
  var id_divisi = $("#id_divisi").val();  
  $.ajax({
    url : "<?php echo site_url('master/karyawan/get_dep')?>",
    type:"POST",
    data:"id_divisi="+id_divisi,      
    cache:false,   
    success:function(msg){            
      $("#id_dep").html(msg);      
    }
  })  
}
function get_dep(){
  var id_department = $("#id_dep").val();  
  $.ajax({
    url : "<?php echo site_url('master/karyawan/get_sub_dep')?>",
    type:"POST",
    data:"id_department="+id_department,      
    cache:false,   
    success:function(msg){            
      $("#id_sub_department").html(msg);      
    }
  })  
}
</script>