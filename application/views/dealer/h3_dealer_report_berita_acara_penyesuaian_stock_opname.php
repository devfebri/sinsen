<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?= $stock_opname->id_stock_opname ?></title>
        <style>
            @media print {
                @page {
                    sheet-size: 210mm 297mm;
                    margin-left: 0.5cm;
                    margin-right: 0.5cm;
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
                    font-size: 8pt;
                }
            }
        </style>
    </head>
    <body>
        <table class="table table-borderedx" style='margin-top: 0px;'>
            <tr>
                <td style='text-align:center; font-size: 15px;'><?php echo $dealer->nama_dealer?></td>
            </tr>
            <tr>
                <td style='text-align:center; font-size: 15px;'>BERITA ACARA STOCK OPNAME</td>
            </tr>
            <hr>
        </table>
        <table class="table table-borderedx">
            <tr>
                <td width="200px">Tanggal Konfirmasi Stock Out</td>
                <td>:</td>
                <td width="550px"><?= $stock_opname->date_opname ?></td>
            </tr>
            <tr>
                <td>Bulan</td>
                <td>:</td>
                <td><?= $stock_opname->bulan_date_opname ?></td>
            </tr>
            <tr>
                <td>Jumlah Item Fisik yang dihitung</td>
                <td>:</td>
                <td><?= $stock_opname->jumlah_item ?></td>
            </tr>
            <tr>
                <td>Jumlah Item Fisik yang didalam sistem</td>
                <td>:</td>
                <td><?= $part_sistem->jumlah_item_sistem ?></td>
            </tr>
            </tr>
        </table>
        <style>
            tr.header td{
                border-bottom: 2px solid black;
                border-top: 2px solid black;
            }
        </style>
        <table style='margin-top: 20px; margin-bottom: 50px;' class="table table-bordered">
            <caption style="margin-bottom: -10px;"><b>Asset Hasil Stock Opname</b></caption>
            <tr class='header'>
                <td width='5%'>No.</td>
                <td>Nomor Part</td>
                <td>Deskripsi Part</td>
                <td width='8%'>Sistem Qty</td>
                <td width='8%'>Actual Qty</td>
                <td>UoM</td>
            </tr>
            <?php 
                $index = 1; 
                foreach ($parts->result() as $part): ?>
                <tr>
                    <td><?= $index ?>.</td>
                    <td><?= $part->id_part ?></td>
                    <td><?= $part->nama_part ?></td>
                    <td><?= $part->stock_sistem ?></td>
                    <td><?= $part->stock_aktual ?></td>
                    <td><?= $part->satuan ?></td>
                </tr>
            <?php $index++; endforeach; ?>
        </table>
        <table style='margin-top: 20px; margin-bottom: 50px;' class="table table-bordered">
            <caption style="margin-bottom: -10px;"><b>Perbedaan Aset Tercatat</b></caption>
            <tr class='header'>
                <td width='5%'>No.</td>
                <td>Nomor Part</td>
                <td>Deskripsi Part</td>
                <td>Qty Difference</td>
                <td>UoM</td>
            </tr>
            <?php 
                $index = 1; 
                if($parts->row()->qty_diff >0){
                foreach ($parts->result() as $part): 
                if($part->qty_diff >0){
                ?>
                <tr>
                    <td><?= $index ?>.</td>
                    <td><?= $part->id_part ?></td>
                    <td><?= $part->nama_part ?></td>
                    <td><?= $part->qty_diff ?></td>
                    <td><?= $part->satuan ?></td>
                </tr>
                $index++;
                <?php }?>
            <?php endforeach; }else{?>
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak Ada Part</td>
                </tr> 
            <?php }?>
        </table>
        <table class="table table-borderedx">
            <tr>
                <td width="200px">Confirmed Oleh</td>
                <td>:</td>
                <td width="550px"></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td><?php echo date("d-m-Y") ?></td>
            </tr>
            </tr>
        </table>
        <hr style="height:2px;border-width:0;color:black;background-color:black">
        <h4 style="margin-top: -10px;">FOR OFFICE USE ONLY</h4>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <hr style="height:1px;border-width:0;color:black;background-color:black">
        <table class="table table-borderedx">
            <tr>
                <td width='18%'>Confirmed Oleh</td>
                <td>:</td>
                <td width='25%'></td>
                <td width='18%'>Confirmed Oleh</td>
                <td>:</td>
                <td width='25%'></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td></td>
                <td>Nama</td>
                <td>:</td>
                <td></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td></td>
                <td>Jabatan</td>
                <td>:</td>
                <td></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td><?php echo date("d-m-Y") ?></td>
                <td>Tanggal</td>
                <td>:</td>
                <td><?php echo date("d-m-Y") ?></td>
            </tr>
            </tr>
        </table>
    </body>
</html>