  <!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->
    <?php 
        function mata_uang($a){
            return number_format($a, 0, ',', '.');
        } 
        // function get_kry($id_user)
        // {
        //   $get =  $this->db->query("SELECT * FROM ms_user JOIN ms_karyawan_dealer ON ms_karyawan_dealer.id_karyawan_dealer=ms_user.id_karyawan_dealer WHERE id_user=$id_user")->row()->nama_lengkap;
        //   return $get;
        // }
    ?>

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
if ($set=='cetak_activity_persales'){ ?>
 <table class="table table-borderedx">
    <tr>
      <td width="100%" align="center" colspan="5"><b>Activity List Sales People</b><br>&nbsp;</td>
    </tr>
    <tr>
      <td width="20%">Tanggal</td><td>: <?= date('d/m/Y') ?></td>
      <td></td>
    </tr>
    <tr>
      <td width="20%">ID Sales People</td><td>: <?= $row->id_flp_md ?></td>
      <td></td>
    </tr>
    <tr>
      <td width="20%">Nama Sales</td><td>: <?= $row->sales ?></td>
      <td></td>
    </tr>
  </table>
  <p style="text-align: center;font-weight: bold;">Aktifitas</p>
  <table class="table table-bordered">
    <tr>
      <td style="line-height: 18px;font-weight: bold;">No</td>
      <td style="line-height: 18px;font-weight: bold;">Aktifitas</td>
      <td style="line-height: 18px;font-weight: bold;">Nama Konsumen</td>
      <td style="line-height: 18px;font-weight: bold;">Status In Progressed / Completed</td>
      <td style="line-height: 18px;font-weight: bold;">Keterangan</td>
    </tr>
    <?php foreach ($result as $key=> $rs): ?>
      <tr>
        <td><?= $key+1 ?></td>
        <td><?= $rs->detail_activity ?></td>
        <td><?= $rs->nama_konsumen ?></td>
        <td><?= $rs->status ?></td>
        <td><?= $rs->keterangan ?></td>
      </tr>
    <?php endforeach ?>
  </table>
<?php } ?>
</body>
</html>
