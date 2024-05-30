<table id="examdpe4" class="table table-bordered table-condensed"> 
	<thead>
		<tr align="center">
			<th style="width: 90%">Program</th>
			<th style="width: 10%" align="center">Aksi</th>
		</tr>
	</thead>
	<tbody>
		<?php $cek=0;foreach ($sp_gab->result() as $key=> $rs): ?>
			<tr>
				<td><?php echo $rs->id_program_md ?> | <?php echo $rs->judul_kegiatan ?></td>
				<td><button type="button" class="btn btn-danger btn-sm btn-flat" onclick="delGabungan(<?= $rs->id ?>)"><i class="fa fa-trash"></i></button></td>
			</tr>
		<?php $cek++; endforeach ?>
	</tbody>
	<tfoot>
		<tr>
			<th>
				<select class="form-control select2" name="id_program_md_gabungan" id="id_program_md_gabungan">
				<?php if ($dt_sp->num_rows() > 0): ?>
					<option value="">--Pilih--</option>
					<?php foreach ($dt_sp->result() as $res): ?>
						<option value="<?=$res->id_program_md?>"><?= $res->id_program_md ?> | <?= $res->judul_kegiatan ?></option>
					<?php endforeach ?>
				<?php endif ?>
				</select>
			</th>
			<th align="center"><button class="btn btn-primary btn-sm btn-flat" type="button" onclick="addGabungan()"><i class="fa fa-plus"></i></button></th>
		</tr>
	</tfoot>
</table>
<input type="hidden" value="<?= $cek ?>" id="cek_gabungan">