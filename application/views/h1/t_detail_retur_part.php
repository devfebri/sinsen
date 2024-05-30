
<table id="myTable" class="table myTable1 order-list" border="0">
   <thead>
    <tr>
      <th width="15%">Kode Part</th>
      <th width="10%">Nama Part</th>      
      <th width="10%">Qty Retur</th>                      
      <th width="10%">Alasan Retur</th>                      
      <th width="5%">Action</th>                      
    </tr>
  </thead>
 	<tbody>
    <?php if (isset($detail_retur_part)) { ?>  
    <?php foreach ($detail_retur_part->result() as $retur): ?>
      <tr>
        <td><?php echo $retur->id_part ?></td>
        <td><?php echo $retur->nama_part ?></td>
        <td><?php echo $retur->qty_retur ?></td>
        <td><?php echo $retur->alasan_retur ?></td>
        <td>
          <button type="button" onClick="deleteDetail(<?php echo $retur->id_retur_part_detail ?>)" class="btn btn-sm btn-danger btn-flat  btn-xs"><i class="fa fa-trash"></i></button> </td>
      </tr>
    <?php endforeach ?>
  <?php } ?>
  <tr>
                      <td>
                        <select class="form-control select2 isi_combo"  id="part" onchange="getPart()">
                          <option value="">- choose -</option>
                        </select>
                      </td>
                      <td>
                        <input type="text" class="form-control isi" name="nama_part" id="nama_part" readonly>
                      </td>
                      <td>
                        <input type="text" class="form-control isi" name="qty_retur" id="qty_retur"> 
                      </td>
                      <td>
                        <select class="form-control select2 isi_combo" id="id_alasan_retur_part">
                          <option value="">- choose -</option>
                           <?php $part = $this->db->query("SELECT * from ms_alasan_retur_part"); ?>
                            <?php foreach ($part->result() as $p): ?>
                              <option value="<?php echo $p->id_alasan_retur_part ?>" ><?php echo $p->alasan_retur ?></option>
                            <?php endforeach ?>
                        </select>
                      </td>
                      <td>
                        <button class='btn btn-flat btn-success btn-xs' type="button" onclick="simpanDetail()">Add</button> 
                        <!--<a class='btn btn-flat btn-primary btn-xs'> Edit</a> 
                        <a class='btn btn-flat btn-danger btn-xs'>Delete</a> --> 
                      </td>
                    </tr>
  </tbody>
</table>

<script type="text/javascript">

  $(document).ready(function(){
    $(".select2").select2();
    jenis_ReturNoSJ();
  });

  function simpanDetail()
  {
  	  var id_part = $("#part option:selected").val();
      if (id_part=="") {
      	alert('Silahkan Pilih Kode Part');
      }
      else
      {
        var qty_retur = $("#qty_retur").val();
	      var id_alasan_retur_part = $("#id_alasan_retur_part option:selected").val();

	       $.ajax({
                beforeSend: function() { $('#loading-status').show(); },
	               url:"<?php echo site_url('h1/retur_part/addDetail');?>",
	               type:"POST",
	               data:"id_part="+id_part
                  +"&qty_retur="+qty_retur
	                +"&id_alasan_retur_part="+id_alasan_retur_part,
	               cache:false,
	               success:function(html){
                $('#loading-status').hide();
	                  $("#tampil_detail").html(html);
	               }
	          });
      }
  }

  function deleteDetail(id)
  {
    	var id_retur_part_detail = id;
    	$.ajax({
                beforeSend: function() { $('#loading-status').show(); },

        url:"<?php echo site_url('h1/retur_part/deleteDetail');?>",
        type:"POST",
        data:"id_retur_part_detail="+id_retur_part_detail,
        cache:false,
        success:function(html){        
                $('#loading-status').hide();    
           $("#tampil_detail").html(html);
        }
    })
  }

</script>

<script type="text/javascript">
  function getPart()
  {
    var id_part = $('#part').val();
    //var nama_part = $('#part option:selected', this).attr('nama_part');
    var nama_part = $('select#part option:selected').attr('nama_part');
    $('#nama_part').val(nama_part);
    
  }
</script>