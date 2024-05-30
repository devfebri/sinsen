<base href="<?php echo base_url(); ?>" />
<?php if(isset($_GET['id'])){ ?>
<body onload="cek_bank();kirim_data_giro();">
<?php }else{ ?>
<body onload="mulai()">
<?php } ?>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Master Data</li>
    <li class="">Master Finance</li>
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
          <a href="master/cek_giro">
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
            <form class="form-horizontal" action="master/cek_giro/save" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Entry</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" autocomplete="off" id="tanggal1" value="<?php echo date("Y-m-d") ?>"  placeholder="Tgl Entry" name="tgl_buat">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Bank</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="bank" id="bank" onchange="cek_bank()">
                      <option value="">- choose -</option>
                      <?php 
                      $r = $this->m_admin->getAll("ms_bank");
                      foreach ($r->result() as $isi) {
                        echo "<option value='$isi->id_bank'>$isi->bank</option>";
                      }
                      ?>
                    </select>
                    <input type="hidden" id="id_cek_giro"  name="id_cek_giro">
                  </div>
                </div>  
                <span id="upnormal">                                                                                    
                  <div class="form-group">                    
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Giro</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="inputEmail3" placeholder="Kode Giro" name="kode_giro">
                    </div>
                  </div>
                </span>
                <span id="normal">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Dari Nomor</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" placeholder="Dari Nomor" name="dari">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Sampai Nomor</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" placeholder="Sampai Nomor" name="sampai">
                    </div>
                  </div>                                
                </span>  
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label"></label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      Active
                    </div>
                  </div>                  
                </div>               
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="edit"){
      $row = $dt_cek_giro->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/cek_giro">
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
            <form class="form-horizontal" action="master/cek_giro/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_cek_giro ?>">
               <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Entry</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->tgl_buat ?>" class="form-control" id="tanggal1" placeholder="Tgl Entry" name="tgl_buat">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Bank</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="bank" id="bank" onchange="cek_bank()">
                      <option value="<?php echo $row->bank ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_bank","id_bank",$row->bank)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->bank;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $r = $this->m_admin->kondisiCond("ms_bank","id_bank != '$row->bank'");                                                
                      foreach ($r->result() as $isi) {
                        echo "<option value='$isi->id_bank'>$isi->bank</option>";
                      }
                      ?>
                    </select>
                    <input type="hidden" value="<?php echo $row->id_cek_giro ?>" id="id_cek_giro"  name="id_cek_giro">
                  </div>
                </div>                                                                                      
                <div class="form-group">                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Giro</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->kode_giro ?>" class="form-control" id="inputEmail3" placeholder="Kode Giro" name="kode_giro">
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label"></label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->active=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="active" value="1">
                      <?php } ?>
                      Active
                    </div>
                  </div>                  
                </div>                   
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Update</button>                
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
          <a href="master/cek_giro/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
          <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  
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
              <th>Tgl Entry</th>
              <th>Bank</th>
              <th>Kode Giro</th>                                          
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_cek_giro->result() as $row) {                             
            $cek_bank = $this->m_admin->getByID("ms_bank","id_bank",$row->bank)->row()->bank;
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->tgl_buat</td>              
              <td>$cek_bank</td>                        
              <td>$row->kode_giro</td>                                      
              <td>";
              ?>
                <a <?php echo $this->m_admin->set_tombol($id_menu,$group,"delete"); ?> data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/cek_giro/delete?id=<?php echo $row->id_cek_giro ?>"><i class="fa fa-trash-o"></i></a>
                <a <?php echo $this->m_admin->set_tombol($id_menu,$group,"edit"); ?> data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/cek_giro/edit?id=<?php echo $row->id_cek_giro ?>'><i class='fa fa-edit'></i></a>
              </td>
            </tr>
          <?php
          $no++;
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }elseif($set=="views"){
      ?>
  
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="master/cek_giro/add">
              <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
            </a>          
            <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  
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
          
          <table id="tbl_set_giro" class="table table-bordered table-hover">
            <thead>
              <tr>              
                <th width="5%">No</th>
                <th>Tgl Entry</th>
                <th>Bank</th>
                <th>Kode Giro</th>                                          
                <th width="10%">Action</th>
              </tr>
            </thead>
            <tbody>            
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
function hide_giro(){
    $("#tampil_giro").hide();
}
function kirim_data_giro(){    
  $("#tampil_giro").show();
  var id_cek_giro = document.getElementById("id_cek_giro").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_cek_giro="+id_cek_giro;                           
     xhr.open("POST", "master/cek_giro/t_giro", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_giro").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function simpan_giro(){
    var id_cek_giro           = document.getElementById("id_cek_giro").value;       
    var no_cek  = $("#no_cek").val();            
    //alert(id_dealer);
    if (no_cek == "") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
        $.ajax({
            url : "<?php echo site_url('master/cek_giro/save_giro')?>",
            type:"POST",
            data:"id_cek_giro="+id_cek_giro+"&no_cek="+no_cek,
            cache:false,
            success:function(msg){            
                data=msg.split("|");
                if(data[0]=="nihil"){
                    kirim_data_giro();
                    kosong();                
                }else{
                    alert('No Cek/giro ini sudah ditambahkan');
                    kosong();                      
                }                
            }
        })    
    }
}
function kosong(args){
  $("#no_cek").val("");  
}
function hapus_giro(a,b){ 
    var id_cek_giro   = a;   
    var id_cek_giro_detail       = b;       
    $.ajax({
        url : "<?php echo site_url('master/cek_giro/delete_giro')?>",
        type:"POST",
        data:"id_cek_giro_detail="+id_cek_giro_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_giro();
            }
        }
    })
}
function cek_bank(){
  var id = $("#bank").val();
  $.ajax({
    url : "<?php echo site_url('master/cek_giro/cek_bank')?>",
    type:"POST",
    data:"id="+id,
    cache:false,   
    success:function(msg){            
      data=msg.split("|");
      var bank = data[0];            
      if(bank == 'Permata' || bank == 'Bank Permata'){
        $("#normal").hide();
        $("#upnormal").show();
        auto();
      }else{
        $("#normal").show();
        $("#upnormal").hide();
      }
    }
  })  
}
function mulai(){
  $("#normal").show();
  $("#upnormal").hide();
}
</script>



<script type="text/javascript">
    $(document).ready(function() {
      table = $('#tbl_set_giro').DataTable({
        "searchable": false,
        "processing": true, 
        "serverSide": true, 
        "order": [],
        "ajax": {
          "url": "<?php echo site_url('master/cek_giro/fetch') ?>",
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