<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Tanda Terima Faktur</title>
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
        <table class="table table-borderedx" style='margin-top: 25px;'>
            <tr>
                <td style='font-size: 12px; font-weight: bold'>PT. Sinar Sentosa Primatama</td>
            </tr>
            <tr>
                <td style='font-size: 12px;'>Jl. Kolonel Abunjani No. 09</td>
            </tr>
            <tr>
                <td style='font-size: 12px;'>Jambi</td>
            </tr>
            <tr>
                <td style='font-size: 12px;'>Telepon: 0741-61551</td>
            </tr>
        </table>
        <table class="table table-borderedx" style='margin-top: 15px;'>
            <tr>
                <td style='text-align:center; font-size: 24; text-decoration: underline;'>TANDA TERIMA FAKTUR</td>
            </tr>
        </table>
        <table class="table table-borderedx">
            <tr>
                <td style='text-align:center; font-size: 12;'>No. <?= $tanda_terima_faktur->no_tanda_terima_faktur ?></td>
            </tr>
        </table>
        <table class="table table-borderedx" style='margin-top: 15px;'>
            <tr>
                <td style='font-size: 12px;'>Telah diterima faktur dari PT. Sinar Sentosa Primatama dengan rincian sbb:</td>
            </tr>
        </table>
        <style>
            table.small-table tr td{
                font-size: 11px;
            }
        </style>
        <table class="table table-bordered small-table" style='margin-bottom: 15px;'>
            <tr>
                <td width='5%'>No.</td>
                <td>Nama Customer</td>
                <td>Kode</td>
                <td>No Faktur</td>
                <td>Jumlah</td>
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
                <td><?= $item->nama_dealer ?></td>
                <td><?= $item->produk ?></td>
                <td><?= $item->no_faktur ?></td>
                <td>Rp <?= number_format($item->total, 0, ",", ".") ?></td>
                <td><?= $item->tgl_jatuh_tempo ?></td>
                <td><?= $item->faktur_lunas == 1 ? "Lunas" : "Belum Lunas" ?></td>
            </tr>
            <?php $total+= $item->total; $index++; endforeach; ?>
            <tr>
                <td colspan='4' class='text-right'>Total:</td>
                <td>Rp <?= number_format($total, 0, ",", ".") ?></td>
            </tr>
        </table>
        <table class="table table-borderedx">
            <tr>
                <td style='font-size: 12px;'>Pembayaran menggunakan Cek / Bilyet Giro harap mencantumkan rekening sebagai berikut:</td>
            </tr>
        </table>
        <style>
            table.rekening tr td{
                font-size: 12px;
            }
        </style>
        <table class="table table-borderedx rekening">
            <tr>
                <td width='7%'>Nama</td>
                <td width='1%'>:</td>
                <td><?= $tanda_terima_faktur->atas_nama ?></td>
            </tr>
        </table>
        <table class="table table-borderedx rekening">
            <tr>
                <td width='7%'>No a/c</td>
                <td width='1%'>:</td>
                <td><?= $tanda_terima_faktur->no_rekening ?></td>
            </tr>
        </table>
        <table class="table table-borderedx rekening">
            <tr>
                <td width='7%'>Bank</td>
                <td width='1%'>:</td>
                <td><?= $tanda_terima_faktur->nama_bank ?></td>
            </tr>
        </table>
        <style>
            table.note tr td{
                font-size: 12px;
            }
        </style>
        <table class="table table-borderedx note" style='margin-top: 15px;'>
            <tr>
                <td width='7%' style='font-weight: bold'>Note :</td>
                <td></td>
            </tr>
            <tr>
                <td colspan='2'>- TT asli harap dikembalikan ke PT. Sinar Sentosa Primatama & TT Copy diberikan kepada konsumen.</td>
            </tr>
            <tr>
                <td colspan='2'>- TT asli akan diberikan kepada konsumen jika faktur telah dibayar.</td>
            </tr>
            <tr>
                <td colspan='2'>- Pembayaran yang di TRANSFER / BILYET GIRO harus mencantumkan nomor rekening yang tertera pada TANDA TERIMA. (Nomor Rekening yang ada diatas note)</td>
            </tr>
            <tr>
                <td colspan='2'>- Apabila FAKTUR tersebut hilang, maka akan menjadi tanggung jawab si penerima,</td>
            </tr>
            <tr>
                <td colspan='2'>- Faktur dianggap LUNAS apabila Bilyet Giro / Dana sudah cair di Bank.</td>
            </tr>
        </table>
        <style>
            table.tanggal tr td{
                font-size: 12px;
            }
        </style>
        <table class="table table-borderedx tanggal" style='margin-top: 15px; margin-bottom: 15px;'>
            <tr>
                <td class='text-center'>Jambi, <?= date('d-m-Y') ?></td>
            </tr>
        </table>
        <style>
            table.header-tanda-tangan{
                margin-top: 5px;
                font-size: 12px;
            }

            table.footer-tanda-tangan{
                margin-top: 70px;
                font-size: 12px;
            }
        </style>
        <table class="table header-tanda-tangan">
            <tr>
                <td class='text-center' width='50%'>Yang Menyerahkan</td>
                <td class='text-center' width='50%'>Yang Menerima</td>
            </tr>
        </table>
        <table class='table footer-tanda-tangan'>
            <tr>
                <td class='text-center' width='50%'>(<?= $tanda_terima_faktur->nama_yang_menyerahkan ?>)</td>
                <td class='text-center' width='50%'>(<?= $tanda_terima_faktur->nama_dealer ?>)</td>
            </tr>
        </table>
        <table class="table header-tanda-tangan">
            <tr>
                <td class='text-center'>Disetujui Oleh,</td>
            </tr>
        </table>
        <table class='table footer-tanda-tangan'>
            <tr>
                <td class='text-center'>(<?= $tanda_terima_faktur->nama_yang_menyetujui ?>)</td>
            </tr>
        </table>
    </body>
</html>