<?php 
function mata_uang3($a){
  return number_format($a, 0, ',', '.');
}
?>
<table id="myTable" class="table myt order-list" border="0">     
  <thead>
    <tr>              
      <th width="5%">No</th>
      <th width="15%">No List SO</th>              
      <th width="10%">Tgl SO</th>
      <th width="20%">Nama Customer</th>
      <th width="30%">Alamat</th>
      <th width="10%">Nilai (Amount)</th>      
      <th width="5%">Aksi</th>        
    </tr>
  </thead>
  <tbody>            
    <?php 
    $no=1;$total=0;$g_total=0;
    foreach ($sql->result() as $isi) {
      $jum = $sql->num_rows();
      $amount = $this->db->query("SELECT SUM(het * qty_order) AS harga FROM tr_so_spare_detail WHERE no_so_spare = '$isi->no_so_spare'")->row();            
      echo "
        <tr>
          <td>$no</td>
          <td>"; ?>
            <input type='text' id='no_so_spare' value='<?php echo $isi->no_so_spare ?>' data-toggle='modal' data-target='.modal_detail' id_part='<?php echo $isi->no_so_spare ?>' onclick="detail_popup('<?php echo $isi->no_so_spare ?>')" placeholder='View' readonly class='form-control isi'>                        
          </td>
          <?php 
          echo "
          <td>$isi->tgl_so</td>
          <td>$isi->nama_dealer</td>
          <td>$isi->alamat</td>
          <td>".mata_uang3($amount->harga)."</td>
          <td>
            <input type='hidden' name='jum' value='$jum'>
            <input type='hidden' name='no_so_spare_$no' value='$isi->no_so_spare'>
            <input type='hidden' name='amount_$no' value='$amount->harga'>
            <input type='checkbox' name='cek_$no' checked>
          </td>
        </tr>";
        $no++;
    }
    ?>
  </tbody>  
</table>
