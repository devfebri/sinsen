<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Delivery Order</title>
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
    <?php 
		$this->load->helper('terbilang');?>
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
    <table class="table" style='margin-bottom: 10px;'>
        <tr>
            <td class='text-center text-bold' style='font-size: 16px;'>DELIVERY ORDER<td>
        </tr>
    </table>
    <!-- <table class="table">
        <tr>
            <td width='16%'>Main Dealer</td>
            <td width='1%'>:</td>
            <td width='83%'>PT. Sinar Sentosa Primatama</td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width='16%'>Alamat</td>
            <td width='1%'>:</td>
            <td width='83%'>Jl. Kol. Abunjani No.9 Sipin - Jambi</td>
        </tr>
    </table> -->
    <table class="table">
        <tr>
            <td width='16%'>Tanggal DO</td>
            <td width='1%'>:</td>
            <td width='33%'><?= date('d/m/Y', strtotime($do_sales_order['tanggal_do'])) ?></td>
            <td width='15%'>Kode Customer</td>
            <td width='1%'>:</td>
            <td width='34%'><?= $do_sales_order['kode_dealer_md'] ?></td>
        </tr>
        <tr>
            <td width='16%'>No DO</td>
            <td width='1%'>:</td>
            <td width='33%'><?= $do_sales_order['id_do_sales_order'] ?><?= $do_sales_order['sudah_revisi'] == '1' ? '-REV' : ''; ?></td>
            <td width='15%'>Nama Customer</td>
            <td width='1%'>:</td>
            <td width='34%'><?= $do_sales_order['nama_dealer'] ?></td>
        </tr>
        <tr>
            <td width='16%'>Tanggal SO</td>
            <td width='1%'>:</td>   
            <td width='33%'><?= date('d/m/Y', strtotime($do_sales_order['tanggal_so'])) ?></td>
            <td width='15%'>Alamat</td>
            <td width='1%'>:</td>   
            <td width='34%'><?= $do_sales_order['alamat'] ?></td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width='16%'>No. SO</td>
            <td width='84%'>: <?= $do_sales_order['id_sales_order'] ?></td>
        </tr>
    </table>
    <table style="margin-top: 20px;" class="table">
        <tr>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='5%'>No.</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='16%'>No. Part</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='26%'>Nama Part</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='15%'>HET</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='5%'>Qty</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='11%'>Disc</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='8%'>Disc. Camp</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' width='14%' class='text-center'>Amount</td>
        </tr>
        <?php 
        $sub_total = 0; 
        $total = 0; 
        $index = 1; 
        foreach($do_sales_order_parts as $part): ?>
        <tr>
            <td><?= $index ?>.</td>
            <td><?= $part['id_part'] ?></td>
            <td><?= $part['nama_part'] ?></td>
            <td>Rp <?= number_format($part['harga_jual'], 0, ',', '.') ?></td>
            <td><?= $part['qty_supply'] ?></td>
            <?php if($part['tipe_diskon_satuan_dealer'] == 'Rupiah'): ?>
            <td>Rp <?= number_format($part['diskon_satuan_dealer'], 0, ',', '.') ?></td>
            <?php elseif($part['tipe_diskon_satuan_dealer'] == 'Persen'): ?>
            <td><?= number_format($part['diskon_satuan_dealer'], 0, ',', '.') ?>%</td>
            <?php else: ?>
            <td><?= number_format($part['diskon_satuan_dealer'], 0, ',', '.') ?></td>
            <?php endif; ?>

            <?php if($part['tipe_diskon_campaign'] == 'Rupiah'): ?>
            <td>Rp <?= number_format($part['diskon_campaign'], 0, ',', '.') ?></td>
            <?php elseif($part['tipe_diskon_campaign'] == 'Persen'): ?>
            <td><?= number_format($part['diskon_campaign'], 0, ',', '.') ?>%</td>
            <?php else: ?>
            <td><?= number_format($part['diskon_campaign'], 0, ',', '.') ?></td>
            <?php endif; ?>
            <td class='text-right'><?= number_format($part['amount'] , 0, ',', '.') ?></td>
        </tr>
        <?php 
        $sub_total += $part['amount'];
        $total += $part['qty_scan']; 
        $index++; 
        endforeach; ?>
        <!-- <tr>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold text-right' colspan="7">Sub Total</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold text-right'>Rp <?= number_format($sub_total, 0, ',', '.') ?></td>
        </tr> -->
        <?php 
            $diskon_insentif = $do_revisi['diskon_insentif_revisi'];
            $diskon_cashback = $do_revisi['diskon_cashback_revisi'];
            $diskon_cashback_otomatis = $do_revisi['diskon_cashback_revisi'];

            $total_diskon = ($diskon_insentif + $diskon_cashback + $diskon_cashback_otomatis);
        ?>
        <!-- <tr>
            <td style='border-top: 1px solid black;' class='text-bold text-right' colspan="7">Total Diskon</td>
            <td style='border-top: 1px solid black;' class='text-bold text-right'>Rp <?= number_format(
                ($total_diskon),
                0,
                ',',
                '.'
            ) ?></td>
        </tr> -->
        <tr>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold text-right' colspan="7">Total</td>
            <td style='border-top: 1px solid black; border-bottom: 1px solid black;' class='text-bold text-right'>Rp <?= number_format( $sub_total - $total_diskon, 0, ',', '.') ?></td>
        </tr>
        <tr>
            <?php $total_do = $sub_total - $total_diskon; ?>
            <td style='border-top: 1px solid black;' colspan="5">Terbilang : <?= number_to_words($total_do) ?> Rupiah</td>
        </tr>
    </table>
    <table style="margin-top: 40px;" class="table">
        <tr>
            <td width="33.33%" class="text-center">Dibuat Oleh,</td>
            <td width="33.33%" class="text-center">Diketahui Oleh,</td>
            <td width="33.33%" class="text-center">Diterima Oleh,</td>
        </tr>
    </table>
    <table style="margin-top: 70px;" class="table">
        <tr>
            <td width="16.6%" class="text-left">(</td>
            <td width="16.6%" class="text-right">)</td>
            <td width="16.6%" class="text-left">(</td>
            <td width="16.6%" class="text-right">)</td>
            <td width="16.6%" class="text-left">(</td>
            <td width="16.6%" class="text-right">)</td>
        </tr>
    </table>
</body>
</html>