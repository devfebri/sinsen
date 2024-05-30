<?php 
function bln(){
  $bulan=$bl=$month=date("m");
  switch($bulan)
  {
    case"1":$bulan="Januari"; break;
    case"2":$bulan="Februari"; break;
    case"3":$bulan="Maret"; break;
    case"4":$bulan="April"; break;
    case"5":$bulan="Mei"; break;
    case"6":$bulan="Juni"; break;
    case"7":$bulan="Juli"; break;
    case"8":$bulan="Agustus"; break;
    case"9":$bulan="September"; break;
    case"10":$bulan="Oktober"; break;
    case"11":$bulan="November"; break;
    case"12":$bulan="Desember"; break;
  }
  $bln = $bulan;
  return $bln;
}
?>
<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 25px;
  padding-left: 4px;
  padding-right: 4px;  
}
</style>
<base href="<?php echo base_url(); ?>" />
<?php 
if(isset($_GET['id'])){
?>
<body onload="kirim_data_pu()">
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
    <li class="">H1</li>
    <li class="">Penerimaan</li>
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
          <a href="h1/penerimaan_unit">
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
            <form class="form-horizontal" action="h1/penerimaan_unit/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Penerimaan Unit</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="id_penerimaan_unit" readonly placeholder="ID Penerimaan Unit" name="id_penerimaan_unit">
                    <input type="hidden" id="tgl" value="<?php echo date("d-M-y") ?>">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Antrian</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="no_antrian" readonly placeholder="No Antrian" name="no_antrian">                    
                  </div>
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="No Surat Jalan" name="no_surat_jalan">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="tanggal2" placeholder="Tgl Surat Jalan" name="tgl_surat_jalan">                                        
                  </div>
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Ekspedisi</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required name="ekspedisi" id="ekspedisi" onchange="take_eks()">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_vendor->result() as $val) {
                        echo "
                        <option value='$val->id_vendor'>$val->vendor_name</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Polisi Ekspedisi</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required id="no_polisi" name="no_polisi">
                      <option value="">- choose -</option>                      
                    </select>
                  </div>
                </div>                                            
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Supir</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required id="nama_driver" name="nama_driver">
                      <option value="">- choose -</option>                      
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telepon</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="No Telepon" name="no_telp">                                        
                  </div>
                </div>                                            
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required name="gudang">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_gudang->result() as $val) {
                        echo "
                        <option>$val->gudang</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly required class="form-control" value="<?php echo date("Y-m-d") ?>" id="tanggal" placeholder="Tanggal Penerimaan" name="tgl_penerimaan">                                        
                  </div>
                </div>

                <hr>                
                <div class="form-group">
                                                      
                  <span id="tampil_pu"></span>                                                                                  
                                    
                </div>                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="button" onClick="cancel_tr()" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
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
          <a href="h1/penerimaan_unit/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Generate New</button>
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
              <th>Kode Tipe Kendaraan</th>              
              <th>Tipe Kendaraan</th>              
              <th>Bulan</th>
              <th>Tahun</th>
              <th>Status</th>              
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_penerimaan_unit->result() as $row) {     
            $s = $this->db->query("SELECT * FROM ms_vendor WHERE id_vendor = '$row->ekspedisi'")->row();          
            echo "
            <tr>
              <td>$no</td>
              <td>$row->no_antrian</td>
              <td>$s->vendor_name</td>
              <td>$row->no_surat_jalan</td>
              <td>";
              $r = $this->db->query("SELECT * FROM tr_penerimaan_unit_detail WHERE id_penerimaan_unit = '$row->id_penerimaan_unit'");
              $rt = $r->row();
              foreach ($r->result() as $k) {
                echo "$k->no_shipping_list <br>";
              }
              echo "
              </td>
              <td>$row->no_polisi</td>              
              <td>
                <a href='h1/penerimaan_unit/scan?id=$row->id_penerimaan_unit'>
                  <button class='btn btn-flat btn-xs btn-success'><i class='fa fa-tags'></i> Scan/Entry No Mesin</button>
                </a>
                <a href='h1/penerimaan_unit/ksu?id=$row->id_penerimaan_unit'>
                  <button class='btn btn-flat btn-xs btn-danger'><i class='fa fa-suitcase'></i> Penerimaan KSU</button>              
                </a>                
              </td>
            </tr>
            ";
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

<div class="modal fade" id="Itemmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search Shipping List
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>No Shipping List</th>            
              <th>Jumlah Unit</th>            
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          foreach ($dt_item->result() as $ve2) {
            $r = $this->db->query("SELECT COUNT(no_rangka) AS jum FROM tr_shipping_list WHERE no_shipping_list = '$ve2->no_shipping_list'")->row();
            echo "
            <tr>
              <td>$no</td>
              <td>$ve2->no_shipping_list</td>
              <td>$r->jum</td>";
              ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="chooseitem('<?php echo $ve2->no_shipping_list; ?>','<?php echo $r->jum; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
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

<div class="modal fade" id="Scanmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search Item
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example3" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>No Mesin</th>            
              <th>No Rangka</th>            
              <th>Tipe</th>
              <th>Warna</th>
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1;       
          if(isset($_GET['id'])){
            $id_pu = $_GET['id'];
          }else{
            $id_pu = "";
          }   
          
          $dt_scan = $this->db->query("SELECT * FROM tr_penerimaan_unit_detail INNER JOIN tr_shipping_list ON 
                  tr_penerimaan_unit_detail.no_shipping_list = tr_shipping_list.no_shipping_list
                  WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id_pu' AND
                  tr_shipping_list.no_rangka NOT IN (SELECT no_rangka FROM tr_scan_barcode)");
          foreach ($dt_scan->result() as $ve2) {            
            echo "
            <tr>
              <td>$no</td>
              <td>$ve2->no_mesin</td>
              <td>$ve2->no_rangka</td>
              <td>$ve2->id_modell</td>
              <td>$ve2->id_warna</td>";
              ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="choose_rangka('<?php echo $ve2->no_mesin; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
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

<div class="modal fade" id="Scanmodal2">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search Item
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example5" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>No Mesin</th>            
              <th>No Rangka</th>            
              <th>Tipe</th>
              <th>Warna</th>
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1;       
          if(isset($_GET['id'])){
            $id_pu = $_GET['id'];
          }else{
            $id_pu = "";
          }   
          
          $dt_scan = $this->db->query("SELECT * FROM tr_penerimaan_unit_detail INNER JOIN tr_shipping_list ON 
                  tr_penerimaan_unit_detail.no_shipping_list = tr_shipping_list.no_shipping_list
                  WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id_pu' AND
                  tr_shipping_list.no_rangka NOT IN (SELECT no_rangka FROM tr_scan_barcode)");
          foreach ($dt_scan->result() as $ve2) {            
            echo "
            <tr>
              <td>$no</td>
              <td>$ve2->no_mesin</td>
              <td>$ve2->no_rangka</td>
              <td>$ve2->id_modell</td>
              <td>$ve2->id_warna</td>";
              ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="choose_rangka2('<?php echo $ve2->no_mesin; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
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



<script type="text/javascript">
function cek(){  
  auto();
  var id_penerimaan_unit_js = document.getElementById("id_penerimaan_unit").value; 
  $.ajax({
      url : "<?php echo site_url('h1/penerimaan_unit/cek_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_penerimaan_unit").val(data[0]);        
        $("#no_antrian").val(data[1]);       
        kirim_data_pu();
      }        
  })
}

function auto(){
  $("#nrfs_div").hide();
  $("#rfs_div").hide();

  var tgl_js=document.getElementById("tgl").value; 
  $.ajax({
      url : "<?php echo site_url('h1/penerimaan_unit/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        if(data[2] == 'nihil'){
          alert("Terdapat transaksi data sebelumnya yg belum selesai dg ID Penerimaan "+data[0]+". Hapus data sebelumnya dan mulai transaksi data baru?");
          hapus_auto(data[0]);
        }else{
          $("#id_penerimaan_unit").val(data[0]);        
          $("#no_antrian").val(data[1]);       
          kirim_data_pu();
        }
      }        
  })
}
function hapus_auto(a){
  var id_p = a;
  $.ajax({
      url : "<?php echo site_url('h1/penerimaan_unit/hapus_auto')?>",
      type:"POST",
      data:"id_p="+id_p,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        auto();
      }        
  })
}

function take_eks(){
  var ekspedisi = $("#ekspedisi").val();                       
  $.ajax({
      url: "<?php echo site_url('h1/penerimaan_unit/take_eks')?>",
      type:"POST",
      data:"ekspedisi="+ekspedisi,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");    
          take_driver();      
          $("#no_polisi").html(msg);                                                    
      } 
  })
}

function take_driver(){
  var ekspedisi = $("#ekspedisi").val();                       
  $.ajax({
      url: "<?php echo site_url('h1/penerimaan_unit/take_driver')?>",
      type:"POST",
      data:"ekspedisi="+ekspedisi,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");          
          $("#nama_driver").html(msg);                                                    
      } 
  })
}
function choose_rangka(no_mesin){
  document.getElementById("rfs_text").value = no_mesin;   
  simpan_rfs();
  $("#Scanmodal").modal("hide");
}
function choose_rangka2(no_mesin){
  document.getElementById("nrfs_text").value = no_mesin;   
  simpan_nrfs();
  $("#Scanmodal2").modal("hide");
}
function chooseitem(no_shipping_list,jum){
  document.getElementById("no_shipping_list").value = no_shipping_list; 
  document.getElementById("jumlah").value = jum; 
  cek_item();
  $("#Itemmodal").modal("hide");
}
function simpan_pu(){
  var id_penerimaan_unit  = document.getElementById("id_penerimaan_unit").value;  
  var no_shipping_list    = document.getElementById("no_shipping_list").value;     
  //alert(id_po);
  if (id_penerimaan_unit == "" || no_shipping_list == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h1/penerimaan_unit/save_pu')?>",
          type:"POST",
          data:"id_penerimaan_unit="+id_penerimaan_unit+"&no_shipping_list="+no_shipping_list,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="ok"){
                  kirim_data_pu();
                  kosong();                
              }else{
                  alert("Gagal Simpan, No Shipping List ini sudah dipilih");
                  kosong();                  
              }                
          }
      })    
  }
}
function kosong(args){
  $("#no_shipping_list").val("");
  $("#jumlah").val("");     
}
function hide_pu(){
    $("#tampil_pu").hide();
}
function kirim_data_pu(){    
  $("#tampil_pu").show();
  var id_penerimaan_unit = document.getElementById("id_penerimaan_unit").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_penerimaan_unit="+id_penerimaan_unit;                           
     xhr.open("POST", "h1/penerimaan_unit/t_pu", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_pu").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}

function hapus_pu(a){ 
    var id_penerimaan_unit_detail  = a;       
    $.ajax({
        url : "<?php echo site_url('h1/penerimaan_unit/delete_pu')?>",
        type:"POST",
        data:"id_penerimaan_unit_detail="+id_penerimaan_unit_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_pu();
            }
        }
    })
}

function hapus_scan(a,b){ 
    var id_scan_barcode  = a;       
    var jenis  = b;       
    $.ajax({
        url : "<?php echo site_url('h1/penerimaan_unit/delete_scan')?>",
        type:"POST",
        data:"id_scan_barcode="+id_scan_barcode+"&jenis="+jenis,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              if(jenis == 'RFS'){
                kirim_data_rfs();
              }else if(jenis == 'NRFS'){
                kirim_data_nrfs();                
              }
            }
        }
    })
}

function rfs_click(){
  $("#nrfs_div").hide();
  $("#rfs_div").show();
  $("#rfs_text").focus();
  kirim_data_rfs();  
}
function nrfs_click(){
  $("#rfs_div").hide();
  $("#nrfs_div").show();
  $("#nrfs_text").focus();
  kirim_data_nrfs();
}
function kosong_rfs(args){
  $("#rfs_text").val("");
  $("#nrfs_text").val("");
}
function kirim_data_rfs(){    
  $("#tampil_data").show();
  var id_pu = document.getElementById("id_pu").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_pu="+id_pu;                           
     xhr.open("POST", "h1/penerimaan_unit/t_rfs", true); 
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
function kirim_data_nrfs(){    
  $("#tampil_data").show();
  var id_pu = document.getElementById("id_pu").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_pu="+id_pu;                           
     xhr.open("POST", "h1/penerimaan_unit/t_nrfs", true); 
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
function simpan_rfs(){
  var id_pu       = document.getElementById("id_pu").value;  
  var rfs_text    = document.getElementById("rfs_text").value;     
  //alert(id_po);
  if (id_pu == "" || rfs_text == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h1/penerimaan_unit/save_rfs')?>",
          type:"POST",
          data:"rfs_text="+rfs_text+"&id_pu="+id_pu,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="ok"){
                  kirim_data_rfs();
                  kosong_rfs();                
              }else if(data[0]=="no"){
                  alert("Gagal Simpan, No Mesin ini sudah di-scan sebelumnya");
                  kosong_rfs();                  
              }else{
                  alert("Gagal Simpan, No Mesin ini tidak terdaftar di No Shipping List");
                  kosong_rfs();                  
              }                
          }
      })    
  }
}
function simpan_nrfs(){
  var id_pu        = document.getElementById("id_pu").value;  
  var nrfs_text    = document.getElementById("nrfs_text").value;     
  //alert(id_po);
  if (id_pu == "" || nrfs_text == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h1/penerimaan_unit/save_nrfs')?>",
          type:"POST",
          data:"nrfs_text="+nrfs_text+"&id_pu="+id_pu,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="ok"){
                  kirim_data_nrfs();
                  kosong_rfs();                
              }else if(data[0]=="no"){
                  alert("Gagal Simpan, No Mesin ini sudah di-scan sebelumnya");
                  kosong_rfs();                  
              }else{
                  alert("Gagal Simpan, No Mesin ini tidak terdaftar di No Shipping List");
                  kosong_rfs();                  
              }                
          }
      })    
  }
}
</script>
<script type="text/javascript">
var rfs_text = document.getElementById("rfs_text");
rfs_text.addEventListener("keydown", function (e) {
    if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
        simpan_rfs();
    }
});
var nrfs_text = document.getElementById("nrfs_text");
nrfs_text.addEventListener("keydown", function (e) {
    if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
        simpan_nrfs();
    }
});
</script>