<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php     
  $row = $sql->row();
    echo "   
    <tr>     
      <td rowspan='4' width='10%' colspan='3'></td>
      <td rowspan='4' width='20%'></td>
      <td rowspan='4' width='14%' align='right'><b>Total</td>

      <td width='17.8%' align='right'><b>AHM Dist to MD</td>
      <td width='7%'><b>$row->jum_m1</td>
      <td width='7%'><b>$row->jum_m</td>
      <td width='7%''><b>$row->jum_fix</td>
      <td width='7%'><b>$row->jum_t1</td>
      <td width='7%'><b>$row->jum_t2</td>
    </tr>
    <tr>
      <td width='17.8%' align='right'><b>Retail Sales</td>
      <td width='7%'><b>$row->um_m1</td>
      <td width='7%'><b>$row->um_m</td>
      <td width='7%''><b>$row->um_fix</td>
      <td width='7%'><b>$row->um_t1</td>
      <td width='7%'><b>$row->um_t2</td>
    </tr>
    <tr>
      <td width='17.8%' align='right'><b>Total Stock</td>
      <td width='7%'><b>$row->ju_m1</td>
      <td width='7%'><b>$row->ju_m</td>
      <td width='7%''><b>$row->ju_fix</td>
      <td width='7%'><b>$row->ju_t1</td>
      <td width='7%'><b>$row->ju_t2</td>
    </tr>
    <tr>
      <td width='17.8%' align='right'><b>Total Stock Days</td>
      <td width='7%'><b>$row->j_m1</td>
      <td width='7%'><b>$row->j_m</td>
      <td width='7%''><b>$row->j_fix</td>
      <td width='7%'><b>$row->j_t1</td>
      <td width='7%'><b>$row->j_t2</td>
    </tr>"; ?>        
</table>
