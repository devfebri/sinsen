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
    if($set=="insert"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/rubbing">
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
            <form class="form-horizontal" action="h1/rubbing/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin NRFS</label>
                  <div class="col-sm-3">
                    <select class="form-control select2" name="no_mesin" id="no_mesin">
                      <option value="">- choose -</option>
                      <?php 
                      $tr = $this->db->query("SELECT * FROM tr_checker WHERE status_checker <> 'close' OR status_checker IS NULL ORDER BY no_mesin ASC");
                      foreach ($tr->result() as $isi) {
                        $cek = $this->m_admin->getByID("tr_rubbing","no_mesin_rusak",$isi->no_mesin);                        
                        if($cek->num_rows() == 0){
                          echo "<option value='$isi->no_mesin'>$isi->no_mesin ($isi->sumber_kerusakan)</option>";
                        }
                      }
                      ?>
                    </select>
                  </div>                  
                  <div class="col-sm-1">
                    <button onclick="generate()" type="button" class="btn btn-flat btn-sm btn-primary"><i class="fa fa-refresh"></i> Generate</button>
                  </div>  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Item</label>
                  <div class="col-sm-4">
                    <input type="text" name="kode_item" placeholder="Kode Item" readonly class="form-control" id="kode_item">
                  </div>                  
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Sumber Rubbing</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="sumber_rubbing" id="sumber"> 
                      <option value="">- choose -</option>
                      <?php 
                      $tre = $this->db->query("SELECT * FROM tr_scan_barcode WHERE status = '1' ORDER BY tipe ASC");
                      foreach ($tre->result() as $isi2) {
                        echo "<option value='$isi2->no_mesin'>$isi2->no_mesin ($isi2->tipe)</option>";
                      }
                      ?>
                    </select>
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <input type="text" name="tipe_ahm" placeholder="Tipe" id="tipe" readonly class="form-control">
                  </div>
                </div>                                                    
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-4">
                    <input type="text" name="keterangan" id="keterangan" placeholder="Keterangan" class="form-control">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" name="warna" id="warna" readonly placeholder="Warna" class="form-control">
                  </div>
                </div>
                

                <div class="form-group">
                  <span id="tampil_data"></span>
                </div>
                
                
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
    }elseif($set=='approval'){
      $row = $dt_rubbing->row();
    ?>


    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/rubbing">
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
            <form class="form-horizontal" action="h1/rubbing/save_approval" method="post" enctype="multipart/form-data">
              <div class="box-body">        
                <button class="btn btn-block btn-flat btn-primary" disabled>No Mesin yg Diperbaiki</button><br>     
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $row->no_mesin_rusak ?>" placeholder="No Mesin" readonly name="no_mesin" id="no_mesin">                    
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
                    <input type="text" required class="form-control" readonly placeholder="Lokasi Lama" name="lokasi_lama" id="lokasi_l">                    
                  </div>
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">Lokasi Suggest</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="lokasi_s" readonly placeholder="Lokasi Suggest" name="lokasi_suggest">                                        
                  </div> -->
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

              <hr>
              <button class="btn btn-block btn-flat btn-warning" disabled>No Mesin Sumber Rubbing</button><br>                 
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $row->sumber_rubbing ?>" placeholder="No Mesin" readonly name="no_mesin2" id="sumber_rubbing">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Item</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Kode Item" readonly name="kode_item2" id="kode_item2">                    
                  </div>                
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Tipe" readonly name="tipe2" id="tipe2">                    
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="warna2"  readonly placeholder="Warna" name="warna2">                                        
                  </div>
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Lokasi Lama</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly placeholder="Lokasi Lama" name="lokasi_lama2" id="lokasi_l2">                    
                  </div>
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">Lokasi Suggest</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="lokasi_s2" readonly placeholder="Lokasi Suggest" name="lokasi_suggest2">                                        
                  </div> -->
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Lokasi Baru</label>
                  <div class="col-sm-4">
                    <select class="form-control" required onchange="ambil_slot2()" id="lokasi_baru2" name="lokasi_baru2">
                      <option value="">- choose -</option>
                      <?php                       
                      foreach($dt_lokasi2->result() as $val) {
                        echo "
                        <option value='$val->id_lokasi_unit'>$val->id_lokasi_unit - $val->gudang</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Slot</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="slot2" id="slot2">
                      <option value="">- choose -</option>
                    </select>
                  </div>
                </div>  
                <div class="form-group">
                  <button type="reset" class="btn btn-warning btn-flat btn-block" disabled>Detail Part No Mesin NRFS</button>                                             
                  <br>

                  <table id="example2" class="table table-bordered table-hovered myTable1" width="100%">
                    <thead>
                      <tr>
                        <th width='10%'>ID Part</th>
                        <th width='10%'>Nama Part</th>                    
                      </tr>  
                    </thead>
                    <tbody>
                      <?php 
                      $no=1;
                      $dt_nosin = $this->db->query("SELECT * FROM tr_rubbing_detail INNER JOIN ms_part ON tr_rubbing_detail.id_part=ms_part.id_part 
                        WHERE tr_rubbing_detail.no_rubbing = '$row->no_rubbing'");  
                      foreach($dt_nosin->result() as $row) {           
                        $cek = $this->db->query("SELECT * FROM tr_rubbing_detail WHERE no_mesin = '$row->no_mesin' AND id_part = '$row->id_part' AND cek = 'ya'");
                        if($cek->num_rows() > 0){
                          $c = 'checked';
                        }else{
                          $c = '';
                        }
                        $jum = $dt_nosin->num_rows();
                        echo "   
                        <tr>                    
                          <td width='10%'>$row->id_part</td>
                          <td width='20%'>
                            $row->nama_part
                            <input type='hidden' value='$jum' name='jum'>
                            <input type='hidden' value='$row->id_part' name='id_part_$no'>
                            <input type='hidden' value='$row->no_mesin' name='no_mesin_$no'>                            
                          </td>
                        </tr>";    
                        $no++;
                        }
                      ?> 
                    </tbody>  
                  </table>                    

                </div>                                                                  
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=='detail'){
      $row = $dt_rubbing->row(); 
    ?>


        <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/rubbing">
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
            <form class="form-horizontal" action="h1/rubbing/save_approve" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin NRFS</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="kode_item" placeholder="Kode Item" readonly class="form-control" value="<?php echo $row->no_mesin_rusak ?>" id="kode_item">
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Item</label>
                  <div class="col-sm-4">
                    <input type="text" name="kode_item" placeholder="Kode Item" value="<?php echo $row->id_item ?>" readonly class="form-control" id="kode_item">
                  </div>                  
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Sumber Rubbing</label>
                  <div class="col-sm-4">
                    <input type="text" name="kode_item" placeholder="Kode Item" value="<?php echo $row->sumber_rubbing ?>" readonly class="form-control" id="kode_item">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <input type="text" name="tipe_ahm" placeholder="Tipe" id="tipe" value="<?php echo $row->tipe_ahm ?>" readonly class="form-control">
                  </div>
                </div>                                                    
                <div class="form-group">                  
                  <div class="col-sm-6"></div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" name="warna" id="warna" value="<?php echo $row->warna ?>" readonly placeholder="Warna" class="form-control">
                  </div>
                </div>
                

                <div class="form-group">
                  <button type="reset" class="btn btn-warning btn-flat btn-block" disabled>Detail Part No Mesin NRFS</button>                                             
                  <br>

                  <table id="example2" class="table table-bordered table-hovered myTable1" width="100%">
                    <thead>
                      <tr>
                        <th width='10%'>ID Part</th>
                        <th width='10%'>Nama Part</th>                    
                      </tr>  
                    </thead>
                    <tbody>
                      <?php 
                      $no=1;
                      $dt_nosin = $this->db->query("SELECT * FROM tr_rubbing_detail INNER JOIN ms_part ON tr_rubbing_detail.id_part=ms_part.id_part 
                        WHERE tr_rubbing_detail.no_rubbing = '$row->no_rubbing'");  
                      foreach($dt_nosin->result() as $row) {           
                        $cek = $this->db->query("SELECT * FROM tr_rubbing_detail WHERE no_mesin = '$row->no_mesin' AND id_part = '$row->id_part' AND cek = 'ya'");
                        if($cek->num_rows() > 0){
                          $c = 'checked';
                        }else{
                          $c = '';
                        }
                        $jum = $dt_nosin->num_rows();
                        echo "   
                        <tr>                    
                          <td width='10%'>$row->id_part</td>
                          <td width='20%'>
                            $row->nama_part
                            <input type='hidden' value='$jum' name='jum'>
                            <input type='hidden' value='$row->id_part' name='id_part_$no'>
                            <input type='hidden' value='$row->no_mesin' name='no_mesin_$no'>                            
                          </td>
                        </tr>";    
                        $no++;
                        }
                      ?> 
                    </tbody>  
                  </table>                    

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
          <a href="h1/rubbing/add">            
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
        <table id="example2" class="table table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>            
              <th>No Rubbing</th>             
              <th>Tgl Rubbing</th> 
              <th>No mesin Rusak</th>              
              <th>Sumber Rubbing</th>
              <th>Status</th>
              <th width="5%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_rubbing->result() as $row) {                                         
            if($row->status_rubbing=='input'){
              $status = "<span class='label label-primary'>input</span>";
              $tom = "<a href='h1/rubbing/approval?id=$row->no_rubbing' type='button' class='btn btn-flat btn-primary btn-xs'><i class='fa fa-check'></i> Approve</a>";
            }else{
              $status = "<span class='label label-danger'>closed</span>";
              $tom = "";
            }
          echo "          
            <tr>
              <td>$no</td>                           
              <td>
                <a href='h1/rubbing/detail?id=$row->no_rubbing'>
                  $row->no_rubbing
                </a>
              </td>              
              <td>$row->tgl_rubbing</td>              
              <td>$row->no_mesin_rusak</td>
              <td>$row->sumber_rubbing</td>
              <td>$status</td>
              <td>";
              echo $tom; ?>                                
              </td>        
          <?php                           
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
  var no_mesin = $("#no_mesin").val();  
  //alert(no_mesin);
  $.ajax({
      url : "<?php echo site_url('h1/rubbing/cari_nosin')?>",
      type:"POST",
      data:"no_mesin="+no_mesin,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        if(data[0]=='ok'){
          $("#kode_item").val(data[1]);              
          $("#tipe").val(data[2]);              
          $("#warna").val(data[3]);              
          tampil_data();
        }else{
          alert(data[0]);
        }        
      }
  })        
} 
function tampil_data(){    
  $("#tampil_data").show();
  var no_mesin = document.getElementById("no_mesin").value;     
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "no_mesin="+no_mesin;
     xhr.open("POST", "h1/rubbing/t_data", true); 
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
<script>
function ambil_slot(){
  var lokasi_baru = $("#lokasi_baru").val(); 
  $.ajax({
    url : "<?php echo site_url('h1/rubbing/get_slot')?>",
    type:"POST",
    data:"lokasi_baru="+lokasi_baru,
    cache:false,   
    success:function(msg){            
      $("#slot").html(msg);      
    }
  })  
}
function ambil_slot2(){
  var lokasi_baru = $("#lokasi_baru2").val(); 
  $.ajax({
    url : "<?php echo site_url('h1/rubbing/get_slot')?>",
    type:"POST",
    data:"lokasi_baru="+lokasi_baru,
    cache:false,   
    success:function(msg){            
      $("#slot2").html(msg);      
    }
  })  
}
</script>
<script type="text/javascript">
function choose_nosin(nosin){
  document.getElementById("no_mesin").value = nosin;   
  cek_nosin();
  $("#nosin_modal").modal("hide");
}
function cek_nosin(){
  var no_mesin  = document.getElementById("no_mesin").value;    
  var tipe      = "RFS";    
  //alert(id_po);  
  $.ajax({
      url : "<?php echo site_url('h1/rubbing/cek_nosin')?>",
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
        cek_nosin2();
      }
  })      
}
function cek_nosin2(){
  var no_mesin  = document.getElementById("sumber_rubbing").value;    
  var tipe      = "NRFS";    
  //alert(id_po);  
  $.ajax({
      url : "<?php echo site_url('h1/rubbing/cek_nosin')?>",
      type:"POST",
      data:"no_mesin="+no_mesin,
      cache:false,
      success:function(msg){            
        data=msg.split("|");                            
        $("#kode_item2").val(data[1]);
        $("#tipe2").val(data[2]);
        $("#warna2").val(data[3]);
        $("#lokasi_l2").val(data[4]);
        $("#lokasi_s2").val(data[5]);
      }
  })      
}


</script>