<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Laporan Stok versi Kelompok</title>
        <style>
            @media print {
                @page {
                    sheet-size: 330mm 210mm;
                    margin-left: 0.5cm;
                    margin-right: 0.5cm;
                    margin-bottom: 0.5cm;
                    margin-top: 0.5cm;
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

                .table-font-sm tr td {
                    font-size: 8px;
                }

                body {
                    font-family: "Arial";
                    font-size: 10pt;
                }

                .text-center{
                    text-align: center;
                }

                .text-right{
                    text-align: right;
                }

                td.header{
                    background-color: yellow;
                }
            }
        </style>
    </head>
    <body>
        <table class="table">
            <tr>
                <td width='50%'>Laporan Sales Out Per Customer</td>
                <td width='50%' class='text-right'><?= date('d/m/Y', strtotime($this->input->get('start_date'))) ?> - <?= date('d/m/Y', strtotime($this->input->get('end_date'))) ?></td>
            </tr>
        </table>
        <table class="table table-bordered table-font-sm" style='margin-top: 15px;'>
            <tr>
                <td class='text-center' width='3%'>No</td>
                <td width='5%'>Tanggal</td>
                <td width='12%'>Nama Konsumen</td>
                <td width='8%'>Alamat</td>
                <td width='5%'>Kota/Kab</td>
                <td width='7%'>Jenis Motor</td>
                <td width='5%'>No. Polisi</td>
                <td width='4%'>Tahun Produksi</td>
                <td width='3%'>Tipe Motor</td>
                <td width='9%'>No. NSC</td>
                <td width='6%'>No. Part</td>
                <td width='12%'>Nama Part</td>
                <td width='5%'>HET</td>
                <td width='3%'>Disc</td>
                <td width='5%'>Total</td>
                <td width='3%'>Kel. Produk</td>
                <td width='5%'>Jenis Promo</td>
            </tr>
            <?php $index = 1; foreach($sales as $each): ?>
            <?php 
                $total = 0;

                if($each['tipe_diskon'] == 'Percentage'){
                    $potongan_harga = ($each['diskon_value']/100) * $each['het'];
                    $total = $each['kuantitas'] * ($each['het'] - $potongan_harga);
                }elseif($each['tipe_diskon'] == 'Value'){
                    $total = $each['kuantitas'] * ($each['het'] - $each['diskon_value']);
                }else{
                    $total = $each['kuantitas'] * $each['het'];
                }

            ?>
            <tr>
                <td class='text-center'><?= $index ?></td>
                <td><?= $each['tanggal_so'] ?></td>
                <td><?= $each['nama_customer'] ?></td>
                <td><?= $each['alamat'] ?></td>
                <td><?= $each['kabupaten'] ?></td>
                <td><?= $each['tipe_kendaraan'] ?></td>
                <td><?= $each['no_polisi'] ?></td>
                <td><?= $each['tahun_produksi'] ?></td>
                <td><?= $each['kategori'] ?></td>
                <td><?= $each['no_nsc'] ?></td>
                <td><?= $each['id_part'] ?></td>
                <td><?= $each['nama_part'] ?></td>
                <td><?= $each['het_formatted'] ?></td>
                <td><?= $each['diskon_formatted'] ?></td>
                <td>Rp  <?= number_format($total, 0, ",", ".") ?></td>
                <td><?= $each['kelompok_part'] ?></td>
                <td><?= $each['jenis_promo'] ?></td>
            </tr>
            <?php $index++; endforeach; ?>
        </table>
    </body>
</html>