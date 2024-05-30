<?php if($sudah_print == 0 || ($faktur_printed == 1 AND $user_group == 'manager')): ?>
<a href="h3/h3_md_packing_sheet/cetak?id=<?= $id ?>" class="btn btn-xs btn-flat btn-info">Cetak</a>
<?php endif; ?>