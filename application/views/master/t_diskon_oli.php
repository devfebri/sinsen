<table class="table table bordered table-condensed">
  <tr>
    <td width="20%">Kode Part</td>
    <td width="80%">
			<input type="text" readonly name="id_part" data-toggle="modal" placeholder="Kode Part" data-target="#Partmodal" class="form-control" id="id_part" value="<?php echo $dt_sql->id_part ?>" onchange="take_part()">                                   	
		</td>
  </tr>
  <tr>
  	<td>Nama Part</td>
  	<td> 
  		<input value="<?php echo $dt_sql->nama_part ?>" name="nama_part" id="nama_part" placeholder="Nama Part" readonly type="text" class="form-control isi">
  	</td>
  </tr>
  <tr>
  	<td>Tipe Diskon</td>
  	<td>
  		<select id="tipe_diskon" class="form-control isi" name="tipe_diskon">
				<option <?php if($dt_sql->tipe_diskon == "") echo "selected" ?> value="">- choose -</option>
				<option <?php if($dt_sql->tipe_diskon == "Rupiah") echo "selected" ?>>Rupiah</option>
				<option <?php if($dt_sql->tipe_diskon == "Persen") echo "selected" ?>>Persen</option>
			</select>
  	</td>
  </tr>
  <tr>
  	<td>Range Diskon 1</td>
  	<td>
  		<input value="<?php echo $dt_sql->range_1 ?>" id="range1" name="range1" placeholder="Range 1" type="text" class="form-control isi">
  	</td>
  </tr>
  <tr>
  	<td>Range Diskon 2</td>
  	<td> 
  		<input value="<?php echo $dt_sql->range_2 ?>" id="range2" name="range2" placeholder="Range 2" type="text" class="form-control isi">
  	</td>
  </tr>
  <tr>
  	<td>Range Diskon 3</td>
  	<td> 
  		<input value="<?php echo $dt_sql->range_3 ?>" id="range3" placeholder="Range 3" name="range3" type="text" class="form-control isi">
  		<input value="<?php echo $dt_sql->id_diskon_oli ?>" id="id_diskon_oli" type="hidden" name="id_diskon_oli">
  	</td>
  </tr>
</table>