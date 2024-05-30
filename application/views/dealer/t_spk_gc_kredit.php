<button class="btn btn-block btn-danger btn-flat" disabled> Kredit </button> <br>
<div class="form-group">
  <label for="inputEmail3" class="col-sm-2 control-label">Nama Penjamin</label>
  <div class="col-sm-4">
    <input type="text" class="form-control" placeholder="Nama Penjamin" name="nama_penjamin" id="nama_penjamin">
  </div>
  <label for="inputEmail3" class="col-sm-2 control-label">Tempat, Tgl Lahir</label>
  <div class="col-sm-2">
    <input type="text" class="form-control" placeholder="Tempat Lahir" name="tempat_lahir" id="tempat_lahir">
  </div>
  <div class="col-sm-2">
    <input type="text" class="form-control" autocomplete="off" placeholder="Tgl Lahir" name="tgl_lahir" id="tanggal2">
  </div>
</div>
<div class="form-group">
  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
  <div class="col-sm-4">
    <input type="text" class="form-control" placeholder="Alamat" name="alamat_penjamin" id="alamat_penjamin">
  </div>
  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
  <div class="col-sm-4">
    <select class="form-control" id="id_pekerjaan" name="id_pekerjaan">
      <option value="">- choose -</option>
      <?php
      foreach ($dt_pekerjaan->result() as $val) {
        echo "
        <option value='$val->id_pekerjaan'>$val->pekerjaan</option>;
        ";
      }
      ?>
    </select>
  </div>
</div>
<div class="form-group">
  <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
  <div class="col-sm-4">
    <input type="text" class="form-control" placeholder="No HP" name="no_hp_penjamin" id="no_hp_penjamin">
  </div>
  <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
  <div class="col-sm-4">
    <input type="text" class="form-control" placeholder="No KTP" name="no_ktp" id="no_ktp">
  </div>
</div>
<div class="form-group">
  <label for="inputEmail3" class="col-sm-2 control-label">Finance Company</label>
  <div class="col-sm-4">
    <select class="form-control" id="id_finance_company" name="id_finance_company">
      <option value="">- choose -</option>
      <?php
      foreach ($dt_finance->result() as $val) {
        echo "
        <option value='$val->id_finance_company'>$val->finance_company</option>;
        ";
      }
      ?>
    </select>
  </div>
</div>
<table class="table table-bordered table-hover myTable1">
  <thead>
    <tr>
      <th>Tipe-Warna</th>
      <th>Qty</th>
      <th>Harga Satuan</th>
      <th>Biaya BBN</th>
      <th>Nilai Voucher</th>
      <th>Voucher Tambahan</th>
      <th>DP Stor</th>
      <th>Angsuran</th>
      <th>Tenor</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $total = 0;
    $no = 1;
    foreach ($detail->result() as $rs) {
      $biaya_bbn   = $rs->biaya_bbn;
      $harga_jual  = $rs->harga_jual;
      $harga       = $rs->harga;
      $ppn         = $rs->ppn;
      $harga_on    = $rs->harga_on;
      $harga_tunai = $harga_on;

      $harga_asli  = $rs->harga_asli;

    ?>
      <tr>
        <td><?= $rs->tipe_ahm . " - " . $rs->warna ?></td>
        <td><?= $rs->qty ?></td>
        <td align="right"><?= mata_uang_rp($harga_jual) ?></td>
        <td><input style="width:80px;text-align:right;" type="text" readonly id="biaya_bbn_<?php echo $no ?>" name="biaya_bbn_<?php echo $no ?>" value="<?php echo $biaya_bbn ?>"></td>
        <td><input style="width:80px;text-align:right;" type="text" readonly id="nilai_voucher_<?php echo $no ?>" name="nilai_voucher_<?php echo $no ?>"></td>
        <td><input style="width:80px;text-align:right;" onchange="kali_gc_kredit()" name="voucher_tambahan_<?php echo $no ?>" id="voucher_tambahan_<?php echo $no ?>" onkeypress="return number_only(event)" type="text"></td>
        <td><input style="width:80px;text-align:right;" onchange="kali_gc_kredit()" onkeypress="return number_only(event)" type="text" name="dp_stor_<?php echo $no ?>" id="dp_stor_<?php echo $no ?>"></td>
        <td><input style="width:80px;text-align:right;" onchange="kali_gc_kredit()" onkeypress="return number_only(event)" type="text" name="angsuran_<?php echo $no ?>" id="angsuran_<?php echo $no ?>"></td>
        <td><input style="width:80px;text-align:right;" onchange="kali_gc_kredit()" onkeypress="return number_only(event)" type="text" name="tenor_<?php echo $no ?>" id="tenor_<?php echo $no ?>"></td>
        <td>
          <input style="width:120px;text-align:right;" type="text" readonly id="total_<?php echo $no ?>" name="total_<?php echo $no ?>">
          <input type="hidden" name="jumlah_kredit" id="jumlah_kredit" value="<?php echo $detail->num_rows() ?>">
          <input type="hidden" name="harga_jual_<?php echo $no ?>" id="harga_jual_<?php echo $no ?>" value="<?php echo $harga_jual ?>">
          <input type="hidden" name="qty_<?php echo $no ?>" id="qty_<?php echo $no ?>" value="<?php echo $rs->qty ?>">
          <input type="hidden" name="id_tipe_kendaraan_<?php echo $no ?>" id="id_tipe_kendaraan_<?php echo $no ?>" value="<?php echo $rs->id_tipe_kendaraan ?>">
          <input type="hidden" name="id_warna_<?php echo $no ?>" value="<?php echo $rs->id_warna ?>">
        </td>
      </tr>
    <?php
      $no++;
    }
    ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="9"></td>
      <td align='right'><input style="width:120px;text-align:right;" type="text" disabled id="g_total"></td>
    </tr>
  </tfoot>
</table>

<script type="text/javascript">
  $('#tanggal2').datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
  });
</script>