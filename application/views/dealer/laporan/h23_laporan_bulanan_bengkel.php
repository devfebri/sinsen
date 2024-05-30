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
                    <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                    <div class="col-sm-3">
                      <input class="form-control monthpicker" id="bulan" readonly />
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
            bulan: $('#bulan').val(),
            bulan_akhir: $('#bulan_akhir').val(),
            tipe: tipe,
            cetak: 'cetak',
          }

          if (value.bulan_akhir == '' || value.bulan == '' || value.kpb == '') {
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
          sheet-size: 210mm 330mm;
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

        .bold {
          font-weight: bold;
        }

        body {
          font-family: "Arial";
          font-size: 10pt;
        }
      }
    </style>
  </head>

  <body>
    <div style="text-align: center;font-size: 13pt"><b><?= $title ?></b></div>
    <hr>
    <table class='table'>
      <tr>
        <td class='bold'>NOMOR</td>
        <td>: <?= dealer()->kode_dealer_md ?></td>
        <td class='bold'>LAP. BULAN</td>
        <td>: <?= $params->bulan ?></td>
      </tr>
      <tr>
        <td class='bold'>NAMA</td>
        <td>: <?= dealer()->nama_dealer ?></td>
        <td class='bold'>TGL. DIBUAT</td>
        <td>: <?= date_dmy(tanggal()) ?></td>
      </tr>
      <tr>
        <td class='bold'>KOTA</td>
        <td>: <?= dealer()->kabupaten ?></td>
      </tr>
    </table>
    <table class="table">
      <tr>
        <td colspan=2>
          <b>I. LAPORAN PENDAPATAN BENGKEL</b> <br>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <b>II. JUMLAH JOB DAN UNIT KENDARAAN YANG DIKERJAKAN</br>
            <table class="table table-bordered">
              <tr>
                <td rowspan=2>TIPE</td>
                <td colspan=4>ASS</td>
                <td rowspan=2>CLAIM</td>
                <td colspan=4>QUICK SERVICE</td>
                <td rowspan=2>HR</td>
                <td rowspan=2>OTHER</td>
                <td rowspan=2>JR</td>
                <td rowspan=2>TOTAL JOB</td>
                <td rowspan=2>UNIT ENTRI</td>
              </tr>
              <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>CS</td>
                <td>LS</td>
                <td>OR +</td>
                <td>LR</td>
              </tr>
            </table>
        </td>
      </tr>
      <tr>
        <td style="vertical-align:top">
          <b>III. LAPORAN PENGELUARAN OPERASIONAL BENGKEL</b>
        </td>
        <td>
          <b>IV. JUMLAH HARI KERJA DALAM BULAN INI :</b> <br>
          <b>V. RATA-RATA UNIT ENTRI PER HARI :</br>
        </td>
      </tr>
      <tr>
        <td><b>VI. LAPORAN PRESTASI MEKANIK</b></td>
      </tr>
    </table>
  </body>

  </html>
<?php } ?>