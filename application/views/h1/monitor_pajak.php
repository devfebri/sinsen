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
  margin-right: 0px; 
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
    <li class="">Finance</li>
    <li class="">Invoice Terima</li>
    <li class="">Tagihan Samsat</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$page)); ?></li>
  </ol>
  </section>
  <section class="content">


    <?php 
    if($set=="detail"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/monitor_pajak">
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
            <form class="form-horizontal" action="h1/monitor_pajak/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Faktur</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" readonly placeholder="No Faktur" readonly class="form-control">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Nilai Dibayar</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin"  placeholder="Total Nilai Dibayar" readonly class="form-control">                    
                  </div>                  
                </div>  
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Faktur</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" readonly placeholder="Tgl Faktur" class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Nilai Notice</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" readonly  placeholder="Total Nilai Notice" class="form-control">                    
                  </div>                                    
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Mohon Samsat</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" readonly placeholder="Tgl Mohon Samsat" class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Selish</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" readonly  placeholder="Selisih" class="form-control">                    
                  </div>                                    
                </div>                
                
                
                
                <table class="table table-bordered table-hovered myTable1" width="100%">
                  <tr>
                    <td>No Mesin</td>
                    <td>No Rangka</td>
                    <td>Tipe</td>
                    <td>Warna</td>
                    <td>Tahun Produksi</td>                    
                    <td>Nilai Dibayar</td>
                    <td>Nilai Notice</td>                    
                  </tr>                  
                </table>  

                <br>


                
                
              </div><!-- /.box-body -->
              
              
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
          <!--a href="h1/monitor_pajak/add">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a-->          
                    
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
              <th>No Faktur</th>                           
              <th>Tgl Faktur</th>              
              <th>Tgl Mohon Samsat</th>              
              <th>Nilai Dibayar</th>
              <th>Nilai Notice</th>
              <th>Selisih</th>
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
                <a href='h1/monitor_pajak/detail?id='>
                  89/POP/2018
                </a>
              </td>              
              <td></td>
              <td></td>                            
              <td></td>                            
              <td></td>                            
              <td></td>                            
              <td>
                <a class='btn btn-warning btn-flat btn-xs'>Cetak Perbedaan Selisih</a>
              </td>                                          
              ";                                      
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
