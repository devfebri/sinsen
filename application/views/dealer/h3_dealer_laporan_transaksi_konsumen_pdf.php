<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Laporan Transaksi Konsumen</title>
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
                <td style='font-size: 20px;'>Laporan Transaksi Konsumen</td>
            </tr>
        </table>
        <?php foreach($data as $row): ?>
            <table class="table table-bordered" style='margin-bottom: 50px;'>
                <tr>
                    <td rowspan='2' class='header text-center' width='3%'>NO</td>
                    <td rowspan='2' width='10%' class=' header text-center'>Nama Konsumen</td>
                    <td rowspan='2' width='10%' class=' header text-center'>Alamat</td>
                    <td rowspan='2' width='10%' class=' header text-center'>No. Handphone</td>
                    <td rowspan='2' width='10%' class=' header text-center'>Kota/Kab</td>
                    <td rowspan='2' width='10%' class=' header text-center'>Tipe Motor</td>
                    <td rowspan='2' width='10%' class=' header text-center'>Nama Tipe Motor</td>
                    <td rowspan='2' width='10%' class=' header text-center'>No. Polisi</td>
                    <td rowspan='2' width='10%' class=' header text-center'>Tgl. Pertama Transaksi di AHASS</td>
                    <?php foreach($row[0]['data_penjualan'] as $month): ?>
                    <td colspan='2' class='text-center header' width='40%'><?= $month['label_month'] ?></td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <?php foreach($row[0]['data_penjualan'] as $month): ?>
                    <td class='text-center header' width='5%'>Oli</td>
                    <td class='text-center header' width='5%'>Part</td>
                    <?php endforeach; ?>
                </tr>
                <?php 
                $index = 1;
                foreach($row as $customer): 
                ?>
                <tr>
                    <td width='3%'><?= $index ?>.</td>
                    <td width='10%'><?= $customer['nama_customer'] ?></td>
                    <td width='10%'><?= $customer['alamat'] ?></td>
                    <td width='10%'><?= $customer['no_hp'] ?></td>
                    <td width='10%'><?= $customer['kab_kota'] ?></td>
                    <td width='10%'><?= $customer['tipe_motor'] ?></td>
                    <td width='10%'><?= $customer['nama_tipe_motor'] ?></td>
                    <td width='10%'><?= $customer['no_polisi'] ?></td>
                    <td width='10%'><?= $customer['transaksi_pertama'] ?></td>
                    <?php foreach($customer['data_penjualan'] as $data_penjualan): ?>
                    <td width='5%' class='text-right'><?= number_format($data_penjualan['oli']['amount'], 0, ",", ".") ?></td>
                    <td width='5%' class='text-right'><?= number_format($data_penjualan['part']['amount'], 0, ",", ".") ?></td>
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