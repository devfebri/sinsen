<?php
    function day_in_indonesia($day_number){
        if ($day_number == 1) {
            return 'Senin';
        }else if($day_number == 2){
            return 'Selasa';
        }else if($day_number == 3){
            return 'Rabu';
        }else if($day_number == 4){
            return 'Kamis';
        }else if($day_number == 5){
            return 'Jumat';
        }else if($day_number == 6){
            return 'Sabtu';
        }else if($day_number == 7){
            return 'Minggu';
        }
        return $day_number;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Cetakan Retur Penjualan</title>
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
        <table class="table table-borderedx" style='margin: 15px 0;'>
            <tr>
                <td style='text-align:center; font-size: 14px; font-weight: bold;'>Retur Penjualan</td>
            </tr>
        </table>
        <table class="table">
            <tr>
                <td style='font-size: 12px;' width='20%'>Main Dealer</td>
                <td style='font-size: 12px;' width='45%'>: PT. SINAR SENTOSA PRIMATAMA</td>
                <td style='font-size: 12px;' width='15%'>Hal</td>
                <td style='font-size: 12px;' width='20%'>: </td>
            </tr>
            <tr>
                <td style='font-size: 12px;' width='20%'>Alamat</td>
                <td style='font-size: 12px;' width='45%'>: -</td>
                <td style='font-size: 12px;' width='15%'>Tgl & Waktu</td>
                <td style='font-size: 12px;' width='20%'>: <?= date('d/m/Y H:i', time()) ?></td>
            </tr>
        </table>
        <table class="table" style='margin-top: 15px;'>
            <tr>
                <td width="20%" style='font-size: 12px;'>Nama Customer</td>
                <td width="80%" style='font-size: 12px;'>: <?= $retur['kode_dealer_md'] ?> - <?= $retur['nama_dealer'] ?></td>
            </tr>
        </table>
        <table class="table">
            <tr>
                <td width="20%" style='font-size: 12px;'>No. Retur</td>
                <td width="30%" style='font-size: 12px;'>: <?= $retur['id_retur_penjualan'] ?></td>
                <td width="20%" style='font-size: 12px;'>Pemesan</td>
                <td width="30%" style='font-size: 12px;'>: <?= $retur['pemesan'] ?></td>
            </tr>
            <tr>
                <td width="20%" style='font-size: 12px;'>Tgl Retur</td>
                <td width="30%" style='font-size: 12px;'>: <?= Mcarbon::parse($retur['tanggal_terima_retur'])->format('d/m/Y') ?></td>
                <td width="20%" style='font-size: 12px;'>Keterangan</td>
                <td width="30%" style='font-size: 12px;'>:</td>
            </tr>
        </table>
        <style>
            table.small-table tr td{
                font-size: 11px;
            }
        </style>
        <table class="table table-bordered small-table" style='margin: 15px 0;'>
            <tr>
                <td style='font-weight: bold;' width='5%'>No.</td>
                <td style='font-weight: bold;'>Nomor Part</td>
                <td style='font-weight: bold;'>Nama Part</td>
                <td style='font-weight: bold;'>Nomor Faktur</td>
                <td style='font-weight: bold;'>Qty</td>
                <td style='font-weight: bold;'>Nilai Retur</td>
            </tr>
            <?php
                $index = 1;
                $total_nilai_retur = 0;
                $total_qty_retur = 0;
                foreach($items as $item):
            ?>
            <tr>
                <td width='6%'><?= $index ?>.</td>
                <td><?= $item['id_part'] ?></td>
                <td><?= $item['nama_part'] ?></td>
                <td><?= $item['no_faktur'] ?></td>
                <td><?= $item['qty_retur'] ?></td>
                <td>Rp <?= number_format($item['amount'], 0, ",", ".") ?></td>
            </tr>
            <?php $total_nilai_retur += $item['amount']; $total_qty_retur += $item['qty_retur']; $index++; endforeach; ?>
            <tr>
                <td colspan='4' class='text-center'>Total :</td>
                <td><?= $total_qty_retur ?></td>
                <td>Rp <?= number_format($total_nilai_retur, 0, ",", ".") ?></td>
            </tr>
        </table>
        <table class="table">
            <tr>
                <td width='20%' style='font-size: 12px;'>Total Nilai Faktur</td>
                <td style='font-size: 12px;'>: Rp <?= number_format($retur['total_nilai_faktur'], 0, ",", ".") ?></td>
            </tr>
            <tr>
                <td width='20%' style='font-size: 12px;'>Total Nilai Retur</td>
                <td style='font-size: 12px;'>: Rp <?= number_format($retur['total_nilai_retur'], 0, ",", ".") ?></td>
            </tr>
            <tr>
                <td width='20%' style='font-size: 12px;'>Total Setelah Retur</td>
                <td style='font-size: 12px;'>: Rp <?= number_format($retur['total_setelah_retur'], 0, ",", ".") ?></td>
            </tr>
        </table>
        <table class="table" style='margin-top: 15px;'>
            <tr>
                <td width='20%' style='font-size: 12px; font-weight: bold; '>Keterangan</td>
                <td style='font-size: 12px;'>: <?= $retur['alasan'] ?></td>
            </tr>
        </table>
        <style>
            table.header-tanda-tangan{
                margin-top: 25px;
                font-size: 12px;
            }

            table.footer-tanda-tangan{
                margin-top: 70px;
                font-size: 12px;
            }
            
            table.jabatan_tanda_tangan{
                font-size: 12px;
            }
        </style>
        <table class="table header-tanda-tangan">
            <tr>
                <td class='text-center' width='33.33%'>Dibuat Oleh,</td>
                <td class='text-center' width='33.33%'>Diperiksa Oleh,</td>
                <td class='text-center' width='33.33%'>Diketahui Oleh,</td>
            </tr>
        </table>
        <table class='table footer-tanda-tangan'>
            <tr>
                <td class='text-center' width='33.33%'>(<?php for ($i=0; $i < 30; $i++): echo '&nbsp;'; endfor;?>)</td>
                <td class='text-center' width='33.33%'>(<?php for ($i=0; $i < 30; $i++): echo '&nbsp;'; endfor;?>)</td>
                <td class='text-center' width='33.33%'>(<?php for ($i=0; $i < 30; $i++): echo '&nbsp;'; endfor;?>)</td>
            </tr>
        </table>
        <!-- <table class='table jabatan_tanda_tangan'>
            <tr>
                <td class='text-center' width='33.33%'><?= $berita_acara_penyerahan_faktur->jabatan_diketahui ?></td>
                <td class='text-center' width='33.33%'><?= $berita_acara_penyerahan_faktur->jabatan_yang_menerima ?></td>
                <td class='text-center' width='33.33%'><?= $berita_acara_penyerahan_faktur->jabatan_debt_collector ?></td>
            </tr>
        </table> -->
    </body>
</html>