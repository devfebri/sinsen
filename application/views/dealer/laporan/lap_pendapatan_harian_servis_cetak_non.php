<?php if ($params['tipe'] == 'download') {
  header("Content-type: application/octet-stream");
  $file_name = remove_space($title, '_') . '.xls';
  header("Content-Disposition: attachment; filename=$file_name.xls");
  header("Pragma: no-cache");
  header("Expires: 0");
}
// send_json($details);
?>
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Cetak</title>
  <style>
    @media print {
      @page {
        sheet-size: 330mm 210mm;
        margin-left: 0.7cm;
        margin-right: 0.7cm;
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
        font-size: 10pt;
      }
    }
  </style>
</head>

<body>
  <table>
    <tr>
      <td><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
    </tr>
  </table>
  <div style="text-align: center;font-size: 13pt"><b><?= $title ?></b></div>
  <div style="text-align: center; font-weight: bold;">Tanggal : <?php echo $params['tanggal'] ?></div>
  <hr>
  <div style="font-weight: bold;">Service</div>
  <table class="table table-bordered" border=1>
    <tr>
      <td colspan=14 rowspan=2>Konsumen</td>
      <td colspan=2 align="center">Pembayaran</td>
      <td colspan=3 align="center">Penarikan Uang Jaminan</td>
      <td colspan=3 align="center">Info Rekening</td>
      <td rowspan=2>Sisa Piutang</td>
    </tr>
    <tr>
      <td>Pembayaran Cash</td>
      <td>Pembayaran Transfer</td>
      <td>No. Tanda Jadi</td>
      <td>Tgl. Titipan</td>
      <td>Nominal</td>
      <td>Bank</td>
      <td>No. Rekening</td>
      <td>Tgl. Transfer</td>
    </tr>
    <tr>
      <td>No.</td>
      <td>No. Kwitansi</td>
      <td>NSC</td>
      <td>NJB</td>
      <td>ID Work Order</td>
      <td>Nama Konsumen</td>
      <td>Waktu</td>
      <td>Mekanik</td>
      <td>KPB</td>
      <td>Jasa</td>
      <td>Oli AHM</td>
      <td>Part</td>
      <td>Diskon</td>
      <td>Total</td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <?php
    $no = 1;
    $tot_jasa_customer = 0;
    $tot_oli = 0;
    $tot_fed_oli = 0;
    $tot_diskon = 0;
    $tot_part = 0;
    $tot_jasa_kpb1 = 0;
    $tot_oli_kpb1 = 0;
    $tot_jasa_kpb2 = 0;
    $tot_jasa_kpb3 = 0;
    $tot_jasa_kpb4 = 0;
    $tot_bayar_cash = 0;
    $tot_bayar_transfer = 0;
    $tot_uang_muka = 0;
    $grand_total = 0;
    $tot_sisa_piutang = 0;
    foreach ($details as $dtl) {
      if($dtl==null)continue;
      $total = ($dtl->tot_jasa_customer + $dtl->tot_oli + $dtl->tot_part + $dtl->tot_fed_oli) - $dtl->diskon;
      $dtl->bayar_cash = $dtl->bayar_cash > $total ? $total : $dtl->bayar_cash;
      $no_mtd = 1;
      $transfer=isset($dtl->bayar_transfer)?$dtl->bayar_transfer:0;
      $bayar_uang_muka=isset($dtl->bayar_uang_muka)?$dtl->bayar_uang_muka:0;
      $sisa_piutang = $total - ($dtl->bayar_cash + $transfer + $bayar_uang_muka);
      $tot_sisa_piutang += $sisa_piutang;
      if (isset($dtl->metode_bayar)) {
        foreach ($dtl->metode_bayar as $dtlmb) {
          if ($dtlmb->metode_bayar == 'Cash') {
            $tot_bayar_cash += (int) $dtlmb->nominal;
          } elseif ($dtlmb->metode_bayar == 'Transfer') {
            $tot_bayar_transfer += (int) $dtlmb->nominal;
          } elseif ($dtlmb->metode_bayar == 'uang_muka') {
            $tot_uang_muka += $dtlmb->nominal;
          }
          if ($no_mtd == 1) {
            $grand_total += $total;
            $tot_jasa_customer += $dtl->tot_jasa_customer;
            $tot_oli += (int) $dtl->tot_oli;
            $tot_fed_oli += (int) $dtl->tot_fed_oli;
            $tot_diskon += $dtl->diskon;
            $tot_part += (int) $dtl->tot_part;
            $tot_jasa_kpb1 += (int) $dtl->tot_jasa_kpb1;
            $tot_oli_kpb1 += (int) $dtl->tot_oli_kpb1;
            $tot_jasa_kpb2 += (int) $dtl->tot_jasa_kpb2;
            $tot_jasa_kpb3 += (int) $dtl->tot_jasa_kpb3;
            $tot_jasa_kpb4 += (int) $dtl->tot_jasa_kpb4;
          }
    ?>
          <tr>
            <td><?= $no_mtd == 1 ? $no : '&nbsp;' ?></td>
            <td><?= $dtlmb->id_receipt ?></td>
            <td><?= $no_mtd == 1 ? $dtl->no_nsc : '&nbsp;' ?></td>
            <td><?= $no_mtd == 1 ? $dtl->no_njb : '&nbsp;' ?></td>
            <td><?= $no_mtd == 1 ? $dtl->id_work_order : '&nbsp;' ?></td>
            <td><?= $no_mtd == 1 ? $dtl->nama_customer : '&nbsp;' ?></td>
            <td><?= $no_mtd == 1 ? (int)$dtl->waktu : '&nbsp;' ?></td>
            <td><?= $no_mtd == 1 ? $dtl->mekanik : '&nbsp;' ?></td>
            <td><?= $no_mtd == 1 ? $dtl->kpb_ke : '&nbsp;' ?></td>
            <td align='right'><?= $no_mtd == 1 ? mata_uang_rp((int) $dtl->tot_jasa_customer) : '&nbsp;' ?></td>
            <td align='right'><?= $no_mtd == 1 ? mata_uang_rp((int) $dtl->tot_oli) : '&nbsp;' ?></td>
            <td align='right'><?= $no_mtd == 1 ? mata_uang_rp((int) $dtl->tot_part) : '&nbsp;' ?></td>
            <td align='right'><?= $no_mtd == 1 ? mata_uang_rp((int) $dtl->diskon) : '&nbsp;' ?></td>
            <td align='right'><?= $no_mtd == 1 ? mata_uang_rp((int) $total) : '&nbsp;' ?></td>
            <td align='right'><?= $dtlmb->metode_bayar == 'Cash' ? mata_uang_rp((int) $dtlmb->nominal) : 0 ?></td>
            <td align='right'><?= $dtlmb->metode_bayar == 'Transfer' ? mata_uang_rp((int) $dtlmb->nominal) : 0 ?></td>
            <td><?= $dtlmb->no_inv_jaminan ?></td>
            <td><?= $dtlmb->tgl_uang_jaminan ?></td>
            <td align='right'><?= $dtlmb->metode_bayar == 'uang_muka' ? mata_uang_rp((int) $dtlmb->nominal) : 0 ?></td>
            <td><?= $dtlmb->bank ?></td>
            <td><?= $dtlmb->no_rekening ?></td>
            <td><?= $dtlmb->metode_bayar == 'Transfer' ? $dtlmb->tanggal : '&nbsp;' ?></td>
            <td align='right'><?= $no_mtd == 1 ? mata_uang_rp($sisa_piutang) : '&nbsp;' ?></td>
          </tr>
        <?php $no_mtd++;
        }
      } else {
        $grand_total       += $total;
        $tot_jasa_customer += $dtl->tot_jasa_customer;
        $tot_oli           += (int) $dtl->tot_oli;
        $tot_fed_oli       += (int) $dtl->tot_fed_oli;
        $tot_diskon        += $dtl->diskon;
        $tot_part          += (int) $dtl->tot_part;
        $tot_jasa_kpb1     += (int) $dtl->tot_jasa_kpb1;
        $tot_oli_kpb1      += (int) $dtl->tot_oli_kpb1;
        $tot_jasa_kpb2     += (int) $dtl->tot_jasa_kpb2;
        $tot_jasa_kpb3     += (int) $dtl->tot_jasa_kpb3;
        $tot_jasa_kpb4     += (int) $dtl->tot_jasa_kpb4;
        ?>
        <tr>
          <td><?= $no ?></td>
          <td></td>
          <td><?= $dtl->no_nsc ?></td>
          <td><?= $dtl->no_njb ?></td>
          <td><?= $dtl->id_work_order ?></td>
          <td><?= $dtl->nama_customer ?></td>
          <td><?= (int)$dtl->waktu ?></td>
          <td><?= $dtl->mekanik ?></td>
          <td><?= $dtl->kpb_ke ?></td>
          <td align='right'><?= mata_uang_rp((int) $dtl->tot_jasa_customer)  ?></td>
          <td align='right'><?= mata_uang_rp((int) $dtl->tot_oli)  ?></td>
          <td align='right'><?= mata_uang_rp((int) $dtl->tot_part)  ?></td>
          <td align='right'><?= mata_uang_rp((int) $dtl->diskon)  ?></td>
          <td align='right'><?= mata_uang_rp((int) $total)  ?></td>
          <td align='right'>0</td>
          <td align='right'>0</td>
          <td></td>
          <td></td>
          <td align='right'></td>
          <td></td>
          <td></td>
          <td></td>
          <td align='right'><?= mata_uang_rp($sisa_piutang) ?></td>
        </tr>
    <?php }
      $no++;
    }
    ?>
    <tr>
      <td colspan=9 align="right">Grand Total</td>
      <td align="right"><?= mata_uang_rp($tot_jasa_customer) ?></td>
      <td align="right"><?= mata_uang_rp($tot_oli) ?></td>
      <td align="right"><?= mata_uang_rp($tot_part) ?></td>
      <td align="right"><?= mata_uang_rp($tot_diskon) ?></td>
      <td align="right"><?= mata_uang_rp($grand_total) ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td colspan=8 align="right">Total Pendapatan Bengkel Jasa & Part</td>
      <td colspan=6 align="right"><?= mata_uang_rp($grand_total) ?></td>
      <td align="right"><?= mata_uang_rp($tot_bayar_cash) ?></td>
      <td align="right"><?= mata_uang_rp($tot_bayar_transfer) ?></td>
      <td></td>
      <td></td>
      <td align="right"><?= mata_uang_rp($tot_uang_muka) ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td align="right"><?= mata_uang_rp($tot_sisa_piutang) ?></td>
    </tr>
  </table>
  <br>
  <div style="font-weight: bold;">Sparepart (Direct Sales).</div>
  <table class="table table-bordered" border=1>
    <tr>
      <td colspan=10></td>
      <td colspan=2 align="center">Pembayaran</td>
      <td colspan=3 align="center">Penarikan Uang Jaminan</td>
      <td colspan=3 align="center">Info Rekening</td>
      <td rowspan=2 align="center">Sisa Piutang</td>
    </tr>
    <tr>
      <td>No.</td>
      <td>No. Kwitansi</td>
      <td>Nama Konsumen</td>
      <td>NSC</td>
      <td>No. Part</td>
      <td>Nama Part</td>
      <td>Harga</td>
      <td>Qty</td>
      <td>Diskon</td>
      <td>Total</td>
      <td>Pembayaran Cash</td>
      <td>Pembayaran Transfer</td>
      <td>No. Tanda Jadi</td>
      <td>Tgl. Titipan</td>
      <td>Nominal</td>
      <td>Bank</td>
      <td>No. Rekening</td>
      <td>Tgl. Transfer</td>
    </tr>
    <?php
    $no = 1;
    $tot_sparepart = 0;
    $temp ='';
    $total_pembayaran_cash = 0;
    $total_pembayaran_transfer = 0;
    $total_pembayaran_uj = 0;
    foreach ($details_sales_part as $dsp) {
      $no_sub = 1;
      if($temp == '' || $temp != $dsp->no_nsc){
        $temp = $dsp->no_nsc;
      if (count($dsp->metode_bayar) > count($dsp->parts_nsc)) {
        $tot_bayar = 0;
        foreach ($dsp->metode_bayar as $km => $vmb) {
          $tot_bayar += $vmb->nominal;
        }
        $sisa_piutang = $subtotal - $tot_bayar;
        if ($sisa_piutang < 0) {
          $sisa_piutang = 0;
        }
        foreach ($dsp->metode_bayar as $km => $vmb) {
          $id_part = '';
          $nama_part = '';
          $harga_beli = 0;
          $qty = 0;
          $diskon_value = 0;
          $subtotal = 0;
          if (isset($dsp->parts_nsc[$km])) {
            $prt            = $dsp->parts_nsc[$km];
            $id_part        = $prt->id_part;
            $nama_part      = $prt->nama_part;
            $harga_beli     = $prt->harga_beli;
            $qty            = $prt->qty;
            $diskon_value   = $prt->diskon_value;
            $subtotal       = $prt->subtotal;
            $tot_sparepart += $subtotal;
          }
          
          if($vmb->metode_bayar == 'Cash'){
            $total_pembayaran_cash+=$vmb->nominal;
          }
          if($vmb->metode_bayar == 'Transfer'){
            $total_pembayaran_transfer+=$vmb->nominal;
          }
          if($vmb->metode_bayar == 'uang_muka'){
            $total_pembayaran_uj+=$vmb->nominal;
          }
    ?>
          <tr>
            <td><?= $no_sub == 1 ? $no : '&nbsp;' ?></td>
            <td><?= $no_sub == 1 ? $dsp->id_receipt : '&nbsp;' ?></td>
            <td><?= $no_sub == 1 ? $dsp->nama_customer : '&nbsp;' ?></td>
            <td><?= $no_sub == 1 ? $dsp->no_nsc : '&nbsp;' ?></td>
            <td><?= $id_part  ?></td>
            <td><?= $nama_part ?></td>
            <td align='right'><?= mata_uang_rp($harga_beli) ?></td>
            <td><?= $qty ?></td>
            <td align='right'><?= mata_uang_rp($diskon_value) ?></td>
            <td align='right'><?= mata_uang_rp($subtotal) ?></td>
            <td align='right'><?= $vmb->metode_bayar == 'Cash' ? mata_uang_rp((int) $vmb->nominal) : 0 ?></td>
            <td align='right'><?= $vmb->metode_bayar == 'Transfer' ? mata_uang_rp((int) $vmb->nominal) : 0 ?></td>
            <td><?= $vmb->no_inv_jaminan ?></td>
            <td><?= $vmb->tgl_uang_jaminan ?></td>
            <td align='right'><?= $vmb->metode_bayar == 'uang_muka' ? mata_uang_rp((int) $vmb->nominal) : 0 ?></td>
            <td><?= $vmb->bank ?></td>
            <td><?= $vmb->no_rekening ?></td>
            <td><?= $vmb->metode_bayar == 'Transfer' ? $vmb->tanggal : '&nbsp;' ?></td>
            <td align='right'><?= $no_mtd == 1 ? mata_uang_rp($sisa_piutang) : '&nbsp;' ?></td>
          </tr>
        <?php $no_sub++;
        }
      } else {
        $tot_bayar = 0;
        foreach ($dsp->metode_bayar as $km => $vmb) {
          $tot_bayar += $vmb->nominal;
          if($vmb->metode_bayar == 'Cash'){
            $total_pembayaran_cash+=$vmb->nominal;
          }
          if($vmb->metode_bayar == 'Transfer'){
            $total_pembayaran_transfer+=$vmb->nominal;
          }
          if($vmb->metode_bayar == 'uang_muka'){
            $total_pembayaran_uj+=$vmb->nominal;
          }
        }

        foreach ($dsp->parts_nsc as $km => $vmb) {
          $bayar_cash = 0;
          $bayar_transfer = 0;
          $no_inv_jaminan = '';
          $tgl_uang_jaminan = '';
          $uang_muka = 0;
          $bank = '';
          $no_rekening = '';
          $tgl_transfer = '';
          $tot_sparepart += $vmb->subtotal;
          if (isset($dsp->metode_bayar[$km])) {
            $mb = $dsp->metode_bayar[$km];
            $bayar_cash = $mb->metode_bayar == 'Cash' ? $mb->nominal : 0;
            $bayar_transfer = $mb->metode_bayar == 'Transfer' ? $mb->nominal : 0;
            $no_inv_jaminan = $mb->no_inv_jaminan;
            $tgl_uang_jaminan = $mb->tgl_uang_jaminan;
            $uang_muka = $mb->metode_bayar == 'uang_muka' ? $mb->nominal : 0;
            $bank = $mb->bank;
            $no_rekening = $mb->no_rekening;
            $tgl_transfer = $mb->metode_bayar == 'Transfer' ? $mb->tanggal : '';
          }
          if ($no_sub == 1) {
            // $sisa_piutang = $vmb->subtotal - $tot_bayar;
            $sisa_piutang = $dsp->tot_part - $tot_bayar;
            if ($sisa_piutang < 0) {
              $sisa_piutang = 0;
            }
          }
        ?>
          <tr>
            <td><?= $no_sub == 1 ? $no : '&nbsp;' ?></td>
            <td><?= $no_sub == 1 ? $dsp->id_receipt : '&nbsp;' ?></td>
            <td><?= $no_sub == 1 ? $dsp->nama_customer : '&nbsp;' ?></td>
            <td><?= $no_sub == 1 ? $dsp->no_nsc : '&nbsp;' ?></td>
            <td><?= $vmb->id_part ?></td>
            <td><?= $vmb->nama_part ?></td>
            <td align=right><?= mata_uang_rp($vmb->harga_beli) ?></td>
            <td><?= $vmb->qty ?></td>
            <td><?= $vmb->diskon_value ?></td>
            <td align='right'><?= mata_uang_rp($vmb->subtotal) ?></td>
            <td align='right'><?= mata_uang_rp($bayar_cash); ?></td>
            <td align='right'><?= mata_uang_rp($bayar_transfer); ?></td>
            <td align='right'><?= $no_inv_jaminan; ?></td>
            <td align='right'><?= $tgl_uang_jaminan; ?></td>
            <td align='right'><?= $uang_muka; ?></td>
            <td align='right'><?= $bank; ?></td>
            <td align='right'><?= $no_rekening; ?></td>
            <td align='right'><?= $tgl_transfer; ?></td>
            <td align='right'><?= $no_sub == 1 ? mata_uang_rp($sisa_piutang) : '&nbsp;' ?></td>
          </tr>
    <?php $no_sub++;
        }
      }
    }else{
      $temp = $dsp->no_nsc;
      
      foreach ($dsp->metode_bayar as $km => $vmb) {
        if($vmb->metode_bayar == 'Cash'){
          $total_pembayaran_cash+=$vmb->nominal;
        }
        if($vmb->metode_bayar == 'Transfer'){
          $total_pembayaran_transfer+=$vmb->nominal;
        }
        if($vmb->metode_bayar == 'uang_muka'){
          $total_pembayaran_uj+=$vmb->nominal;
        }
      ?>
        <tr>
          <td></td>
          <td><?= $no_sub == 1 ? $dsp->id_receipt : '&nbsp;' ?></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td align='right'><?= $vmb->metode_bayar == 'Cash' ? mata_uang_rp((int) $vmb->nominal) : 0 ?></td>
          <td align='right'><?= $vmb->metode_bayar == 'Transfer' ? mata_uang_rp((int) $vmb->nominal) : 0 ?></td>
          <td><?= $vmb->no_inv_jaminan ?></td>
          <td><?= $vmb->tgl_uang_jaminan ?></td>
          <td align='right'><?= $vmb->metode_bayar == 'uang_muka' ? mata_uang_rp((int) $vmb->nominal) : 0 ?></td>
          <td><?= $vmb->bank ?></td>
          <td><?= $vmb->no_rekening ?></td>
          <td><?= $vmb->metode_bayar == 'Transfer' ? $vmb->tanggal : '&nbsp;' ?></td>
          <td align='right'><?= $no_mtd == 1 ? mata_uang_rp($sisa_piutang) : '&nbsp;' ?></td>
        </tr>
        <?php
      }
      $no--;
    }
      $no++;
    } ?>
    <tr>
      <td align='right' colspan=7>Total</td>
      <td></td>
      <td></td>
      <td align='right'><?= mata_uang_rp($tot_sparepart) ?></td>
      <td align='right'><?= mata_uang_rp($total_pembayaran_cash) ?></td>
      <td align='right'><?= mata_uang_rp($total_pembayaran_transfer) ?></td>
      <td></td>
      <td></td>
      <td align='right'><?= mata_uang_rp($total_pembayaran_uj) ?></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </table>
  <br>
  <div style="font-weight: bold;">AHM OIL (direct sales)</div>
  <table class="table table-bordered" border=1>
    <tr>
      <td colspan=10></td>
      <td colspan=2 align="center">Pembayaran</td>
      <td colspan=3 align="center">Penarikan Uang Jaminan</td>
      <td colspan=3 align="center">Info Rekening</td>
      <td rowspan=2>Sisa Piutang</td>
    </tr>
    <tr>
      <td>No.</td>
      <td>No. Kwitansi</td>
      <td>Nama Konsumen</td>
      <td>NSC</td>
      <td>No. Part</td>
      <td>Nama Part</td>
      <td>Harga</td>
      <td>Qty</td>
      <td>Diskon</td>
      <td>Total</td>
      <td>Pembayaran Cash</td>
      <td>Pembayaran Transfer</td>
      <td>No. Tanda Jadi</td>
      <td>Tgl. Titipan</td>
      <td>Nominal</td>
      <td>Bank</td>
      <td>No. Rekening</td>
      <td>Tgl. Transfer</td>
    </tr>
    <?php
    $no = 1;
    $tot_sparepart = 0;
    foreach ($details_sales_oli as $dsp) {
      $no_sub = 1;
      if (count($dsp->metode_bayar) > count($dsp->parts_nsc)) {
        foreach ($dsp->metode_bayar as $km => $vmb) {
          $id_part = '';
          $nama_part = '';
          $harga_beli = 0;
          $qty = 0;
          $diskon_value = 0;
          $subtotal = 0;
          if (isset($dsp->parts_nsc[$km])) {
            $prt          = $dsp->parts_nsc;
            $id_part      = $prt->id_part;
            $nama_part    = $prt->nama_part;
            $harga_beli   = $prt->harga_beli;
            $qty          = $prt->qty;
            $diskon_value = $prt->diskon_value;
            $subtotal     = $prt->subtotal;
            $tot_sparepart += $subtotal;
          }
    ?>
          <tr>
            <td><?= $no_sub == 1 ? $no : '&nbsp;' ?></td>
            <td><?= $no_sub == 1 ? $dsp->id_receipt : '&nbsp;' ?></td>
            <td><?= $no_sub == 1 ? $dsp->nama_customer : '&nbsp;' ?></td>
            <td><?= $id_part  ?></td>
            <td><?= $nama_part ?></td>
            <td align='right'><?= mata_uang_rp($harga_beli) ?></td>
            <td><?= $qty ?></td>
            <td align='right'><?= mata_uang_rp($diskon_value) ?></td>
            <td align='right'><?= mata_uang_rp($subtotal) ?></td>
            <td align='right'><?= $vmb->metode_bayar == 'Cash' ? mata_uang_rp((int) $vmb->nominal) : 0 ?></td>
            <td align='right'><?= $vmb->metode_bayar == 'Transfer' ? mata_uang_rp((int) $vmb->nominal) : 0 ?></td>
            <td><?= $vmb->no_inv_jaminan ?></td>
            <td><?= $vmb->tgl_uang_jaminan ?></td>
            <td align='right'><?= $vmb->metode_bayar == 'uang_muka' ? mata_uang_rp((int) $vmb->nominal) : 0 ?></td>
            <td><?= $vmb->bank ?></td>
            <td><?= $vmb->no_rekening ?></td>
            <td><?= $vmb->metode_bayar == 'Transfer' ? $vmb->tanggal : '&nbsp;' ?></td>
            <td align='right'><?= $no_mtd == 1 ? 0 : '&nbsp;' ?></td>
          </tr>
        <?php $no_sub++;
        }
      } else {
        foreach ($dsp->parts_nsc as $km => $vmb) {
          $bayar_cash = 0;
          $bayar_transfer = 0;
          $no_inv_jaminan = '';
          $tgl_uang_jaminan = '';
          $uang_muka = 0;
          $bank = '';
          $no_rekening = '';
          $tgl_transfer = '';
          $tot_sparepart += $vmb->subtotal;
          if (isset($dsp->metode_bayar[$km])) {
            $mb = $dsp->metode_bayar[$km];
            $bayar_cash = $mb->metode_bayar == 'Cash' ? $mb->nominal : 0;
            $bayar_transfer = $mb->metode_bayar == 'Transfer' ? $mb->nominal : 0;
            $no_inv_jaminan = $mb->no_inv_jaminan;
            $tgl_uang_jaminan = $mb->tgl_uang_jaminan;
            $uang_muka = $mb->metode_bayar == 'uang_muka' ? $mb->nominal : 0;
            $bank = $mb->bank;
            $no_rekening = $mb->no_rekening;
            $tgl_transfer = $mb->tanggal;
          }
        ?>
          <tr>
            <td><?= $no_sub == 1 ? $no : '&nbsp;' ?></td>
            <td><?= $no_sub == 1 ? $dsp->id_receipt : '&nbsp;' ?></td>
            <td><?= $no_sub == 1 ? $dsp->nama_customer : '&nbsp;' ?></td>
            <td><?= $no_sub == 1 ? $dsp->no_nsc : '&nbsp;' ?></td>
            <td><?= $vmb->id_part ?></td>
            <td><?= $vmb->nama_part ?></td>
            <td align=right><?= mata_uang_rp($vmb->harga_beli) ?></td>
            <td><?= $vmb->qty ?></td>
            <td><?= $vmb->diskon_value ?></td>
            <td align='right'><?= mata_uang_rp($vmb->subtotal) ?></td>
            <td align='right'><?= mata_uang_rp($bayar_cash); ?></td>
            <td align='right'><?= mata_uang_rp($bayar_transfer); ?></td>
            <td align='right'><?= $no_inv_jaminan; ?></td>
            <td align='right'><?= $tgl_uang_jaminan; ?></td>
            <td align='right'><?= $uang_muka; ?></td>
            <td align='right'><?= $bank; ?></td>
            <td align='right'><?= $no_rekening; ?></td>
            <td align='right'><?= $tgl_transfer; ?></td>
            <td><?= $no_sub == 1 ? mata_uang_rp(0) : '&nbsp;' ?></td>
          </tr>
    <?php $no_sub++;
        }
      }
      $no++;
    } ?>
    <tr>
      <td align='right' colspan=7>Total</td>
      <td></td>
      <td></td>
      <td align='right'><?= mata_uang_rp($tot_sparepart) ?></td>
    </tr>
  </table>
  <br>
  <div style="font-weight: bold;">Uang Jaminan</div>
  <table class="table table-bordered" border=1>
    <tr>
      <td colspan=10></td>
      <td colspan=2 align="center">Pembayaran</td>
      <td colspan=3 align="center">Info Rekening</td>
      <td rowspan=2>Sisa Piutang</td>
    </tr>
    <tr>
      <td>No.</td>
      <td>No. Kwitansi</td>
      <td>Nama Konsumen</td>
      <td>No. Req. Document</td>
      <td>No. Part</td>
      <td>Nama Part</td>
      <td>Harga</td>
      <td>Qty</td>
      <td>Diskon</td>
      <td>Total</td>
      <td>Pembayaran Cash</td>
      <td>Pembayaran Transfer</td>
      <td>Bank</td>
      <td>No. Rekening</td>
      <td>Tgl. Transfer</td>
    </tr>
    <?php
    $no = 1;
    $total = 0;
    $totalcash = 0;
    $totaltf = 0;
    foreach ($details_uj as $dtj) {
      $count_parts = count($dtj->parts);
      $count_metode_bayar = count($dtj->metode_bayar);
      $loop = $count_parts > $count_metode_bayar ? $count_parts : $count_metode_bayar;
      for ($i = 0; $i < $loop; $i++) {
        $id_part = '';
        $nama_part = '';
        $harga_saat_dibeli = 0;
        $subtotal_part = 0;
        if (isset($dtj->parts[$i])) {
          $prt = $dtj->parts[$i];
          $id_part = $prt->id_part;
          $nama_part = $prt->nama_part;
          $kuantitas = $prt->kuantitas;
          $harga_saat_dibeli = $prt->harga_saat_dibeli;
          $subtotal_part = $kuantitas * $harga_saat_dibeli;
          $total += $subtotal_part;
        }

        $bayar_cash = 0;
        $bayar_transfer = 0;
        $bank = '';
        $no_rekening = '';
        $tanggal_transaksi = '';
        if (isset($dtj->metode_bayar[$i])) {
          $byr = $dtj->metode_bayar[$i];
          $bayar_cash = $byr->metode_bayar == 'Cash' ? $byr->nominal : 0;
          $bayar_transfer = $byr->metode_bayar == 'Transfer' ? $byr->nominal : 0;
          $bank = $byr->bank;
          $no_rekening = $byr->no_rekening;
          $tanggal_transaksi = $byr->tanggal_transaksi;
          $totalcash += $bayar_cash;
          $totaltf += $bayar_transfer;
        }
    ?>
        <tr>
          <td><?= $i == 0 ? $no : '' ?></td>
          <td><?= $i == 0 ? $dtj->no_inv_uang_jaminan : '' ?></td>
          <td><?= $i == 0 ? $dtj->nama_customer : '' ?></td>
          <td><?= $i == 0 ? $dtj->id_booking : '' ?></td>
          <td><?= $id_part ?></td>
          <td><?= $nama_part ?></td>
          <td align='right'><?= mata_uang_rp($harga_saat_dibeli) ?></td>
          <td><?= $kuantitas ?></td>
          <td>0</td>
          <td align='right'><?= mata_uang_rp($subtotal_part) ?></td>
          <td align='right'><?= mata_uang_rp($bayar_cash) ?></td>
          <td align='right'><?= mata_uang_rp($bayar_transfer) ?></td>
          <td><?= $bank ?></td>
          <td><?= $no_rekening ?></td>
          <td><?= $tanggal_transaksi ?></td>
          <td align='right'><?= $i == 0 ? mata_uang_rp($dtj->sisa_bayar) : '' ?></td>
        </tr>
    <?php }
      $no++;
    } ?>
    <tr>
      <td align='right' colspan=7>Total</td>
      <td></td>
      <td></td>
      <td align='right'><?= mata_uang_rp($total) ?></td>
      <td align='right'><?= mata_uang_rp($totalcash) ?></td>
      <td align='right'><?= mata_uang_rp($totaltf) ?></td>
    </tr>
  </table>
  <br>
  <div style='font-size:9pt'>Dicetak : <?= kry_login($this->session->userdata('id_user'))->nama_lengkap . ' ' . waktu() ?></div>
</body>

</html>