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
if(!isset($_GET['id'])){ ?>
<body onload="auto()">
<?php
}
?>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H3</li>
    <li class="">Logistik</li>
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
          <a href="h3/so_spare_part">
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
            <form class="form-horizontal" action="h3/so_spare_part/simpan" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl SO</label>
                  <div class="col-sm-4">                    
                    <input type="text" id="tanggal1" name="tgl_so" readonly value="<?php echo date("Y-m-d") ?>" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required name="id_dealer" id="id_dealer" onchange="cek_dealer()">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_dealer->result() as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->nama_dealer</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>                 
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No SO</label>
                  <div class="col-sm-4">                    
                    <input type="text" id="no_so_spare" name="no_so_spare" readonly placeholder="Auto" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="kode_dealer_md" readonly id="kode_dealer_md" class="form-control">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Penjualan</label>
                  <div class="col-sm-4">                                        
                    <select class="form-control" name="tipe_po">
                      <option value="">- choose -</option>
                      <option>Fix/Reg</option>
                      <option>Hotline</option>
                      <option>Urgent</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Custmoer</label>
                  <div class="col-sm-4">
                    <input type="text" name="alamat" readonly id="alamat" class="form-control">                    
                  </div>
                </div>        
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Salesman</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required name="id_karyawan_dealer" id="id_karyawan_dealer">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_sales->result() as $val) {
                        echo "
                        <option value='$val->id_karyawan_dealer'>$val->nama_lengkap</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembayaran</label>
                  <div class="col-sm-4">                    
                    <select class="form-control" name="jenis_bayar">
                      <option value="">- choose -</option>
                      <option>Credit</option>
                      <option>Tunai</option>                      
                    </select>
                  </div>
                </div>     
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Masa Berlaku SO</label>
                  <div class="col-sm-4">
                    <input type="text" name="masa_berlaku" id="masa_berlaku" class="form-control">                    
                  </div>
                </div>                     
                <div class="form-group">
                  <span id="tampil_so"></span>
                </div>                                                                                                                                   
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>                  
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php  
    }elseif($set=="detail"){
      $row = $dt_sql->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/so_spare_part">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
            <form class="form-horizontal" action="h3/so_spare_part/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No SO</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="no_so_spare" id="no_so_spare" readonly value="<?php echo $row->no_so_spare ?>" placeholder="Auto" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_po" readonly value="<?php echo $row->nama_dealer ?>" placeholder="Auto" class="form-control">                                        
                  </div>
                </div>                 
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl SO</label>
                  <div class="col-sm-4">                    
                    <input type="text" id="tanggal1" name="tgl_pemenuhan" readonly value="<?php echo $row->tgl_so ?>" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="kode_dealer_md" readonly id="kode_dealer_md" class="form-control" value="<?php echo $row->kode_dealer_md ?>">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Penjualan</label>
                  <div class="col-sm-4">                                        
                    <input type="text" name="alamat" readonly id="alamat" class="form-control" value="<?php echo $row->tipe_po ?>">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Custmoer</label>
                  <div class="col-sm-4">
                    <input type="text" name="alamat" readonly id="alamat" class="form-control" value="<?php echo $row->alamat ?>">                    
                  </div>
                </div>                                 
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Salesman</label>
                  <div class="col-sm-4">                                        
                    <input type="text" name="alamat" readonly id="alamat" class="form-control" value="<?php echo $row->nama_lengkap ?>">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembayaran</label>
                  <div class="col-sm-4">                                        
                    <input type="text" name="alamat" readonly id="alamat" class="form-control" value="<?php echo $row->jenis_bayar ?>">                    
                  </div>                  
                </div>                                 
                <div class="form-group">
                  <table id="myTable1" class="table myt order-list" border="0">     
                    <thead>
                      <tr>              
                        <th width="5%">No</th>
                        <th width="15%">Kode Part</th>              
                        <th width="30%">Nama Part</th>
                        <th width="10%">HET</th>
                        <th width="10%">Qty Actual</th>                        
                        <th width="10%">Qty Order</th>
                        <th width="10%">Qty On Hand</th>
                        <th width="10%">Qty Pemenuhan</th>        
                      </tr>
                    </thead>
                    <tbody>            
                      <?php 
                      $no=1;$total=0;$g_total=0;
                      $sql2 = $this->db->query("SELECT tr_so_spare_detail.*,ms_part.nama_part,ms_part.id_part FROM tr_so_spare_detail LEFT JOIN ms_part ON tr_so_spare_detail.id_part = ms_part.id_part
                        WHERE tr_so_spare_detail.no_so_spare = '$row->no_so_spare'");
                      foreach ($sql2->result() as $isi) {
                        $total = mata_uang2($g_tot = $isi->qty_order * $isi->het);
                        $harga = mata_uang2($isi->het);
                        $g_total += $g_tot;
                        echo "
                          <tr>
                            <td>$no</td>
                            <td>
                              <input type='text' id='part' value='$isi->id_part' readonly class='form-control isi'>
                            </td>                            
                            <td>
                              <input type='text' id='part' value='$isi->nama_part' readonly class='form-control isi'>
                            </td>
                            <td>
                              <input type='text' id='het' value='$harga' style='text-align: right;' readonly class='form-control isi'>
                            </td>
                            <td>
                              <input type='text' id='qty_on_hand' value='$isi->qty_order' style='text-align: right;' readonly class='form-control isi'>
                            </td>
                            <td>
                              <input type='text' id='qty_on_hand' value='$isi->qty_order' style='text-align: right;' readonly class='form-control isi'>
                            </td>
                            <td>
                              <input type='text' id='qty_order' value='$isi->qty_order' style='text-align: right;' readonly class='form-control isi'>
                            </td>                            
                            <td>
                              <input type='text' id='qty_order' value='$isi->qty_order' style='text-align: right;' readonly class='form-control isi'>
                            </td>                            
                          </tr>
                        ";
                        $no++;
                      }
                      ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="7" align="right">Grand Total</td>
                        <td align="right" colspan="0">
                          <input type='text' id='total_harga' value="<?php echo mata_uang2($g_total) ?>" style='text-align: right;' readonly class='form-control isi'>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>                                                                                                                                   
              </div><!-- /.box-body -->              
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to approve all data?')" name="save" value="approve" class="btn btn-info btn-flat"><i class="fa fa-check"></i> Approve</button>                  
                  <button type="submit" onclick="return confirm('Are you sure to reject all data?')" name="save" value="reject" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Reject</button>                  
                  <button type="button" name="save" value="edit" class="btn btn-primary btn-flat"><i class="fa fa-edit"></i> Edit</button>                  
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
          <a href="h3/so_spare_part/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>                   
          <a href="h3/so_spare_part/upload">
            <button class="btn bg-green btn-flat margin"><i class="fa fa-upload"></i> Upload</button>
          </a>                   
          <a href="h3/so_spare_part/download">
            <button class="btn btn-warning btn-flat margin"><i class="fa fa-download"></i> Download</button>
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
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>Tgl SO Dealer</th>              
              <th>No SO/PO</th>              
              <th>Tipe Penjualan</th>              
              <th>Nama Customer</th>
              <th>Alamat</th>
              <th>Nilai (Amount)</th>
              <th>Status</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbosdy>            
          <?php 
          $no=1; 
          foreach($dt_so_spare->result() as $row) {            
            $amount = $this->db->query("SELECT SUM(het * qty_order) AS harga FROM tr_so_spare_detail WHERE no_so_spare = '$row->no_so_spare'")->row();
            if($row->status_so == 'input'){
              $status = "<span class='label label-warning'>$row->status_so</span>";
            }elseif($row->status_so == 'approved'){
              $status = "<span class='label label-success'>$row->status_so</span>";
            }else{
              $status = "<span class='label label-danger'>$row->status_so</span>";
            }
            echo "          
            <tr>
              <td>$no</td>              
              <td>$row->tgl_so</td>              
              <td>$row->no_so_spare</td>              
              <td>$row->tipe_po</td>              
              <td>$row->nama_dealer</td>              
              <td>$row->alamat</td>              
              <td>".mata_uang2($amount->harga)."</td>              
              <td>$status</td>              
              <td>
                <a class='btn btn-warning btn-flat btn-xs' href='h3/so_spare_part/detail?id=$row->no_so_spare'><i class='fa fa-eye'></i> View</a>
              </td>              
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
<div class="modal fade" id="Partmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Cari Part
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="table" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="10%"></th>              
              <th>ID Part</th>
              <th>Nama Part</th>                                    
              <th>Satuan</th>
              <th>Harga</th>                                               
            </tr>
          </thead>          
        </table>
      </div>      
    </div>
  </div>
</div>
<div class="modal fade modal_detail">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title">Detail</h4>
      </div>
      <div class="modal-body" id="show_detail">
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function detail_popup(id_part,request_id)
  {
    $.ajax({
         url:"<?php echo site_url('h3/so_spare_part/detail_popup');?>",
         type:"POST",
         data:"id_part="+id_part+"&request_id="+request_id,
         cache:false,
         success:function(html){
            $("#show_detail").html(html);
         }
    });
  }
</script>



<script type="text/javascript">
function cek_dealer(){
  var id_dealer = $("#id_dealer").val();
  $.ajax({
      url : "<?php echo site_url('h3/so_spare_part/cari_dealer')?>",
      type:"POST",
      data:"id_dealer="+id_dealer,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_dealer").val(data[0]);              
        $("#kode_dealer_md").val(data[1]);              
        $("#alamat").val(data[2]);              
      }        
  })
}
function auto(){
  var id_dealer = 1;
  $.ajax({
      url : "<?php echo site_url('h3/so_spare_part/cari_no_spare')?>",
      type:"POST",
      data:"id_dealer="+id_dealer,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#no_so_spare").val(data[0]);                      
        show_detail();
      }        
  })
}
function choosepart(id_part){
  document.getElementById("id_part").value = id_part;  
  $("#Partmodal").modal("hide");
  cek_part();
}
function cek_part(){
  var id_part = $("#id_part").val();
  $.ajax({
      url : "<?php echo site_url('h3/so_spare_part/cek_part')?>",
      type:"POST",
      data:"id_part="+id_part,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_part").val(data[0]);                        
        $("#nama_part").val(data[1]);                        
        $("#het").val(data[2]);                                
      }        
  })
}
function kalikan(){
  var het = $("#het").val();
  var qty_order = $("#qty_order").val();
  nilai = het * qty_order;
  $("#nilai").val(nilai);
}
function simpan_sim(){  
  var id_part        = document.getElementById("id_part").value;   
  var no_so_spare           = document.getElementById("no_so_spare").value;   
  var het         = document.getElementById("het").value;   
  var qty_on_hand            = document.getElementById("qty_on_hand").value;   
  var qty_order          = document.getElementById("qty_order").value;     
  //alert(id_po);
  if (no_so_spare == "" || id_part == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h3/so_spare_part/save_sim')?>",
          type:"POST",
          data:"id_part="+id_part+"&no_so_spare="+no_so_spare+"&het="+het+"&qty_order="+qty_order+"&qty_on_hand="+qty_on_hand,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                show_detail();
                kosong();                
              }else{
                alert(data[0]);
                kosong();                      
              }                
          }
      })    
  }
}
function hapus_sim(a,b){ 
    var id_so_spare_detail   = a;   
    var id_part      = b;       
    $.ajax({
        url : "<?php echo site_url('h3/so_spare_part/delete_sim')?>",
        type:"POST",
        data:"id_so_spare_detail="+id_so_spare_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              show_detail();
            }
        }
    })
}
function kosong(args){
  $("#id_part").val("");
  $("#nama_part").val("");   
  $("#het").val("");   
  $("#qty_order").val("");     
  $("#qty_on_hand").val("");       
  $("#nilai").val("");     
}
function show_detail(){    
  $("#tampil_so").show();
  var no_so_spare = $("#no_so_spare").val();    
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "no_so_spare="+no_so_spare;                           
     xhr.open("POST", "h3/so_spare_part/t_detail", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_so").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
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
            "url": "<?php echo site_url('h3/so_spare_part/ajax_list')?>",
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