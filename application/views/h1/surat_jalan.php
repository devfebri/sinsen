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
if(isset($_GET['k'])){
?>
  <body onload="kirim_data_sj()">
<?php
}elseif(isset($_GET['s'])){
?>
  <body onload="kirim_data_ksu()">
<?php
}elseif(isset($_GET['id'])){
?>
  <body onload="kirim_data_sj()">    
<?php 
}else{
?>
  <body onload="show_text()">
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
    <li class="">Pengeluaran</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="insert"){
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
          <a href="h1/surat_jalan">
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
            <form class="form-horizontal" action="h1/surat_jalan/save" method="post" enctype="multipart/form-data">
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
                   <!--<label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="no_surat_jalan" placeholder="No Surat Jalan" name="no_surat_jalan">                    
                  </div> -->
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal" value="<?php echo date("Y-m-d") ?>" class="form-control" name="tgl_surat">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat SPPM</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="no_surat_sppm" name="no_surat_sppm">
                      <option>- choose -</option>
                      <?php 
                      $dt = $this->db->query("SELECT * FROM tr_sppm INNER JOIN tr_invoice_dealer ON tr_sppm.no_do = tr_invoice_dealer.no_do
                        WHERE tr_sppm.status = 'input' AND tr_invoice_dealer.status_invoice = 'printable'
                        AND tr_sppm.no_surat_sppm NOT IN (SELECT no_surat_sppm from tr_surat_jalan)");

                      foreach($dt->result() as $val) {
                        echo "
                        <option value='$val->no_surat_sppm'>$val->no_surat_sppm</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-sm-1">                  
                    <button onclick="kirim_data_sj()" type="button" class="btn btn-flat btn-primary btn-sm">Generate</button>
                  </div>                  
                </div>       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Picking List</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="no_pl" readonly placeholder="No Picking List" name="no_pl">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="no_do" readonly placeholder="No DO" name="no_do">                    
                  </div>
                </div>       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="nama_dealer" readonly placeholder="Nama Dealer" name="nama_dealer">
                    <input type="hidden" name="id_dealer" id="id_dealer">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Cara Pengambilan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="cara_ambil">
                      <option>Dijemput</option>
                      <option>Dikirim</option>
                    </select>
                  </div>
                </div>                       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Keterangan" name="ket" id="keterangan">                    
                  </div>              
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
                <div class="form-group">
                  <span id="tampil_sj"></span>                                                                                  
                </div>  
                
                <div class="panel-ev" style="display: none;">
                  <button disabled="disabled" class="btn btn-block btn-primary btn-flat"> KELENGKAPAN EV </button>

                  <table class="table myTable1 table-bordered table-hover data-table-scan">
                    <thead>
                      <tr>
                        <th width="5px">No</th>
                        <th width="30%">Serial Number</th>
                        <th width="20%">Tipe</th>
                        <th width="20%">Kode Part</th>
                        <th width="20%">Nama Part </th>
                        <th width="5px">Action </th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>                                                                                  
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



<script>
      function show_scan_ev(no_surat_sppm){
        $(".panel-ev").hide();
        var no_pl  = $("#no_pl").val();  
          var i = 1;
          $.ajax({
              url:"<?php echo site_url('h1/surat_jalan/battery_stok');?>",
              type:"POST",
              data: {
                id:no_surat_sppm,
                no_pl:no_pl,
              },
              cache:false,
              success: function(response) {              
                if(response.status == 1){
                    $(".panel-ev").show();  
                    var tableBody = $('.data-table-scan tbody');
                    tableBody.empty();
                    $.each(response.data, function(index, item){
                    var row = $('<tr></tr>');
                    row.append('<td><b>' + i++ + '</b></td>');
                    row.append('<td><input type="text" class="hidden-input" name="oem[serial_number][]" value="' + item.serial_number + '" readonly></td>');
                    row.append('<td >B</td>');
                    row.append('<td><input type="text" class="hidden-input" name="oem[part][]" value="' + item.part_id + '" readonly></td><input type="hidden" class="hidden-input" name="oem[no_picking_list_battery][]" value="' + item.no_picking_list_battery + '" readonly>');
                    row.append('<td >' + item.part_desc + '</td>');
                    row.append('<td ><input type="checkbox" name="oem[konfirmasi][]" class="form-check-input"></td>');
                    tableBody.append(row);
                   $(".panel-scan-barcode").show();
                  });
                }else{
                  $(".panel-ev").hide();  
                }

                }
          });
        }
    </script>


    <?php 
    }elseif($set=="edit"){
      $row = $dt_sj->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/surat_jalan">
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
            <form class="form-horizontal" action="h1/surat_jalan/update" method="post" enctype="multipart/form-data">
              <div class="box-body">       
                <?php 
                if(isset($_GET["k"])){
                  $k = "konfirm";
                }else{
                  $k = "";
                }
                ?>
                <input id="k" value="<?php echo $k ?>" type="hidden">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="no_surat_jalan" value="<?php echo $row->no_surat_jalan ?>" readonly placeholder="No Surat Jalan" name="no_surat_jalan">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal" value="<?php echo $row->tgl_surat ?>" class="form-control" name="tgl_surat">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat SPPM</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="no_surat_sppm" value="<?php echo $row->no_surat_sppm ?>" readonly placeholder="No Surst SPPM" name="no_surat_sppm">                    
                  </div>                  
                </div>       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Picking List</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="no_pl" readonly value="<?php echo $row->no_picking_list?>" placeholder="No Picking List" name="no_pl">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="no_do" readonly value="<?php echo $row->no_do ?>" placeholder="No DO" name="no_do">                    
                  </div>
                </div>       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="nama_dealer" value="<?php echo $row->nama_dealer ?>" readonly placeholder="Nama Dealer" name="nama_dealer">
                    <input type="hidden" name="id_dealer" id="id_dealer">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Cara Pengambilan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="cara_ambil">
                      <option><?php echo $row->cara_ambil ?></option>
                      <option>Dijemput</option>
                      <option>Dikirim</option>
                    </select>
                  </div>
                </div>                       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?php echo $row->ket ?>" placeholder="Keterangan" name="ket">                    
                  </div>                  
                </div>                       
                
                
                <hr>                
                <div class="form-group">
                                    
                  
                  <span id="tampil_sj"></span>                                                                                  
                  
                  
                </div>                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <?php 
                  $cek = $this->db->query("SELECT * FROM tr_surat_jalan_detail WHERE no_surat_jalan = '$row->no_surat_jalan' AND status_nosin = 'waiting'");
                  if($cek->num_rows() == 0){
                  ?>
                    <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
                  <?php
                  }else{
                  ?>
                    <a href="h1/surat_jalan/approval?id=<?php echo $row->no_surat_jalan ?>">
                      <button type="button" name="approve" value="approve" class="btn btn-warning btn-flat"><i class="fa fa-check"></i> Set Approval</button>                    
                    </a>
                  <?php } ?>
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
      $row = $dt_sj->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/surat_jalan/cek_history">
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
            <form class="form-horizontal" action="h1/surat_jalan/update" method="post" enctype="multipart/form-data">
              <div class="box-body">       
                <?php 
                if(isset($_GET["k"])){
                  $k = "konfirm";
                }else{
                  $k = "";
                }
                ?>
                <input id="k" value="<?php echo $k ?>" type="hidden">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="no_surat_jalan" value="<?php echo $row->no_surat_jalan ?>" readonly placeholder="No Surat Jalan" name="no_surat_jalan">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal" readonly value="<?php echo $row->tgl_surat ?>" class="form-control" name="tgl_surat">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat SPPM</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="no_surat_sppm" value="<?php echo $row->no_surat_sppm ?>" readonly placeholder="No Surst SPPM" name="no_surat_sppm">                    
                  </div>                  
                </div>       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Picking List</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="no_pl" readonly value="<?php echo $row->no_picking_list?>" placeholder="No Picking List" name="no_pl">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="no_do" readonly value="<?php echo $row->no_do ?>" placeholder="No DO" name="no_do">                    
                  </div>
                </div>       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="nama_dealer" value="<?php echo $row->nama_dealer ?>" readonly placeholder="Nama Dealer" name="nama_dealer">
                    <input type="hidden" name="id_dealer" id="id_dealer">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Cara Pengambilan</label>
                  <div class="col-sm-4">
                    <select class="form-control" readonly name="cara_ambil">
                      <option><?php echo $row->cara_ambil ?></option>
                      <option>Dijemput</option>
                      <option>Dikirim</option>
                    </select>
                  </div>
                </div>                       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" readonly value="<?php echo $row->ket ?>" placeholder="Keterangan" name="ket">                    
                  </div>                  
                </div>                       
                
                
                <hr>                
                <div class="form-group">
                                    
                <button class="btn btn-block btn-primary btn-flat" disabled>DETAIL KENDARAAN</button>                  
                <table id="example2" class="table myTable1 table-bordered table-hover">
                  <thead>
                    <tr>            
                      <th width="1%">No</th>            
                      <th width="10%">No Mesin</th>
                      <th width="20%">Tipe</th>
                      <th width="10%">Warna</th>                                
                    </tr>    
                  </thead>
                  <tbody>
                  <?php 
                  $no=1;    
                  $sj = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan=tr_surat_jalan_detail.no_surat_jalan
                              WHERE tr_surat_jalan.no_surat_jalan = '$row->no_surat_jalan' AND tr_surat_jalan_detail.ceklist = 'ya'");
                  foreach ($sj->result() as $isi) {        
                    $item = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
                                INNER JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna
                                WHERE ms_item.id_item = '$isi->id_item'")->row();
                    
                    if($isi->pengganti != "" AND $isi->status_nosin == 'waiting'){
                      $no_mesin = $isi->pengganti;
                      $status = "<span class='label label-warning'>need approval</span>";              
                    }else{
                      $no_mesin = $isi->no_mesin;
                      $status = "";
                    }

                    echo "
                    <tr>          
                      <th width='1%'>$no</th>              
                      <td width='10%'>$no_mesin $status</td>        
                      <td width='20%'>$item->tipe_ahm ($item->id_tipe_kendaraan)</td>
                      <td width='10%'>$item->warna ($item->id_warna)</td>                            
                    </tr>
                    ";  
                    $no++;
                  }
                  ?>
                  </tbody> 
                </table>

                <button class="btn btn-block btn-primary btn-flat" disabled>DETAIL KSU</button>                  
                <table id="example2" class="table myTable1 table-bordered table-hover">
                  <thead>
                    <tr>                  
                      <th width="10%">Kode Item</th>            
                      <th width="10%">Qty SPPM</th>                  
                      <th width="10%">Kode KSU/Qty Supply</th>
                    </tr>    
                  </thead>
                  <tbody>
                  <?php 
                  $no=1;    
                  $x=0;$xx=0;
                  $dt_sj = $this->db->query("SELECT * FROM tr_surat_jalan_ksu INNER JOIN tr_surat_jalan ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_ksu.no_surat_jalan
                    WHERE tr_surat_jalan.no_surat_jalan = '$row->no_surat_jalan'");
                  foreach ($dt_sj->result() as $isi) {            
                    $item = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
                              INNER JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna
                              WHERE ms_item.id_item = '$isi->id_item'");
                    if ($item->num_rows()>0) {
                      $item= $item->row();
                      $id_item = $item->id_item;
                      $id_tipe_kendaraan = $item->id_tipe_kendaraan;
                      $tipe_ahm = $item->tipe_ahm;
                    }                
                    $cek = $this->db->query("SELECT * FROM tr_sppm INNER JOIN tr_sppm_detail ON tr_sppm.no_surat_sppm = tr_sppm_detail.no_surat_sppm
                        WHERE tr_sppm.no_surat_sppm = '$isi->no_surat_sppm' AND tr_sppm_detail.id_item = '$id_item'");
                    if($cek->num_rows() > 0){
                      $t = $cek->row();
                      $qty_sppm = $t->qty_ambil;
                    }else{
                      $qty_sppm = 0;
                    }
                    echo "
                    <tr>               
                      <td width='20%'>$tipe_ahm ($id_tipe_kendaraan) - $id_item</td>
                      <td width='10%'><span id='qty_sppm_$no'>$qty_sppm</span></td>
                      <td width='20%'>";
                      $cek = $this->db->query("SELECT ms_koneksi_ksu_detail.id_ksu FROM ms_koneksi_ksu_detail INNER JOIN ms_koneksi_ksu ON ms_koneksi_ksu.id_koneksi_ksu = ms_koneksi_ksu_detail.id_koneksi_ksu 
                            INNER JOIN ms_ksu ON ms_ksu.id_ksu = ms_koneksi_ksu_detail.id_ksu WHERE ms_koneksi_ksu.id_tipe_kendaraan = '$id_tipe_kendaraan'
                            ORDER BY ms_ksu.ksu ASC");
                      if(count($cek) > 0){
                        $amb = $cek->row();
                        
                        foreach ($cek->result() as $key) {                    
                          $cek2 = $this->db->query("SELECT id_ksu,ksu FROM ms_ksu WHERE id_ksu = '$key->id_ksu'");
                          if(count($cek2) > 0){
                            $rd = $cek2->row();
                            $rty = $this->db->query("SELECT * FROM tr_surat_jalan_ksu WHERE id_ksu = '$rd->id_ksu' AND no_do = '$isi->no_do' AND id_item = '$id_item'");
                            if($rty->num_rows() == 0){
                              echo "
                                 <div class='input-group'>
                                  <span class='input-group-addon bg-maroon'>$rd->ksu</span>                         
                                  <input type='hidden' name='isian' value='insert'>
                                  <input type='hidden' name='id_item_add_$xx' value='$id_item'>                    
                                  <input type='hidden' name='no_do_$xx' value='$isi->no_do'>                    
                                  <input type='hidden' name='qty_do_add_$xx' value='$isi->qty_do'>                    
                                  <input type='hidden' name='id_ksu_add_$xx' value='$rd->id_ksu'>                    
                                  <input type='text' onkeypress='return number_only(event)' readonly onkeyup='cekXX($xx,$no)' onchange='cekXX($xx,$no)' onkeydown='getXX($xx,$no)' value='0' name='qty_add_$xx' class='input-group-addon input-block' style='width:50px;'>
                                  <input type='hidden' name='xx' value='$xx'>
                                </div>";   
                                $xx++;                     
                            }else{
                              $ui = $rty->row();
                              echo "
                                 <div class='input-group'>
                                  <span class='input-group-addon bg-maroon'>$rd->ksu</span>                         
                                  <input type='hidden' name='isian' value='update'>
                                  <input type='hidden' name='id_surat_jalan_ksu_$x' value='$ui->id_surat_jalan_ksu'>
                                  <input type='hidden' name='id_ksu_$x' value='$rd->id_ksu'>
                                  <input type='hidden' name='x' value='$x'>
                                  <input type='hidden' name='id_item_$x' value='$id_item'>                    
                                  <input type='hidden' name='qty_do_$x' value='$isi->qty_do'>                    
                                  <input type='hidden' name='no_do_$x' value='$isi->no_do'>
                                  <input type='text' onkeypress='return number_only(event)' readonly onkeydown='getX($x,$no)' onkeyup='cekX($x,$no)' onchange='cekX($x,$no)'  value='$ui->qty' name='qty_$x' id='qty_$x' class='input-group-addon input-block' style='width:50px;'>
                                </div>";     
                               $x++;     ;               
                            }
                          }
                        }   

                      }
                      echo "
                      </td>              
                      "; ?>           
                    </tr>
                    <?php 
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
                  <?php 
                  $cek = $this->db->query("SELECT * FROM tr_surat_jalan_detail WHERE no_surat_jalan = '$row->no_surat_jalan' AND status_nosin = 'waiting'");
                  if($cek->num_rows() == 0){
                  ?>
                    <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
                  <?php
                  }else{
                  ?>
                    <a href="h1/surat_jalan/approval?id=<?php echo $row->no_surat_jalan ?>">
                      <button type="button" name="approve" value="approve" class="btn btn-warning btn-flat"><i class="fa fa-check"></i> Set Approval</button>                    
                    </a>
                  <?php } ?>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <?php 
    }elseif($set=="penerimaan_dealer"){
      $row = $row->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title"><br>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal">
              <div class="box-body">                          
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Penerimaan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly value="<?= $row->id_penerimaan_unit_dealer ?>" name="ket">                    
                  </div>  
                  <label for="inputEmail3" class="col-sm-2 control-label">No SJ</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly value="<?= $row->no_surat_jalan ?>" name="ket">                    
                  </div>                  
                </div>                       
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Penerimaan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly value="<?= $row->tgl_penerimaan ?>" name="ket">                    
                  </div>  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl SJ</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly value="<?= $row->tgl_surat_jalan ?>" name="ket">                    
                  </div>                  
                </div> 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly value="<?= $row->nama_dealer ?>" name="ket">                    
                  </div>  
                  <label for="inputEmail3" class="col-sm-2 control-label">Terima Di Gudang</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly value="<?= $row->id_gudang_dealer ?>" name="ket">                    
                  </div>                  
                </div> 
                <div class="form-group">
                  <div class="col-md-12">
                    <button style="width: 100%;font-size: 12pt" class="btn btn-primary btn-flat" disabled><b>Detail nomesin yang tidak diterima Dealer namun ada di Surat Jalan</b></button>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-12">
                    <table class="table table-bordered table-condensed table-striped table-hover">
                      <thead>
                        <th>No</th>
                        <th>No Mesin</th>
                        <th>No Rangka</th>
                        <th>Tipe</th>
                        <th>Warna</th>
                      </thead>
                      <tbody>
                        <?php foreach ($tidak_diterima->result() as $key=>$rs): ?>
                          <tr>
                            <td><?= $key+1 ?></td>
                            <td><?= $rs->no_mesin ?></td>
                            <td><?= $rs->no_rangka ?></td>
                            <td><?= $rs->id_tipe_kendaraan.'|'.$rs->tipe_ahm ?></td>
                            <td><?= $rs->id_warna.'|'.$rs->warna ?></td>
                          </tr>
                        <?php endforeach ?>
                        <tr>
                          <td colspan="4" align="right"><b>Total</b></td>
                          <td align="center"><b><?= $tidak_diterima->num_rows() ?></b></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                 <div class="form-group">
                  <div class="col-md-12">
                    <button style="width: 100%;font-size: 12pt" class="btn btn-primary btn-flat" disabled><b>Detail nomesin NRFS</b></button>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-12">
                    <table class="table table-bordered table-condensed table-striped table-hover">
                      <thead>
                        <th>No</th>
                        <th>No Mesin</th>
                        <th>No Rangka</th>
                        <th>Tipe</th>
                        <th>Warna</th>
                      </thead>
                      <tbody>
                        <?php foreach ($detail->result() as $key=>$rs):  
                          $sb = $this->db->get_where('tr_scan_barcode',['no_mesin'=>$rs->no_mesin])->row();
                          $tipe = $this->db->get_where('ms_tipe_kendaraan',['id_tipe_kendaraan'=>$sb->tipe_motor])->row();
                          $wrn = $this->db->get_where('ms_warna',['id_warna'=>$sb->warna])->row();
                        ?>
                          <tr>
                            <td><?= $key+1 ?></td>
                            <td><?= $rs->no_mesin ?></td>
                            <td><?= $sb->no_rangka ?></td>
                            <td><?= $tipe->id_tipe_kendaraan.' | '.$tipe->tipe_ahm ?></td>
                            <td><?= $wrn->id_warna.' | '.$wrn->warna ?></td>
                          </tr>
                        <?php endforeach ?>
                        <tr>
                          <td colspan="4" align="right"><b>Total</b></td>
                          <td align="center"><b><?= $detail->num_rows() ?></b></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>



              </div>

            
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="edit_nosin"){
      $row = $dt_sj->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/surat_jalan/edit?k=konfirm&id=<?php echo $no_sj ?>">
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
            <form class="form-horizontal" action="h1/surat_jalan/update" method="post" enctype="multipart/form-data">
              <div class="box-body">                       
                <div class="form-group">
                  <input type="hidden" id="no_sj" name="no_sj" value="<?php echo $no_sj ?>">
                  <input type="hidden" id="id" name="id" value="<?php echo $_GET['id'] ?>">
                  <label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="no_do" id="no_do">
                      <option value=''>- choose -</option>
                      <?php
                      $no_do = $row->no_do;
                      $ambil = $this->db->query("SELECT * FROM tr_picking_list WHERE status = 'proses' AND no_do != '$no_do'");
                      foreach ($ambil->result() as $isi) {
                        echo "                        
                        <option value='$isi->no_do'>$isi->no_do</option>
                        ";
                      }
                      ?>                      
                    </select>
                  </div>               
                  <div class="col-sm-1">                  
                    <button onclick="kirim_data_nosin()" type="button" class="btn btn-flat btn-primary btn-sm">Generate</button>
                  </div>   
                </div>                
                <hr>                
                  <span id="tampil_nosin"></span>                                                                                  
              </div><!-- /.box-body -->              
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="approval"){
      $row = $dt_sj->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/surat_jalan/edit?k=konfirm&id=<?php echo $no_sj ?>">
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
            <form class="form-horizontal" action="h1/surat_jalan/save_approval" method="post" enctype="multipart/form-data">
              <div class="box-body">                       
                <div class="form-group">
                  <input type="hidden" id="no_sj" name="no_sj" value="<?php echo $no_sj ?>">                  
                </div>             

                <table id="example2" class="table myTable1 table-bordered table-hover">
                  <thead>
                    <tr>            
                      <th width="1%">No</th>            
                      <th width="20%">No Mesin Awal</th>
                      <th width="10%">Tipe</th>
                      <th width="10%">Warna</th>            
                      <th width="20%">No Mesin Pengganti</th>
                      <th width="10%">No DO</th>                                  
                      <th width="10%">Tipe</th>
                      <th width="10%">Warna</th>            
                      <th width="1%">Checklist</th>           
                    </tr>    
                  </thead>
                  <tbody>
                    <?php 
                    $no=1;
                    foreach ($dt_sj->result() as $isi) {
                      $item = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
                          INNER JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna
                          WHERE ms_item.id_item = '$isi->id_item'")->row();

                      $scan = $this->db->query("SELECT tr_scan_barcode.id_item,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
                          INNER JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna INNER JOIN tr_scan_barcode ON ms_item.id_item=tr_scan_barcode.id_item
                          WHERE tr_scan_barcode.no_mesin = '$isi->pengganti'")->row();
                      echo "
                        <td>$no</td>
                        <td>$isi->no_mesin</td>
                        <td>$item->tipe_ahm</td>
                        <td>$item->warna</td>
                        <td>$isi->pengganti</td>
                        <td>$isi->no_do</td>
                        <td>$scan->tipe_ahm</td>
                        <td>$scan->warna</td>
                        <td align='center'>
                          <input type='hidden' value='$isi->id_surat_jalan_detail' name='id_surat_jalan_detail[]'>        
                          <input type='hidden' value='$isi->no_mesin' name='no_mesin[]'>        
                          <input type='hidden' value='$isi->pengganti' name='pengganti[]'>        
                          <input type='checkbox' name='check_nosin[]'>
                        </td>
                      ";
                      $no++;
                    }
                    ?>
                  </tbody>
                </table>
                
                <div class="box-footer">
                  <div class="col-sm-2">
                  </div>
                  <div class="col-sm-10">
                    <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                    <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                  </div>
                </div><!-- /.box-footer -->

              </div><!-- /.box-body -->              
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="ksu"){
      $row = $dt_sj->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/surat_jalan">
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
            <form class="form-horizontal" action="h1/surat_jalan/save_ksu" method="post" enctype="multipart/form-data">
              <div class="box-body">       
                <?php 
                if(isset($_GET["k"])){
                  $k = "konfirm";
                }else{
                  $k = "";
                }
                ?>
                <input id="k" value="<?php echo $k ?>" type="hidden">
                <input id="no_sj" name="no_sj" value="<?php echo $_GET['id'] ?>" type="hidden">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Picking List</label>
                  <div class="col-sm-4">
                    <input type="hidden" id="no_surat_sppm" value="<?php echo $row->no_surat_sppm ?>">                    
                    <input type="text" required class="form-control" id="no_pl" value="<?php echo $row->no_picking_list ?>" readonly placeholder="No Picking List" name="no_picking_list">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Picking List</label>
                  <div class="col-sm-4">
                    <input type="text" readonly value="<?php echo $row->tgl_pl ?>" class="form-control" name="tgl_pl">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Do</label>
                  <div class="col-sm-4">
                    <!-- <input type="text" class="form-control" id="no_do" value="<?php echo $row->no_do ?>" readonly placeholder="No DO" name="no_do_a">   -->
                    <input type="text" class="form-control" id="no_do" value="<?php echo $row->no_do ?>" readonly placeholder="No DO" name="no_do">                    

                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Do</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tgl_do" value="<?php echo $row->tgl_do ?>" readonly placeholder="Tgl DO" name="tgl_do">                    
                  </div>                  
                </div>       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="gudang" readonly value="<?php echo $row->gudang?>" placeholder="Gudang" name="gudang">                    
                  </div>                  
                </div>       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="kode_dealer" value="<?php echo $row->kode_dealer_md ?>" readonly placeholder="Kode Dealer" name="kode_dealer">
                    <input type="hidden" name="id_dealer" id="id_dealer">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="nama_dealer" value="<?php echo $row->nama_dealer ?>" readonly placeholder="Nama Dealer" name="nama_dealer">                    
                  </div>
                </div>                       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan DO</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" readonly value="<?php echo $row->ket ?>" placeholder="Keterangan" name="ket">                    
                  </div>                  
                </div>                       
                
                
                <hr>                
                <div class="form-group">
                                    
                  
                  <span id="tampil_ksu"></span>                                                                                  
                  
                  
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
          <a href="h1/surat_jalan/add">            
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>  
          <a href="h1/surat_jalan/cek_history">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-check"></i> Cek History SJ</button>
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
              <th>No Picking List</th> 
              <th>Tanggal Picking List</th>             
              <th>No Surat Jalan</th>
              <th>Tgl Surat Jalan</th>
              <th>No.Do</th>              
              <th>Nama Dealer</th>
              <th>Status</th>
              <th width="15%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_sj->result() as $row) {  
            $cari = $this->db->query("SELECT tgl_pl,no_do FROM tr_picking_list WHERE no_picking_list = '$row->no_picking_list'");
            if($cari->num_rows() > 0){
              $t = $cari->row();
              $tgl = $t->tgl_pl;
              $no_do = $t->no_do;
            }else{
              $tgl = "";
              $no_do = "";
            }

            // $print = $this->m_admin->set_tombol($id_menu,$group,'print');
            $print='';

            if($row->status=='input'){
              $status = "<span class='label label-danger'>Input</span>";            
             // $t1 = "<a data-toggle=\"tooltip\" title=\"Edit\" class=\"btn btn-primary btn-sm btn-flat\" href=\"h1/surat_jalan/edit?k=konfirm&id=$row->no_surat_jalan\"><i class=\"fa fa-edit\"></i></a>";
              $t1='';
              $t2 = "<a data-toggle=\"tooltip\" title=\"Cetak SL\" $print onclick=\"return confirm('Are you sure to print this data?')\" class=\"btn btn-warning btn-sm btn-flat\" href=\"h1/surat_jalan/cetak?id=$row->no_surat_jalan\"><i class=\"fa fa-print\"></i></a>";
              $t3 = "<a data-toggle=\"tooltip\" title=\"PL KSU\" class=\"btn btn-success btn-sm btn-flat\" href=\"h1/surat_jalan/ksu?s=konfirm&id=$row->no_surat_jalan\"><i class=\"fa fa-download\"></i></a>";              
            }elseif($row->status=='proses'){
              $status = "<span class='label label-primary'>Proses</span>";
              $t1 = "";
              $t2 = "<a data-toggle=\"tooltip\" title=\"Cetak SL\" $print onclick=\"return confirm('Are you sure to print this data?')\" class=\"btn btn-warning btn-sm btn-flat\" href=\"h1/surat_jalan/cetak?id=$row->no_surat_jalan\"><i class=\"fa fa-print\"></i></a>";
              $t3 = "<a data-toggle=\"tooltip\" title=\"PL KSU\" class=\"btn btn-success btn-sm btn-flat\" href=\"h1/surat_jalan/ksu?s=konfirm&id=$row->no_surat_jalan\"><i class=\"fa fa-download\"></i></a>";              
            }elseif($row->status=='close'){
              $status = "<span class='label label-success'>Close</span>";
              $t1 = "";
              $t3 = "";
              $t2 = "";
            }

            $cek = $this->db->query("SELECT no_surat_jalan FROM tr_surat_jalan_ksu WHERE no_surat_jalan = '$row->no_surat_jalan'");
            if($cek->num_rows() == 0){
              $t2 = "";
            }

            $link = " href='h1/picking_list/detail?id=$row->no_picking_list'";

              echo "          
            <tr>
              <td>$no</td>
              <td><a title='View Data'". $link.">".$row->no_picking_list."</a></td>
              <td>$tgl</td>
              <td>$row->no_surat_jalan</td>
              <td>$row->tgl_surat</td>
              <td>$no_do</td>
              <td>$row->nama_dealer</td>
              <td>$status</td>
              <td>";
              echo $t1;
              echo $t2;
              echo $t3;
              $no++;              
            
              ?>
                
              </td>
            </tr>
          <?php
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }elseif($set=="view_new"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/surat_jalan/add">            
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>  
          <a href="h1/surat_jalan/cek_history">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-check"></i> Cek History SJ</button>
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
        <table id="examplesj"  cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>No Picking List</th> 
              <th>Tanggal Picking List</th>             
              <th>No Surat Jalan</th>
              <th>Tgl Surat Jalan</th>
              <th>No.Do</th>              
              <th>Nama Dealer</th>
              <th>Status</th>
              <th width="15%">Action</th>
            </tr>
          </thead>      
          <tbody>            
          </tbody>    
        </table>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

        <script>
          $( document ).ready(function() {
          tablesss = $('#examplesj').DataTable({
                "scrollX": true,
                "processing": true, 
                "bDestroy": true,
                "serverSide": true, 
                "order": [],
                "ajax": {
                  "url": "<?php  echo site_url('h1/surat_jalan/fetch_data_datatables')?>",
                    "type": "POST"
                },  
                      
                "columnDefs": [
                {
                    "targets": [ 0,6 ],
                    "orderable": false, 
                },
                ],
                });
        });
        </script>


  <?php
    }elseif($set=="history"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/surat_jalan">            
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
        <table id="table_cek_history" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>No Picking List</th> 
              <th>Tanggal Picking List</th>             
              <th>No Surat Jalan</th>
              <th>Tgl Surat Jalan</th>
              <th>No.Do</th>              
              <th>Nama Dealer</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          /*
          $no=1; 
          foreach($dt_sj->result() as $row) {  
            $cari = $this->db->query("SELECT * FROM tr_picking_list WHERE no_picking_list = '$row->no_picking_list'");
            if($cari->num_rows() > 0){
              $t = $cari->row();
              $tgl = $t->tgl_pl;
              $no_do = $t->no_do;
            }else{
              $tgl = "";
              $no_do = "";
            }
            $print = $this->m_admin->set_tombol($id_menu,$group,'print');
            
            echo "          
            <tr>
              <td>$no</td>
              <td>$row->no_picking_list</td>
              <td>$tgl</td>
              <td>
                <a href='h1/surat_jalan/detail?id=$row->no_surat_jalan'>
                  $row->no_surat_jalan
                </a>
              </td>
              <td>$row->tgl_surat</td>
              <td>$no_do</td>
              <td>$row->nama_dealer</td>        
              <td>
                <a data-toggle=\"tooltip\" title=\"Cetak SJ\" $print onclick=\"return confirm('Are you sure to print this data?')\" class=\"btn btn-warning btn-sm btn-flat\" href=\"h1/surat_jalan/cetak_ulang?id=$row->no_surat_jalan\"><i class=\"fa fa-print\"></i></a>
              </td>                    
            </tr>";          
          $no++;
          }*/
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <script>
      $( document ).ready(function() {
      tabless = $('#table_cek_history').DataTable({
            "scrollX": true,
            "processing": true, 
            "bDestroy": true,
            "serverSide": true, 
            "order": [],
            "ajax": {
              "url": "<?php  echo site_url('h1/surat_jalan/fetch_data_cek_history_datatables')?>",
                "type": "POST"
            },  
                  
            "columnDefs": [
            {
                "targets": [ 0,5 ],
                "orderable": false, 
            },
            ],
            });
    });
    </script>

    <?php
    }
    ?>
  </section>
</div>


<script type="text/javascript">
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
function simpan_scan(){
  var scan_nosin = document.getElementById("scan_nosin").value;
  var no_do = document.getElementById("no_do").value;
  //alert(scan_nosin);
  $.ajax({
      url : "<?php echo site_url('h1/surat_jalan/save_nosin')?>",
      type:"POST",
      data:"scan_nosin="+scan_nosin+"&no_do="+no_do,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");                
        kirim_data_sj();     
        $("#scan_nosin").val('');
        $("#scan_nosin").focus();
      }        
  })
}
</script>
<script type="text/javascript">
var scan_nosin = document.getElementById("scan_nosin");
scan_nosin.addEventListener("keydown", function (e) {
    //if(scan_nosin.length >= 12) {  //checks whether the pressed key is "Enter"
    if(e.keyCode === 13) {  //checks whether the pressed key is "Enter"
      //alert(scan_nosin);
      simpan_scan();
    }
});
</script>
<script>
// document.getElementById("scan_nosin").addEventListener("keypress", myFunction);
// function myFunction() {
//   var x = document.getElementById("scan_nosin").value;
//   if(x.length >= 12){
//     //alert(x);
//     simpan_scan();
//   }
// }
$('form input').on('keypress', function(e) {
    return e.which !== 13;
});
</script>
<script type="text/javascript">
function kirim_data_sj(){   
  
  $("#tampil_sj").show();
  cari_lain();
  var no_surat_sppm = document.getElementById("no_surat_sppm").value;
  var k     = document.getElementById("k").value;

  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "no_pl="+birthday1_js;          
    var data = "no_surat_sppm="+no_surat_sppm+"&k="+k;                           
     xhr.open("POST", "h1/surat_jalan/t_sj", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_sj").innerHTML = xhr.responseText;
                show_scan_ev(no_surat_sppm);
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
    // $(".panel-ev").show();
}


function cari_lain(){
  var no_surat_sppm  = $("#no_surat_sppm").val();                         
  $.ajax({
      url: "<?php echo site_url('h1/surat_jalan/cari_lain')?>",
      type:"POST",
      data:"no_surat_sppm="+no_surat_sppm,
      cache:false,
      success:function(msg){                
          data=msg.split("|");
          if(data[0]=="ok"){          
            $("#no_pl").val(data[1]);                
            $("#no_do").val(data[2]);                            
            $("#nama_dealer").val(data[3]);                            
            $("#id_dealer").val(data[4]);                            
          }else{
            alert(data[0]);
          }
      } 
  })
}
function kirim_data_nosin(){    
  $("#tampil_nosin").show();
  var no_do = document.getElementById("no_do").value;  
  var no_sj = document.getElementById("no_sj").value;  
  var id    = document.getElementById("id").value;  

  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "no_pl="+birthday1_js;          
    var data = "no_do="+no_do+"&no_sj="+no_sj+"&id="+id;                           
     xhr.open("POST", "h1/surat_jalan/t_sj_nosin", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_nosin").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function kirim_data_ksu(){    
  $("#tampil_ksu").show();
  var no_pl          = document.getElementById("no_pl").value;  
  var no_surat_jalan = document.getElementById("no_sj").value;    
  // alert(no_pl);
  // alert(no_surat_jalan);
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "no_pl="+birthday1_js;          
    var data = "no_pl="+no_pl+"&no_surat_jalan="+no_surat_jalan;
     xhr.open("POST", "h1/surat_jalan/t_sj_ksu", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_ksu").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
</script>

    <script>
      function getX(a,b){
        return parseInt($("#qty_"+a).val());

      }
      function getXX(a,b){
        return parseInt($("#qty_add"+a).val());

      }
      function cekXX(a,b)
      {
        var qty_sppm = parseInt($("#qty_sppm_"+b).text());
        var qty_add = parseInt($("#qty_add"+a).val());
        if (qty_add > qty_sppm) {
          alert('Jumlah yang dimasukkan melebihi Qty SPPM');
          $("#qty_add"+a).val(0);
        }
      }

      function cekX(a,b)
      {
        var qty_sppm = parseInt($("#qty_sppm_"+b).text());
        var qty = parseInt($("#qty_"+a).val());
        // alert(a);
        if (qty > qty_sppm) {
          alert('Jumlah yang dimasukkan melebihi Qty SPPM');
          $("#qty_"+a).val(0);
        }
      }
    </script>

<!-- <script type="text/javascript" language="javascript" >
  $(document).ready(function() {
    var dataTable = $('#example2').DataTable( {
      "processing": true,
      "serverSide": true,
      "orderable": false,
      "ajax":{
        url: "<?php echo site_url('dashboard/ajax')?>",
        type: "post",  // method  , by default get
        error: function(){  // error handling          
          $(".employee-grid-error").html("");
          $("#example2").append('<tbody><tr><th colspan="3">No data found in the server</th></tr></tbody>');                    
          $("#employee-grid_processing").css("display","none");
        }
      }
    } );
  } );
</script>         -->