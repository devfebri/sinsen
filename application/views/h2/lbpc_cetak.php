<?php
if ($set == 'print') { ?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Cetak LBPC</title>
    <style>
      @media print {
        @page {
          sheet-size: 297mm 210mm;
          margin-left: 0.8cm;
          margin-right: 0.8cm;
          margin-bottom: 0.8cm;
          margin-top: 0.8cm;
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
          font-size: 10pt;
        }

        .bg-gray {
          background-color: #eee;
        }
      }
    </style>
  </head>

  <body>
    <table class="table table-borderedx">
      <tr>
        <td rowspan=3 style='width:70%;font-size:12pt'><b>LAPORAN BIAYA PENGGANTIAN CLAIM</b></td>
        <td>Main Dealer</td>
        <td>: PT. SINAR SENTOSA PRIMATAMA</td>
      </tr>
      <tr>
        <td>No. LBPC</td>
        <td>: <?= $row->no_lbpc ?></td>
      </tr>
      <tr>
        <td>Tanggal</td>
        <td>: <?= $row->tgl_lbpc ?></td>
      </tr>
    </table>
    <table class="table table-bordered" style="margin-top:20px">
      <tr class='bg-gray'>
        <td>NO</td>
        <td>NOMOR REGISTRASI CLAIM</td>
        <td>NOMOR PART</td>
        <td>NAMA PART</td>
        <td>JML</td>
        <td>HET<br>(DIGANTI UANG)</td>
        <td>ONGKOS KERJA</td>
        <td>HET<br>(DIGANTI PARTS)</td>
      </tr>
      <?php foreach ($detail['parts'] as $key => $val) { ?>
        <tr>
          <td><?= $key + 1 ?></td>
          <td><?= $val->no_registrasi ?></td>
          <td><?= $val->id_part ?></td>
          <td><?= $val->nama_part ?></td>
          <td><?= mata_uang_rp($val->qty) ?></td>
          <td align='right'><?= mata_uang_rp($val->ganti_uang) ?></td>
          <td align='right'><?= mata_uang_rp($val->ongkos) ?></td>
          <td align='right'><?= mata_uang_rp($val->ganti_part) ?></td>
        </tr>
      <?php } ?>
      <tr>
        <td colspan=8>&nbsp;</td>
      </tr>
      <tr>
        <td colspan=4 align='right'>Sub Total</td>
        <td></td>
        <td align='right'><?= mata_uang_rp($detail['ganti_uang']) ?></td>
        <td align='right'><?= mata_uang_rp($detail['ongkos']) ?></td>
        <td align='right'><?= mata_uang_rp($detail['ganti_part']) ?></td>
      </tr>
      <tr>
        <td colspan=4 align='right'>Yang Ditagih</td>
        <td></td>
        <td align='right'><?= mata_uang_rp($detail['subtotal']) ?></td>
        <td align='right'><?= mata_uang_rp($detail['ongkos']) ?></td>
        <td align='right'><?= mata_uang_rp($detail['ganti_part']) ?></td>
      </tr>
      <tr>
        <td colspan=4 align='right'>Total Tagihan</td>
        <td></td>
        <td align='right'><?= mata_uang_rp($detail['tot_tagihan']) ?></td>
        <td></td>
        <td></td>
      </tr>
    </table>
    <table class='table table-bordered' style='width:40%;margin-top:40px'>
      <tr>
        <td align='center'>Mengetahui</td>
        <td align='center'>Menyetujui</td>
      </tr>
      <tr>
        <td align='center'>
          <br>
          <br>
          <br>
          <br>
          (ERWIN SUSANTO)<br>
          SEVICE MANAGER
        </td>
        <td align='center'>
          <br>
          <br>
          <br>
          <br>
          (FADLI NUR PERMADI)<br>
          CLAIM SUPERVISOR
        </td>
      </tr>
    </table>
  </body>

  </html>
<?php } ?>
<?php if ($set == 'download_excel') :
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=LBPC (Lembaran Biaya Penggantian Claim).xls");
  header("Pragma: no-cache");
  header("Expires: 0");
?>
  <div align="center">LBPC (Lembaran Biaya Penggantian Claim)</div>
  <table border="1">
    <tr>
      <td>No</td>
      <td>ID LBPC</td>
      <td>No Registrasi</td>
      <td>Tgl Pengajuan</td>
      <td>Kode AHASS</td>
      <td>Nama AHASS</td>
      <td>No Mesin</td>
      <td>No Rangka</td>
      <td>Tgl Pembelian</td>
      <td>Tgl Kerusakan</td>
    </tr>
    <?php $no = 1;
    foreach ($result->result() as $rs) : ?>
      <tr>
        <td><?= $no ?></td>
        <td><?= $rs->no_lbpc ?></td>
        <td><?= $rs->no_registrasi ?></td>
        <td><?= $rs->tgl_pengajuan ?></td>
        <td><?= (string) $rs->kode_dealer_md ?></td>
        <td><?= $rs->nama_dealer ?></td>
        <td><?= $rs->no_mesin ?></td>
        <td><?= $rs->no_rangka ?></td>
        <td><?= $rs->tgl_pembelian ?></td>
        <td><?= $rs->tgl_kerusakan ?></td>
      </tr>
    <?php $no++;
    endforeach ?>
  </table>
<?php endif ?>
<?php if ($set == 'cetak_processed') : ?>
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
        <td width="100%" align="center" colspan="5"><b>Cetak LBPC (Lembaran Biaya Penggantian Claim)</b><br>&nbsp;</td>
      </tr>
      <tr>
        <td width="20%">ID LBPC</td>
        <td>: <?= $row->no_lbpc ?></td>
        <td width="10%"></td>
        <td width="20%">Tanggal</td>
        <td>: <?= $row->tgl_lbpc ?></td>
      </tr>
    </table>
  </body>

  </html>
<?php endif ?>