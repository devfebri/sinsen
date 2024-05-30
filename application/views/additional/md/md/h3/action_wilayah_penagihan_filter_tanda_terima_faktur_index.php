<?php if($this->input->post('id_wilayah_penagihan_filter') == $id_wilayah_penagihan): ?>
<button class="btn btn-xs btn-flat btn-danger" type='button' onclick='return pilih_wilayah_penagihan_filter_tanda_terima_faktur_index(<?= $data ?>, "reset_filter")' data-dismiss='modal'><i class="fa fa-trash-o"></i></button>
<?php else: ?>
<button class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_wilayah_penagihan_filter_tanda_terima_faktur_index(<?= $data ?>, "add_filter")' data-dismiss='modal'><i class="fa fa-check"></i></button>
<?php endif; ?>