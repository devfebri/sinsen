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
      <th width="5%">Harga  DPP</th>
      <th width="5%">Qty Supply</th>      
      <th width="10%">Disc Satuan Dealer (%)</th>
      <th width="10%">Disc Campaign (%)</th>
      <th width="5%">Harga Setelah Diskon</th>            
      <th width="5%">Nilai (Amount)</th>        
      <th width="5%">Harga Beli (Sebelum Diskon AHM)</th>        
      <th width="5%">Selisih</th>        
    </tr>
  </thead>
  <tbody>            
    <?php 
    $no=1;$total=0;$g_total=0;
    foreach ($sql->result() as $isi) {
      $jum = $sql->num_rows();
      $dpp = $isi->harga_md_dealer/1.1;
      $harga_disc = $dpp - ($isi->harga_md_dealer * ($isi->disc_satuan/100)) - ($isi->harga_md_dealer * ($isi->disc_satuan/100));
      echo "
        <tr>
          <td>$no</td>          
          <td>$isi->id_part</td>
          <td>$isi->nama_part</td>
          <td>".mata_uang3($dpp)."</td>          
          <td>$isi->qty_supply</td>          
          <td>0</td>          
          <td>0</td>          
          <td>".mata_uang3($harga_disc)."</td>          
          <td>".mata_uang3($nilai = $harga_disc * $isi->qty_supply)."</td>          
          <td>".mata_uang3($harga_disc2 = $isi->harga_md_dealer * $isi->qty_supply)."</td>                    
          <td>".mata_uang3($harga_disc2 - $harga_disc)."</td>                    
        </tr>";
        $no++;        
        $total += $nilai;
    }
    ?>
  </tbody>  
  <tfoot>
    <tr>
      <td align="right" colspan="8">Sub Total</td>
      <td colspan="4">
        <input type='text' value="<?php echo $total ?>" class='form-control isi' style="text-align: right;" value="0" id='sub_total' name='sub_total' readonly>
      </td>      
    </tr>
    <tr>
      <td colspan="2">Total Insentif</td>
      <td>0</td>
      <td colspan="2"></td>
      <td><i class="fa fa-check"></i></td>
      <td colspan="2">Diskon Insentif</td>
      <td colspan="3">
        <input type='text' class='form-control isi' style="text-align: right;" value="0" id='diskon_insentif' onchange="kurangi()" name='diskon_insentif'>
      </td>
    </tr>
    <tr>
      <td colspan="5"></td>            
      <td><i class="fa fa-check"></i></td>
      <td colspan="2">Diskon Cashback</td>
      <td colspan="3">
        <input type='text' class='form-control isi' style="text-align: right;" value="0" id='diskon_cashback' onchange="kurangi()" name='diskon_cashback'>
      </td>
    </tr>
    <tr>
      <td align="right" colspan="8">Total Diskon</td>
      <td colspan="4">
        <input type='text' class='form-control isi' style="text-align: right;" value="0" id='total_diskon' name='total_diskon' readonly>
      </td>      
    </tr>
    <tr>
      <td align="right" colspan="8">Total PPN</td>
      <td colspan="4">
        <input type='text' class='form-control isi' style="text-align: right;" value="<?php echo $ppn = $total * 0.1 ?>" id='total_ppn' name='total_ppn' readonly>
      </td>      
    </tr>
    <tr>
      <td align="right" colspan="8">Total</td>
      <td colspan="4">
        <input type='text' class='form-control isi' style="text-align: right;" value="<?php echo $total - $ppn  ?>" id='total' name='total' readonly>
      </td>      
    </tr>
  </tfoot>
</table>
