<?php $this->load->view('email/header'); ?>
    <body class="">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
            <tr>
                <td>&nbsp;</td>
                <td class="container">
                    <div class="content">
                        <table role="presentation" class="main">
                            <tr>
                                <td height="2" style="width:33.3%;background: rgb(255,0,0);
  background: linear-gradient(90deg, rgba(255,0,0,1) 0%, rgba(255,188,188,1) 50%, rgba(255,0,0,1) 100%);line-height:2px;font-size:2px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="wrapper">
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td align="center">
                                                <p style="border-bottom: 1px solid #D0D0D0;"><img src="<?= base_url('assets/panel/images/logo_sinsen.jpg') ?>" alt="SINARSENTOSA" height="60px">
                                            </td>
                                        </tr>
                                    </table>
                                    <table>
                                        <?php if($selisih == 0): ?>
                                        <tr>
                                            <td>
                                                Penerimaan barang dengan Shipping List nomor <?= $purchase_order['id_packing_sheet'] ?> atas PO no <?= $purchase_order['po_id'] ?> telah selesai. Seluruh daftar parts pada Shipping List telah berhasil diterima dan tidak ditemukan selisih.
                                            </td>
                                        </tr>
                                        <?php else: ?>
                                        <tr>
                                            <td>
                                                Penerimaan barang dengan Shipping List nomor <?= $purchase_order['id_packing_sheet'] ?> atas PO no <?= $purchase_order['po_id'] ?> telah selesai. Dari seluruh parts yang telah diterima terdapat parts yang memiliki selisih yaitu:
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table style='width: 50%; border-collapse: collapse;'>
                                                    <thead>
                                                        <tr style='background-color: #52b4f5;'>
                                                            <td style='border: 1px solid black; padding: 5px;'>Part Number</td>
                                                            <td style='border: 1px solid black; padding: 5px;'>Part Description</td>
                                                            <td style='border: 1px solid black; padding: 5px;'>Qty</td>
                                                            <td style='border: 1px solid black; padding: 5px;'>UoM</td>
                                                            <td style='border: 1px solid black; padding: 5px;'>Reason</td>
                                                        </tr>
                                                    </thead>
                                                    <?php $total = 0; ?>
                                                    <?php foreach($parts as $part): ?>
                                                    <tr>
                                                        <td style='border: 1px solid black;padding: 5px;'><?= $part->id_part ?></td>
                                                        <td style='border: 1px solid black;padding: 5px;'><?= $part->nama_part ?></td>
                                                        <td style='border: 1px solid black;padding: 5px;'><?= $part->qty ?></td>
                                                        <td style='border: 1px solid black;padding: 5px;'><?= $part->satuan ?></td>
                                                        <td style='border: 1px solid black;padding: 5px;'><?= $part->reason ?></td>
                                                    </tr>
                                                    <?php $total += $part->harga_saat_dibeli * ($part->qty); endforeach; ?>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Dengan total sebesar Rp <?= number_format("$total",0,",","."); ?> dan saat ini berada pada temp. warehouse untuk dieksekusi sebagai retur/exchange ke Main Dealer.
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </table>
                        </table>
<?php $this->load->view('email/footer'); ?>