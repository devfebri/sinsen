<?php
function bln()
{
  $bulan = $bl = $month = date("m");
  switch ($bulan) {
    case "1":
      $bulan = "Januari";
      break;
    case "2":
      $bulan = "Februari";
      break;
    case "3":
      $bulan = "Maret";
      break;
    case "4":
      $bulan = "April";
      break;
    case "5":
      $bulan = "Mei";
      break;
    case "6":
      $bulan = "Juni";
      break;
    case "7":
      $bulan = "Juli";
      break;
    case "8":
      $bulan = "Agustus";
      break;
    case "9":
      $bulan = "September";
      break;
    case "10":
      $bulan = "Oktober";
      break;
    case "11":
      $bulan = "November";
      break;
    case "12":
      $bulan = "Desember";
      break;
  }
  $bln = $bulan;
  return $bln;
}
?>
<style type="text/css">
  .myTable1 {
    margin-bottom: 0px;
  }

  .myt {
    margin-top: 0px;
  }

  .isi {
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
        <li class="">Penerimaan Unit</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?> <span id="ubah_label"></span></li>
      </ol>
    </section>
    <section class="content">
  
    <?php
   if($set=="oem_scan"){
    $row = $dt_pu->row();
?>

<style>
      .hidden-input {
        border: none;
          background: none;
      }
</style>

<div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/konfirmasi_pu/">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-arrow-left"></i> Back</button>
          </a>
          <a href="/dealer/konfirmasi_pu/oem_scan?id=<?=$this->input->get('id')?>"><button class="btn btn-default btn-flat" type="button"><i class="fa fa-refresh"></i> Refresh</button></a>          
        
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
      <div class="row" >
          <div class="col-md-12">
            <div class="box-body">              
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" disabled value="<?=$row->nama_dealer ?>" id="nama_dealer">                    
                    </div>   
                    
                    <div class="sumber-kerusakan">
                    <label for="inputEmail3" class="col-sm-2 control-label">Sumber Kerusakan</label>
                       <div class="col-sm-4">
                          <select name="sumber_kerusakan" class="form-control sumber_kerusakan_ev" id="sumber_kerusakan_ev">
                            <option value="">--choose--</option>
                            <option value="MD">Main Dealer</option>
                            <option value="ekspedisi">Ekspedisi</option>
                          </select>
                       </div>     
                    </div>                                                        
                </div>                
              </div>
            </div>

            <div class="col-md-12">
            <div class="box-body">              
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control no_surat_jalan_ev" value="<?=$row->no_surat_jalan ?>"  placeholder="Tanggal penerimaan"  readonly>                    
                    </div>        
                </div>                
              </div>
            </div>

            <div class="col-md-12">
            <div class="box-body">              
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Penerimaan</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" disabled value="<?=date('Y-m-d')?>" id="tanggal" placeholder="Tanggal penerimaan" id="tgl_penerimaan">                    
                    </div>                                                          
                </div>                
              </div>
            </div>
   

        <div class="scan-barcode">
          <div class="col-md-12">
            <div class="box-body">              
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label scan_barcode">Scan Barcode </label>
                <div class="col-sm-6">
                <input type="hidden" id="ready_sale" placeholder="Scan Barcode" name="no_barcode" maxlength="20">
                <input type="text" class="form-control barcodeInput" autofocus id="barcodeInput" placeholder="Scan Barcode" name="no_barcode" maxlength="20">
                </div>                                                          
                <div class="col-sm-2">
                  <button type="button" class="btn btn-primary btn-flat btn-md"  data-toggle="modal" data-target=".modal_detail" onclick="detail_scan_ev('<?= $this->input->get('id');?>')"><i class="fa fa-check"></i> Browse</button>
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
                    <th>No</th>
                    <th>Tipe</th>    
                    <th>Kode Part </th>    
                    <th>Nama Part</th>    
                    <th>Serial Number</th>    
                    <th>FIFO</th>    
                    <th>Status</th>    
                  </tr>
                </thead>
                <tbody> 
              </tbody>     
            </table>
          </div>
        </div>

        
        <div class="modal fade modal_detail">
        <div class="modal-dialog modal-lg" style="max-width: 800px;">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title">Search Item</h4>
            </div>
            <div class="modal-body" id="show_scan">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Tutup</button>
            </div>
          </div>
        </div>

      <script>
        $( document ).ready(function() {
          $(".scan-barcode").hide();
          var ready_sale  = $('#ready_sale').val();
          $(".panel-scan-barcode").hide();
          $(".sumber-kerusakan").hide();
          
          show_scan_ev();
          
          $("#rfsButton").click(function() {
            $(".scan-barcode").show();
            $(".panel-scan-barcode").show();
            var rfsBarcodeValue = "rfs";
            $("#ready_sale").val(rfsBarcodeValue);
            $(".scan_barcode").text('Scan Barcode RFS');
            $(".sumber-kerusakan").hide();
          });

          $("#nrfsButton").click(function() {
            $(".sumber-kerusakan").show();
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
          // var penerimaan_oem = $('#peneriamaan_get').val();
          var no_surat_jalan = $('.no_surat_jalan_ev').val();
          var sumber_kerusakan_ev = $('.sumber_kerusakan_ev').val();
          var i = 1;

          $.ajax({
              url:"<?php echo site_url('dealer/konfirmasi_pu/show_scan_battery');?>",
              type:"POST",
              data: {
                id:id,
                // penerimaan_oem: penerimaan_oem,
                ready_sale:ready_sale,
                no_surat_jalan:no_surat_jalan,
                sumber_kerusakan_ev:sumber_kerusakan_ev,
              },

              cache:false,
              success: function(response) {
                var tableBody = $('.data-table-scan tbody');
                tableBody.empty();
                  $.each(response.data, function(index, item){
                    var row = $('<tr></tr>');
                    row.append('<td>' + i++ + '</td>');
                    row.append('<td>B</td>');
                    row.append('<td>' + item.part_id + '</td>');
                    row.append('<td>' + item.part_desc + '</td>');
                    row.append('<td>' + item.serial_number + '</td>');
                    row.append('<td>' + item.fifo + '</td>');
                    row.append('<td>' + item.ready_for_sale.toUpperCase() + '</td>');
                    tableBody.append(row);
                   $(".panel-scan-barcode").show();
                  });
                }
          });
        }

        function detail_scan_ev(id){

          var penerimaan_oem = $('#peneriamaan_get').val();
          $.ajax({
            url:"<?php echo site_url('dealer/konfirmasi_pu/detail_scan_ev');?>",
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
      }else if ($set == 'unit') {
        $row = $dt_pu->row();
      ?>
        <script>
          $(document).on("keypress", "form", function(event) {
            return event.keyCode != 13;
          });
        </script>
        <div class="modal fade modalPart" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">AHASS</h4>
              </div>
              <div class="modal-body">
                <input type="hidden" id="no_mesin_part">
                <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_part" style="width: 100%">
                  <thead>
                    <tr>
                      <th>ID Part</th>
                      <th>Nama Part</th>
                      <th>Kel. Vendor</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
                <script>
                  $(document).ready(function() {
                    $('#tbl_part').DataTable({
                      processing: true,
                      serverSide: true,
                      "language": {
                        "infoFiltered": ""
                      },
                      order: [],
                      ajax: {
                        url: "<?= base_url('master/kpb/fetch_part') ?>",
                        dataSrc: "data",
                        data: function(d) {
                          // d.kode_item     = $('#kode_item').val();
                          return d;
                        },
                        type: "POST"
                      },
                      "columnDefs": [
                        // { "targets":[4],"orderable":false},
                        {
                          "targets": [2],
                          "className": 'text-center'
                        },
                        // { "targets":[4], "searchable": false } 
                      ]
                    });
                  });
                </script>
              </div>
            </div>
          </div>
        </div>
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="dealer/konfirmasi_pu">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
              </a>

              <button type="button" onclick="set_rfs()" class="btn btn-flat btn-success"><i class="fa fa-qrcode"></i> RFS</button>
              <button type="button" onclick="set_nrfs()" class="btn btn-flat btn-warning"><i class="fa fa-qrcode"></i> NRFS</button>

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
            <div id="row">
              <div class="col-md-12">
                <form class="form-horizontal" action="dealer/konfirmasi_pu/save" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <input type="hidden" readonly class="form-control" id="id_penerimaan_unit_dealer" placeholder="ID Konfirmasi Unit" name="id_penerimaan_unit_dealer">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                      <div class="col-sm-4">
                        <input type="hidden" id="jenis_pu" name="jenis_pu">
                        <input type="hidden" value="<?php echo $_GET['id'] ?>" id="id_surat_jalan" name="id_surat_jalan">
                        <input type="text" value="<?php echo $row->nama_dealer ?>" required class="form-control" id="nama_dealer" readonly placeholder="Nama Dealer" name="nama_dealer">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl Penerimaan</label>
                      <div class="col-sm-4">
                        <input type="text" required class="form-control" id="tanggal" value="<?php echo date("Y-m-d") ?>" placeholder="Tgl Penerimaan" name="tgl_penerimaan">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                      <div class="col-sm-4">
                        <input type="text" value="<?php echo $row->no_surat_jalan ?>" required class="form-control" id="no_surat_jalan" readonly placeholder="No Surat Jalan" name="no_surat_jalan">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat Jalan</label>
                      <div class="col-sm-4">
                        <input type="text" value="<?php echo $row->tgl_surat ?>" required class="form-control" id="tgl_surat" readonly placeholder="Tgl Surat Jalan" name="tgl_surat">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
                      <div class="col-sm-4">
                        <input type="text" value="<?php echo $row->no_do ?>" required class="form-control" id="no_do" readonly placeholder="No DO" name="no_do">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl DO</label>
                      <div class="col-sm-4">
                        <input type="text" value="<?php echo $row->tgl_do ?>" required class="form-control" id="tgl_do" readonly placeholder="Tgl DO" name="tgl_do">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Lokasi Penyimpanan</label>
                      <div class="col-sm-4">
                        <input type="hidden" name="mode" value="" id="mode">
                        <select class="form-control" name="id_gudang_dealer" required id="id_gudang_dealer">
                          <?php

                          /* $cek = $this->m_admin->getByID("tr_penerimaan_unit_dealer","no_surat_jalan",$row->no_surat_jalan)->row();
                      if(isset($cek->id_gudang_dealer)){
                        echo "<option value='$cek->id_gudang_dealer'>$cek->id_gudang_dealer</option>";
                      }else{
                        echo "<option value=''>- choose -</option>";
                      } */
                          ?>
                          <?php
                          $id_dealer = $this->m_admin->cari_dealer();
                          $dt_gudang = $this->db->query("SELECT * FROM ms_gudang_dealer WHERE id_dealer = '$id_dealer' AND active = 1 ORDER BY gudang ASC");
                          foreach ($dt_gudang->result() as $row) {
                            echo "<option value='$row->gudang'>$row->gudang</option>";
                          }
                          ?>
                        </select>
                      </div>
                      <!-- <div class="col-sm-1">
                    <a href='dealer/konfirmasi_pu/gudang?id=<?php echo $_GET['id'] ?>' class="btn btn-flat btn-primary"><i class='fa fa-plus'></i> Gudang</a>
                  </div> -->
                      <span id="scan_nosin">
                        <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="no_mesin" placeholder="No Mesin" name="no_mesin" maxlength="13" minlength="13">
                        </div>
                        <div class="col-sm-1">
                          <!-- <button onclick="simpan_nosin()" type="button" class="btn btn-primary btn-flat btn-md"><i class="fa fa-save"></i> Simpan</button>                       -->
                          <!-- <button type="button" data-toggle="modal" data-target="#Scanmodal" class="btn btn-primary btn-flat btn-md"><i class="fa fa-check"></i> Browse</button>        -->
                          <button type="button" onclick="getScanModal()" class="btn btn-primary btn-flat btn-md"><i class="fa fa-check"></i> Browse</button>
                        </div>
                      </span>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Terima</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" readonly value="" id='qty_terima'>
                      </div>
                      <span id="sumber_kerusakan_div">
                        <label for="inputEmail3" class="col-sm-2 control-label">Sumber Kerusakan</label>
                        <div class="col-sm-4">
                          <select name="sumber_kerusakan" class="form-control" id="sumber_kerusakan">
                            <option value="">--choose--</option>
                            <option value="MD">Main Dealer</option>
                            <option value="ekspedisi">Ekspedisi</option>
                          </select>
                        </div>
                      </span>
                    </div>
                  </div>
              </div>
            </div>
            <span id="tampil_data"></span>
          </div><!-- /.box-body -->
          <div class="box-footer">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-10">
              <button type="submit" name="save" onclick="return confirm('Are you sure to save all this data?')" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
              <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>
            </div>
          </div><!-- /.box-footer -->
        </div><!-- /.box -->
        </form>

      <?php
      } elseif ($set == 'ksu') {
        $ksu = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_sppm ON tr_surat_jalan.no_surat_sppm = tr_sppm.no_surat_sppm                 
        WHERE tr_surat_jalan.id_surat_jalan = '$_GET[id]'")->row();
        $sj = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan = '$ksu->no_surat_jalan'")->row();
        if (isset($sj)) {
          $tgl = $sj->tgl_penerimaan;
        } else {
          $tgl = "";
        }
        $row = $dt_ksu->row();
        if (isset($row->id_penerimaan_unit_dealer)) {
          $id_p = $row->id_penerimaan_unit_dealer;
        } else {
          $id_p = "";
        }
      ?>

        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="dealer/konfirmasi_pu">
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
            <div id="row">
              <div class="col-md-12">
                <form class="form-horizontal" action="dealer/konfirmasi_pu/save_ksu" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <input type="hidden" name="id_penerimaan_unit_dealer" value="<?php echo $id_p ?>">
                      <input type="hidden" name="id_sj" value="<?php echo $_GET['id'] ?>">
                      <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                      <div class="col-sm-4">
                        <input type="text" required class="form-control" value="<?php echo $ksu->no_surat_jalan ?>" readonly placeholder="No Surat Jalan" name="no_surat_jalan">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat Jalan</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" value="<?php echo $ksu->tgl_surat ?>" readonly placeholder="Tgl Surat Jalan" name="tgl_surat">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" value="<?php echo $ksu->no_do ?>" readonly placeholder="No DO" name="no_do">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl DO</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" value="<?php echo $ksu->tgl_do ?>" readonly placeholder="Tgl DO" name="tgl_do">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl Terima</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" value="<?php echo $tgl ?>" readonly placeholder="Tgl Terima" name="tgl_penerimaan">
                      </div>
                    </div>
                  </div>
              </div>
            </div>
            <table id="" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                  <th width="5%">No</th>
                  <th>Kode KSU</th>
                  <th>Aksesoris</th>
                  <th>Qty Supply MD</th>
                  <th width="5%">Qty Penerimaan</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                foreach ($ksu_d->result() as $row) {
                  // $item = $this->db->query("SELECT * FROM ms_item WHERE id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND id_warna = '$row->id_warna'")->row();
                  $id_ksu = $this->db->query("SELECT * FROM ms_ksu WHERE id_ksu = '$row->id_ksu'")->row();
                  $id_p = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer INNER JOIN tr_surat_jalan ON tr_penerimaan_unit_dealer.no_surat_jalan=tr_surat_jalan.no_surat_jalan 
                 WHERE tr_surat_jalan.id_surat_jalan = '$id_surat_jalan'");
                  if ($id_p->num_rows() > 0) {
                    $t = $id_p->row();
                    $id_penerimaan_unit_dealer = $t->id_penerimaan_unit_dealer;
                  } else {
                    $id_penerimaan_unit_dealer = "";
                  }
                  $cek = $this->db->query("SELECT * FROM tr_penerimaan_ksu_dealer WHERE id_penerimaan_unit_dealer = '$id_penerimaan_unit_dealer' 
                    AND id_ksu = '$row->id_ksu'");
                  if ($cek->num_rows() > 0) {
                    $i = $cek->row();
                    $isi = "value='$i->qty_terima'";
                  } else {
                    $isi = "";
                  }
                  if ($row->jum > 0) {
                    echo "
                <tr>
                  <td>$no</td>
                  <td>$row->id_ksu</td>                
                  <td>$row->ksu</td>                
                  <td>$row->jum</td>                                
                  <td>
                    <input type='hidden' name='id_ksu[]' value='$row->id_ksu'>
                    <input type='hidden' name='no_sj[]' value='$ksu->no_surat_jalan'>
                    <input type='hidden' name='qty_md[]' value='$row->jum'>
                    <input type='text' name='qty_terima[]' $isi class='form-control isi' onkeypress=\"return number_only(event)\">
                  </td>
                </tr>
                ";
                    $no++;
                  }
                }
                ?>
              </tbody>
            </table>
          </div><!-- /.box-body -->
          <div class="box-footer">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-10">
              <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
              <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>
            </div>
          </div><!-- /.box-footer -->
        </div><!-- /.box -->
        </form>
      <?php
      } elseif ($set == 'detail') {
        $row = $dt_rfs->row();
        $ro = $dt_pu->row();
      ?>

        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              <?php if (isset($_GET['p'])) { ?>
                <a href="dealer/konfirmasi_pu">
                <?php } else { ?>
                  <a href="dealer/konfirmasi_pu/history">
                  <?php } ?>
                  <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
                  </a>
            </h3>
            <div class="box-tools pull-right">
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
          </div><!-- /.box-header -->
          <div class="box-body">
            <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" disabled value="<?php echo $ro->no_surat_jalan ?>" id="tanggal" placeholder="Tanggal penerimaan" id="tgl_penerimaan">
            </div>
            <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>
            <div class="col-sm-4">
              <?php
              if (isset($ro->tgl_penerimaan)) {
                $tg = $ro->tgl_penerimaan;
              } else {
                $tg = "";
              }
              ?>
              <input type="text" class="form-control" disabled value="<?php echo $tg ?>" id="tanggal" placeholder="Tanggal penerimaan" id="tgl_penerimaan">
            </div>
          </div>
          <div class="box-body">
            <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" disabled value="<?php echo $ro->id_gudang_dealer ?>" id="tanggal" placeholder="Tanggal penerimaan" id="tgl_penerimaan">
            </div>
            <?php $qty_kirim = $this->db->query("SELECT count(no_mesin) as c FROM tr_surat_jalan_detail WHERE no_surat_jalan='$ro->no_surat_jalan'")->row()->c; ?>
            <?php $qty_terima = $this->db->query("SELECT count(no_mesin) as c FROM tr_penerimaan_unit_dealer_detail
             JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer
             WHERE no_surat_jalan='$ro->no_surat_jalan'")->row()->c; ?>
            <label for="inputEmail3" class="col-sm-1 control-label">Qty Kirim</label>
            <div class="col-sm-2">
              <input type="text" class="form-control" disabled value="<?php echo $qty_kirim ?>">
            </div>
            <label for="inputEmail3" class="col-sm-1 control-label">Qty Terima</label>
            <div class="col-sm-2">
              <input type="text" class="form-control" disabled value="<?php echo $qty_terima ?>">
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
                  <th>Tipe</th>
                  <th>Warna</th>
                  <th>Kode Item</th>
                  <th>FIFO</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                foreach ($dt_rfs->result() as $row) {
                  echo "
              <tr>
                <td>$no</td>
                <td>$row->no_mesin</td>
                <td>$row->no_rangka</td>
                <td>$row->id_tipe_kendaraan | $row->tipe_ahm</td>
                <td>$row->id_warna | $row->warna</td>
                <td>$row->id_item</td>
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
                  <th>Tipe</th>
                  <th>Warna</th>
                  <th>Kode Item</th>
                  <th>FIFO</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                foreach ($dt_nrfs->result() as $row) {
                  echo "
              <tr>
                <td>$no</td>
                <td>$row->no_mesin</td>
                <td>$row->no_rangka</td>
                <td>$row->id_tipe_kendaraan | $row->tipe_ahm</td>
                <td>$row->id_warna | $row->warna</td>
                <td>$row->id_item</td>
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
      } elseif ($set == 'penerimaan') {
        $dt_pu = $dt_pu->row();
        $no_do = $dt_pu->no_do;
      ?>

        <div class="box">
          <div class="box-header with-border">
            <div><br></div>
            <div class="box-tools pull-right">
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
          </div>
          <div class="box-body">
            <div class="form-horizontal">
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">No Penerimaan</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" disabled value="<?= $dt_pu->id_penerimaan_unit_dealer ?>" id="tanggal" placeholder="Tanggal penerimaan">
                </div>
                <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" disabled value="<?= $dt_pu->no_surat_jalan ?>" id="tanggal" placeholder="Tanggal penerimaan">
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Tgl Penerimaan</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" disabled value="<?= $dt_pu->tgl_penerimaan ?>" id="tanggal" placeholder="Tanggal penerimaan">
                </div>
                <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat Jalan</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" disabled value="<?= $dt_pu->tgl_surat_jalan ?>" id="tanggal" placeholder="Tanggal penerimaan">
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-12">
                  <button style="width: 100%;font-size: 12pt" class="btn btn-primary btn-flat" disabled><b>Detail No Mesin Diterima Dealer</b></button>
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
                      <th>Harga Beli Dealer</th>
                    </thead>
                    <tbody>
                      <?php foreach ($detail->result() as $key => $rs) : ?>
                        <tr>
                          <td><?= $key + 1 ?></td>
                          <td><?= $rs->no_mesin ?></td>
                          <td><?= $rs->no_rangka ?></td>
                          <td><?= $rs->id_tipe_kendaraan . ' | ' . $rs->tipe_ahm ?></td>
                          <td><?= $rs->id_warna . ' | ' . $rs->warna ?></td>
                          <?php $harga = $this->db->query("SELECT harga FROM tr_do_po_detail WHERE no_do='$no_do' AND id_item='$rs->id_item' ")->row()->harga; ?>
                          <td align="right"><?= mata_uang_rp($harga) ?></td>
                        </tr>
                      <?php endforeach ?>
                      <tr>
                        <td colspan="5" align="right"><b>Total</b></td>
                        <td align="right"><b><?= $detail->num_rows() ?></b></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-8">
                  <button style="width: 100%;font-size: 12pt" class="btn btn-primary btn-flat" disabled><b>Detail KSU Diterima Dealer</b></button>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-8">
                  <table class="table table-bordered table-condensed table-striped table-hover">
                    <thead>
                      <th>No</th>
                      <th>Nama Aksesoris</th>
                      <th>Qty Terima</th>
                    </thead>
                    <tbody>
                      <?php foreach ($ksu->result() as $key => $rs) : ?>
                        <tr>
                          <td><?= $key + 1 ?></td>
                          <td><?= $rs->ksu ?></td>
                          <td><?= $rs->qty_terima ?></td>
                        </tr>
                      <?php endforeach ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-10">
                  <button style="width: 100%;font-size: 12pt" class="btn btn-primary btn-flat" disabled><b>Detail Invoice</b></button>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-10">
                  <table class="table table-bordered table-condensed table-striped table-hover">
                    <thead>
                      <th>No</th>
                      <th>Item Motor</th>
                      <th>Nama</th>
                      <th>Jumlah</th>
                      <th>Harga Kosong</th>
                      <th>Total</th>
                    </thead>
                    <tbody>
                      <?php $get_d  = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
                      INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
                      INNER JOIN ms_gudang ON tr_do_po.id_gudang = ms_gudang.id_gudang
                      WHERE tr_invoice_dealer.no_do = '$no_do'")->row();
                      $get_nosin  = $this->db->query("SELECT * FROM tr_do_po_detail INNER JOIN ms_item ON tr_do_po_detail.id_item = ms_item.id_item
                    INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                    INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
                    WHERE tr_do_po_detail.no_do = '$no_do' AND tr_do_po_detail.qty_do > 0");
                      $i = 1;
                      $qt = 0;
                      $t = 0;
                      $p = 0;
                      $potongan = 0;
                      $potongan = 0;
                      foreach ($get_nosin->result() as $rs) {
                        echo '<tr>';
                        echo '<td>' . $i . '</td>';
                        echo '<td>' . $rs->id_item . '</td>';
                        echo '<td>' . $rs->deskripsi_ahm . ' / ' . $rs->warna . '</td>';
                        echo '<td>' . $rs->qty_do . '</td>';
                        echo '<td>' . number_format($to = $rs->harga, 0, ',', '.') . '</td>';
                        echo '<td>' . number_format($to = $rs->harga * $rs->qty_do, 0, ',', '.') . '</td>';
                        echo '</tr>';
                        $i++;
                        $qt = $qt + $rs->qty_do;
                        $t = $t + $to;
                        $cek2  = $this->db->query("SELECT SUM(tr_invoice_dealer_detail.potongan) as jum FROM tr_invoice_dealer_detail INNER JOIN tr_do_po_detail ON tr_invoice_dealer_detail.no_do = tr_do_po_detail.no_do
                    WHERE tr_do_po_detail.no_do = '$no_do' AND LEFT(tr_invoice_dealer_detail.id_item,6) = '$rs->id_item'");
                        if ($cek2->num_rows() > 0) {
                          $d = $cek2->row();
                          $po = $d->jum;
                        } else {
                          $po = 0;
                        }

                        $potongan = $potongan + (($rs->disc + $po) * $rs->qty_do);
                        //$p = $p + $potongan;
                      }
                      ?>
                      <tr>
                        <td colspan="3" align="right"><b>Total</b></td>
                        <td><?= $qt ?></td>
                        <td></td>
                        <td><?= number_format($t, 0, ',', '.') ?></td>
                      </tr>
                    </tbody>
                  </table>
                  <table class="table table-bordered table-condensed table-striped table-hover">
                    <?php if ($get_d->dealer_financing == 'Ya') { ?>
                      <tr>
                        <td>Potongan</td>
                        <td><?= number_format($pot = $potongan, 0, ',', '.') ?></td>
                      </tr>
                      <tr>
                        <td>Diskon TOP</td>
                        <td><?php
                            $d = (($t - $pot) - ($bunga_bank / 360 * $top)) / (1 + ((getPPN(1.1,false) * $bunga_bank / 360) * $top));
                            $diskon_top = ($t - $pot) - $d;
                            echo number_format($diskon_top, 0, ',', '.');
                            ?>
                        </td>
                      </tr>
                      <tr>
                        <td>DPP</td>
                        <td><?= number_format($d, 0, ',', '.') ?></td>
                      </tr>
                      <tr>
                        <td>PPn</td>
                        <td><?= number_format($hs = $d * getPPN(0.1,false), 0, ',', '.') ?></td>
                      </tr>
                      <tr>
                        <td>Total Bayar</td>
                        <td>
                          <?= number_format($hs + $d, 0, ',', '.') ?>
                        </td>
                      </tr>
                    <?php } else { ?>
                      <tr>
                        <td>Potongan</td>
                        <td><?= number_format($pot = $potongan, 0, ',', '.') ?></td>
                      </tr>
                      <tr>
                        <td>DPP</td>
                        <td><?= number_format($d = $t - $potongan, 0, ',', '.') ?></td>
                      </tr>
                      <tr>
                        <td>PPn</td>
                        <td><?= number_format($hs = $d * getPPN(0.1, false), 0, ',', '.') ?></td>
                      </tr>
                      <tr>
                        <td>Total Bayar</td>
                        <td>
                          <?= number_format($hs + $d, 0, ',', '.') ?>
                        </td>
                      </tr>
                    <?php } ?>
                  </table>
                </div>
              </div>

            </div><!-- /.box -->

          <?php
        } elseif ($set == "view") {
          ?>

            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/konfirmasi_pu/history">
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
                      <th>ID Konfirmasi Unit</th>
                      <th>ID Goods Receipt</th>
                      <th>Tgl Penerimaan</th>
                      <th>No Surat Jalan MD</th>
                      <th>Tgl Surat Jalan MD</th>
                      <th>Nomor Invoice</th>
                      <th>Status SL</th>
                      <?php /*<th>Status</th>               */ ?>
                      <th width="20%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($dt_sj->result() as $row) {
                      $cek = $this->m_admin->getByID("tr_penerimaan_unit_dealer", "no_surat_jalan", $row->no_surat_jalan);
                      if ($cek->num_rows() > 0) {
                        $id_ = $cek->row();
                        $id_penerimaan_unit_dealer = $id_->id_penerimaan_unit_dealer;
                        $tgl_penerimaan = $id_->tgl_penerimaan;
                        $id_goods_receipt = $id_->id_goods_receipt;
                        $no_do = $id_->no_do;
                        $status_sl = '<label class="label label-info">Received</label>';
                      } else {
                        $id_penerimaan_unit_dealer = "";
                        $tgl_penerimaan = "";
                        $id_goods_receipt = '';
                        $no_do = '';
                        $status_sl = '<label class="label label-warning">Draft</label>';
                      }

                      $check = $this->m_admin->cari_dealer();


                      if ($row->status == 'proses') {
                        $status = "<span class='label label-warning'>$row->status</span>";
                        $tombol = "<a href=\"dealer/konfirmasi_pu/unit?id=$row->id_surat_jalan\">
                          <button class=\"btn btn-flat btn-xs btn-success\"><i class=\"fa fa-check\"></i> Konfirmasi PU</button>
                        </a>";

                        $cek_ev = $this->m_admin->getByID("tr_surat_jalan_battery_detail", "no_surat_jalan", $row->no_surat_jalan);

                        if ($cek_ev->num_rows() > 0) {

                          $tombol .= "                
                          <a href=\"dealer/konfirmasi_pu/oem_scan?id=$row->no_surat_jalan\">
                            <button class=\"btn btn-flat btn-xs btn-primary\"><i class=\"fa fa-battery-full\" ></i> Konfirmasi Battery </button>
                          </a>";
                          }

                        if ($id_penerimaan_unit_dealer != "") {
                    
                          $tombol .= "                
                          <a href=\"dealer/konfirmasi_pu/ksu?id=$row->id_surat_jalan\">
                            <button class=\"btn btn-flat btn-xs btn-primary\"><i class=\"fa fa-download\"></i> Konfirmasi KSU</button>
                          </a>
                          <a href=\"dealer/konfirmasi_pu/cetak_accu?id=$row->id_surat_jalan\">
                            <button class=\"btn btn-flat btn-xs btn-warning\"><i class=\"fa fa-print\"></i> Cetak Stiker ACCU</button>
                          </a>
                          
                          <a href=\"dealer/konfirmasi_pu/close?id=$row->id_surat_jalan&id_pu=$id_penerimaan_unit_dealer\" onclick=\"return confirm('Are you sure to close this data?')\">
                            <button class=\"btn btn-flat btn-xs btn-danger\"><i class=\"fa fa-close\"></i> Close</button>
                          </a>";

                        }
                      } elseif ($row->status == 'close') {
                        $status = "<span class='label label-danger'>$row->status</span>";
                        $tombol = "";
                      }
                      $inv = $this->db->query("SELECT tr_invoice_dealer.no_faktur FROM tr_surat_jalan 
                      INNER JOIN tr_picking_list ON tr_surat_jalan.no_picking_list = tr_picking_list.no_picking_list     
                      LEFT JOIN tr_invoice_dealer ON tr_picking_list.no_do=tr_invoice_dealer.no_do
                      WHERE tr_surat_jalan.no_surat_jalan ='$row->no_surat_jalan'
                      ")->row();
                              echo "
                    <tr>
                      <td>$no</td>
                      <td>
                        <a href='dealer/konfirmasi_pu/view?id=$id_penerimaan_unit_dealer&p=n'>
                          $id_penerimaan_unit_dealer
                        </a>
                      </td>              
                      <td>$id_goods_receipt</td>              
                      <td>$tgl_penerimaan</td>              
                      <td>$row->no_surat_jalan</td>              
                      <td>$row->tgl_surat</td>
                      <td>$inv->no_faktur</td>
                      <td>$status_sl</td> " ?>
                            <?php //<td>$status</td>                                          
                              echo "<td>$tombol</td>
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
        elseif($set=="serverside"){
          ?>
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">
                <a href="dealer/cdb_d/add">
                  <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
                </a>          
                <a href="dealer/cdb_d/gc">
                  <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"view"); ?> class="btn btn-warning btn-flat margin"><i class="fa fa-users"></i> Group Customer</button>
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
              <table id="datatable" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th>No SPK</th>  
                    <th>Nama Customer</th>              
                    <th>Alamat</th>              
                    <th>No HP</th>
                    <th>No KTP</th>  
                    <th>Action</th>            
                  </tr>
                </thead>
                <tbody>            
                
                </tbody>
              </table>
      
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
          <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
      
      
          <script type="text/javascript">
            $(document).ready(function(e){
              $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
              {
                  return {
                      "iStart": oSettings._iDisplayStart,
                      "iEnd": oSettings.fnDisplayEnd(),
                      "iLength": oSettings._iDisplayLength,
                      "iTotal": oSettings.fnRecordsTotal(),
                      "iFilteredTotal": oSettings.fnRecordsDisplay(),
                      "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                      "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                  };
              };
      
              var base_url = "<?php echo base_url() ?>";
              $('#datatable').DataTable({
                 "pageLength" : 10,
                 "serverSide": true,
                 "ordering": true, 
                  "processing": true,
                  "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                    searchPlaceholder: "Pencarian..."
                  },
      
                 "order": [[1, "desc" ]],
                 "rowCallback": function (row, data, iDisplayIndex) {
                      var info = this.fnPagingInfo();
                      var page = info.iPage;
                      var length = info.iLength;
                      var index = page * length + (iDisplayIndex + 1);
                      $('td:eq(0)', row).html(index);
                  },
                 "ajax":{
                          url :  base_url+'dealer/konfirmasi_pu/getData',
                          type : 'POST'
                        },
              }); // End of DataTable
            }); 
          </script>
      
            </div><!-- /.box-body -->
          </div><!-- /.box -->
          <?php 
          }
elseif ($set == "history") {
          ?>

            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/konfirmasi_pu">
                    <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
                      <th>ID Konfirmasi Unit</th>
                      <th>ID Goods Receipt</th>
                      <th>Tgl Penerimaan</th>
                      <th>No Surat Jalan MD</th>
                      <th>Tgl Surat Jalan MD</th>
                      <th>Nomor Invoice</th>
                      <th>Status SL</th>
                      <th width="5%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($dt_sj->result() as $row) {
                      $cek = $this->m_admin->getByID("tr_penerimaan_unit_dealer", "no_surat_jalan", $row->no_surat_jalan);
                      if ($cek->num_rows() > 0) {
                        $id_                       = $cek->row();
                        $id_penerimaan_unit_dealer = $id_->id_penerimaan_unit_dealer;
                        $tgl_penerimaan            = $id_->tgl_penerimaan;
                        $id_goods_receipt          = $id_->id_goods_receipt;
                        $no_do                     = $id_->no_do;
                        $status_sl                 = '<label class="label label-info">Received</label>';
                      } else {
                        $id_penerimaan_unit_dealer = "";
                        $tgl_penerimaan            = "";
                        $id_goods_receipt          = '';
                        $no_do                     = '';
                        $status_sl                 = '<label class="label label-warning">Draft</label>';
                      }

                      $tombol = "                                
              <a href='dealer/konfirmasi_pu/view?id=$id_penerimaan_unit_dealer'>
                <button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-eye'></i> Detail</button>
              </a>";
                      $inv = $this->db->query("SELECT * FROM tr_surat_jalan 
              INNER JOIN tr_picking_list ON tr_surat_jalan.no_picking_list = tr_picking_list.no_picking_list     
              LEFT JOIN tr_invoice_dealer ON tr_picking_list.no_do=tr_invoice_dealer.no_do
              WHERE tr_surat_jalan.no_surat_jalan ='$row->no_surat_jalan'
              ")->row();
                      echo "
            <tr>
              <td>$no</td>
              <td>
                <a href='dealer/konfirmasi_pu/view?id=$id_penerimaan_unit_dealer&p=n'>
                  $id_penerimaan_unit_dealer
                </a>
              </td>              
              <td>$id_goods_receipt</td>              
              <td>$tgl_penerimaan</td>              
              <td>$row->no_surat_jalan</td>              
              <td>$row->tgl_surat</td>
              <td>$inv->no_faktur</td>
              <td>$status_sl</td>                                         
              <td>$tombol</td>
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
        }elseif ($set == "history_server_side") {
          ?>

            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/konfirmasi_pu">
                    <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
                <table id="table_konfirmasi_pu" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th width="5%">No</th>
                      <th>ID Konfirmasi Unit</th>
                      <th>ID Goods Receipt</th>
                      <th>Tgl Penerimaan</th>
                      <th>No Surat Jalan MD</th>
                      <th>Tgl Surat Jalan MD</th>
                      <th>Nomor Invoice</th>
                      <th>Status SL</th>
                      <th width="5%">Action</th>
                    </tr>
                  </thead>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
            <script>
                $( document ).ready(function() {
                $('#table_konfirmasi_pu').DataTable({
                      "scrollX": true,
                      "processing": true, 
                      "bDestroy": true,
                      "serverSide": true,
                      "order": [],
                      "ajax": {
                        "url": "<?php  echo site_url('dealer/konfirmasi_pu/fetchData')?>",
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
elseif ($set == "history_battery") {
        ?>

          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">
                <a href="dealer/konfirmasi_pu/history">
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
                    <th width="5%">No</th>
                    <th>No Surat Jalan MD</th>
                    <th>Kode MD</th>
                    <th>Nama MD</th>
                    <th>Kode Dealer</th>
                    <th>Nama Dealer</th>
                    <th>Tipe Aksesoris</th>
                    <th>ID Part</th>
                    <th>Nama Part</th>
                    <th>Serial Number</th>
                    <th>Tgl Penerimaan Dealer</th>
                    <th>Fifo</th>
                    <th>Status Scan</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                      $no = 1;
                      foreach ($penerimaan_oem->result() as $row) { ?>
                    <tr>
                      <td><?=$no?></td>
                      <td><?=$row->no_surat_jalan?></td>                
                      <td>E20</td>                
                      <td>PT. SINAR SENTOSA PRIMATAMA - ABUNJANI</td>                
                      <td><?=$row->kode_dealer_md?></td>                
                      <td><?=$row->nama_dealer?></td>                
                      <td>B</td>                
                      <td><?=$row->id_part?></td>                
                      <td><?=$row->part_desc?></td>                
                      <td><?=$row->serial_number?></td>                
                      <td><?=$row->created_at?></td>                
                      <td><?=$row->fifo?></td>                
                      <td><?=$row->scan?></td>                
                      </tr>
                <?php }?>
              </tbody>
              </table>
            </div><!-- /.box-body -->
          </div><!-- /.box -->
      <?}
        elseif ($set == 'ksu2') {
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
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" class="btn btn-info btn-flat pull-right"><i class="fa fa-save"></i> Save All</button>
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
                        if ($unit->num_rows() > 0) {
                          $jum = $unit->row();
                          $total_unit = $jum->nosin;
                          $tt = $jum->nosin;
                        } else {
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
                        if (count($cek) > 0) {
                          $isi = $cek->row();
                          foreach ($cek->result() as $isi) {
                            $cek2 = $this->db->query("SELECT id_ksu,ksu FROM ms_ksu WHERE id_ksu = '$isi->id_ksu'");
                            if (count($cek2) > 0) {
                              $rd = $cek2->row();
                              $rty = $this->db->query("SELECT * FROM tr_penerimaan_ksu WHERE id_ksu = '$rd->id_ksu' AND no_sl = '$row->no_shipping_list' AND id_tipe_kendaraan = '$row->tipe_motor'");
                              if ($rty->num_rows() == 0) {
                                echo "
                           <div class='input-group'>
                            <span class='input-group-addon bg-maroon'>$rd->ksu</span>                         
                            <input type='hidden' name='id_ksu[]' value='$rd->id_ksu'>
                            <input type='hidden' name='tipe_motor[]' value='$row->tipe_motor'>
                            <input type='hidden' name='no_sl[]' value='$row->no_shipping_list'>
                            <input type='text' onkeypress='return number_only(event)' value='$tt' name='qty[]' class='input-group-addon input-block' style='width:50px;'>
                          </div> <br>";
                              } else {
                                $ui = $rty->row();
                                echo "
                           <div class='input-group'>
                            <span class='input-group-addon bg-maroon'>$rd->ksu</span>                         
                            <input type='hidden' name='id_ksu[]' value='$rd->id_ksu'>
                            <input type='hidden' name='tipe_motor[]' value='$row->tipe_motor'>
                            <input type='hidden' name='no_sl[]' value='$row->no_shipping_list'>
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
        } elseif ($set == 'cetak_accu') {
          $ksu = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_sppm ON tr_surat_jalan.no_surat_sppm = tr_sppm.no_surat_sppm                 
        WHERE tr_surat_jalan.id_surat_jalan = '$_GET[id]'")->row();
          $sj = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan = '$ksu->no_surat_jalan'")->row();
          if (isset($sj)) {
            $tgl = $sj->tgl_penerimaan;
          } else {
            $tgl = "";
          }
          $row = $dt_ksu->row();
          if (isset($row->id_penerimaan_unit_dealer)) {
            $id_p = $row->id_penerimaan_unit_dealer;
          } else {
            $id_p = "";
          }
          ?>

            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/konfirmasi_pu">
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
                <div id="row">
                  <div class="col-md-12">
                    <form class="form-horizontal" action="dealer/konfirmasi_pu/save_ksu" method="post" enctype="multipart/form-data">
                    </form>
                  </div>
                </div>
                <table id="" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                      <th width="5%">No</th>
                      <th>Kode KSU</th>
                      <th>Aksesoris</th>
                      <th>Qty Terima</th>
                      <th width="5%">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($ksu_d->result() as $row) {
                      // $item = $this->db->query("SELECT * FROM ms_item WHERE id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND id_warna = '$row->id_warna'")->row();
                      $id_ksu = $this->db->query("SELECT * FROM ms_ksu WHERE id_ksu = '$row->id_ksu'")->row();
                      $id_p = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer INNER JOIN tr_surat_jalan ON tr_penerimaan_unit_dealer.no_surat_jalan=tr_surat_jalan.no_surat_jalan 
                 WHERE tr_surat_jalan.id_surat_jalan = '$id_surat_jalan'");
                      if ($id_p->num_rows() > 0) {
                        $t = $id_p->row();
                        $id_penerimaan_unit_dealer = $t->id_penerimaan_unit_dealer;
                      } else {
                        $id_penerimaan_unit_dealer = "";
                      }
                      $cek = $this->db->query("SELECT * FROM tr_penerimaan_ksu_dealer WHERE id_penerimaan_unit_dealer = '$id_penerimaan_unit_dealer' 
                    AND id_ksu = '$row->id_ksu'");
                      if ($cek->num_rows() > 0) {
                        $i = $cek->row();
                        $isi = $i->qty_terima;
                      } else {
                        $isi = "";
                      }
                      if ($row->jum > 0) {
                        echo "
                <tr>
                  <td>$no</td>
                  <td>$row->id_ksu</td>                
                  <td>$row->ksu</td>                
                  <td>$isi</td>
                  <td>
                    <a href='dealer/konfirmasi_pu/cetak_act?id=$row->id_ksu&id_p=$id_penerimaan_unit_dealer' class='btn btn-primary btn-xs btn-flat'><i class='fa fa-print'></i> cetak</a>
                  </td>                                                  
                </tr>
                ";
                        $no++;
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->
            </form>


          <?php
        } elseif ($set == "gudang") {
          ?>

            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/konfirmasi_pu/unit?id=<?php echo $_GET['id'] ?>">
                    <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
                <div id="row">
                  <div class="col-md-12">
                    <form class="form-horizontal" action="dealer/konfirmasi_pu/gudang_save" method="post" enctype="multipart/form-data">
                      <div class="box-body">
                        <div class="form-group">
                          <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Gudang</label>
                          <div class="col-sm-4">
                            <input type="text" required class="form-control" placeholder="Nama Gudang" name="gudang">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Kapasitas</label>
                          <div class="col-sm-4">
                            <input type="text" required class="form-control" placeholder="Kapasitas" name="kapasitas">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="field-1" class="col-sm-2 control-label"></label>
                          <div class="col-sm-2">
                            <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                              <input type="checkbox" class="flat-red" name="active" value="1" checked>
                              Active
                            </div>
                          </div>
                        </div>
                      </div><!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-10">
                          <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                          <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>
                        </div>
                      </div><!-- /.box-footer -->
                    </form>
                  </div>
                </div>
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                      <th width="5%">No</th>
                      <th>Gudang</th>
                      <th>Kapasitas</th>
                      <th width="10%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    $id_dealer = $this->m_admin->cari_dealer();
                    $dt_gudang = $this->db->query("SELECT * FROM ms_gudang_dealer WHERE id_dealer = '$id_dealer' AND active = 1");
                    foreach ($dt_gudang->result() as $row) {
                      echo "
            <tr>
              <td>$no</td>
              <td>$row->gudang</td>              
              <td>$row->kapasitas </td>            
              <td>";
                    ?>
                      <a data-toggle='tooltip' <?php echo $this->m_admin->set_tombol($id_menu, $group, "delete"); ?> title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="dealer/konfirmasi_pu/gudang_delete?id=<?php echo $_GET['id'] ?>&idg=<?php echo $row->id_gudang_dealer ?>"><i class="fa fa-trash-o"></i></a>
                      <a data-toggle='tooltip' <?php echo $this->m_admin->set_tombol($id_menu, $group, "edit"); ?> title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='dealer/konfirmasi_pu/gudang_edit?id=<?php echo $_GET['id'] ?>&idg=<?php echo $row->id_gudang_dealer ?>'><i class='fa fa-edit'></i></a>
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
        } elseif ($set == "gudang_show_on_menu_lokasi_penyimpanan") {
          ?>

            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/konfirmasi_pu/gudang_show">
                    <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
                <div id="row">
                  <div class="col-md-12">
                    <form class="form-horizontal" action="dealer/konfirmasi_pu/gudang_save" method="post" enctype="multipart/form-data">
                      <div class="box-body">
                        <div class="form-group">

                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Gudang</label>
                          <div class="col-sm-4">
                            <input type="text" required class="form-control" placeholder="Nama Gudang" name="gudang">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Kapasitas</label>
                          <div class="col-sm-4">
                            <input type="text" required class="form-control" placeholder="Kapasitas" name="kapasitas">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="field-1" class="col-sm-2 control-label"></label>
                          <div class="col-sm-2">
                            <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                              <input type="checkbox" class="flat-red" name="active" value="1" checked>
                              Active
                            </div>
                          </div>
                        </div>
                      </div><!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-10">
                          <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                          <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>
                        </div>
                      </div><!-- /.box-footer -->
                    </form>
                  </div>
                </div>
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                      <th width="5%">No</th>
                      <th>Gudang</th>
                      <th>Kapasitas</th>
                      <th width="10%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    $id_dealer = $this->m_admin->cari_dealer();
                    $dt_gudang = $this->db->query("SELECT * FROM ms_gudang_dealer WHERE id_dealer = '$id_dealer' AND active = 1");
                    foreach ($dt_gudang->result() as $row) {
                      echo "
            <tr>
              <td>$no</td>
              <td>$row->gudang</td>              
              <td>$row->kapasitas </td>            
              <td>";
                    ?>
                      <a data-toggle='tooltip' <?php echo $this->m_admin->set_tombol($id_menu, $group, "delete"); ?> title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="dealer/konfirmasi_pu/gudang_delete?id=&idg=<?php echo $row->id_gudang_dealer ?>"><i class="fa fa-trash-o"></i></a>
                      <a data-toggle='tooltip' <?php echo $this->m_admin->set_tombol($id_menu, $group, "edit"); ?> title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='dealer/konfirmasi_pu/gudang_edit?id=&idg=<?php echo $row->id_gudang_dealer ?>'><i class='fa fa-edit'></i></a>
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
        } elseif ($set == "gudang_edit") {
          $row = $dt_gudang->row();
          ?>

            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="dealer/konfirmasi_pu/gudang?id=<?php echo $_GET['id'] ?>">
                    <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
                <div id="row">
                  <div class="col-md-12">
                    <form class="form-horizontal" action="dealer/konfirmasi_pu/gudang_update" method="post" enctype="multipart/form-data">
                      <div class="box-body">
                        <div class="form-group">
                          <input type="hidden" name="idg" value="<?php echo $row->id_gudang_dealer ?>">
                          <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Gudang</label>
                          <div class="col-sm-4">
                            <input type="text" required class="form-control" value="<?php echo $row->gudang ?>" placeholder="Nama Gudang" name="gudang">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Kapasitas</label>
                          <div class="col-sm-4">
                            <input type="text" required class="form-control" value="<?php echo $row->kapasitas ?>" placeholder="Kapasitas" name="kapasitas">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="field-1" class="col-sm-2 control-label"></label>
                          <div class="col-sm-2">
                            <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                              <?php
                              if ($row->active == '1') {
                              ?>
                                <input type="checkbox" class="flat-red" name="active" value="1" checked>
                              <?php } else { ?>
                                <input type="checkbox" class="flat-red" name="active" value="1">
                              <?php } ?>
                              Active
                            </div>
                          </div>
                        </div>
                      </div><!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-10">
                          <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update</button>
                          <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>
                        </div>
                      </div><!-- /.box-footer -->
                    </form>
                  </div>
                </div>
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                      <th width="5%">No</th>
                      <th>Gudang</th>
                      <th>Kapasitas</th>
                      <th width="10%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    $id_dealer = $this->m_admin->cari_dealer();
                    $dt_gudang = $this->db->query("SELECT * FROM ms_gudang_dealer WHERE id_dealer = '$id_dealer' AND active = 1");
                    foreach ($dt_gudang->result() as $row) {
                      echo "
            <tr>
              <td>$no</td>
              <td>$row->gudang</td>              
              <td>$row->kapasitas</td>"; ?>
                      <td>
                        <a data-toggle='tooltip' <?php echo $this->m_admin->set_tombol($id_menu, $group, "delete"); ?> title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="dealer/konfirmasi_pu/gudang_delete?id=<?php echo $_GET['id'] ?>&idg=<?php echo $row->id_gudang_dealer ?>"><i class="fa fa-trash-o"></i></a>
                        <a data-toggle='tooltip' <?php echo $this->m_admin->set_tombol($id_menu, $group, "edit"); ?> title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='dealer/konfirmasi_pu/gudang_edit?id=<?php echo $_GET['id'] ?>&idg=<?php echo $row->id_gudang_dealer ?>'><i class='fa fa-edit'></i></a>
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

  <div class="modal fade" id="Scanmodal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          Search Item
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" id="showScan">

        </div>
      </div>
  </div>

  <script type="text/javascript">
    function auto() {
      set_rfs();
      var no_sj = document.getElementById("id_surat_jalan").value;
      $.ajax({
        url: "<?php echo site_url('dealer/konfirmasi_pu/cari_id') ?>",
        type: "GET",
        data: "no_sj=" + no_sj,
        cache: false,
        success: function(msg) {
          data = msg.split("|");
          if (data[2] != 'nihil') {
            $("#id_penerimaan_unit_dealer").val(data[2]);
            $("#mode").val("edit");
            $("#jenis_pu").val("rfs");
            kirim_data();
          } else if (data[1] != 'nihil') {
            //alert("Terdapat transaksi data sebelumnya yg belum selesai dg ID Penerimaan "+data[1]+". Hapus data sebelumnya dan mulai transaksi data baru?");
            //hapus_auto(data[1]);                            
            $("#id_penerimaan_unit_dealer").val(data[1]);
            $("#mode").val("new");
            $("#jenis_pu").val("rfs");
            kirim_data();
          } else {
            $("#id_penerimaan_unit_dealer").val(data[0]);
            $("#jenis_pu").val("");
            //$("#tampil_data").hide();
            //$("#scan_nosin").hide();
            $("#mode").val("new");
            $("#jenis_pu").val("rfs");
            kirim_data();
          }
        }
      })
    }

    function set_rfs() {
      $("#jenis_pu").val("rfs");
      kirim_data();
      $("#scan_nosin").show();
      $('#sumber_kerusakan_div').hide();
    }

    function set_nrfs() {
      $("#jenis_pu").val("nrfs");
      isi_part();
      // kirim_data();
      $("#scan_nosin").show();
      $('#sumber_kerusakan_div').show();
    }

    function kirim_data() {
      $("#tampil_data").show();
      var id_pu = document.getElementById("id_penerimaan_unit_dealer").value;
      var jenis_pu = document.getElementById("jenis_pu").value;
      var no_sj = document.getElementById("no_surat_jalan").value;
      var id_sj = document.getElementById("id_surat_jalan").value;
      var xhr;
      if (window.XMLHttpRequest) { // Mozilla, Safari, ...
        xhr = new XMLHttpRequest();
      } else if (window.ActiveXObject) { // IE 8 and older
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
      }
      //var data = "birthday1="+birthday1_js;          
      var data = "id_pu=" + id_pu + "&jenis_pu=" + jenis_pu + "&no_sj=" + no_sj + "&id_sj=" + id_sj;
      xhr.open("POST", "dealer/konfirmasi_pu/t_data", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send(data);
      xhr.onreadystatechange = display_data;

      function display_data() {
        if (xhr.readyState == 4) {
          if (xhr.status == 200) {
            document.getElementById("tampil_data").innerHTML = xhr.responseText;
            getTerima(id_pu);
          } else {
            alert('There was a problem with the request.');
          }
        }
      }
    }

    function getTerima(id_pu) {
      $.ajax({
        url: "<?php echo site_url('dealer/konfirmasi_pu/getTerima') ?>",
        type: "POST",
        data: "id_pu=" + id_pu,
        cache: false,
        success: function(msg) {
          $('#qty_terima').val(msg);
        }
      })
    }

    function choose_nosin(no_mesin) {
      document.getElementById("no_mesin").value = no_mesin;
      simpan_nosin();
      $("#Scanmodal").modal("hide");
    }

    function simpan_nosin() {
      var id_pu = document.getElementById("id_penerimaan_unit_dealer").value;
      var no_mesin = document.getElementById("no_mesin").value;
      var jenis_pu = document.getElementById("jenis_pu").value;
      var no_do = document.getElementById("no_do").value;
      var id_sj = document.getElementById("id_surat_jalan").value;
      var sumber_kerusakan = $('#sumber_kerusakan').val();
      //alert(id_po);
      var panjang = no_mesin.length;
      if (jenis_pu == 'nrfs') {
        if (sumber_kerusakan == '') {
          alert('Silahkan Pilih Sumber Kerusakan !');
          return false
        }
      }
      if ((id_pu == "" || no_mesin == "") && panjang != 12) {
        alert("Isikan data dengan lengkap...!");
        return false;
      } else if (panjang != 12) {
        alert("Tuliskan No Mesin dengan benar...!");
        return false;
      } else {
        $.ajax({
          url: "<?php echo site_url('dealer/konfirmasi_pu/save_nosin') ?>",
          type: "POST",
          data: "no_mesin=" + no_mesin + "&id_pu=" + id_pu + "&no_do=" + no_do + "&jenis_pu=" + jenis_pu + "&id_sj=" + id_sj + "&sumber_kerusakan=" + sumber_kerusakan,
          cache: false,
          success: function(msg) {
            data = msg.split("|");
            if (data[0] == "ok") {
              kirim_data();
              kosong_nosin();
            } else {
              alert(data[0]);
              kosong_nosin();
            }
            // else if(data[0]=="sudah"){

            // }                
          }
        })
      }
    }

    function kosong_nosin(args) {
      $("#no_mesin").val("");
    }

    function hapus_data(a, b, c) {
      var id_pu = a;
      var no_mesin = b;
      var mode = c;
      $.ajax({
        url: "<?php echo site_url('dealer/konfirmasi_pu/delete_data') ?>",
        type: "POST",
        data: "id_pu=" + id_pu + "&no_mesin=" + no_mesin + "&mode=" + mode,
        cache: false,
        success: function(msg) {
          data = msg.split("|");
          if (data[0] == "nihil") {
            kirim_data();
          }
        }
      })
    }

    function hapus_auto(a) {
      var id_p = a;
      $.ajax({
        url: "<?php echo site_url('dealer/konfirmasi_pu/hapus_auto') ?>",
        type: "POST",
        data: "id_p=" + id_p,
        cache: false,
        success: function(msg) {
          data = msg.split("|");
          auto();
          window.location.reload();
        }
      })
    }
  </script>

  
  <script type="text/javascript">
    var no_mesin = document.getElementById("no_mesin");
    no_mesin.addEventListener("keydown", function(e) {
      if (e.keyCode === 13) { //checks whether the pressed key is "Enter"
        simpan_nosin();
        //alert("ok");

      }
    });


    function getScanModal() {
      sumber_kerusakan = $('#sumber_kerusakan').val();
      jenis_pu = $('#jenis_pu').val();
      if (jenis_pu == 'nrfs') {
        if (sumber_kerusakan == '') {
          alert('Silahkan Pilih Sumber Kerusakan !');
          return false
        }
      }
      var value = {
        scan: 'ya',
        id: $("#id_surat_jalan").val()
      }
      $.ajax({
        beforeSend: function() {
          $('#loading-status').show();
        },
        url: "<?php echo site_url('dealer/konfirmasi_pu/getScanModal') ?>",
        type: "POST",
        data: value,
        cache: false,
        success: function(html) {
          $('#loading-status').hide();
          $('#Scanmodal #showScan').html(html);
          $('#Scanmodal').modal('show');
          // datatables();
        },
        statusCode: {
          500: function() {
            $('#loading-status').hide();
            alert("Something Wen't Wrong");
          }
        }
      });
    }

    function showModalPart(no_mesin) {
      $('.modalPart').modal('show');
      $('.modalPart #no_mesin_part').val(no_mesin)
    }

    function datatables() {
      $('#datatables').DataTable({
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        order: [
          [1, "desc"]
        ],
        autoWidth: true
      });
    }

    function pilihPart(part) {
      var no_mesin = $('.modal #no_mesin_part').val()
      $('#id_part_' + no_mesin).val(part.id_part);
    }

    function addPart(no_mesin) {
      var id_part = $('#id_part_' + no_mesin).val();
      var qty_part = $('#qty_part_' + no_mesin).val();
      if (id_part == '' || qty_part == '') {
        alert('Silahkan isi data dengan lengkap !');
        return false;
      }
      var values = {
        no_mesin: no_mesin,
        id_part: id_part,
        qty_part: qty_part
      }
      $.ajax({
        beforeSend: function() {
          $('#loading-status').show();
        },
        url: "dealer/konfirmasi_pu/addPart",
        type: "POST",
        data: values,
        cache: false,
        success: function(data) {
          $('#loading-status').hide();
          if (data == 'sukses') {
            kirim_data();
          }
        },
        statusCode: {
          500: function() {
            $('#loading-status').hide();
            alert("Something Wen't Wrong");
          }
        }
      });
    }

    function isi_part() {
      var id_pu = document.getElementById("id_penerimaan_unit_dealer").value;
      var values = {
        id_pu: id_pu
      }
      $.ajax({
        beforeSend: function() {
          $('#loading-status').show();
        },
        url: "dealer/konfirmasi_pu/isi_part",
        type: "POST",
        data: values,
        cache: false,
        success: function(data) {
          $('#loading-status').hide();
          kirim_data()
        },
        statusCode: {
          500: function() {
            $('#loading-status').hide();
            alert("Something Wen't Wrong");
          }
        }
      });
    }

    function delPart(rowid) {
      var values = {
        rowid: rowid
      };
      $.ajax({
        beforeSend: function() {
          $('#loading-status').show();
        },
        url: "dealer/konfirmasi_pu/delPart",
        type: "POST",
        data: values,
        cache: false,
        success: function(data) {
          $('#loading-status').hide();
          if (data == 'sukses') {
            kirim_data();
          }
        },
        statusCode: {
          500: function() {
            $('#loading-status').hide();
            alert("Something Wen't Wrong");
          }
        }
      });
    }

    function setNeedParts(no_mesin) {
      var need_parts = $('#need_parts_' + no_mesin).val();
      values = {
        need_parts: need_parts,
        no_mesin: no_mesin
      }
      $.ajax({
        // beforeSend: function() {
        //   $('#gnrtBtn').attr('disabled',true);
        // },
        url: '<?= base_url('dealer/konfirmasi_pu/setNeedParts') ?>',
        type: "POST",
        data: values,
        cache: false,
        dataType: 'JSON',
        success: function(response) {

        },
        error: function() {
          alert("Error");
          // $('#gnrtBtn').attr('disabled',false);
        },
        statusCode: {
          500: function() {
            alert('Error Code 500');
            // $('#gnrtBtn').attr('disabled',false);
          }
        }
      });
    }
  </script>

  <script>
          
        $(document).ready(function(e){
        $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
        {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        };

        var base_url = "<?php echo base_url() ?>"; // You can use full url here but I prefer like this
        $('#datatable').DataTable({
           "pageLength" : 10,
           "serverSide": true,
           "ordering": true, // Set true agar bisa di sorting
            "processing": true,
            "language": {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
              searchPlaceholder: "Pencarian..."
            },

           "order": [[1, "desc" ]],
           "rowCallback": function (row, data, iDisplayIndex) {
                var info = this.fnPagingInfo();
                var page = info.iPage;
                var length = info.iLength;
                var index = page * length + (iDisplayIndex + 1);
                $('td:eq(0)', row).html(index);
            },
           "ajax":{
                    url :  base_url+'dealer/cdb_d/getData',
                    type : 'POST'
                  },
        }); // End of DataTable


      }); 
  </script>