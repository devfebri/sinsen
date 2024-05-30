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
}
.isi_combo{   
  height: 30px;
  border:1px solid #ccc;
  padding-left:1.5px;
}
</style>
<?php if(isset($_GET['id'])){ ?>
<body onload="tampil()">
<?php }else{ ?>
<body onload="tampil();auto();">
<?php } ?>
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Master Data</li>
    <li class="">Unit</li>
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
          <a href="master/paket_bundling">
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
            <form class="form-horizontal" action="master/paket_bundling/save" method="post" enctype="multipart/form-data">
              <div class="box-body">                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Paket Bundling</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" readonly required id="id_paket_bundling" placeholder="ID Paket Bundling" name="id_paket_bundling">
                  </div>                   
                  <div class="col-sm-1">
                    <button type="button" onclick="tampil()" class="btn btn-flat btn-primary btn-sm"><i class="fa fa-refresh"></i> Refresh</button>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Paket Bundling</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required id="inputEmail3" placeholder="Nama Paket Bundling" name="nama_paket_bundling">
                  </div>
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Item Lama</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_item">
                      <option value="">- choose -</option>              
                      <?php 
                      foreach ($dt_item->result() as $isi) {
                        echo "<option value='$isi->id_item'>$isi->id_item</option>";
                      }
                      ?>        
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Item Baru</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_item_baru">
                      <option value="">- choose -</option>                      
                      <?php 
                      foreach ($dt_item->result() as $isi) {
                        echo "<option value='$isi->id_item'>$isi->id_item</option>";
                      }
                      ?>        
                    </select>
                  </div>
                </div>  
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      Active
                    </div>
                  </div>                  
                </div>                  

                <button type="reset" class="btn btn-warning btn-flat btn-block" disabled>Detail Part</button>                                             
                <br>

                <span id="tampil_part"></span>  

                <br>

                <button type="reset" class="btn btn-warning btn-flat btn-block" disabled>Apparel</button>                                             
                <br>

                <span id="tampil_apparel"></span>

                <br>

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
      $row = $dt_paket->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/item">
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
            <form class="form-horizontal" action="master/paket_bundling/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_paket_bundling ?>" />
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Paket Bundling</label>
                  <div class="col-sm-3">
                    <input type="text" value="<?php echo $row->id_paket_bundling ?>" readonly class="form-control" required id="id_paket_bundling" placeholder="ID Paket Bundling" name="id_paket_bundling">
                  </div>
                  <div class="col-sm-1">
                    <button type="button" onclick="tampil()" class="btn btn-flat btn-primary btn-sm"><i class="fa fa-refresh"></i> Refresh</button>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Paket Bundling</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->nama_paket_bundling ?>" class="form-control" required id="inputEmail3" placeholder="Nama Paket Bundling" name="nama_paket_bundling">
                  </div>
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Item Lama</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_item">
                      <option value="<?php echo $row->id_item ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_item","id_item",$row->id_item)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->id_item";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_item = $this->m_admin->kondisiCond("ms_item","id_item != '$row->id_item'");                                                
                      foreach ($dt_item->result() as $isi) {
                        echo "<option value='$isi->id_item'>$isi->id_item</option>";
                      }
                      ?>        
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Item Baru</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_item_baru">
                      <option value="<?php echo $row->id_item_baru ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_item","id_item",$row->id_item_baru)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->id_item";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_item = $this->m_admin->kondisiCond("ms_item","id_item != '$row->id_item_baru'");                                                
                      foreach ($dt_item->result() as $isi) {
                        echo "<option value='$isi->id_item'>$isi->id_item</option>";
                      }
                      ?>        
                    </select>
                  </div>
                </div>  
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      Active
                    </div>
                  </div>                  
                </div>                  

                <button type="reset" class="btn btn-warning btn-flat btn-block" disabled>Detail Part</button>                                             
                <br>

                <span id="tampil_part"></span>  

                <br>

                <button type="reset" class="btn btn-warning btn-flat btn-block" disabled>Apparel</button>                                             
                <br>

                <span id="tampil_apparel"></span>

                <br>
                
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
          <a href="master/paket_bundling/add">
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
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>              
              <th>Kode Paket Bundling</th>                            
              <th>Nama Paket Bundling</th>                            
              <th>Kode Item Lama</th>
              <th>Kode Item Baru</th>
              <th width="5%">Active</th>                            
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_paket_bundling->result() as $row) {          
            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";
          echo "          
            <tr>
              <td>$no</td>              
              <td>$row->id_paket_bundling</td>                            
              <td>$row->nama_paket_bundling</td>                            
              <td>$row->id_item</td>                            
              <td>$row->id_item_baru</td>                            
              <td>$active</td>                            
              <td>";
              ?>
                <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/paket_bundling/delete?id=<?php echo $row->id_paket_bundling ?>"><i class="fa fa-trash-o"></i></a>
                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/paket_bundling/edit?id=<?php echo $row->id_paket_bundling ?>'><i class='fa fa-edit'></i></a>
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
    }
    ?>
  </section>
</div>


<div class="modal fade" id="Apparelmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search Apparel
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example3" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>Kode Apparel</th>
              <th>Nama Apparel</th>                                                  
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          foreach ($dt_apparel->result() as $ve2) {
            echo "
            <tr>
              <td>$no</td>
              <td>$ve2->id_apparel</td>
              <td>$ve2->apparel</td>";
              ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="chooseitem2('<?php echo $ve2->id_apparel; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
              </td>           
            </tr>
            <?php
            $no++;
          }
          ?>
          </tbody>
        </table>
      </div>      
    </div>
  </div>
</div>

<div class="modal fade" id="Partmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search Part
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
       <table id="table" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>
              <th>ID Part</th>
              <th>Nama Part</th>
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>                     
          </tbody>
        </table>
      </div>      
    </div>
  </div>
</div>

<!-- Modal Detail -->
<div class="modal fade Partmodal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title">Search Part</h4>
      </div>
      <div class="modal-body" id="showBrowse">
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">

var table;

$(document).ready(function() {
    //datatables
    table = $('#table').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('master/paket_bundling/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });
});

</script>
<script type="text/javascript">
  $('.Partmodal').on('shown.bs.modal', function() {
      var id=1;
        $.ajax({
             url:"<?php echo site_url('master/paket_bundling/browsePart');?>",
             type:"POST",
             data:"id="+id,
             cache:false,
             success:function(html){
                $("#showBrowse").html(html);
             }
        });
  });
</script>

<!-- End Of Modal Detail -->

<script type="text/javascript">
function openModal()
{  
  var id = 1;
  $("#Partmodal").modal();
    //$(e.currentTarget).find('input[name="bookId"]').val(bookId);
    $.ajax({
        url: "<?php echo site_url('master/paket_bundling/browsePart')?>",
        type:"POST",
        data:"id="+id,
        cache:false,
        success:function(msg){                
           $('#showBrowse').html(msg);           
        } 
    })
  
}

function tampil(){
  tampil_a();
  tampil_p();  
}
function tampil_a(){  
  kirim_data_apparel();
}
function tampil_p(){
  kirim_data_part();
}
function chooseitem(id_part){
  document.getElementById("id_part").value = id_part; 
  cek_item();
  $("#Partmodal").modal("hide");
}
function auto(){
  var id = 90;
  $.ajax({
      url: "<?php echo site_url('master/paket_bundling/cari_id')?>",
      type:"POST",
      data:"id="+id,
      cache:false,
      success:function(msg){                
          data=msg.split("|");          
          $("#id_paket_bundling").val(data[0]);                            
      } 
  })
}
function cek_item(){
  var id_part   = $("#id_part").val();                         
  $.ajax({
      url: "<?php echo site_url('master/paket_bundling/cek_part')?>",
      type:"POST",
      data:"id_part="+id_part,
      cache:false,
      success:function(msg){                
          data=msg.split("|");
          if(data[0]=="ok"){          
            $("#id_part").val(data[1]);                
            $("#nama_part").val(data[2]);                            
            $("#qty").focus();                            
          }else{
            alert(data[0]);
          }
      } 
  })
}
function chooseitem2(id_apparel){
  document.getElementById("id_apparel").value = id_apparel; 
  cek_apparel();
  $("#Apparelmodal").modal("hide");
}
function cek_apparel(){
  var id_apparel   = $("#id_apparel").val();                         
  $.ajax({
      url: "<?php echo site_url('master/paket_bundling/cek_apparel')?>",
      type:"POST",
      data:"id_apparel="+id_apparel,
      cache:false,
      success:function(msg){                
          data=msg.split("|");
          if(data[0]=="ok"){          
            $("#id_apparel").val(data[1]);                
            $("#apparel").val(data[2]);                            
            $("#qty_apparel").focus();                            
          }else{
            alert(data[0]);
          }
      } 
  })
}
function simpan_part(){  
  var id_part               = document.getElementById("id_part").value;   
  var id_paket_bundling     = document.getElementById("id_paket_bundling").value;   
  var qty                   = document.getElementById("qty").value;       
  //alert(id_po);
  if (id_paket_bundling == "" || id_part == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
    $.ajax({
        url : "<?php echo site_url('master/paket_bundling/save_part')?>",
        type:"POST",
        data:"id_paket_bundling="+id_paket_bundling+"&id_part="+id_part+"&qty="+qty,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
                tampil_p();
                kosong();                
            }else{
                alert('Part ini sudah ditambahkan');
                kosong();                      
            }                
        }
    })    
  }  
}

function kosong(args){  
  $("#id_part").val("");   
  $("#nama_part").val("");   
  $("#qty").val("");     
}
function hapus_part(a,b){ 
    var id_paket_bundling_detail  = a;   
    var id_part   = b;       
    $.ajax({
        url : "<?php echo site_url('master/paket_bundling/delete_part')?>",
        type:"POST",
        data:"id_paket_bundling_detail="+id_paket_bundling_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              tampil_p();
            }
        }
    })
}
function kirim_data_part(){    
  $("#tampil_part").show();
  var id_paket_bundling = document.getElementById("id_paket_bundling").value;   
  var mode = "input";
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_paket_bundling="+id_paket_bundling+"&mode="+mode;                           
     xhr.open("POST", "master/paket_bundling/t_part", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_part").innerHTML = xhr.responseText;
                hitung();
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}

function simpan_apparel(){  
  var id_apparel            = document.getElementById("id_apparel").value;   
  var id_paket_bundling     = document.getElementById("id_paket_bundling").value;   
  var qty_apparel           = document.getElementById("qty_apparel").value;       
  //alert(id_po);
  if (id_paket_bundling == "" || id_apparel == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
    $.ajax({
        url : "<?php echo site_url('master/paket_bundling/save_apparel')?>",
        type:"POST",
        data:"id_paket_bundling="+id_paket_bundling+"&id_apparel="+id_apparel+"&qty_apparel="+qty_apparel,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
                tampil_a();
                kosong2();                
            }else{
                alert('Apparel ini sudah ditambahkan');
                kosong2();                      
            }                
        }
    })    
  }  
}

function kosong2(args){  
  $("#id_apparel").val("");   
  $("#apparel").val("");   
  $("#qty_apparel").val("");     
}
function hapus_apparel(a,b){ 
    var id_paket_bundling_app  = a;   
    var id_apparel  = b;       
    $.ajax({
        url : "<?php echo site_url('master/paket_bundling/delete_apparel')?>",
        type:"POST",
        data:"id_paket_bundling_app="+id_paket_bundling_app,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              tampil_a();
            }
        }
    })
}
function kirim_data_apparel(){    
  $("#tampil_apparel").show();
  var id_paket_bundling = document.getElementById("id_paket_bundling").value;   
  var mode = "input";
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_paket_bundling="+id_paket_bundling+"&mode="+mode;                           
     xhr.open("POST", "master/paket_bundling/t_apparel", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_apparel").innerHTML = xhr.responseText;  
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
</script>