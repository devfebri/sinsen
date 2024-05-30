<label style="margin-right: 5px;">
    <select style="margin-left:5px;" id="filter_tipe_kendaraan_check_part_stock" class="form-control">
        <option value="">-All-</option>
        <?php foreach($kategori as $e): ?>
        <option value="<?= $e->id_kategori ?>"><?= $e->kategori ?></option>
        <?php endforeach; ?>
    </select>
</label>
