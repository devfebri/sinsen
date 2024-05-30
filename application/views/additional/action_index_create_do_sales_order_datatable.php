<a href="h3/h3_md_create_do_sales_order/detail?id=<?= $id ?>" class="btn btn-xs btn-flat btn-info">View</a>
<?php if(!$so_tidak_diizinkan_batal): ?>
<a onclick='return confirm("Apakah anda yakin ingin menghilangkan SO ini dari menu Create DO?")' href="h3/h3_md_create_do_sales_order/delete_from_create_do_sales_order?id=<?= $id ?>" class="btn btn-xs btn-flat btn-danger" style='margin-top: 5px;'>Del</a>
<?php endif; ?>