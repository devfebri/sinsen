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
    <table class="table">
        <tr>
            <td width='50%'>PT. Sinar Sentosa Primatama</td>
            <td width='50%'>Ditujukan Kepada</td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width='50%'>JL. Kol. Abunjani No.9 Sipin</td>
            <td width='16%'>Nama Toko</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $surat_pengantar['nama_dealer'] ?></td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width='50%'>Jambi</td>
            <td width='16%'>Alamat</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $surat_pengantar['alamat'] ?></td>
        </tr>
    </table>
    <table class="table" style='margin-top: 15px;'>
        <tr>
            <td class='text-center text-bold' style='font-size: 16px;'>SURAT PENGANTAR<td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td class='text-center text-bold' style='font-size: 16px;'>PENGGANTIAN BARANG CLAIM C3<td>
        </tr>
    </table>
    <table class="table" style='margin-bottom: 10px;'>
        <tr>
            <td class='text-center' style='font-size: 11px;'>Nomor : <?= $surat_pengantar['id_surat_pengantar'] ?><td>
        </tr>
    </table>
    <table style="margin-top: 20px;" class="table table-bordered">
        <tr>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='5%'>No.</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Kode Part</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Nama Part</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Qty</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>No. Claim Dealer</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>No. Faktur Dealer</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;'>Keterangan</td>
        </tr>
        <?php $total_qty = 0; $index = 1; foreach($parts as $part): ?>
        <tr>
            <td><?= $index ?>.</td>
            <td><?= $part['id_part'] ?></td>
            <td><?= $part['nama_part'] ?></td>
            <td><?= $part['qty_ganti_barang'] ?></td>
            <td><?= $part['id_claim_dealer'] ?></td>
            <td><?= $part['no_faktur'] ?></td>
            <td><?= $part['keterangan'] ?></td>
        </tr>
        <?php $total_qty += $part['qty_ganti_barang']; $index++; endforeach; ?>
        <tr>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold text-center' colspan="3">Total</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold'><?= $total_qty ?></td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold'></td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold'></td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold'></td>
        </tr>
    </table>
    <table class="table" style='margin-top: 10px;'>
        <tr>
            <td class='text-bold'>Catatan:</td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td style='font-size: 9px;'>* Dengan ditanda tanganinya surat pengantar ini, bapak/ibu telah menerima barang secara lengkap sesuai dengan qty pergantian part claim yang tertera diatas.</td>
        </tr>
    </table>
    <table class="table" style='margin-top: 10px;'>
        <tr>
            <td class='text-bold' style='font-size: 12px;'>Jambi, <?= date('d F Y') ?></td>
        </tr>
    </table>
    <table style="margin-top: 40px;" class="table">
        <tr>
            <td width="33.33%" class="text-center">Yang Mengirim,</td>
            <td width="33.33%" class="text-center">Diketahui Oleh,</td>
            <td width="33.33%" class="text-center">Diterima Oleh,</td>
        </tr>
    </table>
    <table style="margin-top: 70px;" class="table">
        <tr>
            <td width="33.33%" class="text-center" style='padding-left: 4%; padding-right: 4%; text-decoration: underline;'>Rizky Agustina</td>
            <td width="33.33%" class="text-center" style='padding-left: 4%; padding-right: 4%; text-decoration: underline;'>Haryanto Hygens</td>
            <td width="33.33%" class="text-center" style='padding-left: 4%; padding-right: 4%; text-decoration: underline;'><?= $surat_pengantar['pic'] ?></td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width="33.33%" class="text-center" style='padding-left: 4%; padding-right: 4%;'>Admin Warehouse</td>
            <td width="33.33%" class="text-center" style='padding-left: 4%; padding-right: 4%;'>Part Manager</td>
            <td width="33.33%" class="text-center" style='padding-left: 4%; padding-right: 4%;'>Customer</td>
        </tr>
    </table>
    <!-- <table style="margin-top: 70px;" class="table">
        <tr>
            <td width="16.66%" class="text-left" style='padding-left: 4%;'>(</td>
            <td width="16.66%" class="text-right" style='padding-right: 4%;'>)</td>
            <td width="16.66%" class="text-left" style='padding-left: 4%;'>(</td>
            <td width="16.66%" class="text-right" style='padding-right: 4%;'>)</td>
            <td width="16.66%" class="text-left" style='padding-left: 4%;'>(</td>
            <td width="16.66%" class="text-right" style='padding-right: 4%;'>)</td>
        </tr>
    </table> -->
</body>
</html>