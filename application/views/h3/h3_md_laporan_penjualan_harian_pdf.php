<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Report Penjualan Harian</title>
    <style>
        @media print {
            @page {
                sheet-size: 330mm 210mm;
                 margin-left: 0.4cm;
                margin-right: 0.4cm;
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
            }
    
            .table-header {
                border: 1px solid black;
                font-size: 10px;
            }

            .table-item {
                border-left: 1px solid black;
                border-right: 1px solid black;
                font-size: 9px;
                padding: 0 6px;
            }

            .border-bottom {
                border-bottom: 1px solid black;
            }
        }
    </style>
</head>
<body>
    <table class="table" style='margin-bottom: 5px;'>
        <tr>
            <td class='text-bold' style='font-size: 10px;'>Tanggal Penjualan <?= Mcarbon::parse($periode_awal)->format('d-m-Y') ?> s/d <?= Mcarbon::parse($periode_akhir)->format('d-m-Y') ?><td>
        </tr>
    </table>

    <table class="table">
        <tr>
            <td class='text-bold table-header text-center' width='2%'>No.</td>
            <td class='text-bold table-header text-center' width='5%'>Tgl Faktur</td>
            <td class='text-bold table-header text-center' width='11%'>No. Faktur</td>
            <td class='text-bold table-header text-center' width='17%'>Nama Konsumen</td>
            <td class='text-bold table-header text-center' width='7%'>Kode Part</td>
            <td class='text-bold table-header text-center' width='10%'>Nama Part</td>
            <td class='text-bold table-header text-center' width='3%'>Qty</td>
            <td class='text-bold table-header text-center' width='5%'>HPP</td>
            <td class='text-bold table-header text-center' width='5%'>HET</td>
            <td class='text-bold table-header text-center' width='5%'>Diskon</td>
            <td class='text-bold table-header text-center' width='7%'>Cost</td>
            <td class='text-bold table-header text-center' width='7%'>Total</td>
            <td class='text-bold table-header text-center' widht='8%'>Kelompok Barang</td>
            <td class='text-bold table-header text-center' width='6%'>Salesman</td>
            <td class='text-bold table-header text-center' width='2%'>H1</td>
            <td class='text-bold table-header text-center' width='2%'>H2</td>
            <td class='text-bold table-header text-center' width='2%'>H3</td>
        </tr>
        <?php foreach($data as $index => $row): ?>
        <?php
            $borderBottom = count($data) == $index + 1;
        ?>
        <tr>
            <td class='text-center table-item <?= $borderBottom ? 'border-bottom' : null ?>'><?= $index + 1 ?></td>
            <td class='table-item <?= $borderBottom ? 'border-bottom' : null ?>'><?= Mcarbon::parse($row['tgl_faktur'])->format('d/m/Y') ?></td>
            <td class='table-item <?= $borderBottom ? 'border-bottom' : null ?>'><?= $row['no_faktur'] ?></td>
            <td class='table-item <?= $borderBottom ? 'border-bottom' : null ?>'><?= $row['nama_dealer'] ?></td>
            <td class='table-item <?= $borderBottom ? 'border-bottom' : null ?>'><?= $row['id_part'] ?></td>
            <td class='table-item <?= $borderBottom ? 'border-bottom' : null ?>'><?= $row['nama_part'] ?></td>
            <td class='table-item text-right <?= $borderBottom ? 'border-bottom' : null ?>'><?= number_format($row['qty_do'], 0, ',', '.') ?></td>
            <td class='table-item text-right <?= $borderBottom ? 'border-bottom' : null ?>'>Rp <?= number_format($row['hpp'], 0, ',', '.') ?></td>
            <td class='table-item text-right <?= $borderBottom ? 'border-bottom' : null ?>'>Rp <?= number_format($row['het'], 0, ',', '.') ?></td>
            <?php if($row['diskon'] != 0): ?>
            <td class='table-item text-right <?= $borderBottom ? 'border-bottom' : null ?>'>Rp <?= number_format($row['diskon'], 0, ',', '.') ?></td>
            <?php else: ?>
            <td class='table-item text-right <?= $borderBottom ? 'border-bottom' : null ?>'>-</td>
            <?php endif; ?>
            <td class='table-item text-right <?= $borderBottom ? 'border-bottom' : null ?>'>Rp <?= number_format($row['cost'], 0, ',', '.') ?></td>
            <td class='table-item text-right <?= $borderBottom ? 'border-bottom' : null ?>'>Rp <?= number_format($row['sub_total'], 0, ',', '.') ?></td>
            <td class='table-item <?= $borderBottom ? 'border-bottom' : null ?>'><?= $row['kelompok_part'] ?></td>
            <?php if($row['nama_salesman'] != null): ?>
            <td class='table-item text-right <?= $borderBottom ? 'border-bottom' : null ?>'><?= $row['nama_salesman'] ?></td>
            <?php else: ?>
            <td class='table-item text-right <?= $borderBottom ? 'border-bottom' : null ?>'>-</td>
            <?php endif; ?>
            <td class='table-item <?= $borderBottom ? 'border-bottom' : null ?>'><?= $row['h1'] == 1 ? 'Ya' : 'Tidak' ?></td>
            <td class='table-item <?= $borderBottom ? 'border-bottom' : null ?>'><?= $row['h2'] == 1 ? 'Ya' : 'Tidak' ?></td>
            <td class='table-item <?= $borderBottom ? 'border-bottom' : null ?>'><?= $row['h3'] == 1 ? 'Ya' : 'Tidak' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>