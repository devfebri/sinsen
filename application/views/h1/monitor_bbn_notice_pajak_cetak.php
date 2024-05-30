    <?php 
        function mata_uang($a){
            if ($a>0) {
             return number_format($a, 0, ',', '.');   
            }else{
                return 0;
            }
        }
        function dmy($date,$separate){
            return date("d".$separate."m".$separate."Y", strtotime($date));
        }
    ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Cetak</title>
        <style>
            @media print {
                @page {
                    sheet-size: 297mm 210mm;
                    margin-left: 1cm;
                    margin-right: 1cm;
                    margin-bottom: 1cm;
                    margin-top: 1cm;
                }
                .text-center{text-align: center;}
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
                body{
                    font-family: "Arial";
                    font-size: 10pt;
                }
            }
        </style>
    </head>
    <body>
        <table style="font-size: 11pt">
            <tr>
                <td>MAIN DEALER</td><td>: PT Sinar Sentosa Primatama</td>
            </tr>
            <tr>
                <td colspan="2">Laporan Rekapan Selisih Pembayaran BBN</td>
            </tr>
        </table>
        <table class="table table-bordered">
            <tr>
                <td style="text-align: center;">No.</td>
                <td style="text-align: center;width: 13%">Tgl. Mohon Samsat</td>
                <td style="text-align: center;width: 18%">Nama Dealer</td>
                <td style="text-align: center;width: 18%">Nama Konsumen</td>
                <td style="text-align: center;">No. Mesin</td>
                <td style="text-align: center;">Harga BBN Samsat</td>
                <td style="text-align: center;">Harga Notice Pajak</td>
                <td style="text-align: center;">Selisih Harga</td>
            </tr>
            <?php   
                $no = 1;
                $tot_selisih =0;
                foreach($dt_detail->result() as $isi) {
                  $selisih = $isi->biaya_bbn_biro-$isi->notice_pajak;
                  $tot_selisih +=$selisih;
            ?>
            <tr>
                <td><?= $no ?></td>
                <td><?= dmy($isi->tgl_mohon_samsat,'/'); ?></td>
                <td><?= $isi->nama_dealer ?></td>
                <td><?= $isi->nama_konsumen ?></td>
                <td><?= $isi->no_mesin ?></td>
                <td align="right"><?= mata_uang($isi->biaya_bbn_biro) ?></td>
                <td align="right"><?= mata_uang($isi->notice_pajak) ?></td>
                <td align="right"><?= mata_uang($selisih) ?></td>
            </tr>
            <?php $no++; } ?>
            <tr>
                <td colspan="7" align="right">TOTAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td align="right"><?= mata_uang($tot_selisih) ?></td>
            </tr>
        </table>
    </body>
</html>