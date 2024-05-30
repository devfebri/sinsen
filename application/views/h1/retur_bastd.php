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
    <li class="">Faktur STNK</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    
    <?php 
    if($set=="terima"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/retur_bastd">
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
            <form class="form-horizontal" action="h1/retur_bastd/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor Retur</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" placeholder="Nomor Retur" readonly class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Retur</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" placeholder="Tgl Retur" readonly class="form-control">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor BASTD</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" placeholder="Nomor BASTD" readonly class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Alsan Retur</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" placeholder="Alasan Retur" readonly class="form-control">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" name="periode_awal" placeholder="Keterangan" readonly class="form-control">
                  </div>                                    
                </div>
                <div class="form-group">  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" placeholder="Nama Dealer" readonly class="form-control">
                  </div>                                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" placeholder="Alamat Dealer" readonly class="form-control">
                  </div>                  
                </div>
                

                <table class='table table-bordered table-hover' id="example1">
                  <tr>                    
                    <th>No Mesin</th>
                    <th>No Rangka</th>
                    <th>Nama Konsumen</th>                    
                    <th>No Faktur AHM</th>
                    <th>Tipe</th>
                    <th>Warna</th>
                    <th>Tahun</th>                    
                    <th width="1%">Aksi</th>
                  </tr>
                  <tr>                    
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>                    
                    <td></td>                    
                    <td align="center">
                      <input type="checkbox" name="">
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
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="h1/retur_bastd/generate">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-download"></i> Generate File TXT Samsat</button>
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
       <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>                          
              <th>No BASTD</th>
              <th>Tgl BASTD</th>
              <th>Nama Dealer</th>              
              <th>Status</th>
              <th width="20%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_bbn->result() as $row) {
            
           $tombol = "<a href='h1/retur_bastd/terima_retur?id=$row->no_bastd' onclick=\"return confirm('Are you sure to save all data?')\" class='btn btn-primary btn-flat btn-xs'>Terima</a>";    

            //$tombol = "<button type=\"submit\" onclick=\"return confirm('Are you sure to save all data?')\" name=\"save\" value=\"save\" class=\"btn btn-primary btn-flat btn-xs\">Terima</button>";
            $cek2 = $this->m_admin->getByID("tr_faktur_stnk","no_bastd",$row->no_bastd);                        
            $id2    = $cek2->row();                          
            $status = "<span class='label label-danger'>$id2->status_faktur</span>";            
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->no_bastd</td>
              <td>$row->tgl_bastd</td>                           
              <td>$row->nama_dealer</td>                            
              <td>
                $status
              </td>                            
              <td>";
              echo $tombol."</td>";                                      
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
