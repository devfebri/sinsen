<?php
header("Content-type: application/octet-stream");
$file_name = remove_space($title, '_') . '.xls';
header("Content-Disposition: attachment; filename=" . $file_name);
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->

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
        font-size: 11pt;
      }
    }
  </style>
</head>

<body>
  <?= kop_surat_dealer($this->m_admin->cari_dealer()); ?>
  <div style="text-align: center;font-size: 13pt">
    <b><?= $title ?></b></div>
  <table class="table table-bordered" border=1>
    <tr>
      <td>No.</td>
      <td>ID Sales Order</td>
      <td>Tgl. Pengiriman</td>
      <td>Nama Customer</td>
      <td>No. Kontak</td>
      <td>Deskripsi Tipe Unit</td>
      <td>Deskripsi Warna</td>
      <td>Sales People</td>
    </tr>
    <?php $no = 1;
    foreach ($details  as $dt) { ?>
      <tr>
        <td><?= $no ?></td>
        <td><?= $dt->id_sales_order ?></td>
        <td><?= $dt->tgl_pengiriman ?></td>
        <td><?= $dt->nama_konsumen ?></td>
        <td><?= $dt->no_hp ?></td>
        <td><?= $dt->desc_unit ?></td>
        <td><?= $dt->warna ?></td>
        <td><?= $dt->sales ?></td>
      </tr>
    <?php $no++;
    } ?>
  </table>
</body>

</html>