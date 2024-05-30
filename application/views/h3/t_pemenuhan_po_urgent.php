<?php 
function mata_uang3($a){
  return number_format($a, 0, ',', '.');
}
?>
<table id="myTable" class="table myt order-list" border="0">     
  <thead>
    <tr>              
      <th width="5%">No</th>
      <th>Kode Part</th>              
      <th width="40%">Nama Part</th>
      <th width="10%">HET</th>
      <th width="10%">Qty On Hand MD</th>
      <th width="10%">Qty Order</th>      
      <th width="10%">Total Harga</th>        
    </tr>
  </thead>
  <tbody>            
    <?php 
    $no=1;$total=0;$g_total=0;
    foreach ($sql->result() as $isi) {
      $jum = $sql->num_rows();
      $total = mata_uang3($g_tot = $isi->qty_part * $isi->harga_md_dealer);
      $harga = mata_uang3($isi->harga_md_dealer);
      $g_total += $g_tot;
      echo "
        <tr>
          <td>$no</td>
          <td>"; ?>
            <input type='text' id='kode_part' value='<?php echo $isi->id_part ?>' data-toggle='modal' data-target='.modal_detail' id_part='<?php echo $isi->id_part ?>' onclick="detail_popup('<?php echo $isi->id_part ?>','<?php echo $isi->request_id ?>')" placeholder='Browse' readonly class='form-control isi'>            
          </td>
          <?php
          echo "
          <td>
            <input type='text' id='part' value='$isi->nama_part' readonly class='form-control isi'>
          </td>
          <td>
            <input type='text' id='het' value='$harga' style='text-align: right;' readonly class='form-control isi'>
          </td>
          <td>
            <input type='text' id='qty_on_hand' value='$isi->qty_part' style='text-align: right;' readonly class='form-control isi'>
          </td>
          <td>
            <input type='text' id='qty_order' value='$isi->qty_part' style='text-align: right;' readonly class='form-control isi'>
          </td>
          <td>
            <input type='hidden' value='$jum' name='jum'>
            <input type='hidden' value='$isi->qty_part' name='qty_$no'>
            <input type='hidden' value='$isi->id_part' name='id_part_$no'>
            <input type='hidden' value='$isi->harga_md_dealer' name='harga_$no'>
            <input type='hidden' value='$isi->request_id' name='request_id_$no'>
            <input type='hidden' value='$isi->dokumen_nrfs_id' name='dokumen_nrfs_id_$no'>
            <input type='text' id='total_harga' value='$total' style='text-align: right;' readonly class='form-control isi'>
          </td>
        </tr>
      ";
      $no++;
    }
    ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="6" align="right">Grand Total</td>
      <td align="right" colspan="0">
        <input type='text' id='total_harga' value="<?php echo mata_uang3($g_total) ?>" style='text-align: right;' readonly class='form-control isi'>
      </td>
    </tr>
  </tfoot>
</table>
