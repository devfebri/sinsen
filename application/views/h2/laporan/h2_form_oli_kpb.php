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
                      <input class="form-control" id="tgl_awal" readonly />
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Akhir</label>
                    <div class="col-sm-3">
                      <input class="form-control" id="tgl_akhir" readonly />
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
        $(function() {
          var end_date = '';
          $("#tgl_awal").datepicker({
            autoclose: true,
            // todayHighlight: true,
            format: "dd/mm/yyyy"
          }).on("changeDate", function(e) {
            cekTanggal()
          });

          $("#tgl_akhir").datepicker({
            autoclose: true,
            // todayHighlight: true,
            format: "dd/mm/yyyy"
          }).on("changeDate", function(e) {
            cekTanggal()
          });
        });

        function cekTanggal() {
          start = moment($('#tgl_awal').val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
          end = moment($('#tgl_akhir').val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
          start = Date.parse(start);
          end = Date.parse(end);
          if (end < start) {
            alert('Tanggal awal lebih besar !')
            $('#tgl_akhir').val('')
          }
        }

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
    <div style="text-align: center;font-size: 12pt"><b>Laporan Pencairan Oli</b></div>
    <div style="text-align: center;font-size: 12pt"><b>dari tanggal : <?= $params->tgl_awal ?></b></div>
    <div style="text-align: center;font-size: 12pt"><b>sampai tanggal : <?= $params->tgl_akhir ?></b></div>
    <hr>
    <table class="table table-bordered" border=1>
      <tr>
        <td rowspan=2 class='bold center'>NO.</td>
        <td rowspan=2 class='bold center'>NO. AHASS</td>
        <td rowspan=2 class='bold center'>NAMA. AHASS</td>
        <td colspan='<?= count($tipe_5) + 1 ?>' class='bold center'>AHM OIL</td>
      </tr>
      <tr>
        <?php foreach ($tipe_5 as $key => $tp) {
          $grand_tipe_5[$key] = 0;
        ?>
          <td class='bold center'><?= strtoupper($tp->nama_tipe) ?></td>
        <?php } ?>
        <td class='bold center'>TOTAL</td>
      </tr>
      <?php $no = 1;
      foreach ($details as $dtl) {
        foreach ($tipe_5 as $key => $tp) {
          $total_kab[$dtl['id_kabupaten']][$key] = 0;
        }
      ?>
        <?php foreach ($dtl['result_dealer'] as $rd_d) { ?>
          <tr>
            <td width='5%'><?= $no ?></td>
            <td width='5%'><?=$params->tipe=='download'?"'":''?><?= $rd_d['id_dealer'] ?></td>
            <td width='20%'><?= $rd_d['nama_dealer'] ?></td>
            <?php $tot = 0;
            foreach ($rd_d['data'] as $key => $val) {
              $total_kab[$dtl['id_kabupaten']][$key] += $val;
              $grand_tipe_5[$key] += (int)$val;
            ?>
              <td align='center'><?= (int)$val ?></td>
            <?php $tot += $val;
            } ?>
            <td width='5%' align='center'><?= $tot; ?></td>
          </tr>
        <?php $no++;
        } ?>
        <tr>
          <td class='bold center' colspan=3><?= strtoupper($dtl['kabupaten']) ?></td>
          <?php $tot_kab = 0;
          foreach ($total_kab[$dtl['id_kabupaten']] as $key => $val) { ?>
            <td class='center bold'><?= $val ?></td>
          <?php $tot_kab += $val;
          } ?>
          <td class='center bold'><?= $tot_kab ?></td>
        </tr>
      <?php } ?>
      <tr>
        <td colspan=<?= count($tipe_5) + 4 ?>>&nbsp;</td>
      </tr>
      <tr>
        <td align='center' colspan=3><b>TOTAL ALL</b></td>
        <?php $total = 0;
        foreach ($grand_tipe_5 as $val) { ?>
          <td align='center'><b><?= $val ?></b></td>
        <?php $total += $val;
        } ?>
        <td class='center'><b><?= $total ?></b></td>
      </tr>
      <tr>
        <td colspan=3 rowspan=2></td>
        <?php $grand_tot = [];
        foreach ($tipe_5 as $key => $val) {
          $harga = $val->harga;
          $grand_tot[$key] = $harga * $grand_tipe_5[$key];
        ?>
          <td class='center bold'><?= $harga ?></td>
        <?php } ?>
      </tr>
      <tr>
        <?php foreach ($grand_tot as $val) { ?>
          <td class='center bold'><?= $val ?></td>
        <?php } ?>
      </tr>
    </table>
    <h3 align='left' style='margin-left:140px'>Total Keseluruhan = <?= array_sum($grand_tot) ?></h3>
    <table width='100%' class='table-borderedx'>
      <tr>
        <td width='40%'>
          <br>
          Disetujui oleh,
          <br>
          <br>
          <br>
          <br>
          <br>
          <u>Drs. Tony Attan, SH</u><br>
          Direktur
        </td>
        <td width='40%' align='center'>
          <br>
          Dibuat oleh,
          <br>
          <br>
          <br>
          <br>
          <br>
          <u>Evi Chustina</u><br>
          Adm. KPB
        </td>
        <td align='center'>
          Jambi, <?= date_dmy(get_ymd(), '-') ?><br>
          Dikirimkan oleh,
          <br>
          <br>
          <br>
          <br>
          <br>
          (_______________________________)
        </td>
      </tr>
    </table>
  </body>

  </html>
<?php } ?>