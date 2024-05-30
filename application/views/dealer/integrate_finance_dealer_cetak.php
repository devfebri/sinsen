<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->
    <?php 
        function mata_uang($a){
            return number_format($a, 0, ',', '.');
        } 
    ?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Cetak</title>
    <style>
        @media print {
            @page {
                sheet-size: 210mm 297mm;
              /*  margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 1cm;
                margin-top: 1cm;*/
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
if ($set=='print'){ 
    $spk = $this->db->query("SELECT tr_spk.*,warna,tipe_ahm,(SELECT id_flp_md FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_sales_people,
            (SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=tr_spk.id_customer ORDER BY created_at DESC LIMIT 1)as id_karyawan_dealer,
                case when tr_spk.dp_stor = 0 then 'Kredit' else 'Cash' end as tipe_pembayaran   
            FROM tr_spk 
                JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
                JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
                WHERE tr_spk.no_spk='$row->no_spk' ORDER BY tr_spk.created_at DESC")->row();
?>
 <table class="table table-borderedx">
    <tr>
      <td width="100%" align="center" colspan="2"><b>Cetak Pelunasan Invoice</b><br>&nbsp;</td>
    </tr>
    <tr>
      <td width="25%">ID Invoice Pelunasan</td><td>: <?= $row->id_inv_pelunasan ?></td>
    </tr>
    <tr>
      <td>ID SPK</td><td>: <?= $row->no_spk ?></td>
    </tr>
    <tr>
      <td>Sales People ID</td><td>: <?= $row->created_at ?></td>
    </tr>
    <tr>
        <td>Nama Pelanggan</td><td>: <?= $spk->nama_konsumen ?></td>
    </tr>
    <tr>
        <td>No KTP</td><td>: <?= $spk->no_ktp ?></td>
    </tr>
    <tr>
        <td>Tipe</td><td>: <?= $spk->id_tipe_kendaraan.' | '.$spk->tipe_ahm ?></td>
    </tr>
    <tr>
        <td>Warna</td><td>: <?= $spk->id_warna.' | '.$spk->warna ?></td>
    </tr>
    <tr>
        <td>Diskon</td><td>: <?= mata_uang($spk->diskon) ?></td>
    </tr>
    <tr>
        <td>Tipe</td><td>: <?= mata_uang($spk->harga_on_road-$spk->diskon) ?></td>
    </tr>
      <tr>
        <td>Sisa Pelunasan</td><td>: <?= mata_uang(($spk->harga_on_road-$spk->diskon)-$spk->tanda_jadi) ?></td>
    </tr>
  </table>
<?php } ?>
</body>
</html>
