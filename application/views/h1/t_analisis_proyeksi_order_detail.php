<table class="table table-condensed table-hover table-bordered" >
  <!-- <thead>
    <th width="5%">No.</th>
    <th>Tipe</th>
    <th></th>
    <th width=9%" style="text-align: center">M-1 <span style="color: red">[<?=date('m')-1?>]</span></th>
    <th width=9%" style="text-align: center">M <span style="color: red">[<?=date('m')?>]</span></th>
  </thead> -->
  <tbody>
    <?php foreach ($dt_detail as $key => $rs): ?>
    
          <input type="hidden" name="tipe_<?=$key?>" value="<?=$rs->id_tipe_kendaraan?>">
          <input type="hidden" name="stok_distribusi_<?=$key?>" value="<?=$stok_distribusi?>">
          <input type="hidden" name="jenis_moving_<?=$key?>" value="<?=$jenis_moving?>">
          <input type="hidden" name="id_dealer[]" value="<?=$id_dealer?>">
          <tr>
            <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?=$rs->id_tipe_kendaraan?> | <?=$rs->tipe_ahm?></b></td>
            <td colspan="2" style="text-align: right;margin-right: 20px;"><b>Stok Distribusi</b></td>
            <td><b><?=$rs->stok_distribusi ?> %</b></td>
          </tr>
          <tr>
            <td width="5%">No.</td>
            <td>Tipe</td>
            <td></td>
            <td width=9%" style="text-align: center">M-1 <span style="color: red">[<?=date('m')-1?>]</span></td>
            <td width=9%" style="text-align: center">M <span style="color: red">[<?=?>]</span></td>
          </tr>
          <tr>
            <td rowspan="11" style="vertical-align: middle;text-align: center;"><?=$no?></td>
            <td rowspan="11" style="vertical-align: middle;"><?=$rs->id_tipe_kendaraan?> | <?=$rs->tipe_ahm?></td>
            <td style="text-align: right;">Stok Awal MD</td>
            <td style="text-align: center;"><input type="text" name="stok_awal_md_<?=$key?>" readonly class="form-control isi" value="<?=$stok_awal_md?>"></td>
            <td style="text-align: center;"><input type="text" name="stok_md_<?=$key?>" readonly class="form-control isi" value="<?=$stok_md?>"></td>
          </tr>
          <tr>
            <td  style="text-align: right;">Displan AHM</td>
             <td style="text-align: center;"><input type="text" name="displan_ahm_awal_<?=$key?>" readonly class="form-control isi" value="<?=$displan_ahm_awal?>"></td>
            <td style="text-align: center;"><input type="text" name="displan_ahm_<?=$key?>" readonly class="form-control isi" value="<?=$displan_ahm?>"> </td>
          </tr>
          <tr>
            <td style="text-align: right;">Penjualan Dealer <?=$dealer?></td>
             <td style="text-align: center;"><input type="text" name="penjualan_dealer_m1_<?=$key?>" readonly class="form-control isi" value="<?=$penjualan_dealer_m1?>"></td>
            <td style="text-align: center;"><input type="text" name="penjualan_dealer_m_<?=$key?>" readonly class="form-control isi" value="<?=$penjualan_dealer_m?>"></td>
          </tr>
          <tr>
            <td style="text-align: right;">Penjualan All Dealer</td>
             <td style="text-align: center;"><input type="text" name="penjualan_all_dealer_m1_<?=$key?>" readonly class="form-control isi" value="<?=$penjualan_all_dealer_m1?>"></td>
            <td style="text-align: center;"><input type="text" name="penjualan_all_dealer_m_<?=$key?>" readonly class="form-control isi" value="<?=$penjualan_all_dealer_m?>"></td>
          </tr>
          <tr>
            <td colspan="3"></td>
          </tr>
           <tr>
            <td  style="text-align: right;">Distribusi Ke Dealer</td>
             <td style="text-align: center;"><input type="text" name="dist_ke_dealer_m1_<?=$key?>" readonly class="form-control isi" value="<?=$dist_ke_dealer_m1?>"></td>
            <td style="text-align: center;"><input type="text" name="dist_ke_dealer_m_<?=$key?>" readonly class="form-control isi" value="<?=$dist_ke_dealer_m?>"></td>
          </tr>
           <tr>
            <td  style="text-align: right;">+/- Distribusi</td>
             <td style="text-align: center;"><input type="text" name="" readonly class="form-control isi"  value="-" readonly></td>
            <td style="text-align: center;"><input type="text" name="distribusi_<?=$key?>" readonly class="form-control isi" value="<?=floor($distribusi)?>"></td>
          </tr>
          <tr>
            <td  style="text-align: right;">Stok Ditahan</td>
             <td style="text-align: center;"><input type="text" name="" readonly class="form-control isi"  value="-" readonly></td>
            <td style="text-align: center;"><input type="text" name="stok_ditahan_<?=$key?>" readonly class="form-control isi" value="<?=floor($stok_ditahan)?>"></td>
          </tr>
          <tr>
            <td colspan="3"></td>
          </tr>
          <tr>
            <td  style="text-align: right;">Suggest Distribusi</td>
             <td style="text-align: center;"><input type="text" name="" readonly class="form-control isi"  value="-" readonly></td>
            <td style="text-align: center;"><input type="text" name="suggest_distribusi_<?=$key?>" readonly class="form-control isi" value="<?=floor($suggest_distribusi)?>"></td>
          </tr>
          <tr>
            <td  style="text-align: right;">Qty Order</td>
             <td style="text-align: center;"><input type="text" name="" class="form-control isi" value="-" readonly></td>
            <td style="text-align: center;"><input type="text" name="qty_order_<?=$key?>" class="form-control isi" value="<?=floor($suggest_distribusi)?>"></td>
          </tr>
          <tr>
            <td colspan="5" style="background: #e0dddd;min-height: 1px"></td>
          </tr>
      <?php $no++; endforeach ?>
  </tbody>
</table>