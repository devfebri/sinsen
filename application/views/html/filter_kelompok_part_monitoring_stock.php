<label style="margin-right: 5px;">Kelompok Part:
    <select style="margin-left:5px;" id="filter_kelompok_part_monitoring_stock" class="form-control input-sm">
        <option value="">-All-</option>
        <?php foreach($kelompok_part as $e): ?>
        <option value="<?= $e->kelompok_part ?>"><?= $e->kelompok_part ?></option>
        <?php endforeach; ?>
    </select>
</label>
