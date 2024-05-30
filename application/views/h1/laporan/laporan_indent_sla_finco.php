<?php
// $date = date('d-m-Y');
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=SLA-FINCO.xls");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Laporan Indent FIPS</title>
	<style>

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

	<h2>Laporan SLA Finance Company Indent Priority System (FIPS) </h2>

	<table class='table table-bordered' border="1" style='font-size: 10pt' width='100%'>

      <tr>  



          <th width="5%">No</th>
	      <th>Kode Dealer</th>                  
	      <th>Nama Dealer</th>        
	      <th>Nama Konsumen</th>
        <th>Tgl SPK</th>
        <th>Tgl Entry PO</th>
        <th>Aging (Days)</th>
        <th>Kode Tipe</th>
	      <th>Deskripsi Tipe</th>
        <th>Kode Warna</th>
        <th>No Rangka</th>
	      <th>No Mesin</th>
	      <th>Finco</th>
	      



          </tr>

          

          <?php

            $start=1;

            foreach ($query->result() as $row)

            {



                ?>

                

                  <tr>

                    <td><?php echo $start ?></td>
                    <td><?php echo $row->kode_dealer_md ?></td>                            
  		              <td><?php echo $row->nama_dealer ?></td>                            
  		              <td><?php echo $row->nama_konsumen ?></td>
                    <td><?php echo $row->tanggal_spk ?></td>                            
                    <td><?php echo $row->created_at ?></td>                            
                    <td><?php echo $row->selisih_hari ?></td>                            
                    <td><?php echo $row->id_tipe_kendaraan ?></td>                            
                    <td><?php echo $row->tipe_ahm ?></td>                            
                    <td><?php echo $row->id_warna ?></td>                            
                    <td><?php echo $row->no_rangka ?></td>                            
                    <td><?php echo $row->no_mesin ?></td>                            
                    <td><?php echo $row->finance_company ?></td>                            
		              

                  </tr>

                  <?php $start++; ?>

                <?php

                

            }

            ?>



        </table>

</body>
</html>