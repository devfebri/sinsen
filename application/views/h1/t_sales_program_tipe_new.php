<?php function mata_uang($a){
    	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
      return number_format($a, 0, ',', '.');
   } ?>
   <style type="text/css">  .hide{
  display: none;
}</style>
<?php if ($jenis_sales_program=='SCP') {
    $hide = ' hide';
}else{
  $hide='';
} 
$hide2='';
if ($jenis_sales_program!='Direct Gift') {
  $hide='hide';
}
// if($jenis_sales_program=='Group Customer'){
//   $hide2 = '';
// }else{
//   $hide2 = 'hide';
// }

?>
<table id="example4" class="table table-bordered table-hover">
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
      <th class="jenis_barang <?php echo $hide ?>">Jenis Barang</th>
      <th class="qty_minimum <?php echo $hide ?>">Qty</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    $login_id = $this->session->userdata('id_user');    
    $sales_program = $this->db->query("SELECT *,ms_tipe_kendaraan.tipe_ahm FROM tr_sales_program_tipe 
        left join ms_tipe_kendaraan on tr_sales_program_tipe.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
        WHERE tr_sales_program_tipe.status='new' AND tr_sales_program_tipe.created_by='$login_id' ");
        //WHERE tr_sales_program_tipe.id_progrma_md = '$id_progrma_md'");
    if ($sales_program->num_rows() > 0) {
      foreach ($sales_program->result() as $rs) { 
        if (!!$rs->jenis_bayar_dibelakang) {
          $jenis_bayar_dibelakang = ", $rs->jenis_bayar_dibelakang";
        }else{
          $jenis_bayar_dibelakang='';
        }

        ?>
        <tr>
          <td rowspan="7"><?php echo $rs->id_tipe_kendaraan ?></td>
          <td rowspan="7"><?php echo $rs->tipe_ahm ?></td>
          <td rowspan="7"><?php echo $rs->id_warna ?></td>
          <td rowspan="7"><?php echo $rs->tahun_produksi ?></td>
          <td><b>AHM</b></td>
          <td align='right'><?php echo mata_uang($rs->ahm_cash) ?></td>
          <td align='right'><?php echo mata_uang($rs->ahm_kredit )?></td>
          <td rowspan="7"><?php echo $rs->metode_pembayaran ?><?php echo $jenis_bayar_dibelakang ?></td>
         <?php if ($hide==''): ?>
            <td rowspan="7" class="jenis_barang <?php echo $hide ?>"><?php echo $rs->jenis_barang ?></td>
          <td rowspan="7" class="qty_minimum <?php echo $hide2 ?>"><?php echo $rs->qty_minimum ?></td>
         <?php endif ?>
          <td rowspan="4">
            <!--<button type="button" data-toggle="modal" data-target=".modal_edit_detailkendaraan" class="btn btn-warning btn-sm btn-flat" onclick="editDetailKendaraan('<?php echo $rs->id_sales_program_tipe ?>')"><i class="fa fa-pencil"></i></button><br><br> -->
            <button type="button" class="btn btn-danger btn-sm btn-flat" onclick="delDetailKendaraan('<?php echo $rs->id_sales_program_tipe ?>')"><i class="fa fa-trash"></i></button></td>
        </tr>
        <tr>
          <td><b>MD</b></td>
          <td align='right'><?php echo mata_uang($rs->md_cash) ?></td>
          <td align='right'><?php echo mata_uang($rs->md_kredit) ?></td>
        </tr>
        <tr>
          <td><b>Dealer</b></td>
          <td align='right'><?php echo mata_uang($rs->dealer_cash) ?></td>
          <td align='right'><?php echo mata_uang($rs->dealer_kredit )?></td>
        </tr>
        <tr>
          <td><b>Finco</b></td>
          <td align='right'><?php echo mata_uang($rs->other_cash) ?></td>
          <td align='right'><?php echo mata_uang($rs->other_kredit) ?></td>
        </tr>
        <tr>
          <td><b>Add. MD</b></td>
          <td align='right'><?php echo mata_uang($rs->add_md_cash) ?></td>
          <td align='right'><?php echo mata_uang($rs->add_md_kredit) ?></td>
        </tr>
        <tr>
          <td><b>Add. Dealer</b></td>
          <td align='right'><?php echo mata_uang($rs->add_dealer_cash) ?></td>
          <td align='right'><?php echo mata_uang($rs->add_dealer_kredit) ?></td>
        </tr>
        <tr>
          <td><b>Total</b></td>
          <td align='right'><?php echo mata_uang($rs->ahm_cash+$rs->md_cash+$rs->dealer_cash+$rs->other_cash+$rs->add_md_cash+$rs->add_dealer_cash) ?></td>
          <td align='right'><?php echo mata_uang($rs->ahm_kredit+$rs->md_kredit+$rs->dealer_kredit+$rs->other_kredit+$rs->add_md_kredit+$rs->add_dealer_kredit) ?></td>
        </tr>
    <?php  }
    }
     ?>
  </tbody>
  <tfoot>
    <tr>
     <td rowspan="7">
       <select class="select2 " style="width: 100%" id="kode_type" onchange="getTypeWarna()">
         <?php $kendaraan = $this->db->query("SELECT * FROM ms_tipe_kendaraan order by id_tipe_kendaraan ASC");
          if ($kendaraan->num_rows() > 0) {  ?>
            <?php echo "<option value=''>--Pilih--</option>" ?>
            <?php foreach ($kendaraan->result() as $rs): ?>
                <option value="<?php echo $rs->id_tipe_kendaraan ?>" data-nama_type="<?php echo $rs->tipe_ahm ?>"><?php echo $rs->id_tipe_kendaraan ?> | <?php echo $rs->tipe_ahm ?></option>
            <?php endforeach ?>
         <?php }
          ?>
       </select>
     </td>
     <td rowspan="7"><input type="text" id="nama_type" class="form-control" readonly></td>
     <td rowspan="7"><select class="select2 id_warna" style="width: 100%" multiple="multiple" name="id_warna" id="id_warna">
       </select></td>
      <td rowspan="7"><input type="text" name="tahun_produksi" id="tahun_produksi" class="form-control" autocomplete="off" placeholder="Semua Tahun">
          <i style="font-size: 12px">Contoh pengisian : 2017, 2018</i>
      </td>
      <td><b>AHM</b></td><td><input type="text" name="ahm_cash"  id="ahm_cash" class="form-control auto_sum_cash cek_kont_ahm"></td><td><input type="text" name="ahm_kredit" id="ahm_kredit"  class="form-control auto_sum_kredit cek_kont_ahm"></td>
      <td rowspan="7"><select class="form-control" id="metode_pembayaran" name="metode_pembayaran" onchange="cekMetodePembayaran()">
        <option value="Bayar Didepan(Potong DO)">Bayar Didepan(Potong DO)</option>
        <option value="Bayar Dibelakang">Bayar Dibelakang</option>
      </select><br>
      <select class="hide form-control " id="jenis_bayar_dibelakang" name="jenis_bayar_dibelakang">
        <option value="">Pilih</option>
        <option value="Cash">Cash</option>
        <option value="Quotation">Quotation</option>
      </select>
    </td>
     <?php if ($hide==''): ?>
        <td rowspan="7" class="jenis_barang <?php echo $hide ?>"><input type="text" name="jenis_barang" id="jenis_barang" class="form-control"></td>
      <td rowspan="7" class="qty_minimum <?php echo $hide2 ?>"><input type="text" name="qty_minimum" id="qty_minimum" class="form-control"></td>
     <?php endif ?>
      <td rowspan="7">
          <button type="button" class="btn btn-primary btn-sm btn-flat" onclick="addDetailKendaraan()"><i class="fa fa-plus"></i></button>
      </td>
   </tr>
   <tr>
     <td><b>MD</b></td><td><input type="text" name="md_cash" id="md_cash" class="form-control auto_sum_cash"></td><td><input type="text" name="md_kredit" id="md_kredit" class="form-control auto_sum_kredit"></td>
   </tr>
   <tr>
     <td><b>Dealer</b></td><td><input type="text" name="dealer_cash"  id="dealer_cash" class="form-control auto_sum_cash"></td><td><input type="text" name="dealer_kredit" id="dealer_kredit" class="form-control auto_sum_kredit"></td>
   </tr>
   <tr>
     <td><b>Finco</b></td><td><input type="text" name="other_cash" id="other_cash" class="form-control auto_sum_cash"></td><td><input type="text" name="other_kredit" id="other_kredit" class="form-control auto_sum_kredit"></td>
   </tr> 
   <tr>
     <td><b>Add. MD</b></td><td><input type="text" name="add_md_cash" id="add_md_cash" class="form-control auto_sum_cash set_modify_cont"></td><td><input type="text" name="add_md_kredit" id="add_md_kredit" class="form-control auto_sum_kredit set_modify_cont"></td>
   </tr>
   <tr>
     <td><b>Add. Dealer</b></td><td><input type="text" name="add_dealer_cash" id="add_dealer_cash" class="form-control auto_sum_cash set_modify_cont_d"></td><td><input type="text" name="add_dealer_kredit" id="add_dealer_kredit" class="form-control auto_sum_kredit set_modify_cont_d"></td>
   </tr>
   <tr>
     <td><b>Total</b></td><td><input type="text" readonly id="total_cash" class="form-control"></td><td><input type="text" readonly  id="total_kredit" class="form-control"></td>
   </tr> 
  </tfoot>

</table>
<script type="text/javascript">
  const $inputs_cash = $('.auto_sum_cash');
  $inputs_cash.change(function() {
    var total = 0;
    $inputs_cash.each(function() {
      if ($(this).val() != '') {
        total += parseInt($(this).val());
      }
    });
    $('#total_cash').val(total);
  });
  
  const $inputs_kredit = $('.auto_sum_kredit');
  $inputs_kredit.change(function() {
    var total = 0;
    $inputs_kredit.each(function() {
      if ($(this).val() != '') {
        total += parseInt($(this).val());
      }
    });
    $('#total_kredit').val(total);
  });

  var total_kont_ahm = 0;
  var total_kont_md = 0;

  const $inputs_ahm = $('.cek_kont_ahm');
  const $inputs_add = $('.set_modify_cont');
  const $inputs_add_d = $('.set_modify_cont_d');
  
  $inputs_ahm.change(function() {
    var total_kont_ahm = 0;
    $inputs_ahm.each(function() {
      if ($(this).val() != '') {
        total_kont_ahm += parseInt($(this).val());
      }
    });

    var total_kont_md = 0;
    $inputs_add.each(function() {
      if ($(this).val() != '') {
        total_kont_md += parseInt($(this).val());
      }
    });

    var total_kont_d = 0;
    $inputs_add_d.each(function() {
      if ($(this).val() != '') {
        total_kont_d += parseInt($(this).val());
      }
    });

    if(total_kont_ahm>0){
      $('#jenis').val('ahm');
      if(total_kont_md >0 || total_kont_d > 0) {
        $('#jenis').val('ahm_md');
      }
    }else if(total_kont_md>0){
      $('#jenis').val('md');
    }else{
      $('#jenis').val('dealer');
    }
  
  });

  $inputs_add.change(function() {
    $('#jenis').val('dealer');

    var total_kont_ahm = 0;
    $inputs_ahm.each(function() {
      if ($(this).val() != '') {
        total_kont_ahm += parseInt($(this).val());
      }
    });

    var total_kont_md = 0;
    $inputs_add.each(function() {
      if ($(this).val() != '') {
        total_kont_md += parseInt($(this).val());
      }
    });
    
    var total_kont_d = 0;
    $inputs_add_d.each(function() {
      if ($(this).val() != '') {
        total_kont_d += parseInt($(this).val());
      }
    });
    
    if(total_kont_ahm>0){
      $('#jenis').val('ahm');
      if(total_kont_md >0 || total_kont_d >0) {
        $('#jenis').val('ahm_md');
      }
    }else if(total_kont_md>0){
      $('#jenis').val('md');
    }else{
      $('#jenis').val('dealer');
    }
    
  });

  
  $inputs_add_d.change(function() {
    var total_kont_ahm = 0;
    $inputs_ahm.each(function() {
      if ($(this).val() != '') {
        total_kont_ahm += parseInt($(this).val());
      }
    });

    var total_kont_md = 0;
    $inputs_add.each(function() {
      if ($(this).val() != '') {
        total_kont_md += parseInt($(this).val());
      }
    });

    var total_kont_d = 0;
    $inputs_add_d.each(function() {
      if ($(this).val() != '') {
        total_kont_d += parseInt($(this).val());
      }
    });

    if(total_kont_ahm>0){
      $('#jenis').val('ahm');
      if(total_kont_md >0 || total_kont_d) {
        $('#jenis').val('ahm_md');
      }
    }else if(total_kont_md>0){
      $('#jenis').val('md');
    }else{
      $('#jenis').val('dealer');
    }

  });
  
  function cekJenisBarang()
  {
    var jenis_sales_program =  $("#id_jenis_sales_program").select2('text');
    var jenis_sales_program =  'SCP';
    if (jenis_sales_program=='SCP') {
      $('.jenis_barang').addClass('hide');
    }else if (jenis_sales_program=='Group Customer') {
      $('.qty_minimum').addClass('hide');
    }
  }
  function getTypeWarna()
  { 
      var nama_type = $("#kode_type").select2().find(":selected").data("nama_type");
      var kode_type = $("#kode_type").val();
      $('#nama_type').val(nama_type);

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
                  $('.id_warna').html(html);
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
     var metode_pembayaran = $('#metode_pembayaran').val();
     if (metode_pembayaran=='Bayar Dibelakang') {
        $('#jenis_bayar_dibelakang').removeClass('hide');
      }else{
        $('#jenis_bayar_dibelakang').addClass('hide');
      }
  }
</script>