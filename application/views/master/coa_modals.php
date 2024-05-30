<table class="table table-bordered" id="datatables">
	<thead>
		<th>No.</th>
		<th>Kode COA</th>
		<th>COA</th>
		<th>Tipe Transaksi</th>
		<th>Aksi</td>
	</thead>
	<tbody>
		<?php $no=1; foreach ($coa->result() as $co): ?>
			<tr>
				<td><?= $no ?></td>
				<td><?= $co->kode_coa ?></td>
				<td><?= $co->coa ?></td>
				<td><?= $co->tipe_transaksi ?></td>
				<td align="center">
					<button class='btn btn-success btn-xs' data-dismiss='modal' onclick='return pilihCOA(<?= json_encode($co) ?>)'><i class='fa fa-check'></i></button>
				</td>
			</tr>
		<?php $no++ ; endforeach ?>
	</tbody>
</table>
<script>

	function pilihCOA(coa)
	{

		var set = <?=$temp?>;
		var kode_coa 		= $('.kode_coa_temp_'+set).val(coa.kode_coa);
		var coa 		    = $('.coa_temp_'+set).val(coa.coa);
		var tipe_transaksi  = $('.tipe_transaksi_temp_'+set).val(coa.tipe_transaksi);

		// var kode_coa 		= $('#kode_coa_2').val(coa.kode_coa);
		// var coa 		    = $('#coa_2').val(coa.coa);
		// var tipe_transaksi  = $('#tipe_transaksi_2').val(coa.tipe_transaksi);

		
		// form_entri.detail.kode_coa = coa.kode_coa;
		// form_entri.detail.coa = coa.coa;
		// form_entri.detail.tipe_transaksi = coa.tipe_transaksi;
	}
</script>