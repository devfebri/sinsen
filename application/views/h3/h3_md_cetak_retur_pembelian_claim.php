<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Retur Pembelian</title>
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
    <table class="table" style='margin-bottom: 10px;'>
        <tr>
            <td class='text-center text-bold' style='font-size: 16px;'>RETUR PEMBELIAN<td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width='16%'>Main Dealer</td>
            <td width='1%'>:</td>
            <td width='33%'>PT. Sinar Sentosa Primatama</td>
            <td width='16%'>Hal</td>
            <td width='1%'>:</td>
            <td width='33%'></td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width='16%'>Alamat</td>
            <td width='1%'>:</td>
            <td width='33%'>Jl. Kol. Abunjani No.9 Sipin - Jambi</td>
            <td width='16%'>Tgl & Waktu</td>
            <td width='1%'>:</td>
            <td width='33%'><?= date('d/m/Y H:i', time()) ?></td>
        </tr>
    </table>
    <table class="table">
    <tr>
            <td width='16%'>No. Packing Sheet</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $retur_pembelian_claim['packing_sheet_number'] ?></td>
            <td width='16%'>No. Retur</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $retur_pembelian_claim['no_retur'] ?></td>
        </tr>
        <tr>
            <td width='16%'>Tgl. Packing Sheet</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $retur_pembelian_claim['packing_sheet_date'] ?></td>
            <td width='16%'>Tgl. Retur</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $retur_pembelian_claim['tanggal'] ?></td>
        </tr>
        <tr>
            <td width='16%'>No. Faktur</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $retur_pembelian_claim['invoice_number'] ?></td>
            <td width='16%'>Tgl. Faktur</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $retur_pembelian_claim['invoice_date'] ?></td>
        </tr>
    </table>
    <table style="margin-top: 20px;;" class="table">
        <tr>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='5%'>No.</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Kode Part</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Nama Part</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Qty</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Harga</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Total Harga</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Keterangan</td>
        </tr>
        <?php 
        $index = 1; 
        $total_amount = 0;
        $total_qty = 0;
        foreach($parts as $part): 
        ?>
        <tr>
            <td><?= $index ?>.</td>
            <td><?= $part['id_part'] ?></td>
            <td><?= $part['nama_part'] ?></td>
            <td><?= $part['qty'] ?></td>
            <td>Rp <?= number_format($part['price'], 0, ',', '.') ?></td>
            <td>Rp <?= number_format($part['nominal'], 0, ',', '.') ?></td>
            <td><?= $part['keterangan'] ?></td>
        </tr>
        <?php 
        $total_amount += floatval($part['nominal']);
        $total_qty += floatval($part['qty']);
        $index++; 
        endforeach; 
        ?>
        <tr>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold' colspan="2"></td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold'>Total</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold'><?= $total_qty ?></td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold'></td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold'>Rp <?= number_format($total_amount, 0, ',', '.') ?></td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold'></td>
        </tr>
    </table>
    <table style="margin-top: 40px;" class="table">
        <tr>
            <td width="33.33%" class="text-center">Dibuat Oleh,</td>
            <td width="33.33%" class="text-center">Diperiksa Oleh,</td>
            <td width="33.33%" class="text-center">Diketahui Oleh,</td>
        </tr>
    </table>
    <table style="margin-top: 70px;" class="table">
        <tr>
            <td width="16.6%" class="text-left">(</td>
            <td width="16.6%" class="text-right">)</td>
            <td width="16.6%" class="text-left">(</td>
            <td width="16.6%" class="text-right">)</td>
            <td width="16.6%" class="text-left">(</td>
            <td width="16.6%" class="text-right">)</td>
        </tr>
    </table>
</body>
</html>