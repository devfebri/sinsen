<!DOCTYPE html>
<html>
<?php

function penyebut($nilai)
{
  $nilai = abs($nilai);
  $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
  $temp = "";
  if ($nilai < 12) {
    $temp = " " . $huruf[$nilai];
  } else if ($nilai < 20) {
    $temp = penyebut($nilai - 10) . " belas";
  } else if ($nilai < 100) {
    $temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
  } else if ($nilai < 200) {
    $temp = " seratus" . penyebut($nilai - 100);
  } else if ($nilai < 1000) {
    $temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
  } else if ($nilai < 2000) {
    $temp = " seribu" . penyebut($nilai - 1000);
  } else if ($nilai < 1000000) {
    $temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
  } else if ($nilai < 1000000000) {
    $temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
  } else if ($nilai < 1000000000000) {
    $temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
  } else if ($nilai < 1000000000000000) {
    $temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
  }
  return $temp;
}

function func_terbilang($nilai)
{
  if ($nilai < 0) {
    $hasil = "minus " . trim(penyebut($nilai));
  } else {
    $hasil = trim(penyebut($nilai));
  }
  return $hasil;
}

?>

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

      tr.header td {
        border-bottom: 1px solid black;
        border-top: 1px solid black;
        font-weight: bold;
      }

      tr.footer td {
        border-top: 1px solid black;
      }

      .border-bottom {
        border-bottom: 1px solid black;
      }

      body {
        font-family: "Arial";
        font-size: 11pt;
      }
    }
  </style>
</head>

<?php
if ($set == 'cetak_kwitansi') { ?>

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
        <td width="13%">No Receipt</td>
        <td width="36%">
          :
          <?= $row->id_receipt ?>
        </td>
        <td width="20%">No Polisi</td>
        <td>
          :
          <?= $row->no_polisi ?>
        </td>
      </tr>
      <tr>
        <?php if ($row->id_work_order != null) { ?>
          <td>No WO</td>
          <td>
            :
            <?= $row->id_work_order ?>
          </td>
        <?php } ?>
        <?php if ($row->nomor_so != null) { ?>
          <td>Nomor SO</td>
          <td>
            :
            <?= $row->nomor_so ?>
          </td>
        <?php } ?>
        <td>Nama Customer</td>
        <td>
          :
          <?php if($row->tipe_coming == 'bawa' || $row->tipe_coming == 'bawa,pakai' || $row->tipe_coming == 'pakai'){?>
                     <?= strtoupper($row->nama_customer) ?> QQ <?=strtoupper( $row->nama_pembawa) ?>
                <?php }else{?>
                  <?= strtoupper($row->nama_customer) ?>
                <?php }?>
        </td>
      </tr>
      <tr>
        <td>Tgl. WO</td>
        <td>
          :
          <?= $row->tgl_servis ?>
        </td>
        <td>Alamat</td>
        <td>
          :
          <?= $row->alamat ?>
        </td>
      </tr>
    </table>
    <table class="table table-borderedx" style="margin-bottom:10px;magin-top:10px">
      <tr class='header' style='padding-bottom:20px'>
        <td>No. Transaksi</td>
        <td>Tgl. Transaksi</td>
        <td align='right' colspan=2>Nilai Transaksi</td>
      </tr>
      <?php $no = 1;
      foreach ($detail as $val) { ?>
        <tr>
          <td><?= $val->id_referensi ?></td>
          <td><?= date_dmy($val->tgl_transaksi) ?></td>
          <td align="right">Rp. </td>
          <td align="right" width='16%'><?= mata_uang_rp($val->nilai) ?></td>
        </tr>
      <?php $no++;
      } ?>
      <tr class='footer'>
        <td colspan="3"><b>Total</b></td>
        <td align="right" class='border-bottom'><b>Rp. <?= mata_uang_rp($tot_trans) ?></b></td>
      </tr>
    </table>
    <br>
    <table>
      <tr>
        <td>Uang Sejumlah</td>
        <td>: Rp. <?= mata_uang_rp($tot_bayar) ?></td>
      </tr>
      <tr>
        <td>Terbilang</td>
        <td>: <?= ucwords(func_terbilang($tot_bayar)) ?> Rupiah</td>
      </tr>
      <tr>
        <td>Keterangan</td>
        <?php
        $sisa = $sisa > 0 ? $sisa : 0;
        if ($sisa > 0) {
          $keterangan = "Sebagian";
        } else {
          $keterangan = "Pelunasan";
        } ?>
        <td>: <?= $keterangan . ' | Sisa : Rp.' . mata_uang_rp($sisa) ?></td>
      </tr>
    </table>
    <p>Cara Pembayaran :</p>
    <table class='table'>
      <tr>
        <td class='border-bottom' width='20%'>Keterangan</td>
        <td class='border-bottom' width='14%'>Nominal</td>
        <td class='border-bottom'>Tanggal</td>
        <td class='border-bottom'>No. BG / Cek</td>
        <td class='border-bottom'>Nama Bank</td>
      </tr>
      <?php foreach ($metode as $mtd) { ?>
        <tr>
          <td><?= $mtd->metode_bayar ?></td>
          <td><?= mata_uang_rp($mtd->nominal) ?></td>
          <td><?= $mtd->tanggal ?></td>
          <td><?= $mtd->no_rekening ?></td>
          <td><?= $mtd->bank ?></td>
        </tr>
      <?php } ?>
    </table>
    <table style="width:100%;margin-top:20px">
      <tr>
        <td width="77%"></td>
        <td>
          Jambi, <?= date('d/m/Y') ?>
          <br>
          <br>
          <br>
          <br>
          <br>
          <?php $kry = kry_login($this->session->userdata('id_user')); ?>
          <?= $kry->nama_lengkap ?> <br>
          <?= $kry->jabatan ?>
        </td>

      </tr>
    </table>
  </body>

</html>
<?php } ?>