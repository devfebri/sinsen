<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?= $data['id_good_receipt'] ?></title>
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
    <table class="table table-bordereds">
        <tr>
            <td><?= strtoupper($data['nama_dealer']) ?></td>
        </tr>
        <tr>
            <td><?= $data['alamat'] ?></td>
        </tr>
        <tr>
            <td><?= $data['kabupaten'] ?></td>
        </tr>
        <tr>
            <td><?= $data['provinsi'] ?></td>
        </tr>
        <tr>
            <td><?= $data['no_telp'] ?></td>
        </tr>
    </table>
    <table class="table table-borderedx" style='margin-bottom: 15px;'>
        <tr>
            <td style='text-align:center; font-size: 22px;'>Penerimaan Parts</td>
        </tr>
    </table>
    <table class="table table-borderedx">
        <tr>
            <td width='22%'>No. Penerimaan</td>
            <td width='1%'>:</td>
            <td width='30%'><?= $data['id_good_receipt'] ?></td>
           
        </tr>
        <tr>
            <td width='22%'>Jenis Penerimaan</td>
            <td width='1%'>:</td>
            <td width='30%'>Purchase Order</td>
           
        </tr>
        <tr>
            <td width='22%'>Tgl Penerimaan</td>
            <td width='1%'>:</td>
            <td width='30%'><?= date_dmy($data['tanggal_penerimaan']) ?></td>
        </tr>
         <tr>
            <td width='22%'>Ref. Doc</td>
            <td width='1%'>:</td>
            <td width='30%'><?= $data['id_reference'] ?></td>
        </tr>
    </table>
    <style>
        table.table-sm {
            font-size: 12px;
        }

        tr.header td {
            border-bottom: 2px solid black;
            border-top: 2px solid black;
        }
    </style>
    <table style='margin-top: 20px; margin-bottom: 50px;' class="table table-borderedx table-sm">
        <tr class='header'>
            <td width='5%'>No.</td>
            <td width='16%'>Nomor Part</td>
            <td width='34%'>Deskripsi Part</td>
            <td width='10%'>Rak</td>
            <td width='10%' class='text-center'>Qty Terima</td>
            <td width='10%' class='text-center'>Qty PO</td>
        </tr>
        <?php $index = 1;
        foreach ($sparepart as $part) : ?>
            <tr>
                <td><?= $index ?>.</td>
                <td><?= $part->id_part ?></td>
                <td><?= $part->nama_part ?></td>
                <td><?= $part->id_rak ?></td>
                <td class='text-center'><?= $part->qty ?></td>
                <td class='text-center'><?= $part->qty_po ?></td>
            </tr>
        <?php $index++;
        endforeach; ?>

            <tr>
                <td><br></td>
            </tr>
                <tr>
                    <td colspan ="2" style='font-size: 14px;'>Dibuat Oleh,</td>
                    <td style='font-size: 14px; padding: left 30px;'>Diperiksa,</td>
                    <td style='font-size: 14px;'>Disetujui,</td>
                </tr>

                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                
                <tr>
                    <td colspan ="2">___________________________</td>
                    <td style='padding: left 30px;'>________________________________</td>
                    <td>__________________</td>
                </tr>
                <tr>
                    <td style='font-size: 14px;' colspan ="2">Counter Part/Frontdesk</td>
                    <td style='font-size: 14px;padding: left 30px;'>Inventory Part/Counter Part</td>
                    <td style='font-size: 14px;'>Kabeng/Kacab</td>
                </tr>
    </table>
    <footer style='position: absolute; bottom: 0'>
        <span><?= $data['id_good_receipt'] ?> - Dicetak : <?= date('d-M-Y H:i:s') ?></span>
    </footer>
</body>

</html>