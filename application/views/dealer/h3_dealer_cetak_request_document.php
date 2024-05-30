<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?= $request->id_booking ?></title>
        <style>
            .table {
                width: 100%;
                max-width: 100%;
                border-collapse: collapse;
                /*border-collapse: separate;*/
            }

            .table-bordered td{
                border: 1px solid black;
            }

            @page {
                sheet-size: 210mm 297mm;
                 margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 1cm;
                margin-top: 1cm;
            }
            .outer-border{
                border: 2px solid black;
                width: 100%;
                padding: 4px;
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
                padding-top: 67px;
                width: 100%;
                display: block;
                text-align: center;
            }

            span.judul-dokumen{
                font-size: 20px;
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
                <div class="kop-child kop-ahm">
                    <img style='margin-top: 20px;' src="<?= base_url('assets/panel/icon/logo-ahm.png') ?>" alt="">
                    <!-- <div>
                        <span class='ahm'>AHM</span>
                    </div>
                    <div>
                        <span class="ahm-lengkap">PT. Astra Honda Motor</span>
                    </div> -->
                </div>
                <div class="kop-child kop-judul-dokumen">
                    <div>
                        <span class='judul-dokumen'>HOTLINE ORDER</span>
                    </div>
                </div>
                <div class="kop-child kop-resend">
                    <?php for ($i=0; $i < 3; $i++): ?>
                    <table class='table mb-10'>
                        <tr>
                            <td class='box-resend'></td>
                            <td style='padding-left: 10px; width: 80px;'>Resend I :</td>
                            <td></td>
                            <td class='slash'>/</td>
                            <td></td>
                            <td class='slash'>/</td>
                            <td></td>
                        </tr>
                    </table>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="konten-dokumen">
                <table class="table table-bordered mt-10">
                    <tr>
                        <td class='bg-gray text-bold' colspan='2' width='100%'>Diisi oleh Jaringan Resmi AHM</td>
                    </tr>
                    <tr>
                        <td class='text-bold' width='54%'>Data Jaringan</td>
                        <td class='text-bold' width='46%'>Data Konsumen</td>
                    </tr>
                </table>
                <table class="table table-bordered">
                    <tr>
                        <td style='font-size: 10px;' width='24%'>Nama Jaringan</td>
                        <td style='font-size: 10px;' width='30%'><?= $request_document->nama_jaringan ?></td>
                        <td style='font-size: 10px;' width='23%'>Nama</td>
                        <td style='font-size: 10px;' width='23%'><?= $request_document->nama_customer ?></td>
                    </tr>
                    <tr>
                        <td style='font-size: 10px; vertical-align:top;' rowspan='2' width='24%'>Alamat</td>
                        <td style='font-size: 10px; vertical-align:top;' rowspan='2' width='30%'><?= $request_document->alamat_jaringan ?></td>
                        <td style='font-size: 10px; vertical-align:top;' width='23%' height='50px;'>Alamat</td>
                        <td style='font-size: 10px; vertical-align:top;' width='23%' height='50px;'><?= $request_document->alamat_customer ?></td>
                    </tr>
                    <tr>
                        <td style='font-size: 10px;' width='23%'>No. Telpon</td>
                        <td style='font-size: 10px;' width='23%'><?= $request_document->no_telp_customer ?></td>
                    </tr>
                </table>
                <style>
                    .menginap-container{
                        /* background-color: red; */
                    }

                    .box-menginap{
                        width: 15px;
                        height: 15px;
                        display: block;
                        border: 1px solid black;
                    }

                    .checkbox{
                        float: left;
                        width: 20px;
                        /* background-color: blue; */
                        display: block;
                    }
                </style>
                <table class="table table-bordered">
                    <tr>
                        <td style='font-size: 10px;' width='24%'>Telp</td>
                        <td style='font-size: 10px;' width='30%'><?= $request_document->no_telp_jaringan ?></td>
                        <td width='13%' style='border-right: 0xp; font-size: 10px;'>Data S/M</td>
                        <td width='23%' style='border-left: 0px; font-size: 10px;'></td>
                        <td rowspan='4' width='10%'>
                            <div class="menginap-container">
                                <div class="row">
                                    <div class='checkbox'>
                                        Menginap
                                    </div>
                                </div>
                                <div class="row">
                                    <div class='checkbox'>
                                        <?php if($request_document->vor == 1): ?>
                                        <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='15' height='15'>
                                        <?php else: ?>
                                        <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='15' height='15'>
                                        <?php endif; ?>
                                        Ya
                                    </div>
                                </div>
                                <div class="row">
                                    <div class='checkbox'>
                                    <?php if($request_document->vor == 0): ?>
                                        <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='15' height='15'>
                                        <?php else: ?>
                                        <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='15' height='15'>
                                        <?php endif; ?>
                                        Tidak
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style='font-size: 10px;' width='24%'>Faximile</td>
                        <td style='font-size: 10px;' width='30%'><?= $request_document->fax_jaringan ?></td>
                        <td style='border-left: 0px; border-right: 0px; font-size: 10px;' width='13%'>Type / No. Pol</td>
                        <td style='border-left: 0px; border-right: 0px; font-size: 10px;' width='23%'>: <?= $request_document->no_polisi ?></td>
                    </tr>
                    <tr>
                        <td style='font-size: 10px;' width='24%'>No. Order</td>
                        <td style='font-size: 10px;' width='30%'><?= $request_document->nomor_order ?></td>
                        <td style='border-left: 0px; border-right: 0px; font-size: 10px;' width='13%'>Tahun Perakitan</td>
                        <td style='border-left: 0px; border-right: 0px; font-size: 10px;' width='23%'>: <?= $request_document->tahun_perakitan ?></td>
                    </tr>
                    <tr>
                        <td style='font-size: 10px;' width='24%'>Tanggal Order</td>
                        <td style='font-size: 10px;' width='30%'><?= $request_document->tanggal_order ?></td>
                        <td style='border-left: 0px; border-right: 0px; font-size: 10px;' width='13%'>No. Rangka</td>
                        <td style='border-left: 0px; border-right: 0px; font-size: 10px;' width='23%'>: <?= $request_document->no_rangka ?></td>
                    </tr>
                </table>
                <table class="table table-bordered">
                    <tr>
                        <td style='font-size: 10px;' width='24%'>No. Claim C2</td>
                        <td style='font-size: 10px;' width='30%'><?= $request_document->nomor_claim_c2 ?></td>
                        <td style='border-left: 0px; border-right: 0px; font-size: 10px;' width='13%'>No. Mesin</td>
                        <td style='border-left: 0px; font-size: 10px;' width='33%'>: <?= $request_document->no_mesin ?></td>
                    </tr>
                </table>
                <table class='table table-bordered'>
                    <tr>
                        <td width='3%' class="bg-gray text-bold text-center" style='font-size: 10px; padding: 0px;'>No</td>
                        <td class="bg-gray text-bold" width='21%' style='font-size: 10px;'>PART NO.</td>
                        <td class="bg-gray text-bold" width='30%' style='font-size: 10px;'>DESKRIPSI</td>
                        <td class="bg-gray text-bold text-center" width='8%' style='font-size: 10px;'>QTY</td>
                        <td class="bg-gray text-bold text-center" width='15%' style='font-size: 10px;' colspan='2'>HARGA @</td>
                        <td class="bg-gray text-bold text-center" width='23%' style='font-size: 10px;' colspan='2'>JUMLAH</td>
                    </tr>
                    <?php 
                    $parts_length = count($parts);
                    for ($i=1; $i <= 20; $i++): ?>
                    <?php 
                        if($i <= $parts_length):
                            $part = $parts[$i-1];
                    ?>
                    <tr>
                        <td width='3%' style='font-size: 10px; padding:0px' class='text-center'><?= $i ?></td>
                        <td width='21%' style='font-size: 10px;'><?= substr($part['id_part'], 0, 16) ?></td>
                        <td width='30%' style='font-size: 10px;'><?= substr($part['nama_part'], 0, 30) ?></td>
                        <td width='8%' style='font-size: 10px;' class='text-center'><?= $part['kuantitas'] ?></td>
                        <td width='3%' style='font-size: 10px; border-right: 0px;'>Rp</td>
                        <td width='12%' style='font-size: 10px; text-align: right; border-left: 0px;'><?= $part['harga'] ?></td>
                        <td width='3%' style='font-size: 10px; border-right: 0px;'>Rp</td>
                        <td width='20%' style='font-size: 10px; text-align: right; border-left: 0px;'><?= $part['amount'] ?></td>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td style='font-size: 10px; padding-left:0px;' width='3%' class='text-center'><?= $i ?></td>
                        <td style='font-size: 10px;' width='21%'></td>
                        <td style='font-size: 10px;' width='30%'></td>
                        <td style='font-size: 10px;' width='8%'></td>
                        <td style='font-size: 10px; border-right: 0px;' width='3%'></td>
                        <td style='font-size: 10px; border-left: 0px;' width='12%'></td>
                        <td style='font-size: 10px; border-right: 0px;' width='3%'></td>
                        <td style='font-size: 10px; border-left: 0px;' width='20%'></td>
                    </tr>
                    <?php endif; ?>
                    <?php endfor; ?>
                </table>
                <table class='table' style='border: 1px solid black;'>
                    <tr>
                        <td width='54%' rowspan='5' style='vertical-align:top; border-right: 1px solid black; font-size: 10px;'><span class="text-bold">Keterangan :</span> <?= $request_document->keterangan_tambahan ?></td>
                    </tr>
                    <tr>
                        <td width='20%' class='text-bold' style='border: 0px; font-size: 10px;'>TOTAL BAYAR</td>
                        <td width='3%' style='font-size: 10px;'>:</td>
                        <td width='5%' style='font-size: 10px; border-bottom: 1px solid black;' class='text-bold'>Rp</td>
                        <td width='18%' class='text-bold' style='border-left: 0px; border-top: 0px; border-bottom: 1px solid black; font-size: 10px; text-align: right;'><?= $request_document->total_pembayaran ?></td>
                    </tr>
                    <tr>
                        <td width='20%' class='text-italic' style='border: 0px; font-size: 10px;'>UANG MUKA / JAMINAN</td>
                        <td width='3%' style='font-size:10px;'>:</td>
                        <td width='5%' style='font-size: 10px; border-bottom: 1px solid black;' class='text-bold'>Rp</td>
                        <td width='18%' class='text-bold' style='border-left: 0px; border-top: 0px; border-bottom: 1px solid black; font-size: 10px; text-align:right;'><?= $request_document->uang_muka_formatted ?></td>
                    </tr>
                    <tr style='padding-bottom: 30px;'>
                        <td width='20%' class='text-bold' style='border: 0px; font-size: 10px;'>SISA PEMBAYARAN</td>
                        <td width='3%' style=' font-size: 10px;'>:</td>
                        <td width='5%' style='font-size: 10px; border-bottom: 1px solid black;' class='text-bold'>Rp</td>
                        <td width='18%' class='text-bold' style='border-left: 0px; border-top: 0px; border-bottom: 1px solid black; font-size: 10px; text-align: right;'><?= $request_document->sisa_pembayaran ?></td>
                    </tr>
                    <tr>
                        <td colspan='3'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td style='font-size: 9px; border-right: 1px solid black; border-top: 1px solid black;' class='text-bold'>PERKIRAAN KEDATANGAN PARTS : <?= $request_document->perkiraan_hari ?></td>
                        <td colspan='4' class='text-italic' style='font-size: 9px;border-top: 0px;'>Pembayaran DP atau Pelunasan menggunakan tanda terima (Kuintansi)</td>
                    </tr>
                </table>
                <table class="table" style='border: 1px solid black;'>
                    <tr>
                        <td colspan='2' width='54%' style='background-color: black; color: white; font-weight: bold; font-size: 10px;'>CATATAN</td>
                        <td colspan='2' width='23%' class='text-center text-bold' style='font-size: 10px; border-right: 1px solid black; border-bottom: 1px solid black;'>Dibuat oleh,</td>
                        <td colspan='2' width='23%' class='text-center text-bold' style='font-size: 10px; border-bottom: 1px solid black;'>Disetujui oleh,</td>
                    </tr>
                    <tr>
                        <td width='2%' style='border-right: 0px; border-bottom: 0px; border-top:0px;'>&#9679;</td>
                        <td width='52%' style='font-size: 9px; border-left: 0px; border-bottom: 0px; border-top:0px; border-right: 1px solid black;'>Pada saat order wajib melampirkan copy KTP & STNK</td>
                        <td width='23%' colspan='2' rowspan='5' style='border-right: 1px solid black;'>
                            &nbsp;
                        </td>
                        <td width='23%' colspan='2' rowspan='5'>
                            &nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td width='2%' style='border-right: 0px; border-bottom: 0px; border-top:0px;'>&#9679;</td>
                        <td width='52%' style='font-size: 9px; border-left: 0px; border-bottom: 0px; border-top:0px; border-right: 1px solid black;'>Tidak berlaku pembatalan order untuk Hotline Order</td>
                    </tr>
                    <tr>
                        <td width='2%' style='vertical-align: text-top;border-right: 0px; border-bottom: 0px; border-top:0px;'>&#9679;</td>
                        <td width='52%' style='font-size: 9px;border-right: 0px; border-bottom: 0px; border-top:0px; border-left: 0px; border-right: 1px solid black;'>Apabila terdapat perubahan harga pada saat penyerahan barang, maka harga yang digunakan adalah harga yang terbaru.</td>
                    </tr>
                    <tr>
                        <td width='2%' style='border-right: 0px; border-bottom: 0px; border-top:0px;'>&#9679;</td>
                        <td width='52%' style='font-size: 9px;border-right: 0px; border-bottom: 0px; border-top:0px; border-left: 0px; border-right: 1px solid black;'>Pada saat pengambilan barang harap form ini dan tanda terima pembayaran dibawa</td>
                    </tr>
                    <tr>
                        <td width='2%' style='border-right: 0px; border-bottom: 0px; border-top:0px;'>&#9679;</td>
                        <td width='52%' style='font-size: 9px;border-right: 0px; border-bottom: 0px; border-top:0px; border-left: 0px; border-right: 1px solid black;'>Wajib ditulis dengan nama serta alamat yang jelas.</td>
                    </tr>
                    <tr>
                        <td width='2%' style='border-right: 0px;border-top:0px;'>&#9679;</td>
                        <td width='52%' style='font-size: 9px;border-right: 0px; border-top:0px; border-left: 0px; border-right: 1px solid black;'>Penyediaan Suku Cadang hanya dalam masa 7 tahun setelah type motor discontinue</td>
                        <td width='11.5%' class='text-bold' style='font-size: 10px; border-right: 1px solid black; border-top: 1px solid black;'>AHASS</td>
                        <td width='11.5%' class='text-bold' style='font-size: 10px; border-right: 1px solid black; border-top: 1px solid black;'>Tgl: </td>
                        <td width='11.5%' class='text-bold' style='font-size: 10px; border-right: 1px solid black; border-top: 1px solid black;'>Konsumen</td>
                        <td width='11.5%' class='text-bold' style='font-size: 10px; border-right: 1px solid black; border-top: 1px solid black;'>Tgl: </td>
                    </tr>
                </table>
                <span class='text-italic' style='font-size: 10px;'>Catatan: Lembar Putih: Konsumen, Lembar Pink: Arsip AHASS, Lembar Kuning: Main Dealer</span>
            </div>
        </div>
    </body>
</html>