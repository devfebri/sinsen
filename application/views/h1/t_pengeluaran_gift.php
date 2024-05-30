<table class="table table-bordered table-hover myTable1">
  <thead>
    <tr>
                    <th>Apparel</th>
                    <th style="width: 40%">Qty On Hand</th>
                    <th>Qty Kirim</th>
                    <th>Aksi</th>
                  </tr>
  </thead>
  <tbody>
    <?php foreach ($detail->result() as $rs): ?>
      <tr>
        <td><?= $rs->id_apparel?> | <?= $rs->apparel?></td>
        <td><?= $rs->qty_on_hand?></td>
        <td><?= $rs->qty_kirim?></td>
        <td><button type="button" class="btn btn-danger btn-xs btn-flat" title="Add" onclick="delDetail(<?= $rs->id?>)"><i class="fa fa-trash"></i></button></td>
      </tr>
    <?php endforeach ?>
  </tbody>
  <tfoot>
         <tr>
                    <td>
                      <?php $item=$this->db->query("SELECT * FROM ms_apparel ORDER BY apparel ASC") ?>
                      <select class="form-control select2" name="id_apparel" id="id_apparel" onchange="showApparel()">
                          <?php if ($item->num_rows()>0): ?>
                            <option value="">- choose -</option>
                            <?php foreach ($item->result() as $rs):
                                $qty_penerimaan=$this->db->query("SELECT SUM(qty_penerimaan) as jml FROM tr_penerimaan_gift_detail WHERE status<>'new' AND id_apparel='$rs->id_apparel'");
                                if ($qty_penerimaan->num_rows() >0) {
                                  $qty_penerimaan=$qty_penerimaan->row()->jml;
                                }else{
                                  $qty_penerimaan=0;
                                }
                                $qty_kirim=$this->db->query("SELECT SUM(qty_kirim) as jml FROM tr_pengeluaran_gift_detail WHERE status<>'new' AND id_apparel='$rs->id_apparel'");
                                if ($qty_kirim->num_rows() >0) {
                                  $qty_kirim=$qty_kirim->row()->jml;
                                }else{
                                  $qty_kirim=0;
                                }
                                $qty_on_hand=$qty_penerimaan-$qty_kirim;
                             ?>
                                <option value="<?=$rs->id_apparel?>" data-qty_on_hand='<?=$qty_on_hand?>'><?=$rs->id_apparel?> | <?=$rs->apparel?></option>
                            <?php endforeach ?>
                          <?php endif ?>
                      </select>
                    </td>
                    <td>
                      <input type="text" class="form-control" id="qty_on_hand" placeholder="" readonly>
                    </td>
                    <td>
                      <input type="text" class="form-control" id="qty_kirim" placeholder="Qty">
                    </td>
                    <td>
                      <button type="button" class="btn btn-primary btn-xs btn-flat" title="Add" onclick="addDetail()"><i class="fa fa-plus"></i></button>
                    </td>
                  </tr>
  </tfoot>
                </table>   
<script>
  function addDetail()
  {
      var value={id_apparel:$('#id_apparel').val(),
                 qty_on_hand:$('#qty_on_hand').val(),
                 qty_kirim:$('#qty_kirim').val(),
                 id_pengeluaran_gift:<?=$id?>

      }

      $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('h1/pengeluaran_gift/addDetail');?>",
               type:"POST",
               data:value,
              // dataType:'JSON',
               cache:false,
               success:function(data){
                  $('#loading-status').hide();
                  
                  if(data=="nihil"){
                    $('#loading-status').hide();
                    getDetail(<?= $id?>);    
                  }else{
                    alert(data);          
                  }    
               },
               statusCode: {
            500: function() { 
              $('#loading-status').hide();
              alert("Something Wen't Wrong");
            }
          }
          });
  }
  function delDetail(id)
  {
      var value={id:id}

      $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('h1/pengeluaran_gift/delDetail');?>",
               type:"POST",
               data:value,
               //dataType:'JSON',
               cache:false,
               success:function(data){
                  $('#loading-status').hide();
                  if(data=="nihil"){
                    getDetail(<?= $id?>);    
                  }else{
                    alert(data);          
                  }    
               },
               statusCode: {
            500: function() { 
              $('#loading-status').hide();
              alert("Something Wen't Wrong");
            }
          }
          });
  }

  function showApparel()
{
  var qty_on_hand = $("#id_apparel").select2().find(":selected").data("qty_on_hand");
  $('#qty_on_hand').val(qty_on_hand);
}
</script>