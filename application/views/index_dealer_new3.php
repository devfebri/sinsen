<script src="assets/panel/dist/js/canvas.min.js"></script>
<script src="assets/chartjs/Chart.bundle.min.js"></script>
<script type="text/javascript" src="assets/panel/dist/js/chartjs-plugin-labels.js"></script>
<script src="assets/highcharts/highcharts.js"></script>
<script src="assets/highcharts/modules/exporting.js"></script>
<?php
$tgl = gmdate("Y-m-d", time() + 60 * 60 * 7);
?>

<!-- Modal -->
<div class="modal fade" id="mdlCheckLogin" role="dialog">
  <div class="modal-dialog modal-lg">
  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> PEMBERITAHUAN</h4>
      </div>
      <div class="modal-body">
        <p id="psn_pemberitahuan"></p>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
      </div>
    </div>
    
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $.ajax({
      url: '<?php echo base_url() ?>panel/cek_ganti_password',
      type: 'GET',
      dataType: 'JSON',
    })
    .done(function(a) {
      console.log("success");
      console.log("Status ganti password : "+a.status);
      console.log("Status ganti password : "+a.pesan);
      // alert(a.status);
      if (a.status == '1') {
        $("#psn_pemberitahuan").html(a.pesan);
        $('#mdlCheckLogin').modal({backdrop: 'static', keyboard: false});
      } else if (a.status == '2') {
        $(".close").hide();
        $("#psn_pemberitahuan").html(a.pesan);
        $('#mdlCheckLogin').modal({backdrop: 'static', keyboard: false});
      }

    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
  });
</script>

<body onload="tampil_grafik();daily();">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard Dealer
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="panel/home"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <?php
      $id_dealer = $this->m_admin->cari_dealer();
      $tgl = gmdate("Y-m-d", time() + 60 * 60 * 7);
      $bulan       = date("Y-m", strtotime($tgl));
      $jam = gmdate("H:i", time() + 60 * 60 * 7);
      $stk = $this->m_admin->get_data_dashboard_dealer($tgl, $id_dealer);
      ?>
      <div class="row">
        <div class="col-lg-5" style="min-height: 200px">
          <div class="box box-warning">
            <div class="box-header">
              <div class="box-tools pull-right">
                <button class="btn btn-default" onclick="CopyToClipboard('copyReport')"><i class="fa fa-copy"></i></button>
              </div>
            </div>
            <div class="box-body box-warning" id="copyReport" style="min-height: 200px">
              <b><?php
                  echo $nama_dealer = $this->m_admin->getByID("ms_dealer", "id_dealer", $id_dealer)->row()->nama_dealer
                  ?>
              </b> <br>
              <!--             Sales Report S.E.E.D.S <br> -->
              <?= mediumdate_indo($tgl, '-') ?><br>
              <?= $stk['jml_hari'] ?>/<?= $stk['jml_bulan'] ?><br>
              <?php if (is_array($stk['series_detail'])) : ?>
                <?php
                $jml_hari = $this->m_admin->get_penjualan_inv('tanggal', $tgl, null, $id_dealer, null, null, null, null, null, null, 'AL');
                $jml_bulan = $this->m_admin->get_penjualan_inv('bulan', $bulan, null, $id_dealer, null, null, null, null, null, null, 'AL');

                echo "AT LOW " . $jml_hari . "/" . $jml_bulan . " <br>";
                ?>
                <?php foreach ($stk['series_detail'] as $ser) : ?>
                  <?= $ser['series'] . ' ' . $ser['jml_hari'] . '/' . $ser['jml_bulan'] ?><br>
                <?php endforeach ?>
              <?php endif ?>
            </div>
          </div>
        </div>
        <div class="col-lg-7" style="min-height: 200px">
          <div class="row">
            <div class="col-sm-6">
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?= $stk['jml_hari'] ?></h3>
                  <p>Penjualan Hari ini</p>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?= $stk['jml_bulan'] ?></h3>
                  <p>Total Bulan Ini</p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?= $stk['jml_kemarin'] ?></h3>
                  <p>Kemarin</p>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="small-box bg-red">
                <div class="inner">
                  <p>
                    <?php
                    $y = gmdate("d F Y", time() + 60 * 60 * 7);
                    $f = gmdate("H:i", time() + 60 * 60 * 7);
                    ?>
                    <b>
                      <?= $y ?> <br>
                      <?= $f ?>
                    </b>
                  </p>
                  <p>Terakhir di-Update</p>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="col-sm-12">
          <div class="small-box bg-red">
            <div class="inner">
              <div align="center">
                <font size='5px'><b>
                    <?php
                    $tanggal = date("Y-m-d");
                    $no = 0;
                    $rank = "";
                    $total1 = "";
                    $bulan    = substr($tanggal, 0, 7);
                    $where1 = '';
                    $where2 = '';
                    if ($bulan != null) {
                      $where1 = "WHERE LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bulan'";
                      $where2 = "WHERE LEFT(tr_sales_order_gc.tgl_cetak_invoice,7) = '$bulan'";
                    }
                    $sql = $this->db->query("SELECT de, SUM(total) as hasil FROM
                  (
                    SELECT ms_dealer.id_dealer AS de, COUNT(tr_sales_order.no_mesin) AS total 
                    FROM tr_sales_order 
                    INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer 
                    $where1
                    GROUP BY tr_sales_order.id_dealer
                    UNION ALL
                    SELECT ms_dealer.id_dealer AS de, COUNT(tr_sales_order_gc_nosin.no_mesin) AS total 
                    FROM tr_sales_order_gc 
                    INNER JOIN tr_sales_order_gc_nosin ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
                    INNER JOIN ms_dealer ON tr_sales_order_gc.id_dealer = ms_dealer.id_dealer
                    $where2
                    GROUP BY tr_sales_order_gc.id_dealer
                  )a GROUP BY de ORDER BY hasil DESC");
                    foreach ($sql->result() as $isi) {
                      if ($total1 <> $isi->hasil) {
                        $total1 = $isi->hasil;
                        $no++;
                      } else {
                        $total1 = $total1;
                      }
                      if ($isi->de == $id_dealer) {
                        $rank = $no;
                      }
                    }
                    echo $nama_dealer . " Rank No." . $rank ?>
                  </b></font>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /.row -->
      <div class="row">
        <div class="col-sm-4" id="divSalesByCat">
          <div class="box box-warning">
            <div class="box-header">
              <i class="fa fa-graphic"></i>
              <h3 class="login-box-msg" style="font-size: 16px;">Sales By Category</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-primary btn-sm" type="button" onclick="chartByCategory()"><i class="fa fa-refresh"></i></button>
              </div>
            </div>
            <div class="box-body chat">
              <div id="chartByCategory" style="margin: 0 auto;height: 259px"></div>
            </div>
          </div>
        </div>
        <div class="col-sm-4" id="divSalesCompFinco">
          <div class="box box-warning">
            <div class="box-header">
              <i class="fa fa-graphic"></i>
              <h3 class="login-box-msg" style="font-size: 16px;">Sales Comparison By Finco</h3>
            </div>
            <div class="box-body chat">
              <div id="chartByFinco" style="height: 259px; margin: 0 auto;"></div>
            </div>
          </div>
        </div>
        <div class="col-sm-4" id="divSalesContribution">
          <div class="box box-warning">
            <div class="box-header">
              <i class="fa fa-graphic"></i>
              <h3 class="login-box-msg" style="font-size: 16px;">Sales Contribution</h3>
            </div>
            <div class="box-body chat">
              <canvas id="chartByContribution" style="height: 259px; margin: 0 auto;"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6" id="divSalesFincContDP">
          <div class="box box-warning">
            <div class="box-header">
              <i class="fa fa-graphic"></i>
              <h3 class="login-box-msg" style="font-size: 16px;">Sales Finco Contribution By Gross Down Payment</h3>
              <div class="box-tools pull-right">
                <a class="btn btn-primary btn-sm" target="_blank" href="<?= base_url('/dashboard/getSalesFincoByDPDealer?download=y&tanggal=' . $tgl) ?>"><i class="fa fa-download"></i></a>
              </div>
            </div>
            <div class="box-body" style="min-height: 259px;">
              <table class="table table-bordered table-hovered table-striped" id="tabelSalesFincoByDP" style="font-size: 10pt;text-align:center; "></table>
            </div>
          </div>
        </div>
        <div class="col-sm-6" id="divSalesFincContDP">
          <div class="box box-warning">
            <div class="box-header">
              <i class="fa fa-graphic"></i>
              <h3 class="login-box-msg" style="font-size: 16px;">Rank Dealer</h3>
              <div class="box-tools pull-right">
                <a class="btn btn-primary btn-sm" target="_blank" href="<?= base_url('/dashboard/getSSU?download=y&tanggal=' . $tgl) ?>"><i class="fa fa-download"></i></a>
              </div>
            </div>
            <div class="box-body" style="min-height: 259px;">
              <table class="table table-bordered table-hovered table-striped" id="tabelSSU" style="font-size: 10pt;text-align:center;"></table>
            </div>
          </div>
        </div>

      </div>

      <div class="row">
        <div class="col-sm-6" id="divSalesFincContDP">
          <div class="box box-warning">
            <div class="box-header">
              <i class="fa fa-graphic"></i>
              <h3 class="login-box-msg" style="font-size: 16px;">Sales People Performance</h3>
              <div class="box-tools pull-right">
                <a class="btn btn-primary btn-sm" target="_blank" href="<?= base_url('/dashboard/getSalesPerformanceDealer?download=y') ?>"><i class="fa fa-download"></i></a>
              </div>
            </div>
            <div class="box-body" style="min-height: 259px;">
              <table class="table table-bordered table-hovered table-striped" id="showSalesPerformance" style="font-size: 10pt;text-align:center;"></table>
            </div>
          </div>
        </div>
        <div class="col-sm-6" id="divSalesFincContDP">
          <div class="box box-warning">
            <div class="box-header">
              <i class="fa fa-graphic"></i>
              <h3 class="login-box-msg" style="font-size: 16px;">Document and Handling</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-default" onclick="CopyToClipboard('copyReport2')"><i class="fa fa-copy"></i></button>
              </div>
            </div>
            <div class="box-body" style="min-height: 259px;">
              <div class="box-header">
                <div class="box-tools pull-right">
                </div>
              </div>
              <div class="box-body box-warning" id="copyReport2" style="min-height: 200px">
                <table style="font-size:18px;" width="80%" class="table table-bordered">
                  <?php
                  $y = date('Y');
                  $id_dealer = $this->m_admin->cari_dealer();
                  $retail = $this->m_admin->get_penjualan_inv('tahun', $y, null, $id_dealer);

                  $faktur = $this->db->query("SELECT  SUM(jum) AS hasil FROM
                    (
                    SELECT COUNT(tr_faktur_stnk_detail.no_mesin) AS jum FROM tr_faktur_stnk_detail 
                        INNER JOIN tr_faktur_stnk ON tr_faktur_stnk.no_bastd = tr_faktur_stnk_detail.no_bastd
                        INNER JOIN tr_sales_order ON tr_faktur_stnk_detail.no_mesin = tr_sales_order.no_mesin
                        WHERE LEFT(tr_sales_order.tgl_create_ssu,4) = '$y' AND tr_faktur_stnk.id_dealer = '$id_dealer' 
                        AND tr_faktur_stnk.status_faktur = 'approved' AND tr_faktur_stnk_detail.no_mesin 
                        IN (SELECT no_mesin FROM tr_pengajuan_bbn_detail) 
                    UNION ALL                    
                    SELECT COUNT(tr_faktur_stnk_detail.no_mesin) AS jum FROM tr_faktur_stnk_detail 
                        INNER JOIN tr_faktur_stnk ON tr_faktur_stnk.no_bastd = tr_faktur_stnk_detail.no_bastd
                        INNER JOIN tr_sales_order_gc_nosin ON tr_faktur_stnk_detail.no_mesin = tr_sales_order_gc_nosin.no_mesin
                        INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
                        WHERE LEFT(tr_sales_order_gc.tgl_create_ssu,4) = '$y' AND tr_faktur_stnk.id_dealer = '$id_dealer' 
                        AND tr_faktur_stnk.status_faktur = 'approved' AND tr_faktur_stnk_detail.no_mesin 
                        IN (SELECT no_mesin FROM tr_pengajuan_bbn_detail)                   
                    )a");
                  $approved_md = ($faktur->num_rows() > 0) ? $faktur->row()->hasil : 0;


                  $bbn = $this->db->query("SELECT SUM(jum) AS hasil
                  FROM (
                    SELECT COUNT(a.no_mesin) AS jum
                    FROM tr_sales_order a
                    JOIN tr_spk b ON a.no_spk = b.no_spk
                    JOIN tr_pengajuan_bbn_detail d ON a.no_mesin = d.no_mesin
                    WHERE LEFT(a.tgl_cetak_invoice,4) = '$y'
                    AND a.id_dealer = '$id_dealer' AND d.status_bbn = 'generated'
                    UNION
                    SELECT COUNT(b.no_mesin) AS jum
                    FROM tr_sales_order_gc a JOIN tr_sales_order_gc_nosin b ON a.id_sales_order_gc = b.id_sales_order_gc
                    JOIN tr_spk_gc c ON a.no_spk_gc = c.no_spk_gc
                    JOIN tr_pengajuan_bbn_detail d ON b.no_mesin = d.no_mesin
                    WHERE LEFT(a.tgl_cetak_invoice,4) = '$y'
                    AND a.id_dealer = '$id_dealer' AND d.status_bbn = 'generated'
                  ) z ");
                  $polreg = ($bbn->num_rows() > 0) ? $bbn->row()->hasil : 0;

                  $pending_bbn = $approved_md - $retail;
                  $bg = "";
                  if ($pending_bbn > 0) {
                    $bg = "bgcolor='red'";
                  }
                  ?>
                  <tr>
                    <td width="50%">Retail Sales</td>
                    <td width="50%"><?php echo $retail ?></td>
                  </tr>
                  <tr <?php echo $bg ?>>
                    <th>Pending BBN</th>
                    <td><?php echo $pending_bbn ?></td>
                  </tr>
                  <tr>
                    <td>Approve MD</td>
                    <td><?php echo $approved_md ?></td>
                  </tr>
                  <tr>
                    <td>Registered as POLREG</td>
                    <td><?php echo $polreg ?></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>






        <!-- <section class="col-lg-6">      
      <div class="box box-warning">
        <div class="box-header">
          <i class="fa fa-graphic"></i>
          <h3 class="box-title">Stock Monitoring</h3>            
        </div>
        <div class="box-body" style="min-height: 259px;">
          <table class="table table-bordered table-hovered table-striped" id="tabelStokMonitoring" style="font-size: 10pt"></table>            
        </div>
      </div>
    </section>

      
    <section class="col-lg-6 connectedSortable">      
      <div class="box box-warning">
        <div class="box-header">
          <i class="fa fa-graphic"></i>
          <h3 class="box-title"> Sales Structure By DP</h3>            
        </div>
        <div class="box-body" style="min-height: 259px;">
          <table class="table table-bordered table-hovered table-striped" id="tabelSalesDP" style="font-size: 10pt"></table>            
        </div>
      </div>
    </section>

    <section class="col-lg-6 connectedSortable">      
      <div class="box box-warning">
        <div class="box-header">
          <i class="fa fa-graphic"></i>
          <h3 class="box-title">Rangking Dealer By SSU</h3>            
        </div>
        <div class="box-body" style="min-height: 259px;">
          <table class="table table-bordered table-hovered table-striped" id="tabelSSU" style="font-size: 10pt"></table>            
        </div>
      </div>
    </section>
    <section class="col-lg-6 connectedSortable">          
      <div class="box box-warning">
        <div class="box-header">
          <i class="fa fa-graphic"></i>
          <h3 class="box-title"> Finance Company Account Receivable</h3>            
        </div>
        <div class="box-body" style="min-height: 259px;">
          <div class="box-body chat" id="chat-box">
            <div id="container6" style="min-width: 200px; height: 200px; margin: 0 auto"></div>
          </div>
        </div>
      </div>      
    </section> -->



      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="box box-warning">
            <div class="box-header">
              <i class="fa fa-graphic"></i>
              <h3 class="login-box-msg" style="font-size: 16px;">Sales & Stock Unit</h3>
              <div class="box-tools pull-right">
                <a class="btn btn-primary btn-sm" target="_blank" href="<?= base_url('/dashboard/showDetailStokDealer?download=y') ?>"><i class="fa fa-download"></i></a>
              </div>
            </div>
            <div class="box-body" style="min-height: 489px;">
              <table class="table table-bordered table-hovered table-striped " id="showDetailStok" style="text-align:center;">
              </table>
            </div>
          </div>
        </div>
      </div>

  </div>
  </div>

  <script type="text/javascript">
    function timedRefresh(timeoutPeriod) {
      setTimeout("location.reload(true);", timeoutPeriod);
    }
  </script>
  <!-- <script src="assets/panel/dist/js/canvas.min.js"></script> -->

  <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

  <script src="assets/panel/plugins/graphic_new/highcharts.js"></script>
  <script src="assets/panel/plugins/graphic_new/exporting.js"></script>
  <script src="assets/panel/plugins/graphic_new/export-data.js"></script>
  <script src="assets/panel/plugins/graphic_new/series-label.js"></script>
  <script>
    function daily() {
      $("#chat-box_1").show();
      $("#chat-box_2").hide();

      $("#chat-box_7a").show();
      $("#chat-box_7b").hide();
    }

    function change_daily() {
      $("#chat-box_2").show();
      $("#chat-box_1").hide();
    }

    function change_monthly() {
      $("#chat-box_1").show();
      $("#chat-box_2").hide();
    }

    function change_district() {
      $("#chat-box_7a").show();
      $("#chat-box_7b").hide();
    }

    function change_region() {
      $("#chat-box_7b").show();
      $("#chat-box_7a").hide();
    }

    function tampil_grafik() {
      pie2();
    }

    function pie2() {

      var chart = new CanvasJS.Chart("container4", {
        theme: "light2", // "light1", "light2", "dark1", "dark2"
        exportEnabled: true,
        animationEnabled: true,
        // title: {
        //   text: "",
        //   FontSize: 10
        // },
        data: [{
          type: "pie",
          startAngle: 25,
          toolTipContent: "<b>{label}</b>: {y}%",
          showInLegend: "true",
          legendText: "{label}",
          indexLabelFontSize: 12,
          indexLabel: "{label} - {y}%",
          dataPoints: [
            <?php
            $id_dealer = $this->m_admin->cari_dealer();
            $bln = date("Y-m");
            $sql = $this->db->query("SELECT ms_finance_company.finance_company, COUNT(tr_sales_order.no_mesin) AS jum
      FROM tr_sales_order
      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
      INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
      WHERE tr_sales_order.id_dealer = '$id_dealer' AND tr_spk.jenis_beli = 'Kredit'
      AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bln'
      GROUP BY tr_spk.id_finance_company");
            foreach ($sql->result() as $isi) {
              //echo "{ y: 51.08, label: 'Chrome' },";
              $tt = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order
        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
        INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
        WHERE LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bln' AND tr_sales_order.id_dealer = '$id_dealer' AND tr_spk.jenis_beli = 'Kredit'")->row();


              $y = ($isi->jum / $tt->jum) * 100;
              $r = round($y, 2);
              echo "{ y : $r, label: '$isi->finance_company ($isi->jum unit)'},";
            }
            ?>
          ]
        }]
      });
      chart.render();

    }
  </script>

  <!-- Salec Comparison -->
  <script type="text/javascript">
    var chart1; // globally available
    $(document).ready(function() {
      chart1 = new Highcharts.chart('container2', {
        chart: {
          type: 'column'
        },
        title: {
          <?php
          $y = date("Y-m-d");
          $tanggal = date("F Y", strtotime($y));
          ?>
          text: '<?php echo $tanggal ?>'
        },
        subtitle: {
          text: ''
        },
        xAxis: {
          categories: [
            <?php
            $id_dealer = $this->m_admin->cari_dealer();
            $sq = $this->db->query("SELECT DISTINCT(ms_segment.segment) AS segment,ms_segment.id_segment FROM tr_spk 
              INNER JOIN tr_sales_order ON tr_spk.no_spk = tr_sales_order.no_spk
              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
              INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
              INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment
              WHERE tr_sales_order.id_dealer = '$id_dealer'
              ORDER BY ms_segment.segment ASC");
            foreach ($sq->result() as $isi) {

              $bln = date("m");
              $bln_1 = date("m") - 1;
              if ($bln_1 == "0") {
                $bln_1_fix = "12";
                $ty   = date("Y") - 1;
                $th_1 = $ty . "-" . $bln_1_fix;
              } else {
                $bln_1_fix = $bln_1;
                $ty   = date("Y");
                $th_1 = $ty . "-" . $bln_1_fix;
              }
              $th     = date("Y-m");
              $th_b   = date("F Y", strtotime($th));
              $th_1b   = date("F Y", strtotime($th_1));

              //cek bulan lalu
              $cek_1 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS total_jual                
                FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment                
                WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL AND tr_sales_order.id_dealer = '$id_dealer'
                AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$th_1' AND ms_segment.id_segment = '$isi->id_segment'");
              if ($cek_1->num_rows() > 0) {
                $t = $cek_1->row();
                $hasil_1 = $t->total_jual;
              } else {
                $hasil_1 = 0;
              }

              //cek bulan sekarang
              $cek = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS total_jual                
                FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment                
                WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL AND tr_sales_order.id_dealer = '$id_dealer'
                AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$th' AND ms_segment.id_segment = '$isi->id_segment'");
              if ($cek->num_rows() > 0) {
                $t = $cek->row();
                $hasil = $t->total_jual;
              } else {
                $hasil = 0;
              }

              //cek bulan lalu GC
              $cek_3 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS total_jual                
                FROM tr_sales_order_gc_nosin INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
                INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
                INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
                INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment                
                WHERE tr_sales_order_gc.tgl_cetak_invoice IS NOT NULL AND tr_sales_order_gc.id_dealer = '$id_dealer'
                AND LEFT(tr_sales_order_gc.tgl_cetak_invoice,7) = '$th_1' AND ms_segment.id_segment = '$isi->id_segment'");
              if ($cek_3->num_rows() > 0) {
                $t = $cek_3->row();
                $hasil_3 = $t->total_jual;
              } else {
                $hasil_3 = 0;
              }

              //cek bulan sekarang GC
              $cek_4 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS total_jual                
                FROM tr_sales_order_gc_nosin INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
                INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
                INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
                INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment                
                WHERE tr_sales_order_gc.tgl_cetak_invoice IS NOT NULL AND tr_sales_order_gc.id_dealer = '$id_dealer'
                AND LEFT(tr_sales_order_gc.tgl_cetak_invoice,7) = '$th' AND ms_segment.id_segment = '$isi->id_segment'");
              if ($cek_4->num_rows() > 0) {
                $t = $cek_4->row();
                $hasil_4 = $t->total_jual;
              } else {
                $hasil_4 = 0;
              }

              //cari x
              if ($hasil_1 != 0) {
                $x_persen = round((($hasil - $hasil_1) / $hasil_1), 2);
              } else {
                $x_persen = round(($hasil - $hasil_1), 2);
              }

              //cari x_gc
              if ($hasil_3 != 0) {
                $x_persen_2 = round((($hasil_4 - $hasil_3) / $hasil_3), 2);
              } else {
                $x_persen_2 = round(($hasil_4 - $hasil_3), 2);
              }

              $x_persen_fix = $x_persen + $x_persen_2;
              echo "'$isi->segment ($x_persen_fix %)'" . ",";
            }
            ?>
          ],
          crosshair: true
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Total Unit'
          }
        },
        series: [{
          name: '<?php echo $th_1b ?>',
          data: [
            <?php
            $sq = $this->db->query("SELECT DISTINCT(ms_segment.segment) AS segment,ms_segment.id_segment FROM tr_spk 
              INNER JOIN tr_sales_order ON tr_spk.no_spk = tr_sales_order.no_spk
              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
              INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
              INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment
              WHERE tr_sales_order.id_dealer = '$id_dealer'
              ORDER BY ms_segment.segment ASC");
            foreach ($sq->result() as $isi) {
              $cek = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS total_jual_1                
                FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment                
                WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL AND tr_sales_order.id_dealer = '$id_dealer'
                AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$th_1' AND ms_segment.id_segment = '$isi->id_segment'");
              if ($cek->num_rows() > 0) {
                $t = $cek->row();
                $hasil = $t->total_jual_1;
              } else {
                $hasil = 0;
              }
              echo $hasil . ",";
            }
            ?>
          ]

        }, {
          name: '<?php echo $th_b ?>',
          data: [
            <?php
            $sq = $this->db->query("SELECT DISTINCT(ms_segment.segment) AS segment,ms_segment.id_segment FROM tr_spk 
              INNER JOIN tr_sales_order ON tr_spk.no_spk = tr_sales_order.no_spk
              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
              INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
              INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment
              WHERE tr_sales_order.id_dealer = '$id_dealer'
              ORDER BY ms_segment.segment ASC");
            foreach ($sq->result() as $isi) {
              $cek = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS total_jual                
                FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment                
                WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL AND tr_sales_order.id_dealer = '$id_dealer'
                AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$th' AND ms_segment.id_segment = '$isi->id_segment'");


              if ($cek->num_rows() > 0) {
                $t = $cek->row();
                $hasil = $t->total_jual;
              } else {
                $hasil = 0;
              }

              echo $hasil . ",";
            }
            ?>
          ]

        }]
      });
    });
  </script>
  <!-- Sales People Performance District-->
  <script type="text/javascript">
    var chart1; // globally available
    $(document).ready(function() {
      chart1 = new Highcharts.chart('container7a', {
        chart: {
          type: 'column'
        },
        title: {
          <?php
          $y = date("Y-m-d");
          $tanggal = date("F Y", strtotime($y));
          $id_dealer = $this->m_admin->cari_dealer();
          $cari_daerah = $this->db->query("SELECT * FROM ms_dealer INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
      INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
      INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
      INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
      WHERE ms_dealer.id_dealer='$id_dealer'")->row();
          ?>
          text: '<?php echo $cari_daerah->kabupaten; ?>'
        },
        subtitle: {
          text: ''
        },
        xAxis: {
          categories: ['Total Penjualan'],
          crosshair: true
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Total Unit'
          }
        },
        series: [
          <?php
          $bln = date("Y-m");
          $id_dealer = $this->m_admin->cari_dealer();
          $cari_daerah = $this->db->query("SELECT * FROM ms_dealer INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
      INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
      INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
      INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
      WHERE ms_dealer.id_dealer='$id_dealer'")->row();
          $sq = $this->db->query("SELECT DISTINCT(tr_prospek.id_karyawan_dealer) AS sales,ms_karyawan_dealer.nama_lengkap,tr_spk.no_spk FROM tr_sales_order 
        LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
        LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
        LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer 
        LEFT JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer
        LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
        INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
        INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
        INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
        WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bln'              
        AND ms_kabupaten.id_kabupaten = '$cari_daerah->id_kabupaten'
        GROUP BY tr_prospek.id_karyawan_dealer
        ORDER BY ms_karyawan_dealer.nama_lengkap ASC");
          foreach ($sq->result() as $isi) {
            $sr = $this->db->query("SELECT count(tr_sales_order.no_mesin) as jum FROM tr_sales_order 
        LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
        LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
        LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer 
        WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bln'
        AND tr_prospek.id_karyawan_dealer = '$isi->sales'")->row();
            $jumlah = $sr->jum;
            $nama = $isi->nama_lengkap;
          ?> {
              name: '<?php echo $nama; ?>',
              data: [<?php echo $jumlah; ?>]
            },
          <?php } ?>
        ]
      });
    });
  </script>

  <!-- Sales People Performance Region-->
  <script type="text/javascript">
    var chart1; // globally available
    $(document).ready(function() {
      chart1 = new Highcharts.chart('container7b', {
        chart: {
          type: 'column'
        },
        title: {
          <?php
          $y = date("Y-m-d");
          $tanggal = date("F Y", strtotime($y));
          $id_dealer = $this->m_admin->cari_dealer();
          $cari_daerah = $this->db->query("SELECT * FROM ms_dealer INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
      INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
      INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
      INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
      WHERE ms_dealer.id_dealer='$id_dealer'")->row();
          ?>
          text: '<?php echo $cari_daerah->provinsi; ?>'
        },
        subtitle: {
          text: ''
        },
        xAxis: {
          categories: ['Total Penjualan'],
          crosshair: true
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Total Unit'
          }
        },
        series: [
          <?php
          $bln = date("Y-m");
          $id_dealer = $this->m_admin->cari_dealer();
          $cari_daerah = $this->db->query("SELECT * FROM ms_dealer INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
      INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
      INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
      INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
      WHERE ms_dealer.id_dealer='$id_dealer'")->row();
          $sq = $this->db->query("SELECT DISTINCT(tr_prospek.id_karyawan_dealer) AS sales,ms_karyawan_dealer.nama_lengkap,tr_spk.no_spk FROM tr_sales_order 
        LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
        LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
        LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer 
        LEFT JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer
        LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
        INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
        INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
        INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
        WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bln'              
        AND ms_provinsi.id_provinsi = '$cari_daerah->id_provinsi'
        GROUP BY tr_prospek.id_karyawan_dealer
        ORDER BY ms_karyawan_dealer.nama_lengkap ASC");
          foreach ($sq->result() as $isi) {
            $sr = $this->db->query("SELECT count(tr_sales_order.no_mesin) as jum FROM tr_sales_order 
        LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
        LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
        LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer 
        WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bln'
        AND tr_prospek.id_karyawan_dealer = '$isi->sales'")->row();
            $jumlah = $sr->jum;
            $nama = $isi->nama_lengkap;
          ?> {
              name: '<?php echo $nama; ?>',
              data: [<?php echo $jumlah; ?>]
            },
          <?php } ?>
        ]
      });
    });
  </script>

  <!-- Line Graphic Sales Performance Monthly -->
  <script type="text/javascript">
    var chart1; // globally available
    $(document).ready(function() {
      chart1 = new Highcharts.chart('container', {
        chart: {
          type: 'line'
        },
        title: {
          <?php
          $y = date("Y-m");
          $tanggal = date("F Y", strtotime($y));
          ?>
          text: '<?php echo $tanggal ?>'
        },
        subtitle: {
          text: ''
        },
        xAxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        yAxis: {
          title: {
            text: 'Total Penjualan (unit)'
          }
        },
        plotOptions: {
          line: {
            dataLabels: {
              enabled: true
            },
            enableMouseTracking: false
          }
        },
        series: [{
          name: 'Sales Order',
          data: [
            <?php
            $id_dealer = $this->m_admin->cari_dealer();
            for ($i = 1; $i <= 12; $i++) {
              $y = date("Y");
              $b  = sprintf("%'.02d", $i);
              $bln = $y . "-" . $b;
              $sql = $this->db->query("SELECT count(no_mesin) AS jum FROM tr_sales_order WHERE tr_sales_order.tgl_cetak_invoice 
              IS NOT NULL AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bln'
              AND tr_sales_order.id_dealer = '$id_dealer'");
              $ssu2 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS total_jual FROM tr_sales_order_gc_nosin
                INNER JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc                                        
                WHERE tr_sales_order_gc.id_dealer = '$id_dealer' AND LEFT(tr_sales_order_gc.tgl_cetak_invoice,7) = '$bln'")->row();
              foreach ($sql->result() as $isi) {
                echo $isi->jum + $ssu2->total_jual . ",";
              }
            }
            ?>
          ]
        }]
      });
    });
  </script>

  <!-- Line Graphic Sales Performance Daily -->
  <script type="text/javascript">
    var chart1; // globally available
    $(document).ready(function() {
      chart1 = new Highcharts.chart('container_1', {
        chart: {
          type: 'line'
        },
        title: {
          <?php
          $y = date("Y-m-d");
          $tanggal = date("d F Y", strtotime($y));
          ?>
          text: '<?php echo $tanggal ?>'
        },
        subtitle: {
          text: ''
        },
        xAxis: {
          categories: [
            <?php
            $tgl_akhir_bulan = date('t');
            for ($i = 1; $i <= $tgl_akhir_bulan; $i++) {
              echo "'$i'" . ",";
            }
            ?>
          ]
        },
        yAxis: {
          title: {
            text: 'Total Penjualan (unit)'
          }
        },
        plotOptions: {
          line: {
            dataLabels: {
              enabled: true
            },
            enableMouseTracking: false
          }
        },
        series: [{
          name: 'Sales Order',
          data: [
            <?php
            $id_dealer = $this->m_admin->cari_dealer();
            for ($i = 1; $i <= 30; $i++) {
              $y = date("Y-m");
              $b  = sprintf("%'.02d", $i);
              $bln = $y . "-" . $b;
              $sql = $this->db->query("SELECT count(no_mesin) AS jum FROM tr_sales_order WHERE tr_sales_order.tgl_cetak_invoice 
              IS NOT NULL AND tr_sales_order.tgl_cetak_invoice = '$bln'
              AND tr_sales_order.id_dealer = '$id_dealer'");
              $ssu3 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS total_jual FROM tr_sales_order_gc_nosin
                INNER JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc                                        
                WHERE tr_sales_order_gc.id_dealer = '$id_dealer' AND tr_sales_order_gc.tgl_cetak_invoice = '$bln'");
              $total_jual = ($ssu3->num_rows() > 0) ? $ssu3->row()->total_jual : 0;
              foreach ($sql->result() as $isi) {
                echo $isi->jum + $total_jual . ",";
              }
            }
            ?>
          ]
        }]
      });
    });
  </script>

  <script type="text/javascript">
    var chart1; // globally available
    $(document).ready(function() {
      chart1 = new Highcharts.chart('container6', {
        chart: {
          type: 'column'
        },
        title: {
          <?php
          $y = date("Y-m");
          $tanggal = date("F Y", strtotime($y));
          ?>
          text: '<?php echo $tanggal ?>'
        },
        subtitle: {
          text: ''
        },
        xAxis: {
          categories: [
            <?php
            $bln = date("Y-m-d");
            $id_dealer = $this->m_admin->cari_dealer();
            $sql = $this->db->query("SELECT ms_finance_company.finance_company, COUNT(tr_sales_order.no_mesin) AS jum
              FROM tr_sales_order
              INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
              INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
              WHERE tr_sales_order.id_dealer = '$id_dealer' AND tr_spk.jenis_beli = 'Kredit'
              GROUP BY tr_spk.id_finance_company");
            foreach ($sql->result() as $isi) {
              echo "'$isi->finance_company'" . ",";
            }
            ?>
          ],
          crosshair: true
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Total Unit'
          }
        },
        series: [
          <?php
          $bln = date("Y-m-d");
          $id_dealer = $this->m_admin->cari_dealer();
          $sql = $this->db->query("SELECT ms_finance_company.finance_company, ms_finance_company.id_finance_company, COUNT(tr_sales_order.no_mesin) AS jum
              FROM tr_sales_order
              INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
              INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
              WHERE tr_sales_order.id_dealer = '$id_dealer' AND tr_spk.jenis_beli = 'Kredit'
              GROUP BY tr_spk.id_finance_company");
          foreach ($sql->result() as $isi) {
            $jumlah = $isi->jum;
            $nama = $isi->finance_company;
          ?> {
              name: '<?php echo $nama; ?>',
              data: [<?php echo $jumlah; ?>]
            },
          <?php } ?>
        ]
      });
    });
  </script>

  <script type="text/javascript">
    function loadDatatables(el) {
      scrolly = 250;
      ordering = false;
      if (el == 'tabelSalesDP') {
        var scrolly = 173;
      }
      if (el == 'tabelStokMonitoring') {
        var scrolly = 173;
      }

      // console.log(el);
      // console.log(scrolly);
      if (el == 'showDetailStok') {
        $('#' + el).DataTable({
          'paging': true,
          'bLengthChange': false,
          "bInfo": false,
          'searching': true,
          'ordering': ordering,
          'info': false,
          'scrollCollapse': true,
          'autoWidth': true,
          'scrollX': true,
          'pageLength': 5

        })
      } else if (el == 'showSalesPerformance') {
        $('#' + el).DataTable({
          'paging': true,
          'bLengthChange': false,
          "bInfo": false,
          'searching': true,
          'ordering': ordering,
          'info': false,
          'scrollCollapse': true,
          'autoWidth': true,
          'pageLength': 5
        })
      } else if (el == 'tabelSSU') {
        $('#' + el).DataTable({
          'paging': false,
          'bLengthChange': false,
          "bInfo": false,
          'searching': true,
          'ordering': ordering,
          'info': false,
          'scrollCollapse': true,
          'scrollY': '184px',
          'autoWidth': true
        })
      } else if (el == 'tabelSalesFincoByDP') {
        $('#' + el).DataTable({
          'paging': false,
          'bLengthChange': false,
          "bInfo": false,
          'searching': false,
          'ordering': ordering,
          'info': false,
          'scrollY': scrolly + 'px',
          'scrollX': false,
          'scrollCollapse': true,
          'autoWidth': true,
        })
      } else {
        $('#' + el).DataTable({
          'paging': false,
          'bLengthChange': false,
          "bInfo": false,
          'searching': true,
          'ordering': ordering,
          'info': false,
          'scrollY': scrolly + 'px',
          'scrollX': false,
          'scrollCollapse': true,
          'autoWidth': true,

        })
      }
    }

    function getStokMonitoring() {
      values = {
        tanggal: '<?= $tgl ?>'
      }
      $.ajax({
        beforeSend: function() {
          $('#tabelStokMonitoring').html('<tr><td colspan=6 style="font-size:10pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('dashboard/getStokMonitoring') ?>",
        type: "POST",
        data: values,
        cache: false,
        success: function(response) {
          $('#tabelStokMonitoring').html(response);
          loadDatatables('tabelStokMonitoring');
        }
      })
    }

    function getSalesDP() {
      values = {
        tanggal: '<?= $tgl ?>'
      }
      $.ajax({
        beforeSend: function() {
          $('#tabelSalesDP').html('<tr><td colspan=5 style="font-size:10pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('dashboard/getSalesDP') ?>",
        type: "POST",
        data: values,
        cache: false,
        success: function(response) {
          $('#tabelSalesDP').html(response);
          loadDatatables('tabelSalesDP');
        }
      })
    }

    function getSSU() {
      values = {
        tanggal: '<?= $tgl ?>'
      }
      $.ajax({
        beforeSend: function() {
          $('#tabelSSU').html('<tr><td colspan=3 style="font-size:10pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('dashboard/getSSU') ?>",
        type: "POST",
        data: values,
        cache: false,
        success: function(response) {
          $('#tabelSSU').html(response);
          loadDatatables('tabelSSU');
        }
      })
    }

    $(window).load(function() {
      getStokMonitoring();
      getSalesDP();
      getSSU();
      tampil_grafik();
      chartByContribution();
      chartByCategory();
      chartByFinco();
      getSalesFincoByDP();
      getStokDealer();
      getSalesPerformanceDealer();
    })




    function CopyToClipboard(id) {
      if (document.selection) {
        var range = document.body.createTextRange();
        range.moveToElementText(document.getElementById(id));
        range.select().createTextRange();
        document.execCommand("copy");

      } else if (window.getSelection) {
        var range = document.createRange();
        range.selectNode(document.getElementById(id));
        window.getSelection().addRange(range);
        document.execCommand("copy");
        alert("Teks berhasil disalin")
      }
    }

    $('.sidebar-toggle').click(function() {
      console.log($('body').attr('class'));
      setGrafik();
    })

    $('.table_set1').DataTable({
      'paging': false,
      'bLengthChange': false,
      "bInfo": false,
      'searching': true,
      'ordering': true,
      'info': false,
      'scrollY': '301px',
      'scrollX': true,
      'scrollCollapse': true,
      'autoWidth': true,

    })

    function setGrafik(set = null) {
      if ($('body').hasClass('sidebar-collapse')) {
        $('#divSalesByCat').removeClass('col-sm-4');
        $('#divSalesByCat').addClass('col-sm-6');
        $('#divSalesCompFinco').removeClass('col-sm-4');
        $('#divSalesCompFinco').addClass('col-sm-6');
        $('#divSalesContribution').removeClass('col-sm-4');
        $('#divSalesContribution').addClass('col-sm-6');
      } else {
        $('#divSalesByCat').removeClass('col-sm-6');
        $('#divSalesByCat').addClass('col-sm-4');
        $('#divSalesCompFinco').removeClass('col-sm-6');
        $('#divSalesCompFinco').addClass('col-sm-4');
        $('#divSalesContribution').removeClass('col-sm-6');
        $('#divSalesContribution').addClass('col-sm-4');
      }
      // chartByCategory();
      chartByFinco();
      chartByCategory();
      chartByContribution();
    }
    $(document).ready(function() {
      setGrafik('awal');
      $('body').addClass('sidebar-collapse');
    });
    var chartCategory;

    function chartByCategory(categories = null) {
      values = {
        tanggal: '<?= $tgl ?>',
        categories: categories
      }
      $.ajax({
        beforeSend: function() {
          $('#chartByCategory').html('<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('dashboard/chartByCategoryDealer') ?>",
        type: "POST",
        data: values,
        cache: false,
        dataType: 'JSON',
        success: function(response) {
          // console.log(response);        
          chartCategory = new Highcharts.chart('chartByCategory', {
            chart: {
              type: 'column',
              // height: 200,
              spacingBottom: 15,
              spacingTop: 20,
              spacingLeft: 5,
              spacingRight: 15,
              // borderWidth: 1,
              // borderColor: '#ddd'
            },
            title: {
              text: null
            },
            legend: {
              enabled: true,
              padding: 0,
              margin: 2
            },
            credits: {
              enabled: true
            },
            xAxis: {
              categories: response.categories,
              // crosshair: true
            },
            yAxis: {
              min: 0,
              title: {
                text: 'Total Unit'
              }
            },
            series: response.series,
            responsive: {
              rules: [{
                condition: {
                  maxWidth: 500
                },
                chartOptions: {
                  legend: {
                    enabled: true
                  }
                }
              }]
            },
            plotOptions: {
              column: {
                dataLabels: {
                  enabled: true,
                  crop: false,
                  overflow: 'none'
                }
              },
              series: {
                cursor: 'pointer',
                point: {
                  events: {
                    click: function() {
                      chartByCategory(this.category);
                    }
                  }
                }
              }
            },
          });
        }
      })
    }

    var chartFinco;

    function chartByFinco() {
      values = {
        tanggal: '<?= $tgl ?>'
      }
      $.ajax({
        beforeSend: function() {
          $('#chartByFinco').html('<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('dashboard/chartByFincoDealer') ?>",
        type: "POST",
        data: values,
        cache: false,
        dataType: 'JSON',
        success: function(response) {
          // console.log(response);        
          chartFinco = new Highcharts.chart('chartByFinco', {
            chart: {
              type: 'column',
              spacingBottom: 15,
              spacingTop: 20,
              spacingLeft: 5,
              spacingRight: 15,
            },
            title: {
              text: null
            },
            subtitle: {
              text: ''
            },
            xAxis: {
              categories: response.categories,
              crosshair: true
            },
            yAxis: {
              min: 0,
              title: {
                text: 'Total Unit'
              }
            },
            series: response.series,
            responsive: {
              rules: [{
                condition: {
                  maxWidth: 500
                },
                chartOptions: {
                  legend: {
                    enabled: false
                  }
                }
              }]
            },
            plotOptions: {
              column: {
                dataLabels: {
                  enabled: true,
                  crop: false,
                  overflow: 'none'
                }
              },
              series: {
                cursor: 'pointer'
              }
            },
          });
        }
      })
    }

    function createChart(id, type, options, response) {
      var data = {
        labels: response.labels,
        datasets: [{
          label: '',
          data: response.data,
          backgroundColor: response.backgroundColor
        }]
      };

      new Chart(document.getElementById(id), {
        type: type,
        data: data,
        options: options
      });
    }

    function chartByContribution() {
      $.ajax({
        beforeSend: function() {
          $('#chartByContribution').html('<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('dashboard/chartByContributionDealer') ?>",
        type: "POST",
        data: values,
        cache: false,
        dataType: 'JSON',
        success: function(response) {
          createChart('chartByContribution', 'doughnut', {
            responsive: true,
            legend: {
              position: 'bottom',
            },
            maintainAspectRatio: false,
            cutoutPercentage: 61,
            plugins: {
              labels: {
                render: 'value',
                fontSize: 13,
                fontStyle: '',
                fontColor: '#2b2828',
                fontFamily: '"Lucida Console", Monaco, monospace'
              }
            }
          }, response);
        }
      })
    }

    function getSalesPerformanceDealer() {
      $.ajax({
        beforeSend: function() {
          $('#showSalesPerformance').html('<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('dashboard/getSalesPerformanceDealer') ?>",
        type: "POST",
        data: values,
        cache: false,
        success: function(response) {
          $('#showSalesPerformance').html(response);
          loadDatatables('showSalesPerformance');
        }
      })
    }

    function getSalesFincoByDP() {
      $.ajax({
        beforeSend: function() {
          $('#tabelSalesFincoByDP').html('<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('dashboard/getSalesFincoByDPDealer') ?>",
        type: "POST",
        data: values,
        cache: false,
        success: function(response) {
          $('#tabelSalesFincoByDP').html(response);
          loadDatatables('tabelSalesFincoByDP');
        }
      })
    }

    function getStokDealer() {
      $.ajax({
        beforeSend: function() {
          $('#showDetailStok').html('<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('dashboard/showDetailStokDealer') ?>",
        type: "POST",
        data: "",
        cache: false,
        success: function(response) {
          $('#showDetailStok').html(response);
          loadDatatables('showDetailStok');
        }
      })
    }

    function tampil_grafik() {
      //pie2();
    }

    function pie2() {

      var chart = new CanvasJS.Chart("container4", {
        theme: "light2", // "light1", "light2", "dark1", "dark2"
        exportEnabled: true,
        animationEnabled: true,
        // title: {
        //   text: "",
        //   FontSize: 10
        // },
        data: [{
          type: "pie",
          startAngle: 25,
          toolTipContent: "<b>{label}</b>: {y}%",
          showInLegend: "true",
          legendText: "{label}",
          indexLabelFontSize: 12,
          indexLabel: "{label} - {y}%",
          dataPoints: [
            <?php
            $id_dealer = $this->m_admin->cari_dealer();
            $bln = date("Y-m");
            $sql = $this->db->query("SELECT ms_finance_company.finance_company, COUNT(tr_sales_order.no_mesin) AS jum
      FROM tr_sales_order
      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
      INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
      WHERE tr_sales_order.id_dealer = '$id_dealer' AND tr_spk.jenis_beli = 'Kredit'
      AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bln'
      GROUP BY tr_spk.id_finance_company");
            foreach ($sql->result() as $isi) {
              //echo "{ y: 51.08, label: 'Chrome' },";
              $tt = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order
        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
        INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
        WHERE LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bln' AND tr_sales_order.id_dealer = '$id_dealer' AND tr_spk.jenis_beli = 'Kredit'")->row();


              $y = ($isi->jum / $tt->jum) * 100;
              $r = round($y, 2);
              echo "{ y : $r, label: '$isi->finance_company ($isi->jum unit)'},";
            }
            ?>
          ]
        }]
      });
      chart.render();

    }
  </script>