<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Voucher Pengeluaran</title>
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
    <table class="table">
        <tr>
            <td width='10%'>Tgl. Entry</td>
            <td>: <?= Mcarbon::parse($header['tanggal_transaksi'])->format('d/m/Y') ?></td>
        </tr>
        <tr>
            <td width='10%'>Bank</td>
            <td>: <?= $header['nama_account'] ?> a/c <?= $header['no_rekening_account'] ?></td>
        </tr>
        <tr>
            <td width='10%'>No. BG</td>
            <td>: <?= $header['no_giro'] != null ? $header['no_giro'] : '-' ?></td>
        </tr>
        <tr>
            <td width='10%'>Tanggal Bayar</td>
            <td>: <?= Mcarbon::parse($header['tanggal_bayar'])->format('d/m/Y') ?></td>
        </tr>
    </table>
    <table class='table' style='margin-top: 10px;'>
        <tr>
            <td width='30%' class='text-center'><?= $header['nama_penerima_dibayarkan_kepada'] ?></td>
            <td width='20%'>No. Rekening: <?= $header['no_rekening_tujuan'] ?></td>
            <td width='15%'>Bank: <?= $header['bank_tujuan'] ?></td>
            <td width='35%'>AN: <?= $header['atas_nama_tujuan'] ?></td>
        </tr>
    </table>
    <table class="table" style='margin-top: 10px;'>
        <tr>
            <td colspan='3'><?= $header['deskripsi'] ?></td>
            <td colspan='1' width='10%' class='text-right'>Rp <?= number_format($header['total_amount'], 0, ',', '.') ?></td>
        </tr>
    <?php 
    $maxRow = 15;
    $jumlahRowTerpakai = 0;
    foreach($items as $item): 
    ?>
        <tr>
            <td width='25%'><?= $item['referensi'] ?></td>
            <td width='35%'><?= $item['keterangan'] ?></td>
            <td width='15%' class='text-right'>Rp <?= number_format($item['nominal'], 0, ',', '.') ?></td>
            <td width='15%' class='text-right'></td>
        </tr>
    <?php 
    $jumlahRowTerpakai++;
    endforeach; 
    $sisaRow = $maxRow - $jumlahRowTerpakai;
    ?>
    <?php for($i = 0; $i < $sisaRow; $i++): ?>
        <tr>
            <td width='25%'>&nbsp;</td>
            <td width='35%'></td>
            <td width='15%' class='text-right'></td>
            <td width='15%'></td>
        </tr>
    <?php endfor; ?>
        <tr>
            <td colspan='3' class='text-left'>Terbilang: <?= number_to_words($header['total_amount']) ?> Rupiah</td>
            <td colspan='1' class='text-right'>Rp <?= number_format($header['total_amount'], 0, ',', '.') ?></td>
        </tr>
    </table>
    <table class="table" style='margin-top: 20px;'>
        <?php foreach($coa as $row): ?>
        <tr>
            <td width='20%'><?= $row['coa'] ?></td>
            <td>: Rp <?= number_format($row['nominal'], 0, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td width='20%'>Bank</td>
            <td>: <?= $header['nama_account'] ?> a/c <?= $header['no_rekening_account'] ?></td>
        </tr>
        <tr>
            <td width='20%'>Nominal Giro/Transfer</td>
            <td>: Rp <?= number_format($header['nominal_giro_transfer'], 0, ',', '.') ?></td>
        </tr>
    </table>
</body>
</html>