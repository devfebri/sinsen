<?php if($selected == 1): ?>
<button onclick='return pilih_kelompok_part_filter_create_do_sales_order(<?= $data ?>, "hapus_selected")' data-dismiss='modal' class="btn btn-xs btn-flat btn-danger" type="button"><i class="fa fa-trash-o"></i></button>
<?php else: ?>
<button onclick='return pilih_kelompok_part_filter_create_do_sales_order(<?= $data ?>, "add_selected")' data-dismiss='modal' class="btn btn-xs btn-flat btn-success" type="button"><i class="fa fa-check"></i></button>
<?php endif; ?>