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
          <a href="master/kategori_claim">
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
            <form method="POST" role="form" enctype="multipart/form-data" action="master/kategori_claim/save" class="form-horizontal form-groups-bordered">
              <div class="box-body">                                                                                                                                                    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Claim *</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="kode_claim" placeholder="Kode Claim" name="kode_claim" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Nama Claim *</label>            
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Nama Claim" name="nama_claim" required>
                  </div>
                 </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Tipe Claim</label>           
                  <div class="col-sm-4">
                    <select class="form-control" name="tipe_claim">
                      <option value="">- choose -</option>
                      <option>Kualitas</option>
                      <option>Non-Kualitas</option>
                    </select>
                  </div>                
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
      $row = $dt_kategori_claim->row(); 

    ?>
    <body onload="take_kec()">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/kategori_claim">
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
            <form method="POST" role="form" enctype="multipart/form-data" action="master/kategori_claim/update" class="form-horizontal form-groups-bordered">
              <div class="box-body">                                                                                                                                                    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Claim *</label>            
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->kode_claim ?>" class="form-control" id="kode_claim" placeholder="Kode Claim" name="kode_claim" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Nama Claim *</label>            
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?php echo $row->nama_claim ?>"  placeholder="Nama Claim" name="nama_claim" required>
                  </div>
                 </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Tipe Claim</label>           
                  <div class="col-sm-4">
                    <select class="form-control" name="tipe_claim">
                      <option <?php if($row->tipe_claim == "") echo "selected" ?> value="">- choose -</option>
                      <option <?php if($row->tipe_claim == "Kualitas") echo "selected" ?>>Kualitas</option>
                      <option <?php if($row->tipe_claim == "Non-Kualitas") echo "selected" ?>>Non-Kualitas</option>
                    </select>
                  </div>  
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
          <a href="master/kategori_claim/add">
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
              <th>Kode Claim</th>
              <th>Nama Claim</th>             
              <th>Tipe Claim</th>              
              <th width="13%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no=1;
            foreach ($dt_kategori_claim->result() as $val) {                
              echo"
              <tr>
                <td>$no</td>
                <td>$val->kode_claim</td>
                <td>$val->nama_claim</td>
                <td>$val->tipe_claim</td>                
                <td>"; ?>
                  <a href="master/kategori_claim/delete?id=<?php echo $val->kode_claim ?>"><button type="button" class="btn btn-danger btn-sm btn-flat" title="Delete" onclick="return confirm('Are you sure want to delete this data?')"><i class="fa fa-trash"></i></button></a>
                  <a href="master/kategori_claim/edit?id=<?php echo $val->kode_claim ?>"><button type='button' class="btn btn-primary btn-sm btn-flat" title="Edit"><i class="fa fa-edit"></i></button></a>                  
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
