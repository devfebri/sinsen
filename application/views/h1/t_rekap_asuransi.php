<?php 
function mata_uang2($a){
    	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
    return number_format($a, 0, ',', '.');
}
?>
<table class="table table-bordered table-hovered" id="example1" width="100%">
  <thead>
    <tr>
      <th>Kode Tipe</th>
      <th>Tipe Kendaraan</th>
      <th>Harga Satuan</th>
      <th>Qty</th>
      <th>Total</th>                    
      <th>Qty Asuransi</th>
      <th>Total Asuransi</th>                    
    </tr>   
  </thead>
  <tbody>
  <?php 
  $no=1;
  $t1=0;$t2=0;
  foreach ($dt_rekap->result() as $isi) {
    $asur = ($isi->qty * $presentase) / 100;
    $bulan = floor($asur);
    if ($asur<1) {
      $bulan=1;
    }
    $total = $bulan * $isi->harga;
    $jum = $dt_rekap->num_rows();
    $jumlah = $isi->qty * $isi->harga;
    echo "
    <tr>
      <td>
        <input type='hidden' name='jum' value='$jum'>
        <input type='hidden' name='id_tipe_kendaraan_$no' value='$isi->id_tipe_kendaraan'>
        <input type='hidden' name='harga_satuan_$no' value='$isi->harga'>
        <input type='hidden' name='qty_$no' value='$isi->qty'>
        <input type='hidden' name='total_$no' value='$jumlah'>
        <input type='hidden' name='qty_asuransi_$no' value='$bulan'>
        <input type='hidden' name='total_asuransi_$no' value='$total'>
        $isi->id_tipe_kendaraan
      </td>
      <td>$isi->tipe_ahm</td>
      <td>".mata_uang2($isi->harga)."</td>
      <td>".mata_uang2($isi->qty)."</td>
      <td>".mata_uang2($jumlah)."</td>
      <td>".mata_uang2($bulan)."</td>
      <td>".mata_uang2($total)."</td>
    </tr>
    ";
    $t1 = $t1 + $total;
    $no++;
  }
  $cek = $this->m_admin->getByID("ms_rate_asuransi","id_vendor",$id_vendor);
  if($cek->num_rows() > 0){
    $t = $cek->row();
    $rate_premi     = ($t->rate_premi * $t1) / 100;
    $biaya_polis    = $t->biaya_polis;
    $biaya_materai  = $t->biaya_materai;
  }else{
    $rate_premi     = "";
    $biaya_polis    = "";
    $biaya_materai  = "";
  }
  ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="5"></td>
      <td>Total :</td>
      <td>
        <input type='hidden' name='total' value='<?php echo $t1 ?>'>
        <input type='hidden' name='premi_asuransi' value='<?php echo $rate_premi ?>'>
        <input type='hidden' name='biaya_polis' value='<?php echo $biaya_polis ?>'>
        <input type='hidden' name='biaya_materai' value='<?php echo $biaya_materai ?>'>        
        <?php echo mata_uang2($t1) ?>      
      </td>
    </tr>
    <tr>
      <td colspan="5"></td>
      <td>Premi Asuransi :</td>
      <td><?php echo mata_uang2($rate_premi) ?></td>
    </tr>
    <tr>
      <td colspan="5"></td>
      <td>Biaya Polis :</td>
      <td><?php echo mata_uang2($biaya_polis) ?></td>
    </tr>
    <tr>
      <td colspan="5"></td>
      <td>Biaya Materai :</td>
      <td><?php echo mata_uang2($biaya_materai) ?></td>
    </tr>               
    <tr>
      <td colspan="5"></td>
      <td>Total Bayar :</td>
      <td>
        <?php echo mata_uang2($tt = $rate_premi + $biaya_polis + $biaya_materai) ?>
        <input type='hidden' name='total_bayar' value='<?php echo $tt ?>'>
      </td>
    </tr>               
  </tfoot> 
</table>  