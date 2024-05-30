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
          <a href="master/gudang_part">
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
            <form method="POST" role="form" enctype="multipart/form-data" action="master/gudang_part/save" class="form-horizontal form-groups-bordered">
              <div class="box-body">                                                                                                                                                    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Gudang *</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="kode_gudang_part" placeholder="Kode Gudang" name="kode_gudang_part" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Nama Gudang *</label>            
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Nama Gudang" name="nama_gudang" required>
                  </div>
                 </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Jenis Gudang</label>           
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_gudang">
                      <option value="">- choose -</option>
                      <option>Gudang Part</option>
                      <option>Gudang Oli</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Alamat *</label>           
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Alamat" name="alamat" required>                    
                  </div>
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Luas Gudang</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Luas Gudang" name="luas">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Rak</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Jumlah Rak" name="jumlah_rak">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Binbox</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Jumlah Binbox" name="jumlah_binbox">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Pallet</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Jumlah Pallet" name="jumlah_pallet">                                        
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
    }elseif($set=="edit"){
      $row = $dt_gudang_part->row(); 

    ?>    
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/gudang_part">
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
            <form method="POST" role="form" enctype="multipart/form-data" action="master/gudang_part/update" class="form-horizontal form-groups-bordered">
              <div class="box-body">                                                                                                                                                    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Gudang *</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="kode_gudang_part" value="<?php echo $row->kode_gudang_part ?>" readonly placeholder="Kode Gudang" name="kode_gudang_part" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Nama Gudang *</label>            
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Nama Gudang" name="nama_gudang" value="<?php echo $row->nama_gudang ?>" required>
                  </div>
                 </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Jenis Gudang</label>           
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_gudang">
                      <option <?php if($row->jenis_gudang == "") echo "selected"; ?> value="">- choose -</option>
                      <option <?php if($row->jenis_gudang == "Gudang Part") echo "selected"; ?>>Gudang Part</option>
                      <option <?php if($row->jenis_gudang == "Gudang Oli") echo "selected"; ?>>Gudang Oli</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Alamat *</label>           
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Alamat" value="<?php echo $row->alamat ?>" name="alamat" required>                    
                  </div>
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Luas Gudang</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Luas Gudang" value="<?php echo $row->luas ?>" name="luas">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Rak</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Jumlah Rak" value="<?php echo $row->jumlah_rak ?>" name="jumlah_rak">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Binbox</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" placeholder="Jumlah Binbox" value="<?php echo $row->jumlah_binbox ?>" name="jumlah_binbox">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Pallet</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Jumlah Pallet" value="<?php echo $row->jumlah_pallet ?>" name="jumlah_pallet">                                        
                  </div>
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
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update</button>
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
          <a href="master/gudang_part/add">
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
              <th>Kode Gudang</th>
              <th>Nama Gudang</th>             
              <th>Jenis</th>
              <th>Luas</th>              
              <th>Jumlah Rak</th>
              <th>Jumlah Binbox</th>
              <th>Jumlah Pallet</th>                          
              <th width="13%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no=1;
            foreach ($dt_gudang_part->result() as $val) {                
              echo"
              <tr>
                <td>$no</td>
                <td>$val->kode_gudang_part</td>
                <td>$val->nama_gudang</td>
                <td>$val->jenis_gudang</td>
                <td>$val->luas</td>               
                <td>$val->jumlah_rak</td>               
                <td>$val->jumlah_binbox</td>
                <td>$val->jumlah_pallet</td>                                                                          
                <td>"; ?>
                  <a href="master/gudang_part/delete?id=<?php echo $val->kode_gudang_part ?>"><button type="button" class="btn btn-danger btn-sm btn-flat" title="Delete" onclick="return confirm('Are you sure want to delete this data?')"><i class="fa fa-trash"></i></button></a>
                  <a href="master/gudang_part/edit?id=<?php echo $val->kode_gudang_part ?>"><button type='button' class="btn btn-primary btn-sm btn-flat" title="Edit"><i class="fa fa-edit"></i></button></a>                  
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
