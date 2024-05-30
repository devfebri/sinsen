<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?= $outbound->id_surat_jalan ?></title>
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
                body {
                    font-family: "Arial";
                    font-size: 9pt;
                }
            }
        </style>
    </head>
    <body>
        <table class="table table-borderedx" style='margin-bottom: 15px;'>
            <tr>
                <td style='text-align:center; font-size: 22px;'>Report Pengembalian Sparepart Event</td>
            </tr>
        </table>
        <table class="table table-borderedx">
            <tr>
                <td width='30%'>No. Inbound Form</td>
                <td width='1%'>:</td>
                <td width='22%'><?= $inbound->nomor_inbound ?></td>
                <td width='15%'>Tanggal Transaksi</td>
                <td width='1%'>:</td>
                <td><?= $inbound->tanggal_transaksi ?></td>
            </tr>
            <tr>
                <td width='30%'>Surat Jalan</td>
                <td width='1%'>:</td>
                <td width='22%'><?= $inbound->surat_jalan ?></td>
                <td width='15%'>No. ID Event</td>
                <td width='1%'>:</td>
                <td><?= $inbound->id_event ?></td>
            </tr>
            <tr>
                <td width='30%'>No. Outbound for Fulfillment</td>
                <td width='1%'>:</td>
                <td colspan='4'><?= $inbound->nomor_outbound ?></td>
            </tr>
        </table>
        <style>
            tr.header td{
                border-bottom: 2px solid black;
                border-top: 2px solid black;
            }
        </style>
        <table style='margin-top: 20px; margin-bottom: 50px;' class="table table-borderedx">
            <tr class='header'>
                <td width='5%'>No.</td>
                <td width='20%'>Nomor Part</td>
                <td width='20%'>Deskripsi Part</td>
                <td>Qty Book</td>
                <td>Qty Penjualan</td>
                <td>Qty Kerusakan</td>
                <td>Qty Kehilangan</td>
                <td>Qty Tertukar</td>
                <td>Qty Others</td>
                <td>Qty Return</td>
            </tr>
            <?php $index = 1; foreach ($parts as $part): ?>
            <tr>
                <td><?= $index ?>.</td>
                <td><?= $part->id_part ?></td>
                <td><?= $part->nama_part ?></td>
                <td><?= $part->qty_book ?></td>
                <td><?= $part->qty_penjualan ?></td>
                <td><?= $part->qty_kerusakan ?></td>
                <td><?= $part->qty_kehilangan ?></td>
                <td><?= $part->qty_tertukar ?></td>
                <td><?= $part->qty_others ?></td>
                <td><?= $part->qty_return ?></td>
            </tr>
            <?php $index++; endforeach; ?>
        </table>
    </body>
</html>