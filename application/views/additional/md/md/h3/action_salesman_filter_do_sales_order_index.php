<?php if($selected == 1): ?>
<button class="btn btn-xs btn-flat btn-danger" type='button' onclick='return pilih_salesman_filter_do_sales_order_index(<?= $data ?>, "reset_filter")' data-dismiss='modal'><i class="fa fa-trash-o"></i></button>
<?php else: ?>
<button class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_salesman_filter_do_sales_order_index(<?= $data ?>, "add_filter")' data-dismiss='modal'><i class="fa fa-check"></i></button>
<?php endif; ?>