<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>PO Vendor</title>
    <style>
        @media print {
            @page {
                sheet-size: 210mm 297mm;
                /*  margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 1cm;
                margin-top: 1cm;*/
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
                /*border-collapse: separate;*/
            }

            .table-bordered tr td {
                border: 1px solid black;
                padding-left: 6px;
                padding-right: 6px;
            }

            body {
                font-family: "Arial";
                font-size: 12px;
            }

            .top-line td{
                vertical-align: text-top;
            }

            table.small-text td{
                font-size: 12px;
            }

            .line-through{
                text-decoration: line-through;
            }
        }
    </style>
</head>
<body>
    <table class="table" style='margin-bottom: 10px;'>
        <tr>
            <td class='text-center text-bold' style='font-size: 16px;'>PO Vendor<td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width='16%'>Tanggal</td>
            <td width='1%'>:</td>
            <td width='83%'><?=  $po_vendor['tanggal'] ?></td>
        </tr>
        <tr>
            <td width='16%'>Vendor</td>
            <td width='1%'>:</td>
            <td width='83%'><?=  $po_vendor['vendor_name'] ?></td>
        </tr>
        <tr>
            <td width='16%'>Keterangan</td>
            <td width='1%'>:</td>
            <td width='83%'><?=  $po_vendor['keterangan'] ?></td>
        </tr>
    </table>
    <table style="margin-top: 20px;;" class="table">
        <tr>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='5%'>No.</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Part Number</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Nama Part</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Qty Order</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-center'>HPP</td>
        </tr>
        <?php $index = 1; foreach($parts as $part): ?>
        <tr>
            <td><?= $index ?>.</td>
            <td><?= $part['id_part'] ?></td>
            <td><?= $part['nama_part'] ?></td>
            <td><?= $part['qty_order'] ?></td>
            <td class='text-right'><?= $part['harga_formatted'] ?></td>
        </tr>
        <?php $index++; endforeach; ?>
        <tr>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold' colspan="3"></td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold text-right'>Total</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold text-right'><?= $po_vendor['total_formatted'] ?></td>
        </tr>
    </table>
</body>
</html>