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
<?php
if (isset($_GET['id'])) {
?>

  <body onload="kirim_data_niguri_v()">
  <?php } else { ?>

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
          <li class="">H2</li>
          <li class="">KPB</li>
          <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
        </ol>
      </section>
      <section class="content">
        <?php
        if ($set == "insert") {
        ?>

          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">
                <a href="h2/ptcd">
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
                  <form class="form-horizontal" action="h2/ptcd/save" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                        <div class="col-sm-2">
                          <input type="text" id="tanggal" required class="form-control" placeholder="Start Date" name="tipe" id="tipe">
                        </div>
                        <div class="col-sm-2"></div>
                        <label for="inputEmail3" class="col-sm-2 control-label">Kode AHASS</label>
                        <div class="col-sm-2">
                          <input type="text" name="" class="form-control" readonly>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                        <div class="col-sm-2">
                          <input type="text" class="form-control" id="tanggal1" placeholder="End Date" name="">
                        </div>
                        <div class="col-sm-2"></div>
                        <label for="inputEmail3" class="col-sm-2 control-label">Nama AHASS</label>
                        <div class="col-sm-4">
                          <input type="text" name="" class="form-control" readonly>
                        </div>


                      </div>
                      <div class="form-group">
                        <p align="center">
                          <div class="col-sm-4"></div>
                          <div class="col-sm-2"><button class="btn btn-primary btn-flat form-control">Generate</button></div>
                        </p>
                      </div>
                      <br>
                      <table id="example4" class="table   table-hover">
                        <thead>
                          <th>Kode Dealer</th>
                          <th>Nama Dealer</th>
                          <th>Kode Part</th>
                          <th>Kode Tipe</th>
                          <th>Qty</th>
                          <th>Harga</th>
                          <th>Diskon</th>
                          <th>Harga Setelah Diskon</th>
                          <th>Total Harga</th>
                        </thead>
                        <tbody>
                          <?php foreach ($dt->result() as $rs) : ?>
                            <tr>
                              <td><?= $rs->kode_dealer_md ?></td>
                              <td><?= $rs->nama_dealer ?></td>
                              <td><?= $rs->id_part ?></td>
                              <td><?= $rs->nama_part ?></td>
                              <td><?= $rs->jumlah ?></td>
                              <td><?= $rs->harga ?></td>
                              <td>0</td>
                              <td><?= $rs->harga ?></td>
                              <td><?= $rs->harga * $rs->jumlah ?></td>
                            </tr>
                          <?php endforeach ?>
                        </tbody>
                      </table>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                      <div class="col-sm-4">
                      </div>
                      <div class="col-sm-8">
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
        } elseif ($set == "upload") {
        ?>

          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">
                <a href="h2/ptcd">
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
                  <form class="form-horizontal" action="h2/ptcd/import_db" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Choose File</label>
                        <div class="col-sm-10">
                          <input type="file" accept=".xlsx" required class="form-control" autofocus name="userfile">
                        </div>
                      </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                      <div class="col-sm-2">
                      </div>
                      <div class="col-sm-10">
                        <button type="submit" onclick="return confirm('Are you sure to import this data?')" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Start Upload</button>
                      </div>
                    </div><!-- /.box-footer -->
                  </form>
                </div>
              </div>
            </div>
          </div><!-- /.box -->




        <?php
        } elseif ($set == "view") {
        ?>

          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">
                <?php /* ?> <a href="h2/ptcd/upload">
            <button class="btn btn-info btn-flat margin"><i class="fa fa-upload"></i> Upload</button>
          </a>   <?php */ ?>
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
              <table id="example4" class="table table-hover">
                <thead>
                  <tr>
                    <th>No Registrasi Claim</th>
                    <th>No LBPC</th>
                    <th>Kode AHASS</th>
                    <th>Nama AHASS</th>
                    <th>No Mesin</th>
                    <th>No Rangka</th>
                    <th>Tanggal Pembelian</th>
                    <th>Tanggal Kerusakan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  foreach ($dt_result as $rs) {
                    echo "
            <tr>
              <td>$rs->id_rekap_claim</td>
              <td>$rs->no_lbpc</td>
              <td>$rs->kode_dealer_md</td>
              <td>$rs->nama_dealer</td>
              <td>$rs->no_mesin</td>
              <td>$rs->no_rangka</td>
              <td>$rs->tgl_pembelian</td>
              <td>$rs->tgl_kerusakan</td>  
            </tr> ";
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

    <div class="modal fade" id="nosin_modal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            Search No Mesin
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <table id="example5" class="table table-hover">
              <thead>
                <tr>
                  <th width="5%">No</th>
                  <th>No Mesin</th>
                  <th>Tipe</th>
                  <th>Warna</th>
                  <th>Lokasi</th>
                  <th>Status</th>
                  <th width="1%"></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                $dt_nosin = $this->db->query("SELECT tr_scan_barcode.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_warna.id_warna 
                  FROM tr_scan_barcode INNER JOIN ms_tipe_kendaraan ON 
                  tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna ON
                  tr_scan_barcode.warna = ms_warna.id_warna WHERE tr_scan_barcode.status = '1' ORDER BY tr_scan_barcode.no_mesin,tr_scan_barcode.tipe ASC");
                foreach ($dt_nosin->result() as $ve2) {
                  echo "
            <tr>
              <td>$no</td>
              <td>$ve2->no_mesin</td>
              <td>$ve2->tipe_motor-$ve2->tipe_ahm</td>
              <td>$ve2->id_warna-$ve2->warna</td>
              <td>$ve2->lokasi-$ve2->slot</td>
              <td>$ve2->tipe</td>";
                ?>
                  <td class="center">
                    <button title="Choose" data-dismiss="modal" onclick="choose_nosin('<?php echo $ve2->no_mesin; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>
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

    <script>
      function ambil_slot() {
        var lokasi_baru = $("#lokasi_baru").val();
        $.ajax({
          url: "<?php echo site_url('h2/ptcd/get_slot') ?>",
          type: "POST",
          data: "lokasi_baru=" + lokasi_baru,
          cache: false,
          success: function(msg) {
            $("#slot").html(msg);
          }
        })
      }

      function ambil_slot_new() {
        var lokasi_s = $("#lokasi_s").val();
        $.ajax({
          url: "<?php echo site_url('h2/ptcd/get_slot_new') ?>",
          type: "POST",
          data: "lokasi_s=" + lokasi_s,
          cache: false,
          success: function(msg) {
            $("#lokasi_baru").html(msg);
          }
        })
      }

      function ambil_slot_new2() {
        var lokasi_s = $("#lokasi_s").val();
        $.ajax({
          url: "<?php echo site_url('h2/ptcd/get_slot_new2') ?>",
          type: "POST",
          data: "lokasi_s=" + lokasi_s,
          cache: false,
          success: function(msg) {
            $("#slot").html(msg);
          }
        })
      }
    </script>
    <script type="text/javascript">
      function choose_nosin(nosin) {
        document.getElementById("no_mesin").value = nosin;
        cek_nosin();
        $("#nosin_modal").modal("hide");
      }

      function cek_nosin() {
        var no_mesin = document.getElementById("no_mesin").value;
        //alert(id_po);  
        $.ajax({
          url: "<?php echo site_url('h2/ptcd/cek_nosin') ?>",
          type: "POST",
          data: "no_mesin=" + no_mesin,
          cache: false,
          success: function(msg) {
            data = msg.split("|");
            $("#kode_item").val(data[1]);
            $("#tipe").val(data[2]);
            $("#warna").val(data[3]);
            $("#lokasi_l").val(data[4]);
            $("#lokasi_s").val(data[5]);
            ambil_slot_new();
            ambil_slot_new2();
          }
        })
      }
    </script>