<a href="h3/h3_md_validasi_picking_list/open?id_picking_list=<?= $id ?>" class="btn btn-xs btn-flat btn-info">Open</a>
<a href="h3/h3_md_validasi_picking_list/open?id_picking_list=<?= $id ?>&status=start">
    <button <?= $status == 'Closed PL' ? 'disabled' : '' ?> class="btn btn-xs btn-flat btn-success">Start</button>
</a>
<a href="h3/h3_md_validasi_picking_list/cetak_picking_list?id_picking_list=<?= $id ?>" class="btn btn-xs btn-flat btn-info">Cetak</a>