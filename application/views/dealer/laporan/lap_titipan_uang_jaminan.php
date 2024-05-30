<?php
if ($set == "view") {
?>
  <base href="<?php echo base_url(); ?>" />
  <script src="assets/panel/plugins/datepicker/bootstrap-datepicker.js"></script>
  <script type="text/javascript" src="<?= base_url("assets/moment/moment.min.js") ?>"></script>
  <script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
  <link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
  <script>
    $(function() {
      $('#periode').daterangepicker({
        opens: 'left',
        autoUpdateInput: false,
        locale: {
          format: 'DD/MM/YYYY'
        }
      }, function(start, end, label) {
        $('#start_date').val(start.format('YYYY-MM-DD'));
        $('#end_date').val(end.format('YYYY-MM-DD'));
      }).on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
      }).on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $('#start_date').val('');
        $('#end_date').val('');
      });
    });
  </script>
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
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Periode</label>
                    <div class="col-sm-3">
                      <input id='periode' name='periode' type="text" class="form-control" readonly>
                      <input id='start_date' name='start_date' type="hidden" class="form-control" readonly>
                      <input id='end_date' name='end_date' type="hidden" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12" align="center">
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
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            tipe: tipe,
            cetak: 'cetak',
          }

          if (value.tanggal == '') {
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
    header("Content-Disposition: attachment; filename=$file_name.xls");
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
    <div style="text-align: center; font-weight: bold;">Periode : <?php echo $params->start_date . ' - ' . $params->end_date ?></div>
    <hr>
    <table class='table table-bordered'>
      <tr>
        <td><b>No. Uang Jaminan</b></td>
        <td><b>Tgl. Pembayaran</b></td>
        <td><b>Nama Konsumen</b></td>
        <td><b>Jml. Uang Jaminan</b></td>
        <td><b>Tgl. Penarikan Titipan</b></td>
        <td><b>NSC/ No. Bukti Pengeluaran</b></td>
      </tr>
      <?php foreach ($details as $dtl) { ?>
        <tr>
          <td><?= $dtl->no_inv_uang_jaminan ?></td>
          <td><?= $dtl->tgl_uang_jaminan ?></td>
          <td><?= $dtl->nama_customer ?></td>
          <td align='right'>Rp. <?= mata_uang_rp($dtl->total_bayar) ?></td>
          <td></td>
          <td></td>
        </tr>
      <?php } ?>
    </table>
    <div>Dicetak : <?= kry_login($this->session->userdata('id_user'))->nama_lengkap . ' ' . waktu() ?></div>
  </body>

  </html>
<?php } ?>