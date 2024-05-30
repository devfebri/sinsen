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
        <li class="">H1</li>
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
                  <!-- <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>
                    <div class="col-sm-3">
                      <input class="form-control datepicker" id="tgl_pembayaran" readonly />
                    </div>
                  </div> -->
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
            tgl_pembayaran: $('#tgl_pembayaran').val(),
            tgl_akhir: $('#tgl_akhir').val(),
            tipe: tipe,
            cetak: 'cetak',
          }

          if (value.tgl_pembayaran == '') {
            toastr_error('Isi data dengan lengkap ..!');
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
          sheet-size: 297mm 210mm;
          margin-left: 0.5cm;
          margin-right: 0.5cm;
          margin-bottom: 1cm;
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

        .bordered {
          border: 0.01em solid black;
        }

        .border-top td {
          border-top: 0.01em solid black;
        }

        .border-bottom td {
          border-bottom: 0.01em solid black;
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
    <div style="text-align: center;font-size: 12pt"><b><?= $title ?></b></div>
    <hr>
    <table class='table table-bordered' <?php $params->tipe == 'download' ? 'border=1' : ''; ?> style='font-size:10pt;width:100%'>
      <tr>
        <td bgcolor='yellow' rowspan=2 class='center bold'>No</td>
        <td bgcolor='yellow' rowspan=2 class='center bold' width='30%'>Type</td>
        <td bgcolor='yellow' colspan=5 class='center bold'>Sales</td>
        <td bgcolor='yellow' colspan=3 class='center bold'>Daily Sales</td>
        <td bgcolor='yellow' rowspan=2 class='center bold'>Stock D</td>
        <td bgcolor='yellow' rowspan=2 class='center bold'>Stock Days</td>
      </tr>
      <tr>
        <td bgcolor='yellow' class='center bold'>M-1</td>
        <td bgcolor='yellow' class='center bold'>M</td>
        <td bgcolor='yellow' class='center bold'>Growth (Unit)</td>
        <td bgcolor='yellow' class='center bold'>Growth (%)</td>
        <td bgcolor='yellow' class='center bold'>Outlook</td>
        <td bgcolor='yellow' class='center bold'>M-1</td>
        <td bgcolor='yellow' class='center bold'>M</td>
        <td bgcolor='yellow' class='center bold'>Growth (%)</td>
      </tr>
      <?php foreach ($detail as $dt) { ?>
        <tr>
          <td><?= $dt['no'] ?></td>
          <td><?= $dt['tipe_ahm'] ?></td>
          <td><?= $dt['sales_m_1'] ?></td>
          <td><?= $dt['sales_m'] ?></td>
          <td><?= $dt['growth'] ?></td>
          <td><?= $dt['growth_persen'] ?></td>
          <td><?= $dt['outlook'] ?></td>
          <td><?= $dt['daily_sales_m_1'] ?></td>
          <td><?= $dt['daily_sales_m'] ?></td>
          <td><?= $dt['growth_persen_sales'] ?></td>
          <td><?= $dt['stock_dealer'] ?></td>
          <td><?= $dt['stock_days'] ?></td>
        </tr>
      <?php } ?>
    </table>
  </body>

  </html>
<?php } ?>