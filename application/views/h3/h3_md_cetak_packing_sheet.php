<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Packing Sheet</title>
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
            <td class='text-center text-bold' style='font-size: 16px;'>PACKING SHEET<td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width='16%'>Main Dealer</td>
            <td width='1%'>:</td>
            <td width='33%'>PT. Sinar Sentosa Primatama</td>
            <td style="text-align: right;" width='50%'>
                <?php if($packing_sheet->jenis_pembayaran == 'Tunai'): ?>
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
            <td width='16%'>No Surat Jalan (PS)</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $packing_sheet->id_packing_sheet ?></td>
            <td width='15%'>Dealer</td>
            <td width='1%'>:</td>
            <td width='34%'><?= $packing_sheet->kode_dealer_md ?> - <?= $packing_sheet->nama_dealer ?></td>
        </tr>
        <tr>
            <td width='16%'>Tgl Surat Jalan</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $packing_sheet->tgl_packing_sheet ?></td>
            <td width='15%'>Alamat Dealer</td>
            <td width='1%'>:</td>
            <td width='34%'><?= $packing_sheet->alamat ?></td>
        </tr>
        <tr>
            <td width='16%'>No. Faktur</td>
            <td width='1%'>:</td>   
            <td width='33%'><?= $packing_sheet->no_faktur ?></td>
            <td width='15%'>Ditujukan kepada</td>
            <td width='1%'>:</td>   
            <td width='34%'><?= $packing_sheet->pemilik ?></td>
        </tr>
        <tr>
            <td width='16%'>Tgl. Faktur</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $packing_sheet->tanggal_faktur ?></td>
            <td width='15%'>Nama Ekspedisi</td>
            <td width='1%'>:</td>
            <td width='34%'></td>
        </tr>
        <tr>
            <td width='16%'>No. DO</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $packing_sheet->id_do_sales_order ?><?= $packing_sheet->sudah_revisi == 1 ? '-REV' : '' ?></td>
            <td width='15%'>Supir</td>
            <td width='1%'>:</td>
            <td width='34%'></td>
        </tr>
        <?php 
        $total_koli = '';
        foreach ($jumlah_koli as $key => $value):
            if($value > 0){
                $total_koli .= ", {$value} Koli {$key}";
            }
        endforeach;
        $total_koli = substr($total_koli, 2);
        ?>
        <tr>
            <td width='16%'>No. SO</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $packing_sheet->id_sales_order ?></td>
            <td width='15%'>Total Koli</td>
            <td width='1%'>:</td>
            <td width='34%'><?= $total_koli ?></td>
        </tr>
    </table>
    <table style="margin-top: 20px;;" class="table">
        <tr>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='5%'>No.</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>No. Part</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Nama Part</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Serial Number</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Type Acc</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Qty</td>
        </tr>
        <?php $total = 0; $index = 1; foreach($parts as $part): ?>
        <tr>
            <td><?= $index ?>.</td>
            <td><?= $part->id_part ?></td>
            <td><?= $part->nama_part ?></td>
            <td><?= $part->serial_number ?></td>
            <td><?= $part->acc_type ?></td>
            <td><?= $part->qty_scan ?></td>
        </tr>
        <?php $total += $part->qty_scan; $index++; endforeach; ?>
        <tr>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold' colspan="4"></td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold'>Total</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold'><?= $total ?></td>
        </tr>
    </table>
    <table style="margin-top: 40px;" class="table">
        <tr>
            <td width="25%" class="text-center">Dibuat Oleh,</td>
            <td width="25%" class="text-center">Diketahui Oleh,</td>
            <td width="25%" class="text-center">Diserahkan Oleh,</td>
            <td width="25%" class="text-center">Diterima Oleh,</td>
        </tr>
    </table>
    <table style="margin-top: 70px;" class="table">
        <tr>
            <td width="12,5%" class="text-left">(</td>
            <td width="12,5%" class="text-right">)</td>
            <td width="12,5%" class="text-left">(</td>
            <td width="12,5%" class="text-right">)</td>
            <td width="12,5%" class="text-left">(</td>
            <td width="12,5%" class="text-right">)</td>
            <td width="12,5%" class="text-left">(</td>
            <td width="12,5%" class="text-right">)</td>
        </tr>
    </table>
</body>
</html>