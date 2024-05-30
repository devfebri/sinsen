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
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control datepicker required" value="<?= date('Y-m-d') ?>" id="tgl_pembayaran" />
                    </div>
                    <?php /*
                    <label for="inputEmail3" class="col-sm-1 control-label">End Date</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control datepicker required" name="tgl2" value="<?= date('Y-m-d') ?>" id="tgl_pembayaran2">
                    </div>
                    */ ?>
                  </div>
                  <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2 required" name="id_dealer" id="id_dealer">
                      <option value=''>-- Choose --</option>
                      <?php 
                       foreach ($dt_dealer as $isi) {
                        echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md - $isi->nama_dealer</option>";
                      }
                       ?>
                    </select>
                  </div>
                  </div>
                  <div class="form-group" style="border-top:1px solid #f4f4f4">
                    <div class="col-sm-12" align="center" style="padding-top:10px">
                      <button type="button" onclick="getReport('preview')" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>
                      <?php /* <button type="button" onclick="getReport('download')" name="process" value="edit" class="btn bg-blue btn-flat"><i class="fa fa-download"></i> Download .xls</button> */?>
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
            // tgl_pembayaran2: $('#tgl_pembayaran2').val(),
            id_dealer: $('#id_dealer').val(),
            tipe: tipe,
            cetak: 'cetak',
          }

          if (value.tgl_pembayaran == '' || value.id_dealer=='') {
            toastr_error('Isi data dengan lengkap ..!');
            return false;
          } else {
            let values = JSON.stringify(value);
            $('.loader').show();
            $('#btnShow').disabled;
            $("#showReport").attr("src", '<?php echo site_url("h1/h1_md_penerimaan_kas_harian?") ?>cetak=' + value.cetak + '&params=' + values);
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
    $file_name = 'Laporan_kas_harian_dealer.xls';
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
          sheet-size: 380mm 300mm;
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
    <?= kop_surat_dealer($params->id_dealer); ?>
    <div style="text-align: center;font-size: 12pt"><b><?= $title ?></b></div>
    <div style="text-align: center;font-size: 10pt"><b>Tgl. Penerimaan : <?= tgl_indo($params->tgl_pembayaran) ?></b></div>
    
    <hr>
    <table class='table table-borderedx' <?php $params->tipe == 'download' ? 'border=1' : ''; ?> style='font-size:8pt'>
      <tr style='border: 0.01em solid black;'>
        <td rowspan=2 class='center bold bordered'>No</td>
        <td rowspan=2 class='center bold bordered'>No SO</td>
        <td rowspan=2 class='center bold bordered'>No Kuitansi</td>
        <td rowspan=2 class='center bold bordered'>Tgl. Penerimaan</td>
        <td rowspan=2 class='center bold bordered' width='9%'>Nama Konsumen</td>
        <td colspan=5 class='center bold bordered'>Detail Penerimaan</td>
        <td colspan=4 class='center bold bordered'>Jumlah Penerimaan</td>
        <td colspan=3 class='center bold bordered'>Penarikan Titipan</td>
        <td colspan=3 class='center bold bordered'>Info Rekening/BG</td>
        <td rowspan=2 class='center bold bordered'>Sisa Piutang</td>
      </tr>
      <tr>
        <td class='center bold bordered'>DP</td>
        <td class='center bold bordered'>Angsuran</td>
        <td class='center bold bordered'>Denda</td>
        <td class='center bold bordered'>Discount</td>
        <td class='center bold bordered'>BBN</td>
        <td class='center bold bordered'>Tunai</td>
        <td class='center bold bordered'>KU</td>
        <td class='center bold bordered'>BG/Cek</td>
        <td class='center bold bordered'>Total</td>
        <td class='center bold bordered'>No Tanda Jadi</td>
        <td class='center bold bordered'>Tgl Titipan</td>
        <td class='center bold bordered'>Nominal</td>
        <td class='center bold bordered'>Bank</td>
        <td class='center bold bordered'>No Rek</td>
        <td class='center bold bordered'>Tanggal</td>
      </tr>
      <?php $no = 1;
      $total = [
        'dp' => 0,
        'angsuran' => 0,
        'denda' => 0,
        'diskon' => 0,
        'bbn' => 0,
        'tunai' => 0,
        'ku' => 0,
        'bg' => 0,
        'total' => 0,
        'tjs' => 0,
        'sisa_piutang' => 0,
      ];
      $grand = [
        'tunai' => 0,
        'ku' => 0,
        'bg' => 0,
        'total' => 0,
      ];
      $total_tjs = 0;
      foreach ($dp_pelunasan as $dp_pel) { ?>
        <?php
        $amount_tjs = isset($dp_pel['amount_tjs']) ? $dp_pel['amount_tjs'] : 0;
        $count_terima = count($dp_pel['detail_penerimaan']);
        $no_detail = 1;
        $total['dp'] += $dp_pel['amount_dp'];
        $total['angsuran'] += $dp_pel['angsuran'];
        $total['denda'] += $dp_pel['denda'];
        $total['diskon'] += $dp_pel['diskon'];
        $total['bbn'] += $dp_pel['bbn'];
        $total['sisa_piutang'] += $dp_pel['sisa_piutang'];
        foreach ($dp_pel['detail_penerimaan'] as $terima) {
          $total_tjs += $terima['nominal_tjs'];

          $subtotal = $terima['tunai'] + $terima['ku'] + $terima['bg'];
          $total['tunai'] += $terima['tunai'];
          $total['ku'] += $terima['ku'];
          $total['bg'] += $terima['bg'];
          $total['total'] += $subtotal;

          $grand['tunai'] += $terima['tunai'];
          $grand['ku'] += $terima['ku'];
          $grand['bg'] += $terima['bg'];
          $grand['total'] += $subtotal;
        ?>
          <tr>
            <?php
            if ($no_detail == 1) { ?>
              <td rowspan='<?= $count_terima ?>' style='vertical-align:top'><?= $no ?></td>
              <td rowspan='<?= $count_terima ?>' style='vertical-align:top'>&nbsp;<?= $dp_pel['id_sales_order'] ?>&nbsp;</td>
              <td rowspan='<?= $count_terima ?>' style='vertical-align:top'>&nbsp;<?= $dp_pel['id_kwitansi'] ?>&nbsp;</td>
              <td rowspan='<?= $count_terima ?>' style='vertical-align:top'>&nbsp;<?= date_dmy($dp_pel['tgl_penerimaan'], '-') ?>&nbsp;</td>
              <td rowspan='<?= $count_terima ?>' style='vertical-align:top'>&nbsp;<?= $dp_pel['nama_konsumen'] ?>&nbsp;</td>
              <td class='right' style='vertical-align:top' rowspan='<?= $count_terima ?>'><?= mata_uang_rp($dp_pel['amount_dp']) ?></td>
              <td class='right' style='vertical-align:top' rowspan='<?= $count_terima ?>'><?= mata_uang_rp($dp_pel['angsuran']) ?></td>
              <td class='right' style='vertical-align:top' rowspan='<?= $count_terima ?>'><?= mata_uang_rp($dp_pel['denda']) ?></td>
              <td class='right' style='vertical-align:top' rowspan='<?= $count_terima ?>'><?= mata_uang_rp($dp_pel['diskon']) ?></td>
              <td class='right' style='vertical-align:top' rowspan='<?= $count_terima ?>'><?= mata_uang_rp($dp_pel['bbn']) ?></td>
            <?php } ?>
            <td class='right'>&nbsp;<?= mata_uang_rp($terima['tunai']) ?>&nbsp;</td>
            <td class='right'>&nbsp;<?= mata_uang_rp($terima['ku']) ?>&nbsp;</td>
            <td class='right'>&nbsp;<?= mata_uang_rp($terima['bg']) ?>&nbsp;</td>
            <td class='right'>&nbsp;<?= mata_uang_rp($subtotal) ?>&nbsp;</td>
            <?php if ($no_detail == 1) { ?>
              <td style='vertical-align:top' rowspan='<?= $count_terima ?>'>&nbsp;<?= $terima['id_tjs'] ?>&nbsp;</td>
              <td style='vertical-align:top' rowspan='<?= $count_terima ?>'>&nbsp;<?= $terima['tgl_tjs'] ?>&nbsp;</td>
            <?php } ?>
            <td align='right'><?= mata_uang_rp($terima['nominal_tjs']) ?></td>
            <td><?= $terima['bank'] ?></td>
            <td><?= $terima['no_rek_bg'] ?></td>
            <td><?= $terima['tgl_terima'] ?></td>
            <?php if ($no_detail == 1) { ?>
              <td class='right' style='vertical-align:top' rowspan='<?= $count_terima ?>'><?= mata_uang_rp($dp_pel['sisa_piutang']) ?></td>
            <?php } ?>
          </tr>
        <?php $no_detail++;
        } ?>
      <?php $no++;
      } ?>
      <tr class=' border-top border-bottom'>
        <td colspan=5>T O T A L</td>
        <?php foreach ($total as $key => $tot) {
          $skip = ['tjs', 'sisa_piutang'];
          // $skip = [];
          if (in_array($key, $skip)) continue; ?>
          <td class='right'><?= mata_uang_rp($tot) ?></td>
        <?php } ?>
        <td colspan=2></td>
        <td class='right'><?= mata_uang_rp($total_tjs) ?></td>
        <td colspan=3></td>
        <td class='right'><?= mata_uang_rp($total['sisa_piutang']) ?></td>
      </tr>
      <tr>
        <td colspan=3><b>Titipan</b></td>
      </tr>
      <?php $no = 0;
      $total_tjs = [
        'tunai' => 0,
        'ku' => 0,
        'bg' => 0,
        'total' => 0,
      ];
      foreach ($tjs as $tj) { ?>
        <?php $no_d = 1;
        $count_terima = count($tj['detail_penerimaan']);

        foreach ($tj['detail_penerimaan'] as $terima) {
          $subtotal = $terima['tunai'] + $terima['ku'] + $terima['bg'];
          $total_tjs['tunai'] += $terima['tunai'];
          $total_tjs['ku'] += $terima['ku'];
          $total_tjs['bg'] += $terima['bg'];
          $total_tjs['total'] += $subtotal;

          $grand['tunai'] += $terima['tunai'];
          $grand['ku'] += $terima['ku'];
          $grand['bg'] += $terima['bg'];
          $grand['total'] += $subtotal;

        ?>
          <tr>
            <?php if ($no_d == 1) {
              $no++; ?>
              <td rowspan='<?= $count_terima ?>' style='vertical-align:top'><?= $no ?></td>
              <td rowspan='<?= $count_terima ?>' style='vertical-align:top'><?= $tj['id_sales_order'] ?></td>
              <td rowspan='<?= $count_terima ?>' style='vertical-align:top'><?= $tj['id_kwitansi'] ?></td>
              <td rowspan='<?= $count_terima ?>' style='vertical-align:top'><?= date_dmy($tj['tgl_penerimaan'], '-') ?></td>
              <td rowspan='<?= $count_terima ?>' style='vertical-align:top'><?= $tj['nama_konsumen'] ?></td>
              <td rowspan='<?= $count_terima ?>' colspan=5 style='vertical-align:top'><?= $tj['keterangan'] ?></td>
            <?php } ?>
            <td class='right'><?= mata_uang_rp($terima['tunai']) ?></td>
            <td class='right'><?= mata_uang_rp($terima['ku']) ?></td>
            <td class='right'><?= mata_uang_rp($terima['bg']) ?></td>
            <td class='right'><?= mata_uang_rp($subtotal) ?></td>
            <?php if ($no_d == 1) { ?>
              <td rowspan='<?= $count_terima ?>' colspan=3></td>
            <?php } ?>
            <td class='right'><?= mata_uang_rp($terima['bank']) ?></td>
            <td class='right'><?= mata_uang_rp($terima['no_rek_bg']) ?></td>
            <td class='right'><?= mata_uang_rp($terima['tgl_terima']) ?></td>
            <?php if ($no_d == 1) { ?>
              <td rowspan='<?= $count_terima ?>'></td>
            <?php } ?>
          </tr>
        <?php $no_d++;
        } ?>
      <?php } ?>
      <tr class='border-top border-bottom'>
        <td colspan=10>Total Titipan</td>
        <?php foreach ($total_tjs as $key => $tot) { ?>
          <td class='right'><?= mata_uang_rp($tot) ?></td>
        <?php } ?>
        <td colspan=11></td>
      </tr>
      <tr class=' border-top border-bottom'>
        <td colspan=10>Total Penerimaan</td>
        <?php foreach ($grand as $key => $gr) { ?>
          <td class='right'><?= mata_uang_rp($gr) ?></td>
        <?php } ?>
        <td colspan=7></td>
      </tr>
    </table>
  </body>

  </html>
<?php } ?>