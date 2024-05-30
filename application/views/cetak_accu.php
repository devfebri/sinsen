<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Cetak</title>
    <style>
        @media print {
            @page {
                sheet-size: 8.6cm 5.2cm;
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
                    border: 0px solid black;
                    padding-left: 6px;
                    padding-right: 6px;
                }
            body{
                font-family: "Arial";
                font-size: 8pt;
            }
        }
    </style>
</head>

<body>
<p>
    <table>
        <tr>
            <td>Tanggal Penerimaan</td>
            <?php $tgl_mohon     = date("d F Y", strtotime($isi_file->tgl_penerimaan));       ?>
            <td>: <?php echo $tgl_mohon ?></td>
        </tr>
        <tr>
            <td>Kode KSU</td>
            <td>: <?php echo $isi_file->id_ksu ?></td>
        </tr>
        <tr>
            <td>Nama KSU</td>
            <td>: <?php echo $isi_file->ksu ?></td>
        </tr>
    </table>
</p>
</body>
</html>
