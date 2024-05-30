<?php if ($set == 'export') {
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=Service Reminder Schedule.xls");
  header("Pragma: no-cache");
  header("Expires: 0");
?>
  <!DOCTYPE html>
  <html>
  <!-- <html lang="ar"> for arabic only -->

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Export</title>
    <style>
      @media print {
        @page {
          sheet-size: 297mm 210mm;
          margin-left: 0.8cm;
          margin-right: 0.8cm;
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
          font-size: 11pt;
        }

        .str {
          mso-number-format: \@;
        }

      }
    </style>
  </head>

  <body>
    <div style="text-align: center;font-size: 13pt"><b>Service Reminder Schedule</b></div>
    <div style="text-align: center; font-weight: bold;">Periode : <?php echo date('d/m/Y', strtotime($start_date)) ?> s/d <?= date('d/m/Y', strtotime($end_date)) ?></div>
    <table class="table table-bordered" border="1">
      <tr>
        <td>ID CUSTOMER</td>
        <td>NAMA CUSTOMER</td>
        <td>NO HP</td>
        <td>TIPE MOTOR</td>
        <td>TGL. SERVIS TERAKHIR</td>
        <td>TIPE SERVIS TERAKHIR</td>
        <td>TGL. SERVIS SELANJUTNYA</td>
        <td>TIPE SERVIS SELANJUTNYA</td>
        <td>STATUS REMINDER SMS</td>
      </tr>
      <?php foreach ($details as $dt) { ?>
        <tr>
          <td><?= $dt->id_customer ?></td>
          <td><?= $dt->nama_customer ?></td>
          <td><?= $dt->no_hp ?></td>
          <td><?= $dt->tipe_ahm ?></td>
          <td><?= $dt->tgl_servis_sebelumnya ?></td>
          <td><?= $dt->tipe_servis_sebelumnya ?></td>
          <td><?= $dt->tgl_servis_berikutnya ?></td>
          <td><?= $dt->tipe_servis_berikutnya ?></td>
          <td><?= $dt->status_reminder_sms ?></td>
        </tr>
      <?php } ?>
    </table>
  </body>

  </html>
<?php } ?>