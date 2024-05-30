<?php
	$date_buat = date("dmY-Hi", strtotime($date_create));
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=SSU_Document_Handling-$date_buat.xls");
?>
<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <title>Cetak</title>
      <style>
        .str{ mso-number-format:\@; } 
        @media print {
          @page {
            sheet-size: 330mm 210mm;
            margin-left: 0.8cm;
            margin-right: 0.8cm;
            margin-bottom: 1cm;
            margin-top: 1cm;
          }
          
          .text-center{text-align: center;}
          .bold{font-weight: bold;}
          .table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
           /*border-collapse: separate;*/
          }

          .table-bordered tr td {
            border: 0.01em solid black;
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
      <?php if($tanggal2 != ''){ ?>
        <table>
          <tr>
            <th>Periode : </th>
            <th><?php echo tgl_indo($tanggal1).' - '. tgl_indo($tanggal2) ?></th>
          </tr>
        </table>
        <tr></tr>

        <table class='table table-bordered' border="1" style='font-size: 10pt' width='100%'>
          <tr>           
            <th bgcolor='lightblue' class='bold'>No.</th>      
            <th bgcolor='lightblue' class='bold'>Kode Dealer</th>      
            <th bgcolor='lightblue' class='bold'>Dealer</th>
            <th bgcolor='lightblue' class='bold'>Nama Konsumen</th>
            <th bgcolor='lightblue' class='bold'>Tgl SPK</th>
            <th bgcolor='lightblue' class='bold'>Tgl SSU</th>
            <th bgcolor='lightblue' class='bold'>Kode Tipe</th>
            <th bgcolor='lightblue' class='bold'>Deskripsi Tipe</th>
            <th bgcolor='lightblue' class='bold'>Kode Warna</th>
            <th bgcolor='lightblue' class='bold'>No Rangka</th>
            <th bgcolor='lightblue' class='bold'>No Mesin</th>
            <th bgcolor='lightblue' class='bold'>No Polisi</th>
            <th bgcolor='lightblue' class='bold'>No STNK</th>
            <th bgcolor='lightblue' class='bold'>Tgl Serah STNK ke Konsumen</th>
            <th bgcolor='lightblue' class='bold'>Nama Penerima STNK</th>
            <th bgcolor='lightblue' class='bold'>No BPKB</th>
            <th bgcolor='lightblue' class='bold'>Tgl Serah BPKB ke Konsumen</th>
            <th bgcolor='lightblue' class='bold'>Nama Penerima BPKB</th>
            <th bgcolor='lightblue' class='bold'>Tgl Serah Plat ke Konsumen</th>
            <th bgcolor='lightblue' class='bold'>Nama Penerima Plat</th>

          </tr>

          <?php
            $start=1;

            foreach ($query->result() as $dw){
       ?>
                <tr>
                    <td><?php echo $start ?></td>
                    <td class="str"><?php echo $dw->kode_dealer_md ?></td>
                    <td><?php echo $dw->nama_dealer ?></td>
                    <td><?php echo $dw->nama_konsumen?></td>
                    <td><?php echo $dw->tgl_spk ?></td>
                    <td><?php echo $dw->tgl_ssu ?></td>
                    <td><?php echo $dw->id_tipe_kendaraan?></td>
                    <td><?php echo $dw->tipe_ahm?></td>
                    <td><?php echo $dw->kode_warna?></td>
                    <td><?php echo $dw->no_rangka?></td>
                    <td><?php echo $dw->no_mesin?></td>
                    <td><?php echo $dw->no_pol?></td>
                    <td><?php echo $dw->no_stnk?></td>
                    <td><?php echo $dw->tgl_serah_terima_stnk?></td>
                    <td><?php echo $dw->penerima_stnk?></td>
                    <td><?php echo $dw->no_bpkb?></td>
                    <td><?php echo $dw->tgl_serah_terima_bpkb?></td>
                    <td><?php echo $dw->penerima_bpkb?></td>
                    <td><?php echo $dw->tgl_serah_terima_plat?></td>
                    <td><?php echo $dw->penerima_plat?></td>

                </tr>

            <?php
                $start++;
            }

            ?>
        </table>

      <?php }else{ ?>
        <p>Tanggal Rekap Harus ditentukan dulu.</p>
      <?php } ?>                
  </body>
</html>
