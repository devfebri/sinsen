<table class="table table-condensed table-hover table-bordered" >
  <!-- <thead>
    <th width="5%">No.</th>
    <th>Tipe</th>
    <th></th>
    <th width=9%" style="text-align: center">M-1 <span style="color: red">[<?=date('m')-1?>]</span></th>
    <th width=9%" style="text-align: center">M <span style="color: red">[<?=date('m')?>]</span></th>
  </thead> -->
  <tbody>
    <?php $tipe=$this->db->query("SELECT ms_stok_ditahan.*,ms_tipe_kendaraan.tipe_ahm FROM ms_stok_ditahan INNER JOIN ms_tipe_kendaraan on ms_stok_ditahan.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan ORDER BY ms_stok_ditahan.id_tipe_kendaraan ASC");
      $dist = $this->db->query("SELECT * FROM ms_stok_ditahan_header")->row();
    ?>
    <?php if ($tipe->num_rows()>0): ?>
      <?php $no=1; foreach ($tipe->result() as $key => $rs): ?>
          <?php
            $thn=date('Y');$bln_min1=sprintf("%02d",date('m')-1);$tgl=date('d');
            $tgl_bln_min1 = "$thn-$bln_min1-$tgl";
            $tgl_bln      = date('Y-m-d');
            $tgl_bln      = date('Y-m');
            $bln_th = "$thn-$bln_min1";
            
            $stok_awal_md = $this->db->query("SELECT count(tipe_motor) as stok FROM tr_scan_barcode WHERE tipe_motor='$rs->id_tipe_kendaraan' AND LEFT(tgl_penerimaan,7) < '$bln_th' AND status=1")->row()->stok;
            
            $displan_ahm_awal = $this->db->query("SELECT sum(qty_plan) as stok from tr_displan WHERE date_format(str_to_date(tanggal, '%d%m%Y'), '%Y%-%m') = '$bln_th' AND id_tipe_kendaraan='$rs->id_tipe_kendaraan'")->row()->stok;
            
             $dis_ahm = $this->db->query("SELECT sum(qty_plan) as stok from tr_displan WHERE date_format(str_to_date(tanggal, '%d%m%Y'), '%Y%-%m') = '$tgl_bln' AND id_tipe_kendaraan='$rs->id_tipe_kendaraan'")->row()->stok;
             if ($dis_ahm==null OR $dis_ahm=='' OR $dis_ahm==0) {
               $displan_ahm=0;
             }else{
              $displan_ahm=$dis_ahm;
             }
             if ($displan_ahm_awal==null OR $displan_ahm_awal=='' OR $displan_ahm_awal==0) {
               $displan_ahm_awal=0;
             }else{
              $displan_ahm_awal=$displan_ahm_awal;
             }

            $penjualan_dealer_m1 = $this->db->query("SELECT count(tr_spk.id_tipe_kendaraan) as stok from tr_sales_order INNER JOIN tr_spk on tr_sales_order.no_spk=tr_spk.no_spk WHERE id_tipe_kendaraan='$rs->id_tipe_kendaraan' AND status_so='so_invoice' AND tr_sales_order.id_dealer='$id_dealer' AND LEFT(tgl_cetak_invoice,7)='$bln_th' ")->row()->stok;
             $penjualan_all_dealer_m1 = $this->db->query("SELECT count(tr_spk.id_tipe_kendaraan) as stok from tr_sales_order INNER JOIN tr_spk on tr_sales_order.no_spk=tr_spk.no_spk WHERE id_tipe_kendaraan='$rs->id_tipe_kendaraan' AND status_so='so_invoice' AND LEFT(tgl_cetak_invoice,7)='$bln_th'")->row()->stok;

            $stok_distribusi=0;
            if ($rs->jenis_moving=='fast') {
              $stok_distribusi = $dist->persen_fast_moving;
              $jenis_moving = $rs->jenis_moving;
            }elseif ($rs->jenis_moving=='slow') {
              $stok_distribusi = $dist->persen_slow_moving;
              $jenis_moving = $rs->jenis_moving;
            }
            //$stok_awal_md             = 100; //contoh
            //$displan_ahm_awal         = 100; //contoh
         //   $penjualan_dealer_m1      =5; //contoh
            $penjualan_dealer_m       =$penjualan_dealer_m1; //contoh

         //   $penjualan_all_dealer_m1  =50; //contoh
            $penjualan_all_dealer_m   = $penjualan_all_dealer_m1; //contoh
            $dist_ke_dealer_m        = $penjualan_dealer_m;
            $dist_ke_dealer_m1        = $penjualan_dealer_m1;

            $stok_md                  = ($stok_awal_md+$displan_ahm_awal)-$penjualan_all_dealer_m1;
            //$displan_ahm              = 150; //contoh

            $distribusi               = @(($stok_md+$displan_ahm)-($stok_awal_md+$displan_ahm_awal))* @($penjualan_dealer_m1/$penjualan_all_dealer_m);
            $stok_ditahan             = ($dist_ke_dealer_m1+$distribusi)*($stok_distribusi/100);
            $suggest_distribusi       = $dist_ke_dealer_m1+$distribusi-$stok_ditahan;

          ?>
          <input type="hidden" name="tipe_<?=$key?>" value="<?=$rs->id_tipe_kendaraan?>">
          <input type="hidden" name="stok_distribusi_<?=$key?>" value="<?=$stok_distribusi?>">
          <input type="hidden" name="jenis_moving_<?=$key?>" value="<?=$jenis_moving?>">
          <input type="hidden" name="id_dealer[]" value="<?=$id_dealer?>">
          <tr>
            <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?=$rs->id_tipe_kendaraan?> | <?=$rs->tipe_ahm?></b></td>
            <td colspan="2" style="text-align: right;margin-right: 20px;"><b>Stok Distribusi</b></td>
            <td><b><?=$stok_distribusi ?> %</b></td>
          </tr>
          <tr>
            <td width="5%">No.</td>
            <td>Tipe</td>
            <td></td>
            <td width=9%" style="text-align: center">M-1 <span style="color: red">[<?=date('m')-1?>]</span></td>
            <td width=9%" style="text-align: center">M <span style="color: red">[<?=date('m')?>]</span></td>
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
    <?php endif ?>
  </tbody>
</table>