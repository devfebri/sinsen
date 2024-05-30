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
          <a href="master/target_salesman">
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
            <form method="POST" role="form" enctype="multipart/form-data" action="master/target_salesman/save" class="form-horizontal form-groups-bordered">
              <div class="box-body">         
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">NIK Salesman *</label>            
                  <div class="col-sm-4">
                    <select name="id_karyawan_dealer" required onchange="take_karyawan()" class="form-control select2" id="id_karyawan_dealer">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_karyawan->result() as $isi) {
                        echo "<option value='$isi->id_karyawan_dealer'>$isi->id_karyawan_dealer</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Nama Salesman</label>            
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Nama Salesman" name="nama" readonly id="nama_lengkap">
                  </div>
                 </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Periode Awal</label>           
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Periode Awal" autocomplete="off" name="periode_awal" id="tanggal1">                    
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Periode Akhir</label>           
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Periode Akhir" autocomplete="off" name="periode_akhir" id="tanggal2">                    
                  </div>
                </div>
                <span id="detail_target"></span>
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
    }elseif($set=="detail"){
      $row = $dt_target_salesman->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/target_salesman">
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
            <form method="POST" role="form" enctype="multipart/form-data" action="master/target_salesman/save" class="form-horizontal form-groups-bordered">
              <div class="box-body">         
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">NIK Salesman *</label>            
                  <div class="col-sm-4">
                    <input value="<?php echo $row->id_karyawan_dealer ?>" type="text" class="form-control" placeholder="NIK Salesman" name="id_karyawan_dealer" readonly id="id_karyawan_dealer">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Nama Salesman</label>            
                  <div class="col-sm-10">
                    <input value="<?php echo $row->nama_lengkap ?>" type="text" class="form-control" placeholder="Nama Salesman" name="nama" readonly id="nama_lengkap">
                  </div>
                 </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Periode Awal</label>           
                  <div class="col-sm-4">
                    <input value="<?php echo $row->periode_awal ?>" type="text" class="form-control" placeholder="Periode Awal" autocomplete="off" name="periode_awal" id="tanggal1">                    
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Periode Akhir</label>           
                  <div class="col-sm-4">
                    <input value="<?php echo $row->periode_akhir ?>" type="text" class="form-control" placeholder="Periode Akhir" autocomplete="off" name="periode_akhir" id="tanggal2">                    
                  </div>
                </div>
                <button type="button" class="btn btn-block btn-primary btn-flat">Detail Target Salesman</button>
                <table id="myTable" class="table myTable1 order-list" border="0">
                  <thead>   
                    <tr>
                      <th width="15%">Customer</th>
                      <th width="20%">Nama Customer</th>
                      <th width="10%">Target Part (Rp)</th>
                      <th width="10%">Target Oli GMO (Rp)</th>
                      <th width="10%">Target Aksesoris (Rp)</th>      
                    </tr>
                  </thead>  
                  <tbody>
                  <?php 
                  $sql = $this->db->query("SELECT * FROM ms_target_salesman_detail LEFT JOIN ms_toko ON ms_target_salesman_detail.id_toko = ms_toko.id_toko
                    WHERE ms_target_salesman_detail.id_target_salesman = '$row->id_target_salesman'");
                  foreach ($sql->result() as $isi) {
                    echo "
                    <tr>
                      <td>$isi->id_toko</td>
                      <td>$isi->nama_toko</td>
                      <td>$isi->target_part</td>
                      <td>$isi->target_oli</td>
                      <td>$isi->target_acc</td>
                    </tr>
                    ";
                  }
                  ?>
                  </tbody>
                </table>
              </div>                           
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="edit"){
      $row = $dt_target_salesman->row();
    ?>    
    <body onload="take_gudang()">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/target_salesman">
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
             <form method="POST" role="form" enctype="multipart/form-data" action="master/target_salesman/update" class="form-horizontal form-groups-bordered">
              <div class="box-body">         
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">NIK Salesman *</label>            
                  <div class="col-sm-4">
                    <input type="hidden" name="id_target_salesman" value="<?php echo $row->id_target_salesman ?>">
                    <input value="<?php echo $row->id_karyawan_dealer ?>" type="text" class="form-control" placeholder="NIK Salesman" name="id_karyawan_dealer" readonly id="id_karyawan_dealer">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Nama Salesman</label>            
                  <div class="col-sm-10">
                    <input value="<?php echo $row->nama_lengkap ?>" type="text" class="form-control" placeholder="Nama Salesman" name="nama" readonly id="nama_lengkap">
                  </div>
                 </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Periode Awal</label>           
                  <div class="col-sm-4">
                    <input  value="<?php echo $row->periode_awal ?>" type="text" class="form-control" placeholder="Periode Awal" autocomplete="off" name="periode_awal" id="tanggal1">                    
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Periode Akhir</label>           
                  <div class="col-sm-4">
                    <input  value="<?php echo $row->periode_akhir ?>" type="text" class="form-control" placeholder="Periode Akhir" autocomplete="off" name="periode_akhir" id="tanggal2">                    
                  </div>
                </div>
                <button type="button" class="btn btn-block btn-primary btn-flat">Detail Target Salesman</button>
                <table id="myTable" class="table myTable1 order-list" border="0">
                  <thead>   
                    <tr>
                      <th width="15%">Customer</th>
                      <th width="20%">Nama Customer</th>
                      <th width="10%">Target Part (Rp)</th>
                      <th width="10%">Target Oli GMO (Rp)</th>
                      <th width="10%">Target Aksesoris (Rp)</th>      
                      <th width="5%">Aksi</th>      
                    </tr>
                  </thead>  
                  <tbody>
                  <?php 
                  $sql = $this->db->query("SELECT * FROM ms_target_salesman_detail LEFT JOIN ms_toko ON ms_target_salesman_detail.id_toko = ms_toko.id_toko
                    WHERE ms_target_salesman_detail.id_target_salesman = '$row->id_target_salesman'");
                  foreach ($sql->result() as $isi) {
                    echo "
                    <tr>
                      <td>$isi->id_toko</td>
                      <td>$isi->nama_toko</td>
                      <td>$isi->target_part</td>
                      <td>$isi->target_oli</td>
                      <td>$isi->target_acc</td>
                      <td>"; ?>
                        <button onclick="hapus_detail(<?php echo $isi->id_target_salesman_detail ?>)" type="button" class="btn btn-danger btn-xs btn-flat" title="Delete"><i class="fa fa-trash"></i></button>
                        <!-- <button type='button' class="btn btn-primary btn-xs btn-flat" title="Edit"><i class="fa fa-edit"></i></button> -->
                      </td>             
                    </tr>
                    <?php 
                  }
                  ?>
                  </tbody>
                </table>
              </div>     
              <div class="box-footer">
                  <div class="col-sm-2">
                  </div>
                  <div class="col-sm-10">
                    <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update</button>
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
          <a href="master/target_salesman/add">
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
              <th>NIK Salesman</th>
              <th>Nama Salesman</th>             
              <th>Periode Awal</th>              
              <th>Periode Akhir</th>    
              <th>Aksi</th>                        
            </tr>
          </thead>
          <tbody>
            <?php 
            $no=1;
            foreach ($dt_target_salesman->result() as $val) {                
              echo"
              <tr>
                <td>$no</td>
                <td>$val->id_karyawan_dealer</td>
                <td>$val->nama_lengkap</td>
                <td>$val->periode_awal</td>                
                <td>$val->periode_akhir</td>
                <td>"; ?>
                  <a href="master/target_salesman/detail?id=<?php echo $val->id_target_salesman ?>"><button type="button" class="btn btn-warning btn-sm btn-flat" title="View"><i class="fa fa-eye"></i></button></a>
                  <a href="master/target_salesman/edit?id=<?php echo $val->id_target_salesman ?>"><button type='button' class="btn btn-primary btn-sm btn-flat" title="Edit"><i class="fa fa-edit"></i></button></a>                  
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
<div class="modal fade modal_edit" id="Modal_edit">
  <div class="modal-dialog" style="width: 90%;">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title">Edit Diskon Oli Reguler</h4>
      </div>
      <form action="master/target_salesman/update_detail" method="post">
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
function take_karyawan(){
  var id_karyawan_dealer = $("#id_karyawan_dealer").val();                       
  $.ajax({
      url: "<?php echo site_url('master/target_salesman/take_karyawan')?>",
      type:"POST",
      data:"id_karyawan_dealer="+id_karyawan_dealer,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");                    
          $("#id_karyawan_dealer").val(data[0]);                                                    
          $("#nama_lengkap").val(data[1]);                                                              
      } 
  })
}
function take_toko(){
  var id_toko = $("#id_toko").val();                       
  $.ajax({
      url: "<?php echo site_url('master/target_salesman/take_toko')?>",
      type:"POST",
      data:"id_toko="+id_toko,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");                    
          $("#id_toko").val(data[0]);                                                    
          $("#nama_toko").val(data[1]);                                                              
      } 
  })
}
function kirim_data(){    
  $("#detail_target").show();
  var id = 1;
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id="+id;                           
     xhr.open("POST", "master/target_salesman/t_detail", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("detail_target").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function addDetail(){
    var id_toko   = document.getElementById("id_toko").value;   
    var nama_toko   = document.getElementById("nama_toko").value;   
    var target_part   = document.getElementById("target_part").value;   
    var target_oli   = document.getElementById("target_oli").value;   
    var target_aksesoris   = document.getElementById("target_aksesoris").value;   
    
    if (id_toko == "") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
        $.ajax({
            url : "<?php echo site_url('master/target_salesman/save_detail')?>",
            type:"POST",
            data:"id_toko="+id_toko+"&nama_toko="+nama_toko+"&target_part="+target_part+"&target_oli="+target_oli+"&target_aksesoris="+target_aksesoris,
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
function kosong(args){
  $("#id_toko").val("");  
  $("#nama_toko").val("");  
  $("#target_part").val("");  
  $("#target_aksesoris").val("");  
  $("#target_oli").val("");    
}
function delDetail(a){ 
    var id  = a;       
    $.ajax({
        url : "<?php echo site_url('master/target_salesman/delete_detail')?>",
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
function hapus_detail(a){ 
    var id  = a;       
    $.ajax({
        url : "<?php echo site_url('master/target_salesman/hapus_detail')?>",
        type:"POST",
        data:"id="+id,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]!="failed"){
              Location.reload();
            }else{
              alert('Gagal');
              
            }
        }
    })
}

</script>