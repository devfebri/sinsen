<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 30px;  
  padding-left: 5px;
  padding-right: 5px;  
  margin-right: 0px; 
}
.isi_combo{   
  height: 30px;
  border:1px solid #ccc;
  padding-left:1.5px;
}
</style>
<base href="<?php echo base_url(); ?>" />
<?php 
if(isset($_GET['id'])){
?>
<body onload="kirim_data()">
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
    <li class="">Retur Unit</li>
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
          <a href="h1/retur_unit">
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
            <form class="form-horizontal" action="h1/retur_unit/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Retur Unit</label>
                  <div class="col-sm-3">
                    <input type="text" name="periode_awal" placeholder="No Retur Unit" readonly class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" placeholder="Nama Dealer" readonly class="form-control">
                  </div>              
                  <div class="col-sm-1">
                    <button type='button' class="btn btn-primary btn-flat">browse</button>
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
    }elseif($set=='detail'){
      $row = $dt_retur->row();
    ?>

     <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/retur_unit">
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
            <form class="form-horizontal" action="h1/retur_unit/save_approval" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Retur Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $row->no_retur_dealer ?>" placeholder="No Retur Dealer" name="no_retur_d" id="no_retur_d" readonly>
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Retur</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control"  placeholder="Tgl Retur" name="tgl_retur" id="tanggal" value="<?php echo $row->tgl_retur ?>">
                  </div>                  
                </div>                
                <div class="form-group">
                  <button class="btn btn-primary btn-block btn-flat" disabled>Detail Unit</button>
                  <table id="" class="table table-bordered table-hover">
                    <thead>
                      <tr>                    
                        <th width="15%">No Mesin</th>              
                        <th width="15%">No Rangka</th>              
                        <th width="10%">Kode Item</th>              
                        <th width="15%">Tipe</th>              
                        <th width="10%">Warna</th>
                        <th width="10%">Tahun Produksi</th>                            
                        <th width="10%">Tgl Penerimaan</th>
                        <th width="15%">Keterangan</th>              
                      </tr>
                    </thead>
                  
                    <?php   
                    $dt_data = $this->db->query("SELECT tr_scan_barcode.no_rangka,tr_scan_barcode.no_mesin,tr_scan_barcode.id_item,tr_fkb.tahun_produksi, ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_retur_dealer_detail.* FROM tr_retur_dealer_detail
                              LEFT JOIN tr_scan_barcode ON tr_retur_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                              LEFT JOIN tr_fkb ON tr_scan_barcode.no_mesin = tr_fkb.no_mesin_spasi
                              INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                              INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                              WHERE tr_retur_dealer_detail.no_retur_dealer = '$row->no_retur_dealer'");                    
                    foreach($dt_data->result() as $row) {               
                      $tgl = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
                              WHERE tr_penerimaan_unit_dealer_detail.no_mesin = '$row->no_mesin'")->row()->tgl_penerimaan;
                      echo "   
                      <tr>                    
                        <td width='15%'>$row->no_mesin</td>
                        <td width='15%'>$row->no_rangka</td>      
                        <td width='10%'>$row->id_item</td>            
                        <td width='15%'>$row->tipe_ahm</td>            
                        <td width='10%'>$row->warna</td>            
                        <td width='10%'>$row->tahun_produksi</td>            
                        <td width='10%'>$tgl</td>            
                        <td width='15%'>$row->keterangan</td>
                      </tr>";                    
                      }
                    ?>  
                  </table>
                </div>
              </div>              
          </div>
        </div>
      </div>
      <?php if($mode == 'approval'){ ?>
      <div class="box-footer">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-10">
          <button type="submit" <?php echo $this->m_admin->set_tombol($id_menu,$group,"approval"); ?> onclick="return confirm('Are you sure to approve all data?')" name="approval" value="approve" class="btn btn-info btn-flat"><i class="fa fa-check"></i> Approve</button>
          <button type="submit" <?php echo $this->m_admin->set_tombol($id_menu,$group,"approval"); ?> onclick="return confirm('Are you sure to reject all data?')" name="approval" value="reject" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Reject</button>          
        </div>
      </div><!-- /.box-footer -->
      <?php } ?>
    </div><!-- /.box -->

    <?php 
    }elseif($set=='scan'){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/retur_unit">
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
            <form class="form-horizontal" action="h1/retur_unit/save_scan" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required name="gudang" id="gudang">
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
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="no_retur_dealer" id="no_retur_dealer" value="<?php echo $no_retur_dealer ?>">
                    <input type="text" class="form-control" autofocus id="no_mesin" autocomplete="off" placeholder="Scan Barcode" name="no_mesin" maxlength="13">
                  </div>                                                      
                </div>                
                <div class="form-group">
                  <span id="tampil_data"></span>
                </div>
              </div>              
            </form>
          </div>
        </div>
      </div>            

    <?php 
    }elseif($set=='cetak_stiker'){
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/retur_unit">
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
            <form class="form-horizontal" action="h1/retur_unit/save_scan" method="post" enctype="multipart/form-data">
              <div class="box-body">                                  
                <div class="form-group">
                  <button class="btn btn-primary btn-block btn-flat" disabled>Detail Unit</button>
                  <table id="" class="table table-bordered table-hover">
                    <thead>
                      <tr>                    
                        <th width="15%">No Mesin</th>              
                        <th width="15%">No Rangka</th>                                      
                        <th width="15%">Tipe</th>              
                        <th width="10%">Warna</th>
                        <th width="10%">Lokasi</th> 
                        <th width="5%">Aksi</th>                                                   
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      foreach ($dt_scan->result() as $isi) {    
                        $print = $this->m_admin->set_tombol($id_menu,$group,'print');
                        echo "
                        <tr>
                          <td>$isi->no_mesin</td>
                          <td>$isi->no_rangka</td>
                          <td>$isi->tipe_ahm</td>
                          <td>$isi->warna</td>
                          <td>$isi->lokasi - $isi->slot</td>
                          <td>
                            <a target='_blank' href='h1/retur_unit/cetak_s?id=$isi->no_mesin' $print class='btn btn-primary btn-sm btn-flat'><i class='fa fa-print'></i></a>
                          </td>
                        </tr>
                        ";  
                      }
                      ?>
                    </tbody>                                  
                  </table>
                </div>
              </div>              
            </form>
          </div>
        </div>
      </div>            

    <?php 
    }elseif($set=='retur_ksu'){ 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/retur_unit">
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
            <form class="form-horizontal" action="h1/retur_unit/save_ksu" method="post" enctype="multipart/form-data">
              <div class="box-body">                                  
                <div class="form-group">
                  <button class="btn btn-primary btn-block btn-flat" disabled>Retur KSU Unit</button>
                  <table id="" class="table table-bordered table-hover">
                    <thead>
                      <tr>                    
                        <th>Kode KSU</th>              
                        <th>KSU</th>                                      
                        <th width=10%>Qty Terima</th>                                      
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $no=1;
                      foreach ($dt_retur->result() as $isi) {    
                        $jum = $dt_retur->num_rows();
                        $cek = $this->db->query("SELECT * FROM tr_retur_dealer_detail_ksu WHERE id_ksu = '$isi->id_ksu' AND no_retur_dealer = '$no_retur_dealer'");
                        if($cek->num_rows() > 0){
                          $t = $cek->row();
                          $isian = $t->qty_terima;
                        }else{
                          $isian = $isi->jum;
                        }
                        echo "
                        <tr>
                          <td>$isi->id_ksu</td>
                          <td>$isi->ksu</td>                          
                          <td>
                            <input type='text' value='$isian' name='qty_terima_$no' class='form-control isi'>
                            <input type='hidden' value='$jum' name='jum'>
                            <input type='hidden' value='$isi->id_ksu' name='id_ksu_$no'>
                            <input type='hidden' value='$no_retur_dealer' name='no_retur_dealer'>
                          </td>
                        </tr>
                        ";  
                        $no++;
                      }
                      ?>
                    </tbody>                                  
                  </table>
                </div>
              </div>              
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

    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!-- <a href="h1/retur_unit/add">            
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>           -->
                    
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
              <th>No Retur</th>             
              <th>Nama Dealer</th> 
              <th>Status</th>              
              <th width='15%'>Aksi</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_retur->result() as $row) {    
            $print = $this->m_admin->set_tombol($id_menu,$group,'print');
            $approval = $this->m_admin->set_tombol($id_menu,$group,'approval');

            if($row->status_retur_d == 'input'){
              $status = "<span class='label label-warning'>waiting approval</span>";
              $tampil = "<a data-toggle=\"tooltip\" title=\"Approval Data\" $approval class=\"btn btn-xs btn-success btn-sm btn-flat\" href=\"h1/retur_unit/approval?s=approve&id=$row->no_retur_dealer\"><i class=\"fa fa-check\"></i> Approval</a>";
            }elseif($row->status_retur_d == 'approved'){
              $status = "<span class='label label-success'>$row->status_retur_d</span>";
              $tampil = "<a href='h1/retur_unit/scan?id=$row->no_retur_dealer' data-toggle=\"tooltip\" title=\"Scan Unit\"  class=\"btn btn-xs btn-primary btn-sm btn-flat\"><i class=\"fa fa-download\"></i> Scan Unit</a>
                        <a href='h1/retur_unit/cetak_ulang?id=$row->no_retur_dealer' $print data-toggle=\"tooltip\" title=\"Cetak Ulang Stiker\"  class=\"btn btn-xs bg-maroon btn-sm btn-flat\" ><i class=\"fa fa-print\"></i> Cetak Ulang Stiker</a>
                        <a href='h1/retur_unit/retur_ksu?id=$row->no_retur_dealer' data-toggle=\"tooltip\" title=\"Retur KSU\"  class=\"btn btn-xs btn-success btn-sm btn-flat\" ><i class=\"fa fa-refresh\"></i> Retur KSU</a>";              
            }elseif($row->status_retur_d == 'rejected'){
              $status = "<span class='label label-danger'>$row->status_retur_d</span>";            
            }else{
              $status = "<span class='label label-warning'>$row->status_retur_d</span>";
            }                                     
          echo "          
            <tr>
              <td>$no</td>                           
              <td>
                <a href='h1/retur_unit/detail?id=$row->no_retur_dealer'>
                  $row->no_retur_dealer
                </a>
              </td>              
              <td>$row->nama_dealer</td>              
              <td>$status</td>
              <td>$tampil</td>
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
<script type="text/javascript">
function simpan_data(){
  var no_retur_dealer   = document.getElementById("no_retur_dealer").value;  
  var no_mesin          = document.getElementById("no_mesin").value;     
  var gudang          = document.getElementById("gudang").value;     
  //alert(id_po);
  if (no_retur_dealer == "" || no_mesin == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h1/retur_unit/save_scan')?>",
          type:"POST",
          data:"no_mesin="+no_mesin+"&no_retur_dealer="+no_retur_dealer+"&gudang="+gudang,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="ok"){
                kirim_data();
                kosong();                        
              }else if(data[0]=="lokasi"){
                alert("Lokasi tidak tersedia");
                kosong();                        
              }else if(data[0]=="sudah"){
                alert("No Mesin sudah masuk ke database retur MD");
                kosong();                        
              }else{
                alert(data[0]);
                kosong();
              }                
          }
      })    
  }
}
function kirim_data(){    
  $("#tampil_data").show();
  var no_retur_dealer = document.getElementById("no_retur_dealer").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "no_retur_dealer="+no_retur_dealer;                           
     xhr.open("POST", "h1/retur_unit/t_scan", true); 
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
function kosong(args){
  $("#no_mesin").val("");  
}
function cek(){
var no_mesin = document.getElementById("no_mesin").value; 
alert(no_mesin);
}
</script>
<script type="text/javascript">
var no_mesin = document.getElementById("no_mesin");
no_mesin.addEventListener("keydown", function (e) {
    if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
        simpan_data();
    }
});
</script>