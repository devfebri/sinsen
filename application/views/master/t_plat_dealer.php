<table id="example2" class="table table-bordered">
	<thead>
		<th>No.</th>
		<th>ID Dealer</th>
		<th>Nama Dealer</th>
		<th>No. Plat</th>
		<th>Driver</th>
		<th>Action</th>
	</thead>
	<tbody>
		<tr>
			<td colspan="2">
				<select class="form-control select2">
					<option>-- Choose --</option>
					<?php $dealer = $this->db->query("SELECT * FROM ms_dealer ORDER BY nama_dealer"); ?>
					<?php foreach ($dealer->result() as $dealer): ?>
						<option value="<?php echo $dealer->id_dealer ?>"><?php echo $dealer->nama_dealer ?></option>
					<?php endforeach ?>
				</select>
			</td>
		</tr>
	</tbody>
</table>