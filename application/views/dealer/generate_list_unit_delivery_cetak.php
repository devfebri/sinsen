<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->

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
        font-size: 11pt;
      }
    }
  </style>
</head>

<body>

  <?php
  if ($set == 'print') { ?>
    <p style="font-size: 8pt">
      <?= $row->nama_dealer ?><br>
      <?= $row->alamat_dealer ?><br>
      <?= $row->kelurahan_dealer ?>
      <br>
    </p>
    <table class="table table-borderedx">
      <tr>
        <td width="100%" align="center" colspan="5"><b>LIST PENGIRIMAN UNIT</b></b><br>&nbsp;</td>
      </tr>
      <tr>
        <td width="20%">No. Pengiriman Unit</td>
        <td>: <?= $row->id_generate ?></td>
        <td width="20%"></td>
        <td>Tgl. Pengiriman</td>
        <?php $tgl_pengiriman = date('d-m-Y', strtotime($row->tgl_pengiriman)) ?>
        <td>: <?= $tgl_pengiriman ?></td>
      </tr>
      <tr>
        <td>Nama Driver</td>
        <td>: <?= $row->driver ?></td>
      </tr>
      <tr>
        <td>No. Plat</td>
        <td>: <?= $row->no_plat ?></td>
      </tr>
    </table>
    <p style="text-align: center;font-weight: bold;">Detail</p>
    <table class="table table-bordered">
      <tr>
        <td>No</td>
        <td>Nama Konsumen</td>
        <td>No HP</td>
        <td>Alamat/Lokasi Pengiriman</td>
        <td>No Mesin</td>
        <td>No Rangka</td>
        <td>Tipe Motor</td>
        <td>Warna</td>
      </tr>
      <?php
      $no = 1;
      foreach ($units as $unt) : 
        if ($unt->is_ev =='1'){ 
          $row_span = 8;
        }else{
          $row_span = 5;
        }
      ?>

        <tr>
          <td rowspan="<?=$row_span?>" style="vertical-align: top"><?= $no ?></td>
          <td><?= $unt->nama_konsumen ?></td>
          <td><?= $unt->no_hp ?></td>
          <td><?= $unt->lokasi_pengiriman ?></td>
          <td><?= $unt->no_mesin ?></td>
          <td><?= $unt->no_rangka ?></td>
          <td><?= $unt->tipe_ahm ?></td>
          <td><?= $unt->warna ?></td>
        </tr>

        <?php 
        if ($unt->is_ev =='1'){ 
        echo "
          <tr>
          <td colspan='1' class='bold-td'>Tipe Part</td>
          <td colspan='2' class='bold-td'>Kode Part</td>
          <td colspan='2' class='bold-td'>Nama Part</td>
          <td colspan='3' class='bold-td'>Serial Number</td>
        </tr>";
          
          $this->db->select('sb.part_id,sb.part_desc,sb.serial_number,sb.tipe');
          $this->db->from('tr_sales_order_acc_ev sob');
          $this->db->join('tr_stock_battery sb', 'sob.serial_number = sb.serial_number', 'left');
          $this->db->where('sob.no_mesin', $this->db->escape_str($unt->no_mesin));
          $query = $this->db->get();
          $item = $query;
          foreach ($item->result() as $ev) { ?>
 
           <tr>
             <td colspan="1"><?= $ev->tipe ?></td>
             <td colspan="2"><?= $ev->part_id ?></td>
             <td colspan="2"><?= $ev->part_desc ?></td>
             <td colspan="3"><?= $ev->serial_number ?></td>
           </tr>

        <?php  } $rem = $this->db->query("SELECT * from tr_h3_serial_ev_tracking where no_mesin ='$unt->no_mesin' ");

				foreach ($rem->result() as $rem) { 
          ?>
            <tr>
            <td colspan="1"><?= $rem->type_accesories ?></td>
            <td colspan="2"><?= $rem->id_part ?></td>
            <td colspan="2"><?= $rem->nama_part ?></td>
            <td colspan="3"><?= $rem->serial_number ?></td>
          </tr>
            <?}
       }?>

        <tr>
          <td colspan="4">a. Proses PDI</td>
          <td colspan="3">e. Spion</td>
        </tr>
        <tr>
          <td colspan="4">b. Manual Book</td>
          <td colspan="3">f. BPPSG</td>
        </tr>
        <tr>
          <td colspan="4">c. Standard Tool Kit</td>
          <td colspan="3">g. Aksesoris</td>
        </tr>
        <tr>
          <td colspan="4">d. Helmet</td>
          <td colspan="3">h. Direct Gift</td>
        </tr>

      <?php $no++;
      endforeach ?>
    </table>
    <p style="text-align: right;">Jambi, <?= $tgl_pengiriman ?></p>
    <table>
      <tr>
        <td width="20%">
          Driver
          <br><br><br><br><br><br>
          (<?= $row->driver ?>)
        </td>
        <td width="60%"></td>
        <td width="20%">
          PIC Dealer
          <br><br><br><br><br><br>
          (____________________)
        </td>
      </tr>
    </table>
  <?php } ?>
</body>

</html>