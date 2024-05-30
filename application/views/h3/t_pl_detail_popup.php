
<table id="example2" class="table table-bordered table-hovered myTable1" width="100%">
  <thead>
    <th>Kode Part</th>
    <th>Nama Part</th>
    <th>Qty Supply</th>
  </thead>
  <tbody>
    <?php 
    $dt = $this->db->query("SELECT * FROM tr_pl_part_detail 
        LEFT JOIN ms_part ON tr_pl_part_detail.id_part = ms_part.id_part
        WHERE tr_pl_part_detail.no_pl_part = '$no_pl_part'");
    foreach ($dt->result() as $isi) {
      echo "
      <tr>
        <td>$isi->id_part</td>
        <td>$isi->nama_part</td>
        <td>$isi->qty_supply</td>
      </tr>
      ";
    }
    ?>
  </tbody>
</table>