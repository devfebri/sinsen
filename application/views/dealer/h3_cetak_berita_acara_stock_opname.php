<!DOCTYPE html>
<html>
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
 <p style="font-size: 6pt">
  <br>
 </p>
 <table class="table table-borderedx">
    <tr>
      <td width="100%" align="center" colspan="5"><b><?= $dealer->nama_dealer ?></b><br>&nbsp;</td>
    </tr>
    <tr>
      <td width="100%" align="center" colspan="5"><b>BERITA ACARA STOCK OPNAME</b><br>&nbsp;</td>
    </tr>
  </table>
    <?php
        $tanggalKonfirmasi = date('d/m/Y', strtotime($stock_opname->created_at));
        $bulan = date('M d', strtotime($stock_opname->created_at));
    ?>
  <table class="table">
        <tr>
            <td style="text-align: right" width="50%">Tanggal Konfirmasi Stock Out :</td>
            <td style="text-align: left" width="50%"><?= $tanggalKonfirmasi ?></td>
        </tr>
        <tr>
            <td style="text-align: right" width="50%">Bulan :</td>
            <td style="text-align: left" width="50%"><?= $bulan ?></td>
        </tr>
        <?php 
            $fisikDihitung = 0;
            foreach ($stock_opname_parts as $part) {
                $fisikDihitung += (int) $part->stock_aktual;
            }

            $fisikDisistem = 0;
            foreach ($stock_opname_parts_selisih as $part) {
                $fisikDisistem += (int) $part->stock;
            }
        ?>
        <tr>
            <td style="text-align: right" width="50%">Jumlah Item fisik dihitung :</td>
            <td style="text-align: left" width="50%"><?= $fisikDihitung ?></td>
        </tr>
        <tr>
            <td style="text-align: right" width="50%">Jumlah Item fisik didalam sistem :</td>
            <td style="text-align: left" width="50%"><?= $fisikDisistem ?></td>
        </tr>
    </table>
    <table class="table" style="margin-top: 20px;">
    <tr>
        <td style="text-align: right" width="50%">Asset hasil stock opname :</td>
        <td style="text-align: left" width="50%"></td>
    </tr>
    </table>
  <table style="margin-top: 10px;" class="table table-bordered">
    <tr>
        <td width="5%">No</td>
        <td width="25%" style="text-align: center;">Part Number</td>
        <td width="40%" style="text-align: center;">Part Description</td>
        <td width="10%" style="text-align: center;">System Qty</td>
        <td width="10%" style="text-align: center;">Actual Qty</td>
        <td width="10%" style="text-align: center;">UOM</td>
    </tr>
    <?php $index = 1; foreach ($stock_opname_parts as $part): ?>
    <?php 
        $partDetail = $this->part->find($part->id_part, 'id_part');
    ?>
    <tr>
        <td><?= $index ?>.</td>
        <td><?= $part->id_part ?></td>
        <td><?= $partDetail->nama_part ?></td>
        <td><?= $part->stock ?></td>
        <td><?= $part->stock_aktual ?></td>
        <td>Pcs</td>
    </tr>
    <?php $index++; endforeach; ?>
  </table>

  <table class="table" style="margin-top: 20px;">
    <tr>
        <td style="text-align: right" width="50%">Perbedaan Asset Tercatat :</td>
        <td style="text-align: left" width="50%"></td>
    </tr>
    </table>
  <table style="margin-top: 10px;" class="table table-bordered">
    <tr>
        <td width="5%">No</td>
        <td width="25%" style="text-align: center;">Part Number</td>
        <td width="40%" style="text-align: center;">Part Description</td>
        <td width="20%" style="text-align: center;">Quantity Difference</td>
        <td width="10%" style="text-align: center;">UOM</td>
    </tr>
    <?php $index = 1; foreach ($stock_opname_parts_selisih as $part): ?>
    <?php 
        $partDetail = $this->part->find($part->id_part, 'id_part');
    ?>
    <tr>
        <td><?= $index ?>.</td>
        <td><?= $part->id_part ?></td>
        <td><?= $partDetail->nama_part ?></td>
        <td><?= $part->stock - $part->stock_aktual ?></td>
        <td>Pcs</td>
    </tr>
    <?php $index++; endforeach; ?>
  </table>
  <table style="margin-top: 30px;" class="table">
        <tr>
            <td style="text-align: right" width="50%">Confirmed Oleh :</td>
            <td style="text-align: left" width="50%"></td>
        </tr>
        <tr>
            <td style="text-align: right" width="50%">Nama :</td>
            <td style="text-align: left" width="50%"></td>
        </tr>
        <tr>
            <td style="text-align: right" width="50%">Jabatan :</td>
            <td style="text-align: left" width="50%">Warehouse PIC</td>
        </tr>
        <tr>
            <td style="text-align: right" width="50%">Tanggal :</td>
            <td style="text-align: left" width="50%"></td>
        </tr>
    </table>
    <hr>
    <h5 style="margin: 0;">FOR OFFICE USE ONLY</h5>
    <hr style="margin-top: 120px">
    <table class="table">
        <tr>
            <td style="text-align: right" width="25%">Confirmed Oleh :</td>
            <td style="text-align: left" width="25%"></td>
            <td style="text-align: right" width="25%">Confirmed Oleh :</td>
            <td style="text-align: left" width="25%"></td>
        </tr>
        <tr>
            <td style="text-align: right" width="25%">Nama :</td>
            <td style="text-align: left" width="25%"></td>
            <td style="text-align: right" width="25%">Nama :</td>
            <td style="text-align: left" width="25%"></td>
        </tr>
        <tr>
            <td style="text-align: right" width="25%">Jabatan :</td>
            <td style="text-align: left" width="25%">Branch Manager</td>
            <td style="text-align: right" width="25%">Jabatan :</td>
            <td style="text-align: left" width="25%">Owner</td>
        </tr>
        <tr>
            <td style="text-align: right" width="25%">Tanggal :</td>
            <td style="text-align: left" width="25%"></td>
            <td style="text-align: right" width="25%">Tanggal :</td>
            <td style="text-align: left" width="25%"></td>
        </tr>
    </table>
</body>
</html>
