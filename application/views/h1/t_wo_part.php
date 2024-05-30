<button type="reset" class="btn btn-primary btn-flat btn-block" disabled>Detail Part</button>                                             
<table class="table table-bordered table-hovered myTable1" width="100%">
  <thead>
    <tr>
      <th width='10%'>ID Part</th>
      <th width='10%'>Nama Part</th>
      <th width='10%'>Qty</th>
      <th width='10%'>Stock On Hand</th>                    
    </tr>
  </thead>
  <tbody>
  <?php
  $no=1; 
  foreach ($dt_data->result() as $isi) {
    $jum = $dt_data->num_rows();
    $tr = $this->db->query("SELECT * FROM tr_stok_part_h1 WHERE id_part = '$isi->id_part'");
    if($tr->num_rows() > 0){
      $q = $tr->row();
      $qty = $q->qty_h1;
    }else{
      $qty = 0;
    }
    if ($mode=='detail') {
       $qty_w_qty_paket = $isi->qty_part;
    }else{
      $qty_w_qty_paket = $isi->qty_part * $qty_paket;
    }
    if ($qty < $qty_w_qty_paket) {
      $warna = "#e0a3a3";
    }else{
      $warna='';
    }
    echo "
    <tr style='background-color:$warna'>
      <td >
        <input type='hidden' name='id_part_$no' value='$isi->id_part'>
        <input type='hidden' name='qty_part_$no' value='$qty_w_qty_paket'>
        <input type='hidden' name='jum_part' value='$jum'>
        $isi->id_part
      </td>
      <td>$isi->nama_part</td>
      <td>$qty_w_qty_paket</td>
      <td>$qty</td>
    </tr>
    ";
    $no++;
  }
  ?>
  </tbody>  
</table>