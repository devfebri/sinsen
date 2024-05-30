<table class="table table-bordered table-hover myTable1">
  <thead>
    <tr>
                    <th>Kode Apparel</th>
                    <th>Apparel</th>
                    <th>Qty Penerimaan</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                  </tr>
  </thead>
  <tbody>
    <?php foreach ($detail->result() as $rs): ?>
      <tr>
        <td><?= $rs->id_apparel?></td>
        <td><?= $rs->apparel?></td>
        <td><?= $rs->qty_penerimaan?></td>
        <td><?= $rs->keterangan?></td>
        <td><button type="button" class="btn btn-danger btn-xs btn-flat" title="Add" onclick="delDetail(<?= $rs->id?>)"><i class="fa fa-trash"></i></button></td>
      </tr>
    <?php endforeach ?>
  </tbody>
  <tfoot>
         <tr>
                    <td>
                      <?php $item=$this->db->query("SELECT * FROM ms_apparel ORDER BY apparel ASC") ?>
                      <select class="form-control select2" name="id_apparel" id="id_apparel" onchange="showApparel()">>
                          <?php if ($item->num_rows()>0): ?>
                            <option value="">- choose -</option>
                            <?php foreach ($item->result() as $rs): ?>
                                <option value="<?=$rs->id_apparel?>" data-apparel="<?=$rs->apparel?>"><?=$rs->id_apparel?> | <?=$rs->apparel?></option>
                            <?php endforeach ?>
                          <?php endif ?>
                      </select>
                    </td>
                    <td>
                      <input type="text" class="form-control" id="apparel" placeholder="Apparel" disabled="">
                    </td>
                    <td>
                      <input type="text" class="form-control" id="qty_penerimaan" placeholder="QTY Penerimaan">
                    </td>
                    <td>
                      <input type="text" class="form-control" id="keterangan" placeholder="Keterangan">
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
                 qty_penerimaan:$('#qty_penerimaan').val(),
                 keterangan:$('#keterangan').val(),
                 id_penerimaan:<?=$id?>

      }

      $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('h1/penerimaan_gift/addDetail');?>",
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
               url:"<?php echo site_url('h1/penerimaan_gift/delDetail');?>",
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
  var apparel = $("#id_apparel").select2().find(":selected").data("apparel");
  $('#apparel').val(apparel);
}

</script>