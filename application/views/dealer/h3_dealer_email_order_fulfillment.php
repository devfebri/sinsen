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
                                        <tr>
                                            <td>Kepada Yth.</td>
                                        </tr>
                                        <tr>
                                            <td>Pelanggan Setia Kami</td>
                                        </tr>
                                        <tr>
                                            <td>Bapak/Ibu</td>
                                        </tr>
                                    </table>
                                    <table style='margin-top: 10px;'>
                                        <tr>
                                            <td>Salam SATU HATI</td>
                                        </tr>
                                    </table>
                                    <table style='margin-top: 10px;'>
                                        <tr>
                                            <td>Di infokan update pemesanan part Bapak/Ibu di AHASS kami sebagai berikut:</td>
                                        </tr>
                                    </table>
                                    <table style='margin-top: 10px;'>
                                        <tr>
                                            <td>Tanggal Pesanan : <?= $purchase_order->tanggal_order ?></td>
                                        </tr>
                                    </table>
                                    <table style='margin-top: 10px; width: 80%; border-collapse: collapse; margin-left: 5px;' class='table'>
                                        <tr>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black; font-weight: bold'>No</td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black; font-weight: bold'>No. Part</td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black; font-weight: bold'>Nama Part</td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black; font-weight: bold'>Qty</td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black; font-weight: bold'>Keterangan</td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black; font-weight: bold'>Tanggal Estimasi Tersedia</td>
                                        </tr>
                                        <?php $index = 1; foreach($parts as $part): ?>
                                        <tr>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black;'><?= $index ?>.</td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black;'><?= $part->id_part ?></td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black;'><?= $part->nama_part ?></td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black;'><?= $part->kuantitas ?></td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black;'><?= $part->kuantitas == $part->qty_terpenuhi ? "Sudah Tersedia" : "Belum Tersedia" ?></td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black;'><?= $part->kuantitas == $part->qty_terpenuhi ? "-" : $part->eta_terlama ?></td>
                                        </tr>
                                        <?php $index++; endforeach; ?>
                                    </table>
                                    <table style='margin-top: 10px;'>
                                        <tr>
                                            <td>Note: <span style='font-weight: bold'>Tanggal estimasi ketersediaan part bisa berubah dan akan diupdate kembali apabila terjadi perubahan tanggal.</span></td>
                                        </tr>
                                    </table>
                                    <table style='margin-top: 10px;'>
                                        <tr>
                                            <td>Terimakasih telah bersedia menunggu dan berbelanja di AHASS kami.</td>
                                        </tr>
                                    </table>
                                    <table style='margin-top: 10px;'>
                                        <tr>
                                            <td>Hormat Kami,</td>
                                        </tr>
                                    </table>
                                    <table style='margin-top: 70px;'>
                                        <tr>
                                            <td><?= strtoupper($dealer->nama_dealer) ?></td>
                                        </tr>
                                        <tr>
                                            <td><?= strtoupper($dealer->alamat) ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
<?php $this->load->view('email/footer'); ?>