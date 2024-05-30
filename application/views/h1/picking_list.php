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
<body onload="kirim_data_pl()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Pengeluaran</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="detail"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/picking_list">
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
        
        $row = $dt_pl->row();
        ?>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h1/picking_list/save" method="post" enctype="multipart/form-data">
              <div class="box-body">       
                <div class="form-group">
                  <?php 
                  if(isset($_GET["k"])){
                    $k = "konfirm";
                  }else{
                    $k = "";
                  }
                  ?>
                  <input id="k" value="<?php echo $k ?>" type="hidden">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Picking List</label>
                  <div class="col-sm-4">
                    <input type="text" id="no_pl" required class="form-control" value="<?php echo $row->no_picking_list ?>" readonly placeholder="No Picking List" name="no_pl">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Picking List</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_pl" id="tanggal" readonly value="<?php echo $row->tgl_pl ?>" class="form-control">
                  </div>
                </div>       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.DO</label>
                  <div class="col-sm-4">
                    <input type="text" id="no_do" readonly value="<?php echo $row->no_do ?>" name="no_do" placeholder="No.DO" class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl DO</label>
                  <div class="col-sm-4">
                    <input type="text" id="tgl_do" readonly name="tgl_do" value="<?php echo $row->tgl_do ?>" placeholder="Tgl DO" class="form-control">
                  </div>
                </div>       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
                  <div class="col-sm-4">
                    <input type="text" id="gudang" readonly name="gudang" value="<?php echo $row->gudang ?>" placeholder="Gudang" class="form-control">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" id="nama_dealer" name="name_dealer" readonly placeholder="Nama Dealer" value="<?php echo $row->nama_dealer ?>" class="form-control">
                    <input type="hidden" id="biaya_pdi" name="biaya_pdi" value="<?php echo mata_uang($row->biaya_pdi) ?>" readonly placeholder="Biaya PDI" class="form-control">                    
                  </div>
                </div>       
                <!-- <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya PDI</label>
                  <div class="col-sm-4">
                  </div>                  
                </div> -->
                <hr>      
                <button disabled="disabled" class="btn btn-block btn-primary btn-flat"> UNIT </button>          
                <span id="tampil_pl"></span>   

                <?php
                if($is_ev->num_rows()>0){ ?>
                  <div class="box-body">
                  <div class="panel-ev">
                  <button disabled="disabled" class="btn btn-block btn-primary btn-flat"> KELENGKAPAN EV </button>
                    <table class="table table-bordered table-hover data-table-scan">
                      <thead>
                        <tr>
                          <th width="5%">No</th>
                          <th width="16%">Serial Number</th>
                          <th width="15%">Tipe</th>
                          <th width="34%">Kode Part</th>
                          <th width="18%">Nama Part </th>
                          <th width="16%">Fifo</th>
                        </tr>
                      </thead>
                      <tbody>
                        <!-- testing ernesto -->
                              <?php 
                               $no++;
                               foreach ($is_ev->result() as $ev) {?>
                                <tr>
                                  <td><?=$no?></td>
                                  <td><?=$ev->serial_number?></td>
                                  <td>B</td>
                                  <td><?=$ev->id_part?></td>
                                  <td><?=$ev->nama_part?></td>
                                  <td><?=$ev->fifo?></td>
                               </tr>
                              <?}
                        ?>
                      </tbody>
                    </table>                                                                                  
                  </div>  
                  </div>  
               <? }else{
                
               }
               
               ?>


            
                                  
              </div><!-- /.box-body -->
              <!-- <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <?php 
                  $cek_view = $this->db->query("SELECT * FROM tr_picking_list WHERE no_picking_list = '$_GET[id]'")->row();
                  if($cek_view->status == 'input'){
                  ?>
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                  <?php } ?>
                </div>
              </div><!-- /.box-footer --> 
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="konfirmasi"){
    ?>

<style>
      
      .hidden-input {
        border: none;
          background: none;
      }
  
      </style>


    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/picking_list">
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
        
        $row = $dt_pl->row();
        ?>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h1/picking_list/save_konfirmasi" method="post" enctype="multipart/form-data">
              <div class="box-body">       
                <div class="form-group">
                  <?php 
                  if(isset($_GET["k"])){
                    $k = "konfirm";
                  }else{
                    $k = "";
                  }
                  ?>
                  <input id="k" value="<?php echo $k ?>" type="hidden">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Picking List</label>
                  <div class="col-sm-4">
                    <input type="text" id="no_pl" required class="form-control" value="<?php echo $row->no_picking_list ?>" readonly placeholder="No Picking List" name="no_pl">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Picking List</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_pl" id="tanggal" readonly value="<?php echo $row->tgl_pl ?>" class="form-control">
                  </div>
                </div>       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.DO</label>
                  <div class="col-sm-4">
                    <input type="text" id="no_do" readonly value="<?php echo $row->no_do ?>" name="no_do" placeholder="No.DO" class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl DO</label>
                  <div class="col-sm-4">
                    <input type="text" id="tgl_do" readonly name="tgl_do" value="<?php echo $row->tgl_do ?>" placeholder="Tgl DO" class="form-control">
                  </div>
                </div>       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
                  <div class="col-sm-4">
                    <input type="text" id="gudang" readonly name="gudang" value="<?php echo $row->gudang ?>" placeholder="Gudang" class="form-control">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <?php 
                  $th = $this->db->query("SELECT * FROM ms_dealer INNER JOIN tr_do_po ON ms_dealer.id_dealer = tr_do_po.id_dealer 
                      WHERE tr_do_po.no_do = '$row->no_do'")->row();
                  ?>
                  <div class="col-sm-4">
                    <input type="text" id="nama_dealer" name="name_dealer" readonly placeholder="Nama Dealer" value="<?php echo $th->nama_dealer ?>" class="form-control">
                    <input type="hidden" id="biaya_pdi" name="biaya_pdi" value="<?php echo mata_uang($row->biaya_pdi) ?>" readonly placeholder="Biaya PDI" class="form-control">                    
                  </div>
                </div>       
                <div class="form-group">
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">Biaya PDI</label>
                  <div class="col-sm-4">
                  </div>                   -->
                  <div class="col-sm-1"></div>
                  <div class="col-sm-1">
                    <button type="button" onclick="show_text()" name="show_scan" class="btn btn-flat btn-primary"><i class="fa fa-qrcode"></i> Scan</button>
                  </div>
                  <div class="col-sm-4">
                    <input type="text" id="scan_nosin" name="scan_nosin" placeholder="Scan No Mesin" class="form-control">                    
                    <input type="hidden" id="mode">                    
                  </div>                  
                </div>                 
                <hr>        
                <span id="tampil_pl"></span>     
                <?php 
                if($is_ev == 1){ ?>
                
                <div class="box-body">
                <div class="form-group panel-ev">
                <button disabled="disabled" class="btn btn-block btn-primary btn-flat"> KELENGKAPAN EV </button>
                  <table class="table table-bordered table-hover data-table-scan">
                    <thead>
                      <tr>
                        <th width="24px">No</th>
                        <th width="20%">Serial Number</th>
                        <th width="15%">Tipe</th>
                        <th width="14%">Kode Part </th>
                        <th width="18%">Nama Part </th>
                        <th width="16%">FIFO</th>
                        <th width="18px">PDI </th>
                        <th width="12px"><input type="checkbox" class="form-check-input"></th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>                                                                                  
                </div>  
                </div>  
                <?}
                ?>
                                  
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <?php 
                $cek = $this->m_admin->getByID("tr_picking_list_view","no_picking_list",$row->no_picking_list)->num_rows();
                if($cek > 0){
                ?>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to confirm all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-check"></i> Konfirmasi</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
                <?php } ?>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <script>
          $(document).ready(function () {
            var no_pl  = $('#no_pl').val();
            show_scan_ev(no_pl);
          });

      function show_scan_ev(id){
          var i = 1;
          $.ajax({
              url:"<?php echo site_url('h1/picking_list/battery_stok');?>",
              type:"POST",
              data: {
                id:id,
              },
              cache:false,
              success: function(response) {
                var tableBody = $('.data-table-scan tbody');
                tableBody.empty();
                  $.each(response.data, function(index, item){
                    var row = $('<tr></tr>');
                    row.append('<td><b>' + i++ + '</b></td>');
                    row.append('<td><input type="text" class="hidden-input" name="oem[serial_number][]" value="' + item.serial_number + '" readonly></td>');
                    row.append('<td >B</td>');
                    row.append('<td><input type="text" class="hidden-input" name="oem[part][]" value="' + item.id_part + '" readonly></td><input type="hidden" class="hidden-input" name="oem[no_picking_list_battery][]" value="' + item.no_picking_list_battery + '" readonly>');
                    row.append('<td >' + item.nama_part + '</td>');
                    row.append('<td >' + item.fifo + '</td>');
                    row.append('<td><input type="checkbox"  name="oem[pdi]['+ item.serial_number +']"  class="form-check-input"></td>');
                    row.append('<td ><input type="checkbox" name="oem[konfirmasi][]" class="form-check-input"></td>');
                    tableBody.append(row);
                  });
                }
          });
        }
    </script>

    <?php
    }elseif($set=="view"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/picking_list">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-refresh"></i> Refresh</button>
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
        <table id="table" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>No PL</th> 
              <th>Tgl PL</th>             
              <th>No.Do</th>
              <th>Nama Dealer</th>
              <th>Status PL</th>              
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

<?php /*
<div class="modal fade" id="Itemmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search Item
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>ID Item</th>
              <th>Tipe Kendaraan</th>                                    
              <th>Warna</th>                                               
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          foreach ($dt_item->result() as $ve2) {
            echo "
            <tr>
              <td>$no</td>
              <td>$ve2->id_item</td>
              <td>$ve2->tipe_ahm</td>
              <td>$ve2->warna</td>";
              ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="chooseitem('<?php echo $ve2->id_item; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
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
*/
?>

<script type="text/javascript">

  $(document).ready(function() {
     $('#table').DataTable({
	      "scrollX": true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "ajax": {
            "url": "<?php echo site_url('h1/picking_list/fetch_data')?>",
            "type": "POST"
        },
        "columnDefs": [
        {
            "targets": [ 0,5 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });
  });

function cek_do(){
  var no_do = document.getElementById("no_do").value; 
  $.ajax({
      url : "<?php echo site_url('h1/picking_list/cari_do')?>",
      type:"POST",
      data:"no_do="+no_do,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");        
        $("#nama_dealer").val(data[1]);
        $("#gudang").val(data[2]);        
        $("#tgl_do").val(data[3]);
        $("#biaya_pdi").val(data[4]);
        kirim_data_pl();     
        //cek_jenis();                   
      }        
  })
}
function auto(){
  var jenis_do = document.getElementById("tanggal").value; 
  $.ajax({
      url : "<?php echo site_url('h1/picking_list/cari_id')?>",
      type:"POST",
      data:"jenis_do="+jenis_do,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#no_pl").val(data[0]);
        kirim_data_pl();     
        $("#mode").val('');
      }        
  })
}
function show_text(){
  var mode = document.getElementById("mode").value;
  if(mode=='scan'){
    $("#mode").val('');
  }else{
    $("#mode").val('scan');
  }  
  cek_scan();
}
function cek_scan(){
  var mode = document.getElementById("mode").value;
  if(mode=='scan'){
    $("#scan_nosin").show();    
    $("#scan_nosin").val('');    
    $("#scan_nosin").focus();    
  }else{
    $("#scan_nosin").hide();    
  }
}
function kirim_data_pl(){    
  $("#tampil_pl").show();  
  //cek_scan();
  var no_do = document.getElementById("no_do").value;
  var no_pl = document.getElementById("no_pl").value;
  var k     = document.getElementById("k").value;  
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "no_pl="+birthday1_js;          
    var data = "no_do="+no_do+"&no_pl="+no_pl+"&k="+k;                           
     xhr.open("POST", "h1/picking_list/t_pl", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_pl").innerHTML = xhr.responseText;
                JS();
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function tes(){
  alert("ok");
}
//--------------------------------------------------------------------------------------//
function simpan_scan(){
  var scan_nosin = document.getElementById("scan_nosin").value;
  //alert(scan_nosin);
  $.ajax({
      url : "<?php echo site_url('h1/picking_list/save_nosin')?>",
      type:"POST",
      data:"scan_nosin="+scan_nosin,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");                
        kirim_data_pl();     
        $("#scan_nosin").val('');
        $("#scan_nosin").focus();
      }        
  })
}
</script>
<script type="text/javascript">
var scan_nosin = document.getElementById("scan_nosin");
scan_nosin.addEventListener("input", function (e) {
  //if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
  simpan_scan();  
  //}
});
</script>


<script type="text/javascript">
 function JS() {
  $("#checkedAll").change(function(){
    if(this.checked){
      $(".checkSingle").each(function(){
        this.checked=true;
      })              
    }else{
      $(".checkSingle").each(function(){
        this.checked=false;
      })              
    }
  });

  $(".checkSingle").click(function () {
    if ($(this).is(":checked")){
      var isAllChecked = 0;
      $(".checkSingle").each(function(){
        if(!this.checked)
           isAllChecked = 1;
      })              
      if(isAllChecked == 0){ $("#checkedAll").prop("checked", true); }     
    }else {
      $("#checkedAll").prop("checked", false);
    }
  });
}
</script>
<script type="text/javascript">
 function JS() {
  $("#checkedAll2").change(function(){
    if(this.checked){
      $(".checkSingle2").each(function(){
        this.checked=true;
      })              
    }else{
      $(".checkSingle2").each(function(){
        this.checked=false;
      })              
    }
  });

  $(".checkSingle2").click(function () {
    if ($(this).is(":checked")){
      var isAllChecked = 0;
      $(".checkSingle2").each(function(){
        if(!this.checked)
           isAllChecked = 1;
      })              
      if(isAllChecked == 0){ $("#checkedAll2").prop("checked", true); }     
    }else {
      $("#checkedAll2").prop("checked", false);
    }
  });
}
</script>

<script type="text/javascript">
function toggle(source) {
    var checkboxes = document.querySelectorAll('input[class="data_check"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
    }
}
</script>
