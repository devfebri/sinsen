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
<body onload="auto()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Dealer</li>
    <li class="">Proses BBN</li>
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
          <a href="h1/penyerahan_srut">
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
            <form class="form-horizontal" autocomplete="off" action="h1/penyerahan_srut/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Tanda Terima</label>
                  <div class="col-sm-4">
                    <!-- <input type="hidden" name="no_serah_terima" id="no_serah_terima"> -->
                    <input type="text" name="tgl_terima" id="tanggal2" placeholder="Tanggal Tanda Terima" class="form-control">
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer" id="id_dealer">
                      <option value="">- choose -</option>
                      <?php 
                      $dealer = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");
                      foreach($dealer->result() as $isi) {
                        echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md | $isi->nama_dealer</option>";
                      }
                      ?>
                    </select>
                  </div>                                                  
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                  <div class="col-sm-4">
                    <button type="button" onclick="generate()" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Generate</button>
                  </div>  
                </div>
                <span id="tampil_penyerahan_srut"></span>                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="detail"){
      $row = $dt_penyerahan_srut->row();      
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/terima_srut">
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
        <div class="row">
          <div class="col-md-12">            
            <form class="form-horizontal" autocomplete="off" action="h1/penyerahan_srut/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Tanda Terima</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly name="tgl_terima" value="<?php echo $row->tgl_faktur ?>" placeholder="Tanggal Tanda Terima" class="form-control">
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <?php $t = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row() ?>
                    <input type="text" readonly name="id_dealer" value="<?php echo "$t->kode_dealer_md - $t->nama_dealer"; ?>" placeholder="ID Dealer" class="form-control">
                  </div>                                                  
                </div>                
                <table id="example2" class="table myTable1 table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>No SRUT</th>
                      <th>No SRUT dr Pemohon</th>
                      <th>Tahun Pembuatan</th>            
                    </tr>
                  </thead>
                 
                  <tbody>                    
                    <?php   
                    $dt_srut = $this->db->query("SELECT tr_srut.* FROM tr_srut INNER JOIN tr_penyerahan_srut_detail 
                        ON tr_srut.no_mesin = tr_penyerahan_srut_detail.no_mesin                        
                        WHERE tr_penyerahan_srut_detail.no_serah_terima = '$row->no_serah_terima'");                    
                    foreach($dt_srut->result() as $isi) {                                         
                      echo "
                      <tr>                     
                        <td>$isi->no_mesin</td> 
                        <td>$isi->no_rangka</td> 
                        <td>$isi->no_srut</td> 
                        <td>$isi->no_srut_pemohon</td> 
                        <td>$isi->tahun_pembuatan</td>                       
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
    }elseif($set=="terima"){
      $row = $dt_penyerahan_srut->row();      
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/terima_srut">
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
        <div class="row">
          <div class="col-md-12">            
            <form class="form-horizontal" autocomplete="off" action="dealer/terima_srut/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Tanda Terima</label>
                  <div class="col-sm-4">                    
                    <input type="hidden" name="no_serah_terima" value="<?php echo $row->no_serah_terima ?>">
                    <input type="text" readonly name="tgl_terima" value="<?php echo $row->tgl_faktur ?>" placeholder="Tanggal Tanda Terima" class="form-control">
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <?php $t = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row() ?>
                    <input type="text" readonly name="id_dealer" value="<?php echo "$t->kode_dealer_md - $t->nama_dealer"; ?>" placeholder="ID Dealer" class="form-control">
                  </div>                                                  
                </div>                <br>
                <button class="btn btn-block btn-primary btn-flat" disabled>DETAIL</button>
                <table id="table" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>No SRUT</th>
                      <th>No SRUT dr Pemohon</th>
                      <th>Tahun Pembuatan</th>  
                      <th></th>          
                    </tr>
                  </thead>
                 
                  <tbody>                    
                    <?php   
                    $dt_srut = $this->db->query("SELECT tr_srut.* FROM tr_srut INNER JOIN tr_penyerahan_srut_detail 
                        ON tr_srut.no_mesin = tr_penyerahan_srut_detail.no_mesin                        
                        WHERE tr_penyerahan_srut_detail.no_serah_terima = '$row->no_serah_terima'");                    
                    $no=1;
                    foreach($dt_srut->result() as $isi) {                
                      $jum = $dt_srut->num_rows();
                      $cek = $this->m_admin->getByID("tr_terima_srut_detail","no_mesin",$isi->no_mesin);
                      if($cek->num_rows() > 0){
                        $is = "checked disabled";
                      }else{
                        $is = "";
                      }                         
                      echo "
                      <tr>                     
                        <td>$isi->no_mesin</td> 
                        <td>$isi->no_rangka</td> 
                        <td>$isi->no_srut</td> 
                        <td>$isi->no_srut_pemohon</td> 
                        <td>$isi->tahun_pembuatan</td> 
                        <td align='center'>
                          <input type='hidden' name='jum' value='$jum'>
                          <input type='checkbox' name='cek_nosin_$no' $is>
                          <input type='hidden' name='no_mesin_$no' value='$isi->no_mesin'>
                        </td>                      
                      </tr>";                      
                      $no++;
                      }
                    ?>
                  </tbody>
                </table>     
              </div><!-- /.box-body -->          
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>                
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
              <th>No Serah Terima</th>              
              <th>Tgl Serah Terima</th>                        
              <th>Jumlah SRUT</th>
              <th>Status</th>
              <th width="10%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_penyerahan_srut->result() as $row) {                 
            $cek = $this->m_admin->getByID("tr_penyerahan_srut_detail","no_serah_terima",$row->no_serah_terima);
            if($cek->num_rows() > 0){
              $jum = $cek->num_rows();
            }else{
              $jum = 0;
            }
            $cek = $this->m_admin->getByID("tr_terima_srut","no_serah_terima",$row->no_serah_terima);
            if($cek->num_rows() > 0){
              $t = $cek->row();
              $status = "<span class='label label-success'>$row->status</span>";
            }else{
              $status = "<span class='label label-warning'>input</span>";
            }
          echo "          
            <tr>
              <td>$no</td>
              <td>
                <a href='dealer/terima_srut/detail?id=$row->no_serah_terima'>
                  $row->no_serah_terima
                </a>
              </td>
              <td>$row->tgl_faktur</td>                                                       
              <td>$jum Unit</td>                            
              <td>$status</td>
              <td>                                
                <a href='dealer/terima_srut/terima?id=$row->no_serah_terima' class='btn btn-primary btn-flat btn-xs'>Terima</a>                                                
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
<script type="text/javascript">
function auto(){
  var id = 1;
  $.ajax({
      url : "<?php echo site_url('h1/penyerahan_srut/cari_id')?>",
      type:"POST",
      data:"id="+id,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#no_serah_terima").val(data[0]);                
      }        
  })
}
function generate(){    
  $("#tampil_penyerahan_srut").show();
  var tgl_terima  = document.getElementById("tanggal2").value;   
  var id_dealer   = document.getElementById("id_dealer").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "tgl_terima="+tgl_terima+"&id_dealer="+id_dealer;                           
     xhr.open("POST", "h1/penyerahan_srut/t_penyerahan_srut", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_penyerahan_srut").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}
</script>