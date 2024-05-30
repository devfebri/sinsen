<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?= $title ?></title>
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
    <!-- <table class="table table-borderedx">
      <tr>
        <td width="100%" align="center" colspan="5">
          <b>Invoice Uang jaminan</b><br />&nbsp;
        </td>
      </tr>
      <tr>
        <td width="17%">No. Invoice</td>
        <td width="27%"> : <?= $row->no_inv_uang_jaminan ?> </td>
        <td>Tgl Invoice</td>
        <td> : <?= $row->tgl_invoice ?></td>
      </tr>
      <tr>
        <td>Kode Dealer</td>
        <td> : <?= $row->kode_dealer_md ?></td>
        <td>Nama Dealer</td>
        <td> : <?= $row->nama_dealer ?></td>
      </tr>
      <tr>
        <td>ID Booking</td>
        <td> : <?= $row->id_booking ?></td>
        <td>Tgl Request Document</td>
        <td> : <?= $row->tgl_request ?></td>
      </tr>
      <tr>
        <td>ID Customer</td>
        <td> : <?= $row->id_customer ?></td>
        <td>Nama Customer</td>
        <td> : <?= $row->nama_customer ?></td>
      </tr>
      <tr>
        <td>No Claim C2</td>
        <td width="35%"> : <?= isset($row->no_claim_c2) ? $row->no_claim_c2 : '-'  ?></td>
        <td>No Hp Customer</td>
        <td> : <?= $row->no_hp ?></td>
      </tr>
      <?php if (isset($row->id_work_order)) { ?>
        <tr>
          <td>ID Work Order</td>
          <td> : <?= $row->id_work_order ?></td>
        </tr>
      <?php } ?>
      <tr>
    </table> -->

    <table class="table table-borderedx">
      <tr>
        <td width="100%" align="center" colspan="5">
          <b>Invoice Uang Jaminan</b><br />&nbsp;
        </td>
      </tr>
      <tr>
        <!-- <td width="17%">No. Invoice</td>
        <td width="27%"> : <?= $row->no_inv_uang_jaminan ?> </td>
        <td>Tgl Invoice</td>
        <td> : <?= $row->tgl_invoice ?></td> -->
        <td width="17%">No. Invoice</td>
        <td width="50%"> : <?= $row->no_inv_uang_jaminan ?> </td>
        <td width="17%">ID Booking</td>
        <td width="27%"> : <?= $row->id_booking ?></td>

      </tr>
      <!-- <tr>
        <td>Kode Dealer</td>
        <td> : <?= $row->kode_dealer_md ?></td>
        <td>Nama Dealer</td>
        <td> : <?= $row->nama_dealer ?></td>
      </tr> -->
      <tr>
        <!-- <td>ID Booking</td>
        <td> : <?= $row->id_booking ?></td>
        <td>Tgl Request Document</td>
        <td> : <?= $row->tgl_request ?></td> -->
        <td>Tgl Invoice</td>
        <td> : <?= date("d/m/Y", strtotime($row->tgl_invoice)) ?></td>
        <td>Tgl Booking</td>
        <td> : <?= $row->tgl_request ?></td>
      </tr>
      <tr>
        <!-- <td>ID Customer</td>
        <td> : <?= $row->id_customer ?></td>
        <td>Nama Customer</td>
        <td> : <?= $row->nama_customer ?></td> -->
        <td>Nama Customer</td>
        <td> : <?= $row->nama_customer ?></td>
        <td>No Claim C2</td>
        <td> : <?= $row->no_claim_c2  ?></td>
      </tr>
      <tr>
        <!-- <td>No Claim C2</td>
        <td width="35%"> : <?= isset($row->no_claim_c2) ? $row->no_claim_c2 : '-'  ?></td>
        <td>No Hp Customer</td>
        <td> : <?= $row->no_hp ?></td> -->

        <td>No Hp</td>
        <td> : <?= $row->no_hp ?></td>
      </tr>
      <?php if (isset($row->id_work_order)) { ?>
        <tr>
          <td>ID Work Order</td>
          <td> : <?= $row->id_work_order ?></td>
        </tr>
      <?php } ?>
      <tr>
    </table>


    <!-- <div style="font-weight:bold;text-align:center">Detail Pekerjaan</div> -->
    <table class="table" style="margin-top:20px">
      <tr>
        <td style="font-weight:bold;text-align:center">Detail Part</td>
      </tr>
    </table>
    <table class="table table-bordered">
      <tr>
        <td>No.</td>
        <td>Kode Part</td>
        <td>Deskripsi Part</td>
        <td align="center">Qty Part</td>
        <td align="center">Harga Part</td>
        <td align="center">Uang Muka</td>
        <td align="center">Subtotal</td>
      </tr>
      <?php $no = 1;
      foreach ($detail['detail'] as $val) { ?>
        <tr>
          <td><?= $no ?></td>
          <td><?= $val->id_part ?></td>
          <td><?= $val->nama_part ?></td>
          <td align="center"><?= $val->kuantitas ?></td>
          <td align="right">Rp. <?= mata_uang_rp($val->harga_saat_dibeli) ?></td>
          <td align="right">Rp. <?= mata_uang_rp($val->uang_muka) ?></td>
          <td align="right">Rp. <?= mata_uang_rp($val->subtotal) ?></td>
        </tr>
      <?php $no++;
      } ?>
      <!-- <tr>
        <td colspan="6" align="right"><b>Total Tanpa PPN</b></td>
        <td align="right"><b>Rp. <?= mata_uang_rp($detail['total_no_ppn']) ?></b></td>
      </tr> -->
      <!-- <tr>
        <td colspan="6" align="right"><b>PPN</b></td>
        <td align="right"><b>Rp. <?= mata_uang_rp($detail['ppn']) ?></b></td>
      </tr> -->
      <tr>
        <td colspan="6" align="right"><b>Grand Total</b></td>
        <td align="right"><b>Rp. <?= mata_uang_rp($detail['grand']) ?></b></td>
      </tr>
      <tr>
        <td colspan="6" align="right"><b>Uang Muka</b></td>
        <td align="right"><b>Rp. <?= mata_uang_rp($row->total_bayar) ?></b></td>
      </tr>
      <tr>
        <td colspan="6" align="right"><b>Sisa Pembayaran</b></td>
        <td align="right"><b>Rp. <?= mata_uang_rp($detail['sisa']) ?></b></td>
      </tr>
    </table>

    <table class="table" style="margin-top:20px;">
      <tr>
        <td style="text-align:right" colspan="2"> Jambi, <?= tgl_indo($row->tgl_invoice) ?>
      <br><br><br><br><br> <br>
      <?php //echo $nama_user->nama_lengkap ?>
        </td>
      </tr>
      <tr>        <td style="text-align:right;padding: right 40px;" colspan="2"> (Kasir)
        </td></tr>
    </table>
  </body>

</html>

<?php } ?>