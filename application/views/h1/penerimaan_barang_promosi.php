<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 40px;
  padding-left: 5px;
  padding-right: 5px;  
}
.isi_combo{   
  height: 30px;
  border:1px solid #ccc;
  padding-left:1.5px;
}
</style>
<base href="<?php echo base_url(); ?>" />
<body onload="kirim_data_pl()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class=""><?php echo $isi ?></li>
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
          <a href="h1/penerimaan_barang_promosi">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
            <form class="form-horizontal" action="h1/penerimaan_barang_promosi/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">                       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Vendor</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Vendor" name="">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No Surat Jalan" name="">
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal SJ Vendor</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Tanggal Sj Vendor" id="tanggal" name="">
                  </div>                  
                </div>                                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Tanggal Penerimaan" id="tanggal2" name="">
                  </div>                                    
                </div>                    

                <button class="btn btn-info btn-flat btn-sm btn-block" disabled>Detail Item</button>
                <table class="table table-bordered table-hover myTable1">
                  <tr>
                    <th>Nama Item</th>
                    <th>Kategori Item</th>
                    <th>Qty Terima</th>                    
                    <th width="15%">Aksi</th>
                  </tr>
                  <tr>
                    <td>
                      <select class="form-control isi_combo">
                        <option>- choose -</option>
                      </select>
                    </td>
                    <td>
                      <select class="form-control isi_combo">
                        <option>- choose -</option>
                      </select>
                    </td>
                    <td>
                      <input type="text" class="form-control isi_combo" name="" placeholder="Qty Terima">
                    </td>                    
                    <td>
                      <a href='h1/penerimaan_barang_promosi/edit' type='button' class='btn btn-flat btn-primary btn-xs'><i class='fa fa-edit'></i> Add</a>
                      <a href='h1/penerimaan_barang_promosi/edit' type='button' class='btn btn-flat btn-success btn-xs'><i class='fa fa-edit'></i> Edit</a>
                      <a href='h1/penerimaan_barang_promosi/print' type='button' class='btn btn-flat btn-danger btn-xs'><i class='fa fa-print'></i> Del</a>
                    </td>
                  </tr>
                </table>                                                     
                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
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
          <a href="h1/penerimaan_barang_promosi">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
            <form class="form-horizontal" action="h1/penerimaan_barang_promosi/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Penerimaan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No Penerimaan" name="">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Tanggal Penerimaan" name="">
                  </div>                  
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No PO</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No PO" name="">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Vendro</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Vendor" name="">
                  </div>                  
                </div>                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan Vendor</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No Surat Jalan Vendor" name="">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Surat Jalan Vendor</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tanggal" placeholder="Tanggal Surat Jalan Vendor" name="">
                  </div>                  
                </div>                                                         

                <button class="btn btn-info btn-flat btn-sm btn-block" disabled>Detail Penerimaan</button>
                <table class="table table-bordered table-hover">
                  <tr>
                    <th>Nama Item</th>
                    <th>Kategori Item</th>
                    <th>Qty DO</th>
                    <th>Qty Penerimaan</th>
                  </tr>
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                </table>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
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
          <a href="h1/penerimaan_barang_promosi/add">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
              <th>No Penerimaan</th>             
              <th>Tgl Penerimaan</th>               
              <th>No SJ</th>              
              <th>Tanggal SJ</th>                            
              <th>Vendor</th>                            
              <th width="5%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          // $no=1; 
          // foreach($dt_pl->result() as $row) {                                         
          echo "          
            <tr>
              <td>1</td>                           
              <td>
                <a href=''>
                  81918920
                </a>
              </td>                            
              <td>2018-11-12</td>              
              <td>KAJ/981782</td>
              <td>2018-02-12</td>
              <td>Surya Jaya</td>
              <td>
                <a href='h1/penerimaan_barang_promosi/edit' type='button' class='btn btn-flat btn-success btn-xs'><i class='fa fa-edit'></i> Edit</a>                
              </td>";                                      
          // $no++;
          // }
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
