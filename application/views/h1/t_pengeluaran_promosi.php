<table class="table table-bordered table-hover myTable1">
  <thead>
    <tr>
                    <th>Item Barang</th>
                    <th>Kategori Item</th>
                    <th>Qty On Hand</th>
                    <th>Qty Kirim</th>
                    <th>Aksi</th>
                  </tr>
  </thead>
  <tbody>
    <?php foreach ($detail->result() as $rs): ?>
      <tr>
        <td><?= $rs->item_promosi?></td>
        <td><?= $rs->kategori_item?></td>
        <td><?= $rs->qty_on_hand?></td>
        <td><?= $rs->qty_kirim?></td>
        <td><button type="button" class="btn btn-danger btn-xs btn-flat" title="Add" onclick="delDetail(<?= $rs->id?>)"><i class="fa fa-trash"></i></button></td>
      </tr>
    <?php endforeach ?>
  </tbody>
  <tfoot>
         <tr>
                    <td>
                      <?php $item=$this->db->query("SELECT * FROM `ms_item_promosi` left join ms_kategori_item on ms_item_promosi.id_kategori_item=ms_kategori_item.id_kategori_item") ?>
                      <select class="form-control select2" name="item_barang" id="item_barang" onchange="showItem()">
                          <?php if ($item->num_rows()>0): ?>

                            <option value="">- choose -</option>
                            <?php foreach ($item->result() as $rs): ?>
                              <?php 
                            $jml_penerimaan=$this->db->query("SELECT sum(qty_terima) as jum FROM tr_penerimaan_promosi_detail WHERE id_item_promosi='$rs->id_item_promosi' ");
                            $jml_penerimaan = $jml_penerimaan->num_rows()>0?$jml_penerimaan->row()->jum:0;
                            $jml_pengeluaran=$this->db->query("SELECT sum(qty_kirim) as jml FROM tr_pengeluaran_promosi_detail WHERE item_barang='$rs->id_item_promosi' ");
                             $jml_pengeluaran = $jml_pengeluaran->num_rows()>0?$jml_pengeluaran->row()->jml:0;
                             $qty_on_hand = $jml_penerimaan-$jml_pengeluaran;
                             ?>
                                <option value="<?=$rs->id_item_promosi?>" data-kategori="<?= $rs->kategori_item ?>" data-qty_on_hand="<?= $qty_on_hand?>"><?=$rs->id_item_promosi?> | <?=$rs->item_promosi?></option>
                            <?php endforeach ?>
                          <?php endif ?>
                      </select>
                    </td>
                    <td>
                      <input type="text" class="form-control" id="kategori_item" placeholder="Kategori Item" readonly>
                    </td>
                    <td>
                      <input type="text" class="form-control" id="qty_on_hand" placeholder="Qty On Hand" readonly>
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
      var value={item_barang:$('#item_barang').val(),
                 kategori_item:$('#kategori_item').val(),
                 qty_on_hand:$('#qty_on_hand').val(),
                 qty_kirim:$('#qty_kirim').val(),
                 id_pengeluaran_promosi:<?=$id?>

      }

      $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('h1/pengeluaran_promosi/addDetail');?>",
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
               url:"<?php echo site_url('h1/pengeluaran_promosi/delDetail');?>",
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

  function showItem()
{
  var qty_on_hand = $("#item_barang").select2().find(":selected").data("qty_on_hand");
  var kategori_item = $("#item_barang").select2().find(":selected").data("kategori");
  $('#kategori_item').val(kategori_item);
  $('#qty_on_hand').val(qty_on_hand);
}
</script>