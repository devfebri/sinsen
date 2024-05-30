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
          <li class="">Claim</li>
          <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
        </ol>
      </section>
      <section class="content">
        <?php
        if ($set == "view") {
        ?>

          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">
                <?php /* ?>    <a href="h2/lbpc/add">
            <button class="btn bg-blue btn-flat margin"></i>New</button>
          </a> 
          <a href="h2/lbpc/download">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-download"></i> Download Excel</button>
          </a> <?php */ ?>
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
                    <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                    <!-- <th width="5%">No</th>               -->
                    <th>No LBPC</th>
                    <th>Tanggal LBPC</th>
                    <th>Claim QTY</th>
                    <th>Total Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  $ci = &get_instance();
                  $ci->load->model("m_h2");
                  foreach ($dt_result->result() as $rs) {
                    // $cek = $this->db->query("SELECT * FROM tr_rekap_claim_waranty WHERE no_lbpc='$rs->no_lbpc'");
                    // $amount_claim = 0;
                    // foreach ($cek->result() as $ck) {
                    //   $amount_claim += $this->db->query("SELECT SUM(((jumlah+harga)+ongkos)) AS amount_claim FROM tr_rekap_claim_waranty_detail WHERE id_rekap_claim='$ck->id_rekap_claim'")->row()->amount_claim;
                    // }
                    $total = $ci->m_h2->tot_lbpc($rs->no_lbpc);
                    echo "
            <tr> 
              <td>$rs->no_lbpc</td>
              <td>$rs->tgl_lbpc</td>
              <td>" . $total['tot_part'] . "</td>
              <td  align='right'>" . mata_uang_rp($total['total_biaya']) . "</td>
            </tr> ";
                    $no++;
                  }
                  ?>
                </tbody>
              </table>
            </div><!-- /.box-body -->
          </div><!-- /.box -->
        <?php
        } elseif ($set == "generate_skpb") {
        ?>

          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">
                <a href="h2/lbpc">
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
                  <form class="form-horizontal" action="h2/lbpc/save" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                        <div class="col-sm-2">
                          <input type="text" id="tanggal" required class="form-control" placeholder="Start Date" name="tipe" id="tipe">
                        </div>
                        <div class="col-sm-2"></div>
                        <label for="inputEmail3" class="col-sm-2 control-label">5 Digit Nomor Mesin</label>
                        <div class="col-sm-4">
                          <select class="form-control" name="5_digit">
                            <option value="">--Pilih--</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                        <div class="col-sm-2">
                          <input type="text" class="form-control" id="tanggal1" placeholder="End Date" name="">
                        </div>
                        <div class="col-sm-2"></div>
                        <label for="inputEmail3" class="col-sm-2 control-label">Service Ke-</label>
                        <div class="col-sm-4">
                          <select class="form-control" name="5_digit">
                            <option value="">--Pilih--</option>
                          </select>
                        </div>


                      </div>
                      <div class="form-group">
                        <p align="center">
                          <div class="col-sm-4"></div>
                          <div class="col-sm-2"><button class="btn btn-primary btn-flat form-control">Generate</button></div>
                        </p>
                      </div>
                      <button class="btn btn-success btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Detail</button>
                      <br><br><br>
                      <table id="example4" class="table table-hover">
                        <thead>
                          <th>No Mesin</th>
                          <th>No KPB</th>
                          <th>Tanggal Beli SMH</th>
                          <th>KM Service</th>
                          <th>Tanggal Sevice</th>
                          <th>No Surat Claim</th>
                        </thead>
                        <tbody>
                          <td>12345</td>
                          <td>12345</td>
                          <td>2018-10-10</td>
                          <td>2.000</td>
                          <td>2018-10-10</td>
                          <td>12345</td>
                        </tbody>
                      </table>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                      <div class="col-sm-4">
                      </div>
                      <div class="col-sm-4">
                        <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-download"></i> Download File .SKPB</button>
                      </div>
                    </div><!-- /.box-footer -->
                  </form>
                </div>
              </div>
            </div>
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
            <table id="example5" class="table table-bordered table-hover">
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
          url: "<?php echo site_url('h2/lbpc/get_slot') ?>",
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
          url: "<?php echo site_url('h2/lbpc/get_slot_new') ?>",
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
          url: "<?php echo site_url('h2/lbpc/get_slot_new2') ?>",
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
          url: "<?php echo site_url('h2/lbpc/cek_nosin') ?>",
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