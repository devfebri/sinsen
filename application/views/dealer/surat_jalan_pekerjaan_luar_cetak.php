<?php
if ($set == 'print_sj') { ?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Cetak</title>
    <style>
      @media print {
        @page {
          sheet-size: 210mm 297mm;
          margin-left: 0.8cm;
          margin-right: 0.8cm;
          margin-bottom: 1cm;
          margin-top: 1cm;
        }

        .text-center {
          text-align: center;
        }

        .table {
          width: 100%;
          max-width: 100%;
          border-collapse: collapse;
          /*border-collapse: separate;*/
        }

        .table-bordered tr td {
          border: 1px solid black;
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
    <table class="table table-borderedx">
      <tr>
        <td width="100%" align="center" colspan="5"><b>Surat Jalan Pekerjaan Luar</b><br>&nbsp;</td>
      </tr>
      <tr>
        <td width="20%">ID Surat Jalan</td>
        <td width='26%'>: <?= $row->id_surat_jalan ?></td>
        <td></td>
        <td width="16%">Tgl Surat Jalan</td>
        <td>: <?= date('d/m/Y', strtotime($row->tgl_surat_jalan)) ?></td>
      </tr>
      <tr>
        <td>Work Order Number</td>
        <td>: <?= $row->id_work_order ?></td>
        <td></td>

        <td>Vendor</td>
        <td>: <?= $row->nama_vendor ?></td>
      </tr>
      <tr>
        <td>Plat No</td>
        <td>: <?= $row->no_polisi ?></td>
      </tr>
      <tr>
        <td>Nama Customer</td>
        <td>: <?= $row->nama_customer ?></td>
      </tr>
      <tr>
        <td>Frame Number</td>
        <td>: <?= $row->no_rangka ?></td>
      </tr>
      <tr>
        <td>Engine Number</td>
        <td>: <?= $row->no_mesin ?></td>
      </tr>
    </table>
    <h4>Data Pekerjaan </h4>
    <table class="table table-bordered">
      <tr>
        <td>ID Pekerjaan</td>
        <td>Deskripsi</td>
        <td>Harga</td>
        <td>Harga Dari Vendor</td>
      </tr>
      <?php
      foreach ($pekerjaans as $dt) { ?>
        <tr>
          <td><?= $dt->id_jasa ?></td>
          <td><?= $dt->deskripsi ?></td>
          <td align='right'><?= mata_uang_rp($dt->harga) ?></td>
          <td align='right'><?= mata_uang_rp($dt->harga_dari_vendor) ?></td>
        </tr>
      <?php } ?>
    </table>
    <h4>Data Parts Related</h4>
    <table class="table table-bordered">
      <tr>
        <td>ID Part</td>
        <td>Deskripsi</td>
        <td>Qty</td>
      </tr>
      <?php
      foreach ($parts_related as $dt) {  ?>
        <tr>
          <td><?= $dt->id_part ?></td>
          <td><?= $dt->nama_part ?></td>
          <td><?= $dt->qty ?></td>
        </tr>
      <?php } ?>
    </table>
  </body>

  </html>
<?php } ?>