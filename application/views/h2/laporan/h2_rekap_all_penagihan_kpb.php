<?php
if ($set == "view") {
?>
  <base href="<?php echo base_url(); ?>" />
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
        <li class="">H2</li>
        <li class="">Laporan</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      </ol>
    </section>
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" id="frm" method="post" enctype="multipart/form-data">
                <div class="box-body" style="padding-bottom:0px">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Awal</label>
                    <div class="col-sm-3">
                      <input class="form-control datepicker" id="tgl_awal" readonly />
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Akhir</label>
                    <div class="col-sm-3">
                      <input class="form-control datepicker" id="tgl_akhir" readonly />
                    </div>
                  </div>
                  <div class="form-group" style="border-top:1px solid #f4f4f4">
                    <div class="col-sm-12" align="center" style="padding-top:10px">
                      <button type="button" onclick="getReport('preview')" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>
                      <button type="button" onclick="getReport('download')" name="process" value="edit" class="btn bg-blue btn-flat"><i class="fa fa-download"></i> Download .xls</button>
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div style="min-height: 600px">
                    <iframe style="overflow: auto; border: 0px solid #fff; width: 100%; height: 602px;margin-bottom: -5px;" id="showReport"></iframe>
                  </div>
                </div>
              </form>
            </div>

          </div>
        </div>
      </div><!-- /.box -->
      <script>
        function getReport(tipe) {
          var value = {
            tgl_awal: $('#tgl_awal').val(),
            tgl_akhir: $('#tgl_akhir').val(),
            tipe: tipe,
            cetak: 'cetak',
          }

          if (value.tgl_akhir == '' || value.tgl_awal == '' || value.kpb == '') {
            toastr_warning('Isi data dengan lengkap ..!');
            return false;
          } else {
            let values = JSON.stringify(value);
            $('.loader').show();
            $('#btnShow').disabled;
            $("#showReport").attr("src", '<?php echo site_url("h2/" . $isi . "?") ?>cetak=' + value.cetak + '&params=' + values);
            document.getElementById("showReport").onload = function(e) {
              $('.loader').hide();
            };
          }
        }
      </script>

    </section>
  </div>
<?php } elseif ($set == 'cetak') {
  if ($params->tipe == 'download') {
    header("Content-type: application/octet-stream");
    $file_name = remove_space($title, '_') . '.xls';
    header("Content-Disposition: attachment; filename=" . $file_name);
    header("Pragma: no-cache");
    header("Expires: 0");
  }
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Cetak</title>
    <style>
      @media print {
        @page {
          sheet-size: 330mm 210mm;
          margin-left: 0.8cm;
          margin-right: 0.8cm;
          margin-bottom: 0.8cm;
          margin-top: 1cm;
        }

        .text-center {
          text-align: center;
        }

        .bold {
          font-weight: bold;
        }

        .center {
          text-align: center;
        }

        .right {
          text-align: right;
        }

        .table {
          width: 100%;
          max-width: 100%;
          border-collapse: collapse;
          /*border-collapse: separate;*/
        }

        .table-bordered tr td {
          border: 0.01em solid black;
          padding-left: 6px;
          padding-right: 6px;
        }

        body {
          font-family: "Arial";
          font-size: 10pt;
        }
      }
    </style>
  </head>

  <body>
    <div style="text-align: center;font-size: 12pt"><b>MAIN DEALER : PT. Sinar Sentosa Primatama</b></div>
    <div style="text-align: center;font-size: 12pt"><b>Laporan Rekapan All Penagihan KPB</b></div>
    <div style="text-align: center;font-size: 12pt"><b>dari tanggal : <?= $params->tgl_awal ?></b></div>
    <div style="text-align: center;font-size: 12pt"><b>sampai tanggal : <?= $params->tgl_akhir ?></b></div>
    <hr>
    <table class='table table-bordered' border=1>
      <tr>
        <td rowspan=3 class='center bold'>No.</td>
        <td rowspan=3 class='center bold'>NO. AHASS</td>
        <td rowspan=3 class='center bold'>NAMA DEALER/AHASS</td>
        <td colspan=5 class='center bold'>KARTU PERAWATAN BERKALA</td>
        <td rowspan=3 class='center bold'>JUMLAH</td>
      </tr>
      <tr>
        <td class='center bold'>I</td>
        <td rowspan=2 class='center bold'>II</td>
        <td rowspan=2 class='center bold'>III</td>
        <td rowspan=2 class='center bold'>IV</td>
        <td rowspan=2 class='center bold'>TOTAL</td>
      </tr>
      <tr>
        <td class='center bold'>AHM</td>
      </tr>
      <?php
      for ($i = 1; $i <= 4; $i++) {
        $grand[$i] = 0;
      }
      $grand['qty'] = 0;
      $grand['jumlah'] = 0;
      foreach ($details as $kab) { ?>
        <?php $no = 1;
        for ($i = 1; $i <= 4; $i++) {
          $total[$i] = 0;
        }
        $total['qty'] = 0;
        $total['jumlah'] = 0;
        foreach ($kab['result_dealer'] as $rs_d) { ?>
          <tr>
            <td><?= $no ?></td>
            <td width='5%'><?= $params->tipe == 'download' ? "'" : '' ?><?= $rs_d['id_dealer'] ?></td>
            <td><?= $rs_d['nama_dealer'] ?></td>
            <?php $tot_kpb = 0;
            foreach ($rs_d['data']['kpb'] as $key => $kpb) {
              $tot_kpb += $kpb['qty'];
              $total[$key] += $kpb['qty'];
              $grand[$key] += $kpb['qty'];
            ?>
              <td class='center'><?= $kpb['qty'] ?></td>
            <?php } ?>
            <td class='center'><?= $tot_kpb ?></td>
            <td class='right'><?= mata_uang_rp($rs_d['data']['total']) ?></td>
          </tr>
        <?php
          $total['qty'] += $tot_kpb;
          $total['jumlah'] += $rs_d['data']['total'];
          $grand['qty'] += $tot_kpb;
          $grand['jumlah'] += $rs_d['data']['total'];
          $no++;
        } ?>
        <tr>
          <td colspan=3 class='center bold'><?= strtoupper($kab['kabupaten']) ?></td>
          <?php for ($i = 1; $i <= 4; $i++) {  ?>
            <td class='center bold'><?= $total[$i] ?></td>
          <?php } ?>
          <td class='center bold'><?= $total['qty'] ?></td>
          <td class='right bold'><?= mata_uang_rp($total['jumlah']) ?></td>
        </tr>
      <?php } ?>
      <tr>
        <td colspan=9>&nbsp;</td>
      </tr>
      <tr>
        <td colspan=3 class='center bold'>Total All</td>
        <?php for ($i = 1; $i <= 4; $i++) {  ?>
          <td class='center bold'><?= $grand[$i] ?></td>
        <?php } ?>
        <td class='center bold'><?= $grand['qty'] ?></td>
        <td class='right bold'><?= mata_uang_rp($grand['jumlah']) ?></td>
      </tr>
      <?php
      $ppn = $grand['jumlah'] * 0.1;
      $tot_dengan_ppn = $grand['jumlah'] + $ppn;
      ?>
      <tr>
        <td colspan=8 class='center bold'>Total PPn</td>
        <td class='right bold'><?= mata_uang_rp($ppn) ?></td>
      </tr>
      <tr>
        <td colspan=8 class='center bold'>Total + PPn</td>
        <td class='right bold'><?= mata_uang_rp($tot_dengan_ppn) ?></td>
      </tr>
    </table>

  </html>
<?php } ?>