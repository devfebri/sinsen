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

<?php if ($cetak=='cetak_tagihan_ubahnama_stnk'){ ?>
<?php 
    $hari = nama_hari($row->tgl_faktur);
    $tgl_indo = tgl_indo($row->tgl_faktur);
 ?>


    <div>
<br><br>
    <table class="table">
        <tr>
            <td>
                <table>
                    <tr>
                        <td>Nomor</td><td>: </td>
                    </tr>
                    <tr>
                        <td>Perihal</td><td>: Tagihan Ubah Nama STNK</td>
                    </tr>
                </table>
            </td>

            <td>
                <table>
                    <tr>
                        <td>Kepada Yth.</td>
                    </tr>
                    <tr>
                        <td>PT. </td>
                    </tr>
                    <tr>
                        <td>JL.</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table><br>
    <br>
    <p style="text-align: justify;">
        Bersama ini kami dari PT. Sinar Sentosa Primatama, berikut ini detail konsumen pengubahan nama pada STNK :
    </p>
    <table>
        <tr>
            <td style="font-weight: bold;height: 25px">No</td>
            <td style="font-weight: bold;height: 25px">Nama Konsumen Lama</td>
            <td style="font-weight: bold;height: 25px">Nama Konsumen Baru</td>
            <td style="font-weight: bold;height: 25px">No Mesin</td>
        </tr>
        <?php foreach ($dt_tagihan->result() as $key) {
                    if ($key->sengaja=='1') {
                                $re = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
                        INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = ms_item.id_tipe_kendaraan
                        INNER JOIN ms_warna ON ms_warna.id_warna = ms_item.id_warna WHERE tr_scan_barcode.no_mesin='$key->no_mesin'")->row();
                    $nosin_spasi = substr_replace($key->no_mesin," ", 5, -strlen($key->no_mesin));
                    $rw = $this->m_admin->getByID("tr_fkb","no_mesin",$nosin_spasi)->row();
                  echo "                                      
                    <tr> 
                    <td>$key+1</td>                                       
                      <td>$key->nama_konsumen</td>
                      <td>$re->tipe_ahm</td>
                      <td>$re->warna</td>
                      <td>$rw->tahun_produksi</td>
                    </tr>";        
                              }          
                  }
                  ?> ?>
    </table>
    <p style="text-align: justify;">Adapun total Tagihan Pengubahan Nama STNK : Rp. </p>
    <p style="text-align: justify;">Demikian pemberitahuan ini kami sampaikan atas perhatiannya terimakasih.</p>
    <br>
    <p>Hormat Kami,</p>
    <br><br><br>
    <p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</p>
    
    <table class="table">
        <tr>
            <td style="text-align: center;">Disetujui Oleh,<br><br><br><br><br><br></td>
            <td style="text-align: center;vertical-align: top;">Diserahkan Oleh,</td>
            <td style="text-align: center;vertical-align: top;">Diterima Oleh,</td>
        </tr>
        <tr>
            <td style="text-align: center;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            <td style="text-align: center;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            <td style="text-align: center;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
        </tr>
    </table>

</div>
<?php } ?>
</body>
</html>
