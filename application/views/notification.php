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
        <h3 class="box-title">Incoming file list from AHM in your directory.</h3>
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
                      <th>File Name</th>                
                      <th>File Type</th>                    
                      <th width="5%">Action</th>
                    </tr>
                  </thead>
                  <tbody>            
                    <!--?php 
                    
                    $fol = $this->m_admin->getById("tabel_setting","id_setting",1)->row();                           
                    $uploaddir  = $fol->lokasi_upload;
                    $filename   = $uploaddir.'*.*';
                    if (count(glob($filename)) > 0) {
                        $r = count(glob($filename));
                    } else {
                        $r = "";
                    }
                    $files = scandir($uploaddir);                    
                    foreach ($files as $file) {
                      if($file != "." AND $file != ".."){
                        $tempSplit = explode(".", $file);
                        $type = strtoupper($tempSplit[1]);                        
                        echo "
                        <tr>                       
                          <td>$file</td>
                          <td>$type</td>
                          <td>
                            <button type='button' class='btn bg-maroon btn-flat btn-md'><i class='fa fa-upload'></i> Upload</button>
                          </td>
                        </tr>
                        ";
                      }                      
                    }
                    ?-->
                    
                  </tbody>
                </table>
              </div><!-- /.box-body -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
  </section>
</div>
