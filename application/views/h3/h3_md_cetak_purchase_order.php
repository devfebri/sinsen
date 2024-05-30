<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Purchase Order Main Dealer</title>
    <style>
        @media print {
            @page {
                sheet-size: 210mm 297mm;
                 margin-left: 0.5cm;
                margin-right: 0.5cm;
                margin-bottom: 0.5cm;
                margin-top: 0.5cm;
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
            <td class='text-center text-bold' style='font-size: 16px;'>Purchase Order Main Dealer<td>
        </tr>
    </table>
    <table class="table" style='margin-bottom: 15px;'>
        <tr>
            <td width='16%'>Main Dealer</td>
            <td width='1%'>:</td>
            <td width='33%'>PT. Sinar Sentosa Primatama</td>
            <td width='16%'></td>
            <td width='1%'></td>
            <td width='33%'></td>
        </tr>
        <tr>
            <td width='16%'>Alamat</td>
            <td width='1%'>:</td>
            <td width='33%'>JL. Kol. Abunjani No. 9 - Sipin Jambi</td>
            <td width='16%'></td>
            <td width='1%'></td>
            <td width='33%'></td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width='16%'>No. PO</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $purchase_order['id_purchase_order'] ?></td>
            <td width='15%'>Masa Berlaku</td>
            <td width='1%'>:</td>
            <td width='34%'><?= lang("month_{$purchase_order['bulan']}") ?></td>
        </tr>
        <tr>
            <td width='16%'>Tgl PO</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $purchase_order['tanggal_po'] ?></td>
            <td width='15%'>Supplier</td>
            <td width='1%'>:</td>
            <td width='34%'><?= $purchase_order['supplier'] ?></td>
        </tr>
        <tr>
            <td width='16%'>Jenis Order</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $purchase_order['jenis_po'] ?></td>
            <td width='15%'>Dikirim ke</td>
            <td width='1%'>:</td>
            <td width='34%'><?= $purchase_order['dikirim_ke'] ?></td>
        </tr>
        <tr>
            <td width='16%'>Pembayaran</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $purchase_order['jenis_pembayaran'] ?></td>
            <td width='15%'></td>
            <td width='1%'></td>
            <td width='34%'></td>
        </tr>
    </table>
    <table style="margin-top: 20px;" class="table">
        <tr>
            <td class='text-bold' style='border-top: 1px solid black; border-bottom: 1px solid black;' width='5%'>No.</td>
            <td class='text-bold' style='border-top: 1px solid black; border-bottom: 1px solid black;'>No. Part</td>
            <td class='text-bold' style='border-top: 1px solid black; border-bottom: 1px solid black;'>Nama Part</td>
            <td class='text-bold text-center' style='border-top: 1px solid black; border-bottom: 1px solid black'>Qty</td>
            <td class='text-bold text-center' style='border-top: 1px solid black; border-bottom: 1px solid black;' width='10%'>HET</td>
            <td class='text-bold text-center' style='border-top: 1px solid black; border-bottom: 1px solid black;' width='10%'>HPP</td>
            <td class='text-bold text-center' style='border-top: 1px solid black; border-bottom: 1px solid black;'>Amount</td>
        </tr>
        <?php
        $total_qty = 0;
        $total_amount = 0;
        $index = 1; 
        foreach($parts as $part): 
        ?>
        <tr>
            <td><?= $index ?>.</td>
            <td><?= $part['id_part'] ?></td>
            <td><?= $part['nama_part'] ?></td>
            <td class='text-center'><?= number_format($part['qty_order'], 0, ",", ".") ?></td>
            <td class='text-right'>Rp <?= number_format($part['harga_jual'], 0, ",", ".") ?></td>
            <td class='text-right'>Rp <?= number_format($part['harga'], 0, ",", ".") ?></td>
            <td class='text-right'>Rp <?= number_format($part['amount'], 0, ",", ".") ?></td>
        </tr>
        <?php 
        $total_qty += $part['qty_order'];
        $total_amount += $part['amount'];
        $index++; 
        endforeach; 
        ?>
        <tr>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold' colspan="2"></td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold'>Total</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold text-center'><?= $total_qty ?></td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold'></td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold'></td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold text-right'>Rp <?= number_format($total_amount, 0, ",", ".") ?></td>
        </tr>
    </table>
    <table style="margin-top: 40px;" class="table">
        <tr>
            <td width="33.33%" class="text-center">Dibuat Oleh,</td>
            <td width="33.33%" class="text-center">Diketahui Oleh,</td>
            <td width="33.33%" class="text-center">Disetujui Oleh,</td>
        </tr>
    </table>
    <table style="margin-top: 70px;" class="table">
        <tr>
            <td width="16.66%" style='padding-left: 40px;' class="text-left">(</td>
            <td width="16.66%" style='padding-right: 40px;' class="text-right">)</td>
            <td width="16.66%" style='padding-left: 40px;' class="text-left">(</td>
            <td width="16.66%" style='padding-right: 40px;' class="text-right">)</td>
            <td width="16.66%" style='padding-left: 40px;' class="text-left">(</td>
            <td width="16.66%" style='padding-right: 40px;' class="text-right">)</td>
        </tr>
    </table>
</body>
</html>