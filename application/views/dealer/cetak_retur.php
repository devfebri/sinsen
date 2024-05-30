<!DOCTYPE html>
<html>
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
                    border: 0px solid black;
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
<table border="1" width="100%">
    <tr>
        <td>
            <table border="0" width="100%">
                <tr>
                    <td align="center"><b>Surat Permohonan Retur Unit ke Main Dealer</b></td>
                </tr>
            </table>
            <table border="0" width="100%" align="left">
                <tr align="left">
                    <td width="20%">Kepada</td>
                    <td>: Logistik</td>
                </tr>
                <tr align="left">
                    <td>CC</td>
                    <td>: Direct Sales, Logistic, Accounting</td>
                </tr>
                <tr align="left">
                    <td>Tanggal</td>
                    <td>: <?php echo tgl_indo($tanggal,' ') ?></td>
                </tr>
                <tr align="left">
                    <td>Hal</td>
                    <td>: Retur Motor</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<ol>
    <li><b>Perihal untuk Disetujui</b></li>
        <p>
            Mohon untuk disetujui pengajuan retur unit dengan rincian sebagai berikut: <br>
            <table width="100%">
                <?php 
                $sql = $this->db->query("SELECT * FROM tr_retur_dealer_detail INNER JOIN tr_scan_barcode ON tr_retur_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                    WHERE tr_retur_dealer_detail.no_retur_dealer = '$isi_file->no_retur_dealer'");
                foreach ($sql->result() as $row) { ?>                    
                    <tr>
                        <td width="20%">No Rangka</td>
                        <td>: <?php echo $row->no_rangka ?></td>
                    </tr>
                    <tr>
                        <td>No Mesin</td>
                        <td>: <?php echo $row->no_mesin ?></td>
                    </tr>
                    <tr>
                        <td>Stok dari</td>
                        <td>: <?php echo $isi_file->nama_dealer ?></td>
                    </tr>
                    <tr>
                        <td>Retur ke</td>
                        <td>: PT. Sinar Sentosa Primatama</td>
                    </tr>
                    <tr>
                        <td><br></td>
                        <td></td>
                    </tr>                
                <?php } ?>
            </table>
        </p>
    <li><b>Latar Belakang</b></li>        
            <ol>
            <?php
            $sql = $this->db->query("SELECT * FROM tr_retur_dealer_detail INNER JOIN tr_scan_barcode ON tr_retur_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                    WHERE tr_retur_dealer_detail.no_retur_dealer = '$isi_file->no_retur_dealer'");
                foreach ($sql->result() as $row) { ?>                    
                <li><?php echo $row->no_mesin ?> - <?php echo $row->keterangan ?></li>
            <?php } ?>
            </ol>        
    <li><b>Berlaku</b></li>
        <p>
            Memo persetujuan ini berlaku sejak disetujui dan ditandatangani oleh otoritas yang berlaku.
            <table border="1" width="80%" align="center">
                <tr>
                    <td><b><center>Diajukan</td>
                    <td><b><center>Diketahui</td>
                    <td><b><center>Diketahui</td>
                </tr>
                <tr>
                    <td><br><br><br><br><br></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><br><br></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b><center>PJS Dealer SSP</td>
                    <td><b><center>Manager Direct Sales</td>
                    <td><b><center>Logistic Section Head</td>
                </tr>
            </table>
        </p>
</ol>
</body>
</html>
