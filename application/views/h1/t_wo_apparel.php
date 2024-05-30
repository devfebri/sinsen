<button type="reset" class="btn btn-warning btn-flat btn-block" disabled>Detail Apparel</button>                                             
<br>

<table class="table table-bordered table-hovered myTable1" width="100%">
  <tr>
    <th width='10%'>ID Apparel</th>
    <th width='10%'>Nama Apparel</th>
    <th width='10%'>Qty</th>
    <th width='10%'>Stock On Hand</th>                    
  </tr>
  <tbody>
  <?php 
  $no=1;
  foreach ($dt_data->result() as $isi) {
    $jum = $dt_data->num_rows();
    $tr = $this->db->query("SELECT * FROM tr_stok_apparel WHERE id_apparel = '$isi->id_apparel'");
    if($tr->num_rows() > 0){
      $f = $tr->row();
      $qty = $f->qty;
       if ($mode=='detail') {
        $qty_paket_apparel = $isi->qty_apparel;
    }else{
       $qty_paket_apparel = $isi->qty_apparel  * $qty_paket;
    }
     
    }else{
      $qty = 0;
    }    

    if ($qty < $qty_paket_apparel) {
      $warna='#e0a3a3';
    }else{
      $warna='';
    }
    echo "
    <tr style='background-color:$warna'>
      <td>
        <input type='hidden' name='id_apparel_$no' value='$isi->id_apparel'>      
        <input type='hidden' name='qty_apparel_$no' value='$qty_paket_apparel'>      
        <input type='hidden' name='jum_apparel' value='$jum'>      
        $isi->id_apparel
      </td>
      <td>$isi->apparel</td>
      <td>$qty_paket_apparel</td>
      <td>
        $qty
      </td>
    </tr>
    ";  
    $no++;
  }
  ?>
  </tbody>  
</table>  