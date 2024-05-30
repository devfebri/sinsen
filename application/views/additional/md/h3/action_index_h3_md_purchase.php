<?php if($status == 'Approved'): ?>
<a href="h3/h3_md_purchase_order/generate_ppo?id_purchase_order=<?= $id ?>" class="btn btn-xs btn-flat btn-success">Download</a>
<a href="h3/h3_md_purchase_order/cetak?id_purchase_order=<?= $id ?>" class="btn btn-xs btn-flat btn-info">Cetak</a>
<?php endif; ?>
<a href="h3/h3_md_purchase_order/detail?id_purchase_order=<?= $id ?>" class="btn btn-xs btn-flat btn-info">View</a>
<?php if($jenis_po == 'REG'): ?>
<a href="h3/h3_md_purchase_order/download_excel?id_purchase_order=<?= $id ?>" class="btn btn-xs btn-flat btn-info">Report</a>
<?php endif; ?>