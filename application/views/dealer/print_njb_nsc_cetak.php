<!DOCTYPE html>
<html>
<?php $row->tampil_ppn = 0; 
$persentase_ppn = substr(getPPN(false, $row->tgl_wo),0,2);
?>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
        /* padding-left: 6px;
          padding-right: 6px; */
      }

      tr.header td {
        border-bottom: 1.8px solid black;
        border-top: 1.8px solid black;
        font-weight: bold;
      }

      tr.footer td {
        border-top: 1.8px solid black;
      }

      body {
        font-family: Arial;
        font-size: 10pt;
      }
    }
  </style>
</head>

<?php
if ($set == 'cetak_njb') { ?>

  <body>
    <table>
      <tr>
        <td><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
      </tr>
    </table>
    <div style="text-align: center;font-size: 13pt;padding-bottom:20px"><b>Nota Jasa Bengkel (NJB)</b></div>
    <table class="table" style="margin-bottom:10px">
      <tr>
        <td>No. WO</td>
        <td>: <?= $row->id_work_order ?></td>
        <td>ID Customer</td>
        <td>: <?= $row->id_customer ?></td>
      </tr>
      <tr>
        <td>Tgl. Servis</td>
        <td>: <?= $row->tgl_servis ?></td>
        <td>Nama Customer</td>
        <td>: <?= $row->nama_customer ?></td>
      </tr>
      <tr>
        <td>Waktu NJB</td>
        <td>: <?= $row->waktu_njb ?></td>
        <td>No. HP Customer</td>
        <td>: <?= $row->no_hp ?></td>
      </tr>
      <tr>
        <td>ID Mekanik</td>
        <td>: <?= $row->id_karyawan_dealer ?> <?= $row->honda_id ?></td>
        <td>Alamat Customer</td>
        <td>: <?= $row->alamat ?></td>
      </tr>
      <tr>
        <td>Nama Mekanik</td>
        <td>: <?= $row->nama_lengkap ?></td>
        <td>Tipe Unit</td>
        <td>: <?= $row->tipe_ahm ?></td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="65%">No. NJB : <?= $row->no_njb ?></td>
        <td style="font-weight:bold;text-align:center;font-size:12pt">Detail Pekerjaan</td>
      </tr>
    </table>
    <table class="table">
      <tr class='header'>
        <td>No.</td>
        <td>Pekerjaan</td>
        <td align="right">Nilai Promo</td>
        <td align="right">Biaya</td>
        <td align="right">Subtotal</td>
      </tr>
      <?php $no = 1;
      foreach ($detail['details'] as $val) { ?>
        <tr>
          <td><?= $no ?></td>
          <td><?= $val->pekerjaan ?></td>
          <td align="right"><?= set_diskon($val->diskon, $val->tipe_diskon) ?></td>
          <td align="right">Rp. <?= mata_uang_rp($val->harga) ?></td>
          <td align="right">Rp. <?= mata_uang_rp((int) $val->harga_net) ?></td>
        </tr>
      <?php $no++;
      } ?>
      <?php if ($row->pkp_njb == 1 && $row->tampil_ppn == 1) { ?>
        <tr class="footer">
          <td colspan="4" align='right'><b>Total Tanpa PPN</b></td>
          <td align="right"><b>Rp. <?= mata_uang_rp($detail['total']['tot_no_ppn']) ?></b></td>
        </tr>
        <tr>
          <td colspan="4" align='right'><b>Total PPN</b></td>
          <td align="right"><b>Rp. <?= mata_uang_rp($detail['total']['tot_ppn']) ?></b></td>
        </tr>
      <?php } ?>
      <tr class="footer">
        <td colspan="4" align='right'><b>Grand Total</b></td>
        <td align="right"><b>Rp. <?= mata_uang_rp($detail['total']['grand_tot']) ?></b></td>
      </tr>
    </table>
    <p style='font-size:7pt'><b>Catatan :</b> <br> * Harga sudah termasuk PPN <?php echo $persentase_ppn ?> %</p>
  </body>

</html>

<?php } ?>

<?php
if ($set == 'cetak_nsc') { ?>

  <body>
    <div style="text-align: center;font-size: 13pt;padding-bottom:20px"><b>Nota Suku Cadang (NSC)</b></div>

    <table class="table" style="margin-bottom:10px">
      <tr>
        <?php if ($row->id_work_order != null) { ?>
          <td width="25%">No. WO</td>
          <td>: <?= $row->id_work_order ?></td>
        <?php } ?>
        <?php if ($row->nomor_so != null) { ?>
          <td width="25%">No. Sales Order</td>
          <td>: <?= $row->nomor_so ?></td>
        <?php } ?>
        <td>ID Customer</td>
        <td>: <?= $row->id_customer ?></td>
      </tr>
      <tr>
        <td>Tgl. Servis</td>
        <td>: <?= $wo->tgl_servis ?></td>
        <td>Nama Customer</td>
        <td>: <?= $row->nama_customer ?></td>
      </tr>
      <tr>
        <td>Waktu NSC</td>
        <td>: <?= $row->waktu_nsc ?></td>
        <td>No. HP Customer</td>
        <td>: <?= $row->no_hp ?></td>
      </tr>
      <tr>
        <?php if ($row->id_work_order != NULL) { ?>
          <td>ID Mekanik</td>
          <td>: <?= $wo->id_mekanik ?>(<?= $wo->honda_id_mekanik ?>)</td>
        <?php } ?>
        <td>Alamat Customer</td>
        <td>: <?= $row->alamat ?></td>
        <td>No Mesin</td>
        <td>: <?= $row->no_mesin ?></td>
      </tr>
      <tr>
        <?php if ($row->id_work_order != NULL) { ?>
          <td>Nama Mekanik</td>
          <td>: <?= $wo->mekanik ?></td>
        <?php } ?>
        <td>Tipe Unit</td>
        <td>: <?= $row->tipe_ahm ?></td>
        <td>No Rangka</td>
        <td>: <?= $row->no_rangka ?></td>
      </tr>
    </table>
    <table class="table" style="margin-top:20px">
      <tr>
        <td width="40%">No. NSC : <?= $row->no_nsc ?></td>
        <td style="font-weight:bold;text-align:center;font-size:12pt" align="left">Detail Parts</td>
      </tr>
    </table>
    <table class="table">
      <tr class='header'>
        <td width='5%'>No.</td>
        <td width='18%'>ID Part</td>
        <td width='30%'>Deskripsi</td>
        <td width='5%'>Type Acc</td>
        <td align='right'>Qty</td>
        <td align="right">Disc. Value</td>
        <?php $label_harga = 'HET';
        if ($row->pkp == 1 && $row->tampil_ppn == 1) {
          $label_harga = "DPP";
        } ?>
        <td align="right"><?= $label_harga ?></td>
        <td align="right" >Subtotal</td>
      </tr>
      <?php $no = 1;
      $grand_tot_nsc=0;
      foreach ($detail['details'] as $val) {
        $grand_tot_nsc  +=  $val->subtotal;
        ?>
        <tr>
          <td><?= $no ?></td>
          <td><?= $val->id_part ?></td>
          <td><?= $val->nama_part ?></td>
          <td>B</td>
          <!-- <td align='right'><?= $val->qty ?></td> -->
          <td>
            <?php if($val->ev =='ev'){?>
                1
            <?php }else{?>
                <?= $val->qty ?>
            <?php }?>
          </td>
          <td align="right"><?= set_diskon($val->diskon_value, $val->tipe_diskon) ?></td>
          <td align="right">Rp. <?= mata_uang_rp($val->harga_beli) ?></td>
          <!-- <td align="right">Rp. <?= mata_uang_rp($val->subtotal) ?></td> -->
          <td>
            <?php if($val->ev =='ev'){
              if ($val->tipe_diskon == 'Percentage') {
                $diskon = ($val->diskon_value / 100) * $val->harga_beli;
              }
              if ($val->tipe_diskon == 'Value') {
                $diskon = $val->qty * $val->diskon_value;
              }
              $val->subtotal = $val->harga_beli - $diskon;
            ?>
              Rp. <?= mata_uang_rp($val->subtotal) ?>
            <?php }else{?>
              Rp. <?= mata_uang_rp($val->subtotal) ?>
            <?php }?>
          </td>
        </tr>
        <?php if($val->ev =='ev'){?>
                <tr>
                    <td></td>
                    <td> </td>
                    <td>SN : <b><?= $val->serial_number ?></b></td>
                </tr>
        <?php }?>
      <?php $no++;
      } ?>
      <?php if ($row->pkp == 1 && $row->tampil_ppn == 1) { ?>
        <tr class="footer">
          <td colspan="5" align='right'><b>Total Tanpa PPN</b></td>
          <td></td>
          <td align="right"><b>Rp. <?= mata_uang_rp($detail['total']['tot_no_ppn']) ?></b></td>
        </tr>
        <tr>
          <td colspan="5" align='right'><b>PPN</b></td>
          <td></td>
          <td align="right"><b>Rp. <?= mata_uang_rp($detail['total']['tot_ppn']) ?></b></td>
        </tr>
      <?php } ?>
      <!-- <tr>
        <td colspan="5" align='right'><b>Uang Muka</b></td>
        <td></td>
        <td align="right"><b>Rp. <?= mata_uang_rp($detail['total']['uang_muka']) ?></b></td>
      </tr> -->
      <tr>
        <td colspan="6" align='right'><b>Total</b></td>
        <td></td>
        <td align="right"><b>Rp. <?= mata_uang_rp($grand_tot_nsc) ?></b></td>
      </tr>
      <tr>
        <td><br></td>
      </tr>
     
    </table>
    <table>
    <tr>
                    <td colspan ="2" style='font-size: 12px;padding: left 35px;'><b>TANDA TANGAN</b></td>
                </tr>

                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                
                <tr>
                    <td colspan ="2"  style='padding: left 35px;'>___________________________</td>
                    <td style='padding: left 30px;'>______________________________</td>
                    <td>__________________</td>
                </tr>
                <tr>
                    <td style='font-size: 12px;' colspan ="2" style='padding: left 35px;'><b><?= $row->nama_customer ?></b></td>
                    <td style='font-size: 12px;padding: left 30px;'><b>Frontdesk</b></td>
                    <td style='font-size: 12px;'><b>Kasir</b></td>
                </tr>
    </table>
    
              
    <p style='font-size:7pt'><b>Catatan :</b> <br> * Harga sudah termasuk PPN <?php echo $persentase_ppn ?> %</p>
    <p style='font-size:7pt'>
        Dengan ini, Anda menyatakan bahwa data yang anda berikan dalam Nota Suku Cadang ini adalah data yang benar dan lengkap. Guna melindungi privasi Anda serta untuk terus meningkatkan kualitas produk/layanan, dengan ini Anda memberikan persetujuan kepada Main Dealer dan Dealer untuk : 
        <ol style='font-size:7pt;padding-top:-10px;' >
          <li>Memperoleh, mengelola, dan menghapus data pribadi Anda sesuai dengan tujuan Main Dealer dan Dealer yang berkaitan dengan Warranty dan Waste Management dan dengan prosedur yang sesuai dengan peraturan perundang-undangan</li>
          <li>Memberikan akses untuk Main Dealer dan Dealer serta pihak ketiga yang bekerja sama dengan Main Dealer dan Dealer untuk memproses data pribadi Anda untuk keperluan seperti berkomunikasi dengan Anda, menyampaikan promosi, dan informasi lainnya terkait produk/layanan kami yang bermanfaat bagi Anda.</li>
        </ol>
    </p>
  </body>

  </html>

<?php } ?>


<?php
if ($set == 'cetak_gabungan') { ?>

  <body>
    <table>
      <tr>
        <td><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
      </tr>
    </table>
    <div style="text-align: center;font-size: 13pt;padding-bottom:20px"><b>Nota Jasa Bengkel (NJB) & Nota Suku Cadang (NSC)</b></div>
    <table class="table" style="margin-bottom:10px">
      <tr>
        <td>No. WO</td>
        <td>: <?= $row->id_work_order ?></td>
        <td>ID Customer</td>
        <td>: <?= $row->id_customer ?></td>
      </tr>
      <tr>
        <td>Tgl. Servis</td>
        <td>: <?= $row->tgl_servis ?></td>
        <td>Nama Customer</td>
        <td>: <?= $row->nama_customer ?></td>
      </tr>
      <tr>
        <td>Waktu NJB</td>
        <td>: <?= $row->waktu_njb ?></td>
        <td>No. HP Customer</td>
        <td>: <?= $row->no_hp ?></td>
      </tr>
      <tr>
        <td>ID Mekanik</td>
        <td>: <?= $row->id_karyawan_dealer ?> <?= $row->honda_id ?></td>
        <td>Alamat Customer</td>
        <td>: <?= $row->alamat ?></td>
      </tr>
      <tr>
        <td>Nama Mekanik</td>
        <td>: <?= $row->nama_lengkap ?></td>
        <td>Tipe Unit</td>
        <td>: <?= $row->tipe_ahm ?></td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="65%">No. NJB : <?= $row->no_njb ?></td>
        <td style="font-weight:bold;text-align:center;font-size:12pt">Detail Pekerjaan</td>
      </tr>
    </table>
    <table class="table">
      <tr class='header'>
        <td>No.</td>
        <td>Pekerjaan</td>
        <td align="right">Nilai Promo</td>
        <td align="right">Biaya</td>
        <td align="right" width='20%'>Subtotal</td>
      </tr>
      <?php $no = 1;
      foreach ($njb['details'] as $val) { ?>
        <tr>
          <td><?= $no ?></td>
          <td><?= $val->pekerjaan ?></td>
          <td align="right"><?= set_diskon($val->diskon, $val->tipe_diskon) ?></td>
          <td align="right">Rp. <?= mata_uang_rp($val->harga) ?></td>
          <td align="right">Rp. <?= mata_uang_rp((int) $val->harga_net) ?></td>
        </tr>
      <?php $no++;
      } ?>
      <?php if ($row->pkp_njb == 1 && $row->tampil_ppn == 1) { ?>
        <tr class="footer">
          <td colspan="4" align='right'><b>Total Tanpa PPN</b></td>
          <td align="right"><b>Rp. <?= mata_uang_rp($njb['total']['tot_no_ppn']) ?></b></td>
        </tr>
        <tr>
          <td colspan="4" align='right'><b>Total PPN</b></td>
          <td align="right"><b>Rp. <?= mata_uang_rp($njb['total']['tot_ppn']) ?></b></td>
        </tr>
      <?php } ?>
      <tr class="footer">
        <td colspan="4" align='right'><b>Grand Total</b></td>
        <td align="right"><b>Rp. <?= mata_uang_rp($njb['total']['grand_tot']) ?></b></td>
      </tr>
    </table>

    <table class="table" style="margin-top:20px">
      <tr>
        <td width="40%">No. NSC : <?= $row_nsc->no_nsc ?></td>
        <td style="font-weight:bold;text-align:center;font-size:12pt" align="left">Detail Parts</td>
      </tr>
    </table>
    <table class="table">
      <tr class='header'>
        <td width='5%'>No.</td>
        <td width='18%'>ID Part</td>
        <td width='33%'>Deskripsi</td>
        <td width='5%'>Qty</td>
        <td align='center'>Disc. Value</td>
        <?php $label_harga = 'HET';
        if ($row->pkp == 1 && $row->tampil_ppn == 1) {
          $label_harga = "DPP";
        } ?>
        <td align="right"><?= $label_harga ?></td>
        <td align="right">Subtotal</td>
      </tr>
      <?php $no = 1;
      $grand_tot_nsc=0;
      foreach ($nsc['details'] as $val) { 
        $grand_tot_nsc+=$val->subtotal;
        ?>
        <tr>
          <td><?= $no ?></td>
          <td><?= $val->id_part ?></td>
          <td><?= $val->nama_part ?></td>
          <td align='center'><?= $val->qty ?></td>
          <td align="center"><?= set_diskon($val->diskon_value, $val->tipe_diskon) ?></td>
          <td align="right">Rp. <?= mata_uang_rp($val->harga_beli) ?></td>
          <td align="right">Rp. <?= mata_uang_rp($val->subtotal) ?></td>
        </tr>
      <?php $no++;
      } ?>
      <?php if ($row_nsc->pkp == 1 && $row->tampil_ppn == 1) { ?>
        <tr>
          <td colspan="5" align='right'><b>Total Tanpa PPN</b></td>
          <td></td>
          <td align="right"><b>Rp. <?= mata_uang_rp($nsc['total']['tot_no_ppn']) ?></b></td>
        </tr>
        <tr>
          <td colspan="5" align='right'><b>PPN</b></td>
          <td></td>
          <td align="right"><b>Rp. <?= mata_uang_rp($nsc['total']['tot_ppn']) ?></b></td>
        </tr>
      <?php } ?>
      <!-- <tr>
        <td colspan="5" align='right'><b>Uang Muka</b></td>
        <td></td>
        <td align="right"><b>Rp. <?= mata_uang_rp($nsc['total']['uang_muka']) ?></b></td>
      </tr> -->
      <tr>
        <td colspan="5" align='right'><b>Total</b></td>
        <td></td>
        <td align="right"><b>Rp. <?= mata_uang_rp($grand_tot_nsc) ?></b></td>
      </tr>
    </table>
    <?php if(!empty($parts_ev)){?>
    <br>
    <table class="table">
      <tr class='header'>
        <td>No.</td>
        <td >ID Part</td>
        <td >Deskripsi</td>
        <td >Serial Number</td>
      </tr>
      <?php $no = 1;
      foreach ($parts_ev as $part_ev) {
        ?>
        <tr>
          <td><?= $no ?></td>
          <td><?= $part_ev->id_part ?></td>
          <td><?= $part_ev->nama_part ?></td>
          <td><?= $part_ev->serial_number ?></td>
        </tr>
      <?php $no++;
      } ?>
    </table>
    <?php }?>
    <br>
    <table class="table" style="font-size:12pt">
      <tr>
        <td style="width:77%"><b>Grand Total</b></td>
        <td align="right"><b>Rp. <?= mata_uang_rp($grand_tot_nsc + $njb['total']['grand_tot']) ?></b></td>
      </tr>
    </table>
    <p style='font-size:7pt'><b>Catatan :</b> <br> * Harga sudah termasuk PPN <?php echo $persentase_ppn ?> %</p>

  </body>

  </html>

<?php } ?>