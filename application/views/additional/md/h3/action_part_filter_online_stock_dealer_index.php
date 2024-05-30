<?php if($this->input->post('id_part_filter') == $id_part): ?>
<button class="btn btn-xs btn-flat btn-danger" type='button' onclick='return pilih_part_filter_online_stock_dealer_index(<?= $data ?>, "reset_filter")' data-dismiss='modal'><i class="fa fa-trash-o"></i></button>
<?php else: ?>
<button class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_part_filter_online_stock_dealer_index(<?= $data ?>, "add_filter")' data-dismiss='modal'><i class="fa fa-check"></i></button>
<?php endif; ?>