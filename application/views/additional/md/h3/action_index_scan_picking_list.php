<a href="h3/h3_md_scan_picking_list/detail?id_picking_list=<?= $data['id_picking_list'] ?>" class="btn btn-xs btn-flat btn-info margin">View</a>
<?php if($data['selesai_scan'] != 1 && $data['status']!='Canceled'): ?>
<a href="h3/h3_md_scan_picking_list/scan?id_picking_list=<?= $data['id_picking_list'] ?>&status=start" class="btn btn-xs btn-flat btn-success margin">Scan</a>
<?php endif; ?>