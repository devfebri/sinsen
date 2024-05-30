<body onload="metode_a()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">General</li>    
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">Anda bisa lakukan perubahan komponen web dengan fitur ini</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <?php 
      $re = $setting->row();
      ?>
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
            <form class="form-horizontal" action="panel/save_setting" method="post" enctype="multipart/form-data">
              <div class="box-body">                                    
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">Lokasi Download</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?php echo $re->lokasi_download ?>" name="lokasi_download" placeholder="Lokasi Download">
                  </div>
                </div>                
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">Lokasi Upload</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?php echo $re->lokasi_upload ?>" name="lokasi_upload" placeholder="Lokasi Upload">
                  </div>
                </div>                

                
                
                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info">Save</button>
                <button type="reset" class="btn btn-default pull-right">Reset</button>                
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
  </section>
</div>
