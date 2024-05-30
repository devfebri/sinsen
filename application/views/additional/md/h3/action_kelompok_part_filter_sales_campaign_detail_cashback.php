<?php if($this->input->post('id_kelompok_part_filter') == $id_kelompok_part): ?>
<button class="btn btn-xs btn-flat btn-danger" type='button' onclick='return pilih_kelompok_part_filter_part_sales_campaign_detail_cashback(<?= $data ?>, "reset_filter")' data-dismiss='modal'><i class="fa fa-trash-o"></i></button>
<?php else: ?>
<button class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_kelompok_part_filter_part_sales_campaign_detail_cashback(<?= $data ?>, "add_filter")' data-dismiss='modal'><i class="fa fa-check"></i></button>
<?php endif; ?>