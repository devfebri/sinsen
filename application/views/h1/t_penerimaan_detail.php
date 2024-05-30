<?php if (isset($modal)){ ?>
<?php 
function mata_uang2($a){
  if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);      
    if(is_numeric($a) AND $a != 0 AND $a != ""){
      return number_format($a, 0, ',', '.');
    }else{
      return $a;
    }
}
?>
  <?php if ($modal=='referensi'): ?>
    <table class="table table-bordered" id="datatables2">
      <thead>
        <th>Referensi</th>
        <th>Tgl jatuh Tempo</th>
        <th>Nominal</th>
        <th>Aksi</th>
      </thead>
      <tbody>
        <?php foreach ($data as $key=>$dt): ?>
        <tr>
          <td><?= $dt['referensi'] ?></td>
          <td><?= $dt['tgl_jatuh_tempo'] ?></td>
          <td align="right"><?= mata_uang2($dt['nominal']) ?></td>
          <td align="center">
            <button class='btn btn-success btn-xs' data-dismiss='modal' onclick='return pilihRef(<?= json_encode($dt) ?>)'><i class='fa fa-check'></i></button>
          </td>
        </tr>
      <?php endforeach ?>
      </tbody>
    </table>
    <script>
      function pilihRef(dt)
      {
        $('#referensi').val(dt.referensi);
        $('#nominal').val(dt.nominal.toFixed(0));
        $('#sisa_hutang').val(dt.nominal.toFixed(0));
        $('#sisa_hutang_real').val(dt.nominal.toFixed(0));
        form_entri.detail.referensi = dt.referensi;
        form_entri.detail.nominal = parseInt((dt.nominal));
        form_entri.detail.sisa_hutang = parseInt((dt.nominal));
      }
    </script>
  <?php endif ?>
<?php }else{ ?>
<?php 
function mata_uang2($a){
  if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);      
    if(is_numeric($a) AND $a != 0 AND $a != ""){
      return number_format($a, 0, ',', '.');
    }else{
      return $a;
    }
}
?>
<table id="example2" class="table table-hover table-bordered myTable1" width="100%">
  <tr>
    <th width="15%">No Account</th>
    <th width="20%">Jenis Transaksi</th>                    
    <th width="20%">Referensi</th>
    <th width="10%">Nominal</th>
    <th width="10%">Sisa Hutang</th>
    <th width="15%">Keterangan</th>
    <th width="10%">Aksi</th>
  </tr>

  <!-- <?php   
  $count=0;
  foreach($dt_detail->result() as $row) {           
    echo "   
    <tr>                    
      <td width='15%'>$row->kode_coa</td>
      <td width='20%'>$row->coa</td>      
      <td width='20%'>$row->referensi</td>      
      <td align='right' width='10%'>".mata_uang2($row->nominal)."</td>      
      <td align='right' width='10%'>".mata_uang2($row->sisa_hutang)."</td>      
      <td width='15%'>$row->keterangan</td>            
      <td width='10%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_detail('<?php echo $row->id_penerimaan_bank_detail; ?>')"></button>
      </td>
    </tr>
  <?php    
  $count++;
  }
  ?> -->  

  <!-- pakai array -->
  <?php   
  $count=0;
  if($item = $this->item->get_content()) {
    foreach ($item as $row){ 
      echo "   
      <tr>                    
        <td width='15%'>$row[kode_coa]</td>
        <td width='20%'>$row[coa]</td>      
        <td width='20%'>$row[referensi]</td>      
        <td align='right' width='10%'>".mata_uang2($row['nominal'])."</td>      
        <td align='right' width='10%'>".mata_uang2($row['sisa_hutang'])."</td>      
        <td width='15%'>$row[keterangan]</td>            
        <td width='10%'>"; ?>
        <input type="hidden" id="rowid_<?=$row['id']?>" value="<?= $row['rowid'] ?>"> 
          <button data-toggle="tooltip" title="Delete" class="btn btn-danger btn-flat btn-xs" type="button" onclick="hapus_detail('<?= $row['rowid']?>')"><i class="fa fa-trash" ></i></button>                  
        </td>
      </tr>
    <?php    
    $count++;
    }
  }
  ?>


<input type="hidden" id='count_detail' value="<?= $count ?>">
    <tr>
      <td width="15%">        
        <input id="kode_coa" readonly type="text" onclick="showModalCOA()" name="kode_coa" class="form-control isi" placeholder="Kode COA">
      </td>
      <td width="20%">
        <input id="coa" readonly type="text" onclick="showModalCOA()" name="coa" class="form-control isi" placeholder="COA">
      </td>                          
      <td width="20%">
        <input id="referensi" readonly type="text" onclick="showModalRef()" name="referensi" class="form-control isi" placeholder="Referensi">        
      </td>
      <td width="10%">      
        <input  style="text-align: right;" autocomplete="off"  type="text" onchange="cek_hutang()" style="" class="form-control isi_combo" id="nominal" placeholder="Nominal"  >
      </td>
      <td width="10%">
        <input style="text-align: right;" type="text" class="form-control isi_combo" id="sisa_hutang" placeholder="Sisa Hutang" readonly>
        <input type="hidden" id="sisa_hutang_real">
      </td>
      <td width="15%">
        <input type="text" autocomplete="off" class="form-control isi_combo" id="keterangan" placeholder="Keterangan">
      </td>
      <td width="10%">
        <button type="button" onClick="simpan_detail()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>

</table>

<?php } ?>