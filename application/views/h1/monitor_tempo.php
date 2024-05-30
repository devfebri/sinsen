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
    if($set=="detail"){
      $row = $dt->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/monitor_tempo">
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
            <form class="form-horizontal" action="h1/monitor_tempo/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rekap</label>
                  <div class="col-sm-3">
                    <input type="text" name="no_mesin" value="<?php echo $row->no_rekap ?>" placeholder="No Rekap" readonly class="form-control">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-3 control-label">Tgl Jatuh Tempo</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" value="<?php echo $row->tgl_jatuh_tempo ?>" placeholder="Tgl Jatuh Tempo" readonly class="form-control">                    
                  </div>                  
                </div>  
                
                
                <table class="table table-bordered table-hovered" id="example3" width="100%">
                  <thead>
                    <tr>
                      <th>No Faktur</th>
                      <th>Tgl Faktur</th>
                      <th>Tgl Jatuh Tempo</th>
                      <th>Total Amount</th>
                      <th>Total Diskon</th>
                      <th>Total PPN</th>
                      <th>Total PPh</th>
                      <th>Total Bayar</th>                    
                    </tr>                  
                  </thead>
                  <tbody>
                  <?php 
                  foreach ($dt_mon->result() as $isi) {
                    echo 
                    "<tr>
                      <td>$isi->no_faktur</td>
                      <td>$isi->tgl_faktur</td>
                      <td>$isi->tgl_pokok</td>
                      <td>".mata_uang2($isi->jum_amount)."</td>
                      <td>$isi->jum_disc</td>
                      <td>".mata_uang2($isi->jum_ppn)."</td>
                      <td>".mata_uang2($isi->jum_pph)."</td>
                      <td>".mata_uang2($isi->jum_pph + $isi->jum_ppn + $isi->jum_amount-$isi->jum_disc)."</td>
                    </tr>";
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
          <!--a href="h1/monitor_tempo/upload">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-upload"></i> Upload .INV</button>
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
              <th>No Rekap</th>                           
              <th>Tgl Jatuh Tempo</th>              
              <th>Total Amount</th>                            
              <th>Total Diskon</th>
              <th>Total PPN</th>
              <th>Total PPH</th>
              <th>Total Bayar</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_mon->result() as $row) {    
            $bulan = substr($row->tgl_jatuh_tempo, 2,2);
            $tahun = substr($row->tgl_jatuh_tempo, 4,4);
            $tgl = substr($row->tgl_jatuh_tempo, 0,2);
            $tanggal = $tgl."-".$bulan."-".$tahun;                                     
          echo "          
            <tr>
              <td>$no</td>                           
              <td>
                <a href='h1/monitor_tempo/detail?id=$row->tgl_jatuh_tempo'>
                  $row->no_rekap
                </a>
              </td>              
              <td>$tanggal</td>
              <td>".mata_uang2($row->total_pembayaran)."</td>                                          
              <td>".mata_uang2($row->total_diskon)."</td>                            
              <td>".mata_uang2($row->total_ppn)."</td>                            
              <td>".mata_uang2($row->total_pph)."</td>                            
              <td>".mata_uang2($row->total_bayar)."</td>                            
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
