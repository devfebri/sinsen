<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Cetak</title>
    <style>
        @media print {
            @page {
                sheet-size: 210mm 297mm;
                margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 1cm;
                margin-top: 1cm;
            }
            .text-center{text-align: center;}
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
            body{
                font-family: "Arial";
                font-size: 11pt;
            }
        }
    </style>
</head>
<body>

<?php if ($cetak=='cetak_sj'){ ?>
<style>
    @media print {
            @page {
                sheet-size: 210mm 297mm;
                margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 1cm;
                margin-top: 1cm;
            }
    }
</style>
    <p style="text-align: center;font-size: 13pt;font-weight: bold;">SURAT JALAN</p>
    <table class="table">
        <tr>
            <td style="width: 20%">No PO Aksesoris</td><td>: <?= $po->no_po_aksesoris ?></td>
        </tr>
         <tr>
            <td>Tgl PO Aksesoris</td><td>: <?= $po->tgl_po ?></td>
        </tr>
         <tr>
            <td>No Surat Jalan</td><td>: <?= $po->no_surat_jalan ?></td>
        </tr>
        <tr>
            <td>Tgl Cetak Surat Jalan</td><td>: <?= $po->tgl_cetak_sj ?></td>
        </tr>
    </table>
<?php } ?>
</body>
</html>
