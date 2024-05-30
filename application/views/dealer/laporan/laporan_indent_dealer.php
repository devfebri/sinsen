<?php
ini_set('date.timezone', 'Asia/Jakarta');
$date = date("dmY-Hi");
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Indent Dealer-$date.xls");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Laporan Indent Dealer</title>
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

        .num {
          mso-number-format:General;
        }
        .text{
          mso-number-format:"\@";/*force text*/
        }

      </style>
</head>
<body>
	<table class='table table-bordered' border="1" style='font-size: 10pt' width='100%'>
     	 <tr>  
              <th width="5%">No</th>
	      <th>Tanggal SPK</th>
	      <th>No SPK</th>           
              <th>Nama Konsumen</th>
	      <th>No KTP</th>
	      <th>Deskripsi Tipe</th>
	      <th>Tipe</th>
	      <th>Warna</th>
	      <th>Tanda Jadi / Uang Muka</th>
	      <th>Nama Finco</th>
	      <th>Tgl PO</th>
	      <th>No PO</th>
	      <th>Status Indent</th>
              <th>Aging (Days)</th>

          </tr>

          <?php
            $start=1;
            foreach ($query2->result() as $row)
            {
                ?>
                  <tr>
                    <td><?php echo $start ?></td>
                    <td><?php echo $row->tgl_spk ?></td>                               
		    <td><?php echo $row->no_spk ?></td>                           
                    <td><?php echo $row->nama_konsumen ?></td>                            
		    <td class="text"><?php echo $row->no_ktp ?></td>                            
		    <td><?php echo $row->tipe_ahm ?></td>              
		    <td><?php echo $row->id_tipe_kendaraan ?></td>              
		    <td><?php echo $row->id_warna ?></td>                            
		    <td><?php echo $row->tanda_jadi ?></td>
		    <td><?php echo $row->finance_company ?></td>                             
		    <td><?php echo $row->tgl_pembuatan_po ?></td>                             
		    <td><?php echo $row->po_dari_finco ?></td>                             
		    <td><?php echo $row->status ?></td>                            
                    <td><?php echo $row->selisih_hari ?></td> 
                  </tr>
                  <?php $start++; ?>
                <?php            
		}
		?>
        </table>
</body>
</html>