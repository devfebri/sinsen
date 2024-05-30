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
if(isset($_GET['id'])){
?>
<body onload="kirim_data_pu()">
<?php }else{ ?>
<body onload="auto()">
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
    <li class="">Penerimaan</li>
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
          <a href="h1/penerimaan_unit">
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
            <form class="form-horizontal" action="h1/penerimaan_unit/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <input type="hidden" id="tgl" value="<?php echo date("d-M-y") ?>">
                <input type="hidden" id="id_penerimaan_unit" name="id_penerimaan_unit">
                <!-- <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Penerimaan Unit</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="id_penerimaan_unit" readonly placeholder="ID Penerimaan Unit" name="id_penerimaan_unit">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Antrian</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="no_antrian" readonly placeholder="No Antrian" name="no_antrian">                    
                  </div>>
                </div-->
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan Ekspedisi</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="No Surat Jalan" name="no_surat_jalan">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="tanggal2" placeholder="Tgl Surat Jalan" name="tgl_surat_jalan">                                        
                  </div>
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Ekspedisi</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required name="ekspedisi" id="ekspedisi" onchange="take_eks()">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_vendor->result() as $val) {
                        echo "
                        <option value='$val->id_vendor'>$val->vendor_name</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Polisi Ekspedisi</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required id="no_polisi" name="no_polisi">
                      <option value="">- choose -</option>                      
                    </select>
                  </div>
                </div>                                            
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Supir</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required id="nama_driver" name="nama_driver" onchange="take_no()">
                      <option value="">- choose -</option>                      
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telepon</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="no_telp" placeholder="No Telepon" name="no_telp">                                        
                  </div>
                </div>                                            
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
                  <div class="col-sm-3">
                    <select class="form-control select2" required name="gudang" id="gudang" onchange="cek_qty()">
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
                  <div class="col-sm-1">  
                    <input type="text" readonly id="sisa_qty" class="form-control" placeholder="Qty">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly required class="form-control" value="<?php echo date("Y-m-d") ?>" id="tanggal" placeholder="Tanggal Penerimaan" name="tgl_penerimaan">                                        
                  </div>
                </div>

                <hr>                
                <div class="form-group">
                                                      
                  <span id="tampil_pu"></span>                                                                                  
                                    
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
    }elseif($set=="edit"){
      $row = $dt_penerimaan_unit->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/penerimaan_unit">
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
            <form class="form-horizontal" action="h1/penerimaan_unit/update" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <input type="hidden" id="tgl" value="<?php echo date("d-M-y") ?>">                
                <input type="hidden"  class="form-control" id="id_penerimaan_unit" value="<?php echo $row->id_penerimaan_unit ?>" readonly placeholder="ID Penerimaan Unit" name="id_penerimaan_unit">
                <div class="form-group">
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">ID Penerimaan Unit</label>
                  <div class="col-sm-4">
                  </div> -->
                  <label for="inputEmail3" class="col-sm-2 control-label">No Antrian</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="no_antrian" value="<?php echo $row->no_antrian ?>" readonly placeholder="No Antrian" name="no_antrian">                    
                  </div>
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $row->no_surat_jalan ?>" placeholder="No Surat Jalan" name="no_surat_jalan">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $row->tgl_surat_jalan ?>" id="tanggal2" placeholder="Tgl Surat Jalan" name="tgl_surat_jalan">                                        
                  </div>
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Ekspedisi</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required name="ekspedisi" id="ekspedisi" onchange="take_eks()">
                      <option value="<?php echo $row->ekspedisi ?>">
                        <?php 
                        $tr  = $this->m_admin->getByID("ms_vendor","id_vendor",$row->ekspedisi)->row();
                        echo $tr->vendor_name;
                        ?>
                      </option>
                      <?php 
                      $dt_vendor = $this->m_admin->kondisiCond("ms_vendor","id_vendor != '$row->ekspedisi'");                                                
                      foreach($dt_vendor->result() as $val) {
                        echo "
                        <option value='$val->id_vendor'>$val->vendor_name</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Polisi Ekspedisi</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required id="no_polisi" name="no_polisi">
                      <option value="<?php echo $row->no_polisi ?>"><?php echo $row->no_polisi; ?></option>
                    </select>
                  </div>
                </div>                                            
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Supir</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required id="nama_driver" name="nama_driver">
                      <option value="<?php echo $row->nama_driver ?>"><?php echo $row->nama_driver; ?></option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telepon</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $row->no_telp ?>" placeholder="No Telepon" name="no_telp">                                        
                  </div>
                </div>                                            
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
                  <div class="col-sm-3">
                    <select class="form-control select2" required name="gudang" id="gudang" onchange="cek_qty()">
                      <option value="<?php echo $row->gudang ?>"><?php echo $row->gudang; ?></option>
                      <?php                       
                      foreach($dt_gudang->result() as $val) {
                        echo "
                        <option>$val->gudang</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-sm-1">  
                    <input type="text" readonly id="sisa_qty" class="form-control" placeholder="Qty">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly required class="form-control" value="<?php echo $row->tgl_penerimaan ?>" placeholder="Tanggal Penerimaan" name="tgl_penerimaan">                                        
                  </div>
                </div>

                <hr>                
                <div class="form-group">
                                                      
                  <span id="tampil_pu"></span>                                                                                  
                                    
                </div>                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    
    
    <?php 
    }elseif($set == 'scan'){
    ?>
<script type="text/javascript" src="<?= base_url('assets/tray') ?>/js/dependencies/rsvp-3.1.0.min.js"></script>
<script type="text/javascript" src="<?= base_url('assets/tray') ?>/js/dependencies/sha-256.min.js"></script>
    <script type="text/javascript" src="<?= base_url('assets/tray') ?>/js/qz-tray.js"></script>
<script>
  $(document).ready(function(){
    // launchQZ();
    startConnection();

  })
/// Authentication setup ///
qz.security.setCertificatePromise(function(resolve, reject) {
  $.ajax({ url: "<?= base_url('') ?>assets/tray/assets/override.crt", cache: false, dataType: "text" }).then(resolve, reject);
});
qz.security.setSignaturePromise(function(toSign) {
    return function(resolve, reject) {
        //Preferred method - from server
        $.ajax("<?= base_url('') ?>assets/tray/assets/signing/sign-message.php?request=" + toSign).then(resolve, reject);

        //Alternate method - unsigned
        // resolve();
    };
});
</script>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/penerimaan_unit">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-chevron-left"></i> Back</button>
          </a>
          <button class="btn btn-success btn-flat" type="button" onclick="rfs_click()"><i class="fa fa-qrcode"></i> RFS</button>                            
          <button class="btn bg-maroon btn-flat" type="button" onclick="nrfs_click()"><i class="fa fa-qrcode"></i> NRFS</button>          
          <a href="h1/penerimaan_unit/scan?id=<?php echo $_GET['id'] ?>"><button class="btn btn-default btn-flat" type="button"><i class="fa fa-refresh"></i> Refresh</button></a>          

        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-flat btn-sm btn-info" disabled style="font-weight: bold;font-size: 11pt" id="qz_status">Status : </button>
              <button class="btn btn-flat btn-sm btn-success" disabled style="font-weight: bold;font-size: 11pt" id="configPrinter">Printer : </button>
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">  
        <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>
        <div class="col-sm-2">
          <input type="text" class="form-control" disabled value="<?php echo date("Y-m-d") ?>" id="tanggal" placeholder="Tanggal penerimaan" id="tgl_penerimaan">                    
        </div>                       
        <div class="col-md-2 pull-right">
          <input type="text" class="form-control" disabled placeholder="Sisa Unit" id="sisa_unit">                    
        </div>
      </div>
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
        <input type="hidden" name="id_pu" id="id_pu" value="<?php echo $id_pu ?>">
        <div class="row" id="rfs_div">
          <div class="col-md-12">
            <div class="box-body">              
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Scan Barcode RFS</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" autofocus id="rfs_text" placeholder="Scan Barcode" name="no_barcode" maxlength="13">                    
                </div>                                                          
                <div class="col-sm-2">
                  <!-- <button data-toggle="modal" data-target="#Scanmodal" class="btn btn-primary btn-flat btn-md"><i class="fa fa-check"></i> Browse</button> -->
                  <button type="button" class="btn btn-primary btn-flat btn-md"  id_checker="<?php echo $this->input->get("id") ?>" data-toggle="modal" data-target=".modal_detail" onclick="detail_scan('<?php echo $this->input->get("id") ?>')"><i class="fa fa-check"></i> Browse</button>
                </div>                                                          
              </div>                
            </div>
          </div>
        </div>

        <div class="row" id="nrfs_div">
          <div class="col-md-12">
            <div class="box-body">              
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Scan Barcode NRFS</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" autofocus id="nrfs_text" placeholder="Scan Barcode" name="no_barcode" maxlength="13">                    
                </div>                                                          
                <div class="col-sm-2">
                  <!-- <button data-toggle="modal" data-target="#Scanmodal2" class="btn btn-primary btn-flat btn-md"><i class="fa fa-check"></i> Browse</button> -->
                  <button type="button" class="btn btn-primary btn-flat btn-md"  id_checker="<?php echo $this->input->get("id") ?>" data-toggle="modal" data-target=".modal_detail2" onclick="detail_scan2('<?php echo $this->input->get("id") ?>')"><i class="fa fa-check"></i> Browse</button>
                </div>                                                          
              </div>                
            </div>
          </div>
        </div>

        <div id="tampil_data"></div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->


    <?php 
    }elseif($set == 'cetak'){
    ?>
<script type="text/javascript" src="<?= base_url('assets/tray') ?>/js/dependencies/rsvp-3.1.0.min.js"></script>
<script type="text/javascript" src="<?= base_url('assets/tray') ?>/js/dependencies/sha-256.min.js"></script>
    <script type="text/javascript" src="<?= base_url('assets/tray') ?>/js/qz-tray.js"></script>
<script>
  $(document).ready(function(){
    // launchQZ();
    startConnection();

  })
/// Authentication setup ///
qz.security.setCertificatePromise(function(resolve, reject) {
  $.ajax({ url: "<?= base_url('') ?>assets/tray/assets/override.crt", cache: false, dataType: "text" }).then(resolve, reject);
});
qz.security.setSignaturePromise(function(toSign) {
    return function(resolve, reject) {
        //Preferred method - from server
        $.ajax("<?= base_url('') ?>assets/tray/assets/signing/sign-message.php?request=" + toSign).then(resolve, reject);

        //Alternate method - unsigned
        // resolve();
    };
});
</script>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/penerimaan_unit">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-chevron-left"></i> Back</button>
          </a>          
          <!--button class="btn bg-maroon btn-flat margin" ><i class="fa fa-print"></i> Print All</button-->                  
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
        <div class="row" style="padding-bottom: 10px;">
          <div class="col-md-12" align="right">
            <button class="btn btn-flat btn-sm btn-info" disabled style="font-weight: bold;font-size: 11pt" id="qz_status">Status : </button>
            <button class="btn btn-flat btn-sm btn-success" disabled style="font-weight: bold;font-size: 11pt" id="configPrinter">Printer : </button>
          </div>
        </div>
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>No Mesin</th>              
              <th>Tipe Kendaraan</th>              
              <th>Warna</th>
              <th>Status</th>
              <th width="5%">Qty Cetak</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_shipping_list->result() as $row) {                 
            echo "
            <tr>
              <td>$no</td>
              <td>$row->no_mesin</td>
              <td>$row->tipe_ahm</td>
              <td>$row->warna</td>              
              <td>$row->tipe</td>              
              <td>"; ?>
                <!-- <a href="h1/penerimaan_unit/cetak_s?id=<?php echo $row->no_mesin ?>" target="_blank">
                  <button name="cetak" type="button" class="btn bg-maroon btn-flat btn-sm"><i class="fa fa-print"></i></button>
                </a> -->
                <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"print"); ?> name="cetak" type="button" class="btn bg-maroon btn-flat btn-sm" onclick="cetak_stiker(this,'<?= $row->no_mesin ?>')">
                <i class="fa fa-print"></i></button>
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
    }elseif($set == 'detail'){
      $row = $dt_rfs->row();
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <?php if(isset($_GET['h'])){  ?>
            <a href="h1/penerimaan_unit/history">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
            </a>          
          <?php }else{ ?>
            <a href="h1/penerimaan_unit">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
            </a>          
          <?php } ?>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body form-horizontal">
          <div  class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>
          <div class="col-sm-3">
            <?php 
            if(isset($row->tgl_penerimaan)){
              $tg = $row->tgl_penerimaan;
            }else{
              $tg = "";
            }
            ?>
            <input type="text" class="form-control" disabled value="<?php echo $tg ?>" id="tanggal" placeholder="Tanggal penerimaan" id="tgl_penerimaan">                    
          </div>  
           <label for="inputEmail3" class="col-sm-2 control-label">Ekspedisi</label>
            <div class="col-sm-3">
              <?php 
              if(isset($row->tgl_penerimaan)){
                $tg = $row->tgl_penerimaan;
              }else{
                $tg = "";
              }
              ?>
              <?php $pu = $this->db->query("SELECT vendor_name,no_polisi,nama_driver,ekspedisi FROM tr_penerimaan_unit JOIN ms_vendor ON tr_penerimaan_unit.ekspedisi=ms_vendor.id_vendor WHERE id_penerimaan_unit='$id_penerimaan_unit'")->row(); ?>
              <input type="text" class="form-control" disabled value="<?php echo $pu->vendor_name ?>">            
            </div> 
          </div>
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Sopir</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" disabled value="<?php echo $pu->nama_driver ?>">            
              </div>
              <label for="inputEmail3" class="col-sm-2 control-label">No. Polisi</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" disabled value="<?php echo $pu->no_polisi ?>">            
              </div>   
          </div>                                                                 
      </div>
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
        <button class="btn-block btn-primary" disabled> RFS</button>
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>
              <th>No Mesin</th>              
              <th>No Rangka</th>
              <th>No SL</th>
              <!-- <th>Nama Ekspeisi</th>       -->
              <th>Kategori</th>
              <th>Tipe</th>  
              <th>Warna</th>        
              <th>Kode Item</th>    
              <th>Lokasi</th>
              <th>FIFO</th>              
            </tr>
          </thead>
          <tbody>            
            <?php 
            $no = 1;
            foreach ($dt_rfs->result() as $row) {
              $ugm = $this->db->get_where('ms_ugm',['id_tipe_kendaraan'=>$row->tipe_motor]);
              $ugm = $ugm->num_rows()>0?$ugm->row()->kategori:'';      
              echo "
              <tr>
                <td>$no</td>
                <td>$row->no_mesin</td>
                <td>$row->no_rangka</td>
                <td>$row->no_shipping_list</td>
                <td>$ugm</td>
                <td>$row->deskripsi_ahm</td>
                <td>$row->warna</td>
                <td>$row->id_item</td>
                <td>$row->lokasi</td>
                <td>$row->fifo</td>
              </tr>
              ";
              $no++;
            }
            ?>
          </tbody>
        </table>


        <button class="btn-block btn-warning" disabled> NRFS</button>
        <table id="example3" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>
              <th>No Mesin</th>              
              <th>No Rangka</th>
              <th>No SL</th>
              <!-- <th>Nama Ekspeisi</th>       -->
              <th>Kategori</th>
              <th>Tipe</th>  
              <th>Warna</th>        
              <th>Kode Item</th>    
              <th>Lokasi</th>
              <th>FIFO</th>              
            </tr>
          </thead>
          <tbody>            
            <?php 
            $no = 1;
            foreach ($dt_nrfs->result() as $row) {     
              $ugm = $this->db->get_where('ms_ugm',['id_tipe_kendaraan'=>$row->tipe_motor]);
              $ugm = $ugm->num_rows()>0?$ugm->row()->kategori:'';   
              echo "
              <tr>
                <td>$no</td>
                <td>$row->no_mesin</td>
                <td>$row->no_rangka</td>
                <td>$row->no_shipping_list</td>
                <td>$ugm</td>
                <td>$row->deskripsi_ahm</td>
                <td>$row->warna</td>
                <td>$row->id_item</td>
                <td>$row->lokasi</td>
                <td>$row->fifo</td>
              </tr>
              ";
              $no++;
            }
            ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php 
    }elseif($set == 'ksu'){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/penerimaan_unit">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-chevron-left"></i> Back</button>
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
        <form action="h1/penerimaan_unit/save_ksu" method="post">          
        <button type="submit" onclick="return confirm('Are you sure to save all data?')" class="btn btn-primary btn-flat pull-right"><i class="fa fa-save"></i> Save All</button>          
        <input type="hidden" value="<?php echo $_GET['id'] ?>" name="id_pu">
        <br><br>
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>
              <th>No SL</th>
              <th>Tipe</th>              
              <th>Warna</th>              
              <th>Kode Item</th>
              <th>Jumlah Unit</th>
              <th>KSU</th>
            </tr>
          </thead>
          <tbody>            
            <?php 
            $no = 1;
            foreach ($dt_rfs->result() as $row) {
              
              $unit = $this->db->query("SELECT COUNT(no_mesin) AS nosin FROM tr_scan_barcode 
                INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.no_shipping_list = tr_penerimaan_unit_detail.no_shipping_list
                WHERE tr_penerimaan_unit_detail.no_shipping_list = '$row->no_shipping_list' AND tr_scan_barcode.id_item = '$row->id_item'");
              if($unit->num_rows() > 0){
                $jum = $unit->row();
                $total_unit = $jum->nosin;
                $tt = $jum->nosin;
              }else{
                $total_unit = 0;
                $tt = 0;
              }


              echo "
              <tr>
                <td>$no</td>
                <td>$row->no_shipping_list</td>                
                <td>$row->tipe_ahm</td>                
                <td>$row->warna</td>                
                <td>$row->id_item</td>                
                <td>$total_unit Unit</td>                
                <td>";
                $cek = $this->db->query("SELECT id_ksu FROM ms_koneksi_ksu_detail INNER JOIN ms_koneksi_ksu ON ms_koneksi_ksu.id_koneksi_ksu = ms_koneksi_ksu_detail.id_koneksi_ksu WHERE ms_koneksi_ksu.id_tipe_kendaraan = '$row->tipe_motor'");
                if(count($cek) > 0){
                  $isi = $cek->row();
                  foreach ($cek->result() as $isi) {                    
                    $cek2 = $this->db->query("SELECT id_ksu,ksu FROM ms_ksu WHERE id_ksu = '$isi->id_ksu'");
                    if(count($cek2) > 0){
                      $rd = $cek2->row();
                      $rty = $this->db->query("SELECT * FROM tr_penerimaan_ksu WHERE id_ksu = '$rd->id_ksu' AND id_warna = '$row->id_warna' AND no_sl = '$row->no_shipping_list' AND id_tipe_kendaraan = '$row->tipe_motor'");
                      if($rty->num_rows() == 0){
                        echo "
                           <div class='input-group'>
                            <span class='input-group-addon bg-maroon'>$rd->ksu</span>                         
                            <input type='hidden' name='id_ksu[]' value='$rd->id_ksu'>
                            <input type='hidden' name='tipe_motor[]' value='$row->tipe_motor'>
                            <input type='hidden' name='id_warna[]' value='$row->id_warna'>
                            <input type='hidden' name='no_sl[]' value='$row->no_shipping_list'>
                            <input type='hidden' name='total_unit[]' value='$total_unit'>                            
                            <input type='text' onkeypress='return number_only(event)' value='$tt' name='qty[]' class='input-group-addon input-block' style='width:50px;'>
                          </div> <br>";                        
                      }else{
                        $ui = $rty->row();
                        echo "
                           <div class='input-group'>
                            <span class='input-group-addon bg-maroon'>$rd->ksu</span>                         
                            <input type='hidden' name='id_ksu[]' value='$rd->id_ksu'>
                            <input type='hidden' name='tipe_motor[]' value='$row->tipe_motor'>
                            <input type='hidden' name='id_warna[]' value='$row->id_warna'>
                            <input type='hidden' name='no_sl[]' value='$row->no_shipping_list'>
                            <input type='hidden' name='total_unit[]' value='$total_unit'>                            
                            <input type='text' onkeypress='return number_only(event)' value='$ui->qty' name='qty[]' class='input-group-addon input-block' style='width:50px;'>
                          </div> <br>";                        
                      }
                    }
                  }                  
                }
                echo "
                </td>                
              </tr>
              ";
              $no++;
            }
            ?>
            </form>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/penerimaan_unit/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
          <a href="h1/penerimaan_unit/history">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-check"></i> Cek History Penerimaan</button>
          </a>          
          <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  
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
              <th>No Penerimaan Unit</th>
              <th>No Antrian</th>  
              <th>Tanggal Surat Jalan</th>            
              <th>Ekspedisi</th>   
              <th>No Polisi Ekspedisi</th>
              <th>Nama Supir</th>
              <th>Tanggal Penerimaan</th>              
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_penerimaan_unit->result() as $row) {     
            $s = $this->db->query("SELECT * FROM ms_vendor WHERE id_vendor = '$row->ekspedisi'");          
            if($s->num_rows() > 0){
              $r = $s->row();
              $vendor_name = $r->vendor_name;
            }else{
              $vendor_name = "";
            }
            $edit = $this->m_admin->set_tombol($id_menu,$group,'edit');
            $delete = $this->m_admin->set_tombol($id_menu,$group,'delete');
            $print = $this->m_admin->set_tombol($id_menu,$group,'print');
            $approval = $this->m_admin->set_tombol($id_menu,$group,'approval');

            echo "
            <tr>
              <td>$no</td>
              <td>
                <a href='h1/penerimaan_unit/view?id=$row->id_penerimaan_unit'>
                  $row->id_penerimaan_unit
                </a>
              </td>
              <td>$row->no_antrian</td>
              <td>$row->tgl_surat_jalan</td>
              <td>$vendor_name</td>
              <td>$row->no_polisi</td>
              <td>$row->nama_driver</td>
              <td>$row->tgl_penerimaan</td>";
              if($row->status == 'input'){
                echo "
                <td>
                  <a href='h1/penerimaan_unit/scan?id=$row->id_penerimaan_unit'>
                    <button class='btn btn-flat btn-xs btn-success'><i class='fa fa-tags'></i> Scan/Entry No Mesin</button>
                  </a>                  
                  <a href='h1/penerimaan_unit/cetak_stiker?id=$row->id_penerimaan_unit'>
                    <button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-print'></i> Cetak Ulang Stiker</button>                
                  </a>                
                  <a href='h1/penerimaan_unit/edit?id=$row->id_penerimaan_unit'>
                    <button $edit class='btn btn-flat btn-xs bg-maroon'><i class='fa fa-edit'></i> Edit</button>
                  </a>"; ?>
                  <a href='h1/penerimaan_unit/close_scan?id=<?php echo $row->id_penerimaan_unit ?>'>
                    <button onclick="return confirm('Are you sure?')" class='btn btn-flat btn-xs btn-danger'><i class='fa fa-close'></i> Close Scan</button>              
                  </a>
                </td>
              <?php  
              }elseif($row->status == 'close scan'){
                echo "
                <td>                                    
                  <a href='h1/penerimaan_unit/cetak_stiker?id=$row->id_penerimaan_unit'>
                    <button $print class='btn btn-flat btn-xs btn-primary'><i class='fa fa-print'></i> Cetak Ulang Stiker</button>                
                  </a>"; ?>
                  <!-- <a href='h1/penerimaan_unit/close_ksu?id=<?php echo $row->id_penerimaan_unit ?>'>
                    <button onclick="return confirm('Are you sure?')" class='btn btn-flat btn-xs btn-danger'><i class='fa fa-close'></i> Close KSU</button>              
                  </a> -->
                </td>
              <?php 
              }elseif($row->status == 'close ksu'){
                echo "
                <td>                                    
                  <a href='h1/penerimaan_unit/cetak_stiker?id=$row->id_penerimaan_unit'>
                    <button $print class='btn btn-flat btn-xs btn-primary'><i class='fa fa-print'></i> Cetak Ulang Striker</button>                
                  </a>"; ?>
                  <a href='h1/penerimaan_unit/close?id=<?php echo $row->id_penerimaan_unit ?>'>
                    <button onclick="return confirm('Are you sure?')" class='btn btn-flat btn-xs btn-danger'><i class='fa fa-close'></i> Close</button>              
                  </a>
                </td>
              <?php 
              }elseif($row->status == 'close'){
                echo "<td>closed</td>";
              }
              echo "              
            </tr>
            ";
          $no++;
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }elseif($set=="history"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/penerimaan_unit">
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>No Penerimaan Unit</th>
              <th>No Antrian</th>  
              <th>Gudang</th>            
              <th>Ekspedisi</th>              
              <th>No Surat Jalan Ekspedisi</th>
              <th>Shipping List</th>
              <th>No Polisi Ekspedisi</th>
              <th>Nama Supir</th>
              <th>Tanggal Terima</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_penerimaan_unit->result() as $row) {     
            $s = $this->db->query("SELECT * FROM ms_vendor WHERE id_vendor = '$row->ekspedisi'");          
            if($s->num_rows() > 0){
              $r = $s->row();
              $vendor_name = $r->vendor_name;
            }else{
              $vendor_name = "";
            }
            echo "
            <tr>
              <td>$no</td>
              <td>
                <a href='h1/penerimaan_unit/view?h=1&id=$row->id_penerimaan_unit'>
                  $row->id_penerimaan_unit
                </a>
              </td>
              <td>$row->no_antrian</td>
              <td>$row->gudang</td>
              <td>$vendor_name</td>
              <td>$row->no_surat_jalan</td>
              <td>";
              $r = $this->db->query("SELECT * FROM tr_penerimaan_unit_detail WHERE id_penerimaan_unit = '$row->id_penerimaan_unit'");
              $rt = $r->row();
              foreach ($r->result() as $k) {
                echo "$k->no_shipping_list <br>";
              }
              echo "
              </td>
              <td>$row->no_polisi</td>
              <td>$row->nama_driver</td>
              <td>$row->tgl_penerimaan</td>
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

<div class="modal fade" id="Itemmodal2">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search Shipping List
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>No Shipping List</th>            
              <th>Jumlah Unit</th>            
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          foreach ($dt_item->result() as $ve2) {
            $r = $this->db->query("SELECT COUNT(no_rangka) AS jum FROM tr_shipping_list WHERE no_shipping_list = '$ve2->no_shipping_list'")->row();
            echo "
            <tr>
              <td>$no</td>
              <td>$ve2->no_shipping_list</td>
              <td>$r->jum</td>";
              ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="chooseitem('<?php echo $ve2->no_shipping_list; ?>','<?php echo $r->jum; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
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

<div class="modal fade" id="Scanmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search Item
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example3" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>No Mesin</th>            
              <th>No Rangka</th>            
              <th>Tipe</th>
              <th>Warna</th>
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1;       
          if(isset($_GET['id'])){
            $id_pu = $_GET['id'];
          }else{
            $id_pu = "";
          }   
          
          $dt_scan = $this->db->query("SELECT * FROM tr_penerimaan_unit_detail INNER JOIN tr_shipping_list ON 
                  tr_penerimaan_unit_detail.no_shipping_list = tr_shipping_list.no_shipping_list
                  WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id_pu' AND
                  tr_shipping_list.no_rangka NOT IN (SELECT no_rangka FROM tr_scan_barcode WHERE no_rangka IS NOT NULL)");
          foreach ($dt_scan->result() as $ve2) {            
            echo "
            <tr>
              <td>$no</td>
              <td>$ve2->no_mesin</td>
              <td>$ve2->no_rangka</td>
              <td>$ve2->id_modell</td>
              <td>$ve2->id_warna</td>";
              ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal"  onclick="choose_rangka('<?php echo $ve2->no_mesin; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
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

<div class="modal fade" id="Scanmodal2">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search Item
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example5" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>No Mesin</th>            
              <th>No Rangka</th>            
              <th>Tipe</th>
              <th>Warna</th>
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1;       
          if(isset($_GET['id'])){
            $id_pu = $_GET['id'];
          }else{
            $id_pu = "";
          }   
          
          $dt_scan = $this->db->query("SELECT * FROM tr_penerimaan_unit_detail INNER JOIN tr_shipping_list ON 
                  tr_penerimaan_unit_detail.no_shipping_list = tr_shipping_list.no_shipping_list
                  WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$id_pu' AND
                  tr_shipping_list.no_rangka NOT IN (SELECT no_rangka FROM tr_scan_barcode WHERE no_rangka IS NOT NULL)");
          foreach ($dt_scan->result() as $ve2) {            
            echo "
            <tr>
              <td>$no</td>
              <td>$ve2->no_mesin</td>
              <td>$ve2->no_rangka</td>
              <td>$ve2->id_modell</td>
              <td>$ve2->id_warna</td>";
              ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="choose_rangka2('<?php echo $ve2->no_mesin; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
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


<!-- Modal Detail -->



<div class="modal fade modal_detail3">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title">Search Shipping List</h4>
      </div>
      <div class="modal-body">
        <table id="example1" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>No Shipping List</th>            
              <th>Jumlah Unit</th>            
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          $dt_item2 = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list INNER JOIN tr_invoice 
                  ON tr_shipping_list.no_shipping_list = tr_invoice.no_sl WHERE tr_invoice.status = 'approve' AND
                  tr_shipping_list.no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_penerimaan_unit_detail WHERE no_shipping_list IS NOT NULL) ORDER BY tgl_sl DESC");            
          // $dt_item2 = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list INNER JOIN tr_invoice 
          //         ON tr_shipping_list.no_shipping_list = tr_invoice.no_sl WHERE tr_invoice.status = 'approve' AND
          //         tr_shipping_list.no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_penerimaan_unit_detail WHERE no_shipping_list IS NOT NULL) ORDER BY tgl_sl DESC");                      
          foreach ($dt_item2->result() as $ve2) {
            $r = $this->db->query("SELECT COUNT(no_rangka) AS jum FROM tr_shipping_list WHERE no_shipping_list = '$ve2->no_shipping_list'")->row();
            echo "
            <tr>
              <td>$no</td>
              <td>$ve2->no_shipping_list</td>
              <td>$r->jum</td>";
              ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="chooseitem('<?php echo $ve2->no_shipping_list; ?>','<?php echo $r->jum; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
              </td>           
            </tr>
            <?php
            $no++;
          }
          $dt_item3 = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list WHERE no_shipping_list = '1200/2019/16765' OR 
            no_shipping_list = '1100/2019/46623' OR no_shipping_list = '1300/2019/68215'");
          foreach ($dt_item3->result() as $ve3) {
            $r = $this->db->query("SELECT COUNT(no_rangka) AS jum FROM tr_shipping_list WHERE no_shipping_list = '$ve3->no_shipping_list'")->row();
            $s = $this->db->query("SELECT COUNT(no_rangka) AS jum FROM tr_scan_barcode WHERE no_shipping_list = '$ve3->no_shipping_list'")->row();
            $jum = $r->jum - $s->jum;
            if($jum > 0){
              echo "
              <tr>
                <td>$no</td>
                <td>$ve3->no_shipping_list</td>
                <td>$jum</td>";
                ?>
                <td class="center">
                  <button title="Choose" data-dismiss="modal" onclick="chooseitem('<?php echo $ve3->no_shipping_list; ?>','<?php echo $jum; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
                </td>           
              </tr>
              <?php
              $no++;
            }
          }
          ?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Tutup</button>
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
         <h4 class="modal-title">Search Item</h4>
      </div>
      <div class="modal-body" id="show_scan">
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade modal_detail2">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title">Search Item</h4>
      </div>
      <div class="modal-body" id="show_scan2">
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('.Itemmodal').on('shown.bs.modal', function() {
      var id_penerimaan_unit=$(this).attr('id_penerimaan_unit');
        $.ajax({
             url:"<?php echo site_url('h1/penerimaan_unit/detail_sl');?>",
             type:"POST",
             data:"id_penerimaan_unit="+id_penerimaan_unit,
             cache:false,
             success:function(html){
                $("#tampol").html(html);
             }
        });
  });
</script>
<script type="text/javascript">
  $('.modal_detail3').on('shown.bs.modal', function() {
      var id_penerimaan_unit=1;
        $.ajax({
             url:"<?php echo site_url('h1/penerimaan_unit/detail_scan3');?>",
             type:"POST",
             data:"id_penerimaan_unit="+id_penerimaan_unit,
             cache:false,
             success:function(html){
                $("#show_scan3").html(html);
             }
        });
  });
</script>
<script type="text/javascript">
  $('.modal_detail').on('shown.bs.modal', function() {
      var id_penerimaan_unit=$(this).attr('id_penerimaan_unit');
        $.ajax({
             url:"<?php echo site_url('h1/penerimaan_unit/detail_scan');?>",
             type:"POST",
             data:"id_penerimaan_unit="+id_penerimaan_unit,
             cache:false,
             success:function(html){
                $("#show_scan").html(html);
             }
        });
  });
</script>
<script type="text/javascript">
  $('.modal_detail2').on('shown.bs.modal', function() {
      var id_penerimaan_unit=$(this).attr('id_penerimaan_unit');
        $.ajax({
             url:"<?php echo site_url('h1/penerimaan_unit/detail_scan2');?>",
             type:"POST",
             data:"id_penerimaan_unit="+id_penerimaan_unit,
             cache:false,
             success:function(html){
                $("#show_scan2").html(html);
             }
        });
  });
</script>
<!-- End Of Modal Detail -->


<script type="text/javascript">
function detail_sl(){
  var id_penerimaan_unit = document.getElementById("id_penerimaan_unit").value; 
  $.ajax({
   url:"<?php echo site_url('h1/penerimaan_unit/detail_sl');?>",
   type:"POST",
   data:"id_penerimaan_unit="+id_penerimaan_unit,
   cache:false,
   success:function(html){
      $("#show_sl").html(html);
   }
  });
}
function detail_scan(id_penerimaan_unit){
  $.ajax({
   url:"<?php echo site_url('h1/penerimaan_unit/detail_scan');?>",
   type:"POST",
   data:"id_penerimaan_unit="+id_penerimaan_unit,
   cache:false,
   success:function(html){
      $("#show_scan").html(html);
   }
  });
}
function detail_scan2(id_penerimaan_unit){
  $.ajax({
   url:"<?php echo site_url('h1/penerimaan_unit/detail_scan2');?>",
   type:"POST",
   data:"id_penerimaan_unit="+id_penerimaan_unit,
   cache:false,
   success:function(html){
      $("#show_scan2").html(html);
   }
  });
}


function cek(){  
  auto();
  var id_penerimaan_unit_js = document.getElementById("id_penerimaan_unit").value; 
  $.ajax({
      url : "<?php echo site_url('h1/penerimaan_unit/cek_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_penerimaan_unit").val(data[0]);        
        //$("#no_antrian").val(data[1]);       
        kirim_data_pu();
      }        
  })
}

function auto(){
  $("#nrfs_div").hide();
  $("#rfs_div").hide();

  var tgl_js=document.getElementById("tgl").value; 
  $.ajax({
      url : "<?php echo site_url('h1/penerimaan_unit/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        if(data[1] == 'nihil'){
          if (confirm("Terdapat transaksi data sebelumnya yg belum selesai dg ID Penerimaan "+data[0]+". Hapus data sebelumnya dan mulai transaksi data baru?")){
            hapus_auto(data[0]);            
          }
          $("#id_penerimaan_unit").val(data[0]);                  
          kirim_data_pu();
          // alert("Terdapat transaksi data sebelumnya yg belum selesai dg ID Penerimaan "+data[0]+". Hapus data sebelumnya dan mulai transaksi data baru?");
          // hapus_auto(data[0]);
        }else{
          $("#id_penerimaan_unit").val(data[0]);                  
          kirim_data_pu();
        }
      }        
  })
}
function hapus_auto(a){
  var id_p = a;
  $.ajax({
      url : "<?php echo site_url('h1/penerimaan_unit/hapus_auto')?>",
      type:"POST",
      data:"id_p="+id_p,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        auto();
      }        
  })
}

function take_eks(){
  var ekspedisi = $("#ekspedisi").val();                       
  $.ajax({
      url: "<?php echo site_url('h1/penerimaan_unit/take_eks')?>",
      type:"POST",
      data:"ekspedisi="+ekspedisi,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");    
          take_driver();      
          $("#no_polisi").html(msg);                                                    
      } 
  })
}

function take_driver(){
  var ekspedisi = $("#ekspedisi").val();                       
  $.ajax({
      url: "<?php echo site_url('h1/penerimaan_unit/take_driver')?>",
      type:"POST",
      data:"ekspedisi="+ekspedisi,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");          
          $("#nama_driver").html(msg);          
      } 
  })
}

function take_no(){
  var nama_driver = $("#nama_driver").val();                       
  $.ajax({
      url: "<?php echo site_url('h1/penerimaan_unit/take_no')?>",
      type:"POST",
      data:"nama_driver="+nama_driver,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");          
          $("#no_telp").val(data[0]);          
      } 
  })
}
function cek_qty(){
  var gudang = $("#gudang").val();                       
  $.ajax({
      url: "<?php echo site_url('h1/penerimaan_unit/cek_gudang')?>",
      type:"POST",
      data:"gudang="+gudang,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");          
          $("#sisa_qty").val(data[0]);                                                    
      } 
  })
}
function choose_rangka(no_mesin){
  document.getElementById("rfs_text").value = no_mesin;   
  simpan_rfs();
  $("#Scanmodal").modal("hide");
}
function choose_rangka2(no_mesin){
  document.getElementById("nrfs_text").value = no_mesin;   
  simpan_nrfs();
  $("#Scanmodal2").modal("hide");
}
function chooseitem(no_shipping_list,jum){
  document.getElementById("no_shipping_list").value = no_shipping_list; 
  document.getElementById("jumlah").value = jum; 
  cek_item();
  $("#Itemmodal").modal("hide");
}
function simpan_pu(){
  var id_penerimaan_unit  = document.getElementById("id_penerimaan_unit").value;  
  var no_shipping_list    = document.getElementById("no_shipping_list").value;     
  //alert(id_po);
  if (id_penerimaan_unit == "" || no_shipping_list == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h1/penerimaan_unit/save_pu')?>",
          type:"POST",
          data:"id_penerimaan_unit="+id_penerimaan_unit+"&no_shipping_list="+no_shipping_list,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="ok"){
                  kirim_data_pu();
                  kosong();                
              }else{
                  alert("Gagal Simpan, No Shipping List ini sudah dipilih");
                  kosong();                  
              }                
          }
      })    
  }
}
function kosong(args){
  $("#no_shipping_list").val("");
  $("#jumlah").val("");     
}
function hide_pu(){
    $("#tampil_pu").hide();
}
function kirim_data_pu(){  
  $("#nrfs_div").hide();
  $("#rfs_div").hide();  
  $("#tampil_pu").show();
  cek_qty();
  var id_penerimaan_unit = document.getElementById("id_penerimaan_unit").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_penerimaan_unit="+id_penerimaan_unit;                           
     xhr.open("POST", "h1/penerimaan_unit/t_pu", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_pu").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}

function hapus_pu(a){ 
    var id_penerimaan_unit_detail  = a;       
    $.ajax({
        url : "<?php echo site_url('h1/penerimaan_unit/delete_pu')?>",
        type:"POST",
        data:"id_penerimaan_unit_detail="+id_penerimaan_unit_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_pu();
            }
        }
    })
}

function hapus_scan(a,b){ 
    var id_scan_barcode  = a;       
    var jenis  = b;       
    $.ajax({
        url : "<?php echo site_url('h1/penerimaan_unit/delete_scan')?>",
        type:"POST",
        data:"id_scan_barcode="+id_scan_barcode+"&jenis="+jenis,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              if(jenis == 'RFS'){
                kirim_data_rfs();
              }else if(jenis == 'NRFS'){
                kirim_data_nrfs();                
              }
            }
        }
    })
}
function cek_sisa(){ 
    var id_pu = document.getElementById("id_pu").value;   
    $.ajax({
        url : "<?php echo site_url('h1/penerimaan_unit/cek_sisa')?>",
        type:"POST",
        data:"id_pu="+id_pu,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            $("#sisa_unit").val(data[0]);
        }
    })
}

function rfs_click(){
  $("#nrfs_div").hide();
  $("#rfs_div").show();
  $("#rfs_text").focus();
  kirim_data_rfs();  
  cek_sisa();
}
function nrfs_click(){
  $("#rfs_div").hide();
  $("#nrfs_div").show();
  $("#nrfs_text").focus();
  kirim_data_nrfs();
  cek_sisa();
}
function kosong_rfs(args){
  $("#rfs_text").val("");
  $("#nrfs_text").val("");
}
function kirim_data_rfs(){    
  $("#tampil_data").show();
  var id_pu = document.getElementById("id_pu").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_pu="+id_pu;                           
     xhr.open("POST", "h1/penerimaan_unit/t_rfs", true); 
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
function kirim_data_nrfs(){    
  $("#tampil_data").show();
  var id_pu = document.getElementById("id_pu").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_pu="+id_pu;                           
     xhr.open("POST", "h1/penerimaan_unit/t_nrfs", true); 
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
function simpan_rfs(){
  var id_pu       = document.getElementById("id_pu").value;  
  var rfs_text    = document.getElementById("rfs_text").value;     
  //alert(id_po);
  if (id_pu == "" || rfs_text == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h1/penerimaan_unit/save_rfs')?>",
          type:"POST",
          data:"rfs_text="+rfs_text+"&id_pu="+id_pu,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="ok"){
                  kirim_data_rfs();
                  kosong_rfs();      
                  cek_sisa();
                  cetak_stiker(null,rfs_text);     
              }else if(data[0]=="no"){
                  alert("Gagal Simpan, No Mesin ini sudah di-scan sebelumnya");
                  kosong_rfs();                  
              }else if(data[0]=="tipe"){
                  alert("Gagal Simpan, Tipe Kendaraan belum terdaftar di database");
                  kosong_rfs();                  
              }else if(data[0]=="warna"){
                  alert("Gagal Simpan, Warna belum terdaftar di database");
                  kosong_rfs();             
              }else if(data[0]=="item"){
                  alert("Gagal Simpan, Kode Item belum terdaftar di database");
                  kosong_rfs();             
              }else if(data[0]=="ksu"){
                  alert("Gagal Simpan, Tipe Kendaraan ini belum terdaftar di tabel KSU");
                  kosong_rfs();                       
              }else if(data[0]=="FM"){
                  alert("Gagal Simpan, No Mesin ini belum terdaftar di file FM");
                  kosong_rfs();                       
              }else if(data[0]=="none"){
                  alert("Gagal Simpan, No Mesin ini tidak terdaftar di No Shipping List "+data[1]);
                  kosong_rfs();                  
              }else if(data[0]=="lokasi"){
                  alert("Gagal Simpan, Lokasi tidak tersedia.");
                  kosong_rfs();                                            
              }else{
                alert(data[0]);
                kosong_rfs();
              }                
          }
      })    
  }
}
function simpan_nrfs(){
  var id_pu        = document.getElementById("id_pu").value;  
  var nrfs_text    = document.getElementById("nrfs_text").value;     
  //alert(id_po);
  if (id_pu == "" || nrfs_text == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h1/penerimaan_unit/save_nrfs')?>",
          type:"POST",
          data:"nrfs_text="+nrfs_text+"&id_pu="+id_pu,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="ok"){
                  kirim_data_nrfs();
                  kosong_rfs();
                  cek_sisa();
                  //cetak('JM31E1046121'); 
                  cetak_stiker(null,nrfs_text);               
              }else if(data[0]=="no"){
                  alert("Gagal Simpan, No Mesin ini sudah di-scan sebelumnya");
                  kosong_rfs();                  
              }else if(data[0]=="tipe"){
                  alert("Gagal Simpan, Tipe Kendaraan belum terdaftar di database");
                  kosong_rfs();                  
              }else if(data[0]=="warna"){
                  alert("Gagal Simpan, Warna belum terdaftar di database");
                  kosong_rfs();             
              }else if(data[0]=="item"){
                  alert("Gagal Simpan, Kode Item belum terdaftar di database");
                  kosong_rfs();             
              }else if(data[0]=="ksu"){
                  alert("Gagal Simpan, Tipe Kendaraan ini belum terdaftar di tabel KSU");
                  kosong_rfs();            
              }else if(data[0]=="FM"){
                  alert("Gagal Simpan, No Mesin ini belum terdaftar di file FM");
                  kosong_rfs();                                  
              }else if(data[0]=="none"){
                  alert("Gagal Simpan, No Mesin ini tidak terdaftar di No Shipping List");
                  kosong_rfs();                  
              }else if(data[0]=="lokasi"){
                  alert("Gagal Simpan, Lokasi tidak tersedia.");
                  kosong_rfs();                              
              }else{
                alert(data[0]);
                kosong_rfs();
              }                
          }
      })    
  }
}
function cetak(a){ 
  var no_mesin   = a;  
  window.location.href= "h1/penerimaan_unit/cetak_s?id="+no_mesin;
  
}
</script>
<script type="text/javascript">
var rfs_text = document.getElementById("rfs_text");
rfs_text.addEventListener("keydown", function (e) {
    if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
        simpan_rfs();
    }
});
var nrfs_text = document.getElementById("nrfs_text");
nrfs_text.addEventListener("keydown", function (e) {
    if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
        simpan_nrfs();
    }
});
</script>
<script type="text/javascript">
// $(function () {
//   $('#example4').DataTable({
//     "paging": true,
//     "lengthChange": true,
//     "searching": true,
//     "ordering": true,
//     "info": true,
//     "scrollX":true,
//     fixedHeader:true,
//     "lengthMenu": [[10, 25, 50,75,100, -1], [10, 25, 50,75,100, "All"]],
//     "autoWidth": true
//   });
// });

/// Connection ///
function launchQZ() {
    if (!qz.websocket.isActive()) {
        window.location.assign("qz:launch");
        //Retry 5 times, pausing 1 second between each attempt
        startConnection({ retries: 5, delay: 1 });
    }
}
function findDefaultPrinter(set) {
    qz.printers.getDefault().then(function(data) {
        displayMessage("<strong>Found:</strong> " + data);
        if (set) {setPrinter(data);}
    }).catch(displayError);
}
function findPrinters() {
    qz.printers.find().then(function(data) {
        var list = '';
        form_.pilihPrinter='all';
        form_.optAllPrinter= [];
        if (form_.pilihPrinter=='all') {
            for(var i = 0; i < data.length; i++) {
                var option = {text:data[i],value:data[i]};
                form_.optAllPrinter.push(option)
            }
        }
    }).catch(displayError);
}
function startConnection(config) {
    if (!qz.websocket.isActive()) {
        updateState('Waiting', 'default');

        qz.websocket.connect(config).then(function() {
            updateState('Active', 'success');
            findVersion();
            findDefaultPrinter(true);
        }).catch(handleConnectionError);

    } else {
        displayMessage('An active connection with QZ already exists.', 'alert-warning');
    }
}
function endConnection() {
    if (qz.websocket.isActive()) {
        qz.websocket.disconnect().then(function() {
            updateState('Inactive', 'default');
        }).catch(handleConnectionError);
    } else {
        displayMessage('No active connection with QZ exists.', 'alert-warning');
    }
}
/// Helpers ///
function handleConnectionError(err) {
    updateState('Error', 'danger');

    if (err.target != undefined) {
        if (err.target.readyState >= 2) { //if CLOSING or CLOSED
            displayError("Connection to QZ Tray was closed");
        } else {
            displayError("A connection error occurred, check log for details");
            console.error(err);
        }
    } else {
        displayError(err);
    }
}
var qzVersion = 0;
function findVersion() {
    qz.api.getVersion().then(function(data) {
        $("#qz-version").html(data);
        qzVersion = data;
    }).catch(displayError);
}
function updateState(text, css) {
    $("#qz_status").text('Status : '+text);
    console.log(text);
    // $("#qz-connection").text(text);

    // if (text === "Inactive" || text === "Error") {
    //     $("#launch").show();
    // } else {
    //     $("#launch").hide();
    // }
}
function displayError(err) {
    console.error(err);
    displayMessage(err, 'alert-danger');
}
function displayMessage(msg, css) {
    if (css == undefined) { css = 'alert-info'; }

    var timeout = setTimeout(function() { $('#' + timeout).alert('close'); }, 5000);

    var alert = $("<div/>").addClass('alert alert-dismissible fade in ' + css)
            .css('max-height', '20em').css('overflow', 'auto')
            .attr('id', timeout).attr('role', 'alert');
    alert.html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + msg);
    console.log(msg);
    // $("#qz-alert").append(alert);
}

var cfg = null;
function getUpdatedConfig() {
    if (cfg == null) {
        cfg = qz.configs.create(null);
    }
    updateConfig();
    return cfg
}

function updateConfig() {
    var copies = 1;
    var jobName = null;
    cfg.reconfigure({
      copies: copies,
      jobName: jobName,
      units: 'mm',
      rasterize: "false"
  });
}
function setPrinter(printer) {
    var cf = getUpdatedConfig();
    cf.setPrinter(printer);

    if (printer && typeof printer === 'object' && printer.name == undefined) {
        var shown;
        if (printer.file != undefined) {
            shown = "<em>FILE:</em> " + printer.file;
        }
        if (printer.host != undefined) {
            shown = "<em>HOST:</em> " + printer.host + ":" + printer.port;
        }

        $("#configPrinter").html('Printer : '+shown);
    } else {
        if (printer && printer.name != undefined) {
            printer = printer.name;
        }

        if (printer == undefined) {
            printer = 'NONE';
        }
        $("#configPrinter").html('Printer : '+printer);
    }
}
function cetak_stiker(el=null,id) {
  // console.log(el);
  var values = {id:id}
  $.ajax({
    beforeSend: function() {
      $(el).attr('disabled',true);
    },
     url:"<?php echo base_url('h1/penerimaan_unit/cetak_s');?>",
    type:"POST",
    data: values,
    cache:false,
    success:function(response){
        // console.log(response)
        directPrint(response);
        $(el).attr('disabled',false);
    },
    error:function(){
      alert("failure");
        $(el).attr('disabled',false);
    },
    statusCode: {
      500: function() { 
        alert('fail');
        $(el).attr('disabled',false);
      }
    }
  });
}
function directPrint(html) {
  var config    = getUpdatedConfig();  
  var printData = [
      {
          type: 'html',
          format: 'plain',
          data: html
      }
  ];
  qz.print(config, printData).catch(displayError);
}
</script>