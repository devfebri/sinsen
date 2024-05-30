<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>FORM CLAIM C3 PARTS</title>
    <style>
        @media print {
            @page {
                sheet-size: 210mm 297mm;
                 margin-left: 0.5cm;
                margin-right: 0.5cm;
                margin-bottom: 0.5cm;
                margin-top: 0.5cm;
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

            .align-top{
                vertical-align: top;
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
            <td width='20%' style='border-bottom: 1px solid black; border-left: 1px solid black; border-top: 1px solid black; padding: 25px 0;'>
                <img style='width: 150px;' src="<?= base_url('assets/panel/icon/logo-ahm.png') ?>" alt="">
            </td>
            <td width='60%' class='text-center' style='border-bottom: 1px solid black; border-top: 1px solid black;'>
                <span class='text-bold' style='font-size: 20px;'>FORM CLAIM C3 PARTS</span>
            </td>
            <td width='20%' class='text-right' style='border-bottom: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; padding-right: 10px;'>
                <span>HAL: 1 / 1</span>
            </td>
        </tr>
    </table>
    <div width='100%' style='margin-top: 10px;'>
        <div style='width: 53%; float: left;'>
            <table class="table table-bordered">
                <tr>
                    <td colspan='2' style='font-size: 14px; padding-bottom: 10px; font-weight: bold;'>DATA KORESPONDENSI</td>
                </tr>
                <tr>
                    <td width='40%'>Nama Main Dealer</td>
                    <td width='60%'>PT SINAR SENTOSA PRIMATAMA</td>
                </tr>
                <tr>
                    <td width='40%'>Alamat</td>
                    <td width='60%'>JL. KOLONEL ABUNJANI NO. 009 RT 00 36129</td>
                </tr>
                <tr>
                    <td width='40%'>Telepon</td>
                    <td width='60%'>0741-22760</td>
                </tr>
                <tr>
                    <td width='40%'>Alamat Email</td>
                    <td width='60%'></td>
                </tr>
            </table>
        </div>
        <div style='width: 45%; float: left; margin-left: 2%;'>
            <table class="table table-bordered">
                <tr>
                    <td colspan='2' style='font-size: 14px; padding-bottom: 10px; font-weight: bold;'>DATA CLAIM C3</td>
                </tr>
                <tr>
                    <td width='40%'>No. Claim</td>
                    <td width='60%'><?= $header['id_claim_part_ahass'] ?></td>
                </tr>
                <tr>
                    <td width='40%'>Tgl/Bln/Thn</td>
                    <td width='60%'><?= $header['created_at'] ?></td>
                </tr>
                <tr>
                    <td width='40%'>No. Packing Sheet</td>
                    <td width='60%'><?= $header['packing_sheet_number'] ?></td>
                </tr>
                <tr>
                    <td width='40%'>No. Faktur AHM</td>
                    <td width='60%'><?= $header['invoice_number'] ? $header['invoice_number'] : '-' ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div style='width: 100%; padding: 6px 0; margin-top: 5px; border: 1px solid black;' class='text-center'>
        <span style='font-size: 13px; font-weight: bold;'>KATEGORI CLAIM C3</span>
    </div>
    <div style='width: 100%; margin-top: 5px;'>
        <div style='width: 49%; float: left; margin-right: 1%;'>
            <table class="table table-bordered">
                <tr>
                    <td width='80%' style='font-weight: bold;'>CLAIM KUALITAS</td>
                    <td width='20%' style='font-weight: bold; text-align: center;'>KODE</td>
                </tr>
                <tr>
                    <td>KARAT/KOROSI</td>
                    <td style='text-align: center;'>A</td>
                </tr>
                <tr>
                    <td>PERMUKAAN CACAT (JAMUR, GORES, DLL)</td>
                    <td style='text-align: center;'>B</td>
                </tr>
                <tr>
                    <td>BENGKOK/BERUBAH BENTUK</td>
                    <td style='text-align: center;'>C</td>
                </tr>
                <tr>
                    <td>PATAH/PECAH/SOBEK</td>
                    <td style='text-align: center;'>D</td>
                </tr>
                <tr>
                    <td>SUB PART TIDAK LENGKAP</td>
                    <td style='text-align: center;'>E</td>
                </tr>
                <tr>
                    <td>ARUS MATI (ELECTRIC)</td>
                    <td style='text-align: center;'>F</td>
                </tr>
                <tr>
                    <td>BOCOR (LIQUID)</td>
                    <td style='text-align: center;'>G</td>
                </tr>
                <tr>
                    <td>DIMENSI TIDAK SESUAI SPEK</td>
                    <td style='text-align: center;'>H</td>
                </tr>
                <tr>
                    <td>................</td>
                    <td style='text-align: center;'>I</td>
                </tr>
            </table>
        </div>
        <div style='width: 50%; float: left; margin-left: 1%;'>
            <table class="table table-bordered">
                <tr>
                    <td width='80%' style='font-weight: bold;'>CLAIM NON KUALITAS</td>
                    <td width='20%' style='font-weight: bold; text-align: center;'>KODE</td>
                </tr>
                <tr>
                    <td>JUMLAH PART YANG KURANG</td>
                    <td style='text-align: center;'>K</td>
                </tr>
                <tr>
                    <td>JUMLAH PART YANG LEBIH</td>
                    <td style='text-align: center;'>L</td>
                </tr>
                <tr>
                    <td>FISIK PART BEDA</td>
                    <td style='text-align: center;'>M</td>
                </tr>
                <tr>
                    <td>LABEL BEDA</td>
                    <td style='text-align: center;'>N</td>
                </tr>
                <tr>
                    <td>PACKAGING RUSAK</td>
                    <td style='text-align: center;'>O</td>
                </tr>
                <tr>
                    <td>TIDAK ORDER</td>
                    <td style='text-align: center;'>P</td>
                </tr>
                <tr>
                    <td>...............</td>
                    <td style='text-align: center;'>Q</td>
                </tr>
            </table>
        </div>
    </div>
    <div style='width: 100%; border: 1px solid black; padding: 3px 6px; margin-top: 5px;'>
        <div style='width: 100%'>
            <span>DOKUMEN PENDUKUNG YANG WAJIB DISERTAKAN:</span>
        </div>
        <div style='width: 100%; padding-bottom: 5px;'>
            <div style='width: 25%; float: left;'>
                <?php if($header['dokumen_packing_sheet'] == 1): ?>
                <img style='width: 11px;' src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" alt=""> PACKING SHEET
                <?php else: ?>
                <img style='width: 11px;' src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" alt=""> PACKING SHEET
                <?php endif; ?>
            </div>
            <div style='width: 25%; float: left;'>
                <?php if($header['dokumen_packing_ticket'] == 1): ?>
                <img style='width: 11px;' src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" alt=""> PACKING TIKET
                <?php else: ?>
                <img style='width: 11px;' src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" alt=""> PACKING TIKET
                <?php endif; ?>
            </div>
            <div style='width: 50%; float: left;'>
                <?php if($header['dokumen_foto_bukti'] == 1): ?>
                <img style='width: 11px;' src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" alt=""> FOTO BUKTI (PARTS/KARDUS/LABEL/DLL)
                <?php else: ?>
                <img style='width: 11px;' src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" alt=""> FOTO BUKTI (PARTS/KARDUS/LABEL/DLL)
                <?php endif; ?>
            </div>
        </div>
        <div style='width: 100%; padding-bottom: 5px;'>
            <div style='width: 25%; float: left;'>
                <?php if($header['dokumen_shipping_list'] == 1): ?>
                <img style='width: 11px;' src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" alt=""> SHIPPING LIST
                <?php else: ?>
                <img style='width: 11px;' src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" alt=""> SHIPPING LIST
                <?php endif; ?>
            </div>
            <div style='width: 25%; float: left;'>
                <?php if($header['dokumen_nomor_karton'] == 1): ?>
                <img style='width: 11px;' src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" alt=""> NOMOR KARTON
                <?php else: ?>
                <img style='width: 11px;' src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" alt=""> NOMOR KARTON
                <?php endif; ?>
            </div>
            <div style='width: 50%; float: left;'>
                <?php if($header['dokumen_lain'] != ''): ?>
                <img style='width: 11px;' src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" alt=""> <?= $header['dokumen_lain'] ?>
                <?php else: ?>
                <img style='width: 11px;' src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" alt=""> ......................
                <?php endif; ?>
            </div>
        </div>
        <div style='width: 100%; padding-bottom: 5px;'>
            <div style='width: 25%; float: left;'>
                <?php if($header['dokumen_label_timbangan'] == 1): ?>
                <img style='width: 11px;' src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" alt=""> LABEL TIMBANGAN
                <?php else: ?>
                <img style='width: 11px;' src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" alt=""> LABEL TIMBANGAN
                <?php endif; ?>
            </div>
            <div style='width: 75%; float: left;'>
                <?php if($header['dokumen_label_karton'] == 1): ?>
                <img style='width: 11px;' src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" alt=""> LABEL KARTON
                <?php else: ?>
                <img style='width: 11px;' src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" alt=""> LABEL KARTON
                <?php endif; ?>
            </div>
        </div>
    </div>
    <table class="table table-bordered" style='margin-top: 10px;'>
        <tr rowspan='3'>
            <td rowspan='3' width='30px;'>
                No.
            </td>
            <td class='text-center' width='180px;'>
                Part No.
            </td>
            <td rowspan='3'>
                Qty Part Karton
            </td>
            <td rowspan='3'>
                Qty Part Di Claim
            </td>
            <td rowspan='3'>
                Qty Part di kirim ke AHM
            </td>
            <td rowspan='3'>
                Kode Claim
            </td>
            <td rowspan='3'>
                Keterangan
            </td>
            <td rowspan='3'>
                Keputusan
            </td>
        </tr>
        <tr>
            <td class='text-center' width='180px;'>Nama Part</td>
        </tr>
        <tr>
            <td class='text-center' width='180px;'>No. Karton</td>
        </tr>

        <?php 
        $index = 1;
        foreach($parts as $part): 
        ?>
        <tr>
            <td rowspan='2'><?= $index ?>.</td>
            <td rowspan='2'>
                <div style='width: 100%;'><?= $part['id_part'] ?></div>
                <div style='width: 100%;'><?= $part['nama_part'] ?></div>
                <div style='width: 100%'><?= $header['nomor_karton'] ?></div>
            </td>
            <td rowspan='2' class='text-right align-top'><?= $part['qty_ps'] ?></td>
            <td rowspan='2' class='text-right align-top'><?= $part['qty_part_diclaim'] ?></td>
            <td rowspan='2' class='text-right align-top'><?= $part['qty_part_dikirim_ke_md'] ?></td>
            <td rowspan='2' class='align-top'><?= $part['kode_claim'] ?></td>
            <td rowspan='2' class='align-top'><?= $part['nama_claim'] ?></td>
            <td>
                <div style='width: 100%;'>
                    <?php if($part['keputusan'] == 'Terima'): ?>
                    <img style='width: 11px;' src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" alt=""> Terima
                    <?php else: ?>
                    <img style='width: 11px;' src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" alt=""> Terima
                    <?php endif; ?>
                </div>
                <div style='width: 100%;'>
                    <?php if($part['keputusan'] == 'Tolak'): ?>
                    <img style='width: 11px;' src="<?= base_url('assets/panel/icon/checked-checkbox.png') ?>" alt=""> Tolak
                    <?php else: ?>
                    <img style='width: 11px;' src="<?= base_url('assets/panel/icon/unchecked-checkbox.png') ?>" alt=""> Tolak
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div style='width: 100%;'>Ket: </div>
            </td>
        </tr>
        <?php 
        $index++;
        endforeach; 
        ?>
    </table>
    <div style='width: 752px; position: absolute; bottom: 0px; margin-bottom: 20px;'>
        <table class="table" style='width: 100%;'>
            <tr>
                <td style='border-left: 1px solid black; border-top: 1px solid black;' width='200px;'>Cap & Tanda Tangan</td>
                <td colspan='4' style='border: 1px solid black; color: white; background-color: black;'>Diisi Oleh : PT. ASTRA HONDA MOTOR</td>
            </tr>
            <tr>
                <td style='border-left: 1px solid black;'>Main Dealer</td>
                <td class='text-center' style='border: 1px solid black;' width='15%;'>TANGGAL</td>
                <td class='text-center' style='border: 1px solid black;' width='15%'>NAMA</td>
                <td class='text-center' style='border: 1px solid black;' width='15%'>PROSES & KETERANGAN</td>
                <td class='text-center' style='border: 1px solid black;' width='15%'>TANDA TANGAN</td>
            </tr>
            <tr>
                <td rowspan='3' style='border-left: 1px solid black; border-bottom: 1px solid black;'></td>
                <td style='border: 1px solid black; padding: 10px 0;'></td>
                <td style='border: 1px solid black; padding: 10px 0; padding-left: 3px solid black;'>CLAIM C3 OPERATOR</td>
                <td style='border: 1px solid black; padding: 10px 0; padding-left: 3px solid black;'>DITERIMA & DIPERIKSA</td>
                <td style='border: 1px solid black; padding: 10px 0; padding-left: 3px solid black;'></td>
            </tr>
            <tr>
                <td style='border: 1px solid black; padding: 10px 0;'></td>
                <td style='border: 1px solid black; padding: 10px 0; padding-left: 3px solid black;'>CLAIM C3 ADMIN</td>
                <td style='border: 1px solid black; padding: 10px 0; padding-left: 3px solid black;'>DI ENTRY</td>
                <td style='border: 1px solid black; padding: 10px 0; padding-left: 3px solid black;'></td>
            </tr>
            <tr>
                <td style='border: 1px solid black; padding: 10px 0;'></td>
                <td style='border: 1px solid black; padding: 10px 0; padding-left: 3px solid black;'>CLAIM C3 ANALYST</td>
                <td style='border: 1px solid black; padding: 10px 0; padding-left: 3px solid black;'>DI JAWAB</td>
                <td style='border: 1px solid black; padding: 10px 0; padding-left: 3px solid black;'></td>
            </tr>
        </table>
    </div>
</body>
</html>