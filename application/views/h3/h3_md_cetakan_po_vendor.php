<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?= $purchase->po_id ?></title>
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

                table.header-table{
                    font-size: 12px;
                }

                table.table-parts{
                    margin-top: 20px; 
                    margin-bottom: 50px;
                    font-size: 10px;
                }

                .table-parts .header td{
                    border: 1px solid black;
                    padding-left: 6px;
                    padding-right: 6px;
                }

                .table-parts .row td{
                    border-left: 1px solid black;
                    border-right: 1px solid black;
                    padding-left: 6px;
                    padding-right: 6px;
                }

                .table-parts .total{
                    border: 1px solid black;
                    padding-left: 6px;
                    padding-right: 6px;
                }

                .text-center{
                    text-align: center;
                }

                .text-right{
                    text-align: right;
                }

                td.side-border{
                    border-left: 1px solid black;
                    border-right: 1px solid black;
                    padding-left: 6px;
                    padding-right: 6px;
                }
            }
        </style>
    </head>
    <body>
        <style>
            .container{
                width: 100%;
            }
            .half {
                width: 50%;
                float: left;
            }
            .block{
                display: block;
                width: 100%;
            }
        </style>
        <div class='container'>
            <div class='half'>
                <!-- <div class="container">
                    <img style='margin-left: -8px;' src="<?= base_url("assets/panel/images/logo_sinsen.jpg") ?>" alt="Logo" height='50'>
                </div> -->
                <div class="container" style='font-size: 15px; font-weight: bold'>
                    <span class='block'>PT. Sinar Sentosa Primatama</span>
                </div>
                <div class="container" style='font-size: 10px;'>
                    <span class='block'>Jl.Kol. Abunjani No.9 Sipin, Jambi</span>
                </div>
                <div class="container" style='font-size: 10px;'>
                    <span class='block'>Tlp (0741) 61551 (hunting), Fax. (0741) 61573</span>
                </div>
                <div class="container" style='font-size: 10px;'>
                    <span class='block'>Website: www.sinarsentosa.co.id</span>
                </div>
            </div>
            <style>
                table.kop-tujuan-order td{
                    font-size: 10px;
                }
            </style>
            <div class='half'>
                <table class="table kop-tujuan-order">
                    <tr>
                        <td style='text-align: right'>Dealer/Toko</td>
                        <td width='3%'>:</td>
                        <td width='60%'><?= $po_vendor['vendor_name'] ?></td>
                    </tr>
                    <tr>
                        <td style='text-align: right'>Tgl. Order</td>
                        <td width='3%'>:</td>
                        <td width='60%'><?= $po_vendor['tanggal'] ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <table class="table table-borderedx" style='margin-top: 25px;'>
            <tr>
                <td style='text-align:center; font-size: 22px; text-decoration: underline;'>ORDER PEMBELIAN</td>
            </tr>
        </table>
        <table class="table table-borderedx">
            <tr>
                <td style='text-align:center; font-size: 12;'>Nomor Order: <?= $po_vendor['id_po_vendor'] ?></td>
            </tr>
        </table>
        <table class="table table-parts">
            <tr class='header'>
                <td width='5%'>No.</td>
                <td width='10%'>Kode Part</td>
                <td width='30%'>Nama Part</td>
                <td width='5%' class='text-center'>Qty</td>
                <td colspan='2' width='10%'>Harga Beli</td>
                <td colspan='2' width='10%' class='text-right'>Total</td>
                <td width='10%'>Keterangan</td>
            </tr>
            <?php 
            $total = 0;
            $total_qty = 0;
            $index = 1; foreach ($parts as $part): ?>
            <tr class='row'>
                <td width='5%'><?= $index ?>.</td>
                <td width='10%'><?= $part['id_part'] ?></td>
                <td width='30%'><?= $part['nama_part'] ?></td>
                <td width='5%' class='text-center'><?= $part['qty_order'] ?></td>
                <td widht='2%' style='border-right: 0px;'>Rp</td>
                <td width='8%' class='text-right' style='border-left: 0px;'><?= number_format($part['harga'], 0, ',', '.') ?></td>
                <td widht='2%' style='border-right: 0px;'>Rp</td>
                <td width='13%' class='text-right' style='border-left: 0px;'><?= number_format($part['harga'] * $part['qty_order'], 0, ',', '.') ?></td>
                <td width='15%'><?= $part['keterangan'] ?></td>
            </tr>
            <?php 
            $total += $part['harga'] * $part['qty_order'];
            $total_qty += $part['qty_order'];
            $index++; 
            endforeach; ?>
            <tr class='total'>
                <td width='5%'></td>
                <td class='text-center'>Grand Total</td>
                <td></td>
                <td width='5%' class='text-center side-border'><?= $total_qty ?></td>
                <td width='10%'></td>
                <td width='10%'></td>
                <td class='text-center side-border' style='border-right: 0px;'>Rp</td>
                <td class='text-right' style='padding-right: 6px; border-right: 1px solid black;'><?= number_format($total, 0, ',', '.') ?></td>
                <td></td>
            </tr>
        </table>
        <style>
            table.header-tanda-tangan{
                margin-top: 5px;
                font-size: 12px;
            }

            table.footer-tanda-tangan{
                margin-top: 70px;
                font-size: 12px;
            }
        </style>
        <table class="table header-tanda-tangan">
            <tr>
                <td class='text-center' width='50%'>Diketahui/Setuju oleh,</td>
                <td width='15%'></td>
                <td width='35%'>Jambi, Tgl </td>
            </tr>
        </table>
        <table class="table header-tanda-tangan">
            <tr>
                <td class='text-center' width='50%'>Pimpinan/Wakil</td>
                <td class='text-center' width='50%'>Bag. Pembelian</td>
            </tr>
        </table>
        <table class='table footer-tanda-tangan'>
            <tr>
                <td class='text-center' width='50%'>(........................................)</td>
                <td class='text-center' width='50%'>(........................................)</td>
            </tr>
        </table>
    </body>
</html>