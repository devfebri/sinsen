<?php function mata_uang($a){
    	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
      return number_format($a, 0, ',', '.');
   } ?>
   <style type="text/css">  .hide{
  display: none;
}</style>
<?php 
if($jenis_sales_program=='SCP') {
    $hide = ' hide';
}else{
  $hide='';
} 

$hide2='';
 if ($jenis_sales_program!='Direct Gift') {
  $hide='hide';
}
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
      WHERE tr_sales_program_tipe.id_program_md='$id_program_md'");
    if ($sales_program->num_rows() > 0) {
      foreach ($sales_program->result() as $rs) { 
        if (!!$rs->jenis_bayar_dibelakang) {
          $jenis_bayar_dibelakang = ", $rs->jenis_bayar_dibelakang";
        }else{
          $jenis_bayar_dibelakang='';
        }

        ?>
        <tr>
          <td rowspan="4"><?php echo $rs->id_tipe_kendaraan ?></td>
          <td rowspan="4"><?php echo $rs->tipe_ahm ?></td>
          <td rowspan="4"><?php echo $rs->id_warna ?></td>
          <td rowspan="4"><?php echo $rs->tahun_produksi ?></td>
          <td><b>AHM</b></td>
          <td align='right'><?php echo mata_uang($rs->ahm_cash) ?></td>
          <td align='right'><?php echo mata_uang($rs->ahm_kredit )?></td>
          <td rowspan="4"><?php echo $rs->metode_pembayaran ?><?php echo $jenis_bayar_dibelakang ?></td>
          <?php if ($hide==''): ?>
            <td rowspan="4" class="jenis_barang <?php echo $hide ?>"><?php echo $rs->jenis_barang ?></td>
          <td rowspan="4" class="qty_minimum <?php echo $hide2 ?>"><?php echo $rs->qty_minimum ?></td>
          <?php endif ?>
          <td rowspan="4">
            <button type="button" data-toggle="modal" data-target=".modal_edit_detailkendaraan" class="btn btn-warning btn-sm btn-flat" onclick="editDetailKendaraan('<?php echo $rs->id_sales_program_tipe ?>')"><i class="fa fa-pencil"></i></button><br><br>
            <button type="button" class="btn btn-danger btn-sm btn-flat" onclick="delDetailKendaraan('<?php echo $rs->id_sales_program_tipe ?>')"><i class="fa fa-trash"></i></button></td>
        </tr>
        <tr>
          <td><b>MD</b></td>
          <td align='right'><?php echo mata_uang($rs->md_cash) ?></td>
          <td align='right'><?php echo mata_uang( $rs->md_kredit) ?></td>
        </tr>
        <tr>
          <td><b>Dealer</b></td>
          <td align='right'><?php echo mata_uang($rs->dealer_cash) ?></td>
          <td align='right'><?php echo mata_uang($rs->dealer_kredit )?></td>
        </tr>
        <tr>
          <td><b>Other</b></td>
          <td align='right'><?php echo mata_uang($rs->other_cash) ?></td>
          <td align='right'><?php echo mata_uang($rs->other_kredit) ?></td>
        </tr>
    <?php  }
    }
     ?>
  </tbody>
  <tfoot>
    <tr>
     <td rowspan="4">
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
     <td rowspan="4"><input type="text" id="nama_type" class="form-control" readonly></td>
     <td rowspan="4"><select class="select2 id_warna" style="width: 100%" multiple="multiple" name="id_warna" id="id_warna">
       </select></td>
      <td rowspan="4"><input type="text" name="tahun_produksi" id="tahun_produksi" class="form-control" autocomplete="off" placeholder="Semua Tahun">
          <i style="font-size: 12px">Contoh pengisian : 2017, 2018</i>
      </td>
      <td><b>AHM</b></td><td><input type="text" name="ahm_cash"  id="ahm_cash" class="form-control"></td><td><input type="text" name="ahm_kredit" id="ahm_kredit"  class="form-control"></td>
      <td rowspan="4"><select class="form-control" id="metode_pembayaran" name="metode_pembayaran" onchange="cekMetodePembayaran()">
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
        <td rowspan="4" class="jenis_barang <?php echo $hide ?>"><input type="text" name="jenis_barang" id="jenis_barang" class="form-control"></td>
      <td rowspan="4" class="qty_minimum <?php echo $hide2 ?>"><input type="text" name="qty_minimum" id="qty_minimum" class="form-control"></td>
      <?php endif ?>
      <td rowspan="4">
          <button type="button" class="btn btn-primary btn-sm btn-flat" onclick="addDetailKendaraan_edit()"><i class="fa fa-plus"></i></button>
      </td>
   </tr>
   <tr>
     <td><b>MD</b></td><td><input type="text" name="md_cash" id="md_cash" class="form-control"></td><td><input type="text" name="md_kredit" id="md_kredit" class="form-control"></td>
   </tr>
   <tr>
     <td><b>Dealer</b></td><td><input type="text" name="dealer_cash"  id="dealer_cash" class="form-control"></td><td><input type="text" name="dealer_kredit"  id="dealer_kredit" class="form-control"></td>
   </tr>
   <tr>
     <td><b>Other</b></td><td><input type="text" name="other_cash" id="other_cash" class="form-control"></td><td><input type="text" name="other_kredit" id="other_kredit" class="form-control"></td>
   </tr> 
  </tfoot>

</table>
<script type="text/javascript">
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