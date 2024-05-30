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
<body onload="auto()">
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
    if($set=="start"){
      $row = $sql->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/validasi_picking_list">
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
            <form class="form-horizontal" action="h3/validasi_picking_list/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Picking List</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="no_pl_part" readonly  value="<?php echo $row->no_pl_part ?>" class="form-control">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Do</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="tgl_pemenuhan" readonly value="<?php echo $row->no_do_part ?>" class="form-control">                    
                  </div>                  
                </div>                 
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Picking List</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="tgl_pemenuhan" value="<?php echo $row->tgl_pl ?>" readonly class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl DO</label>
                  <div class="col-sm-4">
                    <?php 
                    $tgl_do = "";
                    $cek_tgl1 = $this->db->query("SELECT * FROM tr_create_do_sim INNER JOIN tr_so_part ON tr_create_do_sim.no_so_part = tr_so_part.no_so_part
                      WHERE tr_create_do_sim.no_do_sim_part = '$row->no_do_part'");
                    $cek_tgl2 = $this->db->query("SELECT * FROM tr_create_do_spare INNER JOIN tr_so_spare ON tr_create_do_spare.no_so_spare = tr_so_spare.no_so_spare
                      WHERE tr_create_do_spare.no_do_spare_part = '$row->no_do_part'");
                    $cek_tgl3 = $this->db->query("SELECT * FROM tr_create_do_oli INNER JOIN tr_so_oil ON tr_create_do_oli.no_so_oil = tr_so_oil.no_so_oil
                      WHERE tr_create_do_oli.no_do_oli_reguler = '$row->no_do_part'");
                    if($cek_tgl1->num_rows() > 0){
                      $tgl_do = $cek_tgl1->row()->tgl_do;
                      $tgl_so = $cek_tgl1->row()->tgl_so;
                      $tipe_po = $cek_tgl1->row()->tipe_po;
                    }elseif($cek_tgl2->num_rows() > 0){
                      $tgl_do = $cek_tgl2->row()->tgl_do;
                      $tgl_so = $cek_tgl2->row()->tgl_so;
                      $tipe_po = $cek_tgl2->row()->tipe_po;
                    }elseif($cek_tgl3->num_rows() > 0){
                      $tgl_do = $cek_tgl3->row()->tgl_do;
                      $tgl_so = $cek_tgl3->row()->tgl_so;
                      $tipe_po = $cek_tgl3->row()->tipe_po;
                    }
                    ?>
                    <input type="text" name="kode_dealer_md" value="<?php echo $tgl_do ?>" readonly id="kode_dealer_md" class="form-control">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="tgl_pemenuhan" readonly value="<?php echo $row->nama_dealer ?>" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl SO</label>
                  <div class="col-sm-4">
                    <input type="text" name="kode_dealer_md" readonly value="<?php echo $tgl_so ?>" id="kode_dealer_md" class="form-control">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="tgl_pemenuhan" value="<?php echo $row->alamat ?>" readonly class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Salesman</label>
                  <div class="col-sm-4">
                    <input type="text" name="kode_dealer_md" value="<?php echo $row->nama_lengkap ?>" readonly id="kode_dealer_md" class="form-control">                    
                  </div>
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="tgl_pemenuhan" value="<?php echo $tipe_po ?>" readonly class="form-control">                    
                  </div>                  
                </div>                
                <div class="form-group">
                  <table id="table" class="table table-bordered table-hover">
                    <thead>
                      <tr>                                      
                        <th>No</th>              
                        <th>Kode Part</th>
                        <th>Nama Part</th>
                        <th>Lokasi Part</th>
                        <th>Qty AVS</th>      
                        <th>Qty Picking List</th>        
                        <th>Qty Disiapkan</th>                        
                      </tr>
                    </thead>
                    <tbody>            
                      <?php 
                      $no=1;
                      $sql_2 = $this->db->query("SELECT *,ms_part.id_part AS id_p FROM tr_pl_part_detail
                        LEFT JOIN ms_part ON tr_pl_part_detail.id_part = ms_part.id_part
                        LEFT JOIN tr_stok_part ON tr_pl_part_detail.id_part = tr_stok_part.id_part 
                        WHERE no_pl_part = '$row->no_pl_part'");
                      foreach ($sql_2->result() as $isi) {
                        $jum = $sql_2->num_rows();
                        echo "
                        <tr>
                          <td>$no</td>
                          <td>$isi->id_p</td>
                          <td>$isi->nama_part</td>
                          <td></td>
                          <td>$isi->qty_avs</td>
                          <td>$isi->qty_supply</td>
                          <td>
                            <input type='hidden' name='id_part_$no' value='$isi->id_p'>
                            <input type='hidden' name='jum' value='$jum'>
                            <input type='text' value='$isi->qty_supply' class='form-control isi' name='qty_validasi_$no'>
                          </td>                          
                        </tr>
                        ";
                        $no++;
                      }
                      ?>
                    </tbody>                    
                  </table>
                </div>                                                                                                                                   
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>                  
                  <button type="submit" onclick="return confirm('Are you sure to save & close all data?')" name="save" value="close" class="btn btn-success btn-flat"><i class="fa fa-check"></i> Save & Close</button>                  
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
          <!-- <a href="h3/validasi_picking_list/add">
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
              <th>Nama Picker</th>              
              <th>Tipe PO</th>              
              <th>Tgl Picking List</th>              
              <th>No Picking List</th>
              <th>Nama Customer</th>
              <th>Alamat Customer</th>
              <th>Start Pick</th>
              <th>End Pick</th>
              <th>Durasi</th>
              <th>Status</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbosdy>            
          <?php 
          $no=1; 
          foreach($dt_validasi_picking_list->result() as $row) { 
            $am = $this->db->query("SELECT SUM(qty_supply) AS pcs FROM tr_pl_part_detail WHERE no_pl_part = '$row->no_pl_part'")->row();
            $as = $this->db->query("SELECT COUNT(id_part) AS item FROM tr_pl_part_detail WHERE no_pl_part = '$row->no_pl_part'")->row();
            if($row->status_pl == 'input'){
              $status = "";
            }elseif($row->status_pl == 'open'){
              $status = "<span class='label label-primary'>Open</span>";
            }elseif($row->status_pl == 'on process'){
              $status = "<span class='label label-warning'>On Process</span>";
            }elseif($row->status_pl == 'close'){
              $status = "<span class='label label-danger'>Close</span>";
            }elseif($row->status_pl == 're-check'){
              $status = "<span class='label label-danger'>Re-Check</span>";                        
            }
            echo "          
            <tr>
              <td>$no</td>              
              <td>$row->nama_lengkap</td>   
              <td>$row->jenis_po</td>              
              <td>$row->tgl_pl</td>              
              <td>$row->no_pl_part</td>              
              <td>$row->nama_dealer</td>              
              <td>$row->alamat2</td>              
              <td>$row->start_pick</td>              
              <td>$row->end_pick</td>              
              <td></td>              
              <td>$status</td>
              <td> 
                <a class='btn btn-primary btn-flat btn-xs' href='h3/validasi_picking_list/start?id=$row->no_pl_part'>Start Picking List Open</a>     
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




<script type="text/javascript">
function auto(){
  var id = 1;
  $.ajax({
      url : "<?php echo site_url('h3/validasi_picking_list/cari_id')?>",
      type:"POST",
      data:"id="+id,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#no_validasi_picking_list").val(data[0]);              
      }        
  })
}

function show_detail(){    
  $("#tampil_urgent").show();
  var no_do = 1;
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "no_do="+no_do;                           
     xhr.open("POST", "h3/validasi_picking_list/t_detail", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_urgent").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
</script>