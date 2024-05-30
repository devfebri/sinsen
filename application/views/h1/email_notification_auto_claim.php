<?php $this->load->view('email/header'); ?>
    <body class="">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
            <tr>
                <td>&nbsp;</td>
                <td class="container">
                    <div class="content">
                        <table role="presentation" class="main">
                            <tr>
                                <td height="2" style="width:33.3%;background: rgb(255,0,0); background: linear-gradient(90deg, rgba(255,0,0,1) 0%, rgba(255,188,188,1) 50%, rgba(255,0,0,1) 100%);line-height:2px;font-size:2px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="wrapper">
                                    <table style='margin-top: 10px;'>
                                        <tr>
                                            <td>Dengan Hormat,</td>
                                        </tr>
                                        <tr>
                                            <td>Bersama dengan ini terlampir juklak dan informasi sales id program dengan detail sebagai berikut :</td>
                                        </tr>
                                    </table>
                                   
                                    <table style='margin-top: 10px; width: 90%; border-collapse: collapse; margin-left: 5px;' class='table'>
                                        <tr>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black; font-weight: bold'>No</td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black; font-weight: bold'>ID Program MD</td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black; font-weight: bold'>Deskripsi Program</td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black; font-weight: bold'>Sub Kategori</td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black; font-weight: bold'>Periode Awal</td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black; font-weight: bold'>Periode Akhir</td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black; font-weight: bold'>Nama Tipe Motor</td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black; font-weight: bold'>Total Kontribusi Cash</td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black; font-weight: bold'>Total Kontribusi Kredit</td>
                                        </tr>
                                        <?php $index = 1; foreach($detail as $row_detail): 
                                            $total_cash = $row_detail->ahm_cash + $row_detail->md_cash + $row_detail->dealer_cash + $row_detail->other_cash + $row_detail->add_md_cash + $row_detail->add_dealer_cash;
                                            $total_kredit = $row_detail->ahm_kredit + $row_detail->md_kredit + $row_detail->dealer_kredit + $row_detail->other_kredit + $row_detail->add_md_kredit + $row_detail->add_dealer_kredit;                                            
                                        ?>
                                        <tr>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black;'><?= $index ?>.</td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black;'><?= $row_detail->id_program_md; ?></td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black;'><?= strtoupper($row_detail->judul_kegiatan); ?></td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black;'><?= strtoupper($row_detail->jenis_sales_program); ?></td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black;'><?= formatTanggal($row_detail->periode_awal); ?></td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black;'><?= formatTanggal($row_detail->periode_akhir); ?></td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black;'><?= $row_detail->tipe_ahm; ?></td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black;'><?= number_format($total_cash); ?></td>
                                            <td style='padding: 3px 1px 3px 5px; border: 1px solid black;'><?= number_format($total_kredit); ?></td>
                                        </tr>
                                        <?php $index++; endforeach; ?>
                                    </table>
                                    <table style='margin-top: 10px;'>
                                        <tr>
                                            <td><span style='font-weight: bold'>Seluruh kontribusi di atas dalam bentuk rupiah dan sudah termasuk ppn sebesar 11%</span></td>
                                        </tr>
                                    </table>
                                    <table style='margin-top: 10px;'>
                                        <tr>
                                            <td>Demikian informasi ini kami sampaikan.</td>
                                        </tr>
                                    </table>
                                    <table style='margin-top: 10px; margin_bottom:50px;'>
                                        <tr>
                                            <td>Regards,</td>
                                        </tr>
                                    </table>
                                    <table style='margin-top:10px;'>
                                        <tr>
                                            <td colspan = "2">NMS System</td>
                                        </tr>
                                        <tr>
                                            <td>Note:</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>- Ini adalah email otomatis, mohon untuk tidak me-reply ke alamat ini.</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>- Detail petunjuk pelaksanaan dapat dilihat dalam lampiran file dokumennya.</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>- Untuk lampiran file dokumen dapat di download pada aplikasi SEEDS Dealer masing-masing.</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
<?php $this->load->view('email/footer'); ?>
