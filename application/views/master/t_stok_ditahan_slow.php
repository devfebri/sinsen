<label for="inputEmail3" class="col-sm-4 control-label">Tipe</label>
                  <div class="col-sm-6">
                  <select class="form-control select2" id="id_tipe_kendaraan" name="id_tipe_kendaraan">
                  	<?php $tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan 
                  			WHERE active = 1 AND id_tipe_kendaraan NOT IN(SELECT id_tipe_kendaraan FROM ms_stok_ditahan WHERE id_tipe_kendaraan IS NOT NULL)
                  		ORDER BY ms_tipe_kendaraan.id_tipe_kendaraan ASC");
                  	if ($tipe->num_rows()>0) {
                  		echo "<option value=''>- choose -</option>";
                  		foreach ($tipe->result() as $rs) {
                  			echo "<option value='$rs->id_tipe_kendaraan'>$rs->id_tipe_kendaraan | $rs->tipe_ahm</option>";
                  		}
                  	}
                   ?>
                  </select>
                  </div>
                  <div class="col-sm-1"><button class="btn btn-primary btn-sm" type="button" onclick="addStokSlow()"><i class="fa fa-plus"></i></button></div>
<br>    <br>   <div class="col-sm-offset-2 col-sm-9">
       	<table class="table table-condensed table-bordered">
       		<thead>
       			<th width="5%">No</th>
       			<th width="90%">Tipe</th>
       			<!-- <th>Stok Ditahan (%)</th> -->
       			<th>Aksi</th>
       		</thead>
       		<?php $no=1; foreach ($slow->result() as $rs_f): ?>
       			<tr>
       				<td><?php echo $no?></td>
       				<td><?php echo $rs_f->id_tipe_kendaraan?> | <?php echo $rs_f->tipe_ahm?></td>
       				<!-- <td><?php echo $rs_f->persen_stok_ditahan?></td> -->
       				<td><button type="button" class="btn btn-danger btn-xs" onclick="delStok(<?php echo $rs_f->id_stok_ditahan?>)"><i class="fa fa-trash"></i></button></td>
       			</tr>
       			</tr>
       		<?php $no++; endforeach ?>
       	</table>
       </div>
<script type="text/javascript">
    $(".select2").select2({
            allowClear: false
        });
     function addStokSlow()
	  {
	      var value={id_tipe_kendaraan:$('#showSlow #id_tipe_kendaraan').val(),
	                 jenis_moving:'slow',
	                 persen_stok_ditahan:$('#stok_ditahan_slow').val(),

	      }
	      if (value.id_tipe_kendaraan=='' || value.persen_stok_ditahan=='') {
	      	alert('Silahkan lengkapi data..!')
	      }else{
	      $.ajax({
	               beforeSend: function() { $('#loading-status').show(); },
	               url:"<?php echo site_url('master/stok_ditahan/addStok');?>",
	               type:"POST",
	               data:value,
	              // dataType:'JSON',
	               cache:false,
	               success:function(data){
	                  $('#loading-status').hide();
	                  
	                  if(data=="nihil"){
	                    $('#loading-status').hide();
	                    getSlow();    
	                    getFast();    
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
	                    getDetail();    
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
</script>       