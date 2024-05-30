<button type="button" class="btn btn-block btn-primary btn-flat">Detail Target Salesman</button>
<table id="myTable" class="table myTable1 order-list" border="0">
	<thead>		
		<tr>
			<th width="15%">Kode Customer</th>
			<th width="20%">Nama Customer</th>
			<th width="10%">Tipe Diskon</th>
			<th width="10%">Diskon Fixed Order</th>
			<th width="10%">Diskon Reguler</th>			
			<th width="10%">Diskon Hotline</th>			
			<th width="10%">Diskon Urgent</th>			
			<th width="5%">Aksi</th>
		</tr>
	</thead>	
	<tbody>
		<tr>
			<td>
				<select name="id_dealer" onchange="take_dealer()" class="form-control isi" id="id_dealer">
          <option value="">- choose -</option>
          <?php 
          foreach ($dt_dealer->result() as $isi) {
            echo "<option value='$isi->id_dealer'>$isi->kode_dealer_ahm</option>";
          }
          ?>
        </select>
			</td>
			<td>
				<input id="nama_dealer" placeholder="Nama Dealer" readonly type="text" class="form-control isi">
			</td>
			<td>
				<select class="form-control isi" id="tipe_diskon">
					<option value="">- choose -</option>
					<option>Rupiah</option>
					<option>Persen</option>
				</select>
			</td>
			<td>
				<input type="text" id="diskon_fix" placeholder="Diskon Fixed Order" class="form-control isi">
			</td>
			<td>
				<input type="text" id="diskon_reguler" placeholder="Diskon Reguler" class="form-control isi">
			</td>
			<td>
				<input type="text" id="diskon_hotline" placeholder="Diskon Hotline" class="form-control isi">
			</td>
			<td>
				<input type="text" id="diskon_urgent" placeholder="Diskon Urgent" class="form-control isi">
			</td>
			<td>
				<button class="btn btn-primary btn-xs btn-flat" type="button" onclick="addDetail()"><i class="fa fa-plus"></i> Add</button>
			</td>
		</tr>
	</tbody>
	<tbody>
	<?php 	
	if($item_part = $this->item_part->get_content()) {
		foreach ($item_part as $res){ 
			$sql = $this->m_admin->getByID("ms_dealer","id_dealer",$res['id_dealer']);
			$kode_dealer_md = ($sql->num_rows() > 0) ? $sql->row()->kode_dealer_md : "" ;
			?>
			<tr>
				<td><?= $kode_dealer_md?></td>
				<td><?= $res['nama_dealer'] ?></td>
				<td><?= $res['tipe_diskon'] ?></td>
				<td><?= $res['qty'] ?></td>
				<td><?= $res['price'] ?></td>				
				<td><?= $res['diskon_hotline'] ?></td>				
				<td><?= $res['diskon_urgent'] ?></td>				
				<td align="center">					
					<button data-toggle="tooltip" title="Delete" class="btn btn-danger btn-flat btn-xs" type="button" onclick="delDetail('<?= $res['rowid']?>')"><i class="fa fa-trash" ></i></button>
				</td>				
			<?php } ?>
		<?php } ?>
		</tbody>
</table>