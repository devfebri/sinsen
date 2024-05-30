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
        <title>Berita Acara Penyerahan Faktur</title>
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
        <table class="table table-borderedx" style='margin-top: 15px;'>
            <tr>
                <td style='text-align:center; font-size: 24; text-decoration: underline;'>Berita Acara Penyerahan Faktur Spare Part & OLI MPX</td>
            </tr>
        </table>
        <table class="table table-borderedx">
            <tr>
                <td style='text-align:center; font-size: 12;'>No. <?= $berita_acara_penyerahan_faktur->no_bap ?></td>
            </tr>
        </table>
        <table class="table table-borderedx" style='margin-top: 25px;'>
            <tr>
                <td style='font-size: 12px;'>Pada hari ini, <?= day_in_indonesia(date('N')) ?>, <?= date('d/m/Y') ?> telah dilakukan serah terima faktur sebagai berikut:</td>
            </tr>
        </table>
        <table class="table table-borderedx" style='margin-top: 25px; margin-bottom: 10px;'>
            <tr>
                <td style='font-size: 12px;'>Adapun faktur-faktur yang diserah terimakan sebanyak : <?= count($items) ?> lembar dengan rincian sebagai berikut:</td>
            </tr>
        </table>
        <?php 
            function print_nama_customer($data){
                if($data->no_tanda_terima_faktur == null){
                    return $data->nama_dealer;
                }else{
                    return "{$data->nama_dealer} {$data->no_tanda_terima_faktur} ({$data->jumlah_faktur} Fak)";
                }
            }
        ?>
        <style>
            table.small-table tr td{
                font-size: 11px;
            }
        </style>
        <table class="table table-bordered small-table" style='margin-bottom: 15px;'>
            <tr>
                <td width='5%'>No.</td>
                <td>Nama Customer</td>
                <td>No Faktur</td>
                <td>Nilai Faktur</td>
                <td>Tgl Jatuh Tempo</td>
                <td>Keterangan</td>
            </tr>
            <?php
                $index = 1;
                $total = 0;
                foreach($items as $item):
            ?>
            <tr>
                <td width='8%'><?= $index ?>.</td>
                <td><?= print_nama_customer($item) ?></td>
                <td><?= $item->no_faktur ?></td>
                <td>Rp <?= number_format($item->total, 0, ",", ".") ?></td>
                <td><?= $item->tgl_jatuh_tempo ?></td>
                <td><?= $item->keterangan ?></td>
            </tr>
            <?php $total+= $item->total; $index++; endforeach; ?>
            <tr>
                <td colspan='3' class='text-right'>Total Tagihan:</td>
                <td>Rp <?= number_format($total, 0, ",", ".") ?></td>
                <td colspan='2'></td>
            </tr>
        </table>
        <table class="table table-borderedx">
            <tr>
                <td style='font-size: 12px;'>Demikianlah Berita Acara ini kami buat dengan sebenarnya dan telah diserah terimakan dengan baik, apabila terjadi kehilangan menjadi tanggung jawab sepenuhnya yang menerima faktur (kasir).</td>
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
                <td class='text-center' width='25%'>Diketahui</td>
                <td class='text-center' width='25%'>Yang Menerima</td>
                <td class='text-center' width='25%'>Yang Menagih</td>
                <td class='text-center' width='25%'>Yang Menyerahkan</td>
            </tr>
        </table>
        <table class='table footer-tanda-tangan'>
            <tr>
                <td class='text-center' width='25%'>(<span style='text-decoration: underline;'><?= $berita_acara_penyerahan_faktur->nama_diketahui ?></span>)</td>
                <td class='text-center' width='25%'>(<span style='text-decoration: underline;'><?= $berita_acara_penyerahan_faktur->nama_yang_menerima ?></span>)</td>
                <td class='text-center' width='25%'>(<span style='text-decoration: underline;'><?= $berita_acara_penyerahan_faktur->nama_debt_collector ?></span>)</td>
                <td class='text-center' width='25%'>(<span style='text-decoration: underline;'><?= $berita_acara_penyerahan_faktur->nama_yang_menyerahkan ?></span>)</td>
            </tr>
        </table>
        <table class='table jabatan_tanda_tangan'>
            <tr>
                <td class='text-center' width='25%'><?= $berita_acara_penyerahan_faktur->jabatan_diketahui ?></td>
                <td class='text-center' width='25%'><?= $berita_acara_penyerahan_faktur->jabatan_yang_menerima ?></td>
                <td class='text-center' width='25%'><?= $berita_acara_penyerahan_faktur->jabatan_debt_collector ?></td>
                <td class='text-center' width='25%'><?= $berita_acara_penyerahan_faktur->jabatan_yang_menyerahkan ?></td>
            </tr>
        </table>
        <style>
            table.note tr td{
                font-size: 12px;
            }
        </style>
        <table class="table table-borderedx note" style='margin-top: 25px;'>
            <tr>
                <td width='10%'>Catatan :</td>
                <td></td>
            </tr>
            <tr>
                <td colspan='2'>1. Faktur yang tidak tertagih harus diserah terimakan kembali ke bagian piutang setiap sore pukul 16.00 WIB (tanpa terkecuali)</td>
            </tr>
            <tr>
                <td colspan='2'>2. Setiap pembayaran cek / BG, harus mencantumkan no rekening perusahaan dan stempel toko dibelakang cek/BG, apabila ada penyimpangan wajib melaporkan ke ....</td>
            </tr>
        </table>
    </body>
</html>