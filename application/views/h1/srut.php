<?php 
function bln(){
  $bulan=$bl=$month=date("m");
  switch($bulan)
  {
    case"1":$bulan="Januari"; break;
    case"2":$bulan="Februari"; break;
    case"3":$bulan="Maret"; break;
    case"4":$bulan="April"; break;
    case"5":$bulan="Mei"; break;
    case"6":$bulan="Juni"; break;
    case"7":$bulan="Juli"; break;
    case"8":$bulan="Agustus"; break;
    case"9":$bulan="September"; break;
    case"10":$bulan="Oktober"; break;
    case"11":$bulan="November"; break;
    case"12":$bulan="Desember"; break;
  }
  $bln = $bulan;
  return $bln;
}
?>
<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 25px;
  padding-left: 4px;
  padding-right: 4px;  
}
</style>
<base href="<?php echo base_url(); ?>" />
<?php 
if(isset($_GET['id'])){
?>
<body onload="cek_jenis()">
<?php }else{ ?>
<body onload="auto()">
<?php } ?>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">SRUT</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    
    <?php 
    if($set=="upload"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/srut">
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
            <form class="form-horizontal" autocomplete="off" action="h1/srut/import_db" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Faktur</label>
                  <div class="col-sm-4">
                    <input type="tgl_faktur" id="tanggal1" placeholder="Tanggal Faktur" required class="form-control" name="tgl_faktur">                    
                  </div>                  
                </div>                                                                                                      
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Choose File</label>
                  <div class="col-sm-10">
                    <input type="file" accept=".xlsx" required class="form-control" autofocus name="userfile">                    
                  </div>                  
                </div>                                                                                                      
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to import this data?')" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Start Upload</button>                                  
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
          <a href="h1/srut/upload">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-upload"></i> Upload</button>
          </a>          
          <a href="h1/srut/gagal">
            <button class="btn btn-primary btn-flat margin"><i class="fa fa-eye"></i> Data Gagal</button>
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>            
              <th width="5%">No</th>
              <th>Nomor SUT</th>              
              <th>No Rangka</th>              
              <th>No Mesin</th>              
              <th>No SRUT dr Pemohon</th>
              <th>Tahun Pembuatan</th>                            
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_srut->result() as $row) {          
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->no_srut</td>              
              <td>$row->no_rangka</td>
              <td>$row->no_mesin</td>
              <td>$row->no_srut_pemohon</td>
              <td>$row->tahun_pembuatan</td>              
            </tr>";                        
          $no++;
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php 
    }elseif($set=='gagal'){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">          
          <a href="h1/srut">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>            
              <th width="5%">No</th>
              <th>Nomor SUT</th>              
              <th>No Rangka</th>              
              <th>No Mesin</th>              
              <th>No SRUT dr Pemohon</th>
              <th>Tahun Pembuatan</th>                            
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_srut->result() as $row) {          
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->no_srut</td>              
              <td>$row->no_rangka</td>
              <td>$row->no_mesin</td>
              <td>$row->no_srut_pemohon</td>
              <td>$row->tahun_pembuatan</td>              
            </tr>";                        
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


