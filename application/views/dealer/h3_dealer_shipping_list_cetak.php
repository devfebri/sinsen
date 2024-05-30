<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?= $data['id_penerimaan_barang'] ?></title>
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
                font-size: 9pt;
            }
        }
    </style>
</head>

<body>
    <table>
      <tr>
        <td><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
      </tr>
    </table>
    <table class="table table-borderedx" style='margin-bottom: 15px;'>
        <tr>
            <td style='text-align:center; font-size: 15px;font-weight:bold;'>Penerimaan Parts (Shipping List)</td>
        </tr>
    </table>

    <table class="table table-borderedx">
      <tr>
        <td width="20%">No Penerimaan Barang</td>
        <td width="35%"> : <?= $data['id_penerimaan_barang'] ?> </td>
        <td width="17%">Tgl Penerimaan</td>
        <td width="20%"> : <?= $data['tgl_penerimaan'] ?></td>

      </tr>
      <tr>
        <td>No Shipping List</td>
        <td> : <?= $data['id_surat_pengantar']?></td>
      </tr>
      <tr>
        <td>No Packing Sheet</td>
        <td> : <?= $data['id_packing_sheet'] ?></td>
      </tr>
      <tr>
        <td>No Faktur</td>
        <td> : <?= $data['no_faktur'] ?></td>
      </tr>
        <tr>
          <td>No PO</td>
          <td> : <?= $data['nomor_po'] ?></td>
        </tr>
      <tr>
    </table>

    <style>
        table.table-sm {
            font-size: 12px;
        }

        tr.header td {
            border-bottom: 2px solid black;
            border-top: 2px solid black;
        }
    </style>
    <table style='margin-top: 20px; margin-bottom: 50px;' class="table table-borderedx table-sm">
        <tr class='header'>
            <td width='5%'>No.</td>
            <td width='16%'>Kode Part</td>
            <td width='21%'>Deskripsi Part</td>
            <td width='21%'>Serial Number</td>
            <td width='10%' class='text-center'>Qty PO</td>
            <td width='10%' class='text-center'>Qty Shipping</td>
            <td width='10%' class='text-center'>Qty Good</td>
            <td width='10%' class='text-center'>Gudang</td>
            <td width='10%' class='text-center'>Rak</td>
        </tr>
        <?php $index = 1;
        foreach ($sparepart as $part) : ?>
            <tr>
                <td><?= $index ?>.</td>
                <td><?= $part->id_part ?></td>
                <td><?= $part->nama_part ?></td>
                <td><?= $part->serial_number ?></td>
                <td class='text-center'><?= $part->qty_po ?></td>
                <td class='text-center'><?= $part->qty_ship ?></td>
                <td class='text-center'><?= $part->qty_good ?></td>
                <td class='text-center'><?= $part->id_gudang_good ?></td>
                <td class='text-center'><?= $part->id_rak_good ?></td>
            </tr>
        <?php $index++;
        endforeach; ?>
    </table>

    <table class="table" style="margin-top:20px">
      <tr>
        <td style="font-weight:bold;text-align:left;font-size: 12px;">Detail Part Bad</td>
      </tr>
    </table>
    <table style='margin-top: 20px; margin-bottom: 50px;' class="table table-borderedx table-sm">
        <tr class='header'>
            <td width='5%'>No.</td>
            <td width='16%'>Kode Part</td>
            <td width='21%'>Deskripsi Part</td>
            <td width='10%' class='text-center'>Qty Bad</td>
            <td width='10%' class='text-center'>Gudang Bad</td>
            <td width='10%' class='text-center'>Rak Bad</td>
            <td width='10%' class='text-center'>Alasan Bad</td>
        </tr>
        <?php $index = 1;
        if($sparepart_bad->num_rows() > 0){
            foreach ($sparepart_bad->result() as $part) : ?>
                <tr>
                    <td><?= $index ?>.</td>
                    <td><?= $part->id_part ?></td>
                    <td><?= $part->nama_part ?></td>
                    <td class='text-center'><?= $part->qty_bad ?></td>
                    <td class='text-center'><?= $part->id_gudang_bad ?></td>
                    <td class='text-center'><?= $part->id_rak_bad ?></td>
                    <td><?= $part->nama_claim ?></td>
                </tr>
            <?php $index++;
            endforeach; ?>
        <?php }else{ ?>
            <tr>
                <td colspan="7" class='text-center'> - </td>
            </tr>
        <?php }?>
        
    </table>


    <table class="table" style="margin-top:20px">
      <tr>
        <td style="font-weight:bold;text-align:left;font-size: 12px;">Detail Part Tidak Diterima</td>
      </tr>
    </table>
    <table style='margin-top: 20px; margin-bottom: 50px;' class="table table-borderedx table-sm">
        <tr class='header'>
            <td width='5%'>No.</td>
            <td width='16%'>Kode Part</td>
            <td width='21%'>Deskripsi Part</td>
            <td width='10%' class='text-center'>Qty Tidak Diterima</td>
            <td width='10%' class='text-center'>Alasan Tidak Diterima</td>
        </tr>
        <?php $index = 1;
        if($sparepart_tidak_diterima->num_rows() > 0){
        foreach ($sparepart_tidak_diterima->result() as $part) : ?>
            <tr>
                <td><?= $index ?>.</td>
                <td><?= $part->id_part ?></td>
                <td><?= $part->nama_part ?></td>
                <td class='text-center'><?= $part->qty_tidak_terima ?></td>
                <td class='text-center'><?= $part->nama_claim ?></td>
            </tr>
        <?php $index++;
        endforeach; ?>
        <?php }else{ ?>
            <tr>
                <td colspan="5" class='text-center'> - </td>
            </tr>
        <?php }?>
    </table>

    <table>
                <tr>
                    <td colspan ="2" style='font-size: 13px;'>Dibuat Oleh,</td>
                    <td style='font-size: 13px; padding: left 30px;'>Diperiksa,</td>
                    <td style='font-size: 13px;'>Disetujui,</td>
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
                    <td colspan ="2">__________________________</td>
                    <td style='padding: left 30px;'>______________________________</td>
                    <td>________________</td>
                </tr>
                <tr>
                    <td style='font-size: 13px;' colspan ="2">Counter Part/Frontdesk</td>
                    <td style='font-size: 13px;padding: left 30px;'>Inventory Part/Counter Part</td>
                    <td style='font-size: 13px;'>Kabeng/Kacab</td>
                </tr>
    </table>

    <footer style='position: absolute; bottom: 0'>
        <span><?= $data['id_penerimaan_barang'] ?> - Dicetak : <?= date('d-M-Y H:i:s') ?></span>
    </footer>
</body>

</html>