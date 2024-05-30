<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<body onload="take_kel()">
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Master Data</li>
    <li class="">Dealer</li>
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
          <a href="master/pengangkatan_dealer">
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
            <form class="form-horizontal" action="master/pengangkatan_dealer/save" method="post" enctype="multipart/form-data">
              <div class="box-body">  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.SP3D</label>
                  <div class="col-sm-4">
                    <input type="text" autofocus required class="form-control" placeholder="No.SP3D" name="no_sp3d">                                        
                  </div>
                </div>                                                                                                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Mulai</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal2" placeholder="Tgl Mulai" name="tgl_mulai">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Selesai</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal3" placeholder="Tgl Selesai" name="tgl_selesai">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="ID Dealer" name="id_dealer">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Diangkat</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal" placeholder="Tgl Diangkat" name="tgl_diangkat">                    
                  </div>
                </div>                                 
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Jaringan</label>            
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="h1" value="1" checked>
                      H1
                    </div>
                  </div>                  
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="h2" value="1" checked>
                      H2
                    </div>
                  </div>                  
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="h3" value="1" checked>
                      H3
                    </div>
                  </div>   
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pemilik</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal" placeholder="Nama Pemilik" name="pemilik">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Penanggung Jawab</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="" placeholder="Penanggung Jawab" name="penanggung_jawab">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat 1</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="tanggal" placeholder="Alamat 1" name="alamat1">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat 2</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="" placeholder="Alamat 2" name="alamat2">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="kelurahan" onclick="showModalKelurahan()" placeholder="Kelurahan" readonly>
                    <input type="hidden" class="form-control" id="id_kelurahan" name="id_kelurahan" readonly>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                  <div class="col-sm-4">
                    <input class="form-control" type="hidden" id="id_kecamatan" name="id_kecamatan" readonly>                    
                    <input class="form-control" type="text" id="kecamatan" readonly>                    
                  </div>                  
                </div>
                 <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kabupaten/Kota</label>
                  <div class="col-sm-4">
                    <input class="form-control" type="hidden" id="id_kabupaten" name="id_kabupaten" readonly>                    
                    <input class="form-control" type="text" id="kabupaten" readonly>                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                  <div class="col-sm-4">
                    <input class="form-control" type="hidden" id="id_provinsi" name="id_provinsi" readonly>                    
                    <input class="form-control" type="text" id="provinsi" readonly>                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Pos</label>
                  <div class="col-sm-4">
                    <input name="kode_pos" onkeypress="return number_only(event)" type="text" class="form-control" placeholder="Kode Pos" autocomplete="off" maxlength="5" id="kode_pos" />                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.Telp</label>
                  <div class="col-sm-4">
                    <input name="no_telp" type="text" onkeypress="return number_only(event)" class="form-control" placeholder="No.Telp">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No.Fax</label>
                  <div class="col-sm-4">
                    <input name="no_fax" type="text" onkeypress="return number_only(event)" class="form-control" placeholder="No.Fax">
                  </div>                 
                </div> 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email Pribadi</label>
                  <div class="col-sm-4">
                    <input type="email" class="form-control" id="inputEmail3" placeholder="Email Pribadi" name="mail_pribadi">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Email HEPS</label>
                  <div class="col-sm-4">
                    <input type="email" class="form-control" id="inputEmail3" placeholder="Email HEPS" name="mail_heps">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email ACS</label>
                  <div class="col-sm-4">
                    <input type="email" class="form-control" id="inputEmail3" placeholder="Email ACS" name="mail_acs">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Email USKM</label>
                  <div class="col-sm-4">
                    <input type="email" class="form-control" id="inputEmail3" placeholder="Email USKM" name="mail_uskm">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email ASMD</label>
                  <div class="col-sm-4">
                    <input type="email" class="form-control" id="inputEmail3" placeholder="Email ASMD" name="mail_asmd">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Email ANTENA</label>
                  <div class="col-sm-4">
                    <input type="email" class="form-control" id="inputEmail3" placeholder="Email ANTENA" name="mail_antena">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Bintang</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Status Bintang" name="status_bintang">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No.Surat</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="No.Surat" name="no_surat">
                  </div>
                </div>
                <div class="form-group">               
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      Active
                    </div>
                  </div>                  
                </div>                                                                                                                                                                                                                                                                   
              </div><!-- /.box-body -->
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
      $row = $dt_pengangkatan_dealer->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/pengangkatan_dealer">
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
            <form class="form-horizontal" action="master/pengangkatan_dealer/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->no_sp3d ?>" />
              <div class="box-body">                                                                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.SP3D</label>
                  <div class="col-sm-4">
                    <input type="text" autofocus value="<?php echo $row->no_sp3d ?>" required class="form-control" placeholder="No.SP3D" name="no_sp3d">                                        
                  </div>
                </div>                                                                                                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Mulai</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->tgl_mulai ?>" class="form-control" id="tanggal2" placeholder="Tgl Mulai" name="tgl_mulai">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Selesai</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->tgl_selesai ?>" class="form-control" id="tanggal3" placeholder="Tgl Selesai" name="tgl_selesai">                    
                  </div>
                </div>                                                                                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->id_dealer ?>" placeholder="ID Dealer" name="id_dealer">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Diangkat</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->tgl_diangkat ?>" class="form-control" id="tanggal" placeholder="Tgl Diangkat" name="tgl_diangkat">                    
                  </div>
                </div>                                 
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Jaringan</label>            
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->h1=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="h1" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="h1" value="1">                      
                      <?php } ?>
                      H1
                    </div>
                  </div>                  
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->h2=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="h2" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="h2" value="1">                      
                      <?php } ?>
                      H2
                    </div>
                  </div>                  
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->h3=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="h3" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="h3" value="1">                      
                      <?php } ?>
                      H3
                    </div>
                  </div>                                 
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pemilik</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->pemilik ?>" class="form-control" id="tanggal" placeholder="Nama Pemilik" name="pemilik">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Penanggung Jawab</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->penanggung_jawab ?>" class="form-control" id="" placeholder="Penanggung Jawab" name="penanggung_jawab">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat 1</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->alamat1 ?>" class="form-control" id="tanggal" placeholder="Alamat 1" name="alamat1">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat 2</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->alamat2 ?>" class="form-control" id="" placeholder="Alamat 2" name="alamat2">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                  <div class="col-sm-4">
                    <?php $kelurahan = $this->db->get_where('ms_kelurahan',['id_kelurahan'=>$row->id_kelurahan])>row() ?>
                    <input type="text" class="form-control" id="kelurahan" onclick="showModalKelurahan()" placeholder="Kelurahan" readonly value="<?= $kelurahan ?>">
                    <input type="hidden" class="form-control" id="id_kelurahan" name="id_kelurahan" readonly value="<?= $val->id_kelurahan ?>">
                  </div>
                 
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                  <div class="col-sm-4">
                    <input class="form-control" value="<?php echo $row->id_kecamatan ?>" name="id_kecamatan" type="hidden" id="id_kecamatan" readonly>                    
                    <input class="form-control" type="text" id="kecamatan" readonly>                    
                  </div>                  
                </div>
                 <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kabupaten/Kota</label>
                  <div class="col-sm-4">
                    <input class="form-control" value="<?php echo $row->id_kabupaten ?>" type="hidden" name="id_kabupaten" id="id_kabupaten" readonly>                    
                    <input class="form-control" type="text" id="kabupaten" readonly>                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                  <div class="col-sm-4">
                    <input class="form-control" value="<?php echo $row->id_provinsi ?>" type="hidden" name="id_provinsi" id="id_provinsi" readonly>                    
                    <input class="form-control" type="text" id="provinsi" readonly>                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Pos</label>
                  <div class="col-sm-4">
                    <input name="kode_pos" value="<?php echo $row->kode_pos ?>" onkeypress="return number_only(event)" type="text"  class="form-control" id="kode_pos" placeholder="Kode Pos" autocomplete="off" maxlength="5"/>                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.Telp</label>
                  <div class="col-sm-4">
                    <input name="no_telp" type="text" onkeypress="return number_only(event)" value="<?php echo $row->no_telp ?>" class="form-control" placeholder="No.Telp">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No.Fax</label>
                  <div class="col-sm-4">
                    <input name="no_fax" type="text" onkeypress="return number_only(event)" value="<?php echo $row->no_fax ?>" class="form-control" placeholder="No.Fax">
                  </div>                 
                </div> 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email Pribadi</label>
                  <div class="col-sm-4">
                    <input type="email" value="<?php echo $row->mail_pribadi ?>" class="form-control" id="inputEmail3" placeholder="Email Pribadi" name="mail_pribadi">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Email HEPS</label>
                  <div class="col-sm-4">
                    <input type="email" value="<?php echo $row->mail_heps ?>" class="form-control" id="inputEmail3" placeholder="Email HEPS" name="mail_heps">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email ACS</label>
                  <div class="col-sm-4">
                    <input type="email" value="<?php echo $row->mail_acs ?>" class="form-control" id="inputEmail3" placeholder="Email ACS" name="mail_acs">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Email USKM</label>
                  <div class="col-sm-4">
                    <input type="email" value="<?php echo $row->mail_uskm ?>" class="form-control" id="inputEmail3" placeholder="Email USKM" name="mail_uskm">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email ASMD</label>
                  <div class="col-sm-4">
                    <input type="email" value="<?php echo $row->mail_asmd ?>" class="form-control" id="inputEmail3" placeholder="Email ASMD" name="mail_asmd">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Email ANTENA</label>
                  <div class="col-sm-4">
                    <input type="email" value="<?php echo $row->mail_antena ?>" class="form-control" id="inputEmail3" placeholder="Email ANTENA" name="mail_antena">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Bintang</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->status_bintang ?>" class="form-control" id="inputEmail3" placeholder="Status Bintang" name="status_bintang">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No.Surat</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->no_surat ?>" class="form-control" id="inputEmail3" placeholder="No.Surat" name="no_surat">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>                  
                  <div class="col-sm-3">
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
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Update</button>                
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="detail"){
      $row = $dt_pengangkatan_dealer->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/pengangkatan_dealer">
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
            <form class="form-horizontal" action="master/pengangkatan_dealer/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->no_sp3d ?>" />
              <div class="box-body">                                                                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.SP3D</label>
                  <div class="col-sm-4">
                    <input type="text" disabled value="<?php echo $row->no_sp3d ?>" autofocus required class="form-control" placeholder="No.SP3D" name="no_sp3d">                                        
                  </div>
                </div>                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Mulai</label>
                  <div class="col-sm-4">
                    <input type="text" disabled value="<?php echo $row->tgl_mulai ?>" class="form-control" id="tanggal2" placeholder="Tgl Mulai" name="tgl_mulai">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Selesai</label>
                  <div class="col-sm-4">
                    <input type="text" disabled value="<?php echo $row->tgl_selesai ?>" class="form-control" id="tanggal3" placeholder="Tgl Selesai" name="tgl_selesai">                    
                  </div>
                </div>                                                                                                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->id_dealer ?>" placeholder="ID Dealer" name="id_dealer">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Diangkat</label>
                  <div class="col-sm-4">
                    <input disabled type="text" value="<?php echo $row->tgl_diangkat ?>" class="form-control" id="tanggal" placeholder="Tgl Diangkat" name="tgl_diangkat">                    
                  </div>
                </div>                                 
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Jaringan</label>            
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->h1=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="h1" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="h1" value="1">                      
                      <?php } ?>
                      H1
                    </div>
                  </div>                  
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->h2=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="h2" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="h2" value="1">                      
                      <?php } ?>
                      H2
                    </div>
                  </div>                  
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->h3=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="h3" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="h3" value="1">                      
                      <?php } ?>
                      H3
                    </div>
                  </div>                                 
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pemilik</label>
                  <div class="col-sm-4">
                    <input disabled type="text" value="<?php echo $row->pemilik ?>" class="form-control" id="tanggal" placeholder="Nama Pemilik" name="pemilik">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Penanggung Jawab</label>
                  <div class="col-sm-4">
                    <input disabled type="text" value="<?php echo $row->penanggung_jawab ?>" class="form-control" id="" placeholder="Penanggung Jawab" name="penanggung_jawab">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat 1</label>
                  <div class="col-sm-10">
                    <input disabled type="text" value="<?php echo $row->alamat1 ?>" class="form-control" id="tanggal" placeholder="Alamat 1" name="alamat1">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat 2</label>
                  <div class="col-sm-10">
                    <input disabled type="text" value="<?php echo $row->alamat2 ?>" class="form-control" id="" placeholder="Alamat 2" name="alamat2">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                <div class="col-sm-4">
                    <?php $kelurahan = $this->db->get_where('ms_kelurahan',['id_kelurahan'=>$row->id_kelurahan])>row() ?>
                    <input type="text" class="form-control" id="kelurahan" onclick="showModalKelurahan()" placeholder="Kelurahan" readonly value="<?= $kelurahan ?>">
                    <input type="hidden" class="form-control" id="id_kelurahan" name="id_kelurahan" readonly value="<?= $val->id_kelurahan ?>">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                  <div class="col-sm-4">
                    <input class="form-control" value="<?php echo $row->id_kecamatan ?>" name="id_kecamatan" type="hidden" id="id_kecamatan" readonly>                    
                    <input class="form-control" type="text" id="kecamatan" readonly>                    
                  </div>                  
                </div>
                 <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kabupaten/Kota</label>
                  <div class="col-sm-4">
                    <input class="form-control" value="<?php echo $row->id_kabupaten ?>" type="hidden" name="id_kabupaten" id="id_kabupaten" readonly>                    
                    <input class="form-control" type="text" id="kabupaten" readonly>                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                  <div class="col-sm-4">
                    <input class="form-control" value="<?php echo $row->id_provinsi ?>" type="hidden" name="id_provinsi" id="id_provinsi" readonly>                    
                    <input class="form-control" type="text" id="provinsi" readonly>                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Pos</label>
                  <div class="col-sm-4">
                    <input disabled name="kode_pos" value="<?php echo $row->kode_pos ?>" onkeypress="return number_only(event)" type="text" class="form-control" id="kode_pos" placeholder="Kode Pos" autocomplete="off" maxlength="5"/>                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.Telp</label>
                  <div class="col-sm-4">
                    <input disabled name="no_telp" type="text" onkeypress="return number_only(event)" value="<?php echo $row->no_telp ?>" class="form-control" placeholder="No.Telp">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No.Fax</label>
                  <div class="col-sm-4">
                    <input disabled name="no_fax" type="text" onkeypress="return number_only(event)" value="<?php echo $row->no_fax ?>" class="form-control" placeholder="No.Fax">
                  </div>                 
                </div> 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email Pribadi</label>
                  <div class="col-sm-4">
                    <input disabled type="email" value="<?php echo $row->mail_pribadi ?>" class="form-control" id="inputEmail3" placeholder="Email Pribadi" name="mail_pribadi">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Email HEPS</label>
                  <div class="col-sm-4">
                    <input disabled type="email" value="<?php echo $row->mail_heps ?>" class="form-control" id="inputEmail3" placeholder="Email HEPS" name="mail_heps">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email ACS</label>
                  <div class="col-sm-4">
                    <input disabled type="email" value="<?php echo $row->mail_acs ?>" class="form-control" id="inputEmail3" placeholder="Email ACS" name="mail_acs">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Email USKM</label>
                  <div class="col-sm-4">
                    <input disabled type="email" value="<?php echo $row->mail_uskm ?>" class="form-control" id="inputEmail3" placeholder="Email USKM" name="mail_uskm">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email ASMD</label>
                  <div class="col-sm-4">
                    <input disabled type="email" value="<?php echo $row->mail_asmd ?>" class="form-control" id="inputEmail3" placeholder="Email ASMD" name="mail_asmd">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Email ANTENA</label>
                  <div class="col-sm-4">
                    <input disabled type="email" value="<?php echo $row->mail_antena ?>" class="form-control" id="inputEmail3" placeholder="Email ANTENA" name="mail_antena">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Bintang</label>
                  <div class="col-sm-4">
                    <input disabled type="text" value="<?php echo $row->status_bintang ?>" class="form-control" id="inputEmail3" placeholder="Status Bintang" name="status_bintang">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No.Surat</label>
                  <div class="col-sm-4">
                    <input disabled type="text" value="<?php echo $row->no_surat ?>" class="form-control" id="inputEmail3" placeholder="No.Surat" name="no_surat">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>                  
                  <div class="col-sm-3">
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
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <a href="master/pengangkatan_dealer/edit?id=<?php echo $row->no_sp3d ?>">
                    <button type="button" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Edit Data</button>                
                  </a>
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
          <a href="master/pengangkatan_dealer/add">
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
              <th>No.SP3D</th>
              <th>Dealer</th>
              <th>Pemilik</th>
              <th>Penanggung Jawab</th>
              <th>No.Telp</th>
              <th>Email</th>
              <th>H1</th>
              <th>H2</th>
              <th>H3</th> 
              <th>Active</th>             
              <th width="15%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_pengangkatan_dealer->result() as $row) {       
            if($row->h1=='1') $h1 = "<i class='glyphicon glyphicon-ok'></i>";
                else $h1 = "";
            if($row->h2=='1') $h2 = "<i class='glyphicon glyphicon-ok'></i>";
                else $h2 = "";
            if($row->h3=='1') $h3 = "<i class='glyphicon glyphicon-ok'></i>";
                else $h3 = "";
            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->no_sp3d</td>              
              <td>$row->id_dealer</td>              
              <td>$row->pemilik</td>
              <td>$row->penanggung_jawab</td>              
              <td>$row->no_telp</td>              
              <td>$row->mail_pribadi</td>              
              <td>$h1</td>
              <td>$h2</td>
              <td>$h3</td>              
              <td>$active</td>              
              <td>";
              ?>
                <a <?php echo $this->m_admin->set_tombol($id_menu,$group,"delete"); ?> data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/pengangkatan_dealer/delete?id=<?php echo $row->no_sp3d ?>"><i class="fa fa-trash-o"></i></a>
                <a <?php echo $this->m_admin->set_tombol($id_menu,$group,"edit"); ?> data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/pengangkatan_dealer/edit?id=<?php echo $row->no_sp3d ?>'><i class='fa fa-edit'></i></a>
                <a data-toggle='tooltip' title="View Data" href="master/pengangkatan_dealer/view?id=<?php echo $row->no_sp3d ?>"><button type='button' class="btn btn-info btn-sm btn-flat" title="View"><i class="fa fa-eye"></i></button></a>                
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
<div class="modal fade modalKelurahan" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Data Kelurahan</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="no_mesin_part">
        <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_kelurahan" style="width: 100%">
                  <thead>
                  <tr>
                      <th>Kelurahan</th>
                      <th>Kecamatan</th>
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
                      $('#tbl_kelurahan').DataTable({
                          processing: true,
                          serverSide: true,
                          "language": {                
                                  "infoFiltered": ""
                              },
                          order: [],
                          ajax: {
                              url: "<?= base_url('master/pengangkatan_dealer/fetch_kelurahan') ?>",
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
<script type="text/javascript">
function showModalKelurahan() {
  $('.modalKelurahan').modal('show');
}
function pilihKelurahan(kel) {
    $('#id_kelurahan').val(kel.id_kelurahan);
    $('#kelurahan').val(kel.kelurahan);
    $('#kode_pos').val(kel.kode_pos);
    take_kel();
  }
function take_kel(){
  var id_kelurahan = $("#id_kelurahan").val();  
  $.ajax({
    url : "<?php echo site_url('master/pengangkatan_dealer/get_kelurahan')?>",
    type:"POST",
    data:"id_kelurahan="+id_kelurahan,      
    cache:false,   
    success:function(msg){    
      data=msg.split("|");
      if(data[0]=="ok"){          
        $("#id_kecamatan").val(data[1]);                        
        $("#kecamatan").val(data[2]);                        
        $("#id_kabupaten").val(data[3]);                        
        $("#kabupaten").val(data[4]);                        
        $("#id_provinsi").val(data[5]);                        
        $("#provinsi").val(data[6]);                        
      }else{
        $("#id_kecamatan").val("");                        
        $("#kecamatan").val("");                        
        $("#id_kabupaten").val("");                        
        $("#kabupaten").val("");                        
        $("#id_provinsi").val("");                        
        $("#provinsi").val("");                        
      }        
      //$("#id_kecamatan").html(msg);         
      // $("#id_kabupaten").html(msg);         
      // $("#id_provinsi").html(msg);               
    }
  }) 
}
function take_kab(){
  var id_kabupaten = $("#id_kabupaten").val();  
  $.ajax({
    url : "<?php echo site_url('master/pengangkatan_dealer/get_kabupaten')?>",
    type:"POST",
    data:"id_kabupaten="+id_kabupaten,      
    cache:false,   
    success:function(msg){            
      $("#id_kecamatan").html(msg);         
    }
  }) 
}
function take_kec(){
  var id_kecamatan = $("#id_kecamatan").val();  
  $.ajax({
    url : "<?php echo site_url('master/pengangkatan_dealer/get_kecamatan')?>",
    type:"POST",
    data:"id_kecamatan="+id_kecamatan,      
    cache:false,   
    success:function(msg){            
      $("#id_kelurahan").html(msg);         
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
          url: "<?php echo site_url('master/pengangkatan_dealer/ajax_bulk_delete')?>",
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