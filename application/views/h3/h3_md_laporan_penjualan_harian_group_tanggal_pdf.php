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

            .text-italic{
                font-style: italic;
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
                border-top: 1px solid black;
                border-bottom: 1px solid black;
                font-size: 10px;
            }

            .table-item {
                font-size: 10px;
                padding: 0 6px;
            }

            .border-top {
                border-top: 1px solid black;
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
            <td class='text-bold text-center' style='font-size: 14px;'>Laporan Penjualan<td>
        </tr>
        <tr>
            <td class='text-bold text-center' style='font-size: 10px;'>Tanggal Penjualan <?= Mcarbon::parse($periode_awal)->format('d-m-Y') ?> s/d <?= Mcarbon::parse($periode_akhir)->format('d-m-Y') ?><td>
        </tr>
    </table>
    <?php $grandTotal = 0; ?>
    <?php foreach($data as $indexGroupTanggal => $groupTanggal): ?>
    <?php $marginPerTanggal = (count($data) != $indexGroupTanggal + 1) ? 'margin-bottom: 20px;' : null ?>
    <table class="table" style='<?= $marginPerTanggal ?>'>
        <tr>
            <td colspan='8' class='table-item text-bold'>Tgl. Penjualan : <?= Mcarbon::parse($groupTanggal['tanggal'])->format('d/m/Y') ?></td>
        </tr>
        <thead>
            <tr>
                <td class='text-bold table-header text-center' width='15%'>No. Faktur</td>
                <td class='text-bold table-header text-center' width='20%'>Nama Customer</td>
                <td class='text-bold table-header text-center' width='10%'>No. Part</td>
                <td class='text-bold table-header text-center' width='15%'>Nama Part</td>
                <td class='text-bold table-header text-right' width='10%'>Qty</td>
                <td class='text-bold table-header text-right' width='10%'>HET</td>
                <td class='text-bold table-header text-right' width='10%'>Diskon</td>
                <td class='text-bold table-header text-right' width='10%'>Total Harga</td>
            </tr>
        </thead>
        <?php foreach($groupTanggal['penjualan'] as $index => $penjualan): ?>
            <?php foreach($penjualan['parts'] as $indexPart => $part): ?>
            <tr>
                <?php if($indexPart == 0): ?>
                <td class='table-item'><?= $penjualan['no_faktur'] ?></td>
                <td class='table-item'><?= $penjualan['nama_dealer'] ?></td>
                <?php else: ?>
                <td class="table-item"></td>
                <td class="table-item"></td>
                <?php endif; ?>
                <td class='table-item'><?= $part['id_part'] ?></td>
                <td class='table-item'><?= $part['nama_part'] ?></td>
                <td class='table-item text-right'><?= number_format($part['qty'], 0, ',', '.') ?></td>
                <td class='table-item text-right'><?= number_format($part['het'], 0, ',', '.') ?></td>
                <?php if($part['diskon'] != 0): ?>
                <td class='table-item text-right'><?= number_format($part['diskon'], 0, ',', '.') ?></td>
                <?php else: ?>
                <td class='table-item text-right'>-</td>
                <?php endif; ?>
                <td class='table-item text-right'><?= number_format($part['total_harga'], 0, ',', '.') ?></td>
            </tr>
            <?php 

                $partTerakhir = count($penjualan['parts']) == $indexPart + 1; 
            ?>
            <?php if($partTerakhir): ?>
                <tr>
                    <td colspan='7' style='padding-bottom: 10px;'></td>
                    <td class='table-item border-top text-right text-bold' style='padding-bottom: 10px;'><?= number_format($penjualan['total_per_faktur'], 0, ',', '.') ?></td>
                </tr>
            <?php endif; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <tr>
            <td colspan='7' class='table-item text-bold border-top border-bottom' style='padding: 5px 0;'>Sub total tanggal <?= Mcarbon::parse($groupTanggal['tanggal'])->format('d/m/Y') ?>:</td>
            <td class='text-right table-item text-bold border-top border-bottom' style='padding: 5px 0;'><?= number_format($groupTanggal['total_per_tanggal'], 0, ',', '.') ?></td>
        </tr>
        <?php 
        $grandTotal += floatval($groupTanggal['total_per_tanggal']);
        $menampilkanGrandTotal = count($data) == $indexGroupTanggal + 1;
        if($menampilkanGrandTotal): 
        ?>
        <tr>
            <td colspan='7' class='table-item text-bold border-top border-bottom text-italic' style='padding: 5px 0;'>Grand Total</td>
            <td class='text-right table-item text-bold border-top border-bottom' style='padding: 5px 0;'><?= number_format($grandTotal, 0, ',', '.') ?></td>
        </tr>
        <?php endif; ?>
    </table>
    <?php endforeach; ?>
</body>
</html>