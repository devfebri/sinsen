<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Faktur Penjualan</title>
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

            table tr td{
                vertical-align: top;
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
    <table>
        <tr>
            <td class='text-bold'>PT. Sinar Sentosa Primatama</td>
        </tr>
        <tr>
            <td>Jl. Kolonel Abunjani No. 09</td>
        </tr>
        <tr>
            <td>Jambi</td>
        </tr>
        <tr>
            <td>Telepon: 0741-61551</td>
        </tr>
    </table>
    <table class="table" style='margin-bottom: 20px;'>
        <tr>
            <td class='text-center text-bold' style='font-size: 16px;'>Faktur Penjualan<td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width='11%'>No. Faktur</td>
            <td width='1%'>:</td>
            <td width='42%'><?= $faktur['no_faktur'] ?></td>
            <td width='15%'>Tipe Penjualan</td>
            <td width='1%'>:</td>
            <td width='30%'><?= $faktur['jenis_pembayaran'] ?></td>
        </tr>
        <tr>
            <td width='11%'>Tgl. Faktur</td>
            <td width='1%'>:</td>
            <td width='42%'><?= $faktur['tgl_faktur'] ?></td>
            <td width='15%'>Jatuh Tempo</td>
            <td width='1%'>:</td>
            <td width='30%'><?= $faktur['tgl_jatuh_tempo'] ?></td>
        </tr>
        <tr>
            <td width='11%'>Customer</td>
            <td width='1%'>:</td>   
            <td width='42%'><?= $faktur['nama_dealer'] ?></td>
            <td width='15%'>Nomor PO</td>
            <td width='1%'>:</td>   
            <td width='30%'><?= $faktur['id_ref'] ?></td>
        </tr>
    </table>
    <table style="margin-top: 20px;" class="table">
        <tr>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='5%'>No.</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='16%'>Kode Barang</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='26%'>Nama Barang</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='5%'>Qty</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='11%' class='text-right'>Harga</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='11%' class='text-right'>Discount</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='11%' class='text-right'>Total</td>
        </tr>
        <?php 
        $sub_total = 0; 
        $total = 0; 
        $index = 1; 
        $total_diskon = 0; 
        foreach($parts as $part): ?>
        <tr>
            <td><?= $index ?>.</td>
            <td><?= $part['id_part'] ?></td>
            <td><?= $part['nama_part'] ?></td>
            <td><?= $part['qty_supply'] ?></td>
            <td class='text-right'><?= number_format($part['harga'], 0, ',', '.') ?></td>
            <td class='text-right'><?= number_format($part['diskon'], 0, ',', '.') ?></td>
            <td class='text-right'><?= number_format($part['amount'] , 0, ',', '.') ?></td>
        </tr>
        <?php 
        $sub_total += $part['amount'];
        $total += $part['qty_scan']; 
        $total_diskon = ($part['harga'] - $part['harga_setelah_diskon']) * $part['qty_supply']; 
        $index++; 
        endforeach; ?>
        <!-- <tr>
            <td style='border-top: 1px solid black;' colspan="5">Terbilang : <?= number_to_words($faktur['total']) ?> Rupiah</td>
            <td style='border-top: 1px solid black;' class='text-right'>Total</td>
            <td style='border-top: 1px solid black;' class='text-right'><?= number_format($faktur['total'] , 0, ',', '.') ?></td>
        </tr> -->
        <?php if($faktur['produk']=='Tools'){?>
        <tr>
            <td style='border-top: 1px solid black;' colspan="5"></td>
            <td style='border-top: 1px solid black;' class='text-right'>Sub Total</td>
            <td style='border-top: 1px solid black;' class='text-right'><?= number_format($faktur['sub_total'] , 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td class='text-right'>PPN</td>
            <td class='text-right'><?= number_format($faktur['total_ppn'] , 0, ',', '.') ?></td>
        </tr>
        <?php }?>
        <?php if($faktur['kategori_po']=='KPB'){?>
        <tr>
            <td style='border-top: 1px solid black;' colspan="5"></td>
            <td style='border-top: 1px solid black;' class='text-right'>Sub Total</td>
            <td style='border-top: 1px solid black;' class='text-right'><?= number_format($faktur['sub_total'] , 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td class='text-right'>PPN</td>
            <td class='text-right'><?= number_format($faktur['total_ppn'] , 0, ',', '.') ?></td>
        </tr>
        <?php }?>
        <tr>
            <td style='border-top: 1px solid black;' colspan="5">Terbilang : <?= number_to_words($faktur['total']) ?> Rupiah</td>
            <td style='border-top: 1px solid black;' class='text-right'>Total</td>
            <td style='border-top: 1px solid black;' class='text-right'><?= number_format($faktur['total'] , 0, ',', '.') ?></td>
        </tr>
    </table>
    <table style="margin-top: 40px;" class="table">
        <tr>
            <td width="33.33%" class="text-center">Disetujui Oleh,</td>
            <td width="33.33%" class="text-center"></td>
            <td width="33.33%" class="text-center">Dibuat Oleh,</td>
        </tr>
    </table>
    <table style="margin-top: 70px;" class="table">
        <tr>
            <td width="16.6%" class="text-left">(</td>
            <td width="16.6%" class="text-right">)</td>
            <td width="16.6%" class="text-right"></td>
            <td width="16.6%" class="text-right"></td>
            <td width="33.2%" class="text-center">(<?= $dibuat_oleh ?>)</td>
        </tr>
    </table>
</body>
</html>