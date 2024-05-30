<base href="<?php echo base_url(); ?>" />
<body onload="kirim_data()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Master Data</li>    
    <li class="">Dealer</li>    
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
          <a href="master/diskon_oli_reguler">
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
            <form method="POST" role="form" enctype="multipart/form-data" action="master/diskon_oli_reguler/save" class="form-horizontal form-groups-bordered">
              <div class="box-body">                                                                                                                                                    
                <span id="detail_oli"></span>
              </div>
              

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

    ?>    
    <body onload="take_gudang()">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/diskon_oli_reguler">
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
            <form method="POST" role="form" enctype="multipart/form-data" action="master/diskon_oli_reguler/update" class="form-horizontal form-groups-bordered">
              <div class="box-body">                                                                                                                                                    
                <table id="example2" class="table table-bordered table-hover">
                  <tr>
                    <th width="12%">Kode Part</th>
                    <th width="28%">Nama Part</th>
                    <th width="15%">Tipe Diskon</th>
                    <th width="10%">Range Diskon 1</th>
                    <th width="10%">Range Diskon 2</th>
                    <th width="10%">Range Diskon 3</th>
                    <th width="7%">Aksi</th>                          
                  </tr>
                  <?php 
                  $no=1;
                  $sql = $this->db->query("SELECT * FROM ms_diskon_oli LEFT JOIN ms_part ON ms_diskon_oli.id_part = ms_part.id_part ORDER BY id_diskon_oli ASC");
                  foreach ($sql->result() as $isi) { ?>                    
                    <tr>
                      <td>
                        <input type='text' readonly name='id_part' class='form-control isi' value='<?php echo $isi->id_part ?>'>
                      </td>
                      <td>
                        <input type='text' readonly name='nama_part' class='form-control isi' value='<?php echo $isi->nama_part ?>'>
                      </td>                      
                      <td>
                        <select id='tipe_diskon' readonly class='form-control isi'>
                          <option <?php if($isi->tipe_diskon=='') echo "selected" ?> value=''>- choose -</option>
                          <option <?php if($isi->tipe_diskon=='Rupiah') echo "selected" ?>>Rupiah</option>
                          <option <?php if($isi->tipe_diskon=='Persen') echo "selected" ?>>Persen</option>
                        </select>
                      </td>
                      <td>
                        <input type='text' name='range1' readonly value="<?php echo $isi->range_1 ?>" placeholder="Range Diskon 1" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" id="range2" readonly placeholder="Range Diskon 2" value="<?php echo $isi->range_2 ?>" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" id="range3" readonly placeholder="Range Diskon 3" value="<?php echo $isi->range_3 ?>" class="form-control isi">
                      </td>
                      <td>
                        <a href="master/diskon_oli_reguler/delete?id=<?php echo $isi->id_diskon_oli ?>">
                          <button class="btn btn-danger btn-xs btn-flat" onclick="return confirm('Are you sure want to delete this data?')" type="button"><i class="fa fa-trash"></i></button>                      
                        </a>
                        <button data-toggle="modal" data-target="#Modal_edit" class="btn btn-warning btn-xs btn-flat" type="button" onclick="edit_diskon(<?php echo $isi->id_diskon_oli ?>)"><i class="fa fa-edit"></i></button>                      
                      </td>
                    <tr>
                    <?php 
                  }
                  ?>
                </table>       
              </div>              
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
          <a href="master/diskon_oli_reguler/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
          <a href="master/diskon_oli_reguler/edit">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"edit"); ?> class="btn btn-warning btn-flat margin"><i class="fa fa-edit"></i> Edit</button>
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
              <th>Kode Part Oli</th>
              <th>Nama Oli</th>             
              <th>Tipe Diskon</th>              
              <th>Range Diskon 1</th>              
              <th>Range Diskon 2</th>              
              <th>Range Diskon 3</th>                            
            </tr>
          </thead>
          <tbody>
            <?php 
            $no=1;
            foreach ($dt_diskon_oli_reguler->result() as $val) {                
              echo"
              <tr>
                <td>$no</td>
                <td>$val->id_part</td>
                <td>$val->nama_part</td>
                <td>$val->tipe_diskon</td>                
                <td>$val->range_1</td>                
                <td>$val->range_2</td>                
                <td>$val->range_3</td>                
              </tr>";                               
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
<div class="modal fade modal_edit" id="Modal_edit">
  <div class="modal-dialog" style="width: 90%;">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title">Edit Diskon Oli Reguler</h4>
      </div>
      <form action="master/diskon_oli_reguler/update_detail" method="post">
        <div class="modal-body" id="showEdit">
        </div>
        <div class="modal-footer">
          <p align="center">
            <button type="submit" class="btn btn-primary pull-right">Simpan</button>
          </p>
        </div>
      </form> 
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
              <th>Kode Part</th>
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


<script type="text/javascript">
function edit_diskon(id)
  {
      $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('master/diskon_oli_reguler/edit_diskon');?>",
               type:"POST",
               data:"id_diskon_oli="+id,
               cache:false,
               success:function(data){
                  $('#loading-status').hide();                  
                  $('#showEdit').html(data);                  
               },
               statusCode: {
            500: function() {
              $('#loading-status').hide();
              alert("Something Wen't Wrong");
            }
          }
          });
  }
function take_part(){
  var id_part = $("#id_part").val();                       
  $.ajax({
      url: "<?php echo site_url('master/diskon_oli_reguler/take_part')?>",
      type:"POST",
      data:"id_part="+id_part,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");                    
          $("#id_part").val(data[0]);                                                    
          $("#nama_part").val(data[1]);                                                              
      } 
  })
}
function chooseitem(id_part){
  document.getElementById("id_part").value = id_part; 
  take_part();
  $("#Partmodal").modal("hide");
}
</script>
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
            "url": "<?php echo site_url('master/diskon_oli_reguler/ajax_list')?>",
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
function kirim_data(){    
  $("#detail_oli").show();
  var id = 1;
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id="+id;                           
     xhr.open("POST", "master/diskon_oli_reguler/t_detail", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("detail_oli").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function addDetail(){
    var id_part   = document.getElementById("id_part").value;   
    var nama_part   = document.getElementById("nama_part").value;   
    var tipe_diskon   = document.getElementById("tipe_diskon").value;   
    var range1   = document.getElementById("range1").value;   
    var range2   = document.getElementById("range2").value;   
    var range3   = document.getElementById("range3").value;   
    
    
    if (id_part == "") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
        $.ajax({
            url : "<?php echo site_url('master/diskon_oli_reguler/save_detail')?>",
            type:"POST",
            data:"id_part="+id_part+"&tipe_diskon="+tipe_diskon+"&range1="+range1+"&range2="+range2+"&range3="+range3+"&nama_part="+nama_part,
            cache:false,
            success:function(msg){            
                data=msg.split("|");
                if(data[0]!="failed"){
                    kirim_data();
                    kosong();                
                }else{
                    alert('Detail ini sudah ditambahkan');
                    kosong();                      
                }                
            }
        })    
    }
}
function saveEditDetail(){
    var id_diskon_oli   = document.getElementById("id_diskon_oli").value;   
    var id_part   = document.getElementById("id_part").value;   
    var nama_part   = document.getElementById("nama_part").value;   
    var tipe_diskon   = document.getElementById("tipe_diskon").value;   
    var range1   = document.getElementById("range1").value;   
    var range2   = document.getElementById("range2").value;   
    var range3   = document.getElementById("range3").value;   
    
    
    if (id_part == "") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
        $.ajax({
            url : "<?php echo site_url('master/diskon_oli_reguler/update_detail')?>",
            type:"POST",
            data:"id_part="+id_part+"&tipe_diskon="+tipe_diskon+"&range1="+range1+"&range2="+range2+"&range3="+range3+"&nama_part="+nama_part+"&id_diskon_oli="+id_diskon_oli,
            cache:false,
            success:function(msg){            
                data=msg.split("|");
                if(data[0]!="failed"){
                    alert('ok');
                }else{
                    alert('Detail ini sudah ditambahkan');
                }                
            }
        })    
    }
}
function kosong(args){
  $("#id_part").val("");  
  $("#nama_part").val("");  
  $("#tipe_diskon").val("");  
  $("#range1").val("");  
  $("#range2").val("");  
  $("#range3").val("");  
}
function delDetail(a){ 
    var id  = a;       
    $.ajax({
        url : "<?php echo site_url('master/diskon_oli_reguler/delete_detail')?>",
        type:"POST",
        data:"id="+id,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]!="failed"){
              kirim_data();
            }else{
              alert('Gagal');
              kosong();                      
            }
        }
    })
}

</script>