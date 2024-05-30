<?php 
function mata_uang3($a){
  return number_format($a, 0, ',', '.');
}
?>
<table id="myTable" class="table myt order-list" border="0">     
  <thead>
    <tr>              
      <th width="5%">No</th>
      <th width="10%">Part Number</th>              
      <th width="20%">Nama Part</th>
      <th width="5%">HET</th>
      <th width="10%">Disc Satuan Dealer (%)</th>
      <th width="10%">Disc Campaign (%)</th>
      <th width="5%">Qty On Hand</th>      
      <th width="5%">Qty AVS</th>      
      <th width="5%">Qty SO</th>      
      <th width="10%">Qty Suggest (PB)</th>      
      <th width="5%">Qty Supply</th>      
      <th width="5%">Nilai (Amount)</th>        
    </tr>
  </thead>
  <tbody>            
    <?php 
    $no=1;$total=0;$g_total=0;
    foreach ($sql->result() as $isi) {
      $jum = $sql->num_rows();
      $tgl1 = $isi->tgl_so;
      //$tgl1 = date("Y-m-d");
      $tgl2 = date("Y-m-d");
      $amount = $this->db->query("SELECT SUM(het * qty_order) AS harga FROM tr_so_part_detail WHERE no_so_part = '$isi->no_so_part'")->row();            
      $qty_order = $this->db->query("SELECT * FROM tr_so_part_detail WHERE no_so_part = '$isi->no_so_part'")->row();            
      $sel = $this->db->query("SELECT SUM(qty_order) AS jum FROM tr_so_part_detail INNER JOIN tr_so_part ON tr_so_part.no_so_part = tr_so_part_detail.no_so_part 
        WHERE tr_so_part_detail.id_part = '$isi->id_part' AND tr_so_part.tgl_so BETWEEN '$tgl1' AND '$tgl2'")->row();
      if(is_null($sel->jum)){
        $sel_jum = 0;
      }else{
        $sel_jum = $sel->jum;
      }
      $qty = $this->db->query("SELECT * FROM tr_stok_part WHERE id_part = '$isi->id_part'");
      if($qty->num_rows() > 0){
        $qty_part = $qty->row()->qty;
        $qty_avs = $qty->row()->qty_avs;
      }else{
        $qty_part = 0;$qty_avs=0;
      }
      $pb = $this->db->query("SELECT * FROM tr_pb_sim_part_detail INNER JOIN tr_pb_sim_part ON tr_pb_sim_part.no_pb_sim_part = tr_pb_sim_part_detail.no_pb_sim_part
        WHERE tr_pb_sim_part_detail.no_so_part = '$isi->no_so_part'")->row();
      $qty_suggest = ($sel_jum * ($pb->fix/100)) / ($sel_jum * $qty_order->qty_order);
      echo "
        <tr>
          <td>$no</td>          
          <td>$isi->id_part</td>
          <td>$isi->nama_part</td>
          <td>".mata_uang3($isi->harga_md_dealer)."</td>          
          <td>0</td>          
          <td>0</td>          
          <td>$qty_part</td>          
          <td>$qty_avs</td>          
          <td>$isi->qty_order</td>          
          <td>$qty_suggest</td>          
          <td>
            <input type='hidden' value='$jum' name='jum' id='jum'>
            <input type='hidden' value='$isi->id_part' name='id_part_$no'>
            <input type='hidden' value='$qty_suggest' name='qty_suggest_$no'>
            <input type='hidden' value='$isi->harga_md_dealer' id='harga_$no' name='harga_$no'>
            <input type='hidden' value='0' id='disc_satuan_$no' name='disc_satuan_$no'>
            <input type='hidden' value='0' id='disc_campaign_$no' name='disc_campaign_$no'>                      
            <input type='text' class='form-control isi' id='qty_supply_$no' onchange='kalikan()' name='qty_supply_$no'>
          </td>          
          <td>
            <input type='text' class='form-control isi' id='amount_$no' name='amount_$no' readonly>
          </td>                    
        </tr>";
        $no++;
        $total += $amount->harga;
    }
    ?>
  </tbody>  
  <tfoot>
    <tr>
      <td align="right" colspan="8">Sub Total</td>
      <td colspan="4">
        <input type='text' class='form-control isi' style="text-align: right;" value="0" id='sub_total' name='sub_total' readonly>
      </td>      
    </tr>
    <tr>
      <td align="right" colspan="8">Total PPN</td>
      <td colspan="4">
        <input type='text' class='form-control isi' style="text-align: right;" value="0" id='total_ppn' name='total_ppn' readonly>
      </td>      
    </tr>
    <tr>
      <td align="right" colspan="8">Total</td>
      <td colspan="4">
        <input type='text' class='form-control isi' style="text-align: right;" value="0" id='total' name='total' readonly>
      </td>      
    </tr>
  </tfoot>
</table>
