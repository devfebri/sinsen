<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Laporan Part Lost Sales</title>
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

                table.header-table{
                    font-size: 12px;
                }

                table.table-parts{
                    font-size: 10px;
                }

                .text-center{
                    text-align: center;
                }

                .text-right{
                    text-align: right;
                }

                td.side-border{
                    border-left: 1px solid black;
                    border-right: 1px solid black;
                    padding-left: 6px;
                    padding-right: 6px;
                }
            }
        </style>
    </head>
    <body>
        <table class="table table-borderedx" style='margin-top: 25px;'>
            <tr>
                <td style='text-align:center; font-size: 22px;'>Laporan Part Lost Sales</td>
            </tr>
        </table>
        <table class="table table-borderedx">
            <?php if(date('m', strtotime($start_date)) == date('m', strtotime($end_date))): ?>
            <tr>
                <td style='text-align:center; font-size: 12;'>Periode: <?= date('F Y', strtotime($start_date)) ?></td>
            </tr>
            <?php else: ?>
            <tr>
                <td style='text-align:center; font-size: 12;'>Periode: <?= date('F Y', strtotime($start_date)) ?> - <?= date('F Y', strtotime($end_date)) ?></td>
            </tr>
            <?php endif; ?>
        </table>
        <style>
            .date_range{
                font-size: 10px;
            }
        </style>
        <table class='table'>
            <tr>
                <td class='text-right date_range'>Date : <?= date('d/m/Y', strtotime($start_date)) ?> - <?= date('d/m/Y', strtotime($end_date)) ?></td>
            </tr>
        </table>
        <table class="table table-bordered table-parts">
            <tr class='header'>
                <td width='5%'>No.</td>
                <td>Part Number</td>
                <td>Part Deskripsi</td>
                <td class='text-center'>HET</td>
                <td class='text-center'>Qty</td>
                <td>Total</td>
            </tr>
            <?php 
            $grand_total = 0;
            $total_qty = 0;
            $index = 1;
            foreach($parts as $part): ?>
            <tr class='header'>
                <td><?= $index ?>.</td>
                <td><?= $part->id_part ?></td>
                <td><?= $part->nama_part ?></td>
                <td class='text-right'>Rp <?= number_format($part->het, 0, ',', '.') ?></td>
                <td class='text-center'><?= $part->qty ?></td>
                <td class='text-right'>Rp <?= number_format($part->total, 0, ',', '.') ?></td>
            </tr>
            <?php 
            $total_qty += $part->qty;
            $grand_total += $part->total;
            $index++;
            endforeach; ?>
            <tr class='total'>
                <td class='text-center' colspan='4'>Grand Total</td>
                <td class='text-center'><?= number_format($total_qty, 0, ',', '.') ?></td>
                <td class='text-right'>Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
            </tr>
        </table>
    </body>
</html>