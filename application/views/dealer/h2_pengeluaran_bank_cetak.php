<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Cetak</title>
  <style>
    @media print {
      @page {
        sheet-size: 210mm 297mm;
        margin-left: 0.75cm;
        margin-right: 0.75cm;
        margin-bottom: 1cm;
        margin-top: 1cm;
      }

      .text-center {
        text-align: center;
      }

      .table {
        width: 100%;
        max-width: 100%;
        /* border-collapse: collapse; */
        /*border-collapse: separate;*/
      }

      .table-collapse {
        border-collapse: collapse;
      }

      .table-bordered tr td {
        border: 1px solid black;
        /* padding-left: 6px;
          padding-right: 6px; */
      }

      .tr-bordered td {
        border: 1px solid black;
        /* padding-left: 6px;
          padding-right: 6px; */
      }

      .border-top {
        border-top: 1px solid black;
      }

      .tr-border-tblr {
        border-top: 1px solid black;
        border-bottom: 1px solid black;
        border-right: 1px solid black;
        border-left: 1px solid black;
      }

      .tr-border-blr {
        /* border-top: 1px solid black; */
        border-bottom: 1px solid black;
        border-right: 1px solid black;
        border-left: 1px solid black;
      }

      body {
        font-family: arial;
        font-size: 11pt;
      }
    }
  </style>
</head>

<?php
if ($set == 'cetak') {
  $dl = dealer();
  $logo = $dl->logo;
  if ($dl->logo == NULL || $dl->logo == '') {
    $logo = 'logo_sinsen.jpg';
  }
  // send_json($logo);
?>

  <body style='display:table'>
    <table class='table table-bordered table-collapse'>
      <tr>
        <td style='padding-left:4.5cm; display: inline-block;padding-top:2.2cm;border-right:1px solid white;'><b>BUKTI PENGELUARAN KAS/BANK</b></td>
        <td style='border-left:1px solid white;height:3cm;width:5.3cm;vertical-align:top;text-align:center'>
          <img src='<?= base_url('assets/panel/images/') . $logo ?>' width='100px'>
          </br><br>
          <span style='font-size:8pt'><?= strtoupper($dl->nama_dealer) ?></span>
        </td>
      </tr>
    </table>

    <table class='table table-collapse'>
      <tr class='tr-border-blr'>
        <td colspan=2 style='line-height:0.7cm'>DIBAYAR KEPADA : <?= ucwords($row->dibayar_kepada) ?></td>
      </tr>
      <tr class='tr-bordered'>
        <td style='line-height:0.9cm;text-align:center;width:14.7cm'>UNTUK PEMBAYARAN</td>
        <td align='center' style='width:4.7cm'>J U M L A H</td>
      </tr>
      <tr class='tr-bordered'>
        <td style='height:5cm;width:14.7cm;vertical-align:top'>
          <table style='width:100%'>
            <?php foreach ($detail as $dt) { ?>
              <tr>
                <td style='border:1px solid white;width:80%'><?= $dt->keterangan ?></td>
                <td style='border:1px solid white;text-align:right'><?= 'Rp. ' . mata_uang_rp($dt->dibayar) ?></td>
              </tr>
            <?php } ?>
          </table>
        </td>
        <td align='center' style='height:5cm;width:4.7cm;vertical-align:top'>
          <table style='width:100%'>
            <?php $total = 0;
            foreach ($detail as $dt) { ?>
              <tr>
                <td style='border:1px solid white;text-align:right'><?= 'Rp. ' . mata_uang_rp($dt->dibayar) ?></td>
              </tr>
            <?php $total += $dt->dibayar;
            } ?>
          </table>
        </td>
      </tr>
      <tr class='tr-bordered'>
        <td style="border-bottom: 1px solid white;height:1.4cm">
          <table style='width:100%'>
            <tr>
              <td style='border:1px solid white;width:80%'>TERBILANG</td>
              <td style='border:1px solid white;'>TOTAL</td>
            </tr>
            <tr>
              <td style='border:1px solid white;'><i><?= number_to_words($total) . ' Rupiah' ?></i></td>
            </tr>
          </table>
        </td>
        <td align='right'>
          <?= 'Rp. ' . mata_uang_rp($total) ?>
        </td>
      </tr>
      <tr class='tr-bordered'>
        <td style="border-top: 1px solid white;border-bottom: 1px solid white;line-height:0.2cm" colspan=2>&nbsp;</td>
      </tr>
    </table>
    <table class='table table-collapse'>
      <tr class='tr-bordered' style='border-bottom:1px solid white;'>
        <td style='height:2.5cm;vertical-align:top'>Keterangan
          <table style='width:100%'>
            <tr>
              <td style='border:1px solid white;width:80%'>TERBILANG</td>
              <td style='border:1px solid white;'>TOTAL</td>
            </tr>
          </table>
        </td>
        <td style='height:2.5cm;vertical-align:top'>Disetujui</td>
        <td style='height:2.5cm;vertical-align:top'>Dibayar,</td>
        <td style='height:2.5cm;vertical-align:top'>Diterima,</td>
      </tr>
    </table>

  </body>

</html>
<?php } ?>