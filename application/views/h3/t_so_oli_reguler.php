<?php 
function mata_uang3($a){
  return number_format($a, 0, ',', '.');
}
?>
<table id="myTable" class="table myt order-list" border="0">     
  <thead>
    <tr>              
      <th>Kode Part</th>              
      <th width="30%">Nama Part</th>
      <th width="10%">HET</th>
      <th width="10%">Qty On Hand MD</th>      
      <th width="10%">Qty Order</th>      
      <th width="10%">Total Harga</th>        
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>      
    <?php 
    $total=0;$g_total=0;
    foreach ($sql->result() as $isi) {      
      $tot = $isi->qty_order * $isi->het;
      $g_total += $tot;
      echo "
        <tr>
          <td>$isi->id_part</td>
          <td>$isi->nama_part</td>
          <td>$isi->het</td>
          <td>$isi->qty_on_hand</td>          
          <td>$isi->qty_order</td>
          <td align='right'>".mata_uang3($tot)."</td>
          <td width='5%'>"; ?>
            <button title="Hapus Data"
                class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
                onClick="hapus_sim('<?php echo $isi->id_so_oil_detail; ?>','<?php echo $isi->id_part; ?>')"></button>
          </td>
        </tr>
    <?php
    }
    ?>
    <tr>
      <td>
        <input id="id_part" readonly type="text" data-toggle="modal" data-target="#Partmodal" name="id_part" class="form-control isi" placeholder="ID Part">
      </td> 
      <td>
        <input id="nama_part" readonly type="text" name="nama_part" class="form-control isi" placeholder="Nama Part">
      </td> 
      <td>
        <input id="het" readonly type="text" name="het" class="form-control isi" placeholder="HET">
      </td> 
      <td>
        <input id="qty_on_hand" readonly type="text" name="qty_on_hand" class="form-control isi" placeholder="Qty SIM Part">
      </td>       
      <td>
        <input id="qty_order" onchange="kalikan()" onkeypress="return number_only(event)" type="text" name="qty_order" class="form-control isi" placeholder="Qty Order">
      </td> 
      <td>
        <input id="nilai" type="text" name="nilai" readonly class="form-control isi" placeholder="Nilai">
      </td> 
      <td>
        <button type="button" onClick="simpan_sim()" class="btn btn-sm btn-primary btn-flat btn-xs"> Add</button>                          
      </td>
    </tr>          
  </tbody>
  <tfoot>
    <tr>
      <td colspan="5" align="right">Total</td>
      <td align="right" colspan="0">
        <input type='text' id='total_harga' value="<?php echo mata_uang3($g_total) ?>" style='text-align: right;' readonly class='form-control isi'>
      </td>
      <td></td>
    </tr>
  </tfoot>
</table>
