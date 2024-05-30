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
                <td width='100%' class='text-right'><?= date('d/m/Y', strtotime($this->input->get('start_date'))) ?> - <?= date('d/m/Y', strtotime($this->input->get('end_date'))) ?></td>
            </tr>
            <tr>
                <td class='text-center' style='font-size: 20px;'>Laporan Pendapatan Bengkel</td>
            </tr>
        </table>
        <table class="table table-bordered table-font-sm">
            <tr>
                <td rowspan='2' class='text-center' width='5%'>No.</td>
                <td rowspan='2'>Bulan</td>
                <td colspan='4' class='text-center'>Pendapatan AHASS</td>
            </tr>
            <tr>
                <td>UE</td>
                <td>Parts</td>
                <td>Oil</td>
                <td>Jasa</td>
            </tr>
            <?php if(count($pendapatan) > 0): ?>
            <?php $index = 1; foreach($pendapatan as $row): ?>
            <tr>
                <td><?= $index ?>.</td>
                <td><?= $row['label_month'] ?></td>
                <td><?= $row['ue'] ?></td>
                <td>Rp <?= number_format($row['parts'], 0, ",", ".") ?></td>
                <td>Rp <?= number_format($row['oil'], 0, ",", ".") ?></td>
                <td>Rp <?= number_format($row['jasa'], 0, ",", ".") ?></td>
            </tr> 
            <?php $index++; endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan='6' class='text-center'>Tidak ada data</td>
            </tr> 
            <?php endif; ?>
        </table>
    </body>
</html>