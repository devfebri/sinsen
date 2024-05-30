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
          <a href="master/range_dus_oli">
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
            <form method="POST" role="form" enctype="multipart/form-data" action="master/range_dus_oli/save" class="form-horizontal form-groups-bordered">
              <div class="box-body">                                                                                                                                                    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Range Dus Oli</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="kode_range" placeholder="Kode Range Dus oli" name="kode_range" required>
                  </div>                
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Awal</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Awal" name="awal">
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Akhir</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Akhir" name="akhir">
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
      $row = $dt_range_dus_oli->row(); 

    ?>    
    <body onload="take_gudang()">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/range_dus_oli">
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
            <form method="POST" role="form" enctype="multipart/form-data" action="master/range_dus_oli/update" class="form-horizontal form-groups-bordered">
              <div class="box-body">                                                                                                                                                    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Range Dus Oli</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="kode_range" readonly value="<?php echo $row->kode_range ?>" placeholder="Kode Range Dus oli" name="kode_range" required>
                  </div>                
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Awal</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Awal" value="<?php echo $row->awal ?>" name="awal">
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Akhir</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Akhir" value="<?php echo $row->akhir ?>" name="akhir">
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
          <a href="master/range_dus_oli/add">
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
              <th>Kode Range</th>
              <th>Awal</th>             
              <th>Akhir</th>              
              <th width="13%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no=1;
            foreach ($dt_range_dus_oli->result() as $val) {                
              echo"
              <tr>
                <td>$no</td>
                <td>$val->kode_range</td>
                <td>$val->awal</td>
                <td>$val->akhir</td>                
                <td>"; ?>
                  <a href="master/range_dus_oli/delete?id=<?php echo $val->kode_range ?>"><button type="button" class="btn btn-danger btn-sm btn-flat" title="Delete" onclick="return confirm('Are you sure want to delete this data?')"><i class="fa fa-trash"></i></button></a>
                  <a href="master/range_dus_oli/edit?id=<?php echo $val->kode_range ?>"><button type='button' class="btn btn-primary btn-sm btn-flat" title="Edit"><i class="fa fa-edit"></i></button></a>                  
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
      url: "<?php echo site_url('master/range_dus_oli/take_gudang')?>",
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