<?php
if ($set == 'print') {
  $nama_pembawa_pemilik = explode(',',$row->tipe_coming);
  if (in_array('milik',$nama_pembawa_pemilik)) {
    $nama_pembawa_pemilik = $row->nama_customer;
  }else{
    $nama_pembawa_pemilik = $row->nama_pembawa;
  }
  ?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Cetak</title>
    <style>
      @media print {
        @page {
          sheet-size: 210mm 297mm;
          margin-left: 0.5cm;
          margin-right: 0.8cm;
          margin-bottom: 0.5cm;
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
          font-size: 8pt;
        }

        .border-bottom {
          border-bottom: 1px solid black;
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
        <td class='border-bottom'>
          <b><?= dealer($id_dealer)->nama_dealer . ' ' . dealer($id_dealer)->kode_dealer_md ?></b> <br>
          <?= dealer($id_dealer)->alamat ?><br>
          Telp/Faks : <?= dealer()->no_telp . ' / -' ?>
        </td>
        <td class='border-bottom'>
          <table>
            <tr>
              <td colspan=2><b>PERINTAH KERJA BENGKEL</b></td>
            </tr>
            <tr>
              <td>Nomor</td>
              <td>: <?= $row->id_work_order == NULL ? $row->id_sa_form : $row->id_work_order ?></td>
            </tr>
            <tr>
              <td>Tanggal</td>
              <td>: <?= $row->tgl_servis . ' ' . $row->jam_servis ?></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <table class="table table-borderedx" style="margin-top:5px">
      <tr>
        <td width='10%'>No. Polisi</td>
        <td width='1%'>:</td>
        <td width='20%'><?= $row->no_polisi ?></td>
        <td width='18%'>No. Telp/ Hp</td>
        <td>: <?= $row->no_telp . '/' . $row->no_hp ?></td>
        <td>Km</td>
        <td>: <?= $row->km_terakhir ?></td>
      </tr>
      <tr>
        <td>Pemilik</td>
        <td width='1%'>:</td>
        <td><?= $row->nama_customer ?></td>
        <td>No. Rangka / Mesin</td>
        <td>: <?= $row->no_rangka . ' / ' . $row->no_mesin ?></td>
        <td rowspan=2 style="vertical-align:bottom">No. Antri</td>
        <td rowspan=2 style="vertical-align:bottom">: <b style="font-size:18px"><?= $row->id_antrian_short ?></b></td>
      </tr>
      <tr>
        <td style='vertical-align:top'>Alamat</td>
        <td style='vertical-align:top' width='1%'>:</td>
        <td><?= $row->alamat ?></td>
        <td style='vertical-align:top'>Tipe / Warna</td>
        <td style='vertical-align:top'>: <?= $row->tipe_ahm . ' / ' . $row->warna ?></td>
      </tr>
      <tr>
        <td>Keluhan</td>
        <td width='1%'>:</td>
        <td colspan=3><?= $row->keluhan_konsumen ?></td>
      </tr>
    </table>
    <table class="table" style="margin-top:5px">
      <tr>
        <td style="width:50%;vertical-align:top">
          <table class="table table-bordered">
            <tr>
              <td class='bold'>No.</td>
              <td class='bold'>Kode Jasa</td>
              <td class='bold'>Nama Jasa</td>
              <td class='bold'>Waktu</td>
            </tr>
            <?php $no = 1;
            foreach ($pekerjaan as $pkj) { ?>
              <tr>
                <td><?= $no ?></td>
                <td><?= $pkj->id_jasa ?></td>
                <td><?= $pkj->deskripsi ?></td>
                <td><?= $pkj->waktu ?> Menit</td>
              </tr>
            <?php $no++;
            } ?>
          </table>
        </td>
        <td style="width:50%;vertical-align:top">
          <table class="table table-bordered">
            <tr>
              <td class='bold'>No.</td>
              <td class='bold'>ID Part</td>
              <td class='bold'>Nama Part</td>
              <td class='bold'>Qty</td>
            </tr>
            <?php $no = 1;
            foreach ($parts as $prt) { ?>
              <tr>
                <td><?= $no ?></td>
                <td><?= $prt->id_part ?></td>
                <td><?= $prt->nama_part ?></td>
                <td><?= $prt->qty ?></td>
              </tr>
            <?php $no++;
            } ?>
          </table>
        </td>
      </tr>
    </table>
    <table class="table table-borderedc" style="margin-top:10px;font-size:8pt">
      <tr>
        <td style="width:55%">
          <table class="table">
            <tr>
              <td width='20%'>Mekanik</td>
              <td>: <?= $row->nama_lengkap ?></td>
            </tr>
            <tr>
              <td>Catatan</td>
              <td>: <?= $row->keterangan_tambahan ?></td>
            </tr>
            <tr>
              <td colspan=2>
                SYARAT DAN KETENTUAN : <br>
                - PKB ini merupakan SURAT KUASA dari Pelanggan kepada BENGKEL <br>
                a. Mengerjakan pekerjaan seperti yang tertulis pada KPB ini <br>
                b. Ijin mencoba kendaraan diluar BENGKEL <br>
                Distribusi : Asli => Frontdesk, Copy => Pelanggan <br>
                * Servis terakhir Tgl : <?= isset($last_wo) ? $last_wo->tgl_servis : '-' ?> Mekanik : <?= isset($last_wo) ? $last_wo->nama_lengkap : '-' ?>
              </td>
            </tr>
          </table>
        </td>
        <td style="vertical-align:top" width="45%">
          <table class="table">
            <tr>
              <td align="center" colspan=3>
                Estimasi Waktu Kerja : <?= $row->estimasi_waktu_kerja ?> Menit
              </td>
            </tr>
            <tr>
              <td align="center" colspan=3>
                Estimasi Biaya : Rp. <?= mata_uang_rp($estimasi_biaya) ?>
              </td>
            </tr>
            <tr>
              <td>Pemilik / Pembawa</td>
              <td>Service Advisor</td>
              <td>Final Inspector</td>
            </tr>
            <tr>
              <td>
                <br>
                <br>
                <br>
                <br>
                <?= $nama_pembawa_pemilik ?>
              </td>
              <td>
                <br>
                <br>
                <br>
                <br>
               
              </td>
              <td>
                <br>
                <br>
                <br>
                <br>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>

  </html>
<?php
  // die();
} elseif ($set == 'cetak_pkb_dengan_harga') { ?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Cetak</title>
    <style>
      @media print {
        @page {
          sheet-size: 210mm 297mm;
          margin-left: 0.5cm;
          margin-right: 0.8cm;
          margin-bottom: 0.5cm;
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
          font-size: 9pt;
        }

        .border-bottom {
          border-bottom: 1px solid black;
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
        <td class='border-bottom'>
          <b><?= dealer()->nama_dealer . ' ' . dealer()->kode_dealer_md ?></b> <br>
          <?= dealer()->alamat ?><br>
          Telp/Faks : <?= dealer()->no_telp . ' / -' ?>
        </td>
        <td class='border-bottom'>
          <table>
            <tr>
              <td colspan=2><b>PERINTAH KERJA BENGKEL</b></td>
            </tr>
            <tr>
              <td>Nomor</td>
              <td>: <?= $row->id_work_order == NULL ? $row->id_sa_form : $row->id_work_order ?></td>
            </tr>
            <tr>
              <td>Tanggal</td>
              <td>: <?= $row->tgl_servis . ' ' . $row->jam_servis ?></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <table class="table table-borderedx" style="margin-top:5px">
      <tr>
        <td width='10%'>No. Polisi</td>
        <td width='1%'>:</td>
        <td width='20%'><?= $row->no_polisi ?></td>
        <td width='18%'>No. Telp/ Hp</td>
        <td>: <?= $row->no_telp . '/' . $row->no_hp ?></td>
        <td>Km</td>
        <td>: <?= $row->km_terakhir ?></td>
      </tr>
      <tr>
        <td>Pemilik</td>
        <td width='1%'>:</td>
        <td><?= $row->nama_customer ?></td>
        <td>No. Rangka / Mesin</td>
        <td>: <?= $row->no_rangka . ' / ' . $row->no_mesin ?></td>
        <td rowspan=2 style="vertical-align:bottom">No. Antri</td>
        <td rowspan=2 style="vertical-align:bottom">: <b style="font-size:18px"><?= $row->id_antrian_short ?></b></td>
      </tr>
      <tr>
        <td style='vertical-align:top'>Alamat</td>
        <td style='vertical-align:top' width='1%'>:</td>
        <td><?= $row->alamat ?></td>
        <td style='vertical-align:top'>Tipe / Warna</td>
        <td style='vertical-align:top'>: <?= $row->tipe_ahm . ' / ' . $row->warna ?></td>
      </tr>
      <tr>
        <td>Keluhan</td>
        <td width='1%'>:</td>
        <td colspan=3><?= $row->keluhan_konsumen ?></td>
      </tr>
    </table>
    <table class="table" style="margin-top:5px;font-size:8pt">
      <tr>
        <td style="width:50%;vertical-align:top">
          <table class="table table-bordered">
            <tr>
              <td class='bold'>No.</td>
              <td class='bold'>Kode Jasa</td>
              <td class='bold'>Nama Jasa</td>
              <td class='bold'>Waktu</td>
              <td class='bold'>Harga</td>
              <td class='bold'>Diskon</td>
              <td class='bold'>Total</td>
            </tr>
            <?php $no = 1;
            foreach ($pekerjaan as $pkj) { ?>
              <tr>
                <td><?= $no ?></td>
                <td><?= $pkj->id_jasa ?></td>
                <td><?= $pkj->deskripsi ?></td>
                <td><?= $pkj->waktu ?> Menit</td>
                <td align='right'><?= mata_uang_rp($pkj->harga) ?></td>
                <td align='right'><?= mata_uang_rp($pkj->diskon_rp) ?></td>
                <td align='right'><?= mata_uang_rp($pkj->harga_net) ?></td>
              </tr>
            <?php $no++;
            } ?>
          </table>
        </td>
        <td style="width:50%;vertical-align:top">
          <table class="table table-bordered">
            <tr>
              <td class='bold'>No.</td>
              <td class='bold'>ID Part</td>
              <td class='bold'>Nama Part</td>
              <td class='bold'>Qty</td>
              <td class='bold'>Harga</td>
              <td class='bold'>Diskon</td>
              <td class='bold'>Total</td>
            </tr>
            <?php $no = 1;
            foreach ($parts as $prt) { ?>
              <tr>
                <td><?= $no ?></td>
                <td><?= $prt->id_part ?></td>
                <td><?= $prt->nama_part ?></td>
                <td><?= $prt->qty ?></td>
                <td align='right'><?= mata_uang_rp($prt->harga) ?></td>
                <td align='right'><?= mata_uang_rp($prt->promo_rp) ?></td>
                <td align='right'><?= mata_uang_rp($prt->tot_part) ?></td>
              </tr>
            <?php $no++;
            } ?>
          </table>
        </td>
      </tr>
    </table>
    <table class="table table-borderedc" style="margin-top:10px;font-size:9pt">
      <tr>
        <td style="width:55%">
          <table class="table">
            <tr>
              <td width='20%'>Mekanik</td>
              <td>: <?= $row->nama_lengkap ?></td>
            </tr>
            <tr>
              <td>Catatan</td>
              <td>: <?= $row->keterangan_tambahan ?></td>
            </tr>
            <tr>
              <td colspan=2>
                SYARAT DAN KETENTUAN : <br>
                - PKB ini merupakan SURAT KUASA dari Pelanggan kepada BENGKEL <br>
                a. Mengerjakan pekerjaan seperti yang tertulis pada KPB ini <br>
                b. Ijin mencoba kendaraan diluar BENGKEL <br>
                Distribusi : Asli => Frontdesk, Copy => Pelanggan <br>
                * Servis terakhir Tgl : <?= isset($last_wo) ? $last_wo->tgl_servis : '-' ?> Mekanik : <?= isset($last_wo) ? $last_wo->nama_lengkap : '-' ?>
              </td>
            </tr>
          </table>
        </td>
        <td style="vertical-align:top" width="45%">
          <table class="table">
            <tr>
              <td align="center" colspan=3>
                Estimasi Waktu Kerja : <?= $row->estimasi_waktu_kerja ?> Menit
              </td>
            </tr>
            <tr>
              <td align="center" colspan=3>
                Estimasi Biaya : Rp. <?= mata_uang_rp($estimasi_biaya) ?>
              </td>
            </tr>
            <tr>
              <td>Pemilik / Pembawa</td>
              <td>Service Advisor</td>
              <td>Final Inspector</td>
            </tr>
            <tr>
              <td>
                <br>
                <br>
                <br>
                <br>
                <?= $nama_pembawa_pemilik ?>
              </td>
              <td>
                <br>
                <br>
                <br>
                <br>
                
              </td>
              <td>
                <br>
                <br>
                <br>
                <br>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>

  </html>
<?php } ?>