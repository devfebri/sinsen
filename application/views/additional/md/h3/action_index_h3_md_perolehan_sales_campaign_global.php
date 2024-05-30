<?php 
    $now = Mcarbon::now();
    $akhir_periode = Mcarbon::parse($akhir_periode);
?>
<?php if($total_perolehan_hadiah > 0 AND $now->greaterthan($akhir_periode)): ?>
<a target='_blank' href="h3/h3_md_sales_order/add?id_perolehan=<?= $id ?>&generateGimmickTidakLangsung=true">
    <button <?= intval($sudah_create_so) == 1 ? 'disabled' : null ?>  class="btn btn-xs btn-flat btn-success">
        Create SO
    </button>
</a>

<?php endif; ?>