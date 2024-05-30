<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->
    <?php 
        function mata_uang($a){
            return number_format($a, 0, ',', '.');
        }
    ?>

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
            .text-center{text-align: center;}
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
            body{
                font-family: "Arial";
                font-size: 11pt;
            }
        }
    </style>
</head>

<body>

<?php if ($set=='srut_kredit'){ ?>
 <table class="table table-borderedd">
   <tr>
     <td>Nomor</td><td>: <?= $nomor ?></td>
     <td>Jambi, <?= tgl_indo(date('Y-m-d'),' ') ?></td>
   </tr>
   <tr>
     <td>Lampiran</td><td>:</td>
     <td>Kepada Yth :</td>
   </tr>
   <tr>
     <td>Perihal</td><td>: Penyerahan SRUT Honda</td>
     <td>Finance Company</td>
   </tr>
  </table>
   <p>Dengan hormat,</p>
  <p>Dengan ini kami serahkan kepada Bapak/Ibu, SRUT HONDA sebanyak 5 lembar berdasarkan pada
penjualan PT. SINAR SENTOSA PRIMATAMA (SIPIN) dengan keterangan sbb : </p>
  <table class="table table-bordered">
    <tr>
      <td>No.</td>
      <td>Nama Konsumen</td>
      <td>No. Mesin</td>
      <td>No. SRUT</td>
      <td>No. Faktur</td>
    </tr>
    <?php $no=1; foreach ($srut as $rs): 
      $fkb = $this->db->query("SELECT nomor_faktur from tr_fkb WHERE no_mesin_spasi='$rs->no_mesin'");
      if ($fkb->num_rows() > 0) {
        $fkb = $fkb->row()->nomor_faktur;
      }else{
        $fkb='';
      } 
    ?>
      <tr>
        <td><?= $no ?></td>
        <td><?= $rs->nama_konsumen ?></td>
        <td><?= $rs->no_mesin ?></td>
        <td><?= $rs->no_srut ?></td>
        <td><?= $fkb ?></td>
      </tr>
    <?php $no++; endforeach ?>

  </table>
  <p>Besar harapan Kami, penyerahan SRUT dapat Bapak/Ibu terima dengan baik. Terima Kasih.</p>
  <table class="table">
    <tr>
      <td style="text-align: center;">Yang Menerima
      <br><br><br><br><br>
      _______________________
      </td>
      <td style="text-align: center;">Hormat Kami,<br> Yang Menyerahkan
      <br><br><br><br><br>
      _______________________
      </td>
    </tr>
  </table>
<?php } ?>
<?php if ($set=='srut_cash'): ?>
  <p style="text-align: center;text-decoration: underline;font-weight: bold;font-size: 12pt">TANDA TERIMA</p>
  <p>Sudah diterima dari PT. SINAR SENTOSA PRIMATAMA (SIPIN), Dokumen Kendaraan Roda Dua Merk Honda. <br>
    Atas nama : 
  </p>
  <table>
    <tr>
      <td style="width: 50%">Nama Pemilik</td><td>: <?= $srut->nama_konsumen ?></td>
    </tr>
    <tr>
      <td>Alamat</td><td>: <?= $srut->alamat ?></td>
    </tr>
     <tr>
      <td>No. Rangka</td><td>: <?= $srut->no_rangka ?></td>
    </tr>
     <tr>
      <td>No. Mesin</td><td>: <?= $srut->no_mesin ?></td>
    </tr>
     <tr>
      <td>Tipe/Warna</td><td>: <?= $srut->id_tipe_kendaraan.'/'.$srut->id_warna ?></td>
    </tr>
     <tr>
      <td>No. Polisi</td><td>: <?= $srut->no_plat ?></td>
    </tr>
  </table>
  <p>Yang diterima antara lain:</p>
  <table>
    <tr>
      <td style="width: 50%">BPKB Asli Motor</td><td>: <?= $srut->no_bpkb ?></td>
    </tr>
    <tr>
      <td>SRUT Asli Motor</td><td>: <?= $srut->no_srut ?></td>
    </tr>
    <?php  $fkb = $this->db->query("SELECT nomor_faktur from tr_fkb WHERE no_mesin_spasi='$srut->no_mesin'");
      if ($fkb->num_rows() > 0) {
        $fkb = $fkb->row()->nomor_faktur;
      }else{
        $fkb='';
      }  ?>
    <tr>
      <td>Faktur Nomor</td><td>: <?= $fkb ?></td>
    </tr>
  </table>
  <br>
  <table class="table">
    <tr>
      <td></td>
     <td style="text-align:center;">Jambi, <?= tgl_indo(date('Y-m-d'),' ') ?></td>
    </tr>
     <tr>
      <td style="text-align: center;">Diserahkan Oleh
      <br><br><br><br><br>
      _______________________
      </td>
      <td style="text-align: center;">Yang Menerima
      <br><br><br><br><br>
      _______________________
      </td>
    </tr>
  </table>
<?php endif ?>
</body>
</html>
