<ul onclick='return open_keterangan_monitor_plafon("<?= $no_faktur ?>")' class='no-margin'>
    <?php foreach($open_keterangan as $row): ?>
    <li class='text-left'><?= $row['nomor_bg'] ?> - <?= $row['tanggal_jatuh_tempo_bg'] ?></li>
    <?php endforeach; ?>
</ul>