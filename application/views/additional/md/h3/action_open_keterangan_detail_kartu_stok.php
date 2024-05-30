<?php if($sumber_transaksi == 'h3_md_laporan_penerimaan_barang'): ?>
    <span onclick='return open_view_packing_sheet_ahm("<?= $keterangan ?>", <?= json_encode($nomor_karton) ?>)'><?= $keterangan ?></span>
<?php elseif($sumber_transaksi == 'h3_md_create_faktur'): ?>
    <span onclick='return open_view_create_faktur("<?= $id_do_sales_order ?>")'><?= $keterangan ?></span>
<?php elseif($sumber_transaksi == 'h3_md_penerimaan_po_vendor'): ?>
    <span onclick='return open_view_penerimaan_po_vendor("<?= $keterangan ?>")'><?= $keterangan ?></span>
<?php else: ?>
    <?= $keterangan ?>
<?php endif;