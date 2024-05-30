<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?= $picking_slip['nomor_ps'] ?></title>
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
                <td><?= strtoupper($dealer['nama_dealer']) ?></td>
                <td colspan="9" style ="font-size: 10px;text-align:right;">
                    Dicetak : <?= date('d-M-Y H:i') ?>
                </td>
            </tr>
            <tr>
                <td><?= $dealer['alamat'] ?></td>
            </tr>
            <tr>
                <td><?= $dealer['kabupaten'] ?></td>
            </tr>
            <tr>
                <td><?= $dealer['provinsi'] ?></td>
            </tr>
            <tr>
                <td><?= $dealer['no_telp'] ?></td>
            </tr>
        </table>
        <table class="table table-bordereds" style='margin-bottom: 15px;'>
            <tr>
                <td style='text-align:center; font-size: 22px;'>Picking Slip</td>
            </tr>
        </table>
        <table class="table table-bordereds">
            <tr>
                <td width='22%'>No. Picking Slip</td>
                <td width='1%'>:</td>
                <td width='30%'><?= $picking_slip['nomor_ps'] ?></td>
                <td width='15%'>Nama</td>
                <td width='1%'>:</td>
                <td>
                    <?php if($picking_slip['id_work_order'] != ''|| $picking_slip['id_work_order'] != NULL){?>
                        <?php if($picking_slip['nama_pembeli'] != $picking_slip['nama_customer']){?>
                            <?= $picking_slip['nama_customer'] ?> QQ <?= $picking_slip['nama_pembeli'] ?>
                        <?php }else{?>
                            <?= $picking_slip['nama_pembeli'] ?>
                        <?php }?>
                    <?php }else{ ?>
                        <?= $picking_slip['nama_pembeli'] ?>
                    <?php } ?>
                    
                </td>
            </tr>
            <tr>
                <td width='22%'>No. Sales Order</td>
                <td width='1%'>:</td>
                <td width='30%'><?= $picking_slip['nomor_so'] ?></td>
                <td width='15%'>No. HP</td>
                <td width='1%'>:</td>
                <td><?= $picking_slip['no_hp_pembeli'] ?></td>
            </tr>
            <tr>
                <td width='22%'>Tanggal Sales Order</td>
                <td width='1%'>:</td>
                <td width='30%'><?= $picking_slip['tanggal_so'] ?></td>
                <td width='15%'>Alamat</td>
                <td width='1%'>:</td>
                <td><?= $picking_slip['alamat_pembeli'] ?></td>
            </tr>
            <tr>
                <?php if($picking_slip['id_work_order'] != ''|| $picking_slip['id_work_order'] != NULL){?>
                    <td width='22%'>No. Work Order</td>
                    <td width='1%'>:</td>
                    <td width='30%'><?= $picking_slip['id_work_order'] ?></td>
                <?php
                    }
                ?>
                <td width='15%'>No. Polisi</td>
                <td width='1%'>:</td>
                <td><?= $picking_slip['no_polisi'] ?></td>
            </tr>
        </table>
        <style>
            table.table-sm{
                font-size: 12px;
            }
            tr.header td{
                border-bottom: 2px solid black;
                border-top: 2px solid black;
            }
        </style>
        <?php 
            $is_retur = 0;
        ?>
        <table style='margin-top: 20px; margin-bottom: 50px;' class="table table-borderedx table-sm">
            <tr class='header'>
                <td width='5%'>No.</td>
                <td width='16%'>Nomor Part</td>
                <td width='34%'>Deskripsi Part</td>
                <td width='15%'>Gudang</td>
                <td width='10%'>Rak</td>
                <td width='10%' class='text-center'>Qty</td>
                <td width='10%' class='text-center'>Qty Return</td>
            </tr>
            <?php $index = 1; foreach ($parts as $part): ?>
            <tr>
                <td><?= $index ?>.</td>
                <td><?= $part['id_part'] ?></td>
                <td><?= $part['nama_part'] ?></td>
                <td><?= $part['id_gudang'] ?></td>
                <td><?= $part['id_rak'] ?></td>
                <!-- <td class='text-center'><?= $part['kuantitas'] ?></td> -->
                <td class='text-center'>
                    <?php if($part['ev'] =='ev'){?>
                        1
                    <?php }else{?>
                        <?= $part['kuantitas'] ?></td>
                    <?php }?>
                </td>
                <!-- <td class='text-center'><?= $part['kuantitas_return'] ?></td> -->
                <td class='text-center'>
                    <?php if($part['ev'] =='ev'){?>
                        <?= $part['is_return_ev'] ?>
                    <?php }else{?>
                        <?= $part['kuantitas_return'] ?>
                    <?php }?>
                </td>
            </tr>
            <?php if($part['ev'] =='ev'){?>
                <tr>
                    <td></td>
                    <td> </td>
                    <td>SN : <b><?= $part['serial_number'] ?></b></td>
                </tr>
            <?php }?>
            
            <?php $index++; 
                $is_retur += $part['kuantitas_return'];
                endforeach; 
            ?>
            <tr>
                <td><br></td>
            </tr>

            <?php if($is_retur==0){?>

                <tr>
                    <td colspan ="2" style='font-size: 14px;'>Dibuat Oleh,</td>
                    <td style='font-size: 14px;'>Mengeluarkan,</td>
                    <td colspan ="2" style='font-size: 14px;'>Menerima,</td>
                    <td style='font-size: 14px;'>Disetujui,</td>
                </tr>

                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                
                <tr>
                    <td colspan ="2">__________________</td>
                    <td>__________________</td>
                    <td colspan ="2">__________________</td>
                    <td>__________________</td>
                </tr>
                <tr>
                    <td style='font-size: 14px;' colspan ="2">Frontdesk</td>
                    <td style='font-size: 14px;'>Inventory Part</td>
                    <td style='font-size: 14px;' colspan ="2">Mekanik</td>
                    <td style='font-size: 14px;'>Kabeng/SA</td>
                </tr>

            <?php }else{ ?>
                
                <tr>
                    <td colspan ="2" style='font-size: 14px;'>Dibuat Oleh,</td>
                    <td style='font-size: 14px;'>Menyerahkan,</td>
                    <td colspan ="2"  style='font-size: 14px;'>Menerima,</td>
                    <td style='font-size: 14px;'>Diketahui,</td>
                </tr>

                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                
                <tr>
                    <td colspan ="2">__________________</td>
                    <td>__________________</td>
                    <td colspan ="2">__________________</td>
                    <td colspan ="2">__________________</td>
                </tr>
                <tr>
                    <td style='font-size: 14px;' colspan ="2">Frontdesk</td>
                    <td style='font-size: 14px;'>Mekanik</td>
                    <td colspan ="2" style='font-size: 14px;'>Inventory Part</td>
                    <td colspan ="2" style='font-size: 14px;'>Kabeng/ SA</td>
                </tr>
            <?php } ?>


        </table>
        <!-- <footer style='position: absolute; top: 0;'> -->
        <footer >


        </footer>
    </body>
</html>