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
<body onload="cek_tampil();kurangi();">
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
    if($set=="detail"){
      $row = $dt_sql->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/do_sim_part">
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
            <form class="form-horizontal" action="h3/do_sim_part/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal DO</label>
                  <div class="col-sm-4">                    
                    <input type="text" value="<?php echo $row->tgl_do ?>" name="no_po" readonly placeholder="Auto" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_po" readonly value="<?php echo $row->nama_dealer ?>" placeholder="Auto" class="form-control">                                        
                  </div>
                </div>                 
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="no_do_sim_part" id="no_do_sim_part"  value="<?php echo $row->no_do_sim_part ?>" readonly placeholder="Auto" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_po" readonly value="<?php echo $row->kode_dealer_md ?>" placeholder="Auto" class="form-control">                                        
                  </div>
                </div>                 
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No SO</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="no_so_part" id="no_so_part" readonly value="<?php echo $row->no_so_part ?>" class="form-control">                    
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
                    <input type="text" name="plafon" readonly  value="<?php echo $row->plafon ?>" class="form-control" placeholder="Plafon">
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">TOP</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="tgl_pemenuhan" readonly value="0" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Sisa Plafon</label>
                  <div class="col-sm-4">
                    <input type="text" name="plafon" readonly  value="<?php echo $row->sisa_plafon ?>" class="form-control" placeholder="Plafon">
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Salesman</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="tgl_pemenuhan" readonly value="-" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Plafon Booking</label>
                  <div class="col-sm-4">
                    <input type="text" name="plafon" readonly  value="<?php echo $row->plafon_booking ?>" class="form-control" placeholder="Plafon">
                  </div>
                </div>                              
                <div class="form-group">
                  <span id="tampil_create"></span>                  
                </div>                                                                                                                                   
              </div><!-- /.box-body -->        
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to approve all data?')" name="save" value="approve" class="btn btn-info btn-flat"><i class="fa fa-check"></i> Approve</button>                                    
                  <button type='button' data-toggle='modal' data-target='.modal_reject' onclick="detail_popup('<?php echo $row->no_do_sim_part ?>')" class='btn btn-danger btn-flat'><i class="fa fa-close"></i> Reject</button>                                                
                </div>
              </div><!-- /.box-footer -->      
              <div class="col-sm-10">
                <div class="form-group">                                    
                  <table id="myTable1" class="table table-bordered table-hover">
                    <thead>
                      <th>No Faktur</th>
                      <th>Tanggal Faktur</th>
                      <th>Tanggal Jatuh Tempo</th>
                      <th>Nominal</th>
                      <th>Status pembayaran</th>
                    </thead>
                    <tbody>
                      
                    </tbody>
                    <tfoot>
                      <tr>
                        <td align="right" colspan="3">Total Nominal</td>
                        <td></td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>                
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
          <!-- <a href="h3/do_sim_part/add">
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
              <th>Tgl DO</th>              
              <th>No Do</th>
              <th>Nama Customer</th>                            
              <th>Alamat</th>
              <th>Nilai (Amount)</th>              
              <th>Aksi</th>
              <!-- <th width="10%">Action</th> -->
            </tr>
          </thead>
          <tbosdy>            
          <?php 
          $no=1; 
          foreach($dt_do_sim_part->result() as $row) {            
            if($row->status_do == 'input'){
              $status = "<span class='label label-warning'>$row->status_do</span>";
            }else{
              $status = "<span class='label label-red'>$row->status_do</span>";            
            }
            $amount = $this->db->query("SELECT SUM(amount) AS amount FROM tr_create_do_sim_detail WHERE no_do_sim_part = '$row->no_do_sim_part'")->row();            
            echo "          
            <tr>
              <td>$no</td>              
              <td>$row->tgl_so</td>              
              <td>$row->tgl_do</td>              
              <td>$row->no_do_sim_part</td>              
              <td>$row->nama_dealer</td>              
              <td>$row->alamat</td>              
              <td>".mata_uang2($amount->amount)."</td>              
              <td>                
                <a class='btn btn-warning btn-flat btn-xs' href='h3/do_sim_part/detail?id=$row->no_do_sim_part'><i class='fa fa-eye'></i> View</a>                
                <a class='btn btn-success btn-flat btn-xs' href='h3/do_sim_part/cetak?id=$row->no_do_sim_part'><i class='fa fa-print'></i> Cetak</a>
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
<div class="modal fade modal_reject">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title">Detail</h4>
      </div>
      <div class="modal-body" id="show_pop">
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function detail_popup(no_do_sim_part)
  {
    $.ajax({
         url:"<?php echo site_url('h3/do_sim_part/detail_popup');?>",
         type:"POST",
         data:"no_do_sim_part="+no_do_sim_part,
         cache:false,
         success:function(html){
            $("#show_pop").html(html);
         }
    });
  }
</script>



<script type="text/javascript">
function kurangi(){
  var diskon_cashback = $("#diskon_cashback").val(); 
  var diskon_insentif = $("#diskon_insentif").val(); 
  var sub_total = $("#sub_total").val();     
  var total_diskon = parseInt(diskon_cashback) + parseInt(diskon_insentif);
  $("#total_diskon").val(total_diskon);   
  // alert(total_diskon);
  var ppn = (sub_total - total_diskon) * 0.1;  
  $("#total_ppn").val(ppn);   
  var total = parseInt(sub_total) - parseInt(total_diskon) - parseInt(ppn);  
  $("#total").val(total);     
  //alert(diskon_insentif);

}
function cek_tampil(){    
  $("#tampil_create").show();
  var no_do_sim_part = $("#no_do_sim_part").val();        
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "no_do_sim_part="+no_do_sim_part;
     xhr.open("POST", "h3/do_sim_part/t_detail", true); 
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