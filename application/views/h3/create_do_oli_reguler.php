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
<?php if(isset($_GET['id'])){ ?>
<body onload="cek_tampil()">
<?php } ?>
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
          <a href="h3/create_do_oli_reguler">
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
            <form class="form-horizontal" action="h3/create_do_oli_reguler/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Proses Bagi Barang</label>
                  <div class="col-sm-2">                    
                    <input type="text" name="no_create_do_oli_reguler" placeholder="Auto" id="no_create_do_oli_reguler" readonly class="form-control">                    
                  </div>                  
                </div>                 
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                  <div class="col-sm-2">                    
                    <input type="text" id="tanggal1" name="tgl_1" value="<?php echo date("Y-m-d") ?>" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-1 control-label">End Date</label>
                  <div class="col-sm-2">
                    <input type="text" id="tanggal2" name="tgl_2" value="<?php echo date("Y-m-d") ?>" class="form-control">                    
                  </div>                
                  <div class="col-sm-4">
                    <button onclick="show_detail()" class="btn btn-primary btn-flat" type="button"><i class="fa fa-refresh"></i> Generate</button>
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Presentase Bagi</label>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Fix %</label>
                  <div class="col-sm-2">                                        
                    <input type="text" name="fix" id="fix" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-1 control-label">Reg %</label>
                  <div class="col-sm-2">                                        
                    <input type="text" name="reg" id="reg" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-1 control-label">HO %</label>
                  <div class="col-sm-2">                                        
                    <input type="text" name="ho" id="ho" class="form-control">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Urgent %</label>
                  <div class="col-sm-2">                                        
                    <input type="text" name="urgent" id="urgent" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-1 control-label">Umum %</label>
                  <div class="col-sm-2">                                        
                    <input type="text" name="umum" id="umum" class="form-control">                    
                  </div>
                </div>                 
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                  <div class="col-sm-4">                                        
                  </div>                  
                  <div class="col-sm-2"></div>                
                </div>
                <div class="form-group">
                  <span id="tampil_pb"></span>
                </div>                                                                                                                                   
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to process all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Process</button>                  
                  <!-- <button type="button" onclick="return confirm('Are you sure to edit all data?')" name="save" value="save" class="btn btn-primary btn-flat"><i class="fa fa-edit"></i> Edit</button>                  
                  <button type="button" onclick="return confirm('Are you sure to non-aktif all data?')" name="save" value="save" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Non Aktif</button>                   -->
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
          <a href="h3/create_do_oli_reguler">
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
            <form class="form-horizontal" action="h3/create_do_oli_reguler/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="no_po" readonly placeholder="Auto" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_po" readonly value="<?php echo $row->nama_dealer ?>" placeholder="Auto" class="form-control">                                        
                  </div>
                </div>                 
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No SO</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="no_so_oil" id="no_so_oil" readonly value="<?php echo $row->no_so_oil ?>" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="alamat" readonly id="alamat" class="form-control" value="<?php echo $row->alamat ?>">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl SO</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="tgl_pemenuhan" readonly value="<?php echo $row->tgl_so ?>" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Plafon</label>
                  <div class="col-sm-4">
                    <input type="text" name="plafon" readonly class="form-control" value="0" placeholder="Plafon">
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Part Number</label>
                  <div class="col-sm-4">                    
                    <select id="id_part" name="id_part" class="form-control select2" onchange="cek_tampil()">
                      <option value="">- All -</option>
                      <?php 
                      $sql = $this->db->query("SELECT * FROM ms_part WHERE kelompok_part = 'OIL'");
                      //$sql = $this->m_admin->getAll("ms_part");
                      foreach ($sql->result() as $key) {
                        echo "<option value='$key->id_part'>$key->nama_part</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Plafon Booking</label>
                  <div class="col-sm-4">
                    <input type="text" name="plafon_booking" readonly class="form-control" value="0" placeholder="Plafon Booking">
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelompok Part</label>
                  <div class="col-sm-4">                    
                    <select id="kelompok_part" name="kelompok_part" class="form-control select2" onchange="cek_tampil()">
                      <option value="">- All -</option>
                      <?php 
                      $sql2 = $this->db->query("SELECT DISTINCT(kelompok_part) FROM ms_part");
                      foreach ($sql2->result() as $key) {
                        echo "<option value='$key->kelompok_part'>$key->kelompok_part</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Sisa Plafon</label>
                  <div class="col-sm-4">
                    <input type="text" name="sisa_plafon" readonly class="form-control" value="0" placeholder="Sisa Plafon">
                  </div>
                </div>                
                <div class="form-group">
                  <span id="tampil_create"></span>                  
                </div>                                                                                                                                   
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Alasan Finance Reject</label>
                  <div class="col-sm-10">
                    <?php 
                    $alasan_reject="";
                    $cek = $this->db->query("SELECT * FROM tr_create_do_oli WHERE no_so_oil = '$row->no_so_oil'");
                    if($cek->num_rows() > 0){
                      $alasan_reject = $cek->row()->alasan_reject;
                    }
                    ?>
                    <input type="text" name="alasan_finance_reject" value="<?php echo $alasan_reject ?>" readonly class="form-control" placeholder="Alasan Finance Reject">
                  </div>
                </div>                
              </div><!-- /.box-body -->        
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to create all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Create</button>                  
                  <!-- <button type="button" onclick="return confirm('Are you sure to edit all data?')" name="save" value="save" class="btn btn-primary btn-flat"><i class="fa fa-edit"></i> Edit</button>                   -->
                  <button type="button" onclick="return confirm('Are you sure to delete all data?')" name="save" value="save" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Delete</button>                  
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
          <!-- <a href="h3/create_do_oli_reguler/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>                    -->
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
              <th>Tgl SO</th>              
              <th>No SO</th>              
              <th>Nama Customer</th>                            
              <th>Alamat</th>
              <th>Nilai (Amount)</th>
              <th>Status</th>
              <th>Aksi</th>
              <!-- <th width="10%">Action</th> -->
            </tr>
          </thead>
          <tbosdy>            
          <?php 
          $no=1; 
          foreach($dt_pb_oli_reguler->result() as $row) {            
            $cek = $this->db->query("SELECT * FROM tr_create_do_oli WHERE no_so_oil = '$row->no_so_oil'");
            if($cek->num_rows() > 0){
              $status_do = $cek->row()->status_do;
              if($status_do == 'input'){
                $status = "<span class='label label-primary'>New</span>";  
              }elseif($status_do == 'approved'){
                $status = "<span class='label label-success'>Approved</span>";  
              }else{
                $status = "<span class='label label-danger'>Reject By Finance</span>";  
              }
            }else{              
              if($row->status_pb_detail == 'input'){
                $status = "<span class='label label-primary'>New</span>";
              }else{
                $status = "<span class='label label-red'>Reject by Finance</span>";            
              }            
            }
            echo "          
            <tr>
              <td>$no</td>              
              <td>$row->tgl_so</td>              
              <td>$row->no_so_oil</td>              
              <td>$row->nama_dealer</td>              
              <td>$row->alamat</td>              
              <td>".mata_uang2($row->amount)."</td>
              <td>$status</td>              
              <td>
                <a class='btn btn-primary btn-flat btn-xs'><i class='fa fa-edit'></i> Edit</a>
                <a class='btn btn-danger btn-flat btn-xs' href='h3/create_do_oli_reguler/delete?id=$row->no_so_oil&d=$row->no_pb_oli_reguler'><i class='fa fa-close'></i> Del</a>
                <a class='btn btn-warning btn-flat btn-xs' href='h3/create_do_oli_reguler/detail?id=$row->no_so_oil'><i class='fa fa-eye'></i> View</a>
                <a class='btn btn-success btn-flat btn-xs'><i class='fa fa-plus'></i> Create</a>
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
<div class="modal fade modal_detail">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title">Detail</h4>
      </div>
      <div class="modal-body" id="show_pop">
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function detail_popup(no_so_oil)
  {
    $.ajax({
         url:"<?php echo site_url('h3/create_do_oli_reguler/detail_popup');?>",
         type:"POST",
         data:"no_so_oil="+no_so_oil,
         cache:false,
         success:function(html){
            $("#show_pop").html(html);
         }
    });
  }
</script>



<script type="text/javascript">
function auto(){
  var id_dealer = 1;
  $.ajax({
      url : "<?php echo site_url('h3/create_do_oli_reguler/cari_id')?>",
      type:"POST",
      data:"id_dealer="+id_dealer,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#no_create_do_oli_reguler").val(data[0]);                              
      }        
  })
}
function kalikan(){
  var jum = $("#jum").val(); 
  var sub_add = 0;
  for(i=1;i<=jum;i++){  
    var qty_supply  = $("#qty_supply_"+i+"").val();            
    var harga  = $("#harga_"+i+"").val();            
    var amount = qty_supply * harga;  
    $("#amount_"+i+"").val(amount);   
    
    sub_add = sub_add + amount;
  }
  //var sub_total = parseInt($("#sub_total").val());   
  $("#sub_total").val(sub_add);   
  var ppn = sub_add * 0.1;
  $("#total_ppn").val(ppn);   
  var g_total = sub_add - ppn;  
  $("#total").val(g_total);   
}
function cek_tampil(){    
  $("#tampil_create").show();
  var no_so_oil = $("#no_so_oil").val();      
  var id_part = $("#id_part").val();      
  var kelompok_part = $("#kelompok_part").val();      
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "no_so_oil="+no_so_oil+"&id_part="+id_part+"&kelompok_part="+kelompok_part;                           
     xhr.open("POST", "h3/create_do_oli_reguler/t_detail", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_create").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
</script>