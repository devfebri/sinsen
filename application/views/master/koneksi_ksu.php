<base href="<?php echo base_url(); ?>" />
<?php 
if(isset($_GET['id'])){ ?>
<body onload="kirim_data_ksu()">
<?php }else{ ?>
<body onload="auto()">
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
    <li class="">KSU</li>
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
          <a href="master/koneksi_ksu">
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
            <form class="form-horizontal" action="master/koneksi_ksu/save" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                                                                                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Koneksi KSU</label>
                  <div class="col-sm-4">
                    <input id="tgl" value="123" type="hidden">
                    <input type="text" readonly class="form-control" name="id_koneksi_ksu" placeholder="Koneksi KSU" id="id_koneksi_ksu">
                  </div>                                                   
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Tipe Kendaraan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required name="id_tipe_kendaraan" id="id_tipe_kendaraan">
                      <option value="">- choose -</option>
                      <?php                       
                      foreach($dt_tipe->result() as $val) {
                        echo "
                        <option value='$val->id_tipe_kendaraan'>$val->id_tipe_kendaraan - $val->tipe_ahm</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div> 
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-4">
                    <select class="form-control" name="active">                      
                      <option value="1">Aktif</option>
                      <option value="0">Non Aktif</option>
                    </select>
                  </div>                                   
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID KSU</label>
                  <div class="col-sm-3">
                    <select class="form-control select2" name="id_ksu" id="id_ksu">
                      <option value="">- choose -</option>
                      <?php                       
                      foreach($dt_ksu->result() as $val) {
                        echo "
                        <option value='$val->id_ksu'>$val->id_ksu | $val->ksu</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>              
                  <div class="col-sm-1">
                    <button type="button" onclick="simpan_ksu()" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Add</button>
                  </div> 
                </div> 

                <div id="tampil_ksu"></div>                     

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
      $row = $dt_koneksi_ksu->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/koneksi_ksu">
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
            <form class="form-horizontal" action="master/koneksi_ksu/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" id="id" value="<?php echo $row->id_koneksi_ksu ?>" />
              <div class="box-body">                                                                                                                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Koneksi KSU</label>
                  <div class="col-sm-4">
                    <input id="tgl" value="123" type="hidden">
                    <input type="text" value="<?php echo $row->id_koneksi_ksu ?>" readonly class="form-control" name="id_koneksi_ksu" placeholder="Koneksi KSU" id="id_koneksi_ksu">
                  </div>                                                   
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Tipe Kendaraan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="id_tipe_kendaraan" required name="id_tipe_kendaraan">
                      <option value="<?php echo $row->id_tipe_kendaraan ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_tipe_kendaraan","id_tipe_kendaraan",$row->id_tipe_kendaraan)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->tipe_ahm;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_tipe = $this->m_admin->kondisi("ms_tipe_kendaraan","id_tipe_kendaraan != '$row->id_tipe_kendaraan'");                                                
                      foreach($dt_tipe->result() as $val) {
                        echo "
                        <option value='$val->id_tipe_kendaraan'>$val->id_tipe_kendaraan - $val->tipe_ahm</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div> 
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-4">
                    <select class="form-control" name="active">                      
                    <?php 
                    if($row->active=='1'){
                    ?>                      
                      <option value="1">Aktif</option>
                      <option value="0">Non Aktif</option>                      
                    <?php }else{ ?>
                      <option value="0">Non Aktif</option>                      
                      <option value="1">Aktif</option>
                    <?php } ?>
                    </select>
                  </div>                                   
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID KSU</label>
                  <div class="col-sm-3">
                    <select class="form-control select2" name="id_ksu" id="id_ksu">
                      <option value="">- choose -</option>
                      <?php                       
                      foreach($dt_ksu->result() as $val) {
                        echo "
                        <option value='$val->id_ksu'>$val->id_ksu | $val->ksu</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>              
                  <div class="col-sm-1">
                    <button type="button" onclick="simpan_ksu()" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Add</button>
                  </div> 
                </div> 

                <div id="tampil_ksu"></div>                     

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
          <a href="master/koneksi_ksu/add">
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>              
              <th>Tipe Kendaraan</th>              
              <th>ID KSU</th>              
              <th width="5%">Active</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_koneksi_ksu->result() as $row) { 
            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";                  
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->id_tipe_kendaraan - $row->tipe_ahm</td>                            
              <td>";
              $rt = $this->db->query("SELECT * FROM ms_koneksi_ksu_detail WHERE id_koneksi_ksu = '$row->id_koneksi_ksu'");
              $jum = $rt->num_rows();
              $ndul = 1;
              foreach ($rt->result() as $isi) {
                $ss = $this->db->query("SELECT * FROM ms_ksu WHERE id_ksu = '$isi->id_ksu'");
                if($ss->num_rows()>0){
                  $io = $ss->row();
                  $ju = $io->ksu;                  
                }else{
                  $ju = "";
                }
                echo "$isi->id_ksu ($ju)";                
                $ndul++;              
                if($ndul <= $jum){
                  echo ", ";
                }
              }
              echo "
              </td>                            
              <td>$active</td>              
              <td>";
              ?>
                <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/koneksi_ksu/delete?id=<?php echo $row->id_koneksi_ksu ?>"><i class="fa fa-trash-o"></i></a>
                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/koneksi_ksu/edit?id=<?php echo $row->id_koneksi_ksu ?>'><i class='fa fa-edit'></i></a>
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

<script type="text/javascript">
function simpan(){      
  var id_ksu = $('#id_ksu').val();
  var id_tipe_kendaraan = $('#id_tipe_kendaraan').val();
  var active = $('#active').val();
  // alert(id_ksu);
  // alert(id_tipe_kendaraan);
  // alert(active);
  if (id_ksu=="" || id_tipe_kendaraan=="") {    
    alert("Isikan data dengan lengkap...!");
    return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('master/koneksi_ksu/save')?>",
          type:"POST",
          data:"id_ksu="+id_ksu+"&id_tipe_kendaraan="+id_tipe_kendaraan+"&active="+active,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                  //alert("Berhasil Simpan");
                  window.location.href = "master/koneksi_ksu";                      
              }else{
                  //alert('Gagal Simpan');                      
                  window.location.href = "master/koneksi_ksu";                                            
              }                
          }
      })    
  }
}
function update_kan(){      
  var id_ksu = $('#id_ksu').val();
  var id_tipe_kendaraan = $('#id_tipe_kendaraan').val();
  var active = $('#active').val();
  var id = $('#id').val();
  //alert(id_ksu);
  // alert(id_tipe_kendaraan);
  // alert(active);
  if (id_ksu=="" || id_tipe_kendaraan=="") {    
    alert("Isikan data dengan lengkap...!");
    return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('master/koneksi_ksu/update')?>",
          type:"POST",
          data:"id_ksu="+id_ksu+"&id_tipe_kendaraan="+id_tipe_kendaraan+"&active="+active+"&id="+id,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                  //alert("Berhasil Simpan");
                  window.location.href = "master/koneksi_ksu";                      
              }else{
                  //alert('Gagal Simpan');                      
                  window.location.href = "master/koneksi_ksu";                                            
              }                
          }
      })    
  }
}
function bulk_delete(){
  var list_id = [];
  $(".data-check:checked").each(function() {
    list_id.push(this.value);
  });
  if(list_id.length > 0){
    if(confirm('Are you sure delete this '+list_id.length+' data?'))
      {
        $.ajax({
          type: "POST",
          data: {id:list_id},
          url: "<?php echo site_url('master/koneksi_ksu/ajax_bulk_delete')?>",
          dataType: "JSON",
          success: function(data)
          {
            if(data.status){
              window.location.reload();
            }else{
              alert('Failed.');
            }                  
          },
          error: function (jqXHR, textStatus, errorThrown){
            alert('Error deleting data');
          }
        });
      }
    }else{
      alert('no data selected');
  }
}
function auto(){
  var ksu_js=document.getElementById("tgl").value; 
  $.ajax({
      url : "<?php echo site_url('master/koneksi_ksu/cari_id')?>",
      type:"POST",
      data:"ksu="+ksu_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_koneksi_ksu").val(data[0]);
        kirim_data_ksu();     
        //cek_jenis();                   
      }        
  })
}

function hide_ksu(){
    $("#tampil_ksu").hide();
}
function kirim_data_ksu(){    
  $("#tampil_ksu").show();
  var id_koneksi_ksu = document.getElementById("id_koneksi_ksu").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_koneksi_ksu="+id_koneksi_ksu;                           
     xhr.open("POST", "master/koneksi_ksu/t_ksu", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_ksu").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function simpan_ksu(){
    var id_koneksi_ksu   = document.getElementById("id_koneksi_ksu").value;   
    var id_ksu           = document.getElementById("id_ksu").value;   
    
    if (id_koneksi_ksu == "" || id_ksu == "") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
        $.ajax({
            url : "<?php echo site_url('master/koneksi_ksu/save_ksu')?>",
            type:"POST",
            data:"id_koneksi_ksu="+id_koneksi_ksu+"&id_ksu="+id_ksu,
            cache:false,
            success:function(msg){            
                data=msg.split("|");
                if(data[0]=="nihil"){
                    kirim_data_ksu();
                    kosong();                
                }else{
                    alert('ID KSU ini sudah ditambahkan');
                    kosong();                      
                }                
            }
        })    
    }
}
function kosong(args){
  $("#id_ksu").val("");  
}
function hapus_ksu(a,b){ 
    var id_koneksi_ksu_detail  = a;   
    var id_koneksi_ksu   = b;       
    $.ajax({
        url : "<?php echo site_url('master/koneksi_ksu/delete_ksu')?>",
        type:"POST",
        data:"id_koneksi_ksu_detail="+id_koneksi_ksu_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_ksu();
            }
        }
    })
}
</script>