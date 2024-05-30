<?php if (isset($set_page)){ ?>
  <?php if ($set_page=='rekap'): ?>
    <?php if (count($rekap)>0): ?>
        <table class="table table-bordered" width="70%" id="example2">
        <thead>
          <th>No Rekap</th>
          <th>Total Potongan</th>
          <th>Aksi</th>
        </thead>
        <tbody>
          <?php foreach ($rekap as $key=>$rk): ?>
            <tr>
              <td><?= $rk['no_rekap'] ?></td>
              <td align="right"><?= mata_uang_rp($rk['nominal']) ?></td>
              <td align="center">
                <input type="hidden" name="nominal_<?=$key?>" id="nominal_<?=$key?>" value="<?= $rk['nominal'] ?>">
                <input type="hidden" id="no_rekap_<?=$key?>" name="no_rekap_<?=$key?>" value="<?= $rk['no_rekap'] ?>">
                <input type="checkbox" name="chk_rekap_<?=$key?>" id="chk_rekap_<?=$key?>" onchange="totRekap()">
              </td>
            </tr>
          <?php endforeach ?>
          <input type="hidden" name="count_rekap" value="<?= $key ?>" id="count_rekap">
        </tbody>
      </table>
      <div class="fom-group">
        <label for="inputEmail3" class="col-md-offset-7 col-sm-2 control-label">Total</label>
        <div class="col-md-3">
          <input type="text" id="tot_rekap_show" class="form-control" readonly>
          <input type="hidden" id="tot_rekap" name="tot_rekap" class="form-control" readonly>
        </div>
      </div>
      <script>
        function totRekap(){
          var sum = 0;
          var count = $('#count_rekap').val();
          for (var i = 0; i <= count; i++) {
            if ($("#chk_rekap_"+i).is(":checked")) {
              var nilai = parseInt($('#nominal_'+i).val());
              console.log(nilai);
              sum += isNaN(nilai)?0:nilai;
            }
          }
          $('#tot_rekap_show').val(formatRupiah(sum.toString()));
          $('#tot_rekap').val(sum);
        }
      </script>
    <?php endif ?>
  <?php endif ?>
<?php }else{ ?>
  <table id="s" class="table table-hover table-bordered" width="100%">
  <thead>
    <th width="15%">No Account</th>
    <th width="15%">Jenis Transaksi</th>                    
    <th width="15%">Referensi</th>
    <th width="15%">Sisa Hutang</th>
    <th width="15%">Nominal</th>
    <th width="15%">Keterangan</th>
    <th width="5%" align="center">Aksi</th>
  </thead>
  <tbody>
  <?php   
  $totNominal=0;
  foreach($dt_detail->result() as $row) {           
    echo "   
    <tr>                    
      <td>$row->kode_coa</td>
      <td>$row->coa</td>      
      <td>$row->referensi</td>   
      <td align='right'>".mata_uang_rp($row->sisa_hutang)."</td>      
      <td align='right'>".mata_uang_rp($row->nominal)."</td>      
      <td>$row->keterangan</td>            
      <td align='center'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_detail('<?php echo $row->id_voucher_bank_detail; ?>')"></button>
      </td>
    </tr>
  <?php   
    if($row->kode_coa == '2.01.21062.00' OR $row->kode_coa == '2.01.21063.00' OR $row->kode_coa == '2.01.21064.00' OR $row->kode_coa == '5.01.5107.01' OR $row->kode_coa == '5.01.5107.03' OR $row->kode_coa == '5.01.5107.05' OR $row->kode_coa == '6.01.6013.01' OR $row->kode_coa == '6.02.6010.02' OR $row->kode_coa == '6.03.6010.03' OR $row->kode_coa == '6.03.6011.03'){      
      $totNominal-= $row->nominal;      
    }else{  
      $totNominal+= $row->nominal;
    }
  }
  ?>  
  </tbody>

    <tr>
      <td>
        <!-- <select class="form-control select2 isi_combo" id="kode_coa" onchange="cek_coa()">
          <option value="">- choose -</option>
          <?php 
          $vendor = $this->m_admin->getAll("ms_coa","kode_coa","ASC");
          foreach ($vendor->result() as $isi) {
            echo "<option value='$isi->kode_coa'>$isi->kode_coa</option>";
          }
          ?>
        </select> -->
        <input id="kode_coa" readonly type="text" onclick="showModalCOA()" name="kode_coa" class="form-control isi" placeholder="Kode COA">
      </td>
      <td>
        <input type="text" readonly id="coa" class="form-control isi_combo" onclick="showModalCOA()" placeholder="COA">
      </td>                          
      <td>
        <select class="form-control isi_combo select2" id="referensi" onchange="cek_ref()">
        </select>
      </td>
      <td>
        <input type="text" class="form-control isi_combo" id="sisa_hutang" placeholder="Sisa Hutang" readonly>
      </td>
      <td>
        <input type="text" class="form-control isi_combo tanpa_rupiah" id="nominal" onkeyup="rupiah(this)" placeholder="Nominal">
      </td>
      
      <td>
        <input type="text" class="form-control isi_combo" id="keterangan" placeholder="Keterangan">
      </td>
      <td>
        <button type="button" onClick="simpan_detail()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i></button>                          
      </td>                        
    </tr>
    <tr>
      <td align="right" colspan="4"><b>Total</b></td>
      <td align="right"><span id="totNominal" style="font-weight: bold;"><?= mata_uang_rp($totNominal) ?></span></td>
      <td colspan="2"></td>
    </tr>
  </tbody>                        
</table>
<?php } ?>