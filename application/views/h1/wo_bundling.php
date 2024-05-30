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
<?php if(isset($_GET['id'])){ ?>
  <body onload="generate()">
<?php }else{ ?>
  <body onload="kirim_data_pl()">
<?php } ?>
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
          <a href="h1/wo_bundling">
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
            <form class="form-horizontal" action="h1/wo_bundling/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">    
                <input type="hidden" id="mode" value="insert">                   
                <input type="hidden" id="no_wo_bundling" value="">                   
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Paket Bundling</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_paket_bundling" id="id_paket_bundling" onchange="cek_paket()">
                      <option value="">- choose -</option>
                      <?php 
                      $sql = $this->m_admin->getAll("ms_paket_bundling");
                      foreach ($sql->result() as $isi) {
                        echo "<option value='$isi->id_paket_bundling'>$isi->id_paket_bundling</option>";
                      }
                      ?>
                    </select>
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Item</label>
                  <div class="col-sm-4">
                    <input type="text" name="kode_item" id="kode_item" placeholder="Kode Item" readonly class="form-control">
                  </div>                  
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Qty Paket Bundling</label>
                  <div class="col-sm-4">
                    <input type="text" name="qty_paket" onkeypress="return number_only(event)" id="qty_paket" placeholder="Qty Paket Bundling" class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <input type="text" name="tipe_ahm" id="tipe_ahm" placeholder="Tipe" readonly class="form-control">
                  </div>
                </div>                                                    
                <div class="form-group">                  
                  <div class="col-sm-2"></div>
                  <div class="col-sm-4">
                    <button type="button" onclick="generate()" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Generate</button>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" name="warna" readonly id="warna" placeholder="Warna" class="form-control">
                  </div>
                </div>
                <div class="form-group">                  
                  <span id="tampil_data_part"></span>
                </div>
                <div class="form-group">                  
                  <span id="tampil_data_apparel"></span>
                </div>
                <div class="form-group">                  
                  <span id="tampil_data_nosin"></span>
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
    }elseif($set=='detail'){
      $row = $dt_wo->row();
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/wo_bundling">
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
            <form class="form-horizontal" action="h1/wo_bundling/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">  
                  <input type="hidden" id="mode" value="detail">                
                  <input type="hidden" id="no_wo_bundling" value="<?php echo $row->no_wo_bundling ?>">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Paket Bundling</label>
                  <div class="col-sm-4">
                    <input type="text" name="id_paket_bundling" id="id_paket_bundling" placeholder="ID Paket Bundling" value="<?php echo $row->id_paket_bundling ?>" readonly class="form-control">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Item</label>
                  <div class="col-sm-4">
                    <input type="text" name="kode_item" id="kode_item" placeholder="Kode Item" readonly class="form-control">
                  </div>                  
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Qty Paket Bundling</label>
                  <div class="col-sm-4">
                    <input type="text" readonly name="qty_paket" onkeypress="return number_only(event)" value="<?php echo $row->qty_paket ?>" id="qty_paket" placeholder="Qty Paket Bundling" class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <input type="text" name="tipe_ahm" id="tipe_ahm" placeholder="Tipe" readonly class="form-control">
                  </div>
                </div>                                                    
                <div class="form-group">                  
                  <div class="col-sm-2"></div>
                  <div class="col-sm-4">
                    <?php if(isset($_GET['id'])){ ?>
                    <button style="visibility: hidden;" type="button" onclick="generate()" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Generate</button>
                    <?php }else{ ?>
                    <button type="button" onclick="generate()" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Generate</button>                    
                    <?php } ?>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" name="warna" readonly id="warna" placeholder="Warna" class="form-control">
                  </div>
                </div>
                <div class="form-group">                  
                  <span id="tampil_data_part"></span>
                </div>
                <div class="form-group">                  
                  <span id="tampil_data_apparel"></span>
                </div>
                <div class="form-group">                  
                  <span id="tampil_data_nosin"></span>
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
          <a href="h1/wo_bundling/add">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>            
              <th>No WO Bundling</th>             
              <th>Tgl WO Bundling</th> 
              <th>Status</th>              
              <th width="5%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_wo->result() as $row) { 
          if($row->status_paket == 'input'){
            $tom = "<a onclick=\"return confirm('Are you sure to cancel all data?')\" href=\"h1/wo_bundling/batal?id=$row->no_wo_bundling\" type=\"button\" class=\"btn btn-flat btn-warning btn-xs\"><i class=\"fa fa-refresh\"></i> Batal</a>
            <a onclick=\"return confirm('Are you sure to save all data?')\" href=\"h1/wo_bundling/close_wo?id=$row->no_wo_bundling\" type=\"button\" class=\"btn btn-flat btn-danger btn-xs\"><i class=\"fa fa-close\"></i> Close WO Bundling</a>";
          }else{
            $tom = "";
          }                                        
          echo "          
            <tr>
              <td>$no</td>                           
              <td>
                <a href='h1/wo_bundling/detail?id=$row->no_wo_bundling'>
                  $row->no_wo_bundling
                </a>
              </td>              
              <td>$row->tgl_paket</td>              
              <td>$row->status_paket</td>
              <td>
                $tom
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
function cek_paket(){
  var id_paket_bundling = $("#id_paket_bundling").val();  
  //alert(id_paket_bundling);
  $.ajax({
    url : "<?php echo site_url('h1/wo_bundling/cek_paket')?>",
    type:"POST",
    data:"id_paket_bundling="+id_paket_bundling,      
    cache:false,   
    success:function(msg){            
        data=msg.split("|");
        if(data[0]=="nihil"){
          $("#kode_item").val(data[1]);
          $("#tipe_ahm").val(data[2]);
          $("#warna").val(data[3]);
        }
    }
  })  
}
function generate(){
  var qty_paket = document.getElementById("qty_paket").value;    
  if(qty_paket != ""){
    kirim_data_part();
    kirim_data_apparel();
    kirim_data_nosin();
    cek_paket();
  }else{
    alert("Tentukan qty yg diinginkan");
    return false;
  }
}
function kirim_data_part(){    
  $("#tampil_data_part").show();
  var id_paket_bundling = document.getElementById("id_paket_bundling").value; 
  var qty_paket = document.getElementById("qty_paket").value;

  var mode = document.getElementById("mode").value;    
  var no_wo_bundling = document.getElementById("no_wo_bundling").value;    
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "no_pl="+birthday1_js;          
    var data = "id_paket_bundling="+id_paket_bundling+"&mode="+mode+"&no_wo_bundling="+no_wo_bundling+"&qty_paket="+qty_paket;
     xhr.open("POST", "h1/wo_bundling/t_part", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_data_part").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function kirim_data_apparel(){    
  $("#tampil_data_apparel").show();
  var id_paket_bundling = document.getElementById("id_paket_bundling").value;    
  var mode = document.getElementById("mode").value;    
  var no_wo_bundling = document.getElementById("no_wo_bundling").value;   
  var qty_paket = document.getElementById("qty_paket").value;

  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "no_pl="+birthday1_js;          
    var data = "id_paket_bundling="+id_paket_bundling+"&mode="+mode+"&no_wo_bundling="+no_wo_bundling+"&qty_paket="+qty_paket;
     xhr.open("POST", "h1/wo_bundling/t_apparel", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_data_apparel").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function kirim_data_nosin(){    
  $("#tampil_data_nosin").show();
  var id_paket_bundling = document.getElementById("id_paket_bundling").value;    
  var qty_paket = document.getElementById("qty_paket").value;    
  var kode_item = document.getElementById("kode_item").value;    
  var mode = document.getElementById("mode").value;    
  var no_wo_bundling = document.getElementById("no_wo_bundling").value;    
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "no_pl="+birthday1_js;          
    var data = "id_paket_bundling="+id_paket_bundling+"&qty_paket="+qty_paket+"&kode_item="+kode_item+"&mode="+mode+"&no_wo_bundling="+no_wo_bundling;
     xhr.open("POST", "h1/wo_bundling/t_nosin", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_data_nosin").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
</script>