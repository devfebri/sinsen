<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Laporan Hotline Order</title>
        <style>
            @media print {
                @page {
                    sheet-size: 210mm 297mm;
                    margin-left: 0cm;
                    margin-right: 0cm;
                    margin-bottom: 0cm;
                    margin-top: 0cm;
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
                    font-size: 9px;
                }

                body {
                    font-family: "Arial";
                    font-size: 9px;
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
                <td style='font-size: 15px;'>FORM MONITORING HOTLINE ORDER DEALER</td>
            </tr>
            <tr>
                <td style='font-size: 15px;'>PERIODE : <?= date('F Y', strtotime($this->input->get('end_date'))) ?></td>
            </tr>
        </table>
        <table class="table" style='margin-bottom: 20px;'>
            <tr>
                <td width='8%'>Nama Dealer</td>
                <td width='1%'>:</td>
                <td><?= $dealer->nama_dealer ?></td>
            </tr>
            <tr>
                <td width='8%'>Alamat</td>
                <td width='1%'>:</td>
                <td><?= $dealer->alamat ?></td>
            </tr>
            <tr>
                <td width='8%'>Nama PIC Dealer</td>
                <td width='1%'>:</td>
                <td><?= $dealer->pemilik ?></td>
            </tr>
            <tr>
                <td width='8%'>Nama PIC Parts</td>
                <td width='1%'>:</td>
                <td><?= $dealer->pic_parts ?></td>
            </tr>
        </table>
        <table class="table table-bordered table-font-sm">
            <tr>
                <td style='background-color: #ff9a49' rowspan='2'>No</td>
                <td style='background-color: #ff9a49' rowspan='2'>Tgl PO Dealer</td>
                <td style='background-color: #ff9a49' rowspan='2'>No. PO HTL Dealer</td>
                <td style='background-color: #ff9a49' rowspan='2'>Nama Konsumen</td>
                <td style='background-color: #ff9a49' rowspan='2'>Alamat Konsumen</td>
                <td style='background-color: #ff9a49' rowspan='2'>No. Telp Konsumen</td>
                <td style='background-color: #ff9a49' rowspan='2'>No. Rangka</td>
                <td style='background-color: #ff9a49' rowspan='2'>No. Mesin</td>
                <td style='background-color: #ff9a49' rowspan='2'>Item No.</td>
                <td style='background-color: #ff9a49' rowspan='2'>Part Number</td>
                <td style='background-color: #ff9a49' rowspan='2'>Deskripsi</td>
                <td style='background-color: #ff9a49' rowspan='2'>Order</td>
                <td style='background-color: #ff9a49' rowspan='2'>Supply</td>
                <td style='background-color: #ff9a49' rowspan='2'>BO</td>
                <td style='background-color: #ff9a49' rowspan='2'>ETA Awal</td>
                <td style='background-color: #ff9a49' rowspan='2'>ETA Revisi</td>
                <td style='background-color: #ff9a49' colspan='3'>Penerimaan Sparepart dari MD</td>
                <td style='background-color: #ff9a49' colspan='2'>Konsumen Terima barang</td>
            </tr>
            <tr>
                <td style='background-color: #ff9a49'>Tgl PS</td>
                <td style='background-color: #ff9a49'>No PS</td>
                <td style='background-color: #ff9a49'>Tgl Terima</td>
                <td style='background-color: #ff9a49'>Tgl Terima</td>
                <td style='background-color: #ff9a49'>Lead Time</td>
            </tr>
            <?php if(count($purchase_hotlines) > 0): ?>
                <?php $index_purchase = 1; foreach($purchase_hotlines as $purchase_hotline): ?>
                    <tr>
                        <td rowspan='<?= count($purchase_hotline['parts']) ?>'><?= $index_purchase ?>.</td>
                        <td rowspan='<?= count($purchase_hotline['parts']) ?>'><?= $purchase_hotline['tanggal_order'] ?></td>
                        <td rowspan='<?= count($purchase_hotline['parts']) ?>'><?= $purchase_hotline['nomor_po'] ?></td>
                        <td rowspan='<?= count($purchase_hotline['parts']) ?>'><?= $purchase_hotline['nama_customer'] ?></td>
                        <td rowspan='<?= count($purchase_hotline['parts']) ?>'><?= $purchase_hotline['alamat'] ?></td>
                        <td rowspan='<?= count($purchase_hotline['parts']) ?>'><?= $purchase_hotline['no_hp'] ?></td>
                        <td rowspan='<?= count($purchase_hotline['parts']) ?>'><?= $purchase_hotline['no_rangka'] ?></td>
                        <td rowspan='<?= count($purchase_hotline['parts']) ?>'><?= $purchase_hotline['no_mesin'] ?></td>
                        <td>1.</td>
                        <td><?= $purchase_hotline['parts'][0]['id_part'] ?></td>
                        <td><?= $purchase_hotline['parts'][0]['nama_part'] ?></td>
                        <td><?= $purchase_hotline['parts'][0]['kuantitas'] ?></td>
                        <td><?= $purchase_hotline['parts'][0]['qty_supply'] ?></td>
                        <td><?= $purchase_hotline['parts'][0]['back_order'] ?></td>
                        <td><?= $purchase_hotline['parts'][0]['eta_awal'] ?></td>
                        <td><?= $purchase_hotline['parts'][0]['eta_revisi'] ?></td>
                        <td><?= $purchase_hotline['parts'][0]['tgl_packing_sheet'] ?></td>
                        <td><?= $purchase_hotline['parts'][0]['id_packing_sheet'] ?></td>
                        <td><?= $purchase_hotline['parts'][0]['tanggal_terima_dari_md'] ?></td>
                        <td><?= $purchase_hotline['parts'][0]['tanggal_terima_konsumen'] ?></td>
                        <td><?= $purchase_hotline['parts'][0]['lead_time'] ?></td>
                    </tr>
                    <?php $index_parts = 1; foreach($purchase_hotline['parts'] as $part): ?>
                    <?php if($index_parts != 1): ?>
                    <tr>
                        <td><?= $index_parts ?>.</td>
                        <td><?= $part['id_part'] ?></td>
                        <td><?= $part['nama_part'] ?></td>
                        <td><?= $part['kuantitas'] ?></td>
                        <td><?= $part['qty_supply'] ?></td>
                        <td><?= $part['back_order'] ?></td>
                        <td><?= $part['eta_awal'] ?></td>
                        <td><?= $part['eta_revisi'] ?></td>
                        <td><?= $part['tgl_packing_sheet'] ?></td>
                        <td><?= $part['id_packing_sheet'] ?></td>
                        <td><?= $part['tanggal_terima_dari_md'] ?></td>
                        <td><?= $part['tanggal_terima_konsumen'] ?></td>
                        <td><?= $part['lead_time'] ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php $index_parts++; endforeach; ?>
                <?php $index_purchase++; endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan='21' class='text-center'>Tidak ada data</td>
            </tr>
            <?php endif; ?>
        </table>
    </body>
</html>