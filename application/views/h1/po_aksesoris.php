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
    <li class="">H1</li>
    <li class="">Kontrol Unit</li>
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
          <a href="h1/po_aksesoris">
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
            <form class="form-horizontal" action="h1/po_aksesoris/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <input type="hidden" name="no_po_aksesoris" id="no_po_aksesoris">
                  <input type="hidden" name="tgl_po" id="tgl_po" value="<?php echo date("Y-m-d") ?>">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Paket Bundling</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_paket_bundling" id="id_paket_bundling">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_paket->result() as $isi) {
                        echo "<option value='$isi->id_paket_bundling'>$isi->id_paket_bundling</option>";
                      }
                      ?>
                    </select>                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Qty Paket Bundling</label>
                  <div class="col-sm-4">
                    <input type="text" name="qty_paket" id="qty_paket" onkeypress="return number_only(event)" class="form-control">                    
                  </div>
                </div>                                                                                                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Keterangan" name="keterangan">
                  </div>
                </div>    
                <div class="form-group">
                  <div class="col-sm-2">
                  </div>
                  <div class="col-sm-4">
                    <button onclick="cari_bundling()" type="button" class="btn btn-flat btn-primary"><i class='fa fa-refresh'></i> Generate</button>
                  </div>
                </div>
                <div class="form-group">
                  <span id="tampil_bundling"></span>                                                                                  
                </div>                                                                                                                                                            
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=='penerimaan'){
      $row = $dt_pemenuhan_po->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/po_aksesoris">
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
            <form class="form-horizontal" action="h1/po_aksesoris/save_penerimaan" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="id" readonly value="<?php echo $row->no_po_aksesoris ?>" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal PO</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_po" id="tgl_po" readonly value="<?php echo $row->tgl_po ?>" class="form-control">                    
                  </div>
                </div>                 
                <div class="form-group">
                  <table id="example" class="table table-bordered table-hover">
                    <thead>
                      <tr>              
                        <th width="5%">No</th>
                        <th>Kode Part</th>              
                        <th>Nama Part</th>
                        <th>QTY On Hand</th>
                        <th>Qty PO</th>
                        <th>Harga</th>      
                        <th>Qty Pemenuhan</th>        
                      </tr>
                    </thead>
                    <tbody>            
                      <?php 
                      $no=1;
                      foreach ($dt_paket->result() as $row) {   
                        $jum = $dt_paket->num_rows();                     
                        echo "
                        <tr>
                          <td>$no</td>
                          <td>$row->id_part</td>
                          <td>$row->nama_part</td>
                          <td>$row->qty</td>                          
                          <td>$row->qty</td>                          
                          <td>".mata_uang2($row->harga_md_dealer)."</td>                          
                          <td>
                            <input type='hidden' name='id_part_$no' value='$row->id_part'>
                            <input type='hidden' name='qty_po_$no' value='$row->qty'>
                            <input type='hidden' name='harga_$no' value='$row->harga_md_dealer'>
                            <input type='hidden' name='jum' value='$jum'>
                            <input type='text' class='form-control isi' name='terima_$no' value='$row->pemenuhan'>
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
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    
    <?php 
    }elseif($set=='detail'){
      $row = $dt_pemenuhan_po->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/po_aksesoris">
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
            <form class="form-horizontal" action="h1/po_aksesoris/save_penerimaan" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="id" readonly value="<?php echo $row->no_po_aksesoris ?>" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal PO</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_po" id="tgl_po" readonly value="<?php echo $row->tgl_po ?>" class="form-control">                    
                  </div>
                </div>                 
                <div class="form-group">
                  <table id="example" class="table table-bordered table-hover">
                    <thead>
                      <tr>              
                        <th width="5%">No</th>
                        <th>Kode Part</th>              
                        <th>Nama Part</th>
                        <th>Qty PO</th>
                        <th>Harga</th>      
                      </tr>
                    </thead>
                    <tbody>            
                      <?php 
                      $no=1;
                      foreach ($dt_paket->result() as $row) {   
                        $jum = $dt_paket->num_rows();                     
                        echo "
                        <tr>
                          <td>$no</td>
                          <td>$row->id_part</td>
                          <td>$row->nama_part</td>
                          <td>$row->qty</td>                          
                          <td>".mata_uang2($row->harga_md_dealer)."</td>                          
                        </tr>
                        ";
                        $no++;
                      }
                      ?>
                    </tbody>
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
          <a href="h1/po_aksesoris/add">
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
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>No.PO</th>              
              <th>Tgl PO</th>              
              <th>Total Harga</th>              
              <th>Status</th>
              <th width="25%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_po_aksesoris->result() as $row) {    
            $approval = $this->m_admin->set_tombol($id_menu,$group,'approval');

            if($row->status_po=='input'){
              $status = "<span class='label label-warning'>$row->status_po</span>";
              $tomb = "<a $approval data-toggle='tooltip' title=\"Approve\" onclick=\"return confirm('Are you sure to approve this data?')\" class=\"btn btn-xs btn-success btn-sm btn-flat\" href=\"h1/po_aksesoris/approve?id=$row->no_po_aksesoris\"><i class=\"fa fa-check\"></i> Approve</a>
                <a $approval data-toggle='tooltip' title=\"Reject\" onclick=\"return confirm('Are you sure to reject this data?')\" class=\"btn btn-danger btn-sm btn-flat btn-xs\" href=\"h1/po_aksesoris/reject?id=$row->no_po_aksesoris\"><i class=\"fa fa-close\"></i> Reject</a>";
            }elseif($row->status_po=='approved' OR $row->status_po == 'diterima'){ 
              $status = "<span class='label label-success'>$row->status_po</span>";
              $tomb = "";
            }elseif($row->status_po=='rejected' OR $row->status_po=='reject H3'){ 
              $status = "<span class='label label-danger'>$row->status_po</span>";
              $tomb = "";
            }elseif($row->status_po=='terpenuhi'){ 
              $status = "<span class='label label-info'>$row->status_po</span>";
              $tomb = "<a data-toggle='tooltip' title=\"Penerimaan\" class=\"btn btn-primary btn-sm btn-flat btn-xs\" href=\"h1/po_aksesoris/penerimaan?id=$row->no_po_aksesoris\"><i class=\"fa fa-list\"></i> Penerimaan</a>";
            }
            $total_harga = $this->db->query("SELECT SUM(qty * harga) as tot FROM tr_po_aksesoris_detail WHERE no_po_aksesoris = '$row->no_po_aksesoris'");
            if($total_harga->num_rows() > 0){
              $to = $total_harga->row();
              $total = $to->tot;
            }else{
              $total = 0;
            }
          echo "          
            <tr>
              <td>$no</td>
              <td><a href='h1/po_aksesoris/detail?id=$row->no_po_aksesoris'>$row->no_po_aksesoris</a></td>
              <td>$row->tgl_po</td>
              <td>".mata_uang2($total)."</td>              
              <td>$status</td>              
              <td>";
              echo $tomb;
              ?>              
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
function auto(){
  var id = 1;
  $.ajax({
      url : "<?php echo site_url('h1/po_aksesoris/cari_id')?>",
      type:"POST",
      data:"id="+id,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#no_po_aksesoris").val(data[0]);              
      }        
  })
}
function cari_bundling(){    
  $("#tampil_bundling").show();
  var id_paket_bundling = document.getElementById("id_paket_bundling").value;   
  var qty_paket = document.getElementById("qty_paket").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_paket_bundling="+id_paket_bundling+"&qty_paket="+qty_paket;                           
     xhr.open("POST", "h1/po_aksesoris/t_bundling", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_bundling").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
</script>