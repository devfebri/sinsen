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
          <a href="master/lokasi_part">
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
            <form method="POST" role="form" enctype="multipart/form-data" action="master/lokasi_part/save" class="form-horizontal form-groups-bordered">
              <div class="box-body">                                                                                                                                                    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Lokasi *</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="kode_lokasi_part" placeholder="Kode Lokasi" name="kode_lokasi_part" required>
                  </div>                
                  <label for="field-1" class="col-sm-2 control-label">Kapasitas</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Kapasitas" name="kapasitas">
                  </div>
                 </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Gudang Part</label>           
                  <div class="col-sm-4">
                    <select class="form-control" id="kode_gudang_part" name="kode_gudang_part" onchange="take_gudang()">
                      <option value="">- choose -</option>
                      <?php  
                      foreach ($sql->result() as $isi) {
                        echo "<option $isi->kode_gudang_part>$isi->kode_gudang_part</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Nama Gudang</label>           
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="nama_gudang" placeholder="Nama Gudang" readonly name="nama_gudang">                    
                  </div>
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Lokasi Retur</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="lokasi_retur">
                      <option value="">- choose -</option>
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>                                
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
      $row = $dt_lokasi_part->row(); 

    ?>    
    <body onload="take_gudang()">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/lokasi_part">
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
            <form method="POST" role="form" enctype="multipart/form-data" action="master/lokasi_part/update" class="form-horizontal form-groups-bordered">
              <div class="box-body">                                                                                                                                                    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Lokasi *</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="kode_lokasi_part" value="<?php echo $row->kode_lokasi_part ?>" placeholder="Kode Lokasi" name="kode_lokasi_part" required>
                  </div>                
                  <label for="field-1" class="col-sm-2 control-label">Kapasitas</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Kapasitas" value="<?php echo $row->kapasitas ?>" name="kapasitas">
                  </div>
                 </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Gudang Part</label>           
                  <div class="col-sm-4">
                    <select class="form-control" id="kode_gudang_part" name="kode_gudang_part" onchange="take_gudang()">
                      <option value="">- choose -</option>
                      <?php                        
                      foreach ($sql->result() as $isi) {
                        $hasil = ($row->kode_gudang_part == $isi->kode_gudang_part) ? "selected" : "" ;
                        echo "<option $hasil $isi->kode_gudang_part>$isi->kode_gudang_part</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Nama Gudang</label>           
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="nama_gudang" placeholder="Nama Gudang" readonly name="nama_gudang">                    
                  </div>
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Lokasi Retur</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="lokasi_retur">
                      <option <?php if($row->lokasi_retur == '') echo "selected"; ?> value="">- choose -</option>
                      <option <?php if($row->lokasi_retur == 'Ya') echo "selected"; ?>>Ya</option>
                      <option <?php if($row->lokasi_retur == 'Tidak') echo "selected"; ?>>Tidak</option>
                    </select>
                  </div>                                                  
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
          <a href="master/lokasi_part/add">
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
              <th>Kode Lokasi Part</th>
              <th>Kapasitas</th>             
              <th>Kode Gudang Part</th>              
              <th width="13%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no=1;
            foreach ($dt_lokasi_part->result() as $val) {                
              echo"
              <tr>
                <td>$no</td>
                <td>$val->kode_lokasi_part</td>
                <td>$val->kapasitas</td>
                <td>$val->nama_gudang</td>                
                <td>"; ?>
                  <a href="master/lokasi_part/delete?id=<?php echo $val->kode_lokasi_part ?>"><button type="button" class="btn btn-danger btn-sm btn-flat" title="Delete" onclick="return confirm('Are you sure want to delete this data?')"><i class="fa fa-trash"></i></button></a>
                  <a href="master/lokasi_part/edit?id=<?php echo $val->kode_lokasi_part ?>"><button type='button' class="btn btn-primary btn-sm btn-flat" title="Edit"><i class="fa fa-edit"></i></button></a>                  
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
function take_gudang(){
  var kode_gudang_part = $("#kode_gudang_part").val();                       
  $.ajax({
      url: "<?php echo site_url('master/lokasi_part/take_gudang')?>",
      type:"POST",
      data:"kode_gudang_part="+kode_gudang_part,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");                    
          $("#nama_gudang").val(data[0]);                                                              
      } 
  })
}
</script>