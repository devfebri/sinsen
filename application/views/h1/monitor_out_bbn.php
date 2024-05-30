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
          <a href="h1/monitor_out_bbn">
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
            <form class="form-horizontal" action="h1/monitor_out_bbn/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No BASTD</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->no_bastd ?>" name="no_mesin" placeholder="Nama Dealer" readonly class="form-control">                    
                  </div>                                                                                        
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->nama_dealer ?>" name="no_mesin" placeholder="Nama Dealer" readonly class="form-control">                    
                  </div>                                                      
                </div>                
                                                   
                <br>                                    
                <table id="example1" class="table table-hover table-bordered myTable1" width="100%">
                  <thead>
                    <tr>
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>Tipe</th>                    
                      <th>Warna</th>
                      <th>Nama Konsumen</th>
                      <th>Biaya BBN</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $sql = $this->db->query("SELECT * FROM tr_faktur_stnk_detail INNER JOIN tr_scan_barcode ON tr_faktur_stnk_detail.no_mesin = tr_scan_barcode.no_mesin
                      WHERE tr_faktur_stnk_detail.no_bastd = '$row->no_bastd'");
                    foreach ($sql->result() as $isi) {
                      echo "
                      <tr>
                        <td>$isi->no_mesin</td>
                        <td>$isi->no_rangka</td>
                        <td>$isi->tipe_motor</td>
                        <td>$isi->warna</td>
                        <td>$isi->nama_konsumen</td>
                        <td>".mata_uang2($isi->biaya_bbn)."</td>
                      </tr>
                      ";
                    }
                    ?>
                  </tbody>
                </table>
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
          <!-- <a href="h1/monitor_out_bbn/add">            
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>   -->        
                    
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
              <th width="10%">No Rekap</th>                           
              <th width="12%">Tgl Rekap</th>   
              <th>Dealer</th>           
              <th width="19%">No BASTD</th>                            
              <th width="14%">Total</th>                            
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_rekap->result() as $row) {                                         
          echo "          
            <tr>               
              <td>$no</td>             
              <td>
                <a href='h1/monitor_out_bbn/view?id=$row->no_rekap'>$row->no_rekap</a>
              </td>                            
              <td>$row->tgl_rekap</td>                            
              <td>$row->dealer</td>                            
              <td>$row->no_bastd</td>                                          
              <td align='right'>".mata_uang2($row->total)."</td>                                          
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
    
    elseif($set=="serverside"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
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
        <table  id="tbl_set_monitor_out_bbn" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>            
              <th width="10%">No Rekap</th>                           
              <th width="12%">Tgl Rekap</th>   
              <th>Dealer</th>           
              <th width="19%">No BASTD</th>                            
              <th width="14%">Total</th>                            
            </tr>
          </thead>
          <tbody>            
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <script>
        $( document ).ready(function() {
        $('#tbl_set_monitor_out_bbn').DataTable({
              "searchable": false,
              "language": {
              "lengthMenu ": "Display _MENU_ records per page",
              "zeroRecords": "Nothing found - sorry",
              "infoEmpty": "No records available",
              "infoFiltered": ""
          },
              "processing": true, 
              "serverSide": true, 
              "order": [],
              "ajax": {
                "url": "<?php echo site_url('/h1/monitor_out_bbn/fetch') ?>",
                "type": "POST",
                data: function(d) {
                return d;
              },
              },
              "columnDefs": [{
                "targets": [0], 
                "orderable": false, 
              }, ],
            });
            });
            </script>
    

    <?php
    }
    ?>
  </section>
</div>
