<?php
if ($set == 'print') { ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Cetak</title>
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

    <body>
        <table class="table table-borderedx">
            <tr>
                <td width="100%" align="center" colspan="5"><b>Cetak LBPC (Lembaran Biaya Penggantian Claim)</b><br>&nbsp;</td>
            </tr>
            <tr>
                <td width="20%">ID LBPC</td>
                <td>: <?= $row->no_lbpc ?></td>
                <td></td>
                <td width="20%">Tanggal</td>
                <td>: <?= $row->tgl_lbpc ?></td>
            </tr>
        </table>
    </body>

    </html>
<?php } ?>
<?php if ($set == 'download_excel') :
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=LBPC (Lembaran Biaya Penggantian Claim).xls");
    header("Pragma: no-cache");
    header("Expires: 0");
?>
    <div align="center">LBPC (Lembaran Biaya Penggantian Claim)</div>
    <table border="1">
        <tr>
            <td>No</td>
            <td>ID LBPC</td>
            <td>No Registrasi</td>
            <td>Tgl Pengajuan</td>
            <td>Kode AHASS</td>
            <td>Nama AHASS</td>
            <td>No Mesin</td>
            <td>No Rangka</td>
            <td>Tgl Pembelian</td>
            <td>Tgl Kerusakan</td>
        </tr>
        <?php $no = 1;
        foreach ($result->result() as $rs) : ?>
            <tr>
                <td><?= $no ?></td>
                <td><?= $rs->no_lbpc ?></td>
                <td><?= $rs->no_registrasi ?></td>
                <td><?= $rs->tgl_pengajuan ?></td>
                <td><?= $rs->kode_dealer_md ?></td>
                <td><?= $rs->nama_dealer ?></td>
                <td><?= $rs->no_mesin ?></td>
                <td><?= $rs->no_rangka ?></td>
                <td><?= $rs->tgl_pembelian ?></td>
                <td><?= $rs->tgl_kerusakan ?></td>
            </tr>
        <?php $no++;
        endforeach ?>
    </table>
<?php endif ?>