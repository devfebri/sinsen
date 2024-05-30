<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?= $outbound->id_surat_jalan ?></title>
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
            }
        </style>
    </head>
    <body>
        <table class="table table-bordereds">
            <tr>
                <td><?= strtoupper($dealer->nama_dealer) ?></td>
            </tr>
            <tr>
                <td><?= $dealer->alamat ?></td>
            </tr>
            <tr>
                <td><?= $dealer->kabupaten ?></td>
            </tr>
            <tr>
                <td><?= $dealer->provinsi ?></td>
            </tr>
            <tr>
                <td><?= $dealer->no_telp ?></td>
            </tr>
        </table>
        <table class="table table-borderedx" style='margin-bottom: 15px;'>
            <tr>
                <td style='text-align:center; font-size: 22px;'>SURAT JALAN</td>
            </tr>
        </table>
        <table class="table table-borderedx">
            <tr>
                <td width='22%'>No. Surat Jalan</td>
                <td width='1%'>:</td>
                <td width='30%'><?= $outbound->id_surat_jalan ?></td>
                <td width='15%'>Nama Event</td>
                <td width='1%'>:</td>
                <td><?= $outbound->nama_event ?></td>
            </tr>
            <tr>
                <td width='22%'>ID Event</td>
                <td width='1%'>:</td>
                <td width='30%'><?= $outbound->id_event ?></td>
                <td width='15%'>PIC Event</td>
                <td width='1%'>:</td>
                <td><?= $outbound->pic_event ?></td>
            </tr>
            <tr>
                <td width='22%'>Tanggal Peminjaman</td>
                <td width='1%'>:</td>
                <td width='30%'><?= $outbound->tanggal_peminjaman ?></td>
                <td width='15%'>Periode Event</td>
                <td width='1%'>:</td>
                <td><?= $outbound->tanggal_mulai_event ?> - <?= $outbound->tanggal_selesai_event ?></td>
            </tr>
            <?php 
                $kode_gudang = '';
                foreach ($gudang as $each) {
                    $kode_gudang .= ', ' . $each->id_gudang;
                }
                $kode_gudang = substr($kode_gudang, 2);
            ?>
            <tr>
                <td width='22%'>Kode Gudang</td>
                <td width='1%'>:</td>
                <td colspan='4'><?= $kode_gudang ?></td>
            </tr>
        </table>
        <style>
            tr.header td{
                border-bottom: 2px solid black;
                border-top: 2px solid black;
            }
        </style>
        <table style='margin-top: 20px; margin-bottom: 50px;' class="table table-borderedx">
            <tr class='header'>
                <td width='10%'>No.</td>
                <td>Nomor Part</td>
                <td>Deskripsi Part</td>
                <td>Qty</td>
            </tr>
            <?php $index = 1; foreach ($parts as $part): ?>
            <tr>
                <td><?= $index ?>.</td>
                <td><?= $part->id_part ?></td>
                <td><?= $part->nama_part ?></td>
                <td><?= $part->kuantitas ?></td>
            </tr>
            <?php $index++; endforeach; ?>
        </table>
        <table style='margin-top: 20px; margin-bottom: 50px;' class="table table-borderedx">
            <tr>
                <td style='text-align:center;' width='33%'>Yang menyerahkan,</td>
                <td style='text-align:center;' width='33%'>Diketahui,</td>
                <td style='text-align:center;' width='33%'>Diterima oleh,</td>
            </tr>
        </table>

        <table style='margin-top: 60px; margin-bottom: 50px;' class="table table-borderedx">
            <tr>
                <td style='text-align:center;' width='33%'>PIC Warehouse</td>
                <td style='text-align:center;' width='33%'>Kepala Cabang</td>
                <td style='text-align:center;' width='33%'>PIC Event</td>
            </tr>
        </table>
    </body>
</html>