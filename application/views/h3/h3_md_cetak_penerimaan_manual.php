<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Penerimaan Manual</title>
    <style>
        @media print {
            @page {
                sheet-size: 210mm 297mm;
                /*  margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 1cm;
                margin-top: 1cm;*/
            }

            .text-bold {
                font-weight: bold;
            }

            .text-center {
                text-align: center;
            }

            .text-left {
                text-align: left;
            }

            .text-right {
                text-align: right;
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
                font-size: 12px;
            }

            .top-line td{
                vertical-align: text-top;
            }

            table.small-text td{
                font-size: 12px;
            }

            .line-through{
                text-decoration: line-through;
            }
        }
    </style>
</head>
<body>
    <table class="table" style='margin-bottom: 20px;'>
        <tr>
            <td class='text-center text-bold' style='font-size: 16px;'>LAPORAN PENERIMAAN MANUAL<td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width='20%'>No. Penerimaan</td>
            <td width='1%'>:</td>
            <td width='29%'><?= $penerimaan_manual['id_penerimaan_manual'] ?></td>
            <td width='15%'>No. Dok Ref</td>
            <td width='1%'>:</td>
            <td width='34%'><?= $penerimaan_manual['id_referensi'] ?></td>
        </tr>
        <tr>
            <td width='20%'>Tanggal Penerimaan</td>
            <td width='1%'>:</td>
            <td width='29%'><?= $penerimaan_manual['tanggal_penerimaan_manual'] ?></td>
            <td width='15%'>Tgl. Dok Ref</td>
            <td width='1%'>:</td>
            <td width='34%'><?= $penerimaan_manual['tanggal_referensi'] ?></td>
        </tr>
    </table>
    <table style="margin-top: 20px;;" class="table">
        <tr>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='5%'>No.</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black; padding-right: 12px;'>No. Part</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Nama Part</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='5%'>Qty</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='11%' class='text-center' colspan='2'>Harga Beli</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='11%' class='text-center' colspan='2'>Total Harga</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Lokasi/Rak</td>
        </tr>
        <?php $total_qty = 0; $total_amount = 0; $index = 1; foreach($parts as $part): ?>
        <tr>
            <td><?= $index ?>.</td>
            <td style='padding-right: 12px;'><?= $part['id_part'] ?></td>
            <td><?= $part['nama_part'] ?></td>
            <td><?= $part['qty_terima'] ?></td>
            <td width='5%' style='padding-left: 10px;'>Rp</td>
            <td width='11%' class='text-right' style='padding-right: 10px;'><?= $part['harga_formatted'] ?></td>
            <td width='5%' style='padding-left: 10px;'>Rp</td>
            <td width='11%' class='text-right' style='padding-right: 10px;'><?= $part['total_amount_formatted'] ?></td>
            <td><?= $part['lokasi'] ?></td>
        </tr>
        <?php $total_qty += $part['qty_terima']; $total_amount += $part['total_amount']; $index++; endforeach; ?>
        <tr>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold text-center' colspan='3'>Total</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold'><?= $total_qty ?></td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold'></td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'></td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black; padding-left: 10px;'>Rp</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black; padding-right: 10px;' class='text-bold text-right'><?= number_format($total_amount, 0, ',', '.') ?></td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'></td>
        </tr>
    </table>
    <table>
        <tr>
            <td class='text-bold' style='font-style: italic'>Terbilang : <?= number_to_words($total_amount) ?></td>
        </tr>
    </table>
    <table class="table" style="margin-top: 20px;">
        <tr>
            <td>Jambi, <?= date('d-m-Y', time()) ?></td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width="33.33%">Dibuat Oleh,</td>
            <td width="33.33%" class="text-center">Diterima/Diperiksa,</td>
            <td width="33.33%" class="text-center">Diketahui Oleh,</td>
        </tr>
    </table>
    <!-- <table style="margin-top: 70px;" class="table">
        <tr>
            <td width="16.66%" class="text-left">(</td>
            <td width="16.66%" class="text-right">)</td>
            <td width="16.66%" class="text-left">(</td>
            <td width="16.66%" class="text-right">)</td>
            <td width="16.66%" class="text-left">(</td>
            <td width="16.66%" class="text-right">)</td>
        </tr>
    </table> -->
</body>
</html>