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
<body onload="cek_nosin()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Repair</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="detail"){
      $row = $dt_wo->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/wo">
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
            <form class="form-horizontal" action="h1/wo/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Checker</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_checker" readonly value="<?php echo $row->tgl_checker ?>" placeholder="Tanggal wo" class="form-control">
                  </div>                  
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" value="<?php echo $row->no_mesin ?>" placeholder="No Mesin" readonly class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Item</label>
                  <div class="col-sm-4">
                    <input type="text" name="kode_item" value="<?php echo $row->id_item ?>" placeholder="Kode Item" readonly class="form-control">
                  </div>
                </div>                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Sumber Kerusakan</label>
                  <div class="col-sm-4">
                    <input type="text" name="sumber_kerusakan"  value="<?php echo $row->sumber_kerusakan ?>" placeholder="Sumber Kerusakan" readonly class="form-control">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                  <div class="col-sm-4">
                    <input type="text" name="tipe_ahm" value="<?php echo $row->tipe_ahm ?>" readonly placeholder="Tipe Kendaraan" class="form-control">
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-4">
                    <input type="text" name="keterangan" value="<?php echo $row->keterangan ?>" readonly placeholder="Keterangan" class="form-control">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" name="warna" value="<?php echo $row->warna ?>" readonly placeholder="Warna" class="form-control">
                  </div>
                </div>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Ekspedisi</label>
                  <div class="col-sm-4">
                    <input type="text" readonly name="ekspedisi" placeholder="Ekspedisi" value="<?php echo $row->ekspedisi ?>" class="form-control">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Polisi</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_polisi" readonly placeholder="No Polisi" value="<?php echo $row->no_polisi ?>" class="form-control">
                  </div>
                </div>             

                <button type="reset" class="btn btn-warning btn-flat btn-block" disabled>Detail Part</button>                                             
                <br>

                <table id="example2" class="table table-bordered table-hovered myTable1" width="100%">
                  <thead>
                    <tr>
                      <th width='10%'>Part</th>
                      <th width='10%'>Deskripsi</th>
                      <th width='10%'>Gejala</th>
                      <th width='10%'>Penyebab</th>
                      <th width='10%'>Ongkos Kerja</th>
                      <th width='10%'>Keterangan</th>                    
                    </tr>
                  </thead>
                  <tbody>
                    <?php   
                    $dt_check = $this->m_admin->getByID("tr_checker_detail","id_checker",$row->id_checker);
                    foreach($dt_check->result() as $row) {           
                      echo "   
                      <tr>                    
                        <td width='10%'>$row->id_part</td>
                        <td width='20%'>$row->deskripsi</td>
                        <td width='15%''>$row->gejala</td>
                        <td width='15%'>$row->penyebab</td>
                        <td width='10%'>$row->ongkos_kerja</td>
                        <td width='15%'>$row->ket</td>
                      </tr>";                    
                    }
                    ?>  
                  </tbody>
                </table>  

                <br>

                
                
              </div><!-- /.box-body -->
              
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    
    <?php 
    }elseif($set=='close'){
      $row = $dt_wo->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/wo">
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
            <form class="form-horizontal" action="h1/wo/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="no_wo" value="<?php echo $no_wo ?>">
                    <input onchange="cek_nosin()" type="text" required value="<?php echo $no_mesin ?>" class="form-control" placeholder="No Mesin" readonly name="no_mesin" id="no_mesin">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Item</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Kode Item" readonly name="kode_item" id="kode_item">                    
                  </div>                
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Tipe" readonly name="tipe" id="tipe">                    
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="warna"  readonly placeholder="Warna" name="warna">                                        
                  </div>
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Lokasi Lama</label>
                  <div class="col-sm-4">
                    <input type="text" required  class="form-control" readonly placeholder="Lokasi Lama" name="lokasi_lama" id="lokasi_l">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Lokasi Suggest</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="lokasi_s" readonly placeholder="Lokasi Suggest" name="lokasi_suggest">                                        
                  </div>
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Lokasi Baru</label>
                  <div class="col-sm-4">
                    <select class="form-control" required onchange="ambil_slot()" id="lokasi_baru" name="lokasi_baru">
                      <option value="">- choose -</option>
                      <?php                       
                      foreach($dt_lokasi->result() as $val) {
                        echo "
                        <option value='$val->id_lokasi_unit'>$val->id_lokasi_unit - $val->gudang</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Slot</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="slot" id="slot">
                      <option value="">- choose -</option>
                    </select>
                  </div>
                </div>                                                                    
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save And Set Close WO</button>
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
          <!--a href="h1/wo/add">            
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

        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>            
              <th>No WO</th>             
              <th>Tgl WO</th> 
              <th>No Checker</th>
              <th>Tgl Checker</th>
              <th>Tipe Kendaraan</th>              
              <th>Harga Jasa</th>
              <th>No Mesin</th>
              <th width="5%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_wo->result() as $row) {                                         
            $rt = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_tipe_kendaraan 
                ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan WHERE tr_scan_barcode.no_mesin = '$row->no_mesin'");
            if($rt->num_rows()>0){
              $t = $rt->row();
              $tipe_ahm = $t->tipe_ahm;
              $tipe_motor = $t->tipe_motor;
              $nosin = $t->no_mesin;
            }else{
              $tipe_ahm = "";
              $tipe_motor = "";
              $nosin = "";
            }
            if($row->status_wo == 'input'){
              $tom = "<a href='h1/wo/close?id=$row->no_mesin&d=$row->no_wo' class='btn btn-flat btn-danger btn-xs'><i class='fa fa-close'></i> Close WO</a>";
            }else{
              $tom = "<button name=\"cetak\" type=\"button\" class=\"btn bg-maroon btn-flat btn-sm\" 
                  onclick=\"javascript:wincal=window.open('h1/wo/cetak_s?id=$row->no_mesin',
                  'Print','width=600,height=400');\">
                <i class=\"fa fa-print\"></i></button>";
            }
          echo "          
            <tr>
              <td>$no</td>                           
              <td>
                <a href='h1/wo/detail?id=$row->id_checker'>
                  $row->no_wo
                </a>
              </td>              
              <td>$row->tgl_wo</td>
              <td>$row->id_checker</td>              
              <td>$row->tgl_checker</td>              
              <td>$tipe_ahm</td>
              <td>".mata_uang2($row->harga_jasa)."</td>
              <td>$row->no_mesin</td>
              <td>";
              echo $tom;
              echo "
              </td>";                                      
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
<script>
function ambil_slot(){
  var lokasi_baru = $("#lokasi_baru").val(); 
  $.ajax({
    url : "<?php echo site_url('h1/wo/get_slot')?>",
    type:"POST",
    data:"lokasi_baru="+lokasi_baru,
    cache:false,   
    success:function(msg){            
      $("#slot").html(msg);      
    }
  })  
}
</script>
<script type="text/javascript">
function cek_nosin(){
  var no_mesin  = document.getElementById("no_mesin").value;    
  //alert(id_po);  
  $.ajax({
      url : "<?php echo site_url('h1/wo/cek_nosin')?>",
      type:"POST",
      data:"no_mesin="+no_mesin,
      cache:false,
      success:function(msg){            
        data=msg.split("|");                            
        $("#kode_item").val(data[1]);
        $("#tipe").val(data[2]);
        $("#warna").val(data[3]);
        $("#lokasi_l").val(data[4]);
        $("#lokasi_s").val(data[5]);
      }
  })      
}
</script>