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

                .table-font-sm tr td {
                    font-size: 11px;
                }

                body {
                    font-family: "Arial";
                    font-size: 10pt;
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
        <table class="table">
            <tr>
                <td colspan='2' class='text-center' style='font-size: 20px;'>Laporan Stok All</td>
            </tr>
            <tr>
                <td width='50%'>Periode : <?= date('F', strtotime($this->input->get('start_date'))) ?> - <?= date('F', strtotime($this->input->get('end_date'))) ?></td>
                <td width='50%' class='text-right'><?= date('d/m/Y', strtotime($this->input->get('start_date'))) ?> - <?= date('d/m/Y', strtotime($this->input->get('end_date'))) ?></td>
            </tr>
        </table>
        <table class="table table-bordered table-font-sm">
            <tr>
                <td class='text-center' width='5%'>No</td>
                <td width='10%'>Part Number</td>
                <td width='20%'>Description</td>
                <td width='5%'>Qty</td>
                <td width='10%'>Harga Beli</td>
                <td width='10%'>Jumlah</td>
                <td width='10%'>Harga Jual</td>
                <td width='10%'>Jumlah</td>
                <td width='5%'>Kel. Produk</td>
                <td width='5%'>Rank</td>
                <td width='10%'>Status</td>
            </tr>
            <?php 
                $total_harga_beli = $total_harga_jual = 0;
                $index = 1; foreach($stock as $each_stok): 
            ?>
            <tr>
                <td class='text-center'><?= $index ?></td>
                <td><?= $each_stok['id_part'] ?></td>
                <td><?= $each_stok['nama_part'] ?></td>
                <td><?= $each_stok['kuantitas'] ?></td>
                <td>Rp <?= number_format($each_stok['harga_beli'], 0, ',', '.') ?></td>
                <?php 
                    $amount_harga_beli = $each_stok['harga_beli'] * $each_stok['kuantitas'];
                    $total_harga_beli += $amount_harga_beli;
                ?>
                <td>Rp <?= number_format($amount_harga_beli, 0, ',', '.') ?></td>
                <td>Rp <?= number_format($each_stok['harga_jual'], 0, ',', '.') ?></td>
                <?php 
                    $amount_harga_jual = $each_stok['harga_jual'] * $each_stok['kuantitas'];
                    $total_harga_jual += $amount_harga_jual;
                ?>
                <td>Rp <?= number_format($amount_harga_jual, 0, ',', '.') ?></td>
                <td><?= $each_stok['kelompok_part'] ?></td>
                <td><?= $each_stok['rank'] ?></td>
                <td><?= $each_stok['status'] ?></td>
            </tr>
            <?php $index++; endforeach; ?>
            <tr>
                <td class='text-right' colspan='5'>Total</td>
                <td class='text-right'>Rp <?= $total_harga_beli ?></td>
                <td></td>
                <td class='text-right'>Rp <?= $total_harga_jual ?></td>
                <td class='text-right' colspan='3'></td>
            </tr>
        </table>
    </body>
</html>