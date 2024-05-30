<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->
    <?php 
        function mata_uang($a){
            return number_format($a, 0, ',', '.');
        } ?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Cetak</title>
    <style>
        @media print {
            @page {
                sheet-size: 210mm 297mm;
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
                font-size: 11pt;
            }
        }
    </style>
</head>

<body>
    <?php 
        $row = $this->db->query("SELECT tr_penyerahan_srut.*,ms_dealer.nama_dealer FROM tr_penyerahan_srut JOIN ms_dealer ON tr_penyerahan_srut.id_dealer=ms_dealer.id_dealer WHERE no_serah_terima='$id'")->row();
        $detail = $this->db->query("SELECT tr_penyerahan_srut_detail.* ,tr_srut.no_srut FROM tr_penyerahan_srut_detail 
            LEFT JOIN tr_srut ON tr_srut.no_mesin=tr_penyerahan_srut_detail.no_mesin
            WHERE no_serah_terima='$id'");
     ?>
    <table class="table table-bodrdered">
        <tr>
            <td width="20%">Nomor</td><td>: <?= $row->no_serah_terima ?></td><td>JAMBI,</td>
        </tr>
        <tr>
            <td>Lampiran</td><td>: <?= $detail->num_rows() ?></td><td>Kepada Yth :</td>
        </tr>
        <tr>
            <td>Perihal</td><td>: PENYERAHAN SRUT HONDA</td><td><?= $row->nama_dealer ?></td>
        </tr>
    </table>

    <p>Dengan hormat,</p>
    <p>Dengan ini kami serahkan kepada Bapak/Ibu, SRUT HONDA penjualan <?= $row->nama_dealer ?> sebanyak <?= $detail->num_rows() ?> dengan ketarangan sebagai berikut :</p>
    <table class="table table-bordered"> 
        <tr>
            <td align="center">No.</td>
            <td>No Mesin</td>
            <td>No. SRUT</td>
        </tr>
        <?php $no=1; foreach ($detail->result() as $rs): ?>
            <tr>
                <td align="center"><?= $no ?></td>
                <td><?= $rs->no_mesin ?></td>
                <td><?= $rs->no_srut ?></td>
            </tr>
        <?php $no++; endforeach ?>
    </table>
    <p>Besar harapan kami penyerahan SRUT dapat Bapak/Ibu terima dengan bail. Terima Kasih</p>
    <table class="table-borderedd" style="width: 95%" align="center">
        <tr>
            <td>
                <br>
                Yang Menerima,
                <br><br><br><br>
                __________________
            </td>
            <td width="45%"></td>
            <td>
                Hormat Kami,<br>
                Yang Menyerahkan,
                <br><br><br><br>
                FERMAWATI <br>
                FINANCE MANAGER
            </td>
        </tr>
    </table>
</body>
</html>
