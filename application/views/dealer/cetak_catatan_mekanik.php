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
          margin-left: 0.2cm;
          margin-right: 0.2cm;
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
  ?>

  <body>
    <?php /*
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
    */?>
    <p style="text-align:center;font-size:11pt;padding-top:5px;"><u>Catatan AHASS <?php echo $dealer->kode_dealer_md ?></u></p>
    <p style="font-size:9pt;margin-bottom:5px;">Tgl Service : ___________________</p>

    <p style="font-size:9pt;margin-bottom:5px;">Saran / Catatan Mekanik :</p>
    <table>
      <?php for($i=1;$i<=15;$i++){ ?>
      <tr>
        <td style="vertical-align:top height:25px"><?php echo $i;?>.</td>
        <td style="height:25px" align="justify">_________________________________________________________</td>
      </tr>
      <?php } ?>
    </table>
    <br>
    <div style="border: solid 1px black; padding:5px;">
      <table>
        <tr>
          <td>Melayani : <br>1. Service Berkala (Booking Service & Servis Kunjung)</td>
        </tr>
	<tr>
		<td>2. Penjualan Sparepart</td>
	</tr>
        <tr>
          <td align="justify">Hubungi / SMS : _______________________</td>
        </tr>
      </table>
    </div>
  </body>
</html>