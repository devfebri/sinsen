<?php if($this->input->post('id_jabatan_filter') == $id_jabatan): ?>
<button class="btn btn-xs btn-flat btn-danger" type='button' onclick='return pilih_jabatan_filter_salesman_target_salesman(<?= $data ?>, "reset_filter")' data-dismiss='modal'><i class="fa fa-trash-o"></i></button>
<?php else: ?>
<button class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_jabatan_filter_salesman_target_salesman(<?= $data ?>, "add_filter")' data-dismiss='modal'><i class="fa fa-check"></i></button>
<?php endif; ?>