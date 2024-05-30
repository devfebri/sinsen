<label style="margin-right: 5px;">Part:
    <select style="margin-left:5px;" id="filter_part_record_demand" class="form-control input-sm">
        <option value="">-All-</option>
        <?php foreach($parts as $e): ?>
        <option value="<?= $e->id_part ?>"><?= $e->id_part ?> - <?= $e->nama_part ?></option>
        <?php endforeach; ?>
    </select>
</label>
