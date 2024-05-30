<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Laporan Penjualan Per Part Number</title>
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
                    font-size: 7pt;
                }

                .text-center{
                   text-align: center;
                }
                
                .text-right{
                   text-align: right;
                }

                td.header{
                    background-color: yellow;
                }
            }
        </style>
    </head>
    <body>
        <table class="table" style='margin-bottom: 10px;'>
            <tr>
                <td style='font-size: 20px;'>Laporan Kelompok Barang Per Part Number</td>
            </tr>
        </table>
        <?php foreach($data as $chunk_month): ?>
            <table class="table table-bordered" style='margin-bottom: 50px;'>
                <tr>
                    <td rowspan='3' class='header text-center' width='3%'>NO</td>
                    <td rowspan='3' width='15%' class=' header text-center'>Nomor Part</td>
                    <td rowspan='3' width='12%' class=' header text-center'>HET</td>
                    <?php foreach($chunk_month[0]['payloads'] as $month): ?>
                    <td colspan='6' class='text-center header' width='40%'><?= $month['label_month'] ?></td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <?php foreach($chunk_month[0]['payloads'] as $month): ?>
                    <td colspan='2' class='text-center header' width='10%'>Pembelian</td>
                    <td colspan='2' class='text-center header' width='10%'>Penjualan</td>
                    <td colspan='2' class='text-center header' width='10%'>Stok</td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <?php foreach($chunk_month[0]['payloads'] as $month): ?>
                    <td class='text-center header' width='5%'>Qty</td>
                    <td class='text-center header' width='5%'>Amount</td>
                    <td class='text-center header' width='5%'>Qty</td>
                    <td class='text-center header' width='5%'>Amount</td>
                    <td class='text-center header' width='5%'>Qty</td>
                    <td class='text-center header' width='5%'>Amount</td>
                    <?php endforeach; ?>
                </tr>
                <?php 
                $index = 1;
                foreach($chunk_month as $part): 
                ?>
                <tr>
                    <td width='3%'><?= $index ?></td>
                    <td width='15%'><?= $part['id_part'] ?></td>
                    <td width='12%' class='text-right'><?= number_format($part['harga_dealer_user'], 0, ",", ".") ?></td>
                    <?php foreach($part['payloads'] as $payload): ?>
                    <td width='5%'><?= number_format($payload['sales_in']['kuantitas'], 0, ",", ".") ?></td>
                    <td width='5%' class='text-right'><?= number_format($payload['sales_in']['amount'], 0, ",", ".") ?></td>
                    <td width='5%'><?= number_format($payload['sales_out']['kuantitas'], 0, ",", ".") ?></td>
                    <td width='5%' class='text-right'><?= number_format($payload['sales_out']['amount'], 0, ",", ".") ?></td>
                    <td width='5%'><?= number_format($payload['stock']['kuantitas'], 0, ",", ".") ?></td>
                    <td width='5%' class='text-right'><?= number_format($payload['stock']['amount'], 0, ",", ".") ?></td>
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