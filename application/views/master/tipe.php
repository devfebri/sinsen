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
    <li class="">Unit</li>    
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
  <?php 
    if($set=="add_part_ev_qty"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/tipe/part_ev">
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
            <form method="POST" role="form" enctype="multipart/form-data" action="master/tipe/save_part_qty" class="form-horizontal form-groups-bordered">
                                   
                <div class="form-group">                
                  <label for="field-1" class="col-sm-2 control-label">Tipe Kendaraan</label>            
                  <div class="col-sm-4">
                    <select class="form-control" name="id_tipe_kendaraan">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_tipe_kendaraan->result() as $val) {
                        echo "
                        <option value='$val->id_tipe_kendaraan'>$val->id_tipe_kendaraan ($val->tipe_ahm)</option>;
                        ";
                      }
                      ?>                      
                    </select>
                  </div>
                </div>    

                                           
                <div class="form-group">                
                  <label for="field-1" class="col-sm-2 control-label">Tipe Part</label>            
                  <div class="col-sm-4">
                    <select class="form-control select-2" name="id_part">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_part->result() as $val) {
                        echo "
                        <option value='$val->part_id'>$val->part_id ($val->part_desc)</option>;
                        ";
                      }
                      ?>                      
                    </select>
                  </div>
                </div>    
    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">QTY</label>            
                  <div class="col-sm-4">
                    <input type="number" class="form-control" id="field-1" placeholder="Qty" name="qty">
                  </div>
                      
                </div>                
                <div class="form-group">                  
                  <label for="field-1" class="col-sm-2 control-label"></label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="form-control flat-red" name="active" value="1" checked>
                      Active
                    </div>
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
    }
    else if($set=="insert"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/tipe">
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
            <form method="POST" role="form" enctype="multipart/form-data" action="master/tipe/save" class="form-horizontal form-groups-bordered">
              <div class="box-body">     
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Tipe Kendaraan</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="field-1" placeholder="Kode Tipe Kendaraan" name="id_tipe_kendaraan">
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Nama Tipe Kendaraan</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="field-1" placeholder="Nama Tipe Kendaraan" name="tipe_ahm">
                  </div>                   
                </div>                                                                                                                                                              
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Deskripsi Tipe Kendaraan (AHM)</label>            
                  <div class="col-sm-10">
                    <textarea class="form-control" id="textarea-full" name="deskripsi_ahm" rows="2">
                    </textarea>
                  </div>                   
                </div>                
                <!--div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Deskripsi Tipe Kendaraan (Customer)</label>            
                  <div class="col-sm-10">
                    <textarea class="form-control" name="tipe_customer">
                    </textarea>
                  </div>                   
                </div-->
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Deskripsi Tipe Kendaraan (Part)</label>            
                  <div class="col-sm-10">
                    <textarea class="form-control" name="tipe_part">
                    </textarea>
                  </div>                   
                </div>                                
                <div class="form-group">                
                  <label for="field-1" class="col-sm-2 control-label">Segment</label>            
                  <div class="col-sm-4">
                    <select class="form-control" name="id_segment">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_segment->result() as $val) {
                        echo "
                        <option value='$val->id_segment'>$val->segment</option>;
                        ";
                      }
                      ?>                      
                    </select>
                  </div>
               
                  <label for="field-1" class="col-sm-2 control-label">Kategori</label>           
                  <div class="col-sm-4">
                    <select class="form-control" name="id_kategori">
                      <option value="">- choose -</option>   
                      <?php 
                      foreach($dt_kategori->result() as $val) {
                        echo "
                        <option value='$val->id_kategori'>$val->kategori</option>;
                        ";
                      }
                      ?>                 
                    </select>
                  </div>                  
                </div>    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Series</label>           
                  <div class="col-sm-4">
                    <select class="form-control" name="id_series">
                      <option value="">- choose -</option>   
                      <?php 
                      foreach($dt_series->result() as $val) {
                        echo "
                        <option value='$val->id_series'>$val->series</option>;
                        ";
                      }
                      ?>                 
                    </select>
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">CC Motor</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="field-1" placeholder="CC Motor" name="cc_motor">
                  </div>                   
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Tgl Awal Efektif</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal2" placeholder="Tanggal Awal Efektif" name="tgl_awal">
                  </div>                   
                  <label for="field-1" class="col-sm-2 control-label">Tgl Akhir Efektif</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal" placeholder="Tanggal Akhir Efektif" name="tgl_akhir">                    
                  </div>
                </div>   
                <div class="form-group">                  
                  <label for="field-1" class="col-sm-2 control-label">Status WL</label>            
                  <div class="col-sm-4">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="form-control flat-red" name="status_wl" value="1" checked>
                      Active
                    </div>
                  </div>                
                  <label for="field-1" class="col-sm-2 control-label">Qty WL</label>                              
                  <div class="col-sm-4">
                    <input type="number" class="form-control" id="field-1" placeholder="Qty" name="qty_wl">                    
                  </div>  
                </div>             
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Deskripsi Samsat</label>            
                  <div class="col-sm-10">
                    <textarea class="form-control" name="deskripsi_samsat">
                    </textarea>
                  </div>                   
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">5 Digit No.Mesin</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="field-1" placeholder="No.Mesin" name="no_mesin">
                  </div>
                  <!--label for="field-1" class="col-sm-2 control-label">Kode PTM</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="field-1" placeholder="Kode PTM" name="kode_ptm">
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Tipe Part</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="field-1" placeholder="Kode Tipe Part" name="kode_part">
                  </div-->                   
                </div>                
                <div class="form-group">                  
                  <label for="field-1" class="col-sm-2 control-label"></label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="form-control flat-red" name="active" value="1" checked>
                      Active
                    </div>
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
      $row = $dt_tipe->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/tipe">
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
            <form class="form-horizontal" action="master/tipe/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_tipe_kendaraan ?>" />
              <div class="box-body"> 
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Tipe Kendaraan</label>            
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->id_tipe_kendaraan ?>" class="form-control" id="field-1" placeholder="Kode Tipe Kendaraan" name="id_tipe_kendaraan">
                  </div>           
                  <label for="field-1" class="col-sm-2 control-label">Nama Tipe Kendaraan</label>            
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->tipe_ahm ?>" class="form-control" id="field-1" placeholder="Nama Tipe Kendaraan" name="tipe_ahm">
                  </div>        
                </div>                                                                                                                                                                                                                                                                                                                                 
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Deskripsi Tipe Kendaraan (AHM)</label>            
                  <div class="col-sm-10">
    <textarea class="form-control" name="deskripsi_ahm"><?php echo $row->deskripsi_ahm ?></textarea>
                  </div>                   
                </div>                
                <!--div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Deskripsi Tipe Kendaraan (Customer)</label>            
                  <div class="col-sm-10">
                    <textarea class="form-control" name="tipe_customer">
                      <?php echo $row->tipe_customer ?>
                    </textarea>
                  </div>                   
                </div-->
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Deskripsi Tipe Kendaraan (Part)</label>            
                  <div class="col-sm-10">
                    <textarea class="form-control" name="tipe_part"><?php echo $row->tipe_part ?></textarea>
                  </div>                   
                </div>                                
                <div class="form-group">                  
                  <label for="field-1" class="col-sm-2 control-label">Segment</label>            
                  <div class="col-sm-4">
                    <select class="form-control" name="id_segment">
                      <option value="<?php echo $row->id_segment ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_segment","id_segment",$row->id_segment)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->segment;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_segment = $this->m_admin->kondisiCond("ms_segment","id_segment != '$row->id_segment'");                                                
                      foreach($dt_segment->result() as $val) {
                        echo "
                        <option value='$val->id_segment'>$val->segment</option>;
                        ";
                      }
                      ?>                      
                    </select>
                  </div>
               
                  <label for="field-1" class="col-sm-2 control-label">Kategori</label>           
                  <div class="col-sm-4">
                    <select class="form-control" name="id_kategori">
                      <option value="<?php echo $row->id_kategori ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_kategori","id_kategori",$row->id_kategori)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->kategori;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_kategori = $this->m_admin->kondisiCond("ms_kategori","id_kategori != '$row->id_kategori'");                                                
                      foreach($dt_kategori->result() as $val) {
                        echo "
                        <option value='$val->id_kategori'>$val->kategori</option>;
                        ";
                      }
                      ?>                 
                    </select>
                  </div>                  
                </div>    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Series</label>           
                  <div class="col-sm-4">
                    <select class="form-control" name="id_series">
                      <option value="<?php echo $row->id_series ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_series","id_series",$row->id_series)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->series;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_series = $this->m_admin->kondisiCond("ms_series","id_series != '$row->id_series'");                                                
                      foreach($dt_series->result() as $val) {
                        echo "
                        <option value='$val->id_series'>$val->series</option>;
                        ";
                      }
                      ?>                 
                    </select>
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">CC Motor</label>            
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->cc_motor ?>" class="form-control" id="field-1" placeholder="CC Motor" name="cc_motor">
                  </div>                   
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Tgl Awal Efektif</label>            
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->tgl_awal ?>" class="form-control" id="tanggal2" placeholder="Tanggal Awal Efektif" name="tgl_awal">
                  </div>                   
                  <label for="field-1" class="col-sm-2 control-label">Tgl Akhir Efektif</label>            
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->tgl_akhir ?>" class="form-control" id="tanggal" placeholder="Tanggal Akhir Efektif" name="tgl_akhir">                    
                  </div>
                </div>   
                <div class="form-group">                  
                  <label for="field-1" class="col-sm-2 control-label">Status WL</label>            
                  <div class="col-sm-4">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->status_wl=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="status_wl" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="status_wl" value="1">                      
                      <?php } ?>
                      Active
                    </div>
                  </div>                
                  <label for="field-1" class="col-sm-2 control-label">Qty WL</label>                              
                  <div class="col-sm-4">
                    <input type="number" value="<?php echo $row->qty_wl ?>" class="form-control" id="field-1" placeholder="Qty" name="qty_wl">                    
                  </div>  
                </div>             
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Deskripsi Samsat</label>            
                  <div class="col-sm-10">
                    <textarea class="form-control" name="deskripsi_samsat">
                      <?php echo $row->deskripsi_samsat ?>
                    </textarea>
                  </div>                   
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">5 Digit No.Mesin</label>            
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->no_mesin ?>" class="form-control" id="field-1" placeholder="No.Mesin" name="no_mesin">
                  </div>
                  <!--label for="field-1" class="col-sm-2 control-label">Kode PTM</label>            
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->kode_ptm ?>" class="form-control" id="field-1" placeholder="Kode PTM" name="kode_ptm">
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Tipe Part</label>            
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->kode_part ?>" class="form-control" id="field-1" placeholder="Kode Tipe Part" name="kode_part">
                  </div-->                   
                </div>    
                <div class="form-group">                  
                  <label for="field-1" class="col-sm-2 control-label"></label>            
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
    }elseif($set=="edit_ev"){
      // $row = $dt_tipe->row(); 

   ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/tipe">
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
            <form class="form-horizontal" action="master/tipe/update_ev" method="post" enctype="multipart/form-data">
              <div class="box-body"> 

                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Tipe Kendaraan</label>            
                  <div class="col-sm-4">
                    <input type="text"  class="form-control" name="tipe_kendaraan" value="<?=$value->id_tipe_kendaraan?>" readonly>
                  </div>           
                </div>                                                                                                                                                                                                                                                                                                                                 
    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Tipe Part</label>            
                  <div class="col-sm-4">
                  <select  class="form-control" name="id_part">
                  <?php
                        foreach($id_part as $part) { ?>
                          <option value="<?= $part->part_id ?>"><?= $part->part_id ?></option>
                      <?php
                        } ?>
                  </select >  
                  </div>
                </div>    

                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Qty</label>            
                  <div class="col-sm-4">
                    <input type="text" value="<?=$value->qty ?>" class="form-control" id="field-1" placeholder="Qty" name="qty">
                  </div>
                </div>  

                <div class="form-group">                  
                  <label for="field-1" class="col-sm-2 control-label"></label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($value->active=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="active" value="1">                      
                      <?php } ?>
                      Active
                    </div>
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
    }
  elseif($set=="view_ev"){

 ?>

  <div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">
        <a href="master/tipe">
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
          <form class="form-horizontal" action="master/tipe/update_ev" method="post" enctype="multipart/form-data">
            <div class="box-body"> 

              <div class="form-group">
                <label for="field-1" class="col-sm-2 control-label">Tipe Kendaraan</label>            
                <div class="col-sm-4">
                  <input type="text"  class="form-control" name="tipe_kendaraan" value="<?=$row->id_tipe_kendaraan?>" readonly>
                </div>           
              </div>                                                                                                                                                                                                                                                                                                                                 
  
              <div class="form-group">
                <label for="field-1" class="col-sm-2 control-label">Tipe Part</label>            
                <div class="col-sm-4">
                <input type="text"  class="form-control" name="tipe_kendaraan" value="<?=$row->id_part?>" readonly>
                </div>
              </div>    

              <div class="form-group">
                <label for="field-1" class="col-sm-2 control-label">Qty</label>            
                <div class="col-sm-4">
                  <input type="text" value="<?=$row->qty ?>" class="form-control" id="field-1" placeholder="Qty" name="qty" readonly>
                </div>
              </div>  

              <div class="form-group">                  
                <label for="field-1" class="col-sm-2 control-label"></label>            
                <div class="col-sm-2">
                  <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                    <?php 
                    if($row->active=='1'){
                    ?>
                    <input type="checkbox" class="flat-red" name="active" value="1" >
                    <?php }else{ ?>
                    <input type="checkbox" class="flat-red" name="active" value="0" >                      
                    <?php } ?>
                    Active
                  </div>
                </div>                  
              </div>
            </div>
            <div class="box-footer">
              <div class="col-sm-2">
              </div>
              <div class="col-sm-10">
              </div>
            </div><!-- /.box-footer -->
          </form>
        </div>
      </div>
    </div>
  </div><!-- /.box -->

  <?php 
  }
    
    
    elseif($set=="detail"){
      $row = $dt_tipe->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/tipe">
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
            <form class="form-horizontal" action="master/tipe/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_tipe_kendaraan ?>" />
              <div class="box-body">  
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Tipe Kendaraan</label>            
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->id_tipe_kendaraan ?>" class="form-control" id="field-1" placeholder="Kode Tipe Kendaraan" name="id_tipe_kendaraan">
                  </div>     
                  <label for="field-1" class="col-sm-2 control-label">Nama Tipe Kendaraan</label>            
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->tipe_ahm ?>" class="form-control" id="field-1" placeholder="Nama Tipe Kendaraan" name="tipe_ahm">
                  </div>        
                </div>                                                                                                                                                                                                                                                                                                                                 
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Deskripsi Tipe Kendaraan (AHM)</label>            
                  <div class="col-sm-10">
                    <textarea class="form-control" disabled name="deskripsi_ahm">
                      <?php echo $row->deskripsi_ahm ?>                    
                    </textarea>
                  </div>                   
                </div>                
                <!--div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Deskripsi Tipe Kendaraan (Customer)</label>            
                  <div class="col-sm-10">
                    <textarea disabled class="form-control" name="tipe_customer">
                      <?php echo $row->tipe_customer ?>
                    </textarea>
                  </div>                   
                </div-->                    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Deskripsi Tipe Kendaraan (Part)</label>            
                  <div class="col-sm-10">
                    <textarea disabled class="form-control" name="tipe_part">
                      <?php echo $row->tipe_part ?>
                    </textarea>
                  </div>                   
                </div>            
                <div class="form-group">                 
                  <label for="field-1" class="col-sm-2 control-label">Segment</label>            
                  <div class="col-sm-4">
                    <select disabled class="form-control" name="id_segment">
                      <option value="<?php echo $row->id_segment ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_segment","id_segment",$row->id_segment)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->segment;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_segment = $this->m_admin->kondisiCond("ms_segment","id_segment != '$row->id_segment'");                                                
                      foreach($dt_segment->result() as $val) {
                        echo "
                        <option value='$val->id_segment'>$val->segment</option>;
                        ";
                      }
                      ?>                      
                    </select>
                  </div>
                
                  <label for="field-1" class="col-sm-2 control-label">Kategori</label>           
                  <div class="col-sm-4">
                    <select disabled class="form-control" name="id_kategori">
                      <option value="<?php echo $row->id_kategori ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_kategori","id_kategori",$row->id_kategori)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->kategori;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_kategori = $this->m_admin->kondisiCond("ms_kategori","id_kategori != '$row->id_kategori'");                                                
                      foreach($dt_kategori->result() as $val) {
                        echo "
                        <option value='$val->id_kategori'>$val->kategori</option>;
                        ";
                      }
                      ?>                 
                    </select>
                  </div>                  
                </div>    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Series</label>           
                  <div class="col-sm-4">
                    <select disabled class="form-control" name="id_series">
                      <option value="<?php echo $row->id_series ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_series","id_series",$row->id_series)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->series;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_series = $this->m_admin->kondisiCond("ms_series","id_series != '$row->id_series'");                                                
                      foreach($dt_series->result() as $val) {
                        echo "
                        <option value='$val->id_series'>$val->series</option>;
                        ";
                      }
                      ?>                 
                    </select>
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">CC Motor</label>            
                  <div class="col-sm-4">
                    <input type="text" disabled value="<?php echo $row->cc_motor ?>" class="form-control" id="field-1" placeholder="CC Motor" name="cc_motor">
                  </div>                   
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Tgl Awal Efektif</label>            
                  <div class="col-sm-4">
                    <input disabled type="text" value="<?php echo $row->tgl_awal ?>" class="form-control" id="tanggal2" placeholder="Tanggal Awal Efektif" name="tgl_awal">
                  </div>                   
                  <label for="field-1" class="col-sm-2 control-label">Tgl Akhir Efektif</label>            
                  <div class="col-sm-4">
                    <input disabled type="text" value="<?php echo $row->tgl_akhir ?>" class="form-control" id="tanggal" placeholder="Tanggal Akhir Efektif" name="tgl_akhir">                    
                  </div>
                </div>   
                <div class="form-group">                  
                  <label for="field-1" class="col-sm-2 control-label">Status WL</label>            
                  <div class="col-sm-4">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->status_wl=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="status_wl" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="status_wl" value="1">                      
                      <?php } ?>
                      Active
                    </div>
                  </div>                
                  <label for="field-1" class="col-sm-2 control-label">Qty WL</label>                              
                  <div class="col-sm-4">
                    <input disabled type="number" value="<?php echo $row->qty_wl ?>" class="form-control" id="field-1" placeholder="Qty" name="qty_wl">                    
                  </div>  
                </div>             
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Deskripsi Samsat</label>            
                  <div class="col-sm-10">
                    <textarea disabled class="form-control" name="deskripsi_samsat">
                      <?php echo $row->deskripsi_samsat ?>
                    </textarea>
                  </div>                   
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">5 Digit No.Mesin</label>            
                  <div class="col-sm-4">
                    <input disabled type="text" value="<?php echo $row->no_mesin ?>" class="form-control" id="field-1" placeholder="No.Mesin" name="no_mesin">
                  </div>
                  <!--label for="field-1" class="col-sm-2 control-label">Kode PTM</label>            
                  <div class="col-sm-4">
                    <input disabled type="text" value="<?php echo $row->kode_ptm ?>" class="form-control" id="field-1" placeholder="Kode PTM" name="kode_ptm">
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Tipe Part</label>            
                  <div class="col-sm-4">
                    <input disabled type="text" value="<?php echo $row->kode_part ?>" class="form-control" id="field-1" placeholder="Kode Tipe Part" name="kode_part">
                  </div-->                   
                </div> 
              <?php /* <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <a href="master/tipe/edit?id=<?php echo $row->id_tipe_kendaraan ?>">
                    <button type="button" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Edit Data</button>                
                  </a>
                  <button type="button" onclick="cek()" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                
                </div>
              </div><!-- /.box-footer --> */ ?>
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
              <img src="assets/panel/images/tipe/<?php echo $row->foto_tipe ?>" width='100%'>
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
          <a href="master/tipe/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>       
          <a href="master/tipe/part_ev">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-plus"></i> Setting Part EV</button>
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
              <th>Kode Tipe Kendaraan</th>
              <th>Nama Tipe</th>
              <th>Segment</th>              
              <th>Kategori</th>
              <th>Status WL</th>
              <th>Qty WL</th>
              <th width="5%">Active</th>                                        
              <th width="13%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no=1;
            foreach ($dt_tipe->result() as $val) {    
              if($val->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";          
              if($val->status_wl=='1') $status_wl = "<i class='glyphicon glyphicon-ok'></i>";
                else $status_wl = "";          
              echo"
              <tr>
                <td>$no</td>
                <td>$val->id_tipe_kendaraan</td>
                <td>$val->tipe_ahm</td>
                <td>$val->segment</td>               
                <td>$val->kategori</td>
                <td>$status_wl</td>                
                <td>$val->qty_wl</td>                               
                <td>$active</td>                               
                <td>"; ?>
                  <a href="master/tipe/delete?id=<?php echo $val->id_tipe_kendaraan ?>"><button type="button" class="btn btn-danger btn-sm btn-flat" title="Delete" onclick="return confirm('Are you sure want to delete this data?')"><i class="fa fa-trash"></i></button></a>
                  <a href="master/tipe/edit?id=<?php echo $val->id_tipe_kendaraan ?>"><button type='button' class="btn btn-primary btn-sm btn-flat" title="Edit"><i class="fa fa-edit"></i></button></a>
                  <a href="master/tipe/view?id=<?php echo $val->id_tipe_kendaraan ?>"><button type='button' class="btn btn-info btn-sm btn-flat" title="View"><i class="fa fa-eye"></i></button></a>
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

 elseif($set=="part_ev"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/tipe/add_part_ev_qty">
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
              <th>Kode Tipe Kendaraan</th>
              <th>ID Part</th>
              <th>Nama Part</th>              
              <th>Qty</th>              
              <th width="5%">Active</th>                                        
              <th width="13%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no=1;
            foreach ($dt_tipe->result() as $val) {    
              if($val->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";          
              if($val->status_wl=='1') $status_wl = "<i class='glyphicon glyphicon-ok'></i>";
                else $status_wl = "";          
              echo"
              <tr>
                <td>$no</td>
                <td>$val->id_tipe_kendaraan</td>
                <td>$val->id_part</td>
                <td>$val->nama_part</td>
                <td>$val->qty</td>               
                <td>$active</td>                               
                <td>"; ?>
                  <a href="master/tipe/edit_part_ev?id=<?php echo $val->id_tipe_kendaraan ?>"><button type='button' class="btn btn-primary btn-sm btn-flat" title="Edit"><i class="fa fa-edit"></i></button></a>
                  <a href="master/tipe/view_part_ev?id=<?php echo $val->id_tipe_kendaraan ?>"><button type='button' class="btn btn-info btn-sm btn-flat" title="View"><i class="fa fa-eye"></i></button></a>
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
          url: "<?php echo site_url('master/tipe/ajax_bulk_delete')?>",
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