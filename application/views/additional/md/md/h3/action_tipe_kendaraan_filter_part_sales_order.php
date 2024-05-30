<?php if($this->input->post('id_tipe_kendaraan_filter') == $id_tipe_kendaraan): ?>
<button class="btn btn-xs btn-flat btn-danger" type='button' onclick='return pilih_tipe_kendaraan_filter_part_sales_order(<?= $data ?>, "reset_filter")' data-dismiss='modal'><i class="fa fa-trash-o"></i></button>
<?php else: ?>
<button class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_tipe_kendaraan_filter_part_sales_order(<?= $data ?>, "add_filter")' data-dismiss='modal'><i class="fa fa-check"></i></button>
<?php endif; ?>