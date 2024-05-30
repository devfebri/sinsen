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
<?php if ($set=='insert'): ?>
  <body onload="GetDataNosin()">
<?php endif ?>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Kontrol Unit</li>
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
          <a href="h1/rfs_pinjaman">
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
            <butto class="close" data-dismiss="alert">
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
            <form class="form-horizontal" action="h1/rfs_pinjaman/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Pinjam</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" placeholder="Tanggal Pinjam" id="tgl_pinjaman" value="<?php echo date("Y-m-d") ?>" readonly class="form-control">
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="gudang" id="gudang" onchange="GetDataNosin()">
                      <?php $gudang = $this->m_admin->getAll("ms_gudang"); ?>
                      <option>-- Choose --</option>
                      <?php foreach ($gudang->result() as $gd): ?>
                        <option value="<?php echo $gd->id_gudang ?>"><?php echo $gd->gudang ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>                                                                  
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" name="keterangan" id="keterangan" placeholder="Keterangan" class="form-control">
                  </div>                                    
                </div>
                <!--
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label"></label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="ksu" id="ksu" value="1" checked>
                      KSU
                    </div>
                  </div>                  
                </div>  -->               
              <!--  <table class='table table-bordered table-hover' id="example1">
                  <tr>                    
                    <th>tipe</th>
                    <th>Warna</th>
                    <th>Kode Item</th>                    
                    <th>No Mesin</th>
                    <th>No Rangka</th>                    
                    <th width="1%">Aksi</th>
                  </tr>
                  <tr>                    
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>                                                        
                  </tr>
                  
                </table>-->
                <div id="tampil_detail"></div>
                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="button" name="save" value="save" class="btn btn-info btn-flat btn_submit"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<?php 
    }elseif($set=="konfirmasi"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/rfs_pinjaman">
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
            <butto class="close" data-dismiss="alert">
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
            <form class="form-horizontal" action="h1/rfs_pinjaman/konfirmasi" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <?php $dt_p = $dt_p->row(); ?>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Pinjam</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_rfs_pinjaman" value="<?php echo $dt_p->id_rfs_pinjaman ?>">
                    <input type="text" name="periode_awal" placeholder="Tanggal Pinjam" id="tgl_pinjaman" value="<?php echo $dt_p->tgl_pinjaman ?>" readonly class="form-control">
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="gudang" id="gudang" onchange="GetDataNosin()">
                      <?php $gudang = $this->m_admin->getAll("ms_gudang"); ?>
                      <option>-- Choose --</option>
                      <?php foreach ($gudang->result() as $gd): ?>
                        <option value="<?php echo $gd->id_gudang ?>"><?php echo $gd->gudang ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>                                                                  
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" name="keterangan" id="keterangan" placeholder="Keterangan" class="form-control" value="<?php echo $dt_p->keterangan ?>">
                  </div>                                    
                </div>
                <table id="example2" class="table order-list" border="0">
                  <thead>
                    <tr>
                      <th width="15%">No. Mesin</th>
                      <th width="20 %">Tipe</th>      
                      <th width="20%">Warna</th>                      
                      <th width="10%">Kode Item</th>   
                      <th width="35%">KSU</th>  
                      <th>Konfirmasi</th>                 
                    </tr>
                  </thead>              
                  <?php 
                  $no=1;
                  foreach ($dt_pinjaman->result() as $rfs){ ?>
                    <?php $ksu = $this->db->query("SELECT GROUP_CONCAT(' ',ksu) as ksu FROM tr_rfs_pinjaman_detail_ksu join ms_ksu on tr_rfs_pinjaman_detail_ksu.id_ksu = ms_ksu.id_ksu WHERE id_rfs_pinjaman = '$rfs->id_rfs_pinjaman' AND tr_rfs_pinjaman_detail_ksu.checked=1")->row(); ?>
                    <tr>
                      <td><?php echo $rfs->no_mesin ?></td>
                      <td><?php echo $rfs->tipe_motor ?>-<?php echo $rfs->tipe_ahm ?></td>
                      <td><?php echo $rfs->id_warna ?>-<?php echo $rfs->warna ?></td>
                      <td><?php echo $rfs->id_item ?></td>    
                      <td> <?php echo $ksu->ksu ?></td>  
                      <td>
                        <input type="checkbox" name="konfirmasi_<?php echo $no ?>" value="" >
                        <input type="hidden" name="count" value="<?php echo $no ?>">
                        <input type="hidden" name="id_rfs_pinjaman_detail_<?php echo $no ?>" value="<?php echo $rfs->id_rfs_pinjaman_detail ?>">
                      </td>                
                    </tr>                  
                <?php $no++; } ?> 
              </table>
                
                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to confirm ?')" name="submit_konfirmasi" value="submit_konfirmasi" class="btn btn-primary btn-flat"><i class="fa fa-save"></i> Konfirmasi</button>
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
      $row = $dt_p->row();
    ?>
     <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/rfs_pinjaman">
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
            <butto class="close" data-dismiss="alert">
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
            <form class="form-horizontal" action="h1/rfs_pinjaman/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Pinjam</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" placeholder="Tanggal Pinjam" id="tgl_pinjaman" value="<?php echo $row->tgl_pinjaman ?>" readonly class="form-control">
                  </div>                 
                </div>                                     
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" name="keterangan" id="keterangan" value="<?php echo $row->keterangan ?>" placeholder="Keterangan" class="form-control">
                  </div>                                    
                </div>       
                <?php /* ?> <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label"></label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->ksu=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="ksu" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="ksu" value="1">
                      <?php } ?>
                      KSU
                    </div>
                  </div>                  
                </div>      
                <?php */ ?>   
                <table id="example2" class="table order-list" border="0">
                  <thead>
                    <tr>
                      <th width="15%">No. Mesin</th>
                      <th width="20%">Tipe</th>      
                      <th width="20%">Warna</th>                      
                      <th width="10%">Kode Item</th>   
                      <th width="40%">KSU</th>                   
                      <th width="20%">Konfirmasi</th>                   
                    </tr>
                  </thead>              
                  <?php 
                  foreach ($dt_pinjaman->result() as $rfs){ ?>
                    <?php $ksu = $this->db->query("SELECT GROUP_CONCAT(' ',ksu) as ksu FROM tr_rfs_pinjaman_detail_ksu join ms_ksu on tr_rfs_pinjaman_detail_ksu.id_ksu = ms_ksu.id_ksu WHERE id_rfs_pinjaman = '$rfs->id_rfs_pinjaman' AND checked=1")->row(); ?>
                    <tr>
                      <td><?php echo $rfs->no_mesin ?></td>
                      <td><?php echo $rfs->tipe_motor ?>-<?php echo $rfs->tipe_ahm ?></td>
                      <td><?php echo $rfs->id_warna ?>-<?php echo $rfs->warna ?></td>
                      <td><?php echo $rfs->id_item ?></td>    
                      <td> <?php echo $ksu->ksu ?></td>                  
                      <td> <?php echo $rfs->konfirmasi ?></td>                  
                    </tr>                  
                <?php } ?> 
              </table>
                
              </div>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="pengembalian"){
      $row = $dt_p->row();
    ?>
     <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/rfs_pinjaman">
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
            <butto class="close" data-dismiss="alert">
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
            <form class="form-horizontal" action="h1/rfs_pinjaman/save_pengembalian" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Pinjam</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" placeholder="Tanggal Pinjam" id="tgl_pinjaman" value="<?php echo $row->tgl_pinjaman ?>" readonly class="form-control">
                  </div>                 
                </div>                                     
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" readonly name="keterangan" id="keterangan" value="<?php echo $row->keterangan ?>" placeholder="Keterangan" class="form-control">
                  </div>                                    
                </div>       
                <?php /* ?> <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label"></label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->ksu=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="ksu" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="ksu" value="1">
                      <?php } ?>
                      KSU
                    </div>
                  </div>                  
                </div>      
                <?php */ ?>   
                <table id="example2" class="table order-list" border="0">
                  <thead>
                    <tr>
                      <th width="15%">No. Mesin</th>
                      <th width="20%">Tipe</th>      
                      <th width="20%">Warna</th>                      
                      <th width="10%">Kode Item</th>   
                      <th width="40%">KSU</th>                   
                      <th width="20%">Terima</th>                   
                    </tr>
                  </thead>              
                  <?php 
                  $no=1;
                  foreach ($dt_pinjaman->result() as $rfs){ 
                    $jum = $dt_pinjaman->num_rows();
                    ?>
                    <?php $ksu = $this->db->query("SELECT GROUP_CONCAT(' ',ksu) as ksu FROM tr_rfs_pinjaman_detail_ksu join ms_ksu on tr_rfs_pinjaman_detail_ksu.id_ksu = ms_ksu.id_ksu WHERE id_rfs_pinjaman = '$rfs->id_rfs_pinjaman' AND checked=1")->row(); ?>
                    <tr>
                      <td><?php echo $rfs->no_mesin ?></td>
                      <td><?php echo $rfs->tipe_motor ?>-<?php echo $rfs->tipe_ahm ?></td>
                      <td><?php echo $rfs->id_warna ?>-<?php echo $rfs->warna ?></td>
                      <td><?php echo $rfs->id_item ?></td>    
                      <td> <?php echo $ksu->ksu ?></td>                  
                      <td>
                        <input type="hidden" name="no_mesin_<?php echo $no ?>" value="<?php echo $rfs->no_mesin ?>">
                        <input type="hidden" name="id_rfs_pinjaman" value="<?php echo $rfs->id_rfs_pinjaman ?>">
                        <input type="hidden" name="jum" value="<?php echo $jum ?>">
                        <select name="terima_<?php echo $no ?>" class="form-control">
                          <option value=''>- choose -</option>
                          <option>RFS</option>
                          <option>NRFS</option>
                        </select>
                      </td>                  
                    </tr>                  
                <?php $no++; } ?> 
              </table>
              
              </div>
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="submit_konfirmasi" value="submit_konfirmasi" class="btn btn-primary btn-flat"><i class="fa fa-save"></i> Save All</button>                  
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
          <a href="h1/rfs_pinjaman/add">            
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
              <th>No Pinjaman</th>                        
              <th>Tgl Pinjaman</th>
              <th>Keterangan Pinjaman</th>
              <th>Status</th>                            
              <th width="14%" align="center">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
           $no=1; 
          foreach($rfs_pinjaman->result() as $row) {    
            $approval = $this->m_admin->set_tombol($id_menu,$group,'approval');
            $print = $this->m_admin->set_tombol($id_menu,$group,'print');

          $tomb='';                         
            if($row->status == 'Waiting Approval'){
              if ($row->status_cetak=='' or $row->status_cetak ==null) {
                $tomb = "<a $approval href='h1/rfs_pinjaman/approve?id=$row->id_rfs_pinjaman' class='btn btn-success btn-flat btn-xs'>Approve</a>                                
                <a $approval href='h1/rfs_pinjaman/reject?id=$row->id_rfs_pinjaman' class='btn btn-danger btn-flat btn-xs'>Reject</a>";
              }
            }elseif($row->status == 'approved'){
              $tomb = "<a href='h1/rfs_pinjaman/pengembalian?id=$row->id_rfs_pinjaman' class='btn btn-success btn-flat btn-xs'>Pengembalian</a>
                <a href='h1/rfs_pinjaman/cetak_bast?id=$row->id_rfs_pinjaman' class='btn btn-warning btn-flat btn-xs'>Cetak BAST</a>";              
            }else{
              if ($row->status_cetak=='cetak_pl') {
                $tomb = "<a href='h1/rfs_pinjaman/konfirmasi?id=$row->id_rfs_pinjaman' class='btn btn-primary btn-flat btn-xs'>Konfirmasi PL</a>";
              }elseif ($row->status_cetak=='konfirmasi') {
                $tomb = "<a $print href='h1/rfs_pinjaman/cetak_sj?id=$row->id_rfs_pinjaman' class='btn btn-default btn-flat btn-xs' target='_blank'>Cetak Surat Jalan</a>";
              }elseif ($row->status_cetak=='cetak_sj') {
                $tomb = "";              
              }else{
                if ($row->status=='rejected') {
                  $tomb='';
                }else{
                  $tomb = "<a $print href='h1/rfs_pinjaman/cetak_pl?id=$row->id_rfs_pinjaman' class='btn btn-warning btn-flat btn-xs' target='_blank'>Cetak PL</a>";
                }
              }
            }
          echo "          
            <tr>
              <td>$no</td>              
              <td>
                <a href='h1/rfs_pinjaman/detail?id=$row->id_rfs_pinjaman'>
                  $row->id_rfs_pinjaman
                </a>
              </td>              
              <td>$row->tgl_pinjaman</td>              
              <td>$row->keterangan</td>                           
              <td>$row->status</td>                                          
              <td align='center'>$tomb</td>";                                      
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
  function GetDataNosin() { 

        var id_gudang = $("#gudang option:selected").val();
       //$("#tampil_detail").load('<?php echo site_url('h1/rfs_pinjaman/t_detail') ?>');
       $.ajax({
       		   beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('h1/rfs_pinjaman/t_detail');?>",
               type:"POST",
               data:"id_gudang="+id_gudang,
               cache:false,
               success:function(html){
               	  $('#loading-status').hide();
                  $("#tampil_detail").html(html);
               }
          });
  }

 $(document).on("click",".btn_submit",function(){
        var id_gudang = $("#gudang option:selected").val();
        var id_rfs_pinjaman = $(".myTable1 .id_rfs_pinjaman").val();
        var tgl_pinjaman = $("#tgl_pinjaman").val();
        var keterangan = $("#keterangan").val();
        var ksu = $("#ksu").val();
       $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('h1/rfs_pinjaman/save');?>",
               type:"POST",
               data:"id_rfs_pinjaman="+id_rfs_pinjaman
                  +"&ksu="+ksu
                  +"&keterangan="+keterangan
                  +"&tgl_pinjaman="+tgl_pinjaman,
               cache:false,
               success:function(html){
               	  $('#loading-status').hide();
                  window.location.replace("<?php echo site_url('h1/rfs_pinjaman/add') ?>");
               },
               statusCode: {
            500: function() {
              $('#loading-status').hide();
              alert('Terjadi Kesalahan Saat Menambahkan Data');
            }
          }
          });
})
</script>