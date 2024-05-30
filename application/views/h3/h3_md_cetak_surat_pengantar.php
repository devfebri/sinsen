<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>SURAT PENGANTAR (SHIPPING LIST)</title>
    <style>
        @media print {
            @page {
                sheet-size: 210mm 297mm;
                /* sheet-size: A3-L; */
                /* margin-left: 0mm;
                margin-right: 0mm; */
                /* margin-bottom: 1cm;
                margin-top: 1cm; */
            }

            .text-bold{
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
                font-size: 11px;
            }

            .top-line td{
                vertical-align: text-top;
            }

            table.small-text td{
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <table class="table" style='margin-bottom: 20px;'>
        <tr>
            <td class='text-center text-bold' style='font-size: 16px;'>SURAT PENGANTAR (SHIPPING LIST)</td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width='20%'>Main Dealer</td>
            <td width='1%'>:</td>
            <td width='79%'>PT. Sinar Sentosa Primatama</td>
        </tr>
        <tr>
            <td width='20%'>Alamat</td>
            <td width='1%'>:</td>
            <td width='79%'>JL. Kol. Abunjani No.9 Sipin - Jambi</td>
        </tr>
    </table>
    <table class="table" style='margin-top: 20px;'>
        <tr>
            <td width='20%'>No. Surat Pengantar (SL)</td>
            <td width='1%'>:</td>
            <td width='29%'><?= $surat_pengantar['id_surat_pengantar'] ?></td>
            <td width='16%'>Ditujukan Kepada</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $surat_pengantar['kode_dealer_md'] ?> - <?= $surat_pengantar['nama_dealer'] ?></td>
        </tr>
        <tr>
            <td width='20%'>Tgl Surat Pengantar</td>
            <td width='1%'>:</td>
            <td width='29%'><?= $surat_pengantar['tanggal'] ?></td>
            <td width='16%'>Alamat</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $surat_pengantar['alamat'] ?></td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width='50%'></td>
            <td width='16%'>Telepon</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $surat_pengantar['no_telp'] ?></td>
        </tr>
        <tr>
            <td width='50%'></td>
            <td width='16%'>Nama Pemilik</td>
            <td width='1%'>:</td>
            <td width='33%'>Bpk/ Ibu. <?= $surat_pengantar['pemilik'] ?></td>
        </tr>
    </table>
    <table class='table'>
        <tr>
            <td>Telah diterima dengan baik:</td>
        </tr>
    </table>
    <table class="table table-bordered">
        <tr>
            <td rowspan='2' class='text-center'>No.</td>
            <td rowspan='2' class='text-center'>Faktur No.</td>
            <td rowspan='2' class='text-center'>No. SO</td>
            <td rowspan='2' class='text-center'>Tgl SJ</td>
            <td rowspan='2' class='text-center'>No Surat Jalan</td>
            <td colspan='3' class='text-center'>Jumlah Barang (Koli)</td>
        </tr>
        <tr>
            <td class='text-center'>Parts</td>
            <td class='text-center'>Ban</td>
            <td class='text-center'>Oli</td>
        </tr>
        <tr>
            <?php $index = 1; $total_parts = 0; $total_ban = 0; $total_oil = 0; foreach($packing_sheets as $packing_sheet): ?>
            <tr>
                <td><?= $index ?>.</td>
                <td><?= $packing_sheet['no_faktur'] ?></td>
                <td><?= $packing_sheet['id_sales_order'] ?></td>
                <td><?= $packing_sheet['tgl_packing_sheet'] ?></td>
                <td><?= $packing_sheet['id_packing_sheet'] ?></td>
                <td class='text-center'><?= $packing_sheet['parts'] ?></td>
                <td class='text-center'><?= $packing_sheet['ban'] ?></td>
                <td class='text-center'><?= $packing_sheet['oil'] ?></td>
            </tr>
            <?php $total_parts += $packing_sheet['parts']; $total_ban += $packing_sheet['ban']; $total_oil += $packing_sheet['oil']; $index++; endforeach; ?>
        </tr>
        <tr>
            <td colspan='5' class='text-center text-bold'>Total</td>
            <td class='text-center text-bold'><?= $total_parts ?></td>
            <td class='text-center text-bold'><?= $total_ban ?></td>
            <td class='text-center text-bold'><?= $total_oil ?></td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td class='text-bold'>Catatan :</td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td>Dengan ditandatanganinya Surat Pengiriman (Shipping List) dan Surat Jalan ini, Bapak/ Ibu telah menerima barang secara lengkap dengan faktur dan surat jalan yang tertera diatas.</td>
        </tr>
        <tr>
            <td>Setelah ditandatangni harap surat jalan & surat pengantar (warna pink) dikembali ke PT Sinar Sentosa Primatama</td>
        </tr>
    </table>
     <table style="margin-top: 40px;" class="table">
        <tr>
            <td width="33.33%" class="text-center">Bag. Pengiriman</td>
            <td width="33.33%" class="text-center">Ekspedisi</td>
            <td width="33.33%" class="text-center">Outlet/Toko</td>
        </tr>
    </table>
    <table style="margin-top: 70px;" class="table">
        <tr>
            <td width="16.66%" class="text-left">(</td>
            <td width="16.66%" class="text-right">)</td>
            <td width="16.66%" class="text-left">(</td>
            <td width="16.66%" class="text-right">)</td>
            <td width="16.66%" class="text-left">(</td>
            <td width="16.66%" class="text-right">)</td>
        </tr>
    </table>
</body>
</html>