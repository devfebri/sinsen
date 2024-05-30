<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?= $outbound->id ?></title>
        <style>
            @media print {
                @page {
                    sheet-size: 210mm 297mm;
                    margin-left: 0.5cm;
                    margin-right: 0.5cm;
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
                    font-size: 8pt;
                }
            }
        </style>
    </head>
    <body>
        <table class="table table-borderedx" style='margin-bottom: 15px;'>
            <tr>
                <td style='text-align:center; font-size: 22px;'>Form Part Transfer</td>
            </tr>
        </table>
        <table class="table table-borderedx">
            <tr>
                <td width='27%'>ID Outbound Form Part Transfer</td>
                <td width='1%'>:</td>
                <td width='25%'><?= $outbound->id ?></td>
                <td width='15%'>Tanggal Request</td>
                <td width='1%'>:</td>
                <td><?= $outbound->tanggal_request ?></td>
            </tr>
            <tr>
                <td width='27%'>Tipe</td>
                <td width='1%'>:</td>
                <td width='25%'><?= $outbound->tipe ?></td>
                <td width='15%'>Tanggal In Transit</td>
                <td width='1%'>:</td>
                <td><?= $outbound->tanggal_transit ?></td>
            </tr>
            <tr>
                <td width='27%'>Gudang Asal</td>
                <td width='1%'>:</td>
                <td width='25%'><?= $outbound->gudang_asal ?></td>
                <td width='15%'>Tanggal Close</td>
                <td width='1%'>:</td>
                <td><?= $outbound->tanggal_closed ?></td>
            </tr>
            <tr>
                <td width='27%'>Alasan Transfer</td>
                <td width='1%'>:</td>
                <td width='25%'><?= $outbound->alasan ?></td>
                <td width='15%'>Status</td>
                <td width='1%'>:</td>
                <td><?= $outbound->status ?></td>
            </tr>
            </tr>
        </table>
        <style>
            tr.header td{
                border-bottom: 2px solid black;
                border-top: 2px solid black;
            }
        </style>
        <table style='margin-top: 20px; margin-bottom: 50px;' class="table table-bordered">
            <tr class='header'>
                <td width='5%'>No.</td>
                <td>Nomor Part</td>
                <td>Deskripsi Part</td>
                <td width='8%'>Qty Asal</td>
                <td width='8%'>Qty Transfer</td>
                <td>Gudang Asal</td>
                <td>Rak Asal</td>
                <td>Gudang Tujuan</td>
                <td>Rak Tujuan</td>
            </tr>
            <?php $index = 1; foreach ($parts as $part): ?>
            <tr>
                <td><?= $index ?>.</td>
                <td><?= $part->id_part ?></td>
                <td><?= $part->nama_part ?></td>
                <td><?= $part->qty_asal ?></td>
                <td><?= $part->kuantitas ?></td>
                <td><?= $part->id_gudang ?></td>
                <td><?= $part->id_rak ?></td>
                <td><?= $part->id_gudang_tujuan ?></td>
                <td><?= $part->id_rak_tujuan ?></td>
            </tr>
            <?php $index++; endforeach; ?>
        </table>
    </body>
</html>