<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 30px;  
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
    <li class="">Invoice Keluar</li>
    <li class="">Inovice Dealer</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$page)); ?></li>
  </ol>
  </section>
  <section class="content">


    
    <?php 
    if($set=="detail"){
      $row = $dt_rekap->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/monitor_out_bantuan">
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
            <form class="form-horizontal" action="h1/monitor_out_bantuan/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Faktur</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" value="<?php echo $row->no_faktur ?>" placeholder="No Faktur" readonly class="form-control">                    
                  </div>                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Faktur</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" value="<?php echo $row->tgl_faktur ?>" placeholder="Tgl Faktur" readonly class="form-control">                    
                  </div>                                                      
                </div>  
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pemohon</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" value="<?php echo $row->nama_pemohon ?>" placeholder="Nama Pemohon" readonly class="form-control">                    
                  </div>                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="No Mesin" readonly class="form-control">                    
                  </div>                                                      
                </div>                
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="No Rangka" readonly class="form-control">                    
                  </div>                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Tipe Kendaraan" readonly class="form-control">                    
                  </div>                                                      
                </div>                
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Warna" readonly class="form-control">                    
                  </div>                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun Produksi</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Tahun Produksi" readonly class="form-control">                    
                  </div>                                                      
                </div>                
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor KTP</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Nomor KTP" readonly class="form-control">                    
                  </div>                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Nama Konsumen" readonly class="form-control">                    
                  </div>                                                      
                </div>                
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Konsumen</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Alamat Konsumen" readonly class="form-control">                    
                  </div>                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="No Telp" readonly class="form-control">                    
                  </div>                                                      
                </div>                
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Gadis Ibu Kandung</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Nama Gadis Ibu Kandung" readonly class="form-control">                    
                  </div>                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">Apakah ini dari pemenang atau bukan?</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Ya/Tidak" readonly class="form-control">                    
                  </div>                                                      
                </div>                
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Pemenang dari?</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Pemenag dari?" readonly class="form-control">                    
                  </div>                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">Tagih ke?</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Tagih ke?" readonly class="form-control">                    
                  </div>                                                      
                </div>                
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya Administrasi</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Biaya Administrasi" readonly class="form-control">                    
                  </div>                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya BBN</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Biaya BBN" readonly class="form-control">                    
                  </div>                                                      
                </div>                
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Total</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Total" readonly class="form-control">                    
                  </div>                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Mohon Samsat</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Tgl Mohon Samsat" readonly class="form-control">                    
                  </div>                                                      
                </div>                

                
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
          <!--a href="h1/monitor_out_bantuan/add">            
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
              <th>Nama Konsumen</th>                           
              <th>Pemohon</th>              
              <th>Tagih Ke</th>                            
              <th>Tipe</th>                            
              <th>Warna</th>
              <th>Total</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_rekap->result() as $row) {                                         
          echo "          
            <tr>               
              <td>$no</td>             
              <td>$row->nama_konsumen</td>                            
              <td>$row->pemohon</td>                            
              <td>$row->tagih_ke</td>                                          
              <td>$row->tipe_ahm</td>                            
              <td>$row->warna</td>                            
              <td>".mata_uang2($row->total)."</td>                            
              <td>$row->status_mon</td>                                          
              ";                                      
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
