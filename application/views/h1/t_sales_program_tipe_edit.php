<?php if ($get_tipe->num_rows() > 0){
  $row=$get_tipe->row();
?>

<?php 
if ($row->jenis_barang=='' OR $row->jenis_barang==null) {
    $hide = ' hide';
}else{
  $hide='';
}

if ($row->qty_minimum=='' OR $row->qty_minimum==null) {
    $hide2 = ' hide';
}else{
  $hide2='';
} ?>

<?php if ($row->jenis_bayar_dibelakang=='' or $row->jenis_bayar_dibelakang==null) {
    $hide_jenis_bayar = ' hide';
}else{
  $hide_jenis_bayar='';
} ?>

<body onload="getTypeWarnaEdit();cekMetodePembayaran();">
<table class="table table bordered table-condensed">
  <thead>
    <tr>              
      <th style="width: 15%">Kode Type</th>                    
      <th>Nama Type</th>
      <th style="width: 8%">Warna</th>
      <th style="width: 8%">Tahun Produksi</th>
      <th>Kontribusi</th>
      <th>Cash</th>
      <th>Kredit</th>
      <th>Metode Pembayaran</th>
      <th class="jenis_barang <?php echo $hide_jenis_bayar ?>">Jenis Barang</th>
      <th class="qty_minimum <?php echo $hide ?>">Qty</th>
    </tr>
  </thead>
  <tr>
     <td rowspan="7">
       <select class="select2"  style="width: 100%" id="kode_type" onchange="getTypeWarnaEdit()">
         <?php $kendaraan = $this->db->query("SELECT * FROM ms_tipe_kendaraan order by id_tipe_kendaraan ASC");
          if ($kendaraan->num_rows() > 0) {  ?>
            <?php echo "<option value=''>--Pilih--</option>" ?>
            <?php foreach ($kendaraan->result() as $rs):
                if ($rs->id_tipe_kendaraan==$row->id_tipe_kendaraan) {
                  $selected='selected';
                }else{
                  $selected='';
                }
             ?>
                <option value="<?php echo  $rs->id_tipe_kendaraan ?>" data-nama_type="<?php echo  $rs->tipe_ahm ?>" <?php echo  $selected ?>><?php echo  $rs->id_tipe_kendaraan ?> | <?php echo  $rs->tipe_ahm ?></option>
            <?php endforeach ?>
         <?php }
          ?>
       </select>
     </td>
     <td rowspan="7">
      <input type="text" id="nama_type" class="form-control" readonly value="<?php echo  $row->tipe_ahm?>">
      <input type="hidden" id="id_sales_program_tipe" value="<?php echo  $row->id_sales_program_tipe ?>">
     </td>
     <td rowspan="7" style="width: 10%"><select class="select2 id_warna" style="width: 100%" multiple="multiple" name="id_warna" id="id_warna">
              <?php foreach ($dt_warna as $wrn):
                  $warna =explode(',',$row->id_warna);
                  if (count($warna) > 1) {
                    if (in_array($wrn->id_warna, $warna)){
                      $selected = "selected";
                    }else{
                      $selected='';
                    }
                  }
                   ?>
                  <option value="<?php echo  $wrn->id_warna?>" <?php echo  $selected ?> ><?php echo  $wrn->warna?></option>
              <?php endforeach ?>
       </select></td>
      <td rowspan="7"><input type="text" name="tahun_produksi" id="tahun_produksi" value="<?php echo $row->tahun_produksi ?>" class="form-control" autocomplete="off" placeholder="Semua Tahun">
          <i style="font-size: 12px">Contoh pengisian : 2017, 2018</i>
      </td>
      <td><b>AHM</b></td><td><input type="text" name="ahm_cash"  id="ahm_cash" class="form-control auto_sum_cash" value="<?php echo  $row->ahm_cash?>"></td><td><input type="text" name="ahm_kredit" id="ahm_kredit"  class="form-control auto_sum_kredit" value="<?php echo  $row->ahm_kredit?>"></td>
      <td rowspan="7"><select class="form-control" id="metode_pembayaran_edit" name="metode_pembayaran" onchange="cekMetodePembayaran()">       
        <?php if($row->metode_pembayaran == 'Bayar Dibelakang'){ ?>          
          <option value="Bayar Dibelakang">Bayar Dibelakang</option>
          <option value="Bayar Didepan(Potong DO)">Bayar Didepan(Potong DO)</option>
        <?php }else{ ?>
          <option value="Bayar Didepan(Potong DO)">Bayar Didepan(Potong DO)</option>
          <option value="Bayar Dibelakang">Bayar Dibelakang</option>
        <?php } ?>
      </select><br>

      <?php if ($row->jenis_bayar_dibelakang=='Cash') {
            $sel_1='selected';
            $sel_2='';
        }else{
          $sel_1='';
          $sel_2='selected';
        } ?>
      <span id="jenis_bayar">
        <select class="form-control <?php echo $hide_jenis_bayar ?>" id="jenis_bayar_dibelakang_edit" name="jenis_bayar_dibelakang">
          <?php if($row->jenis_bayar_dibelakang=='Cash'){
              $sel_1='selected';
              $sel_2='';
              $sel_3='';
          }elseif($row->jenis_bayar_dibelakang=='Quotation'){
              $sel_1='';
              $sel_2='selected';
              $sel_3='';
          }else{ 
              $sel_1='';
              $sel_2='';
              $sel_3='selected';
          }?>
          <option value="" <?php echo  $sel_3 ?>>Pilih</option>
          <option value="Cash" <?php echo  $sel_1 ?> >Cash</option>
          <option value="Quotation" <?php echo  $sel_2 ?>>Quotation</option>
        </select>
      </span>
    </td>
      <td rowspan="7" class="jenis_barang <?php echo  $hide_jenis_bayar ?>"><input type="text" name="jenis_barang" id="jenis_barang" class="form-control"></td>
      <td rowspan="7" class="qty_minimum <?php echo  $hide ?>"><input type="text" name="qty_minimum" id="qty_minimum" class="form-control"></td>
   </tr>
   <tr>
     <td><b>MD</b></td><td><input type="text" name="md_cash" id="md_cash" class="form-control auto_sum_cash" value="<?php echo  $row->md_cash?>"></td><td><input type="text" name="md_kredit" id="md_kredit" class="form-control auto_sum_kredit" value="<?php echo  $row->md_kredit?>"></td>
   </tr>
   <tr>
     <td><b>Dealer</b></td><td><input type="text" name="dealer_cash"  id="dealer_cash" class="form-control auto_sum_cash" value="<?php echo  $row->dealer_cash?>"></td><td><input type="text" name="dealer_kredit"  id="dealer_kredit" class="form-control auto_sum_kredit" value="<?php echo  $row->dealer_kredit?>"></td>
   </tr>
   <tr>
     <td><b>Finco</b></td><td><input type="text" name="other_cash" id="other_cash" class="form-control auto_sum_cash" value="<?php echo  $row->other_cash?>"></td><td><input type="text" name="other_kredit" id="other_kredit" class="form-control auto_sum_kredit" value="<?php echo  $row->other_kredit?>"></td>
   </tr> 
   <tr>
     <td><b>Add. MD</b></td><td><input type="text" name="add_md_cash" id="add_md_cash" class="form-control auto_sum_cash" value="<?php echo $row->add_md_cash?>"></td><td><input type="text" name="add_md_kredit" id="add_md_kredit" class="form-control auto_sum_kredit" value="<?php echo  $row->add_md_kredit?>"></td>
   </tr>
   <tr>
     <td><b>Add. Dealer</b></td><td><input type="text" name="add_dealer_cash"  id="add_dealer_cash" class="form-control auto_sum_cash" value="<?php echo $row->add_dealer_cash?>"></td><td><input type="text" name="add_dealer_kredit"  id="add_dealer_kredit" class="form-control auto_sum_kredit" value="<?php echo  $row->add_dealer_kredit?>"></td>
   </tr>
   <tr>
     <td><b>Total</b></td><td><input type="text" value="<?php echo ($row->ahm_cash+$row->md_cash+$row->dealer_cash+$row->other_cash+$row->add_md_cash+$row->add_dealer_cash); ?>" readonly id="m_total_cash" class="form-control"></td><td><input type="text" readonly value="<?php echo $row->ahm_kredit+$row->md_kredit+$row->dealer_kredit+$row->other_kredit+$row->add_md_kredit+$row->add_dealer_kredit?>" id="m_total_kredit" class="form-control"></td>
   </tr> 
</table>
 
<?php } ?>

<script type="text/javascript">
  const $inputs_cash = $('.auto_sum_cash');
  $inputs_cash.change(function() {
    var total = 0;
    $inputs_cash.each(function() {
      if ($(this).val() != '') {
        total += parseInt($(this).val());
      }
    });
    $('#m_total_cash').val(total);
  });
  
  const $inputs_kredit = $('.auto_sum_kredit');
  $inputs_kredit.change(function() {
    var total = 0;
    $inputs_kredit.each(function() {
      if ($(this).val() != '') {
        total += parseInt($(this).val());
      }
    });
    $('#m_total_kredit').val(total);
  });
  
  function getTypeWarnaEdit()
  { 
      var nama_type = $(".modal_edit_detailkendaraan #kode_type").select2().find(":selected").data("nama_type");
      var kode_type = $(".modal_edit_detailkendaraan #kode_type").val();
      $('.modal_edit_detailkendaraan #nama_type').val(nama_type);

      $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('h1/sales_program/getWarna');?>",
               type:"POST",
               data:"kode_type="+kode_type,
               /*   +"&ksu="+ksu
                  +"&keterangan="+keterangan
                  +"&tgl_pinjaman="+tgl_pinjaman,
               */
               cache:false,
               success:function(html){
                  $('#loading-status').hide();
                  $('.modal_edit_detailkendaraan .id_warna').html(html);
               },
               statusCode: {
            500: function() {
              $('#loading-status').hide();
              swal("Something Wen't Wrong");
            }
          }
          });
  }  
  function cekMetodePembayaran()
  {
      var metode_pembayaran = $('#metode_pembayaran_edit').val();
      //alert(metode_pembayaran);
      if(metode_pembayaran=='Bayar Dibelakang') {
        $('#jenis_bayar_dibelakang_edit').show();
      }else{
        $('#jenis_bayar_dibelakang_edit').hide();
      }
  }

</script>