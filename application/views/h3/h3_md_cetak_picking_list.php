<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Picking List</title>
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
            <td class='text-center text-bold' style='font-size: 16px;'>PICKING LIST<td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width='16%'>Main Dealer</td>
            <td width='1%'>:</td>
            <td width='33%'>PT. Sinar Sentosa Primatama</td>
            <td style="text-align: right;" width='50%'>
                <?php if($picking_list->jenis_pembayaran == 'Cash'): ?>
                <span>CASH</span>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width='16%'>Alamat</td>
            <td width='1%'>:</td>
            <td width='83%'>Jl. Kol. Abunjani No.9 Sipin - Jambi</td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width='16%'>No Picking List</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $picking_list->id_picking_list ?></td>
            <td width='15%'>Dealer</td>
            <td width='1%'>:</td>
            <td width='34%'><?= $picking_list->kode_dealer_md ?> - <?= $picking_list->nama_dealer ?></td>
        </tr>
        <tr>
            <td width='16%'>Tgl Picking List</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $picking_list->tanggal_picking ?></td>
            <td width='15%'>Alamat Dealer</td>
            <td width='1%'>:</td>
            <td width='34%'><?= $picking_list->alamat ?></td>
        </tr>
        <tr>
            <td width='16%'>No. DO</td>
            <td width='1%'>:</td>   
            <td width='33%'><?= $picking_list->id_do_sales_order ?></td>
            <td width='15%'>No. SO Dealer</td>
            <td width='1%'>:</td>   
            <td width='34%'><?= $picking_list->id_sales_order ?></td>
        </tr>
        <tr>
            <td width='16%'>Tgl. DO</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $picking_list->tanggal_do ?></td>
            <td width='15%'>Tgl. SO</td>
            <td width='1%'>:</td>
            <td width='34%'><?= $picking_list->tanggal_so ?></td>
        </tr>
        <tr>
            <td width='16%'>Nama Picker</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $picking_list->nama_picker ?></td>
            <td width='15%'>Pembayaran</td>
            <td width='1%'>:</td>
            <td width='34%'><?= $picking_list->jenis_pembayaran ?></td>
        </tr>
    </table>
    <table style="margin-top: 20px;;" class="table">
        <tr>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='5%'>No.</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>No. Part</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Nama Part</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Qty</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Serial Number</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>No. Rak</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Ket</td>
        </tr>
        <?php $index = 1; foreach($parts as $part): ?>
        <tr>
            <td><?= $index ?>.</td>
            <td><?= $part->id_part ?></td>
            <td><?= $part->nama_part ?></td>
            <td><?= $part->qty_supply ?></td>
            <td><?= $part->serial_number ?></td>
            <td><?= $part->kode_lokasi_rak ?></td>
            <td><?= $part->deskripsi ?></td>
        </tr>
        <?php $index++; endforeach; ?>
    </table>
    <table style="margin-top: 40px;" class="table">
        <tr>
            <td width="33.33%" class="text-center">Dicetak Oleh,</td>
            <td width="33.33%" class="text-center"></td>
            <td width="33.33%" class="text-center">Diterima Oleh,</td>
        </tr>
    </table>
    <table style="margin-top: 70px;" class="table">
        <tr>
            <td width="16,6%" class="text-left">(</td>
            <td width="16,6%" class="text-right">)</td>
            <td width="16,6%" class="text-left"></td>
            <td width="16,6%" class="text-right"></td>
            <td width="16,6%" class="text-left">(</td>
            <td width="16,6%" class="text-right">)</td>
        </tr>
    </table>
</body>
</html>