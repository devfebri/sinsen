<table class="table table-condensed">
  <?php 
    $getAnalisis = $this->db->query("SELECT * FROM tr_analisis_proyeksi_order_detail
                        inner JOIN tr_analisis_proyeksi_order on tr_analisis_proyeksi_order_detail.id_analisis= tr_analisis_proyeksi_order.id_analisis
                        WHERE id_tipe_kendaraan='$id_tipe_kendaraan' AND id_dealer='$id_dealer'
      ");
    $getTipe = $this->db->query("SELECT * FROM tr_analisis_proyeksi_order_detail
                        inner JOIN tr_analisis_proyeksi_order on tr_analisis_proyeksi_order_detail.id_analisis= tr_analisis_proyeksi_order.id_analisis
                        inner join ms_tipe_kendaraan on tr_analisis_proyeksi_order_detail.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
                        WHERE id_dealer='$id_dealer' AND qty_order > 0"); 
    $getItem = $this->db->query("SELECT * FROM ms_item WHERE active=1 AND id_tipe_kendaraan='$id_tipe_kendaraan'");
    $getQtyMD = $this->db->query("SELECT count(tipe_motor) as stok FROM tr_scan_barcode WHERE tipe_motor='$id_tipe_kendaraan' AND status=1 AND tipe='RFS'")->row()->stok;
   ?>
  <tbody>
    <?php if ($getItem->num_rows()>0) {
      $getA = $getAnalisis->row();
      $x = $getItem->num_rows();
      $i=0;
      foreach ($getItem->result() as $key => $res) { ?>
        <tr>
          <?php if ($i==0): ?>
            <td rowspan="<?=$x?>" width="30%">
            <select class="form-control select2" onchange="getInput()" id="id_tipe_kendaraan" style="width: 100%">
              <?php if ($getTipe->num_rows()>0) {
                echo "<option value=''>- choose -</option>";
              foreach ($getTipe->result() as $tp) { 
                  if ($tp->id_tipe_kendaraan==$id_tipe_kendaraan) {
                    $selected='selected';
                  }else{
                    $selected='';
                  }
                ?>
                <option value="<?=$tp->id_tipe_kendaraan?>" <?=$selected?>><?=$tp->id_tipe_kendaraan?> | <?=$tp->tipe_ahm?></option>
             <?php }
            } ?>
            </select>
          </td>
          <td rowspan="<?=$x?>" width="15%"><?=$getA->qty_order?></td>
          <!-- <td rowspan="<?=$x?>" width="15%"><?=$getQtyMD?></td> -->
          <?php endif ?>
          <td width="15%"><input type="text" name="id_item_<?=$key?>" id="id_item_<?=$key?>" value="<?=$res->id_item?>" readonly class="form-control"></td>
          <td width="15%"><input type="text" name="qty_order_<?=$key?>" id="qty_order_<?=$key?>" class="form-control" autocomplete="off" onkeyup="cekAnalisis(<?=$key?>)">
          </td>
         <?php if ($i==0): ?>
            <td rowspan="<?=$x?>" width="10%">
              <button class="btn btn-primary btn-flat" type="button" onclick="addDetailPO()"><i class="fa fa-plus"></i></button>
            </td>
         <?php endif ?>
        </tr>
     <?php $i++; }
    } ?>
  </tbody> 
</table>
<script type="text/javascript">
  function addDetailPO()
   {
    var value={
                 qty:<?=$x?>,
                 <?php for($i=0;$i<$getItem->num_rows();$i++){ ?>
                    id_item_<?=$i?>:$('#id_item_<?=$i?>').val(),
                    qty_po_fix_<?=$i?>:$('#qty_order_<?=$i?>').val(),
                <?php } ?>
                id_po:$('#id_po').val(),
      }
      var tot=0;
    for (var i = 0; i < <?=$getItem->num_rows()?>; i++) {
      var qty   = parseInt($("#qty_order_"+i).val());
      if (isNaN(qty)) qty = 0;
      tot += qty;
    }
    var qty_analisis = <?=$getA->qty_order?>;
    if (tot< qty_analisis) {
      alert('Qty Order yang dimasukkan kurang dari Qty Analisis')
    }else{
      
      $.ajax({
           beforeSend: function() { $('#loading-status').show(); },
           url:"<?php echo site_url('dealer/po_d/save_po_reg')?>",
           type:"POST",
           data:value,
           cache:false,
           success:function(html){
              $('#loading-status').hide();
              kirim_data_po_reg();
           },
           statusCode: {
        500: function() {
          $('#loading-status').hide();
          alert("Something Wen't Wrong");
        }
      }
      });
    }
   }
   function cekAnalisis(c)
   {
    var tot=0;
    for (var i = 0; i < <?=$getItem->num_rows()?>; i++) {
      var qty   = parseInt($("#qty_order_"+i).val());
      if (isNaN(qty)) qty = 0;
      tot += qty;
    }
    if (tot > <?=$getA->qty_order?>) {
      alert('Qty melebihi batasan analisis');
      $('#qty_order_'+c).val('');
    };
   }
</script>