<button type="button" class="btn btn-block btn-primary btn-flat">Detail Target Salesman</button>
<table id="myTable" class="table myTable1 order-list" border="0">
	<thead>		
		<tr>
			<th width="15%">Customer</th>
			<th width="20%">Nama Customer</th>
			<th width="10%">Target Part (Rp)</th>
			<th width="10%">Target Oli GMO (Rp)</th>
			<th width="10%">Target Aksesoris (Rp)</th>			
			<th width="5%">Aksi</th>
		</tr>
	</thead>	
	<tbody>
		<tr>
			<td>
				<select name="id_toko" onchange="take_toko()" class="form-control isi" id="id_toko">
          <option value="">- choose -</option>
          <?php 
          foreach ($dt_toko->result() as $isi) {
            echo "<option value='$isi->id_toko'>$isi->id_toko</option>";
          }
          ?>
        </select>
			</td>
			<td>
				<input id="nama_toko" placeholder="Nama Toko" readonly type="text" class="form-control isi">
			</td>
			<td>
				<input type="text" id="target_part" placeholder="Target Part" class="form-control isi">
			</td>
			<td>
				<input type="text" id="target_oli" placeholder="Target Oli Gmo" class="form-control isi">
			</td>
			<td>
				<input type="text" id="target_aksesoris" placeholder="Target Aksesoris" class="form-control isi">
			</td>
			<td>
				<button class="btn btn-primary btn-xs btn-flat" type="button" onclick="addDetail()"><i class="fa fa-plus"></i> Add</button>
			</td>
		</tr>
	</tbody>
	<tbody>
	<?php 	
	if($item_target = $this->item_target->get_content()) {
		foreach ($item_target as $res){ ?>
			<tr>
				<td><?= $res['id_toko']?></td>
				<td><?= $res['nama_toko'] ?></td>
				<td><?= $res['target_part'] ?></td>
				<td><?= $res['qty'] ?></td>
				<td><?= $res['price'] ?></td>				
				<td align="center">					
					<button data-toggle="tooltip" title="Delete" class="btn btn-danger btn-flat btn-xs" type="button" onclick="delDetail('<?= $res['rowid']?>')"><i class="fa fa-trash" ></i></button>
				</td>				
			<?php } ?>
		<?php } ?>
		</tbody>
</table>