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
<body>
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
    <li class="">Inovice Asuransi</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$page)); ?></li>
  </ol>
  </section>
  <section class="content">


    <?php 
    if($set=="insert"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/rekap_asuransi_unit">
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
            <form class="form-horizontal" action="h1/rekap_asuransi_unit/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Vendor</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_vendor" id="id_vendor">
                      <option>- choose -</option>
                      <?php 
                      $sql = $this->db->query("SELECT DISTINCT(ms_rate_asuransi.id_vendor) as id_vendor,ms_vendor.vendor_name FROM ms_rate_asuransi INNER JOIN ms_vendor
                          ON ms_rate_asuransi.id_vendor = ms_vendor.id_vendor ORDER BY ms_vendor.vendor_name ASC");
                      foreach ($sql->result() as $isi) {
                        echo "<option value='$isi->id_vendor'>$isi->id_vendor | $isi->vendor_name</option>";
                      }                      
                      ?>
                    </select>
                  </div>                                    
                </div>  
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Periode Faktur (Awal)</label>
                  <div class="col-sm-4">
                    <input type="text" autocomplete="off" name="periode_awal" placeholder="Periode Faktur (Awal)" id="tanggal" class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Periode Faktur (Akhir)</label>
                  <div class="col-sm-4">
                    <input type="text" autocomplete="off" name="periode_akhir" id="tanggal1" placeholder="Periode Faktur (Akhir)" class="form-control">                    
                  </div>                                    
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Presentase Qty Asuransi</label>
                  <div class="col-sm-4">
                    <input type="text" name="presentase" id="presentase" autocomplete="off" placeholder="Presentase Qty Asuransi" class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                  <div class="col-sm-4">
                    <button type="button" onclick="generate()" class='btn btn-flat btn-primary'><i class="fa fa-refresh"></i> Generate</button>
                  </div>                                    
                </div>                                                                            
                <span id="tampil_data"></span>
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
    }elseif($set=="detail"){
      $row = $dt_rekap->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/rekap_asuransi_unit">
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
            <form class="form-horizontal" action="h1/rekap_asuransi_unit/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Vendor</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" value="<?php echo $row->vendor_name ?>" placeholder="Periode Faktur (Awal)" readonly id="tanggal3" class="form-control">                    
                  </div>                                    
                </div>  
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Periode Faktur (Awal)</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" value="<?php echo $row->tgl_awal ?>" placeholder="Periode Faktur (Awal)" readonly id="tanggal3" class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Periode Faktur (Akhir)</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" value="<?php echo $row->tgl_akhir ?>" id="tanggal2" readonly placeholder="Periode Faktur (Akhir)" class="form-control">                    
                  </div>                                    
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Presentase Qty Asuransi</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" value="<?php echo $row->presentase ?>" readonly placeholder="Presentase Qty Asuransi" class="form-control">                    
                  </div>                                    
                </div>                
                
                
                
                <table class="table table-bordered table-hovered" id="example1" width="100%">
                  <thead>
                    <tr>
                      <th>Kode Tipe</th>
                      <th>Tipe Kendaraan</th>
                      <th>Harga Satuan</th>
                      <th>Qty</th>
                      <th>Total</th>                    
                      <th>Qty Asuransi</th>
                      <th>Total Asuransi</th>                    
                    </tr>   
                  </thead>
                  <tbody>
                  <?php 
                  $no=1;
                  $t1=0;$t2=0;
                  foreach ($dt_rekap2->result() as $isi) {
                    $qty_asur = 0;
                    $asur =  ($isi->qty * $row->presentase) / 100;

                    // if( $row->tgl_rekap >= '2021-01-01' ){
		    if(1){
                      if ($asur <= 1) {
                        $qty_asur = 1;
                      } elseif ($asur > 1) {
                        $qty_asur = floor($asur);
                      }
                    }else{
                      $qty_asur = floor( $asur );
                    }


                    // $asur = ($isi->qty * $row->presentase) / 100;
                    // $bulan = floor($asur);

                    $total = $qty_asur * $isi->harga_satuan;
                    $jum = $dt_rekap->num_rows();
                    $jumlah = $isi->qty * $isi->harga_satuan;
                    echo "
                    <tr>
                      <td>$isi->id_tipe_kendaraan</td>
                      <td>$isi->tipe_ahm</td>
                      <td>".mata_uang2($isi->harga_satuan)."</td>
                      <td>".mata_uang2($isi->qty)."</td>
                      <td>".mata_uang2($jumlah)."</td>
                      <td>".mata_uang2($qty_asur)."</td>
                      <td>".mata_uang2($total)."</td>
                    </tr>
                    ";
                    $t1 = $t1 + $total;
                    $no++;
                  }
                  $cek = $this->m_admin->getByID("ms_rate_asuransi","id_vendor",$row->id_vendor);
                  if($cek->num_rows() > 0){
                    $t = $cek->row();
                    $rate_premi     = ($t->rate_premi * $t1) / 100;
                    $biaya_polis    = $t->biaya_polis;
                    $biaya_materai  = $dt_rekap->row()->biaya_materai; //$t->biaya_materai;
                  }else{
                    $rate_premi     = "";
                    $biaya_polis    = "";
                    $biaya_materai  = "";
                  }
                  ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="5"></td>
                      <td>Total :</td>
                      <td>
                        <input type='hidden' name='total' value='<?php echo $t1 ?>'>
                        <input type='hidden' name='premi_asuransi' value='<?php echo $rate_premi ?>'>
                        <input type='hidden' name='biaya_polis' value='<?php echo $biaya_polis ?>'>
                        <input type='hidden' name='biaya_materai' value='<?php echo $biaya_materai ?>'>        
                        <?php echo mata_uang2($t1) ?>      
                      </td>
                    </tr>
                    <tr>
                      <td colspan="5"></td>
                      <td>Premi Asuransi :</td>
                      <td><?php echo mata_uang2($rate_premi) ?></td>
                    </tr>
                    <tr>
                      <td colspan="5"></td>
                      <td>Biaya Polis :</td>
                      <td><?php echo mata_uang2($biaya_polis) ?></td>
                    </tr>
                    <tr>
                      <td colspan="5"></td>
                      <td>Biaya Materai :</td>
                      <td><?php echo mata_uang2($biaya_materai) ?></td>
                    </tr>               
                    <tr>
                      <td colspan="5"></td>
                      <td>Total Bayar :</td>
                      <td>
                        <?php echo mata_uang2($tt = $rate_premi + $biaya_polis + $biaya_materai) ?>
                        <input type='hidden' name='total_bayar' value='<?php echo $tt ?>'>
                      </td>
                    </tr>               
                  </tfoot> 
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
          <a href="h1/rekap_asuransi_unit/add">            
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
              <th>No Rekap</th>                           
              <th>Tgl Rekap</th>              
              <th>Vendor</th>              
              <th>Periode</th>
              <th>Total</th>              
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
                <a href='h1/rekap_asuransi_unit/detail?id=$row->id_rekap_asuransi'>
                  $row->id_rekap_asuransi
                </a>
              </td>              
              <td>$row->tgl_rekap</td>                            
              <td>$row->vendor_name</td>                            
              <td>$row->tgl_awal s/d $row->tgl_akhir</td>                            
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
<script type="text/javascript">
function generate(){    
  var tanggal1  = document.getElementById("tanggal1").value;   
  var tanggal   = document.getElementById("tanggal").value;   
  var id_vendor = document.getElementById("id_vendor").value;   
  var presentase = document.getElementById("presentase").value;     
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_vendor="+id_vendor+"&tanggal="+tanggal+"&tanggal1="+tanggal1+"&presentase="+presentase;                           
     xhr.open("POST", "h1/rekap_asuransi_unit/t_data", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_data").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}
</script>