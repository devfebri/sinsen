<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Laporan Penerimaan By Packing Sheet</title>
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
                font-size: 10px;
                padding: 0 6px;
            }

            .border-bottom {
                border-bottom: 1px solid black !important;
            }
        }
    </style>
</head>
<body>
    <table class="table" style='margin-bottom: 5px;'>
        <tr>
            <td class='text-center text-bold' style='font-size: 16px;'>Laporan Penerimaan Barang<td>
        </tr>
    </table>
    <?php if($periode_awal != null AND $periode_akhir != null): ?>
    <table class="table" style='margin-bottom: 10px;'>
        <tr>
            <td class='text-left text-bold' style='font-size: 10px;'>Periode <?= Mcarbon::parse($periode_awal)->format('d/m/Y') ?> s.d <?= Mcarbon::parse($periode_akhir)->format('d/m/Y') ?><td>
        </tr>
    </table>
    <?php endif; ?>
    <table class="table">
        <tr>
            <td class='text-bold table-header text-center' width='3%'>No.</td>
            <td class='text-bold table-header text-center' width='6%'>Tanggal Penerimaan</td>
            <td class='text-bold table-header text-center' width='8%'>No. Penerimaan</td>
            <td class='text-bold table-header text-center' width='6%'>Tgl. PS</td>
            <td class='text-bold table-header text-center' width='10%'>No. Packing Sheet</td>
            <td class='text-bold table-header text-center'>No. Karton</td>
            <td class='text-bold table-header text-center'>No. PO MD</td>
            <td class='text-bold table-header text-center'>Kode Part</td>
            <td class='text-bold table-header text-center'>Nama Part</td>
            <td class='text-bold table-header text-center'>Serial Number</td>
            <td class='text-bold table-header text-center'>Qty</td>
            <td class='text-bold table-header text-center'>Lokasi</td>
            <td class='text-bold table-header text-center' width='5%'>Kelompok Produk</td>
        </tr>
        <?php foreach($data as $index => $row): ?>
            <?php foreach($row['items'] as $itemIndex => $item): ?>
            <?php 
                $borderBottom = ( (count($data) == $index + 1) AND (count($row['items']) == $itemIndex + 1) );
            ?>
            <tr>
                <?php if($itemIndex == 0): ?>
                <td class='table-item <?= $borderBottom ? 'border-bottom': null ?>'><?= $index + 1 ?></td>
                <td class='table-item <?= $borderBottom ? 'border-bottom': null ?>'><?= Mcarbon::parse($row['tanggal_penerimaan'])->format('d-m-Y') ?></td>
                <td class='table-item <?= $borderBottom ? 'border-bottom': null ?>'><?= $row['no_penerimaan_barang'] ?></td>
                <td class='table-item <?= $borderBottom ? 'border-bottom': null ?>'><?= Mcarbon::parse($row['packing_sheet_date'])->format('d-m-Y') ?></td>
                <td class='table-item <?= $borderBottom ? 'border-bottom': null ?>'><?= $row['packing_sheet_number'] ?></td>
                <?php else: ?>
                <td class='table-item <?= $borderBottom ? 'border-bottom': null ?>'></td>
                <td class='table-item <?= $borderBottom ? 'border-bottom': null ?>'></td>
                <td class='table-item <?= $borderBottom ? 'border-bottom': null ?>'></td>
                <td class='table-item <?= $borderBottom ? 'border-bottom': null ?>'></td>
                <td class='table-item <?= $borderBottom ? 'border-bottom': null ?>'></td>
                <?php endif; ?>
                <td class='table-item <?= $borderBottom ? 'border-bottom': null ?>'><?= $item['nomor_karton'] ?></td>
                <td class='table-item <?= $borderBottom ? 'border-bottom': null ?>'><?= $item['no_po'] ?></td>
                <td class='table-item <?= $borderBottom ? 'border-bottom': null ?>'><?= $item['id_part'] ?></td>
                <td class='table-item <?= $borderBottom ? 'border-bottom': null ?>'><?= $item['nama_part'] ?></td>
                <td class='table-item <?= $borderBottom ? 'border-bottom': null ?>'><?= $item['serial_number'] ?></td>
                <td class='table-item <?= $borderBottom ? 'border-bottom': null ?>'><?= $item['qty_diterima'] ?></td>
                <td class='table-item <?= $borderBottom ? 'border-bottom': null ?>'><?= $item['kode_lokasi_rak'] ?></td>
                <td class='table-item <?= $borderBottom ? 'border-bottom': null ?>'><?= $item['kelompok_part'] ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </table>
</body>
</html>