<!DOCTYPE html>
<html>
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
if ($set=='print'){ ?>
 <table class="table table-bordered">
    <tr>
      <td width="100%" align="center" colspan="2"><b>Antrian</b><br>&nbsp;</td>
    </tr>
    <tr>
      <td width="20%">No. Antrian</td><td width="20%">: <?= $row->id_antrian ?></td>
    </tr>
    <tr>
        <td >No. Polisi</td><td>: <?= $row->no_polisi ?></td>
    </tr>
  </table>
  
<?php } ?>
</body>
</html>
