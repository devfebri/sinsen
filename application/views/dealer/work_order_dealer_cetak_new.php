  <!DOCTYPE html>
  <html>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Cetak</title>
    <style>
      @media print {
        <?php
        $height = 340;
        $height += (count($pekerjaan) + count($parts)) * 5;
        ?>@page {
          sheet-size: 78mm <?= $height ?>mm;
          margin-left: 0.1cm;
          margin-right: 0.1cm;
          margin-bottom: 0.2cm;
          margin-top: 0.2cm;
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
          font-size: 8pt;
        }
      }
    </style>
  </head>
  <?php
  $dealer = dealer($id_dealer);
  $pembawa = (string)$row->nama_pembawa == '' ? $row->nama_customer : $row->nama_pembawa;
  ?>

  <body>
    <table>
      <tr>
        <td>
          <?= $dealer->nama_dealer ?><br>
          <?= $dealer->alamat ?><br>
          <?= $dealer->kelurahan . ', Kec. ' . $dealer->kecamatan . ', ' . ucwords(strtolower($dealer->kabupaten)) ?><br>
          Telp. <?= $dealer->no_telp ?><br>
        </td>
        <td align='right' style="vertical-align:top">
          <?php $logo = (string)$dealer->logo==''?'logo_sinsen.jpg':$dealer->logo; ?>
          <img src="<?='assets/panel/images/'.$logo?>" width="100px">
        </td>
      </tr>
    </table>
    <p style="text-align:center;font-size:10pt">PERINTAH KERJA BENGKEL</p>
    <table>
      <tr>
        <td>Tgl. Servis</td>
        <td>: <?= $row->tgl_servis ?> - <?= $row->jam_servis ?></td>
      </tr>
      <tr>
        <td>No. PKB</td>
        <td>: <?= $row->id_work_order ?></td>
      </tr>
      <tr>
        <td>Tipe RSV</td>
        <td>: <?= strtoupper($row->jenis_customer) ?></td>
      </tr>
      <tr>
        <td>Antrian / Kode Pit</td>
        <td>: <?= $row->id_antrian_short . ' / ' . $row->id_pit ?></td>
      </tr>
      <tr>
        <td>Service Advisor</td>
        <td>: <?= strtoupper($row->service_advisor) ?></td>
      </tr>
    </table>
    <p style="font-size:10pt;margin-bottom:0px">DATA PEMBAWA KENDARAAN</p>
    <table>
      <tr>
        <td>Nama</td>
        <td>: <?= strtoupper($pembawa) ?></td>
      </tr>
      <tr>
        <td>Alamat</td>
        <td>: <?= (string)$row->alamat_pembawa == '' ? strtoupper($row->alamat) : strtoupper($row->alamat_pembawa) ?></td>
      </tr>
      <tr>
        <td>Kel / Kec</td>
        <td>: <?= (string)$row->kelurahan_pembawa == '' ? strtoupper($row->kelurahan) : strtoupper($row->kelurahan_pembawa) ?>/<?= (string)$row->kecamatan_pembawa == '' ? strtoupper($row->kecamatan) : strtoupper($row->kecamatan_pembawa) ?></td>
      </tr>
      <tr>
        <td>No. Telp / HP</td>
        <td>: <?= (string)$row->no_hp_pembawa == '' ? $row->no_hp : $row->no_hp_pembawa ?></td>
      </tr>
    </table>
    <p style="font-size:10pt;margin-bottom:0px">DATA KENDARAAN</p>
    <table class="table">
      <tr>
        <td style="width:18%">No. Polisi</td>
        <td>: <?= strtoupper($row->no_polisi) ?></td>
        <td style="width:29%"></td>
        <td>Kilometer : </td>
      </tr>
      <tr>
        <td>No. Mesin</td>
        <td>: <?= strtoupper($row->no_mesin) ?></td>
        <td></td>
        <td><?= mata_uang_rp($row->km_terakhir) ?> Km </td>
      </tr>
    </table>
    <p style="padding-left:20px">
      <img src="<?= base_url('assets/panel/icon/fuel-bar-' . (int)$row->informasi_bensin . '.png') ?>" width='70'>
    </p>
    <p style="font-size:10pt;margin-bottom:0px">KELUHAN DAN CATATAN</p>
    <table>
      <tr>
        <td>Keluhan</td>
        <td>: <?= strtoupper($row->keluhan_konsumen) ?></td>
      </tr>
      <tr>
        <td>Catatan</td>
        <td>: <?= strtoupper($row->rekomendasi_sa) ?></td>
      </tr>
      <tr>
        <td colspan=2>Penggantian Suku Cadang : <?= strtoupper(str_replace('_', ' ', $row->konfirmasi_pekerjaan_tambahan)) ?></td>
      </tr>
    </table>
    <p style="font-size:10pt;margin-bottom:0px">RINCIAN PEKERJAAN DAN SUKU CADANG</p>
    <p style="font-size:9pt;margin-top:0px;margin-bottom:5px">Pekerjaan :</p>
    <table class="table" style="margin-bottom:0px">
      <?php $subtot_pkj = 0;
      $k = 0;
      foreach ($pekerjaan as $key => $pkj) {
        $k++;
        // if ($k > 2) break;
        $subtot_pkj += $pkj->subtotal; ?>
        <tr>
          <td><?= $pkj->deskripsi ?></td>
          <td>Rp. </td>
          <td align='right'><?= mata_uang_rp($pkj->subtotal) ?> </td>
        </tr>
      <?php } ?>
      <tr>
        <td style="border-bottom:1px solid #000">Sub Total</td>
        <td style="border-bottom:1px solid #000">Rp. </td>
        <td style="border-bottom:1px solid #000" align='right'><?= mata_uang_rp($subtot_pkj) ?> </td>
      </tr>
    </table>
    <p style="font-size:9pt;margin-top:2px;margin-bottom:5px;">Suku Cadang :</p>
    <table class="table">
      <?php $subtot_prt = 0;
      $k = 0;
      foreach ($parts as $key => $prt) {
        $k++;
        // if ($k > 1) break;
        $subtot_prt += $prt->tot_part; ?>
        <tr>
          <td><?= $prt->nama_part ?></td>
          <td>Rp. </td>
          <td align='right'><?= mata_uang_rp($prt->tot_part) ?> </td>
        </tr>
      <?php } ?>
      <tr>
        <td style="border-bottom:1px solid #000">Sub Total</td>
        <td style="border-bottom:1px solid #000">Rp. </td>
        <td style="border-bottom:1px solid #000" align='right'><?= mata_uang_rp($subtot_prt) ?> </td>
      </tr>
      <tr>
        <td style="border-bottom:1px solid #000">Total Estimasi</td>
        <td style="border-bottom:1px solid #000">Rp. </td>
        <td style="border-bottom:1px solid #000" align='right'><?= mata_uang_rp($row->grand_total) ?> </td>
      </tr>
    </table>
    <p style="font-size:9pt;margin-bottom:5px;">Estimasi Waktu Kerja : <?= $row->estimasi_waktu_kerja ?> Menit</p>

    <p style="font-size:9pt;margin-bottom:10px;">Mekanik : <?= strtoupper($nama_mekanik) ?></p>

    <table class="table" style="padding-left:5px;padding-right:5px;">
      <tr>
        <td align='center'>Pembawa Kendaraan</td>
        <td style="width:5px"></td>
        <td align='center'>Final Inspector</td>
      </tr>
      <tr>
        <td align="center" style="border-bottom:1px solid #000"><br><br><br><?= strtoupper($pembawa) ?></td>
        <td></td>
        <td align="center" style="border-bottom:1px solid #000"><br><br><br>&nbsp;</td>
      </tr>
      <tr>
        <td align='center'><br>Penyerahan <br> Kendaraan</td>
        <td></td>
        <td align='center'><br>Persetujuan Tambahan<br> Pekerjaan</td>
      </tr>
      <tr>
        <td align="center" style="border-bottom:1px solid #000"><br><br><br><br><br></td>
        <td></td>
        <td align="center" style="border-bottom:1px solid #000"><br><br><br><br><br></td>
      </tr>
    </table>
    <p style="font-size:9pt;margin-bottom:5px;">Syarat dan Ketentuan :</p>
    <table>
      <tr>
        <td style="vertical-align:top">1.</td>
        <td align="justify">Perintah Kerja Bengkel ini merupakan Surat Kuasa dari Pelanggan kepada <?= $dealer->nama_dealer ?> untuk melakukan pekerjaan seperti yang tertulis pada Perintah Kerja Bengkel ini.</td>
      </tr>
      <tr>
        <td style="vertical-align:top">2.</td>
        <td align="justify"><?= $dealer->nama_dealer ?> tidak bertanggung jawab atas segala kerugian yang timbul dari musibah luar kekuasaan kami.</td>
      </tr>
      <tr>
        <td style="vertical-align:top">3.</td>
        <td align="justify">Garansi perbaikan berlaku sejak tanggal serah terima kendaraan : <br>
          <ol type="a">
            <li>500 km / 1 minggu untuk servis reguler *)</li>
            <li>1.000 km / 1 bulan untuk bongkar mesin reguler *)</li>
            <li>1.000 km / 1 bulan servis CBR 250 dan PCX 150 *)</li>
            <li>1.500 km / 45 hari untuk bongkar mesin CBR 250 dan PCX 150 *)</li>
          </ol>
          (*) Mana yang tercapai terlebih dahulu
        </td>
      </tr>
      <tr>
        <td style="vertical-align:top">4.</td>
        <td align="justify">Harap tidak meninggalkan barang berharga di dalam kendaraan, <?= $dealer->nama_dealer ?> tidak bertanggung jawab apabila terjadi kehilangan.</td>
      </tr>
      <tr>
        <td style="vertical-align:top">5.</td>
        <td align="justify">Semua estimasi yang dihasilkan berkenaan dengan waktu penyelesaian maupun biaya yang diperlukan merupakan suatu taksiran dan tidak mengikat.</td>
      </tr>
    </table>
    <p style="font-size:7pt">Tgl Cetak : <?=waktu_full()?></p>
  </body>

  </html>