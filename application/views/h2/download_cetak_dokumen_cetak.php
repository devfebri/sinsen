<?php
if ($set == 'lbpc_ahm') { ?>
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
        <td style='border:none' colspan=4 align='right'>Sub Total</td>
        <td style='border:none'></td>
        <td style='border:none' align='right'><?= mata_uang_rp($detail['ganti_uang']) ?></td>
        <td style='border:none' align='right'><?= mata_uang_rp($detail['ongkos']) ?></td>
        <td style='border:none' align='right'><?= mata_uang_rp($detail['ganti_part']) ?></td>
      </tr>
      <tr>
        <td style='border:none' colspan=4 align='right'>Yang Ditagih</td>
        <td style='border:none'></td>
        <td style='border:none' align='right'><?= mata_uang_rp($detail['subtotal']) ?></td>
        <td style='border:none' align='right'><?= mata_uang_rp($detail['ongkos']) ?></td>
        <td style='border:none' align='right'><?= mata_uang_rp($detail['ganti_part']) ?></td>
      </tr>
      <tr>
        <td style='border:none' colspan=4 align='right'>Total Tagihan</td>
        <td style='border:none'></td>
        <td style='border:none' align='right'><?= mata_uang_rp($detail['tot_tagihan']) ?></td>
        <td style='border:none'></td>
        <td style='border:none'></td>
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
<?php } elseif ($set == 'rekap_lbpc_internal') {
  header("Content-type: application/octet-stream");
  $file_name = remove_space($title, '_') . '.xls';
  header("Content-Disposition: attachment; filename=" . $file_name);
  header("Pragma: no-cache");
  header("Expires: 0");
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?= $title ?></title>
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
    Yth. Keuangan PT. Sinar Sentosa Primatama<br>
    Mohon dapat segera diproses dan ditransfer ke AHASS yang Claim seperti tertera dalam tabel sebagai berikut :
    <p style='text-align:center;font-weight:bold'>REKAPITULASI PEMBAYARAN CLAIM</p>
    <table class='table table-bordered'>
      <tr>
        <td rowspan=2>No</td>
        <td rowspan=2>AHASS</td>
        <td rowspan=2>No SPPB (D to MD)</td>
        <td rowspan=2>No SPPB (MD to AHM)</td>
        <td colspan=6>PEMBAYARAN KE AHASS</td>
        <td rowspan=2>KETERANGAN</td>
      </tr>
      <tr>
        <td align='center'>PART(HET:1.1)</td>
        <td align='center'>JASA</td>
        <td align='center'>TOTAL</td>
        <td align='center'>PPN</td>
        <td align='center'>PPH</td>
        <td align='center'>Total</td>
      </tr>
      <?php $no = 1;
      foreach ($detail as $dtl) { ?>
        <tr>
          <td><?= $no ?></td>
          <td><?= $dtl['nama_dealer'] ?></td>
          <td><?= $dtl['no_lbpc_ahass_to_md'] ?></td>
          <td><?= $dtl['no_lbpc'] ?></td>
          <td align='right'><?= mata_uang_rp($dtl['tot_part']) ?></td>
          <td align='right'><?= mata_uang_rp($dtl['jasa']) ?></td>
          <td align='right'><?= mata_uang_rp($dtl['tot_part_jasa']) ?></td>
          <td align='right'><?= mata_uang_rp($dtl['ppn']) ?></td>
          <td align='right'><?= mata_uang_rp($dtl['pph']) ?></td>
          <td align='right'><?= mata_uang_rp($dtl['total']) ?></td>
          <td></td>
        </tr>
      <?php $no++;
      } ?>
      <tr>
        <td colspan=4><b>Total</b></td>
        <td align='right'><?= mata_uang_rp($grand['tot_part']) ?></td>
        <td align='right'><?= mata_uang_rp($grand['jasa']) ?></td>
        <td align='right'><?= mata_uang_rp($grand['tot_part_jasa']) ?></td>
        <td align='right'><?= mata_uang_rp($grand['ppn']) ?></td>
        <td align='right'><?= mata_uang_rp($grand['pph']) ?></td>
        <td align='right'><?= mata_uang_rp($grand['total']) ?></td>
        <td></td>
      </tr>
    </table>
    <table style='padding-top:20px' width='20%'>
      <tr>
        <td>Ket :</td>
        <td>
          <table class="table table-bordered">
            <tr>
              <td colspan=2>POTONGAN NPWP</td>
            </tr>
            <tr>
              <td align='center'>ADA</td>
              <td align='center'>TDK</td>
            </tr>
            <tr>
              <td align='center'>2%</td>
              <td align='center'>4%</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <br>
    <table class='table-borderedc' width='88%' align='center'>
      <tr>
        <td width='35%'>Jambi, <?= $grand['tgl_pengajuan'] ?><br>
          Dibuat Oleh,
          <br><br><br><br><br>
          <u>BAYU FAJAR KUSUMA</u><br>
          <i>Claim Processor</i>
        </td>
        <td width='36%'><br>
          Diperiksa Oleh,
          <br><br><br><br><br>
          <u>FADLI NUR PERMADI</u><br>
          <i>PQM & WCL</i>
        </td>
        <td><br>
          Disetujui Oleh
          <br><br><br><br><br>
          <u>NUBIT MAHESA</u><br>
          <i>Deputy Manager</i>
        </td>
      </tr>
    </table>
    <table style='font-size:8pt;text-align:justify;width:44%;padding-top:24px'>
      <tr>
        <td style='vertical-align:top;padding-top:4px'>Catatan :</td>
        <td>
          <table>
            <tr>
              <td style='vertical-align:top'>1.</td>
              <td>Terlampir lampiran pengajuan pembayaran claim</td>
            </tr>
            <tr>
              <td style='vertical-align:top'>2.</td>
              <td>Pembayaran biaya claim ke AHASS setiap bulan paling lambat tanggal 10, setelah pembayaran bukti pembayaran di fotocopy & diserahkan ke TSD</td>
            </tr>
            <tr>
              <td style='vertical-align:top'>3.</td>
              <td>Pengajuan pembayaran claim ke pimpinan paling lambat tanggal 05 setiap bulan</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>

  </html>
<?php } elseif ($set == 'ganti_claim_internal') {
  header("Content-type: application/octet-stream");
  $file_name = remove_space($title, '_') . '.xls';
  header("Content-Disposition: attachment; filename=" . $file_name);
  header("Pragma: no-cache");
  header("Expires: 0");
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?= $title ?></title>
    <style>
      @media print {
        @page {
          sheet-size: 330mm 215mm;
          margin-left: 0.8cm;
          margin-right: 0.8cm;
          margin-bottom: 0.8cm;
          margin-top: 0.8cm;
        }

        .text-center {
          text-align: center;
        }

        .table {
          /* width: 100%;
          max-width: 100%; */
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

  <body style='font-size:9pt'>
    <?php $no = 1;

    foreach ($detail as $dtl_dealer) {
      foreach ($dtl_dealer['result'] as $dtl) {
    ?>
        DAFTAR PENGGANTIAN CLAIM
        <table>
          <tr>
            <td>Nama AHASS</td>
            <td>:</td>
            <td><?= $dtl['nama_dealer'] ?></td>
          </tr>
          <tr>
            <td>No. AHASS</td>
            <td>:</td>
            <td><?= $dtl['kode_dealer_md'] ?></td>
          </tr>
          <tr>
            <td>Kota</td>
            <td>:</td>
            <td><?= dealer($dtl['id_dealer'])->kabupaten ?></td>
          </tr>
          <tr>
            <td>No. LBPC</td>
            <td>:</td>
            <td><?= $dtl['no_lbpc'] ?></td>
          </tr>
        </table>
        <table class='table table-bordered'>
          <tr>
            <td rowspan=2 width='3%'>No</td>
            <td rowspan=2 width='16%'>No. Registrasi</td>
            <td rowspan=2 width='19%'>Nama Sparepart</td>
            <td colspan=3>Tagihan Ke AHM</td>
            <td colspan=4>Total Pencairan dari AHM</td>
            <td rowspan=2>KETERANGAN</td>
          </tr>
          <tr>
            <td width='7%'>Part</td>
            <td width='5%'>Jasa</td>
            <td width='7%'>Total</td>
            <td width='7%'>Total</td>
            <td width='7%'>PPN</td>
            <td width='5%'>PPH</td>
            <td width='7%'>Total</td>
          </tr>
          <?php foreach ($dtl['parts'] as $key => $prt) {
            $total = $prt->nilai_part + $prt->ongkos; ?>
            <tr>
              <?php if ($key == 0) { ?>
                <td rowspan='<?= count($dtl['parts']) ?>'><?= $no ?></td>
                <td rowspan='<?= count($dtl['parts']) ?>'><?= $dtl['no_registrasi'] ?></td>
                <td rowspan='<?= count($dtl['parts']) ?>'><?= $prt->nama_part ?></td>
              <?php } ?>
              <td align='right'><?= mata_uang_rp($prt->nilai_part) ?></td>
              <td align='right'><?= mata_uang_rp($prt->ongkos) ?></td>
              <td align='right'><?= mata_uang_rp($total) ?></td>
              <td align='right'><?= mata_uang_rp($total) ?></td>
              <td align='right'><?= mata_uang_rp($prt->ppn) ?></td>
              <td align='right'><?= mata_uang_rp($prt->pph) ?></td>
              <td align='right'><?= mata_uang_rp($prt->total) ?></td>
              <td></td>
            </tr>
          <?php } ?>
          <tr>
            <td colspan=3>TOTAL</td>
            <td align='right'><?= mata_uang_rp($dtl['total']['nilai_part']) ?></td>
            <td align='right'><?= mata_uang_rp($dtl['total']['ongkos']) ?></td>
            <td align='right'><?= mata_uang_rp($dtl['total']['part_ongkos']) ?></td>
            <td align='right'><?= mata_uang_rp($dtl['total']['part_ongkos']) ?></td>
            <td align='right'><?= mata_uang_rp($dtl['total']['ppn']) ?></td>
            <td align='right'><?= mata_uang_rp($dtl['total']['pph']) ?></td>
            <td align='right'><?= mata_uang_rp($dtl['total']['total']) ?></td>
            <td>Total penggantian ke <?= $dtl['nama_dealer'] ?></td>
          </tr>
        </table>
        <br>
      <?php $no++;
      } ?>
      <table class='table table-bordered' width='83%'>
        <tr>
          <td width='44%'>TOTAL KESELURUHAN</td>
          <td width='7%' align='right'><?= mata_uang_rp($dtl_dealer['grand']['nilai_part']) ?></td>
          <td width='5%' align='right'><?= mata_uang_rp($dtl_dealer['grand']['ongkos']) ?></td>
          <td width='7%' align='right'><?= mata_uang_rp($dtl_dealer['grand']['part_ongkos']) ?></td>
          <td width='7%' align='right'><?= mata_uang_rp($dtl_dealer['grand']['part_ongkos']) ?></td>
          <td width='7%' align='right'><?= mata_uang_rp($dtl_dealer['grand']['ppn']) ?></td>
          <td width='5%' align='right'><?= mata_uang_rp($dtl_dealer['grand']['pph']) ?></td>
          <td width='7%' align='right'><?= mata_uang_rp($dtl_dealer['grand']['total']) ?></td>
        </tr>
      </table>
    <?php } ?>
  </body>

  </html>
<?php }
