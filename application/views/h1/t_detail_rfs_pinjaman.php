<table id="myTable" class="table myTable1 order-list" border="0">
   <thead>
    <tr>
      <th width="35%">No. Mesin</th>
      <th width="15%">Tipe</th>      
      <th width="5%">Warna</th>                      
      <th width="5%">Kode Item</th>                      
      <th width="20%">Keterangan</th>                      
      <th width="20%">KSU</th>                      
      <th width="5%">Action</th>                      
    </tr>
  </thead>
  <tbody>
 	<?php if (isset($rfs_pinjaman_detail)) { ?>  
 		<?php foreach ($rfs_pinjaman_detail->result() as $rfs): ?>
 			<tr>
 				<td><?php echo $rfs->no_mesin ?></td>
 				<td><?php echo $rfs->tipe_motor ?>-<?php echo $rfs->tipe_ahm ?></td>
 				<td><?php echo $rfs->id_warna ?>-<?php echo $rfs->warna ?></td>
        <td><?php echo $rfs->id_item ?></td>
 				<td><?php echo $rfs->keterangan ?></td>
        <td>
          <?php
            $rd = $this->db->query("SELECT * from tr_rfs_pinjaman_detail_ksu left join ms_ksu on tr_rfs_pinjaman_detail_ksu.id_ksu = ms_ksu.id_ksu where no_mesin = '$rfs->no_mesin' and id_rfs_pinjaman = '$rfs->id_rfs_pinjaman' ");?>
            <?php foreach ($rd->result() as $rd): ?>
              <?php  echo "
                 <div class='input-group' style='padding-bottom:5px;'>
                  <span class='input-group-addon bg-maroon'>$rd->ksu</span>       ";?>
                  <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
            &nbsp;<input type="checkbox" class="flat-red checked_ksu" name="ksu" id="checked_ksu" id_rfs_ksu="<?php echo $rd->id ?>" value="<?php echo $rd->checked ?>" <?php if($rd->checked=='1'){echo "checked"; } ?>>
          </div></div>
            <?php endforeach ?>
           
        </td>
 				<td>
 					<input type="hidden" name="id_rfs_pinjaman" value="<?php echo $rfs->id_rfs_pinjaman ?>" class="id_rfs_pinjaman" >
 					<button type="button" onClick="deleteDetail(<?php echo $rfs->id_rfs_pinjaman_detail ?>)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button> </td>
 			</tr>
 		<?php endforeach ?>
 	<?php } ?>                     
    <tr>
      <td style="width: 35%">
        <select class="form-control select2" id="id_detail" onchange="getDetail()" >          
          <option value="">-- Choose --</option>
          <?php if ($mesin->num_rows()>0): ?>
          <?php endif ?>
          <?php foreach ($mesin->result() as $msn): ?>
            <option value="<?php echo $msn->id_scan_barcode ?>" 
                    warna="<?php echo $msn->warna ?>"
                    id_warna="<?php echo $msn->id_warna ?>"
                    id_scan_barcode="<?php echo $msn->id_scan_barcode ?>"
                    lokasi="<?php echo $msn->lokasi ?>"
                    id_item="<?php echo $msn->id_item ?>"
                    tipe_ahm="<?php echo $msn->tipe_ahm ?>"
                    no_mesin="<?php echo $msn->no_mesin ?>"
                    tipe_motor="<?php echo $msn->tipe_motor ?>"
              ><?php echo $msn->no_mesin ?></option>
          <?php endforeach ?>
        </select>
      </td>
      <td>
        <input type="text" name="type" id="type" disabled="" width="100%">
      </td>      
      <td>
        <input type="text" name="Warna" id="Warna" disabled="">
        
      </td>      
      <td>
        <input type="text" name="Kode Item" id="kdItem" disabled="">
      </td>      
     
      <td><input type="text" name="keterangan" id="keterangan_mesin" placeholder="Keterangan"></td>
       <td></td>
      <td>
        <button type="button" onClick="simpanDetail()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table>

<script type="text/javascript">

  $(document).ready(function(){
    $(".select2").select2();
  });
  function getDetail()
  {
      var warna = $("#id_detail option:selected").attr("warna");
      var kdItem = $("#id_detail option:selected").attr("id_item");
      var tipe_ahm = $("#id_detail option:selected").attr("tipe_ahm");
      $('#Warna').val(warna);
      $('#type').val(tipe_ahm);
      $('#kdItem').val(kdItem);
  }

  function simpanDetail()
  {
  	  var id_scan_barcode = $("#id_detail option:selected").val();
      if (id_scan_barcode=="") {
      	alert('Silahkan Pilih No. Mesin');
      }
      else
      {
      	  var id_gudang = $("#gudang option:selected").val();
	      var id_scan_barcode = $("#id_detail option:selected").val();
	      var id_item = $("#id_detail option:selected").attr("id_item");
	      var lokasi = $("#id_detail option:selected").attr("lokasi");
	      var no_mesin = $("#id_detail option:selected").attr("no_mesin");
	      var tipe_motor = $("#id_detail option:selected").attr("tipe_motor");
        var id_warna = $("#id_detail option:selected").attr("id_warna");
	      var keterangan = $("#keterangan_mesin").val();

	       $.ajax({
                 beforeSend: function() { $('#loading-status').show(); },
	               url:"<?php echo site_url('h1/rfs_pinjaman/addDetail');?>",
	               type:"POST",
	               data:"id_scan_barcode="+id_scan_barcode
	                +"&no_mesin="+no_mesin
	                +"&lokasi="+lokasi
	                +"&tipe_motor="+tipe_motor
	                +"&id_warna="+id_warna
                  +"&id_gudang="+id_gudang
	                +"&keterangan="+keterangan
	                +"&id_item="+id_item,
	               cache:false,
	               success:function(html){
                  $('#loading-status').hide();
	                  $("#tampil_detail").html(html);
	                  GetDataNosin();
	               }
	          });
      }
  }

  function deleteDetail(id)
  {
    	var id_rfs_pinjaman_detail = id;
    	var id_gudang = $("#gudang option:selected").val();
    	$.ajax({
        beforeSend: function() { $('#loading-status').show(); },
        url:"<?php echo site_url('h1/rfs_pinjaman/deleteDetail');?>",
        type:"POST",
        data:"id_rfs_pinjaman_detail="+id_rfs_pinjaman_detail
        +"&id_gudang="+id_gudang,
        cache:false,
        success:function(html){      
                  $('#loading-status').hide();
                
           $("#tampil_detail").html(html);
	                  GetDataNosin();

        }
    })
  }

  $('.checked_ksu').on('change',function(e){
      var id_rfs_ksu=$(this).attr('id_rfs_ksu');
      var val=$(this).is(":checked");
      $.ajax({
           url:"<?php echo site_url('h1/rfs_pinjaman/checkedRfs');?>",
           type:"POST",
           data:"id_rfs_ksu="+id_rfs_ksu
           		+"&checked="+val,
           cache:false,
           success:function(html){
           }
      }); 
  })

</script>