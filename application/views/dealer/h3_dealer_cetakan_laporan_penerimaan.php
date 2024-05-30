<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Laporan Penerimaan <?= $start_date ?> s/d <?= $end_date ?></title>
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
                    font-size: 11pt;
                }
            }
        </style>
    </head>
    <style>
        .header-laporan-penerimaan{
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .periode-laporan-penerimaan{
            text-align: center;
            font-size: 12px;
        }

        td.tanggal{
            font-size: 12px;
        }

        td.tipe{
            font-size: 12px;
            text-align: right;
        }
    </style>
    <body>
        <table class="table table-bordereds">
            <tr>
                <td class='header-laporan-penerimaan'>Laporan Penerimaan</td>
            </tr>
            <tr>
                <td class='periode-laporan-penerimaan'>Tgl. <?= $start_date ?> s/d <?= $end_date ?></td>
            </tr>
        </table>
        <table style='margin-top: 10px;' class="table table-borderedx">
            <tr>
                <td width='50%' class='tanggal'>Tanggal: <?= $start_date ?></td>
                <td width='50%' class='tipe'>Type: <?= $this->input->get('type') ?></td>
            </tr>
        </table>
        <style>
            table.penerimaan{
                font-size: 10px;
            }

            table.penerimaan td {
                border: 1px solid black;
                padding: 0 5px;
            }

            tr.header td{
                border-bottom: 2px solid black;
                border-top: 2px solid black;
            }
        </style>
        <table style='margin-top: 5px; margin-bottom: 50px;' class="table table-borderedx penerimaan">
            <tr class='header'>
                <td width='3%'>No.</td>
                <td>No. Penerimaan</td>
                <td>No. PS</td>
                <td width='10%'>No. Karton</td>
                <td width='15%'>No. Part</td>
                <td width='15%'>Deskripsi Part</td>
                <td width='5%'>Qty</td>
                <td>Lokasi Rak</td>
                <?php if($this->input->get('type') == 'Bad'): ?>
                <td width='20%'>Keterangan</td>
                <?php endif; ?>
            </tr>
            <?php $index = 1; foreach($penerimaan as $each): ?>
            <tr>
                <td><?= $index ?></td>
                <td><?= $each->nomor_penerimaan ?></td>
                <td><?= $each->nomor_packing_sheet ?></td>
                <td><?= $each->nomor_karton ?></td>
                <td><?= $each->id_part ?></td>
                <td><?= $each->nama_part ?></td>
                <td><?= $each->qty ?></td>
                <td><?= $each->id_gudang ?> - <?= $each->id_rak ?></td>
                <?php if($this->input->get('type') == 'Bad'): ?>
                <td><?= $each->keterangan ?></td>
                <?php endif; ?>
            </tr>
            <?php $index++; endforeach; ?>
        </table>
    </body>
</html>