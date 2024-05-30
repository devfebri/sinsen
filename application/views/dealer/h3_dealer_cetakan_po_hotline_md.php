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

            .table-bordered td{
                border: 1px solid black;
            }

            @page {
                sheet-size: 210mm 297mm;
                 margin-left: 0.5cm;
                margin-right: 0.5cm;
                margin-bottom: 1cm;
                margin-top: 1cm;
            }
            /* .outer-border{
                border: 2px solid black;
                width: 100%;
                padding: 4px;
            } */

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
                        <td class='text-bold' width='50%'>Data Jaringan</td>
                        <td class='text-bold' width='50%'>Data Konsumen</td>
                    </tr>
                </table>
                <table class="table table-bordered">
                    <tr>
                        <td style='font-size: 10px;' width='18%'>Nama Jaringan</td>
                        <td style='font-size: 10px;' width='32%'><?= $purchase_order['nama_jaringan'] ?></td>
                        <td style='font-size: 10px;' width='23%'>Nama</td>
                        <td style='font-size: 10px;' width='27%'><?= $purchase_order['nama_customer'] ?></td>
                    </tr>
                    <tr>
                        <td style='font-size: 10px; vertical-align:top;' rowspan='2' width='18%'>Alamat</td>
                        <td style='font-size: 10px; vertical-align:top;' rowspan='2' width='32%'><?= $purchase_order['alamat_jaringan'] ?></td>
                        <td style='font-size: 10px; vertical-align:top;' width='23%' height='50px;'>Alamat</td>
                        <td style='font-size: 10px; vertical-align:top;' width='27%' height='50px;'><?= $purchase_order['alamat_customer'] ?></td>
                    </tr>
                    <tr>
                        <td style='font-size: 10px;' width='23%'>No. Telpon</td>
                        <td style='font-size: 10px;' width='27%'><?= $purchase_order['no_telp_customer'] ?></td>
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
                        <td style='font-size: 10px;' width='18%'>Telp</td>
                        <td style='font-size: 10px;' width='32%'><?= $purchase_order['no_telp_jaringan'] ?></td>
                        <td width='17%' style='border-right: 0xp; font-size: 10px;'>Data S/M</td>
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
                                        <?php if($purchase_order['vor'] == 1): ?>
                                        <img src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" width='15' height='15'>
                                        <?php else: ?>
                                        <img src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" width='15' height='15'>
                                        <?php endif; ?>
                                        Ya
                                    </div>
                                </div>
                                <div class="row">
                                    <div class='checkbox'>
                                    <?php if($purchase_order['vor'] == 0): ?>
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
                        <td style='font-size: 10px;' width='18%'>Faximile</td>
                        <td style='font-size: 10px;' width='32%'><?= $purchase_order['fax_jaringan'] ?></td>
                        <td style='border-left: 0px; border-right: 0px; font-size: 10px;' width='17%'>Type / No. Pol</td>
                        <td style='border-left: 0px; border-right: 0px; font-size: 10px;' width='23%'>: <?= $purchase_order['no_polisi'] ?></td>
                    </tr>
                    <tr>
                        <td style='font-size: 10px;' width='18%'>No. Order</td>
                        <td style='font-size: 10px;' width='32%'><?= $purchase_order['nomor_order'] ?></td>
                        <td style='border-left: 0px; border-right: 0px; font-size: 10px;' width='17%'>Tahun Perakitan</td>
                        <td style='border-left: 0px; border-right: 0px; font-size: 10px;' width='23%'>: <?= $purchase_order['tahun_perakitan'] ?></td>
                    </tr>
                    <tr>
                        <td style='font-size: 10px;' width='18%'>Tanggal Order</td>
                        <td style='font-size: 10px;' width='32%'><?= $purchase_order['tanggal_order'] ?></td>
                        <td style='border-left: 0px; border-right: 0px; font-size: 10px;' width='17%'>No. Rangka</td>
                        <td style='border-left: 0px; border-right: 0px; font-size: 10px;' width='23%'>: <?= $purchase_order['no_rangka'] ?></td>
                    </tr>
                </table>
                <table class="table table-bordered">
                    <tr>
                        <td style='font-size: 10px;' width='18%'>No. Claim C2</td>
                        <td style='font-size: 10px;' width='32%'><?= $purchase_order['nomor_claim_c2'] ?></td>
                        <td style='border-left: 0px; border-right: 0px; font-size: 10px;' width='17%'>No. Mesin</td>
                        <td style='border-left: 0px; font-size: 10px;' width='33%'>: <?= $purchase_order['no_mesin'] ?></td>
                    </tr>
                </table>
                <div style='width: 100%;'>
                    <?php 
                    $index_chunk = 0;
                    foreach(array_chunk($parts, 10) as $chunk): ?>
                    <div style='width: 50%; float: left;'>
                        <table class='table table-bordered'>
                            <tr>
                                <td width='10%' class="bg-gray text-bold text-center" style='font-size: 9px; padding: 0px;'>No</td>
                                <td width='26%' class="bg-gray text-bold" style='font-size: 9px;'>PART NO.</td>
                                <td width='44%' class="bg-gray text-bold" style='font-size: 9px;'>DESKRIPSI</td>
                                <td width='20%' class="bg-gray text-bold text-center" style='font-size: 9px;'>QTY</td>
                            </tr>
                            <?php 
                            $index = 0;
                            $parts_length = count($chunk);
                            $dummy_rows = 10 - $parts_length;
                            foreach($chunk as $part):
                            ?>
                            <tr>
                                <td width='10%' height='10px;' style='font-size: 9px; padding:0px' class='text-center'><?= 1 + $index_chunk ?></td>
                                <td width='26%' height='10px;' style='font-size: 9px;'><?= substr($part['id_part'], 0, 16) ?></td>
                                <td width='44%' height='10px;' style='font-size: 9px;'><?= substr($part['nama_part'], 0, 30) ?></td>
                                <td width='20%' height='10px;' style='font-size: 9px;' class='text-center'><?= $part['kuantitas'] ?></td>
                            </tr>
                            <?php 
                                $index++;
                                $index_chunk++;
                                endforeach; 
                            ?>
                            <?php for ($i=0; $i < $dummy_rows; $i++) : ?>
                            <tr>
                                <td width='10%' height='10px;' style='font-size: 9px; padding:0px' class='text-center'><?= $index_chunk + ($i + 1) ?></td>
                                <td width='26%' height='10px;' style='font-size: 9px;'><span style='visibility: hidden;'>--</span></td>
                                <td width='44%' height='10px;' style='font-size: 9px;'></td>
                                <td width='20%' height='10px;' style='font-size: 9px;' class='text-center'></td>
                            </tr>
                            <?php endfor; ?>
                        </table>
                    </div>
                    <?php endforeach; ?>
                    <?php if(count($parts) <= 10): ?>
                    <div style='width: 50%; float: left;'>
                        <table class='table table-bordered'>
                            <tr>
                                <td width='10%' class="bg-gray text-bold text-center" style='font-size: 9px; padding: 0px;'>No</td>
                                <td width='26%' class="bg-gray text-bold" style='font-size: 9px;'>PART NO.</td>
                                <td width='44%' class="bg-gray text-bold" style='font-size: 9px;'>DESKRIPSI</td>
                                <td width='20%' class="bg-gray text-bold text-center" style='font-size: 9px;'>QTY</td>
                            </tr>
                            <?php for ($i=0; $i < 10; $i++) : ?>
                            <tr>
                                <td width='10%' height='10px;' style='font-size: 9px; padding:0px' class='text-center'><?= 10 + $i ?></td>
                                <td width='26%' height='10px;' style='font-size: 9px;'><span style='visibility: hidden;'>--</span></td>
                                <td width='44%' height='10px;' style='font-size: 9px;'></td>
                                <td width='20%' height='10px;' style='font-size: 9px;' class='text-center'></td>
                            </tr>
                            <?php endfor; ?>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
                <table class='table' style='border: 1px solid black;'>
                    <tr>
                        <td width='68%' rowspan='6' style='vertical-align:top; border-right: 1px solid black; font-size: 10px;'><span class="text-bold">Keterangan :</span> <?= $purchase_order['keterangan_tambahan'] ?></td>
                    </tr>
                    <tr>
                        <td width='32%' colspan='2' style='font-size: 9px; border-right: 1px solid black; border-top: 1px solid black; text-align: center;' class='text-bold'>CAP & TANDA TANGAN Jaringan</td>
                    </tr>
                    <tr>
                        <td width='32%' colspan='2'><span style='visibility: hidden;'>--</span></td>
                    </tr>
                    <tr>
                        <td width='32%' colspan='2'><span style='visibility: hidden;'>--</span></td>
                    </tr>
                    <tr>
                        <td widht='16%' style='text-align: left; padding-left: 50px;'>(</td>
                        <td widht='16%' style='text-align: right; padding-right: 50px;'>)</td>
                    </tr>
                    <tr>
                        <td width='32%' colspan='2' style='font-size: 9px; text-align: center;' class='text-bold'>
                            <span style='visibility: hidden;'>NAMA JELAS</span>
                        </td>
                    </tr>
                </table>
                <table class="table" style='margin-top: 15px;'>
                    <tr>
                        <td colspan='4' class='bg-gray' style='border: 1px solid black; font-size: 12px; font-weight: bold;'>Diisi oleh AHM</td>
                    </tr>
                    <tr>
                        <td width='18%' style='font-size: 10px; border: 1px solid black;'>Tanggal Terima</td>
                        <td width='32%' style='font-size: 10px; border: 1px solid black;'></td>
                        <td width='5%' style='font-size: 10px;'>ETD :</td>
                        <td width='45%' style='font-size: 10px; border: 1px solid black; border-left: 0;'><span style='visibility: hidden;'>-</span></td>
                    </tr>
                    <tr>
                        <td width='18%' style='font-size: 10px; border: 1px solid black;'>No. PO Cust</td>
                        <td width='32%' style='font-size: 10px; border: 1px solid black;'></td>
                        <td width='5%' colspan='2' style='font-size: 10px; border: 1px solid black;'><span style='visibility: hidden;'>--</span></td>
                    </tr>
                </table>
                <table class="table" style='margin-top: 10px;'>
                    <tr>
                        <td width='10%' style='font-size: 10px; text-align: right;'>*CATATAN:</td>
                        <td width='90%' style='font-size: 10px;'>- Penyediaan suku cadang hanya dalam masa 7 tahun setelah type motor di-discontinue (tidak diproduksi)</td>
                    </tr>
                    <tr>
                        <td width='10%' style='font-size: 10px;'><span style='visibility: hidden'>-</span></td>
                        <td width='90%' style='font-size: 10px;'>- Harga yang berlaku adalah harga pada waktu penyerahan barang</td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>