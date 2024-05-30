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
<body onload="auto()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Bussiness Control</li>
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
          <a href="h1/promosi">
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
            <form class="form-horizontal" action="h1/promosi/save" method="post" enctype="multipart/form-data">
              <button type="reset" class="btn btn-primary btn-flat btn-block" disabled>Promosi</button>                                  
              <div class="box-body">       
                <div class="form-group">                  
                  <input type="hidden" name="id_promosi" id="id_promosi">
                  <label for="inputEmail3" class="col-sm-2 control-label">Program Promosi</label>
                  <div class="col-sm-4">
                    <select class="form-control select2"  name="id_program_promosi">
                      <option value="">- choose -</option>
                      <?php 
                      $promosi = $this->m_admin->getSortCond("ms_program_promosi","program_promosi","ASC");
                      foreach ($promosi->result() as $isi) {
                        echo "<option value='$isi->id_program_promosi'>$isi->program_promosi</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Promosi</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_jenis_promosi">
                      <option value="">- choose -</option>
                      <?php 
                      $jenis = $this->m_admin->getSortCond("ms_jenis_promosi","jenis_promosi","ASC");
                      foreach ($jenis->result() as $isi) {
                        echo "<option value='$isi->id_jenis_promosi'>$isi->jenis_promosi</option>";
                      }
                      ?>
                    </select>
                  </div>                  
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Reg</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_reg" placeholder="No Reg" class="form-control">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tema</label>
                  <div class="col-sm-4">
                    <input type="text" name="tema" placeholder="Tema" class="form-control">
                  </div>
                </div>                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Reg</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_reg" id="tanggal" placeholder="Tanggal Reg" class="form-control">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Mulai</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_mulai" id="tanggal2" placeholder="Tanggal Mulai" class="form-control">
                  </div>
                </div>
                <div class="form-group">                  
                  <div class="col-sm-6"></div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Selesai</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_selesai" id="tanggal3" placeholder="Tanggal Selesai" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Sumber Dana</label>
                  <div class="col-sm-4">
                    <table border="0" width="100%">                      
                      <tr>
                        <td colspan="2">
                          <div class="checkbox">
                            <input type="checkbox" name="ahm"> AHM
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                          <div class="checkbox">
                            <input type="checkbox" name="main_dealer"> Main Dealer
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td valign="top">
                          <div class="checkbox">
                            <input type="checkbox" name="dealer"> Dealer >>
                          </div>
                        </td>
                        <td>
                          <div class="checkbox">
                            <input type="text" class="form-control" name="presentase_d" placeholder="Presentase">
                          </div>
                          <div class="checkbox">
                            <input type="text" class="form-control" name="rupiah_d" placeholder="Rupiah">
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td valign="top">
                          <div class="checkbox">
                            <input type="checkbox" name=""> Other >>
                          </div>
                        </td>
                        <td>
                          <div class="checkbox">
                            <input type="text" class="form-control" name="presentase_o" placeholder="Presentase">
                          </div>
                          <div class="checkbox">
                            <input type="text" class="form-control" name="rupiah_o" placeholder="Rupiah">
                          </div>
                        </td>
                      </tr>

                    </table>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Media/Lokasi</label>
                  <div class="col-sm-4">
                    <input type="text" name="lokasi" placeholder="Media/Lokasi" class="form-control">
                  </div>
                </div>                                

                <button type="reset" class="btn btn-danger btn-flat btn-block" disabled>Dealer</button>                                             
                <br>
                
                <div class="form-group">                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer yg Ikut</label>
                  <div class="col-sm-2">
                    <select class="form-control" id="dealer_ikut" name="dealer_ikut" onchange="cek_dealer()">
                      <option value="">- choose -</option>
                      <option value="Tidak Ikut">Tidak Ikut</option>
                      <option value="All Dealer">All Dealer</option>
                      <option value="Per Dealer">Per Dealer</option>
                    </select>  
                  </div>
                  <div class="col-sm-2">
                    <button class="btn btn-warning btn-flat btn-sm" type="button" onclick="reset_dealer()">Reset</button>
                  </div>                      
                  <div class="col-sm-6">
                    <span id="tampil_data_dealer"></span>                                
                  </div>
                </div>                                    
                

                

                <button type="reset" class="btn btn-success btn-flat btn-block" disabled>Target Penjualan</button>                                             
                <br>
                <div class="form-group">                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Motor</label>
                  <div class="col-sm-2">
                    <select class="form-control" id="tipe_ikut" name="tipe_ikut" onchange="cek_tipe()">
                      <option value="">- choose -</option>
                      <option value="Tidak Ikut">Tidak Ikut</option>
                      <option value="All Tipe">All Tipe</option>
                      <option value="Per Tipe">Per Tipe</option>
                    </select>  
                  </div>
                  <div class="col-sm-2">
                    <button class="btn btn-warning btn-flat btn-sm" type="button" onclick="reset_tipe()">Reset</button>
                  </div>                      
                  <div class="col-sm-6">
                    <span id="tampil_data_tipe"></span>                                
                  </div>
                </div>                                    

                <button type="reset" class="btn btn-warning btn-flat btn-block" disabled>Leasing Pendukung</button>                                             
                <br>

                <div class="form-group">                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Leasing</label>
                  <div class="col-sm-2">
                    <select class="form-control" id="leasing_ikut" name="leasing_ikut" onchange="cek_leasing()">
                      <option value="">- choose -</option>
                      <option value="Tidak Ikut">Tidak Ikut</option>
                      <option value="All Leasing">All Leasing</option>
                      <option value="Per Leasing">Per Leasing</option>
                    </select>  
                  </div>
                  <div class="col-sm-2">
                    <button class="btn btn-warning btn-flat btn-sm" type="button" onclick="reset_leasing()">Reset</button>
                  </div>                      
                  <div class="col-sm-6">
                    <span id="tampil_data_leasing"></span>                                
                  </div>
                </div>
                

                <button type="reset" class="btn btn-deafult btn-flat btn-block" disabled>Rincian Biaya</button>                                             
                <br>

                <span id="tampil_data_biaya"></span>  

                <br>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Harga</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" name="total_harga" id="total_harga" placeholder="Total Harga">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" name="no_po" id="no_po" placeholder="No PO">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Cara Pembayaran & Claim Biaya</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="id_cara_bayar">
                      <option value="">- choose -</option>
                      <option>Dibayarkan setelah event dan pertanggungjawaban diterima</option>
                      <option>Lainnya</option>
                    </select>
                  </div>                                                      
                </div>

                <br>

                <button type="reset" class="btn btn-primary btn-flat btn-block" disabled>Kolom Pembuatan Proposal</button>                                             
                <br>
                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Judul</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" name="judul" placeholder="Judul">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Latar Belakang</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" name="latar_belakang" placeholder="Latar Belakang">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Proposal</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="jenis_proposal">                      
                      <option>Internal</option>
                      <option>Eksternal</option>
                    </select>
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Isi</label>
                  <div class="col-sm-4">                    
                    <textarea class="form-control" name="isi"></textarea>
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Approval Sampai ke Top Management</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="approval">                      
                      <option>Ya</option>
                      <option>Tidak</option>
                    </select>
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Penutup</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" name="penutup" placeholder="Penutup">
                  </div>                  
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
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/promosi/add">            
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
              <th>Program Promosi</th>             
              <th>No Reg</th> 
              <th>Tgl Reg</th>
              <th>Jenis</th>
              <th>Tema</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_pl->result() as $row) {     
            $delete = $this->m_admin->set_tombol($id_menu,$group,'delete');

          echo "          
            <tr>
              <td>$no</td>                           
              <td>$row->program_promosi</td>
              <td>$row->no_reg</td>
              <td>$row->tgl_reg</td>
              <td>$row->jenis_promosi</td>
              <td>$row->tema</td>
              <td>
                <a href='h1/promosi/delete?id=$row->id_promosi' $delete type='button' class='btn btn-flat btn-danger btn-xs'><i class='fa fa-trash-o'></i> del</a>
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
function cek_dealer(){
  var dealer_ikut = document.getElementById("dealer_ikut").value;   
  if(dealer_ikut == 'Per Dealer'){
    kirim_data_dealer();
    document.getElementById("dealer_ikut").disabled = true;  
  }
}
function reset_dealer(){  
  var id_promosi = $("#id_promosi").val();
  $.ajax({
      url : "<?php echo site_url('h1/promosi/reset_dealer')?>",
      type:"POST",
      data:"id_promosi="+id_promosi,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");        
        document.getElementById("dealer_ikut").disabled = false;
        $("#dealer_ikut").val("- choose -");
        $("#tampil_data_dealer").hide();
      }        
  })
}
function cek_tipe(){
  var tipe_ikut = document.getElementById("tipe_ikut").value;   
  if(tipe_ikut == 'Per Tipe'){
    kirim_data_tipe();
    document.getElementById("tipe_ikut").disabled = true;  
  }
}
function reset_tipe(){  
  var id_promosi = $("#id_promosi").val();
  $.ajax({
      url : "<?php echo site_url('h1/promosi/reset_tipe')?>",
      type:"POST",
      data:"id_promosi="+id_promosi,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");        
        document.getElementById("tipe_ikut").disabled = false;
        $("#tipe_ikut").val("- choose -");
        $("#tampil_data_tipe").hide();
      }        
  })
}
function cek_leasing(){
  var leasing_ikut = document.getElementById("leasing_ikut").value;   
  if(leasing_ikut == 'Per Leasing'){
    kirim_data_leasing();
    document.getElementById("leasing_ikut").disabled = true;  
  }
}
function reset_leasing(){  
  var id_promosi = $("#id_promosi").val();
  $.ajax({
      url : "<?php echo site_url('h1/promosi/reset_leasing')?>",
      type:"POST",
      data:"id_promosi="+id_promosi,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");        
        document.getElementById("leasing_ikut").disabled = false;
        $("#leasing_ikut").val("- choose -");
        $("#tampil_data_leasing").hide();
      }        
  })
}
function auto(){
  var id = 1;
  $.ajax({
      url : "<?php echo site_url('h1/promosi/cari_id')?>",
      type:"POST",
      data:"id="+id,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_promosi").val(data[0]);        
        kirim_data_biaya();        
      }        
  })
}
function ambil_harga(){
  var id_item_promosi = $("#id_item_promosi").val();
  $.ajax({
      url : "<?php echo site_url('h1/promosi/cari_harga')?>",
      type:"POST",
      data:"id_item_promosi="+id_item_promosi,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#harga").val(data[0]);                
      }        
  })
}
function hitung(){
  var id_promosi = $("#id_promosi").val();
  $.ajax({
      url : "<?php echo site_url('h1/promosi/cari_total')?>",
      type:"POST",
      data:"id_promosi="+id_promosi,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#total_harga").val(data[0]);                
      }        
  })
}
function kirim_data_biaya(){    
  $("#tampil_data_biaya").show();
  var id_promosi = document.getElementById("id_promosi").value;   
  var mode = "input";
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_promosi="+id_promosi+"&mode="+mode;                           
     xhr.open("POST", "h1/promosi/t_biaya", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_data_biaya").innerHTML = xhr.responseText;
                hitung();
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function kirim_data_dealer(){    
  $("#tampil_data_dealer").show();
  var id_promosi = document.getElementById("id_promosi").value;   
  var mode = "input";
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_promosi="+id_promosi+"&mode="+mode;                           
     xhr.open("POST", "h1/promosi/t_dealer", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_data_dealer").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function kirim_data_tipe(){    
  $("#tampil_data_tipe").show();
  var id_promosi = document.getElementById("id_promosi").value;   
  var mode = "input";
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_promosi="+id_promosi+"&mode="+mode;                           
     xhr.open("POST", "h1/promosi/t_tipe", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_data_tipe").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function kirim_data_leasing(){    
  $("#tampil_data_leasing").show();
  var id_promosi = document.getElementById("id_promosi").value;   
  var mode = "input";
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_promosi="+id_promosi+"&mode="+mode;                           
     xhr.open("POST", "h1/promosi/t_leasing", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_data_leasing").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function simpan_biaya(){  
  var id_promosi      = document.getElementById("id_promosi").value;   
  var id_vendor       = document.getElementById("id_vendor").value;   
  var id_item_promosi = document.getElementById("id_item_promosi").value;   
  var ppn             = document.getElementById("ppn").value;     
  var qty             = document.getElementById("qty").value;     
  var keterangan      = document.getElementById("keterangan").value;     
  //alert(id_po);
  if (id_vendor == "" || id_item_promosi == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h1/promosi/save_biaya')?>",
          type:"POST",
          data:"id_promosi="+id_promosi+"&id_item_promosi="+id_item_promosi+"&id_vendor="+id_vendor+"&qty="+qty+"&ppn="+ppn+"&keterangan="+keterangan,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                kirim_data_biaya();
                kosong();                
              }else{
                alert(data[0]);
                kosong();                            
              }                
          }
      })    
  }
}
function hapus_biaya(a,b){ 
    var id_promosi_biaya   = a;   
    var id_promosi  = b;       
    $.ajax({
        url : "<?php echo site_url('h1/promosi/delete_biaya')?>",
        type:"POST",
        data:"id_promosi_biaya="+id_promosi_biaya,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_biaya();              
            }
        }
    })
}
function kosong(args){
  $("#id_vendor").val("");
  $("#id_item_promosi").val("");   
  $("#qty").val("");     
  $("#keterangan").val("");     
  $("#ppn").val("");     
}
function simpan_dealer(){  
  var id_promosi  = document.getElementById("id_promosi").value;   
  var id_dealer   = document.getElementById("id_dealer").value;     
  //alert(id_po);
  if (id_dealer == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h1/promosi/save_dealer')?>",
          type:"POST",
          data:"id_dealer="+id_dealer+"&id_promosi="+id_promosi,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                kirim_data_dealer();
                kosong_dealer();                
              }else{
                alert(data[0]);
                kosong_dealer();                      
              }                
          }
      })    
  }
}
function hapus_dealer(a,b){ 
    var id_promosi_dealer   = a;   
    var id_promosi  = b;       
    $.ajax({
        url : "<?php echo site_url('h1/promosi/delete_dealer')?>",
        type:"POST",
        data:"id_promosi_dealer="+id_promosi_dealer,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_dealer();
            }
        }
    })
}
function kosong_dealer(args){
  $("#id_dealer").val("");  
}
function simpan_tipe(){  
  var id_promosi  = document.getElementById("id_promosi").value;   
  var id_tipe_kendaraan   = document.getElementById("id_tipe_kendaraan").value;     
  var qty_target   = document.getElementById("qty_target").value;     
  //alert(id_po);
  if (id_tipe_kendaraan == "" || qty_target == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h1/promosi/save_tipe')?>",
          type:"POST",
          data:"id_tipe_kendaraan="+id_tipe_kendaraan+"&id_promosi="+id_promosi+"&qty_target="+qty_target,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                kirim_data_tipe();
                kosong_tipe();                
              }else{
                alert(data[0]);
                kosong_tipe();                      
              }                
          }
      })    
  }
}
function hapus_tipe(a,b){ 
    var id_promosi_tipe   = a;   
    var id_promosi  = b;       
    $.ajax({
        url : "<?php echo site_url('h1/promosi/delete_tipe')?>",
        type:"POST",
        data:"id_promosi_tipe="+id_promosi_tipe,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_tipe();
            }
        }
    })
}
function kosong_tipe(args){
  $("#id_tipe_kendaraan").val("");  
  $("#qty_target").val("");  
}
function simpan_leasing(){  
  var id_promosi  = document.getElementById("id_promosi").value;   
  var id_finance_company   = document.getElementById("id_finance_company").value;       
  //alert(id_po);
  if (id_finance_company == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h1/promosi/save_leasing')?>",
          type:"POST",
          data:"id_finance_company="+id_finance_company+"&id_promosi="+id_promosi,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                kirim_data_leasing();
                kosong_leasing();                
              }else{
                alert(data[0]);
                kosong_leasing();                      
              }                
          }
      })    
  }
}
function hapus_leasing(a,b){ 
    var id_promosi_leasing   = a;   
    var id_promosi  = b;       
    $.ajax({
        url : "<?php echo site_url('h1/promosi/delete_leasing')?>",
        type:"POST",
        data:"id_promosi_leasing="+id_promosi_leasing,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_leasing();
            }
        }
    })
}
function kosong_leasing(args){
  $("#id_finance_company").val("");    
}
</script>