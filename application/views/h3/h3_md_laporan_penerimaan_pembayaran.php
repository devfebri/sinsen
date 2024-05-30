<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Report Penerimaan Pembayaran</title>
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
                font-size: 8px;
                padding: 0 6px;
            }

            .border-bottom {
                border-bottom: 1px solid black;
            }
        }
    </style>
</head>
<body>
    <!-- <table class="table" style='margin-bottom: 5px;'>
        <tr>
            <td class='text-center text-bold' style='font-size: 16px;'>Report Penerimaan Pembayaran<td>
        </tr>
    </table>

    <?php if($periode_awal != null AND $periode_akhir != null): ?>
    <table class="table" style='margin-bottom: 10px;'>
        <tr>
            <td class='text-center text-bold' style='font-size: 10px;'>Periode <?= Mcarbon::parse($periode_awal)->format('d/m/Y') ?> s.d <?= Mcarbon::parse($periode_akhir)->format('d/m/Y') ?><td>
        </tr>
    </table>
    <?php endif; ?> -->

    <table class="table" style="table-layout: fixed">
        <caption style='font-size: 16px; font-weight: bold;'>Report Penerimaan Pembayaran <br> <p style='font-size: 10px; font-weight: bold;'><?php if($periode_awal != null AND $periode_akhir != null): ?> Periode <?= Mcarbon::parse($periode_awal)->format('d/m/Y') ?> s.d <?= Mcarbon::parse($periode_akhir)->format('d/m/Y') ?></p><?php endif; ?></caption>
        <thead>
            <tr>
                <td class='text-bold table-header text-center'>No.</td>
                <td class='text-bold table-header text-center' width='18%'>Nama Customer</td>
                <td class='text-bold table-header text-center' width='10%'>No. Bukti</td>
                <td class='text-bold table-header text-center' width='5%'>Tgl Bayar</td>
                <td class='text-bold table-header text-center' width='10%'>No. Faktur</td>
                <td class='text-bold table-header text-center' width='8%'>Jumlah Bayar</td>
                <td class='text-bold table-header text-center' width='8%'>Cash</td>
                <td class='text-bold table-header text-center' width='8%'>Transfer</td>
                <td class='text-bold table-header text-center' width='8%'>BG</td>
                <td class='text-bold table-header text-center' width='8%'>No. BG</td>
                <td class='text-bold table-header text-center' width='5%'>Tgl. Transfer / BG</td>
                <td class='text-bold table-header text-center'>Bank Tujuan</td>
                <td class='text-bold table-header text-center'>Keterangan</td>
            </tr>
        </thead>
        
        <?php $index = 1; foreach($data as $row): ?>
            <?php $indexItem = 1; foreach($row['items'] as $item): ?>
            <tr>
                <?php if($indexItem == 1): ?>
                <td class='text-center table-item'><?= $index ?></td>
                <td class='table-item <?= count($data) == $index AND count($data['items']) == 1 ? 'border-bottom' : null ?>'><?= $row['nama_dealer'] ?></td>
                <td class='table-item <?= count($data) == $index AND count($data['items']) == 1 ? 'border-bottom' : null ?>'><?= $row['id_penerimaan_pembayaran'] ?></td>
                <?php else: ?>
                <td class='table-item <?= count($data) == $index ? 'border-bottom' : null ?>'></td>
                <td class='table-item <?= count($data) == $index ? 'border-bottom' : null ?>'></td>
                <td class='table-item <?= count($data) == $index ? 'border-bottom' : null ?>'></td>
                <?php endif; ?>
                <td class='table-item <?= (count($data) == $index AND count($row['items']) == $indexItem) ? 'border-bottom' : null ?>'><?= Mcarbon::parse($row['created_at'])->format('d/m/Y') ?></td>
                <td class='table-item <?= (count($data) == $index AND count($row['items']) == $indexItem) ? 'border-bottom' : null ?>'><?= $item['referensi'] ?></td>
                <td class='text-right table-item <?= (count($data) == $index AND count($row['items']) == $indexItem) ? 'border-bottom' : null ?>'>Rp <?= number_format($row['jumlah_pembayaran'], 0, ',', '.') ?></td>
                <?php if($item['nominal_cash'] != 0): ?>
                <td class='text-right table-item <?= (count($data) == $index AND count($row['items']) == $indexItem) ? 'border-bottom' : null ?>'>Rp <?= number_format($item['nominal_cash'], 0, ',', '.') ?></td>
                <?php else: ?>
                <td class='text-right table-item <?= (count($data) == $index AND count($row['items']) == $indexItem) ? 'border-bottom' : null ?>'>-</td>
                <?php endif; ?>
                <?php if($item['nominal_transfer'] != 0): ?>
                <td class='text-right table-item <?= (count($data) == $index AND count($row['items']) == $indexItem) ? 'border-bottom' : null ?>'>Rp <?= number_format($item['nominal_transfer'], 0, ',', '.') ?></td>
                <?php else: ?>
                <td class='text-right table-item <?= (count($data) == $index AND count($row['items']) == $indexItem) ? 'border-bottom' : null ?>'>-</td>
                <?php endif; ?>
                <?php if($item['nominal_bg'] != 0): ?>
                <td class='text-right table-item <?= (count($data) == $index AND count($row['items']) == $indexItem) ? 'border-bottom' : null ?>'>Rp <?= number_format($item['nominal_bg'], 0, ',', '.') ?></td>
                <?php else: ?>
                <td class='text-right table-item <?= (count($data) == $index AND count($row['items']) == $indexItem) ? 'border-bottom' : null ?>'>-</td>
                <?php endif; ?>
                <td class='table-item <?= (count($data) == $index AND count($row['items']) == $indexItem) ? 'border-bottom' : null ?>'><?= $row['nomor_bg'] != null ? $row['nomor_bg'] : '-' ?></td>
                <td class='table-item <?= (count($data) == $index AND count($row['items']) == $indexItem) ? 'border-bottom' : null ?>'><?= $row['tgl_transfer_atau_bg'] != null ? Mcarbon::parse($row['tgl_transfer_atau_bg'])->format('d/m/Y') : '-' ?></td>
                <td class='table-item <?= (count($data) == $index AND count($row['items']) == $indexItem) ? 'border-bottom' : null ?>'><?= $row['bank_tujuan'] != null ? $row['bank_tujuan'] : '' ?></td>
                <td class='table-item <?= (count($data) == $index AND count($row['items']) == $indexItem) ? 'border-bottom' : null ?>'><?= $row['keterangan'] != null ? $row['keterangan'] : '' ?></td>
            </tr>
            <?php $indexItem++; endforeach; ?>
        <?php $index++; endforeach; ?>
    </table>
</body>
</html>