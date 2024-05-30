<table id="myTable" class="table myTable1 order-list" border="0">
	<thead>		
		<tr>
			<td width="15%">Kode Part</td>
			<td width="30%">Nama Part</td>
			<td width="15%">Tipe Diskon</td>
			<td width="10%">Range Diskon 1</td>
			<td width="10%">Range Diskon 2</td>
			<td width="10%">Range Diskon 3</td>
			<td width="5%">Aksi</td>
		</tr>
	</thead>	
	<tbody>
		<tr>
			<td>
				<input type="text" readonly id="id_part" data-toggle="modal" placeholder="Kode Part" data-target="#Partmodal" class="form-control" id="id_part" onchange="take_part()">                               
			</td>
			<td>
				<input id="nama_part" placeholder="Nama Part" readonly type="text" class="form-control isi">
			</td>
			<td>
				<select id="tipe_diskon" class="form-control isi">
					<option value="">- choose -</option>
					<option>Rupiah</option>
					<option>Persen</option>
				</select>
			</td>
			<td>
				<input type="text" id="range1" placeholder="Range Diskon 1" class="form-control isi">
			</td>
			<td>
				<input type="text" id="range2" placeholder="Range Diskon 2" class="form-control isi">
			</td>
			<td>
				<input type="text" id="range3" placeholder="Range Diskon 3" class="form-control isi">
			</td>
			<td>
				<button class="btn btn-primary btn-xs btn-flat" type="button" onclick="addDetail()"><i class="fa fa-plus"></i> Add</button>
			</td>
		</tr>
	</tbody>
	<tbody>
	<?php 	
	if($item = $this->item->get_content()) {
		foreach ($item as $res){ ?>
			<tr>
				<td><?= $res['id_part']?></td>
				<td><?= $res['nama_part'] ?></td>
				<td><?= $res['tipe_diskon'] ?></td>
				<td><?= $res['qty'] ?></td>
				<td><?= $res['price'] ?></td>
				<td><?= $res['range3'] ?></td>				
				<td align="center">					
					<button data-toggle="tooltip" title="Delete" class="btn btn-danger btn-flat btn-xs" type="button" onclick="delDetail('<?= $res['rowid']?>')"><i class="fa fa-trash" ></i></button>
				</td>				
			<?php } ?>
		<?php } ?>
		</tbody>
</table>