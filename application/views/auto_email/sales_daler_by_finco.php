<?php $this->load->view('email/header'); ?>

<body class="container">
  <div class="main">
    <div class="content">
      <div style="font-weight:bold">
        PT. Sinar Sentosa Primatama <br>
        Sales Dealer By Finco <br>
        Date : <?= $tanggal ?> <br>
        Time : <?= $waktu ?><br> <br>
      </div>
      <table class="table table-bordered">
        <tr style="font-weight:bold">
          <td>No</td>
          <td>Dealer</td>
          <?php foreach ($get_data['finco'] as $fc) {
            echo "<td>$fc->finance_company</td>";
          }
          $rs = count($get_data['result']) + 2;
          echo "<td>Other</td>
    <td>Cash</td>
    <td>Total</td>
    <td rowspan=$rs></td>";
          foreach ($get_data['finco'] as $fc) {
            echo  "<td>$fc->finance_company</td>";
          }
          echo  "<td>Other</td>
    <td>Cash</td>
    <td>Total</td>";
          echo  "
  </tr>";
          foreach ($get_data['result'] as $key => $vl) {
            echo  "<tr>";
            $k = $key + 1;
            echo  "<td>" . $k . "</td>";

            foreach ($vl['unit'] as $vu) {
              echo  "<td>" . $vu . "</td>";
            }
            foreach ($vl['persen'] as $ps) {
              echo  "<td>" . $ps . " %</td>";
            }
            echo  "
</td>";
          }
          echo "
<tr>";
          echo "<td colspan=2><b>Total</b></td>";
          $g_tot = 0;
          foreach ($get_data['total'] as $tot) {
            echo "<td><b>$tot</b></td>";
            $g_tot += $tot;
          }
          foreach ($get_data['tot_persen'] as $tot) {
            echo "<td><b>$tot %</b></td>";
            // $g_tot += $tot;
          } ?>
        <tr>
          <!-- <td><b><?= $g_tot ?></b></td> -->
        </tr>
      </table>
    </div>
  </div>
</body>