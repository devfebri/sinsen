<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?= $title ?></title>
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
        /* padding-left: 6px;
          padding-right: 6px; */
      }

      body {
        font-family: "Arial";
        font-size: 11pt;
      }
    }
  </style>
</head>

<?php
if ($set == 'cetak') { ?>

  <body>
    <table class="table table-borderedx">
      <tr>
        <td width="100%" align="center" colspan="5">
          <b>Report KPB <?php echo $nama_dealer; ?></b><br />&nbsp;
        </td>
      </tr>
      <tr>
    </table>


    <!-- <div style="font-weight:bold;text-align:center">Detail Pekerjaan</div> -->
    <table class="table table-bordered">
      <tr> 		
        <td align="center"><b>No</b></td>
        <td align="center"><b>No NJB</b></td>
        <td align="center"><b>Tipe Motor</b></td>
        <td align="center"><b>No Mesin</b></td>
        <td align="center"><b>No Rangka</b></td>
        <td align="center"><b>Tgl Beli</b></td>
        <td align="center"><b>Tgl Service</b></td>
        <td align="center"><b>KM Terakhir</b></td>
        <td align="center"><b>KPB</b></td>
        <td align="center"><b>Jasa</b></td>
        <td align="center"><b>Oli (Btl)</b></td>
      </tr>
      <?php 
	    $sum_kpb_ke1=0;
      $sum_kpb_ke2=0;
      $sum_kpb_ke3=0;
      $sum_kpb_ke4=0;
      $total_kpb =0;
      $total_rupiah_oli=0;
      $total_botol_oli=0;
      $no = 1;
      foreach ($rows as $row) { 
        $rupiah_oli = $row->tot_pekerjaan;
			  $botol_oli = $row->tot_qty_oli;?>
        <tr>
          <td><?php echo $no?></td>
          <td><?php echo $row->no_njb?></td>
          <td><?php echo $row->id_tipe_kendaraan?></td>
          <td><?php echo $row->no_mesin?></td>
          <td><?php echo $row->no_rangka?></td>
          <td><?php echo $row->tgl_pembelian_indo?></td>
          <td><?php echo $row->tgl_servis_indo?></td>
          <td><?php echo $row->km_terakhir?></td>
          <td style='text-align:center'><?php echo $row->kpb_ke?></td>
          <td>Rp. <?php echo number_format($row->tot_pekerjaan,0,',','.')?></td>
          <td style='text-align:center' ><?php echo $row->tot_qty_oli?></td>
 			  </tr>
      <?php $no++;
         if ($row->kpb_ke === '1'){
            $sum_kpb_ke1++;
        }elseif($row->kpb_ke === '2'){
            $sum_kpb_ke2++;
        }elseif($row->kpb_ke === '3'){
            $sum_kpb_ke3++;
        }else{
            $sum_kpb_ke4++;
        }

        $total_rupiah_oli += $rupiah_oli;
        $total_botol_oli += $botol_oli;
      } ?>
      <tr>
          <td style='text-align:center' colspan='8'><b>Grand Total</b></td>
          <td style='text-align:center'><b><?php echo $cetak_laporan->num_rows()?></b></td>
          <td style='text-align:center'><b><?php echo number_format($total_rupiah_oli,0,',','.')?></b></td>
          <td style='text-align:center'><b><?php echo $total_botol_oli?></b></td>
 			</tr>
    </table>

    <table class="table table-bordered">
      <tr>
        <td>KPB Ke 1 = <?php echo $sum_kpb_ke1?></td>
      </tr>
      <tr>
        <td>KPB Ke 2 = <?php echo $sum_kpb_ke2?></td>
      </tr>
      <tr>
        <td>KPB Ke 3 = <?php echo $sum_kpb_ke3?></td>
      </tr>
      <tr>
        <td>KPB Ke 4 = <?php echo $sum_kpb_ke4?></td>
      </tr>
      <tr>
        <td>Total KPB = <?php echo $sum_kpb_ke1+$sum_kpb_ke2+$sum_kpb_ke3+$sum_kpb_ke4?></td>
      </tr>
    </table>
  </body>

</html>

<?php } ?>