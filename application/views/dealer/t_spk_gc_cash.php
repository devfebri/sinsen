<?php
function mata_uang($a)
{
  if (preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
  return number_format($a, 0, ',', '.');
}
?>
<button class="btn btn-block btn-danger btn-flat" disabled> Cash </button> <br>
<div class="form-group">
  <label for="inputEmail3" class="col-sm-2 control-label">On/Off The Road</label>
  <div class="col-sm-4">
    <select class="form-control" name="on_road_gc" id="on_road_gc" onchange="cek_road_gc()">
      <option value="">- choose -</option>
      <option>On The Road</option>
      <option>Off The Road</option>
    </select>
  </div>
</div>
<table class="table table-bordered table-hover myTable1">
  <thead>
    <tr>
      <th>Tipe Kendaraan</th>
      <th>Warna</th>
      <th>Qty</th>
      <th>Harga Satuan</th>
      <th>Biaya BBN</th>
      <th>Nilai Voucher</th>
      <th>Voucher Tambahan</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $total = 0;
    $no = 1;
    $tipe = '';
    $where_kelompok = '';
    $cek = $this->m_admin->getByID("tr_prospek_gc", "id_prospek_gc", $id);
    if ($cek->num_rows() > 0) {
      $rt = $cek->row();
      if ($rt->jenis == "Instansi") {
        $tipe = "Instansi";
        $where_kelompok = " AND ms_kelompok_harga.id_kelompok_harga='$rt->id_kelompok_harga'";
      } else {
        $tipe = "Customer Umum";
      }
    }
    foreach ($detail->result() as $rs) {
      $jum = $detail->num_rows();
      $biaya_bbn = $rs->biaya_bbn;
      $harga_jual = $rs->harga_jual;
      $harga    = $rs->harga;
      $ppn      = $rs->ppn;
      $harga_on = $rs->harga_on;
      $harga_tunai = $rs->harga_tunai;
      $harga_asli = $rs->harga_asli;

    ?>
      <tr>
        <td><?= $rs->id_tipe_kendaraan . " | " . $rs->tipe_ahm ?></td>
        <td><?= $rs->id_warna . " | " . $rs->warna ?></td>
        <td><?= $rs->qty ?></td>
        <td align="right"><?= mata_uang($harga_jual) ?></td>
        <td align="right">
          <input type="hidden" id="harga_jual_gc_<?php echo $no ?>" name="harga_jual_gc_<?php echo $no ?>" value="<?php echo $harga_jual ?>">
          <input type="hidden" id="qty_gc_<?php echo $no ?>" name="qty_gc_<?php echo $no ?>" value="<?php echo $rs->qty ?>">
          <input type="hidden" id="biaya_bbn_gc_on_<?php echo $no ?>" value="<?php echo $biaya_bbn ?>">
          <input type="hidden" id="biaya_bbn_gc_off_<?php echo $no ?>" value="0">
          <input type="hidden" id="jumlah_gc" name="jumlah_gc" value="<?php echo $jum ?>">
          <input type="hidden" id="id_tipe_kendaraan_gc_<?php echo $no ?>" name="id_tipe_kendaraan_gc_<?php echo $no ?>" value="<?php echo $rs->id_tipe_kendaraan ?>">
          <input type="hidden" id="id_warna_gc_<?php echo $no ?>" name="id_warna_gc_<?php echo $no ?>" value="<?php echo $rs->id_warna ?>">
          <input type="text" style="width:100px;text-align:right;" readonly id="biaya_bbn_gc_<?php echo $no ?>" name="biaya_bbn_gc_<?php echo $no ?>" onchange="kali_gc_cash()">
        </td>
        <td><input style="width:100px;text-align:right;" type="text" readonly id="nilai_voucher_gc_<?php echo $no ?>" name="nilai_voucher_gc_<?php echo $no ?>" onchange="kali_gc_cash()"></td>
        <td><input style="width:100px;text-align:right;" id="voucher_tambahan_gc_<?php echo $no ?>" name="voucher_tambahan_gc_<?php echo $no ?>" onkeypress="return number_only(event)" onchange="kali_gc_cash()" type="text"></td>
        <td><input style="width:120px;text-align:right;" type="text" readonly id="total_gc_<?php echo $no ?>" name="total_gc_<?php echo $no ?>"></td>
      </tr>
    <?php
      $no++;
      //$total += $grand;
    }
    ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="7"></td>
      <td>
        <input style="width:120px;text-align:right;" type="text" disabled id="g_total_gc">
      </td>
    </tr>
  </tfoot>
</table>