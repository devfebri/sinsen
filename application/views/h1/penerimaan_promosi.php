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
<base href="<?php echo base_url(); ?>" />
<?php 
if(isset($_GET['id'])){
?>
<body onload="kirim_data()">
<?php
}else{
?>
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
    <li class="">H1</li>
    <li class=""><?php echo $isi ?></li>
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
          <a href="h1/penerimaan_promosi">
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
            <form class="form-horizontal" action="h1/penerimaan_promosi/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">                       
                <div class="form-group">
                  <input type="hidden" id="mode" value="new">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Penerimaan Promosi</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly id="no_penerimaan_promosi" placeholder="No Penerimaan Promosi" name="no_penerimaan_promosi">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Vendor</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_vendor">
                      <option value="">- choose -</option>
                      <?php 
                      $vendor = $this->m_admin->getSortCond("ms_vendor","vendor_name","ASC");
                      foreach ($vendor->result() as $isi) {
                        echo "<option value='$isi->id_vendor'>$isi->vendor_name</option>";
                      }
                      ?>
                    </select>
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No Surat Jalan" name="no_surat_jalan">
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal SJ Vendor</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Tanggal Sj Vendor" id="tanggal" name="tgl_sj">
                  </div>                  
                </div>                                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Tanggal Penerimaan" id="tanggal2" name="tgl_penerimaan">
                  </div>                                    
                </div>                    

                <button class="btn btn-info btn-flat btn-sm btn-block" disabled>Detail Item</button>
                <span id="tampil_data"></span>                                                     
                
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
    }elseif($set=="edit"){
      $row = $dt_promosi->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/penerimaan_promosi">
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
            <form class="form-horizontal" action="h1/penerimaan_promosi/update" method="post" enctype="multipart/form-data">              
              <div class="box-body">                       
                <div class="form-group">
                  <input type="hidden" id="mode" value="edit">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Penerimaan Promosi</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly value="<?php echo $row->no_penerimaan_promosi ?>" id="no_penerimaan_promosi" placeholder="No Penerimaan Promosi" name="no_penerimaan_promosi">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Vendor</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_vendor">
                      <option value="<?php echo $row->id_vendor ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_vendor","id_vendor",$row->id_vendor)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->vendor_name";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $vendor = $this->m_admin->kondisiCond("ms_vendor","id_vendor != '$row->id_vendor'");                                                
                      foreach ($vendor->result() as $isi) {
                        echo "<option value='$isi->id_vendor'>$isi->vendor_name</option>";
                      }
                      ?>
                    </select>
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->no_surat_jalan ?>" placeholder="No Surat Jalan" name="no_surat_jalan">
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal SJ Vendor</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->tgl_sj ?>" placeholder="Tanggal Sj Vendor" id="tanggal" name="tgl_sj">
                  </div>                  
                </div>                                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->tgl_penerimaan ?>" placeholder="Tanggal Penerimaan" id="tanggal2" name="tgl_penerimaan">
                  </div>                                    
                </div>                    

                <button class="btn btn-info btn-flat btn-sm btn-block" disabled>Detail Item</button>
                <span id="tampil_data"></span>                                                     
                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
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
      $row = $dt_promosi->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/penerimaan_promosi">
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
            <form class="form-horizontal" action="h1/penerimaan_promosi/update" method="post" enctype="multipart/form-data">              
              <div class="box-body">                       
                <div class="form-group">
                  <input type="hidden" id="mode" value="detail">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Penerimaan Promosi</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly value="<?php echo $row->no_penerimaan_promosi ?>" id="no_penerimaan_promosi" placeholder="No Penerimaan Promosi" name="no_penerimaan_promosi">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Vendor</label>
                  <div class="col-sm-4">
                    <select readonly class="form-control select2" name="id_vendor">
                      <option value="<?php echo $row->id_vendor ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_vendor","id_vendor",$row->id_vendor)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->vendor_name";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $vendor = $this->m_admin->kondisiCond("ms_vendor","id_vendor != '$row->id_vendor'");                                                
                      foreach ($vendor->result() as $isi) {
                        echo "<option value='$isi->id_vendor'>$isi->vendor_name</option>";
                      }
                      ?>
                    </select>
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $row->no_surat_jalan ?>" placeholder="No Surat Jalan" name="no_surat_jalan">
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal SJ Vendor</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $row->tgl_sj ?>" placeholder="Tanggal Sj Vendor" id="tanggal" name="tgl_sj">
                  </div>                  
                </div>                                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $row->tgl_penerimaan ?>" placeholder="Tanggal Penerimaan" id="tanggal2" name="tgl_penerimaan">
                  </div>                                    
                </div>                    

                <button class="btn btn-info btn-flat btn-sm btn-block" disabled>Detail Item</button>
                <span id="tampil_data"></span>                                                     
                
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
          <a href="h1/penerimaan_promosi/add">            
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
              <th>No Penerimaan</th>             
              <th>Tgl Penerimaan</th>               
              <th>No SJ</th>              
              <th>Tanggal SJ</th>                            
              <th>Vendor</th>                            
              <th width="5%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_promosi->result() as $row) {                                         
            $edit = $this->m_admin->set_tombol($id_menu,$group,'edit');            
          echo "          
            <tr>
              <td>1</td>                           
              <td>
                <a href='h1/penerimaan_promosi/view?id=$row->no_penerimaan_promosi'>
                  $row->no_penerimaan_promosi
                </a>
              </td>                            
              <td>$row->tgl_penerimaan</td>              
              <td>$row->no_surat_jalan</td>
              <td>$row->tgl_sj</td>
              <td>$row->vendor_name</td>
              <td>
                <a $edit href='h1/penerimaan_promosi/edit?id=$row->no_penerimaan_promosi' type='button' class='btn btn-flat btn-success btn-xs'><i class='fa fa-edit'></i> Edit</a>                
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
      url : "<?php echo site_url('h1/penerimaan_promosi/cari_id')?>",
      type:"POST",
      data:"id="+id,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#no_penerimaan_promosi").val(data[0]);        
        kirim_data();        
      }        
  })
}
function kirim_data(){    
  $("#tampil_data").show();
  var no_penerimaan_promosi = document.getElementById("no_penerimaan_promosi").value;   
  var mode = document.getElementById("mode").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "no_penerimaan_promosi="+no_penerimaan_promosi+"&mode="+mode;                           
     xhr.open("POST", "h1/penerimaan_promosi/t_data", true); 
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
function simpan_data(){  
  var no_penerimaan_promosi = document.getElementById("no_penerimaan_promosi").value;   
  var id_item_promosi       = document.getElementById("id_item_promosi").value;   
  var id_kategori_item      = document.getElementById("id_kategori_item").value;   
  var qty_terima            = document.getElementById("qty_terima").value;     
  //alert(id_po);
  if (no_penerimaan_promosi == "" || id_kategori_item == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h1/penerimaan_promosi/save_data')?>",
          type:"POST",
          data:"no_penerimaan_promosi="+no_penerimaan_promosi+"&id_item_promosi="+id_item_promosi+"&id_kategori_item="+id_kategori_item+"&qty_terima="+qty_terima,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                kirim_data();
                kosong();                
              }else{
                alert(data[0]);
                kosong();                      
              }                
          }
      })    
  }
}
function hapus_data(a,b){ 
    var no_penerimaan_promosi   = a;   
    var id_penerimaan_promosi_detail  = b;       
    $.ajax({
        url : "<?php echo site_url('h1/penerimaan_promosi/delete_data')?>",
        type:"POST",
        data:"id_penerimaan_promosi_detail="+id_penerimaan_promosi_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data();
            }
        }
    })
}
function kosong(args){
  $("#id_kategori_item").val("");
  $("#id_item_promosi").val("");   
  $("#qty_terima").val("");     
}
function cek_kategori(){
  var id_item_promosi = $("#id_item_promosi").val();
  $.ajax({
      url : "<?php echo site_url('h1/penerimaan_promosi/cek_kategori')?>",
      type:"POST",
      data:"id_item_promosi="+id_item_promosi,
      cache:false,
      success:function(msg){            
        data=msg.split("|");          
        $("#id_kategori_item").val(data[0])          
      }
  })
}
</script>