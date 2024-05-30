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
          /*  margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 1cm;
                margin-top: 1cm;*/
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
        <td width="30%">ID Surat Jalan</td>
        <td>: <?= $row->id_surat_jalan ?></td>
        <td></td>
        <td width="30%">Tgl Surat Jalan</td>
        <td>: <?= date('d/m/Y', strtotime($row->tgl_surat_jalan)) ?></td>
      </tr>
      <tr>
        <td width="30%">Work Order Number</td>
        <td>: <?= $cust->id_work_order ?></td>
      </tr>
      <tr>
        <td width="30%">Plat No</td>
        <td>: <?= $cust->no_polisi ?></td>
      </tr>
      <tr>
        <td width="30%">Nama Customer</td>
        <td>: <?= $cust->nama_customer ?></td>
      </tr>
      <tr>
        <td width="30%">Frame Number</td>
        <td>: <?= $cust->no_rangka ?></td>
      </tr>
      <tr>
        <td width="30%">Engine Number</td>
        <td>: <?= $cust->no_mesin ?></td>
      </tr>
    </table>
    <h4>Data Pekerjaan </h4>
    <table class="table table-bordered">
      <tr>
        <td>Kategori Pekerjaan</td>
        <td>Type Pekerjaan</td>
        <td>Pekerjaan</td>
        <td>Estimasi Biaya Service</td>
        <td>Estimasi Waktu Pekerjaan</td>
      </tr>
      <?php
      foreach ($detail as $dt) {
        $parts = $dt->parts ?>
        <tr>
          <td><?= $dt->kategori ?></td>
          <td><?= $dt->job_type ?></td>
          <td><?= $dt->pekerjaan ?></td>
          <td align='right'><?= mata_uang_rp($dt->harga) ?></td>
          <td><?= $dt->waktu ?></td>
        </tr>
      <?php } ?>
    </table>
    <?php if (isset($parts)) { ?>
      <h4>Data Parts </h4>
      <table class="table table-bordered">
        <tr>
          <td>ID Part</td>
          <td>Deskripsi</td>
          <td>Qty</td>
        </tr>
        <?php
        foreach ($parts as $dt) {  ?>
          <tr>
            <td><?= $dt->id_part ?></td>
            <td><?= $dt->nama_part ?></td>
            <td><?= $dt->qty ?></td>
          </tr>
        <?php } ?>
      </table>
    <?php } ?>
  </body>

  </html>
<?php } ?>