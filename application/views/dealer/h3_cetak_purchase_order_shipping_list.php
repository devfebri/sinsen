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
 <p style="font-size: 8pt">
  <br>
 </p>
 <table class="table table-borderedx">
    <tr>
      <td width="100%" align="center" colspan="5"><b>Purchase Order</b><br>&nbsp;</td>
    </tr>
    <tr>
        <td width="20%">No. PO Part</td>
        <td width="5%" style="text-align: right;">:</td>
        <td width="75%" ><?= $purchase_order->po_id ?></td>
    </tr>
    <tr>
        <td width="20%">Tgl. PO</td>
        <td width="5%" style="text-align: right;">:</td>
        <td width="75%" ><?= date('d-m-Y', strtotime($purchase_order->tanggal_order)) ?></td>
    </tr>
  </table>
  <table style="margin-top: 20px;" class="table table-bordered">
    <tr>
        <td width="5%">No</td>
        <td width="15%" style="text-align: center;">Nomor Part</td>
        <td width="15%" style="text-align: center;">Nama Part</td>
        <td width="5%" style="text-align: center;">Qty</td>
        <td width="20%" style="text-align: center;">Harga Beli</td>
        <td width="20%" style="text-align: center;">Jumlah Beli</td>
        <td width="20%" style="text-align: center;">Jumlah</td>
    </tr>
    <?php $index = 1; foreach ($purchase_order_parts as $part): ?>
    <?php 
        $ms_part = $this->ms_part->find($part->id_part, 'id_part');    
    ?>
    <tr>
        <td width="5%"><?= $index ?>.</td>
        <td width="15%"><?= $part->id_part ?></td>
        <td width="15%"><?= $ms_part->nama_part ?></td>
        <td width="5%"><?= $part->kuantitas ?></td>
        <td width="20%"><?= $part->harga_saat_dibeli ?></td>
        <td width="20%"><?= $part->harga_saat_dibeli * $part->kuantitas ?></td>
        <td width="20%"><?= $part->harga_saat_dibeli * $part->kuantitas ?></td>
    </tr>
    <?php $index++; endforeach; ?>
  </table>
</body>
</html>
