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
        <title>Memo Pengajuan Return</title>
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
        <table class="table">
            <tr>
                <td style='font-size: 12px;'>Main Dealer : PT. Sinar Sentosa Primatama</td>
            </tr>
        </table>
        <table class="table table-borderedx" style='margin-top: 15px;'>
            <tr>
                <td style='text-align:center; font-size: 12px;'>MEMO PENGAJUAN RETUR</td>
            </tr>
        </table>
        <table class="table table-borderedx">
            <tr>
                <td style='text-align:center; font-size: 12px;'>No. <?= $retur['id_retur_penjualan'] ?></td>
            </tr>
        </table>
        <table class="table" style='margin-top: 15px;'>
            <tr>
                <td width="20%" style='font-size: 12px;'>Nama Customer</td>
                <td width="50%" style='font-size: 12px;'>: <?= $retur['kode_dealer_md'] ?> - <?= $retur['nama_dealer'] ?></td>
                <td width="30%" style='font-size: 12px;' class='text-right'>HAL</td>
            </tr>
            <tr>
                <td width="20%" style='font-size: 12px;'>Alamat Customer</td>
                <td width="50%" style='font-size: 12px;'>: <?= $retur['alamat'] ?></td>
                <td width="30%" style='font-size: 12px;' class='text-right'><?= date('d/m/Y H:i', time()) ?></td>
            </tr>
        </table>
        <table class="table">
            <tr>
                <td width="20%" style='font-size: 12px;'>Nama Salesman</td>
                <td width="80%" style='font-size: 12px;'>: <?= $retur['nama_salesman'] ?></td>
            </tr>
            <tr>
                <td width="20%" style='font-size: 12px;'>Jenis Order</td>
                <td width="80%" style='font-size: 12px;'>: <?= $retur['jenis_order'] ?></td>
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
                <td style='font-weight: bold;'>Tgl Faktur</td>
                <td style='font-weight: bold;'>Nomor Faktur</td>
                <td style='font-weight: bold;'>Nomor Part</td>
                <td style='font-weight: bold;'>Nama Part</td>
                <td style='font-weight: bold;'>Qty Fak</td>
                <td style='font-weight: bold;'>Qty Retur</td>
                <td style='font-weight: bold;'>Nilai Retur</td>
            </tr>
            <?php
                $index = 1;
                $total_nilai_retur = 0;
                foreach($items as $item):
            ?>
            <tr>
                <td width='6%'><?= $index ?>.</td>
                <td><?= $item['tgl_faktur'] ?></td>
                <td><?= $item['no_faktur'] ?></td>
                <td><?= $item['id_part'] ?></td>
                <td><?= $item['nama_part'] ?></td>
                <td><?= $item['qty_faktur'] ?></td>
                <td><?= $item['qty_retur'] ?></td>
                <td>Rp <?= number_format($item['amount'] , 0, ',', '.') ?></td>
            </tr>
            <?php 
            $index++;
            $total_nilai_retur += $item['amount'];
            endforeach; 
            ?>
            <tr>
                <td class='text-center' colspan='7'>Total</td>
                <td>Rp <?= number_format($total_nilai_retur , 0, ',', '.') ?></td>
            </tr>
        </table>
        <table class="table">
            <tr>
                <td width='20%' style='font-size: 12px; font-weight: bold; '>Alasan Retur</td>
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
                <td class='text-center' width='33.33%'>Diketahui Oleh,</td>
                <td class='text-center' width='33.33%'>Disetujui Oleh,</td>
            </tr>
        </table>
        <table class='table footer-tanda-tangan'>
            <tr>
                <td class='text-center' width='33.33%'>(<?php for ($i=0; $i < 30; $i++): echo '&nbsp;'; endfor;?>)</td>
                <td class='text-center' width='33.33%'>(<?php for ($i=0; $i < 30; $i++): echo '&nbsp;'; endfor;?>)</td>
                <td class='text-center' width='33.33%'>(<?php for ($i=0; $i < 30; $i++): echo '&nbsp;'; endfor;?>)</td>
            </tr>
        </table>
        <table class='table jabatan_tanda_tangan'>
            <tr>
                <td class='text-center' width='33.33%'><?= $berita_acara_penyerahan_faktur->jabatan_diketahui ?></td>
                <td class='text-center' width='33.33%'><?= $berita_acara_penyerahan_faktur->jabatan_yang_menerima ?></td>
                <td class='text-center' width='33.33%'><?= $berita_acara_penyerahan_faktur->jabatan_debt_collector ?></td>
            </tr>
        </table>
    </body>
</html>