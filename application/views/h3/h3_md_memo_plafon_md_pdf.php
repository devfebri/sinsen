<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Memo Plafon</title>
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
                border: 1px solid black;
                font-size: 10px;
            }

            .cell-spacing {
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
            <td class='text-bold text-center' style='font-size: 18px; border-top: 1px solid black; border-bottom: 1px solid black;'>MEMO INTERN<td>
        </tr>
    </table>
    <table class="table" style='margin-bottom: 5px;'>
        <tr>
            <td style='font-size: 12px;' width='20%'>Tanggal</td>
            <td style='font-size: 12px;' width='80%'>: <?= Mcarbon::now()->format('d') ?> <?= lang('month_' . Mcarbon::now()->format('n')) ?> <?= Mcarbon::now()->format('Y') ?><td>
        </tr>
        <tr>
            <td style='font-size: 12px;' width='20%'>Kepada Yth</td>
            <td style='font-size: 12px;' width='80%'>: Bp. Drs. Tony Attan, SH</td>
        </tr>
        <tr>
            <td style='font-size: 12px;' width='20%'>Perihal</td>
            <td style='font-size: 12px;' width='80%'>: Pengajuan Penambahan/Sementara Plafon Sparepart</td>
        </tr>
    </table>
    <table class="table table-bordered">
        <tr>
            <td class='text-bold table-header text-center' rowspan='2' width='3%'>No.</td>
            <td class='text-bold table-header text-center' rowspan='2' width='9%'>Nama Toko</td>
            <td class='text-bold table-header text-center' rowspan='2' width='4%'>Kode Customer</td>
            <td class='text-bold table-header text-center' rowspan='2' width='8%'>Alamat</td>
            <td class='text-bold table-header text-center' rowspan='2' width='6%'>Status Toko</td>
            <td class='text-bold table-header text-center' rowspan='2' width='6%'>Plafon Awal</td>
            <td class='text-bold table-header text-center' rowspan='2' width='6%'>Sisa Plafon</td>
            <td class='text-bold table-header text-center' colspan='2'>Rincian Nilai Tagihan Faktur Sparepart & AHM Oil</td>
            <td class='text-bold table-header text-center' rowspan='2' width='6%'>Keterangan Pembayaran</td>
            <td class='text-bold table-header text-center' colspan='2'>Nilai PO Sparepart & AHM Oil</td>
            <td class='text-bold table-header text-center' rowspan='2' width='6%'>Nilai Plafon yang diajukan marketing</td>
            <td class='text-bold table-header text-center' rowspan='2' width='6%'>Nilai Plafon yang disetujui Finance</td>
            <td class='text-bold table-header text-center' rowspan='2' width='6%'>Keterangan Plafon</td>
            <td class='text-bold table-header text-center' rowspan='2' width='6%'>Nilai Plafon yang disetujui Pimpinan</td>
        </tr>
        <tr>
            <td class='text-bold table-header text-center' width='6%'>Tgl Jatuh Tempo</td>
            <td class='text-bold table-header text-center' width='6%'>Nilai Uang</td>
            <td class='text-bold table-header text-center' width='6%'>PO Sparepart</td>
            <td class='text-bold table-header text-center' width='6%'>PO AHM Oil</td>
        </tr>
        <?php foreach($data as $index => $plafon): ?>
            <?php if(count($plafon['faktur']) > 0): ?>
                <?php foreach($plafon['faktur'] as $indexFaktur => $faktur): ?>
                    <tr>
                        <?php if($indexFaktur == 0): ?>
                        <td class='table-item cell-spacing' rowspan='<?= $plafon['rowspan_faktur'] ?>'><?= $index + 1 ?></td>
                        <td class='table-item cell-spacing' rowspan='<?= $plafon['rowspan_faktur'] ?>'><?= $plafon['nama_dealer'] ?></td>
                        <td class='table-item cell-spacing' rowspan='<?= $plafon['rowspan_faktur'] ?>'><?= $plafon['kode_dealer_md'] ?></td>
                        <td class='table-item cell-spacing' rowspan='<?= $plafon['rowspan_faktur'] ?>'><?= $plafon['alamat'] ?></td>
                        <?php 
                            $status_toko = sprintf('%s RUKO ', $plafon['jumlah_ruko']);
                            $status_toko .= $plafon['gudang_sendiri'] == 1 ? 'MILIK SENDIRI' : 'BUKAN MILIK SENDIRI';
                        ?>
                        <td class='table-item cell-spacing' rowspan='<?= $plafon['rowspan_faktur'] ?>'><?= $status_toko ?></td>
                        <td class='table-item text-right cell-spacing' rowspan='<?= $plafon['rowspan_faktur'] ?>'><?= number_format($plafon['plafon_awal'], 0, ',', '.') ?></td>
                        <td class='table-item text-right cell-spacing' rowspan='<?= $plafon['rowspan_faktur'] ?>'><?= number_format($plafon['sisa_plafon'], 0, ',', '.') ?></td>
                        <?php endif; ?>
                        <td class="table-item cell-spacing text-center"><?= Mcarbon::parse($faktur['tgl_jatuh_tempo'])->format('d/m/Y') ?></td>
                        <td class="table-item text-right cell-spacing"><?= number_format($faktur['nilai_faktur'], 0, ',', '.') ?></td>
                        <?php if(count($faktur['rincian_pembayaran']) > 0): ?>
                        <?php $keterangan_bg = ''; foreach($faktur['rincian_pembayaran'] as $rincian_pembayaran): ?>
                        <?php
                            $keterangan_bg .= sprintf('No. BG : %s ', $rincian_pembayaran['nomor_bg']);
                            $keterangan_bg .= sprintf('<br>Tgl Cair : %s <br><br>', Mcarbon::parse($rincian_pembayaran['tanggal_jatuh_tempo_bg'])->format('d/m/Y'));    
                        ?>
                        <?php endforeach ?>
                        <?php else: ?>
                        <?php $keterangan_bg = '-'; ?>
                        <?php endif; ?>
                        <td class="table-item cell-spacing text-center"><?= $keterangan_bg ?></td>
                        <?php if($indexFaktur == 0): ?>
                        <td class='table-item text-right cell-spacing' rowspan='<?= $plafon['rowspan_faktur'] ?>'><?= number_format($plafon['nilai_po_part'], 0, ',', '.') ?></td>
                        <td class='table-item text-right cell-spacing' rowspan='<?= $plafon['rowspan_faktur'] ?>'><?= number_format($plafon['nilai_po_oli'], 0, ',', '.') ?></td>
                        <td class='table-item text-right cell-spacing' rowspan='<?= $plafon['rowspan_faktur'] ?>'><?= number_format($plafon['nilai_penambahan_plafon'], 0, ',', '.') ?></td>
                        <td class='table-item text-right cell-spacing' rowspan='<?= $plafon['rowspan_faktur'] ?>'><?= number_format($plafon['nilai_penambahan_plafon_finance'], 0, ',', '.') ?></td>
                        <td class='table-item cell-spacing' rowspan='<?= $plafon['rowspan_faktur'] ?>'><?= $plafon['keterangan_pengajuan'] ?></td>
                        <td class='table-item text-right cell-spacing' rowspan='<?= $plafon['rowspan_faktur'] ?>'><?= number_format($plafon['nilai_penambahan_plafon_pimpinan'], 0, ',', '.') ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td class='table-item cell-spacing'><?= $index + 1 ?></td>
                    <td class='table-item cell-spacing'><?= $plafon['nama_dealer'] ?></td>
                    <td class='table-item cell-spacing'><?= $plafon['kode_dealer_md'] ?></td>
                    <td class='table-item cell-spacing'><?= $plafon['alamat'] ?></td>
                    <?php 
                        $status_toko = sprintf('%s RUKO ', $plafon['jumlah_ruko']);
                        $status_toko .= $plafon['gudang_sendiri'] == 1 ? 'MILIK SENDIRI' : 'BUKAN MILIK SENDIRI';
                    ?>
                    <td class='table-item cell-spacing'><?= $status_toko ?></td>
                    <td class='table-item text-right cell-spacing'><?= number_format($plafon['plafon_awal'], 0, ',', '.') ?></td>
                    <td class='table-item text-right cell-spacing'><?= number_format($plafon['sisa_plafon'], 0, ',', '.') ?></td>
                    <td class="table-item cell-spacing text-center">-</td>
                    <td class="table-item text-right cell-spacing">-</td>
                    <td class="table-item cell-spacing text-center">-</td>
                    <td class='table-item text-right cell-spacing'><?= number_format($plafon['nilai_po_part'], 0, ',', '.') ?></td>
                    <td class='table-item text-right cell-spacing'><?= number_format($plafon['nilai_po_oli'], 0, ',', '.') ?></td>
                    <td class='table-item text-right cell-spacing'><?= number_format($plafon['nilai_penambahan_plafon'], 0, ',', '.') ?></td>
                    <td class='table-item text-right cell-spacing'><?= number_format($plafon['nilai_penambahan_plafon_finance'], 0, ',', '.') ?></td>
                    <td class='table-item cell-spacing'><?= $plafon['keterangan_pengajuan'] ?></td>
                    <td class='table-item text-right cell-spacing'><?= number_format($plafon['nilai_penambahan_plafon_pimpinan'], 0, ',', '.') ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
    <style>
        table.tanda-tangan{
            margin-top: 10px;
        }

        table.tanda-tangan tr td{
            font-size: 10px;
        }

        td.padding-b60{
            padding-bottom: 60px;
        }
    </style>
    <table class="table tanda-tangan">
        <tr>
            <td class='padding-b60' width='25%'>Dibuat Oleh,</td>
            <td class='padding-b60' width='25%'>Pemohon,</td>
            <td class='padding-b60 text-center' width='25%' colspan='2'>Diketahui Oleh,</td>
            <td class='padding-b60 text-center' width='25%'>Disetujui Oleh,</td>
        </tr>
        <tr>
            <td width='20%'><?= $this->input->get('admin') ?></td>
            <td width='20%'><?= $this->input->get('marketing') ?></td>
            <td class='text-center' width='20%'><?= $this->input->get('part_manager') ?></td>
            <td class='text-center' width='20%'><?= $this->input->get('finance_head') ?></td>
            <td class='text-center' width='20%'><?= $this->input->get('pimpinan') ?></td>
        </tr>
        <tr>
            <td width='20%'>Admin</td>
            <td width='20%'>Marketing</td>
            <td class='text-center' width='20%'>Part Manager</td>
            <td class='text-center' width='20%'>Finance Head</td>
            <td class='text-center' width='20%'>Pimpinan</td>
        </tr>
    </table>
</body>
</html>