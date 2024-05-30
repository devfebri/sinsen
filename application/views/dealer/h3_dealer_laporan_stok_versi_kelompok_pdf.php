<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Laporan Stok versi Kelompok</title>
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
                    font-size: 8pt;
                }

                .text-center{
                    align-text: center;
                }

                td.header{
                    background-color: yellow;
                }
            }
        </style>
    </head>
    <body>
        <?php foreach($data as $chunk_row): ?>
            <table class="table table-bordered" style='margin-bottom: 50px;'>
                <tr>
                    <td rowspan='3' class='header text-center' width='5%'>NO</td>
                    <td rowspan='3' class='header' width='10%'>PRODUK</td>
                    <?php foreach($chunk_row[0]['payloads'] as $payload): ?>
                    <td colspan='7' class='text-center header' width='35%'><?= $payload['label_month'] ?></td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <?php foreach($chunk_row[0]['payloads'] as $payload): ?>
                    <td colspan='2' class='text-center header' width='15%'>Sales In</td>
                    <td colspan='2' class='text-center header' width='15%'>Sales Out</td>
                    <td colspan='2' class='text-center header' width='15%'>Stok</td>
                    <td rowspan='2' class='text-center header' width='5%'>S/L</td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <?php foreach($chunk_row[0]['payloads'] as $payload): ?>
                    <td class='text-center header' width='5%'>Qty</td>
                    <td class='text-center header' width='10%'>Amount</td>
                    <td class='text-center header' width='5%'>Qty</td>
                    <td class='text-center header' width='10%'>Amount</td>
                    <td class='text-center header' width='5%'>Qty</td>
                    <td class='text-center header' width='10%'>Amount</td>
                    <?php endforeach; ?>
                </tr>
                <?php 
                $index = 1;
                foreach($chunk_row as $row): 
                ?>
                <tr>
                    <td width='5%'><?= $index ?></td>
                    <td width='10%'><?= $row['kelompok_part'] ?></td>
                    <?php foreach($row['payloads'] as $payload): ?>
                    <td width='5%'><?= $payload['sales_in']['kuantitas'] ?></td>
                    <td width='10%'>Rp <?= number_format($payload['sales_in']['amount'], 0, ',', '.') ?></td>
                    <td width='5%'><?= $payload['sales_out']['kuantitas'] ?></td>
                    <td width='10%'>Rp <?= number_format($payload['sales_out']['amount'], 0, ',', '.') ?></td>
                    <td width='5%'><?= $payload['stock']['kuantitas'] ?></td>
                    <td width='10%'>Rp <?= number_format($payload['stock']['amount'], 0, ',', '.') ?></td>
                    <td width='5%'><?= number_format($payload['sales_out_3_month']['average'], 2) ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php 
                $index++;
                endforeach; 
                ?>
            </table>
            <pagebreak />
        <?php endforeach; ?>
    </body>
</html>