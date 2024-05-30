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
    <li class="">Invoice AHM</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$page)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="check"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/pembelian_unit">
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
            <form class="form-horizontal" action="h1/pembelian_unit/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Faktur</label>
                  <div class="col-sm-3">
                    <input type="text" name="no_mesin" placeholder="No Faktur" readonly class="form-control">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-3 control-label">Tgl Jatuh Tempo Pembayaran Pokok</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Tgl Jatuh Tempo Pembayaran Pokok" readonly class="form-control">                    
                  </div>                  
                </div>  
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Faktur</label>
                  <div class="col-sm-3">
                    <input type="text" name="no_mesin" placeholder="Tgl Faktur" readonly class="form-control">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-3 control-label">Tgl Jatuh Tempo Pembayaran PPN</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Tgl Jatuh Tempo Pembayaran PPN" readonly class="form-control">                    
                  </div>                  
                </div>  
                <div class="form-group">                                    
                  <div class="col-sm-5">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-3 control-label">Tgl Jatuh Tempo Pembayaran PPh</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Tgl Jatuh Tempo Pembayaran PPh" readonly class="form-control">                    
                  </div>                  
                </div>  
                
                <table class="table table-bordered table-hovered myTable1" width="100%">
                  <tr>
                    <td>Kode Tipe</td>
                    <td>Kode Warna</td>
                    <td>Qty</td>
                    <td>No SIPB</td>
                    <td>No SL</td>
                    <td>Disc Quotation</td>
                    <td>Dist Tipe Cash</td>
                    <td>Dic Other</td>
                    <td>Amount</td>
                    <td>PPN</td>
                    <td>PPh</td>                    
                  </tr>                  
                </table>  

                <br>


                
                
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
          <a href="h1/pembelian_unit/upload">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-upload"></i> Upload .INV</button>
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
              <th>No Faktur</th>             
              <th>Tgl Faktur</th> 
              <th>Tgl Jatuh Tempo</th>              
              <th>No Invoice</th>
              <th>Tgl Invoice</th>
              <th>Total Amount</th>
              <th>Total Diskon</th>
              <th>Total PPN</th>
              <th>Total PPH</th>
              <th>Total Bayar</th>
              <th>No Voucher</th>
              <th>Status</th>
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
                <a href='h1/pembelian_unit/detail?id='>
                  89/POP/2018
                </a>
              </td>              
              <td></td>
              <td></td>                            
              <td></td>                            
              <td></td>                            
              <td></td>                            
              <td></td>                            
              <td></td>                            
              <td></td>                            
              <td></td>                            
              <td></td>                            
              <td></td>                            
              <td>
                <a href='h1/pembelian_unit/check?id=' type='button' class='btn btn-flat btn-warning btn-xs'> Cross Check</a>
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
