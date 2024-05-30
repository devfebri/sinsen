<?php 

	function mata_uang($a){

			return number_format($a, 0, ',', '.');

	} ?>



<table class="table table-condensed table-bordered">

	<tbody>

		<?php if ($detail->num_rows()>0){ ?>

			<?php $x=0; foreach ($detail->result() as $rs): ?>

					<tr>

						<?php if ($x==0): ?>

								<td width="22%" rowspan="<?php echo  $detail->num_rows()+1?>" style="vertical-align: middle;text-align: left">

									<?php $tipe=$this->db->query("SELECT * FROM ms_tipe_kendaraan");

								if ($tipe->num_rows()>0) { ?>

									<select id="id_tipe_kendaraan" class="form-control select2" onchange="getInput()">

										<option value="">- choose -</option>

								<?php

									foreach ($tipe->result() as $dt) { 

										if ($dt->id_tipe_kendaraan == $id_tipe_kendaraan) {

											$selected='selected';

										}else{

											$selected='';

										}

									?>

<option  <?php echo  $selected ?> value="<?php echo $dt->id_tipe_kendaraan?>"> <?php echo $dt->id_tipe_kendaraan?> | <?php echo  strip_tags($dt->deskripsi_ahm) ?></option>

									<?php }

									}



									?>

									</select>

							 </td>

						<?php endif ?>



						<td width="10%"><input type="text" name="" id="id_item_<?php echo $x?>" value="<?php echo $rs->id_item?>" readonly class="form-control" ></td>

						<td width="22%"><?php echo $rs->warna?>

							<input type="hidden" value="<?php echo  ucwords($rs->bundling)=='Ya'?'y':'n' ?>" id='bdl_<?php echo $x?>'>

						</td>

						



						<td width="7%" align="center"><input type="checkbox" name="chk_<?php echo $x?>" id="chk_<?php echo $x?>" onchange="setHargaBaru(<?php echo  $x ?>)"></td>

						<td width="15%"><?php echo  mata_uang($rs->harga_jual)?></td>

						<?php ucwords($rs->bundling)=='Ya'?$input_all='input_harga_baru_bundling':$input_all='input_harga_baru'; ?>

						<?php ucwords($rs->bundling)=='Ya'?$setAll='setAllBundling('.$x.')':$setAll='setAll('.$x.')'; ?>

						<td width="15%"><input type="" name="" onkeyup="<?php echo $setAll?>" class="tanpa_rupiah form-control <?php echo $input_all?>" id="harga_baru_<?php echo $x?>" autocomplete="off" readonly></td>

						<?php if ($x==0): ?>

							<td  rowspan="<?php echo  $detail->num_rows()?>" style="vertical-align: middle;text-align: center" ><button type="button" class="btn btn-primary btn-flat btn-xs" onclick="addDetail()"><i class="fa fa-plus"></i></button></td>

						<?php endif ?>

			<?php $x++; endforeach ?>

	</tr>









		<?php }else{ ?>

		 <td width="22%">

				<?php $tipe=$this->db->query("SELECT * FROM ms_tipe_kendaraan");

			if ($tipe->num_rows()>0) { ?>

				<select id="id_tipe_kendaraan" class="form-control select2" onchange="getInput()">

					<option value="">- choose -</option>

			<?php

				foreach ($tipe->result() as $rs) { 

					if ($rs->id_tipe_kendaraan == $id_tipe_kendaraan) {

						$selected='selected';

					}else{

						$selected='';

					}

				?>

	<option value="<?php echo  $rs->id_tipe_kendaraan?>"  <?php echo  $selected ?> ><?php echo $rs->id_tipe_kendaraan?> | <?php echo  strip_tags($rs->deskripsi_ahm) ?></option>

				<?php }

				}



				?>

				</select>

		 </td>

		 <td></td>

		 <td></td>

		 <td></td>

		 <td></td>

		 <td></td>



		<?php } ?>

	</tbody>

</table>





<script type="text/javascript">

	function setHargaBaru(a)

	{

		var awal = $('.one').val();

		var awal_bdl = $('.one_bdl').val();

		var bdl = $('#bdl_'+a).val();



		if ($('#chk_'+a).is(':checked')) {

			if (bdl=='y') {

				if (awal_bdl===undefined) {

					$('#harga_baru_'+a).addClass("one_bdl");

				}

				$('#harga_baru_'+a).val(awal_bdl);

			}

			else

			{

				if (awal===undefined) {

					$('#harga_baru_'+a).addClass("one");

				}

				$('#harga_baru_'+a).val(awal);

			}



			$('#harga_baru_'+a).removeAttr('readonly');

			

		}else{

			$('#harga_baru_'+a).removeClass("one");

			$('#harga_baru_'+a).removeClass("one_bdl");

		}

		

	}



	function addDetail()

	{

			var value={id_tipe_kendaraan:$('#id_tipe_kendaraan').val(),

								 qty_kirim:$('#qty_kirim').val(),

					<?php for($i=0;$i<$detail->num_rows();$i++){ ?>

										harga_baru_<?php echo $i?>:$('#harga_baru_<?php echo $i?>').unmask(),

										id_item_<?php echo $i?>:$('#id_item_<?php echo $i?>').val(),

										chk_<?php echo $i?>: $('#chk_<?php echo $i?>').is(":checked")?'ya':'',

								<?php } ?>

									id_tipe_kendaraan:'<?php echo $id_tipe_kendaraan?>',

			}

      //alert(id_tipe_kendaraan);

			$.ajax({

							 beforeSend: function() { $('#loading-status').show(); },

							 url:"<?php echo site_url('master/kelompok_md/addDetail');?>",

							 type:"POST",

							 data:value,

							// dataType:'JSON',

							 cache:false,

							 success:function(data){

									$('#loading-status').hide();

									

									if(data=="nihil"){

										$('#loading-status').hide();

										generate();

									}else{

										alert(data); 

                    generate();         

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





const thousands = (x) => {

	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

}



	function setAll(a)

	{

			var a = $('#harga_baru_'+a).unmask();

			var a = $('.one').val();

			var vall = thousands(a);

			// $('.input_harga_baru').val(vall);

	}



	function setAllBundling(a)

	{

			var a = $('#harga_baru_'+a).unmask();

			var vall = thousands(a);

			// $('.input_harga_baru_bundling').val(vall);

	}



</script>