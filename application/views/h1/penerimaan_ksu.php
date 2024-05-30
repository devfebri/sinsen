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
<!-- <body onload="kirim_data_pu()"> -->
<body>
<?php }else{ ?>
<body>
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
  if($set=="penerimaan_oem"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
        <a href="h1/penerimaan_ksu/">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button>
          </a> 
          <a href="h1/penerimaan_ksu/scan_oem?id=<?=$this->input->get('id');?>">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-bolt" aria-hidden="true"></i>  Scan OEM</button>
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
              <th>No</th>
              <th>No.SL</th>              
              <th>Tgl.SL</th>    
              <th>Kode MD</th>              
              <th>Type</th>
              <th>ID Part</th>
              <th>Nama Part</th>
              <th>Serial Number</th>
              <th>Tgl Terima MD</th>
              <!-- <th width="10%">Action</th> -->
            </tr>
          </thead>
          <tbody> 
                      
          <?php 
          $no=1; 
          foreach($dt_shipping_list as $isi) {  ?>
            <tr>
              <td  width="2%"><?=$no++?></td>                      
              <td><?=$isi->no_shipping_list?></td> 
              <td><?=$isi->tgl_shipping_list?></td> 
              <td>E20</td> 
              <td>B</td> 
              <td><?=$isi->part_id?></td> 
              <td><?=$isi->part_desc?></td> 
              <td><?=$isi->serial_number?></td> 
              <td><?=$isi->tgl_penerimaan?></td>
          </tr>
         <? }?>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->


    <?php
    }
    else if($set=="insert"){
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
    }  else if($set=="detail_oem"){
      ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
  
          <a href="h1/penerimaan_ksu/">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button>
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
                <th>No</th>
                <th>Tipe </th>    
                <th>Deskripsi</th>    
                <th>Stok MD</th>              
              </tr>
            </thead>
            <tbody> 
                        
            <?php 
            $no=1; 
            foreach($dt_shipping_list as $isi) {  ?>
              <tr>
                <td  width="2%"><?=$no++?></td>                      
                <td>B</td> 
                <td>Battery</td> 
                <td><?=$isi->qty?></td> 
            </tr>
           <? }?>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
  
  
      <?php
      }

else if($set=="scan_oem"){
?>

<div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/penerimaan_ksu/?>">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-arrow-left"></i> Back</button>
          </a>
          <a href="/h1/penerimaan_ksu/scan_oem?id=<?=$this->input->get('id')?>"><button class="btn btn-default btn-flat" type="button"><i class="fa fa-refresh"></i> Refresh</button></a>          
          <button class="btn btn-success btn-flat" type="button" id="rfsButton"><i class="fa fa-qrcode"></i> RFS</button>
          <button class="btn bg-maroon btn-flat" type="button"   id="nrfsButton"><i class="fa fa-qrcode"></i> NRFS</button>

        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-flat btn-sm btn-info" disabled style="font-weight: bold;font-size: 11pt" id="qz_status">Status : </button>
              <button class="btn btn-flat btn-sm btn-success" disabled style="font-weight: bold;font-size: 11pt" id="configPrinter">Printer : </button>
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->

      <div class="box-body">


      <div class="row" >
          <div class="col-md-12">
            <div class="box-body">              
              <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Tgl Penerimaan</label>
            <div class="col-sm-2">
              <input type="text" class="form-control" disabled value="<?=date('Y-m-d')?>" id="tanggal" placeholder="Tanggal penerimaan" id="tgl_penerimaan">                    
            </div>                                                          
              </div>                
            </div>
          </div>

       </div>

        <div class="row" >

          <div class="col-md-12">
            <div class="box-body">              
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Penerimaan </label>
                <div class="col-sm-3">
                  <input type="text" class="form-control"  id="peneriamaan_get" value="<?=$this->input->get('id')?>" readonly>                    
                </div>                                                          
              </div>                
            </div>
          </div>
        </div>


        <div class="scan-barcode">
        <div class="row" >
          <div class="col-md-12">
            <div class="box-body">              
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label scan_barcode" >Scan Barcode </label>
                <div class="col-sm-6">
                <input type="hidden" id="ready_sale" placeholder="Scan Barcode" name="no_barcode" maxlength="20">
                <input type="text" class="form-control barcodeInput" autofocus id="barcodeInput" placeholder="Scan Barcode" name="no_barcode" maxlength="20">
                </div>                                                          
                <div class="col-sm-2">
                  <button type="button" class="btn btn-primary btn-flat btn-md button-show-modal-oem"  data-toggle="modal" data-target=".modal_detail" onclick="detail_scan_ev('<?= $this->input->get('id');?>')"><i class="fa fa-check"></i> Browse</button>
                </div>                                                          
              </div>                
            </div>
          </div>
        </div>

        </div>

        <div class="box panel-scan-barcode">
          <div class="box-header with-border">
            <h3 class="box-title">
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

        <table class="table table-bordered table-hover data-table-scan" id='data-table'>
              <thead>
                <tr>
                <th width="2%">No</th>
                  <th>No Shipping List</th>
                  <th>Tanggal Shipping List</th>
                  <th>Kode MD</th>
                  <th>Type</th>
                  <th>Kode Part</th>              
                  <th>Nama Part</th>              
                  <th>Serial Number</th>    
                  <th>Tgl Terima MD</th>    
                  <th>FIFO</th>    
                  <th width="2%">Status Ready</th>
                </tr>
              </thead>
              <tbody> 
             </tbody>     
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->

        
    <div class="modal fade modal_detail">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Search Item</h4>
            </div>
            <div class="modal-body" id="show_scan" style="width: 100%; height: 100%;">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Tutup</button>
            </div>
          </div>
        </div>
      </div>   


      <script>
        $( document ).ready(function() {
          $(".scan-barcode").hide();

          var ready_sale  = $('#ready_sale').val();

          $(".panel-scan-barcode").hide();
          
          show_scan_ev();
          
          $("#rfsButton").click(function() {
            $(".scan-barcode").show();
            $(".panel-scan-barcode").show();
            var rfsBarcodeValue = "rfs";
            $("#ready_sale").val(rfsBarcodeValue);
            $(".scan_barcode").text('Scan Barcode RFS');
          });

          $("#nrfsButton").click(function() {
            $(".scan-barcode").show();
            $(".panel-scan-barcode").show();
            var nrfsBarcodeValue = "nrfs";
            $("#ready_sale").val(nrfsBarcodeValue);
            $(".scan_barcode").text('Scan Barcode NRFS');
          });

          });

          $('.barcodeInput').keypress(function(event){
            if(event.which === 13) { // Check if the pressed key is Enter (key code 13)
              var barcodeValue = $(this).val().trim();
              show_scan(barcodeValue);
            }
          });


        function show_scan_ev(id){
          var ready_sale  = $('#ready_sale').val();
          var penerimaan_oem = $('#peneriamaan_get').val();
          var i = 1;

          $.ajax({
              url:"<?php echo site_url('h1/penerimaan_ksu/show_scan');?>",
              type:"POST",
              data: {
                id:id,
                penerimaan_oem: penerimaan_oem,
                ready_sale:ready_sale
              },
              cache:false,
              success: function(response) {
                var tableBody = $('.data-table-scan tbody');
                tableBody.empty();
                  $.each(response.data, function(index, item){
                    var row = $('<tr></tr>');
                    row.append('<td>' + i++ + '</td>');
                    row.append('<td>' + item.no_shipping_list + '</td>');
                    row.append('<td>' + item.tgl_shipping_list + '</td>');
                    row.append('<td>E20</td>');
                    row.append('<td>B</td>');
                    row.append('<td>' + item.part_id + '</td>');
                    row.append('<td>' + item.part_desc + '</td>');
                    row.append('<td>' + item.serial_number + '</td>');
                    row.append('<td>' + item.created_at + '</td>');
                    row.append('<td>' + item.fifo + '</td>');
                    row.append('<td>' + item.status_ready + '</td>');
                    tableBody.append(row);
                   $(".panel-scan-barcode").show();
                  });
                }
          });
        }

        function detail_scan_ev(id){
          var penerimaan_oem = $('#peneriamaan_get').val();
          $.ajax({
            url:"<?php echo site_url('h1/penerimaan_ksu/detail_scan_ev');?>",
            type:"POST",
            data:{
              id:id,
              penerimaan_oem: penerimaan_oem 
            },
            cache:false,
            success:function(html){
                $("#show_scan").html(html);
            }
          });
        }

        function choose_serial_number(id){
          show_scan_ev(id);
        }

        </script>
        <?php
      }
      elseif($set == 'sticker_oem'){
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
                $.ajax("<?=base_url('') ?>assets/tray/assets/signing/sign-message.php?request=" + toSign).then(resolve, reject);
    
                //Alternate method - unsigned
                // resolve();
            };
        });

    </script>
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h1/penerimaan_ksu">
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
                  <th width="5%">No</th>
                  <th>Tipe Part</th>              
                  <th>ID Part</th>              
                  <th>Nama Part</th>              
                  <th>Serial Number</th>              
                  <th width="5%">Action</th>              
                </tr>
              </thead>
              <tbody>            
              <?php 
              $no=1; 
       
              foreach($cetak_sticker as $row) {  ?>
                <tr>
                  <td><?=$no?></td>
                  <td>B</td>
                  <td><?=$row->part_id?></td>
                  <td><?=$row->part_desc?></td>
                  <td><?=$row->serial_number?></td>              
                  <td>
                    <button  name="cetak" type="button" class="btn bg-maroon btn-flat btn-sm" onclick="cetak_stiker_oem(this,'<?= $row->serial_number?>')" title='Print Direct'>
                      <i class="fa fa-print"></i>
                    </button>
                    <form action="/h1/penerimaan_ksu/print_stiker_oem?id=<?= $row->serial_number?>" method="post">
                        <button type="submit" class="btn bg-yellow btn-flat btn-sm"  title='Show Print'><i class="fa fa-print"></i>  </button>
                    </form>
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

        <script>
          function cetak(a){ 
            var serial_number   = a;  
            window.location.href= "h1/penerimaan_ksu/print_stiker_oem?id="+serial_number;
          }
    
          function cetak_stiker_oem(el=null,id) {
            // console.log(el);
            var values = {id:id}
            $.ajax({
              beforeSend: function() {
                $(el).attr('disabled',true);
              },
              url:"<?php echo base_url('h1/penerimaan_ksu/print_stiker_oem');?>",
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
        <?php 
        }
     else if($set == 'ksu'){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/penerimaan_ksu">
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
        <form action="h1/penerimaan_ksu/save_ksu" method="post">          
        <button type="submit" onclick="return confirm('Are you sure to save all data?')" class="btn btn-primary btn-flat pull-right"><i class="fa fa-save"></i> Save All</button>          
        <input type="hidden" value="<?php echo $_GET['id'] ?>" name="id_pu">
        <br><br>
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>
              <th>No SL</th>
              <th>Tipe</th>              
              <th>Warna</th>              
              <th>Kode Item</th>
              <th>Jumlah Unit</th>
              <th>KSU</th>
              <th>Qty Kekurangan AHM</th>
              <th>Qty Kekurangan Ekspedisi</th>
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
                  $nu = 1;
                  foreach ($cek->result() as $isi) {                    
                    $cek2 = $this->db->query("SELECT id_ksu,ksu FROM ms_ksu WHERE id_ksu = '$isi->id_ksu'");
                    if(count($cek2) > 0){
                      $rd = $cek2->row();
                      $nonu = $no."_".$nu;
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
                            <input type='text' readonly onkeypress='return number_only(event)' value='$tt' id='isi_ksu_$nonu' name='qty[]' class='input-group-addon input-group-sm input-block' style='width:50px;'>
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
                            <input type='text' readonly onkeypress='return number_only(event)' value='$ui->qty' id='isi_ksu_$nonu' name='qty[]' class='input-group-addon input-block' style='width:50px;'>
                          </div> <br>";                        
                      }
                    }
                    $nu++;
                  }                  
                }
                echo "
                </td>
                <td>";
                $cek = $this->db->query("SELECT id_ksu FROM ms_koneksi_ksu_detail INNER JOIN ms_koneksi_ksu ON ms_koneksi_ksu.id_koneksi_ksu = ms_koneksi_ksu_detail.id_koneksi_ksu WHERE ms_koneksi_ksu.id_tipe_kendaraan = '$row->tipe_motor'");
                if(count($cek) > 0){
                  $isi = $cek->row();
                  $ni = 1;
                  foreach ($cek->result() as $isi) {                                      
                    $cek2 = $this->db->query("SELECT id_ksu,ksu FROM ms_ksu WHERE id_ksu = '$isi->id_ksu'");
                    if(count($cek2) > 0){
                      $rd = $cek2->row();
                      $noni = $no."_".$ni;
                      $rty = $this->db->query("SELECT * FROM tr_penerimaan_ksu WHERE id_ksu = '$rd->id_ksu' AND id_warna = '$row->id_warna' AND no_sl = '$row->no_shipping_list' AND id_tipe_kendaraan = '$row->tipe_motor'");
                      if($rty->num_rows() == 0){
                        echo "
                           <div class='input-group'>
                            <span class='input-group-addon bg-maroon'>$rd->ksu</span>   
                            <input type='hidden' id='total_unit_$no' value='$total_unit'>                                                                              
                            <input type='text' onkeypress='return number_only(event)' onchange='ubah_qty($no,$ni)' id='ahm_$noni' value='0' name='qty_ahm[]' class='input-group-addon input-block' style='width:50px;'>
                          </div> <br>";                        
                      }else{
                        $ui = $rty->row();
                        echo "
                           <div class='input-group'>
                            <span class='input-group-addon bg-maroon'>$rd->ksu</span>                                                     
                            <input type='hidden' id='total_unit_$no' value='$total_unit'>                                                                              
                            <input type='text' onkeypress='return number_only(event)' onchange='ubah_qty($no,$ni)' id='ahm_$noni' value='$ui->qty_ahm' name='qty_ahm[]' class='input-group-addon input-block' style='width:50px;'>
                          </div> <br>";                        
                      }
                    }
                    $ni++;
                  }                  
                }
                echo "
                </td>
                <td>";
                $cek = $this->db->query("SELECT id_ksu FROM ms_koneksi_ksu_detail INNER JOIN ms_koneksi_ksu ON ms_koneksi_ksu.id_koneksi_ksu = ms_koneksi_ksu_detail.id_koneksi_ksu WHERE ms_koneksi_ksu.id_tipe_kendaraan = '$row->tipe_motor'");
                if(count($cek) > 0){
                  $isi = $cek->row();
                  $ne = 1;
                  foreach ($cek->result() as $isi) {                    
                    $cek2 = $this->db->query("SELECT id_ksu,ksu FROM ms_ksu WHERE id_ksu = '$isi->id_ksu'");
                    if(count($cek2) > 0){
                      $rd = $cek2->row();
                      $none = $no."_".$ne;
                      $rty = $this->db->query("SELECT * FROM tr_penerimaan_ksu WHERE id_ksu = '$rd->id_ksu' AND id_warna = '$row->id_warna' AND no_sl = '$row->no_shipping_list' AND id_tipe_kendaraan = '$row->tipe_motor'");
                      if($rty->num_rows() == 0){
                        echo "
                           <div class='input-group'>
                            <span class='input-group-addon bg-maroon'>$rd->ksu</span>                                                     
                            <input type='text' onkeypress='return number_only(event)' onchange='ubah_qty_2($no,$ne)' id='eks_$none' value='0' name='qty_eks[]' class='input-group-addon input-block' style='width:50px;'>
                          </div> <br>";                        
                      }else{
                        $ui = $rty->row();
                        echo "
                           <div class='input-group'>
                            <span class='input-group-addon bg-maroon'>$rd->ksu</span>                                                     
                            <input type='text' onkeypress='return number_only(event)' onchange='ubah_qty_2($no,$ne)' id='eks_$none' value='$ui->qty_eks' name='qty_eks[]' class='input-group-addon input-block' style='width:50px;'>
                          </div> <br>";                        
                      }
                    }
                    $ne++;
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

            
            $cek = $this->m_admin->getByID("tr_penerimaan_ksu","id_penerimaan_unit",$row->id_penerimaan_unit);
            $st = "";
            if($cek->num_rows() > 0){
              $st = "<span class='label label-success'>saved</span>";
            }
            echo "
            <tr>
              <td>$no</td>
              <td>$row->id_penerimaan_unit $st</td>
              <td>$row->no_antrian</td>
              <td>$row->tgl_surat_jalan</td>
              <td>$vendor_name</td>
              <td>$row->no_polisi</td>
              <td>$row->nama_driver</td>
              <td>$row->tgl_penerimaan</td>";              
              if($row->status == 'close scan'){
                echo "  
                <td>  
                
                
                  <a href='h1/penerimaan_ksu/ksu?id=$row->id_penerimaan_unit'>
                    <button class='btn btn-flat btn-xs btn-success'><i class='fa fa-suitcase'></i> Penerimaan KSU</button>              
                  </a>"; ?>
                  
                  <a href='h1/penerimaan_ksu/close_ksu?id=<?php echo $row->id_penerimaan_unit ?>'>
                    <button onclick="return confirm('Are you sure?')" class='btn btn-flat btn-xs btn-danger'><i class='fa fa-close'></i> Close KSU</button>              
                  </a>

                  <!-- <a href="h1/penerimaan_ksu/scan_oem?id=<?$row->id_penerimaan_unit ?>">
                      <button class='btn btn-flat btn-xs btn-primary'><i class="fa fa-battery-full" aria-hidden="true"></i></i> Penerimaan OEM</button>
                  </a>   -->

                </td>

                                    
           
              <?php               
              }elseif($row->status == 'close'){
                echo "<td>closed</td>";
              }else{
                echo "<td></td>";
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
    }
    elseif($set=="view_serverside"){
      ?>
  
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">     
          <a href="h1/penerimaan_ksu/history">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history" aria-hidden="true"></i> History</button>
          </a> 

          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
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
        
          <table id="tbl_set_oem" class="table table-bordered table-hover">
            <thead>
              <tr>
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
            </tbody>
          </table>
        </div>
      </div>

      <script>
      $(document).ready(function() {
          const mode = '<?php echo $mode; ?>'; 
          $('#tbl_set_oem').DataTable({
              "searching": true,
              "language": {
                  "lengthMenu": "Display _MENU_ records per page",
                  "zeroRecords": "Nothing found - sorry",
                  "infoEmpty": "No records available",
                  "infoFiltered": ""
              },
              "processing": true,
              "serverSide": true,
              "order": [],
              "ajax": {
                  "url": "<?php echo site_url('/h1/penerimaan_ksu/fetch_penerimaan_oem'); ?>",
                  "type": "POST",
                  "data": {
                      "mode": mode
                  }
              },
              "columnDefs": [{
                  "targets": [0],
                  "orderable": true
              }]
          });
      });
      </script>


      
    <?}
    ?>
  </section>
</div>

<script type="text/javascript">
function ubah_qty(no,ni){  
  var a = $("#total_unit_"+no+"").val();
  var c = $("#ahm_"+no+"_"+ni+"").val();
  var d = $("#eks_"+no+"_"+ni+"").val();  
  var b = a - c - d;
  if(b < 0 || b > a){
    alert("Jumlah yang anda inputkan tidak sesuai ketentuan, mohon ulangi lagi!");        
    $("#ahm_"+no+"_"+ni+"").val("");  
    $("#ahm_"+no+"_"+ni+"").focus();  
  }else{
    $("#isi_ksu_"+no+"_"+ni+"").val(b);
  }  
}
function ubah_qty_2(no,ni){  
  var a = $("#total_unit_"+no+"").val();
  var c = $("#ahm_"+no+"_"+ni+"").val();
  var d = $("#eks_"+no+"_"+ni+"").val();  
  var b = a - c - d;
  if(b < 0 || b > a){
    alert("Jumlah yang anda inputkan tidak sesuai ketentuan, mohon ulangi lagi!");        
    $("#eks_"+no+"_"+ni+"").val("");  
    $("#eks_"+no+"_"+ni+"").focus();
  }else{
    $("#isi_ksu_"+no+"_"+ni+"").val(b);
  }  
}
</script>