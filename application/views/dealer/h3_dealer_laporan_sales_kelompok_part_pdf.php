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
                    font-size: 8pt;
                }

                td.header{
                    background-color: yellow;
                }
            }
        </style>
    </head>
    <body>
        <?php foreach($data as $chunk): ?>
            <table class="table table-bordered" style='margin-bottom: 50px;'>
                <tr>
                    <td rowspan='2' class='header text-center' width='6%'>NO</td>
                    <td rowspan='2' class='header' width='10%'>PRODUK</td>
                    <?php foreach($chunk[0]['payloads'] as $payload): ?>
                    <td colspan='4' class='text-center header' width='35%'><?= $payload['label_month'] ?></td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <?php foreach($chunk[0]['payloads'] as $payload): ?>
                    <td class='text-center header' width='7%'>UE</td>
                    <td class='text-center header' width='7%'>Qty</td>
                    <td class='text-center header' width='7%'>Amount Sales</td>
                    <td class='text-center header' width='7%'>% Ach</td>
                    <?php endforeach; ?>
                </tr>
                <?php 
                $index = 1;
                foreach($chunk as $kelompok_part): 
                ?>
                <tr>
                    <td width='6%'><?= $index ?></td>
                    <td width='10%'><?= $kelompok_part['kelompok_part'] ?></td>
                    <?php foreach($kelompok_part['payloads'] as $payload): ?>
                    <td width='5%'><?= number_format($payload['unit_entry']['kuantitas'], 0, ",", ".") ?></td>
                    <td width='5%'><?= number_format($payload['sales_out']['kuantitas'], 0, ",", ".") ?></td>
                    <td width='5%' class='text-right'><?= number_format($payload['sales_out']['amount'], 0, ",", ".") ?></td>
                    <td width='5%'><?= number_format($payload['ach'], 0, ",", ".") ?>%</td>
                    <?php endforeach; ?>
                </tr>
                <?php 
                $index++;
                endforeach; 
                ?>
            </table>
            <!-- <pagebreak /> -->
            <?php endforeach; ?>
    </body>
</html>