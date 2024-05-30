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
        <li class="">Finance H23</li>
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
                    <label for="inputEmail3" class="col-sm-2 control-label">Bulan Awal</label>
                    <div class="col-sm-3">
                      <input class="form-control monthpicker" id="bulan_awal" readonly />
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Bulan Akhir</label>
                    <div class="col-sm-3">
                      <input class="form-control monthpicker" id="bulan_akhir" readonly />
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
            bulan_awal: $('#bulan_awal').val(),
            bulan_akhir: $('#bulan_akhir').val(),
            tipe: tipe,
            cetak: 'cetak',
          }

          if (value.bulan_akhir == '' || value.bulan_awal == '' || value.kpb == '') {
            alert('Isi data dengan lengkap ..!');
            return false;
          } else {
            let values = JSON.stringify(value);
            $('.loader').show();
            $('#btnShow').disabled;
            $("#showReport").attr("src", '<?php echo site_url("dealer/" . $isi . "?") ?>cetak=' + value.cetak + '&params=' + values);
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
          margin-bottom: 1cm;
          margin-top: 1cm;
        }

        .text-center {
          text-align: center;
        }

        .bold {
          font-weight: bold;
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
    <table>
      <tr>
        <td><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
      </tr>
    </table>
    <div style="text-align: center;font-size: 13pt"><b><?= $title ?></b></div>
    <hr>
    <?php
    //Details
    foreach ($details as $key => $dt) { ?>
      <h3><?= $kelompok[$key] ?></h3>
      <table class="table table-bordered" border=1>
        <tr>
          <td rowspan=2><b>No.</b></td>
          <td rowspan=2><b>Bulan</b></td>
          <?php for ($i = 1; $i <= 31; $i++) {
          ?>
            <td align='center' colspan=6><?= $i ?></td>
          <?php } ?>
          <td align='center' colspan=6>Total</td>
        </tr>
        <tr>
          <?php for ($i = 1; $i <= 32; $i++) {
            $tots[$i]['ue'] = 0;
            $tots[$i]['ass1'] = 0;
            $tots[$i]['ass24'] = 0;
            $tots[$i]['others'] = 0;
            $tots[$i]['oil'] = 0;
          ?>
            <td align='center'>UE</td>
            <td align='center'>ASS 1</td>
            <td align='center'>ASS 2-4</td>
            <td align='center'>Others</td>
            <td align='center'>Oil</td>
            <td align='center'>% Ach</td>
          <?php } ?>
        </tr>
        <?php $no = 1;
        foreach ($dt as $dts) { ?>
          <tr>
            <td><?= $no ?></td>
            <td><?= $dts['bulan'] ?></td>
            <?php
            $tot = ['ue' => 0, 'ass1' => 0, 'ass24' => 0, 'others' => 0, 'oil' => 0];
            $i = 1;
            foreach ($dts['data'] as $dts_dt) {
              $tots[$i]['ue'] += $dts_dt->ue;
              $tots[$i]['ass1'] += $dts_dt->ass1;
              $tots[$i]['ass24'] += $dts_dt->ass24;
              $tots[$i]['others'] += $dts_dt->others;
              $tots[$i]['oil'] += $dts_dt->oil;

              $tot['ue'] += $dts_dt->ue;
              $tot['ass1'] += $dts_dt->ass1;
              $tot['ass24'] += $dts_dt->ass24;
              $tot['others'] += $dts_dt->others;
              $tot['oil'] += $dts_dt->oil;

            ?>
              <td><?= $dts_dt->ue ?></td>
              <td><?= $dts_dt->ass1 ?></td>
              <td><?= $dts_dt->ass24 ?></td>
              <td><?= $dts_dt->others ?></td>
              <td><?= $dts_dt->oil ?></td>
              <td><?= $dts_dt->ach ?></td>
            <?php $i++;
            }
            $tots[$i]['ue'] += $tot['ue'];
            $tots[$i]['ass1'] += $tot['ass1'];
            $tots[$i]['ass24'] += $tot['ass24'];
            $tots[$i]['others'] += $tot['others'];
            ?>
            <td><?= $tot['ue'] ?></td>
            <td><?= $tot['ass1'] ?></td>
            <td><?= $tot['ass24'] ?></td>
            <td><?= $tot['others'] ?></td>
            <td><?= $tot['oil'] ?></td>
            <td><?= @ROUND($tot['oil'] / $tot['ue']) ?></td>
          </tr>
        <?php $no++;
        }
        ?>
        <tr>
          <td colspan=2>Total</td>
          <?php
          // send_json($tots);
          foreach ($tots as $val) {
            foreach ($val as $v) { ?>
              <td><?= $v ?></td>
            <?php } ?>
            <td><?= @ROUND($val['oil'] / $val['ue']) ?></td>
          <?php }
          ?>
        </tr>
      </table>
    <?php
    } // Details
    // die();
    ?>
  </body>

  </html>
<?php } ?>