<?php
$cek_nama_pembawa_pemilik = explode(',', $row->tipe_coming);
if (in_array('milik', $cek_nama_pembawa_pemilik)) {
  $nama_pembawa_pemilik = $row->nama_customer;
} else {
  $nama_pembawa_pemilik = $row->nama_pembawa;
}
$dealer = dealer($id_dealer);
// send_json($row);
?>
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Cetak Form Service Advisor</title>
  <style>
    @media print {
      @page {
        sheet-size: 215mm 328mm;
        margin-left: 0.5cm;
        margin-right: 0.5cm;
        margin-bottom: 0.5cm;
        margin-top: 0.5cm;
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
      }

      .bordered {
        border: 1px solid black;
      }

      body {
        font-family: "Arial";
        font-size: 11pt;
      }

      .bold {
        font-weight: bold;
      }
    }
  </style>
</head>

<body>
  <table class="table table-borderedx">
    <tr>
      <td colspan=4 class='bordered' align='center'>
        <table class="table">
          <tr>
            <td width="15%" align='left'>
              <img src="<?= base_url('assets/panel/icon/honda.jpg') ?>" width='100'>
            </td>
            <td align='center'>
              <?= $dealer->kode_dealer_md ?> - <?= $dealer->nama_dealer ?> <br>
              Alamat : <font style="text-decoration:underline"><?= $dealer->alamat ?> <?= $dealer->no_telp ?></font><br>
              Booking Service & Service Kunjung : <?= $dealer->contact_booking_service ?>
            </td>
            <td width="15%" align='right'>
              <img src="<?= base_url('assets/panel/icon/ahass_jaminan.jpg') ?>" width='100'>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan=4 align='right' class='bordered'><b style='font-size:15pt'>FORM SERVICE ADVISOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><b style='font-size:14pt;font-weight:400'><?= substr($row->id_sa_form, -5) ?></b>&nbsp;&nbsp;</td>
    </tr>
    <tr>
      <td colspan=4 class='bordered'>
        <table>
          <tr>
            <!-- Data Motor -->
            <td style='width:36%; vertical-align:top'>
              <table style='font-size:10pt'>
                <tr>
                  <td colspan=2><b>Data Motor</b></td>
                </tr>
                <tr>
                  <td>No. Urut</td>
                  <td>: <?= $row->id_antrian_short ?></td>
                </tr>
                <tr>
                  <td>Tgl. Servis</td>
                  <td>: <?= $row->tgl_servis ?></td>
                </tr>
                <tr>
                  <td>No. Mesin</td>
                  <td>: <?= $row->no_mesin ?></td>
                </tr>
                <tr>
                  <td>No. Rangka</td>
                  <td>: <?= $row->no_rangka ?></td>
                </tr>
                <tr>
                  <td>No. Polisi</td>
                  <td>: <?= $row->no_polisi ?></td>
                </tr>
                <tr>
                  <td>Type</td>
                  <td>: <?= $row->tipe_ahm ?></td>
                </tr>
                <tr>
                  <td>Tahun</td>
                  <td>: <?= $row->tahun_produksi ?></td>
                </tr>
                <tr>
                  <td>KM</td>
                  <td>: <?= mata_uang_rp($row->km_terakhir) ?></td>
                </tr>
                <tr>
                  <td>* Email</td>
                  <td>: <?= $row->email ?></td>
                </tr>
                <tr>
                  <td>* Sosmed</td>
                  <td>: <?= $row->facebook ?></td>
                </tr>
              </table>
            </td>
            <!-- Data Pembawa -->
            <td style='vertical-align:top;width:36%'>
              <table style='font-size:10pt'>
                <tr>
                  <td colspan=2><b>Data Pembawa</b></td>
                </tr>
                <tr>
                  <td>Nama</td>
                  <td>: <?= $row->nama_pembawa ?></td>
                </tr>
                <tr>
                  <td>Alamat</td>
                  <td>: <?= $row->alamat_pembawa ?></td>
                </tr>
                <tr>
                  <td>Kel/Kec</td>
                  <td>: <?= $row->kelurahan_pembawa . '/' . $row->kecamatan_pembawa ?></td>
                </tr>
                <tr>
                  <td>No. Telp/HP</td>
                  <td>: <?= '/' . $row->no_hp_pembawa ?></td>
                </tr>
              </table>
              <table>
                <tr>
                  <td colspan=2><br> <b>Data Pemilik</b></td>
                </tr>
                <tr>
                  <td>Nama</td>
                  <td>: <?= $row->nama_customer ?></td>
                </tr>
                <tr>
                  <td>Alamat</td>
                  <td>: <?= $row->alamat ?></td>
                </tr>
                <tr>
                  <td>Kel/Kec</td>
                  <td>: <?= $row->kelurahan . '/' . $row->kecamatan ?></td>
                </tr>
                <tr>
                  <td>No. Telp/HP</td>
                  <?php $no_telp = (string)$row->no_telp == '' ? '-' : $row->no_telp; ?>
                  <td>: <?= $no_telp . '/' . $row->no_hp ?></td>
                </tr>
              </table>
            </td>
            <!-- Alasan Ke AHASS & Data Lainnya -->
            <td style='vertical-align:top;width:28%'>
              <table style='font-size:10pt'>
                <tr>
                  <td>Dari Dealer Sendiri :
                    Y <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='15' height='15'> &nbsp;
                    T <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='15' height='15'></td>
                </tr>
                <tr>
                  <td>Hubungan Pembawa & <br> Pemilik : <?= $row->hubungan_dengan_pemilik == '' ? '-' : $row->hubungan_dengan_pemilik ?></td>
                </tr>
                <tr>
                  <td><br><br> <b>Alasan Ke AHASS</b></td>
                </tr>
                <tr>
                  <td>
                    <div class="<?= $row->alasan_ke_ahass == 'Inisiatif sendiri' ? 'bold' : '' ?>">a. Inisiatif Sendiri</div>
                    <div class="<?= $row->alasan_ke_ahass == 'SMS' ? 'bold' : '' ?>">b. SMS Reminder</div>
                    <div class="<?= $row->alasan_ke_ahass == 'Telepon' ? 'bold' : '' ?>">c. Telp Reminder</div>
                    <div class="<?= $row->alasan_ke_ahass == 'Stiker Reminder' ? 'bold' : '' ?>">d. Sticker Reminder</div>
                    <div class="<?= $row->alasan_ke_ahass == 'Lainnya' ? 'bold' : '' ?>">e. Lainnya</div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class='bordered bold' style='font-size:12pt;text-align:center'>Kondisi Awal SMH</td>
      <td class='bordered bold' style='font-size:12pt;text-align:center'>Pekerjaan</td>
      <td class='bordered bold' style='font-size:12pt;text-align:center'>Estimasi Biaya</td>
      <td class='bordered bold' style='font-size:12pt;text-align:center'></td>
    </tr>
    <tr>
      <td rowspan=4 class='bordered' align='left' style='padding-top:10px;vertical-align:top'>
        <table>
          <tr>
            <td class='bordered' style='padding:10px'>
              <img src="<?= base_url('assets/panel/icon/fuel-' . (int)$row->informasi_bensin . '.png') ?>" width='130'>
            </td>
          </tr>
        </table>
        Catatan Lain : <br>
        <?= $row->catatan_tambahan ?>
      </td>
      <td class='bordered' style="vertical-align:top">
        <ol>
          <?php foreach ($pekerjaan as $pkj) { ?>
            <li><?= $pkj->deskripsi ?></li>
          <?php } ?>
        </ol>
      </td>
      <td class='bordered' style="vertical-align:top">
        <ol>
          <?php foreach ($pekerjaan as $pkj) { ?>
            <li>Rp. <?= mata_uang_rp($pkj->harga) ?></li>
          <?php } ?>
        </ol>
      </td>
      <td rowspan=8 class='bordered' style="vertical-align:top;text-align:center">
        <div class='bold'>Saran Ganti Sparepart</div>
        <table class='table table-bordered'>
          <tr>
            <td>Periode Ganti (KM)</td>
            <td>Sparepart</td>
          </tr>
          <?php foreach ($saran_ganti_utama as $sgs) { ?>
            <tr>
              <td><?= $sgs->periode_ganti ?></td>
              <td><?= $sgs->sparepart ?></td>
            </tr>
          <?php } ?>
        </table>
        <br>
        <div class='bold'>Paket Tambahan</div>
        <table class='table table-bordered'>
          <?php foreach ($saran_ganti_tambahan as $sgs) { ?>
            <tr>
              <td><?= $sgs->periode_ganti ?></td>
              <td><?= $sgs->sparepart ?></td>
            </tr>
          <?php } ?>
        </table>
      </td>
    </tr>
    <tr>
      <td class='bordered bold' style='font-size:12pt;text-align:center;height:20px'>Suku Cadang</td>
      <td class='bordered bold' style='font-size:12pt;text-align:center'>Estimasi Harga</td>
    </tr>
    <tr>
      <td class='bordered' style='vertical-align:top'>
        <ol>
          <?php foreach ($parts as $pkj) { ?>
            <li><?= $pkj->nama_part ?></li>
          <?php } ?>
        </ol>
      </td>
      <td class='bordered' style='vertical-align:top'>
        <ol>
          <?php foreach ($parts as $pkj) { ?>
            <li>Rp. <?= mata_uang_rp($pkj->harga) ?></li>
          <?php } ?>
        </ol>
      </td>
    </tr>
    <tr>
      <td class='bordered bold' style='font-size:12pt;text-align:center;height:20px'>Total Harga</td>
      <td class='bordered bold' style='font-size:12pt;'>Rp. <?= mata_uang_rp($row->grand_total) ?></td>
    </tr>
    <tr>
      <td colspan=3 class='bordered bold' style='font-size:12pt;text-align:center;height:20px'>Keluhan Konsumen</td>
    </tr>
    <tr>
      <td colspan=3 class='bordered' style='font-size:10pt;text-align:left;vertical-align:top'><?= $row->keluhan_konsumen ?></td>
    </tr>
    <tr>
      <td colspan=3 class='bordered bold' style='font-size:12pt;text-align:center;height:20px'>Analisa Service Advisor</td>
    </tr>
    <tr>
      <td colspan=3 class='bordered' style='font-size:10pt;text-align:left;vertical-align:top'><?= $row->rekomendasi_sa ?></td>
    </tr>
  </table>

  </td>
  </tr>
  </table>

  <table style='font-size:8pt;margin-top:20px'>
    <tr>
      <td style='width:60%;font-size:9pt;vertical-align:top'>
        <div class="">Apabila ada tambahan <b>PEKERJAAN/PERGANTIAN PART</b> di luar daftar di atas maka : </div>
        <div class="">
          <?php $chk_pekerjaan = $row->konfirmasi_pekerjaan_tambahan == 'via_no_hp' ? 'checked' : 'unchecked'; ?>
          <img src="<?= base_url('assets/panel/icon/' . $chk_pekerjaan . '-checkbox.png') ?>" width='15' height='15'>&nbsp;&nbsp;Konfirmasi dulu/telp. ke : <?= $row->no_hp_pembawa ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <?php $chk_pekerjaan = $row->konfirmasi_pekerjaan_tambahan == 'langsung' ? 'checked' : 'unchecked'; ?>
          <img src="<?= base_url('assets/panel/icon/' . $chk_pekerjaan . '-checkbox.png') ?>" width='15' height='15'>&nbsp;&nbsp;Langsung dikerjakan
        </div>
        <div>Part bekas dibawa konsumen :
          <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='15' height='15'> Ya &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='15' height='15'> Tidak
        </div>
      </td>
      <td>
        <div class="bold">Syarat Dan Ketentuan</div>
        <div>
          <ol>
            <li>Formulir ini adalah surat kuasa pekerjaan/PKB</li>
            <li>Bengkel tidak bertanggung jawab terhadap sepeda motor yang tidak diambil dalam 30 hari</li>
            <li>Bengkel tidak bertanggung jawab jika terjadi Force Majeure</li>
          </ol>
        </div>
      </td>
    </tr>
  </table>
  <table>
    <tr>
      <!-- Persetujuan Pekerjaan -->
      <td>
        <table class='table table-bordered' style='font-size:9pt'>
          <tr>
            <td colspan=2><b>Persetujuan Pekerjaan + Biaya + Waktu</b></td>
          </tr>
          <tr>
            <td style='height:70px;font-size:7pt;vertical-align:top;text-align:center'>Konsumen Ttd</td>
            <td style='height:70px;font-size:7pt;vertical-align:top;text-align:center'>Service Advisor Ttd</td>
          </tr>
        </table>
      </td>
      <!-- Tambahan Pekerjaan -->
      <td>
        <table class='table table-bordered' style='font-size:9pt'>
          <tr>
            <td><b>Tambahan Pekerjaan</b></td>
          </tr>
          <tr>
            <td style='height:70px;font-size:7pt;vertical-align:top;text-align:center'>Service Advisor Ttd</td>
          </tr>
        </table>
      </td>
      <!-- Tambahan Pekerjaan -->
      <td>
        <table class='table table-bordered' style='font-size:9pt'>
          <tr>
            <td><b>OK</b></td>
            <td><b>Paraf Final Ins.</b></td>
          </tr>
          <tr>
            <td colspan=2 style='height:70px;font-size:7pt;vertical-align:top;text-align:center'></td>
          </tr>
        </table>
      </td>
      <td>
        <table class='table table-bordered' style='font-size:9pt'>
          <tr>
            <td colspan=2><b>Penyerahan Motor Oleh SA</b></td>
          </tr>
          <tr>
            <td style='width:15px;height:70px;vertical-align:middle;font-size:16pt'>OK</td>
            <td style='height:70px;font-size:7pt;vertical-align:top;text-align:center'>Service Advisor Ttd</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <table class='table'>
    <tr>
      <!-- Saran Mekanik -->
      <td style='width:70%'>
        <table class='table table-bordered' style='font-size:11pt'>
          <tr>
            <td colspan=2><b>Saran Mekanik</b></td>
          </tr>
          <tr>
            <td style='height:70px;vertical-align:top;text-align:left'><?= $row->saran_mekanik ?></td>
            <td style='width:25%;height:70px;vertical-align:bottom;text-align:left'><?= $row->mekanik ?></td>
          </tr>
        </table>
      </td>
      <!-- Estimasi Waktu -->
      <td>
        <table class='table' style='font-size:11pt'>
          <tr>
            <td class='bordered' align='center'><b>Estimasi Waktu</b></td>
          </tr>
          <tr>
            <td class='bordered'>
              <table>
                <tr>
                  <td>Pendaftaran</td>
                  <td>: <?= substr($row->estimasi_waktu_daftar, -8) ?></td>
                </tr>
                <tr>
                  <td>Dikerjakan</td>
                  <td>: <?= substr($wo->start_at, -8) ?></td>
                </tr>
                <tr>
                  <td>Selesai</td>
                  <td>: <?= substr($wo->closed_at, -8) ?></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <table class='table'>
    <tr>
      <td>
        <div class="bold" style='font-size:9pt'>Garansi :</div>
        <div class="" style='font-size:8pt'>- 500 Km/1 minggu untuk Servis Reguler</div>
        <div class="" style='font-size:8pt'>- 1.000 Km/1 Bulan untuk Bongkar Mesin Reguler</div>
        <div class="" style='font-size:8pt'>- 1.000 Km/1 Bulan untuk Servis CBR 250 dan PCX 150</div>
        <div class="" style='font-size:8pt'>- 1.500 Km/1 Bulan untuk Bongkar Mesin CBR 250 dan PCX 150</div>
        <div class="bold" style='font-size:11pt'>SERVIS RUTIN DI AHASS MOTOR TERAWAT KANTONG HEMAT</div>
      </td>
      <td align='right'>
        <img src="<?= base_url('assets/panel/icon/hanya_di_ahass.jpg') ?>" width='140'>
      </td>
    </tr>
  </table>
</body>

</html>