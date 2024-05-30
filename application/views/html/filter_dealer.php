<label style="margin-right: 5px;">Dealer:
    <select style="margin-left:5px; width:120px" id="filter_dealer" class="form-control input-sm">
        <option value="">-Dealer-</option>
        <?php foreach($dealer as $each): ?>
        <option value="<?= $each->id_dealer ?>"><?= $each->nama_dealer ?></option>
        <?php endforeach; ?>
    </select>
</label>
