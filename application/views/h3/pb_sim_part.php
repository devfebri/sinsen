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
          <a href="h3/pb_sim_part">
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
            <form class="form-horizontal" action="h3/pb_sim_part/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Proses Bagi Barang</label>
                  <div class="col-sm-2">                    
                    <input type="text" name="no_pb_sim_part" placeholder="Auto" id="no_pb_sim_part" readonly class="form-control">                    
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
          <a href="h3/pb_sim_part">
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
            <form class="form-horizontal" action="h3/pb_sim_part/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="no_po" readonly value="<?php echo $row->id_pb_sim_part ?>" placeholder="Auto" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_po" readonly value="<?php echo $row->nama_dealer ?>" placeholder="Auto" class="form-control">                                        
                  </div>
                </div>                 
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Pemenuhan</label>
                  <div class="col-sm-4">                    
                    <input type="text" id="tanggal1" name="tgl_pemenuhan" readonly value="<?php echo $row->tgl_pemenuhan ?>" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="kode_dealer_md" readonly id="kode_dealer_md" class="form-control" value="<?php echo $row->kode_dealer_md ?>">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                  <div class="col-sm-4">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Custmoer</label>
                  <div class="col-sm-4">
                    <input type="text" name="alamat" readonly id="alamat" class="form-control" value="<?php echo $row->alamat ?>">                    
                  </div>
                </div>                                 
                <div class="form-group">
                  <table id="myTable1" class="table myt order-list" border="0">     
                    <thead>
                      <tr>              
                        <th width="5%">No</th>
                        <th>Kode Part</th>              
                        <th width="40%">Nama Part</th>
                        <th width="10%">HET</th>
                        <th width="10%">Qty On Hand MD</th>
                        <th width="10%">Qty Order</th>      
                        <th width="10%">Total Harga</th>        
                      </tr>
                    </thead>
                    <tbody>            
                      <?php 
                      $no=1;$total=0;$g_total=0;
                      $sql2 = $this->db->query("SELECT tr_pb_sim_part_detail.*,ms_part.nama_part,ms_part.id_part FROM tr_pb_sim_part_detail LEFT JOIN ms_part ON tr_pb_sim_part_detail.id_part = ms_part.id_part
                        WHERE tr_pb_sim_part_detail.id_pb_sim_part = '$row->id_pb_sim_part'");
                      foreach ($sql2->result() as $isi) {
                        $total = mata_uang2($g_tot = $isi->qty * $isi->harga);
                        $harga = mata_uang2($isi->harga);
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
                              <input type='text' id='qty_on_hand' value='$isi->qty' style='text-align: right;' readonly class='form-control isi'>
                            </td>
                            <td>
                              <input type='text' id='qty_order' value='$isi->qty' style='text-align: right;' readonly class='form-control isi'>
                            </td>
                            <td>                              
                              <input type='text' id='total_harga' value='$total' style='text-align: right;' readonly class='form-control isi'>
                            </td>
                          </tr>
                        ";
                        $no++;
                      }
                      ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="6" align="right">Grand Total</td>
                        <td align="right" colspan="0">
                          <input type='text' id='total_harga' value="<?php echo mata_uang2($g_total) ?>" style='text-align: right;' readonly class='form-control isi'>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>                                                                                                                                   
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
          <a href="h3/pb_sim_part/add">
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
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>No Proses Barang Bagi</th>              
              <th>Start Date</th>              
              <th>End Date</th>                            
              <th>Amount</th>
              <!-- <th width="10%">Action</th> -->
            </tr>
          </thead>
          <tbosdy>            
          <?php 
          $no=1; 
          foreach($dt_pb_sim_part->result() as $row) {            
            $amount = $this->db->query("SELECT SUM(amount) AS amount FROM tr_pb_sim_part_detail WHERE no_pb_sim_part = '$row->no_pb_sim_part'")->row();
              // <td>
              //   <a class='btn btn-warning btn-flat btn-xs' href='h3/pb_sim_part/detail?id=$row->no_pb_sim_part'><i class='fa fa-eye'></i> View</a>
              // </td>              
            echo "          
            <tr>
              <td>$no</td>              
              <td>$row->no_pb_sim_part</td>              
              <td>$row->start_date</td>              
              <td>$row->end_date</td>              
              <td>".mata_uang2($amount->amount)."</td>              
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
function detail_popup(no_so_part)
  {
    $.ajax({
         url:"<?php echo site_url('h3/pb_sim_part/detail_popup');?>",
         type:"POST",
         data:"no_so_part="+no_so_part,
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
      url : "<?php echo site_url('h3/pb_sim_part/cari_id')?>",
      type:"POST",
      data:"id_dealer="+id_dealer,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#no_pb_sim_part").val(data[0]);                              
      }        
  })
}

function show_detail(){    
  $("#tampil_pb").show();
  var start_date = $("#tanggal1").val();    
  var end_date = $("#tanggal2").val();    
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "start_date="+start_date+"&end_date="+end_date;                           
     xhr.open("POST", "h3/pb_sim_part/t_detail", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_pb").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
</script>