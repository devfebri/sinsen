<?php if($this->input->post('id_debt_collector_filter') == $id_karyawan): ?>
<button class="btn btn-xs btn-flat btn-danger" type='button' onclick='return pilih_debt_collector_filter_penerimaan_pembayaran_index(<?= $data ?>, "reset_filter")' data-dismiss='modal'><i class="fa fa-trash-o"></i></button>
<?php else: ?>
<button class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_debt_collector_filter_penerimaan_pembayaran_index(<?= $data ?>, "add_filter")' data-dismiss='modal'><i class="fa fa-check"></i></button>
<?php endif; ?>