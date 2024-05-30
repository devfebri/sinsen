<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?= $purchase_order['po_id'] ?></title>
        <style>
            .table {
                width: 100%;
                max-width: 100%;
                border-collapse: collapse;
                /*border-collapse: separate;*/
            }

            .table tr td {
                font-size: 10px;
            }

            .table-bordered td{
                border: 1px solid black;
            }

            @page {
                sheet-size: 210mm 297mm;
                margin-left: 0.5cm;
                margin-right: 0.5cm;
                margin-bottom: 1cm;
                margin-top: 0.5cm;
            }
            .outer-border{
                /* border: 2px solid black;
                width: 100%;
                padding: 4px; */
            }

            .kop-dokumen .kop-child{
                width: 33.33%;
                float: left;
            }

            .kop-ahm{
                text-align: center;
            }

            .kop-ahm div{
                width: 100%;
                display: inline-block;
            }

            span.ahm{
                font-size: 55px;
                font-weight: bold;
            }

            span.ahm-lengkap{
                font-size: 15px;
                font-weight: bold;
            }

            .kop-judul-dokumen div{
                padding-top: 20px;
                width: 100%;
                display: block;
                text-align: center;
            }

            span.judul-dokumen{
                font-size: 16px;
                font-weight: bold;
            }

            .kop-resend{
                width: 100%;
                display: block;
            }

            .box-resend{
                /* background-color: red; */
            }

            td.slash{
                width: 10px;
            }

            .mb-10{
                margin-bottom: 10px;
            }

            .mt-10{
                margin-top: 10px;
            }

            .bg-gray{
                background-color: #eee;
            }

            .konten-dokumen table td{
                padding-left: 7px;
                padding-top: 3px;
                padding-bottom: 3px;
                font-size: 12px;
            }

            .text-bold{
                font-weight: bold;
            }

            .text-center{
                text-align: center;
            }

            .text-right{
                text-align: right;
            }

            .text-italic{
                font-style: italic;
            }

            td.borderless{
                border: 0px !important;
            }

            .hidden{
                visibility: hidden;
            }
        </style>
    </head>
    <body>
        <div class='outer-border'>
            <div class="kop-dokumen">
                <div class="kop-child kop-ahm" style='text-align:left;'>
                    <img width='150px;' style='margin-top: 20px;' src="<?= base_url('assets/panel/icon/logo-ahm.png') ?>" alt="">
                </div>
                <div class="kop-child kop-judul-dokumen">
                    <div>
                        <span class='judul-dokumen'>FORM PENOMORAN ULANG</span>
                        <span class='judul-dokumen'>HOTLINE ORDER</span>
                    </div>
                </div>
            </div>
            <div class="konten-dokumen" style='margin-top: 30px;'>
                <div width='100%'>
                    <div width='50%' style='float: left;'>
                        <table class="table">
                            <tr>
                                <td class='text-bold' style='border: 1px solid black;'>Data Jaringan</td>
                            </tr>
                            <tr>
                                <td style='border-left: 1px solid black; border-right: 1px solid black;'>Nama Jaringan : <?= $purchase_order['nama_jaringan'] ?></td>
                            </tr>
                            <tr>
                                <td style='height: 74px; vertical-align: top; border-left: 1px solid black; border-right: 1px solid black;'>Alamat : <?= $purchase_order['alamat_jaringan'] ?></td>
                            </tr>
                            <tr>
                                <td style='border-left: 1px solid black; border-right: 1px solid black;'>Telepon : <?= $purchase_order['no_telp_jaringan'] ?></td>
                            </tr>
                            <tr>
                                <td style='border-left: 1px solid black; border-right: 1px solid black;'>Faximile : <?= $purchase_order['fax_jaringan'] ?></td>
                            </tr>
                            <tr>
                                <td style='border-left: 1px solid black; border-right: 1px solid black;'>No. Order : <?= $purchase_order['po_id'] ?></td>
                            </tr>
                            <tr>
                                <td style='border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;'>Tanggal Order : <?= date('d/m/Y', strtotime($purchase_order['tanggal_order'])) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div width='50%' style='float: left;'>
                        <table class="table">
                            <tr>
                                <td class='text-bold' style='border: 1px solid black; border-left: 0;'>Data Konsumen</td>
                            </tr>
                            <tr>
                                <td style='border-right: 1px solid black;'>Nama : <?= $purchase_order['nama_customer'] ?></td>
                            </tr>
                            <tr>
                                <td style='border-right: 1px solid black;'>Alamat : <?= $purchase_order['alamat_customer'] ?></td>
                            </tr>
                            <tr>
                                <td style='border-right: 1px solid black;'>Kota dan Kode Pos : <?= $purchase_order['provinsi'] ?> - <?= $purchase_order['kode_pos'] ?></td>
                            </tr>
                            <tr>
                                <td style='border-right: 1px solid black;'>No. Telp / HP : <?= $purchase_order['no_telp_customer'] ?></td>
                            </tr>
                        </table>
                        <table class="table">
                            <tr>
                                <td style='border-bottom: 1px solid black; border-top: 1px solid black;' width='15%'>Data S/M</td>
                                <td style='font-size: 8px; text-align: right; padding-right: 8px; border-bottom: 1px solid black; border-top: 1px solid black;'>Menginap</td>
                                <td width='4%' style='font-size: 8px; padding: 0px; border-bottom: 1px solid black; border-top: 1px solid black;'>
                                    <?php if($purchase_order['vor'] == 1): ?>
                                    <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                                    <?php else: ?>
                                    <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                                    <?php endif; ?>
                                </td>
                                <td width='5%' style='font-size: 8px; text-align: left; padding: 0px; border-bottom: 1px solid black; border-top: 1px solid black;'>Ya</td>
                                <td width='4%' style='font-size: 8px; padding: 0px; border-bottom: 1px solid black; border-top: 1px solid black;'>
                                    <?php if($purchase_order['vor'] == 0): ?>
                                    <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                                    <?php else: ?>
                                    <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                                    <?php endif; ?>
                                </td>
                                <td width='7%' style='font-size: 8px; text-align: left; padding: 0px; border-bottom: 1px solid black; border-top: 1px solid black;'>Tidak</td>
                                <td style='font-size: 8px; text-align: right; padding-right: 8px; border-bottom: 1px solid black; border-top: 1px solid black;'>Job Return</td>
                                <td width='4%' style='font-size: 8px; padding: 0px; border-bottom: 1px solid black; border-top: 1px solid black;'>
                                    <?php if($purchase_order['job_return_flag'] == 1): ?>
                                    <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                                    <?php else: ?>
                                    <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                                    <?php endif; ?>
                                </td>
                                <td width='5%' style='font-size: 8px; text-align: left; padding: 0px; border-bottom: 1px solid black; border-top: 1px solid black;'>Ya</td>
                                <td width='4%' style='font-size: 8px; padding: 0px; border-bottom: 1px solid black; border-top: 1px solid black;'>
                                    <?php if($purchase_order['job_return_flag'] == 0): ?>
                                    <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                                    <?php else: ?>
                                    <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                                    <?php endif; ?>
                                </td>
                                <td width='7%' style='font-size: 8px; text-align: left; padding: 0px; border-bottom: 1px solid black; border-right: 1px solid black; border-top: 1px solid black;'>Tidak</td>
                            </tr>
                        </table>
                        <table class="table">
                            <tr>
                                <td style='border-right: 1px solid black;'>Type S/M : <?= $purchase_order['tipe_kendaraan'] ?></td>
                            </tr>
                            <tr>
                                <td style='border-right: 1px solid black;'>Tahun Perakitan : <?= $purchase_order['tahun_perakitan'] ?></td>
                            </tr>
                            <tr>
                                <td style='border-right: 1px solid black;'>No. Rangka : <?= $purchase_order['no_rangka'] ?></td>
                            </tr>
                            <tr>
                                <td style='border-right: 1px solid black; border-bottom: 1px solid black;'>No. Mesin : <?= $purchase_order['no_mesin'] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <table class='table table-bordered'>
                    <tr>
                        <td class="bg-gray text-bold text-center" width='45%' style='font-size: 10px;' colspan='2'>ORDER</td>
                        <td class="bg-gray text-bold" width='15%' style='font-size: 10px;'>PART NUMBER</td>
                        <td class="bg-gray text-bold text-center" width='40%' style='font-size: 10px;' colspan='2'>HET</td>
                    </tr>
                    <?php 
                    $index = 1;
                    foreach ($parts as $part) : ?>
                    <tr>
                        <td width='3%' style='font-size: 10px; padding:0px' class='text-center'>
                            <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                        </td>
                        <td width='42%' style='font-size: 10px;'><?= $part['nama_part'] ?></td>
                        <td width='15%' style='font-size: 10px;'><?= substr($part['id_part'], 0, 30) ?></td>
                        <td width='3%' style='font-size: 10px; border-right: 0px;'>Rp</td>
                        <td width='37%' style='font-size: 10px; border-left: 0px; text-align: right;' class='text-center'><?= number_format($part['harga_saat_dibeli'], 2, '.', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <style>
                    table.table-syarat tr td{
                        font-size: 9px;
                    }

                    table.table-syarat tr td.bordered{
                        border: 1px solid black;
                    }

                    table.table-syarat tr td.bordered-right{
                        border-right: 1px solid black;
                    }
                </style>
                <table class="table table-syarat" style='border: 1px solid black;'>
                    <tr>
                        <td width='100%' colspan='4' class='text-bold' style='text-align: center; color: white; background-color: black;'>PERSYARATAN YANG HARUS DI PENUHI</td>
                    </tr>
                    <tr>
                        <td width='3%' class='bordered' style='border-right: 0px;'>
                            <?php if($purchase_order['tipe_penomoran_ulang'] == 'claim_c1_c2'): ?>
                            <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                            <?php else: ?>
                            <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                            <?php endif; ?>
                        </td>
                        <td width='47%' class='text-bold bordered' style='border-left: 0px;'>CLAIM C1/C2</td>
                        <td width='3%' class='bordered' style='border-right: 0px;'>
                            <?php if($purchase_order['tipe_penomoran_ulang'] == 'non_claim'): ?>
                            <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                            <?php else: ?>
                            <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                            <?php endif; ?>
                        </td>
                        <td width='47%' class='text-bold bordered' style='border-left: 0px;'>NON - CLAIM</td>
                    </tr>
                    <tr>
                        <td width='3%'>
                            <?php if($purchase_order['form_warranty_claim_c2_c2'] != null or $purchase_order['form_warranty_claim_c2_c2'] != ''): ?>
                            <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                            <?php else: ?>
                            <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                            <?php endif; ?>
                        </td>
                        <td width='47%' class='bordered-right'>FORM WARRANTY CLAIM C1/C2</td>
                        <td width='3%'>
                            <?php if($purchase_order['copy_bpkb_faktur_ahm_non_claim'] == 1): ?>
                            <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                            <?php else: ?>
                            <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                            <?php endif; ?>
                        </td>
                        <td width='47%'>COPY BPKB/FAKTUR AHM</td>
                    </tr>
                    <tr>
                        <td width='3%'>&nbsp;</td>
                        <td width='47%' class='text-bold bordered-right'>
                            No. Claim : 
                            <?php if($purchase_order['form_warranty_claim_c2_c2'] != null or $purchase_order['form_warranty_claim_c2_c2'] != ''): ?>
                            <span style='text-decoration: underline;'><?= $purchase_order['form_warranty_claim_c2_c2'] ?></span>
                            <?php else: ?>
                            <span style='text-decoration: underline;'>.........</span>
                            <?php endif; ?>
                        </td>
                        <td width='3%'>
                            <?php if($purchase_order['copy_stnk_non_claim'] == 1): ?>
                            <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                            <?php else: ?>
                            <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                            <?php endif; ?>
                        </td>
                        <td width='47%'>COPY STNK</td>
                    </tr>
                    <tr>
                        <td width='3%'>
                            <?php if($purchase_order['copy_faktur_ahm_claim_c1_c2'] == 1): ?>
                            <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                            <?php else: ?>
                            <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                            <?php endif; ?>
                        </td>
                        <td width='47%' class='bordered-right'>COPY FAKTUR AHM</td>
                        <td width='3%'>
                            <?php if($purchase_order['copy_ktp_non_claim'] == 1): ?>
                            <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                            <?php else: ?>
                            <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                            <?php endif; ?>
                        </td>
                        <td width='47%'>COPY KTP</td>
                    </tr>
                    <tr>
                        <td width='3%'>
                            <?php if($purchase_order['gesekan_nomor_framebody_claim_c1_c2'] == 1): ?>
                            <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                            <?php else: ?>
                            <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                            <?php endif; ?>
                        </td>
                        <td width='47%' class='bordered-right'>GESEKAN NOMOR FRAMEBODY (RANGKA)</td>
                        <td width='3%'>
                            <?php if($purchase_order['gesekan_nomor_framebody_non_claim'] == 1): ?>
                            <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                            <?php else: ?>
                            <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                            <?php endif; ?>
                        </td>
                        <td width='47%'>GESEKAN NOMOR FRAMEBODY (RANGKA)</td>
                    </tr>
                    <tr>
                        <td width='3%'>
                            <?php if($purchase_order['gesekan_nomor_crankcase_claim_c1_c2'] == 1): ?>
                            <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                            <?php else: ?>
                            <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                            <?php endif; ?>
                        </td>
                        <td width='47%' class='bordered-right'>GESEKAN NOMOR CRANKCASE (MESIN)</td>
                        <td width='3%'>
                            <?php if($purchase_order['gesekan_nomor_crankcase_non_claim'] == 1): ?>
                            <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                            <?php else: ?>
                            <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                            <?php endif; ?>
                        </td>
                        <td width='47%'>GESEKAN NOMOR CRANKCASE (MESIN)</td>
                    </tr>
                    <tr>
                        <td width='3%'>&nbsp;</td>
                        <td width='47%' class='text-bold bordered-right'>KHUSUS UNTUK CLAIM C2</td>
                        <td width='3%'>
                            <?php if($purchase_order['potongan_no_rangka_mesin_non_claim'] == 1): ?>
                            <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                            <?php else: ?>
                            <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                            <?php endif; ?>
                        </td>
                        <td width='47%'>POTONGAN NO RANGKA/MESIN (JANGAN DIPOTONG)*</td>
                    </tr>
                    <tr>
                        <td width='3%'>
                            <?php if($purchase_order['copy_ktp_claim_c1_c2'] == 1): ?>
                            <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                            <?php else: ?>
                            <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                            <?php endif; ?>
                        </td>
                        <td width='47%' class='bordered-right'>COPY KTP</td>
                        <td width='3%'>
                            <?php if($purchase_order['surat_permohonan_penomoran_ulang_dari_kepolisian_non_claim'] == 1): ?>
                            <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                            <?php else: ?>
                            <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                            <?php endif; ?>
                        </td>
                        <td width='47%'>SURAT PERMOHONAN PENOMORAN ULANG DARI KEPOLISIAN (ASLI)</td>
                    </tr>
                    <tr>
                        <td width='3%'>
                            <?php if($purchase_order['copy_stnk_claim_c1_c2'] == 1): ?>
                            <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                            <?php else: ?>
                            <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                            <?php endif; ?>
                        </td>
                        <td width='47%' class='bordered-right'>COPY STNK</td>
                        <td width='3%'>&nbsp;</td>
                        <td width='47%' class='text-bold'>KHUSUS UNTUK KASUS NOMOR PADA RANGKA / MESIN TIDAK TERBACA</td>
                    </tr>
                    <tr>
                        <td width='3%'>&nbsp;</td>
                        <td width='47%' class='bordered-right'>&nbsp;</td>
                        <td width='3%'>
                            <?php if($purchase_order['surat_laporan_forensik_kepolisian_non_claim'] == 1): ?>
                            <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='12' height='12'>
                            <?php else: ?>
                            <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                            <?php endif; ?>
                        </td>
                        <td width='47%'>SURAT LAPORAN FORENSIK KEPOLISIAN (ASLI)</td>
                    </tr>
                </table>
                <div style='width: 100%'>
                    <div style='width: 70%; float: left;'>
                        <table class="table table-bordered">
                            <tr>
                                <td style='height: 126px; vertical-align: left;'>Keterangan : <?= $purchase_order['keterangan_tambahan'] ?></td>
                            </tr>
                        </table>
                    </div>
                    <div style='width: 30%; float: left;'>
                        <table class="table">
                            <tr>
                                <td style='border-right: 1px solid black;' colspan='2' class='text-center'>CAP & TANDA TANGAN MAIN DEALER</td>
                            </tr>
                            <tr>
                                <td style='border-right: 1px solid black;' colspan='2'><span class="hidden">-</span></td>
                            </tr>
                            <tr>
                                <td style='border-right: 1px solid black;' colspan='2'><span class="hidden">-</span></td>
                            </tr>
                            <tr>
                                <td style='border-right: 1px solid black;' colspan='2'><span class="hidden">-</span></td>
                            </tr>
                            <tr>
                                <td style='border-right: 1px solid black;' colspan='2'><span class="hidden">-</span></td>
                            </tr>
                            <tr>
                                <td class='text-center'>(</td>
                                <td style='border-right: 1px solid black;' class='text-center'>)</td>
                            </tr>
                            <tr>
                                <td style='border-bottom: 1px solid black; border-right: 1px solid black;' colspan='2' class='text-center'>NAMA JELAS</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <table class="table">
                    <tr>
                        <td style='border-left: 1px solid black; border-right: 1px solid black;' class='text-bold' colspan='2'>PERHATIAN</td>
                    </tr>
                    <tr>
                        <td style='vertical-align: top; border-left: 1px solid black;' class='text-bold'>*</td>
                        <td style='border-right: 1px solid black;' class='text-bold'>UNTUK NON CLAIM : RANGKA/MESIN DIPOTONG SETELAH DAPAT PERSETUJUAN DARI PT. AHM</td>
                    </tr>
                    <tr>
                        <td style='vertical-align: top; border-left: 1px solid black; border-bottom: 1px solid black;' class='text-bold'>*</td>
                        <td style='border-right: 1px solid black; border-bottom: 1px solid black;' class='text-bold'>PENOMORAN ULANG AKAN DAPAT DIPROSES SETELAH SEMUA PRASYARAT DOKUMEN DAN SURAT PERMOHONAN MAIN DEALER TELAH LANGKAP DITERIMA OLEH PT. AHM</td>
                    </tr>
                </table>
                <table class="table" style='margin-top: 15px;'>
                    <tr>
                        <td class='text-bold' style='border: 1px solid black;' colspan='3'>Diisi oleh Main Dealer</td>
                    </tr>
                    <tr>
                        <td style='border-left: 1px solid black; border-right: 1px solid black;' width='70%'>Nama Main Dealer : </td>
                        <td style='border-right: 1px solid black;' colspan='2' width='30%' class='text-center'>CAP & TANDA TANGAN MAIN DEALER</td>
                    </tr>
                    <tr>
                        <td style='border-left: 1px solid black; border-right: 1px solid black;' width='70%'>No. Order : </td>
                        <td style='border-right: 1px solid black;' colspan='2' width='30%' class='text-center'>
                            <span class="hidden">-</span>
                        </td>
                    </tr>
                    <tr>
                        <td style='border-left: 1px solid black; border-right: 1px solid black;' width='70%'>Tanggal Order: </td>
                        <td style='border-right: 1px solid black;' colspan='2' width='30%' class='text-center'>
                            <span class="hidden">-</span>
                        </td>
                    </tr>
                    <tr>
                        <td style='border-left: 1px solid black; border-right: 1px solid black;' width='70%'>No. Surat Permohonan Main Dealer: </td>
                        <td width='15%' class='text-center'>(</td>
                        <td style='border-right: 1px solid black;' width='15%' class='text-center'>)</td>
                    </tr>
                    <tr>
                        <td style='border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;' width='70%'>
                            <span class="hidden">-</span>
                        </td>
                        <td style='border-right: 1px solid black; border-bottom: 1px solid black;' colspan='2' width='30%' class='text-center'>
                            NAMA JELAS
                        </td>
                    </tr>
                </table>
                <table class="table">
                    <tr>
                        <td class='text-bold' colspan='2' style='border: 1px solid black; color: white; background-color: black;'>PERSYARATAN YANG HARUS DILENGKAPI OLEH MAIN DEALER:</td>
                    </tr>
                    <tr>
                        <td width='5%' style='text-align: right; border-left: 1px solid black;'>
                            <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                        </td>
                        <td style='border-right: 1px solid black;'>SURAT PERMOHONAN PENOMORAN ULANG MAIN DEALER</td>
                    </tr>
                    <tr>
                        <td width='5%' style='text-align: right; border-left: 1px solid black; border-bottom: 1px solid black;'>
                            <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='12' height='12'>
                        </td>
                        <td style='border-right: 1px solid black; border-bottom: 1px solid black;'>KELENGKAPAN PERSYARATAN YANG HARUS DIPENUHI OLEH JARINGAN</td>
                    </tr>
                </table>

                <table class="table" style='margin-top: 15px;'>
                    <tr>
                        <td style='border: 1px solid black;' class='text-bold' colspan='2'>Diisi oleh AHM (PARTS DIV)</td>
                    </tr>
                    <tr>
                        <td width='30%' style='border-left: 1px solid black; border-bottom: 1px dotted black; border-right: 1px solid black;'>Tanggal Terima</td>
                        <td width='70%' style='border-bottom: 1px dotted black; border-right: 1px solid black;'><span class="hidden">-</span></td>
                    </tr>
                    <tr>
                        <td width='30%' style='border-left: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;'>No. MEMO</td>
                        <td width='70%' style='border-bottom: 1px solid black; border-right: 1px solid black;'><span class="hidden">-</span></td>
                    </tr>
                </table>
                <table class="table" style='margin-top: 15px;'>
                    <tr>
                        <td width='10%'>*CATATAN:</td>
                        <td width='90%'>- Penyediaan suku cadang hanya dalam masa 7 tahun setelah type motor di-discontinue (tidak diproduksi)</td>
                    </tr>
                    <tr>
                        <td width='10%'><span class="hidden">-</span></td>
                        <td width='90%'>- DP (Down Payment) untuk pemesanan suku cadang melalui Hotline Order minimum 50% dari HET (Harga Eceran Tertinggi)</td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>