<ul class='no-margin' onclick='return open_status_pembayaran("<?= $referensi ?>")'>
    <?php foreach ($list_bg as $row): ?>
    <li class='text-left'><?= $row['nomor_bg'] ?></li>
    <?php endforeach; ?>
</ul>
<!-- <button onclick='return open_status_pembayaran("<?= $referensi ?>")' class="btn btn-flat btn-xs btn-info">View</button> -->