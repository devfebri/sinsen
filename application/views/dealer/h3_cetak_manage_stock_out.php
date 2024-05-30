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
    <p style="font-size: 8pt">
        <br>
    </p>
    <table class="table table-borderedx">
        <tr>
            <td width="100%" align="center" colspan="5"><b><?= $dealer->nama_dealer ?></b>
                <br>&nbsp;</td>
        </tr>
        <tr>
            <td width="100%" align="center" colspan="5"><b>BERITA ACARA STOCK OUT</b>
                <br>&nbsp;</td>
        </tr>
    </table>
    <?php
        $tanggalKonfirmasi = date('d/m/Y', strtotime($manage_stock_out->created_at));
        $bulan = date('M d', strtotime($manage_stock_out->created_at));
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
        <tr>
            <td style="text-align: right" width="50%">Jumlah Item fisik yang dikeluarkan :</td>
            <td style="text-align: left" width="50%"><?= count($manage_stock_out_parts) ?></td>
        </tr>
        <tr>
            <td style="text-align: right" width="50%">Detail Asset yang dikeluarkan :</td>
            <td style="text-align: left" width="50%"></td>
        </tr>
    </table>
    <table style="margin-top: 10px;" class="table table-bordered">
        <tr>
            <td width="5%">No</td>
            <td width="10" style="text-align: center;">Part Number</td>
            <td width="30%" style="text-align: center;">Parts Description</td>
            <td width="5%" style="text-align: center;">Qty</td>
            <td width="10%" style="text-align: center;">Uom</td>
        </tr>
        <?php $index = 1; foreach ($manage_stock_out_parts as $part): 

        $partDetail = $this->part->find($part->id_part, 'id_part');
        $satuan = $this->satuan->find($part->id_satuan_stock, 'id_satuan');

        ?>
        <tr>
            <td><?= $index ?>.</td>
            <td><?= $part->id_part ?></td>
            <td><?= $partDetail->nama_part ?></td>
            <td><?= $part->kuantitas ?></td>
            <td><?= $satuan->satuan ?></td>
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
            <td style="text-align: left" width="50%"></td>
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
            <td style="text-align: right" width="50%">Confirmed Oleh :</td>
            <td style="text-align: left" width="50%"></td>
        </tr>
        <tr>
            <td style="text-align: right" width="50%">Nama :</td>
            <td style="text-align: left" width="50%"></td>
        </tr>
        <tr>
            <td style="text-align: right" width="50%">Jabatan :</td>
            <td style="text-align: left" width="50%"></td>
        </tr>
        <tr>
            <td style="text-align: right" width="50%">Tanggal :</td>
            <td style="text-align: left" width="50%"></td>
        </tr>
    </table>
</body>
</html>