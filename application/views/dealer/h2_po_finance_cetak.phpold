<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
        /* padding-left: 6px;
          padding-right: 6px; */
      }

      body {
        font-family: "Arial";
        font-size: 11pt;
      }
    }
  </style>
</head>

<?php
if ($set == 'cetak') { ?>

  <body>
    <table>
      <tr>
        <td><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
      </tr>
    </table>
    <div style="text-align: center;font-size: 13pt"><b>KWITANSI</b></div>
    <br>
    <table class="table table-borderedx" style="margin-bottom:10px">
      <tr>
        <td width="20%">Nama Vendor</td>
        <td>
          :
          <?= $row->nama_vendor ?>
        </td>
      </tr>
      <tr>
        <td width="25%">Tanggal</td>
        <td>
          :
          <?= $row->tgl_po ?>
        </td>
      </tr>
    </table>
    <div style="text-align: center;font-size: 12pt"><b>ORDER PEMBELIAN</b></div>
    <div style="text-align: center;font-size: 11pt">No. Order : <?= $row->id_po ?></div>

    <table class="table table-bordered" style="margin-bottom:10px">
      <tr>
        <td>No.</td>
        <td>Nama Barang</td>
        <td>Qty</td>
        <td>Harga Satuan</td>
        <td>Total</td>
      </tr>
      <?php $no = 1;
      foreach ($detail as $val) { ?>
        <tr>
          <td><?= $no ?></td>
          <td><?= $val->nama_barang ?></td>
          <td><?= $val->qty ?></td>
          <td align="right">Rp. <?= mata_uang_rp($val->harga_satuan) ?></td>
          <td align="right">Rp. <?= mata_uang_rp((int) $val->subtotal) ?></td>
        </tr>
      <?php $no++;
      } ?>
      <tr>
        <td colspan="4"><b>Total</b></td>
        <td align="right"><b>Rp. <?= mata_uang_rp($row->total) ?></b></td>
      </tr>
    </table>
    <div style="text-align: left;font-size: 11pt">Keterangan :</div>
    <div style="text-align: left;font-size: 11pt"><?= $row->keterangan ?></div>

    <table style="width:100%;margin-top:20px">
      <tr>
        <td colspan=2 align="right">Jambi, <?= date('d/m/Y') ?></td>
      </tr>
      <tr>
        <td width="65%">
          Diketahui/Setuju Oleh,
          <br>
          Pimpinan/Wakil
          <br>
          <br>
          <br>
          <br>
          <br>
          (________________________)
        </td>
        <td>
          <br>
          <br>
          Pimpinan/Wakil
          <br>
          <br>
          <br>
          <br>
          <br>
          (________________________)
        </td>
      </tr>
    </table>
  </body>

</html>
<?php } ?>