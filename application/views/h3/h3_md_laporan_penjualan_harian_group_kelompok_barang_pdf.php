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
    <table class="table">
        
        <tr>
            <td class='text-bold table-header text-center' width='15%'>No. Part</td>
            <td class='text-bold table-header text-center' width='20%'>Deksripsi Part</td>
            <td class='text-bold table-header text-right' width='10%'>Qty</td>
            <td class='text-bold table-header text-right' width='15%'>Harga Pokok</td>
            <td class='text-bold table-header text-right' width='10%'>Harga Jual</td>
            <td class='text-bold table-header text-right' width='10%'>Disc. Jual</td>
            <td class='text-bold table-header text-right' width='10%'>Total (Jual)</td>
            <td class='text-bold table-header text-right' width='10%'>Total (Pokok)</td>
        </tr>
        <?php $grandTotalJual = 0; ?>
        <?php $grandTotalPokok = 0; ?>
        <?php foreach($data as $indexGroupKelompokBarang => $groupKelompokBarang): ?>
        <tr>
            <td colspan='8' class='table-item text-bold'>Kelompok : <?= $groupKelompokBarang['kelompok_part'] ?></td>
        </tr>
            <?php foreach($groupKelompokBarang['penjualan'] as $index => $penjualan): ?>
                <tr>
                    <td class='table-item'><?= $penjualan['id_part'] ?></td>
                    <td class='table-item'><?= $penjualan['nama_part'] ?></td>
                    <td class='table-item text-right'><?= number_format($penjualan['qty'], 0, ',', '.') ?></td>
                    <td class='table-item text-right'><?= number_format($penjualan['hpp'], 0, ',', '.') ?></td>
                    <td class='table-item text-right'><?= number_format($penjualan['het'], 0, ',', '.') ?></td>
                    <?php if($penjualan['diskon'] != 0): ?>
                    <td class='table-item text-right'><?= number_format($penjualan['diskon'], 0, ',', '.') ?></td>
                    <?php else: ?>
                    <td class='table-item text-right'>-</td>
                    <?php endif; ?>
                    <td class='table-item text-right'><?= number_format($penjualan['total_jual'], 0, ',', '.') ?></td>
                    <td class='table-item text-right'><?= number_format($penjualan['total_pokok'], 0, ',', '.') ?></td>
                </tr>
                <?php 

                    $partTerakhir = count($groupKelompokBarang['penjualan']) == $index + 1; 
                ?>
                <?php if($partTerakhir): ?>
                    <tr>
                        <td colspan='6' class='border-top border-bottom' style='padding-bottom: 10px;'></td>
                        <td class='table-item border-top border-bottom border-top text-right text-bold' style='padding-bottom: 10px;'><?= number_format($groupKelompokBarang['total_jual_per_kelompok_barang'], 0, ',', '.') ?></td>
                        <td class='table-item border-top border-bottom border-top text-right text-bold' style='padding-bottom: 10px;'><?= number_format($groupKelompokBarang['total_pokok_per_kelompok_barang'], 0, ',', '.') ?></td>
                    </tr>
                <?php endif; ?>
                
            <?php endforeach; ?>
            <?php 
            $grandTotalJual += floatval($groupKelompokBarang['total_jual_per_kelompok_barang']);
            $grandTotalPokok += floatval($groupKelompokBarang['total_pokok_per_kelompok_barang']);
            $menampilkanGrandTotal = count($data) == $indexGroupKelompokBarang + 1;
            if($menampilkanGrandTotal): 
            ?>
            <tr>
                <td colspan='6' class='table-item text-bold border-top border-bottom text-italic' style='padding: 5px 0;'>Grand Total</td>
                <td class='text-right table-item text-bold border-top border-bottom' style='padding: 5px 0;'><?= number_format($grandTotalJual, 0, ',', '.') ?></td>
                <td class='text-right table-item text-bold border-top border-bottom' style='padding: 5px 0;'><?= number_format($grandTotalPokok, 0, ',', '.') ?></td>
            </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
</body>
</html>