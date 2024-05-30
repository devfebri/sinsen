<table id="perolehan_gimmick_item" class="table table-bordered table-hover table-condensed">
    <thead>
    <tr>
        <th rowspan='2'>No.</th>
        <th rowspan='2'>Nama Dealer</th>

        <?php foreach($sales_campaign_details as $sales_campaign_detail): ?>
        <?php if($sales_campaign_detail['jenis_item_gimmick'] == 'Per Kelompok Part'): ?>
        <th rowspan='2' class='text-center'><?= $sales_campaign_detail['id_kelompok_part'] ?></th>
        <?php else: ?>
        <th rowspan='2' class='text-center'><?= $sales_campaign_detail['nama_part'] ?><br>(<?= $sales_campaign_detail['id_part'] ?>)</th>
        <?php endif; ?>
        <?php endforeach; ?>

        <?php foreach($sales_campaign_details as $sales_campaign_detail): ?>
        <?php if($sales_campaign_detail['jenis_item_gimmick'] == 'Per Kelompok Part'): ?>
        <th colspan='<?= count($sales_campaign_detail['sales_campaign_gimmick_items']) ?>' class='text-center'>Hadiah Pembelian <?= $sales_campaign_detail['id_kelompok_part'] ?></th>
        <?php else: ?>
        <th colspan='<?= count($sales_campaign_detail['sales_campaign_gimmick_items']) ?>' class='text-center'>Hadiah Pembelian <?= $sales_campaign_detail['nama_part'] ?> (<?= $sales_campaign_detail['id_part'] ?>)</th>
        <?php endif; ?>
        <?php endforeach; ?>

        <?php foreach($sales_campaign_details as $sales_campaign_detail): ?>
        <?php if($sales_campaign_detail['jenis_item_gimmick'] == 'Per Kelompok Part'): ?>
        <th rowspan='2' class='text-center'>Sisa Pembelian <?= $sales_campaign_detail['id_kelompok_part'] ?></th>
        <?php else: ?>
        <th rowspan='2' class='text-center'>Sisa Pembelian <?= $sales_campaign_detail['nama_part'] ?><br>(<?= $sales_campaign_detail['id_part'] ?>)</th>
        <?php endif; ?>
        <?php endforeach; ?>
        <th rowspan='2' class='text-center'></th>
    </tr>
    <tr>
        <?php foreach($sales_campaign_details as $sales_campaign_detail): ?>
            <?php foreach($sales_campaign_detail['sales_campaign_gimmick_items'] as $sales_campaign_gimmick_item): ?>
            <?php if($sales_campaign_gimmick_item['hadiah_part'] == 1): ?>
            <th class='text-center'>Jml Bonus <?= $sales_campaign_gimmick_item['qty_hadiah'] ?> <?= $sales_campaign_gimmick_item['satuan_hadiah'] ?> <?= $sales_campaign_gimmick_item['id_part'] ?></th>
            <?php else: ?>
            <th class='text-center'>Jml Bonus <?= $sales_campaign_gimmick_item['qty_hadiah'] ?> <?= $sales_campaign_gimmick_item['satuan_hadiah'] ?> <?= $sales_campaign_gimmick_item['nama_hadiah'] ?></th>
            <?php endif; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script>
$(document).ready(function() {
    perolehan_gimmick_item = $('#perolehan_gimmick_item').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        scrollX: true,
        ordering: false,
        ajax: {
            url: "<?= base_url('api/md/h3/perolehan_gimmick_item') ?>",
            dataSrc: "data",
            type: "POST",
            data: function(d){
                d.id_campaign = <?= $this->input->get('id') ?>;
            }
        },
        createdRow: function (row, data, index) {
            $('td', row).addClass('align-middle');
        },
        columns: [
            { data: 'index', orderable: false, width: '3%' },
            { 
                data: 'nama_dealer',
                render: function(data, type, row){
                    return row.kode_dealer_md + ' - ' + row.nama_dealer;
                },
                width: '350px'
            },
            <?php foreach($sales_campaign_details as $sales_campaign_detail): ?>
            { 
                data: '<?= $sales_campaign_detail['id'] ?>_detail',
                render: function(data){
                    decimal_point = data % 1 == 0 ? 0 : 2;

                    return accounting.formatNumber(data, decimal_point, '.') + '<?= $sales_campaign['satuan_rekapan_gimmick'] == 'Dus' ? ' Dus' : '' ?>';
                }
            },
            <?php endforeach; ?>
            <?php foreach($sales_campaign_details as $sales_campaign_detail): ?>
                <?php foreach($sales_campaign_detail['sales_campaign_gimmick_items'] as $sales_campaign_gimmick_item): ?>
                { 
                    data: '<?= $sales_campaign_gimmick_item['id'] ?>_item',
                    render: function(data){
                        decimal_point = data % 1 == 0 ? 0 : 2;

                        return accounting.formatNumber(data, decimal_point, '.') + ' <?= $sales_campaign_gimmick_item['satuan_hadiah'] ?>';
                    }
                },
                <?php endforeach; ?>
            <?php endforeach; ?>

            <?php foreach($sales_campaign_details as $sales_campaign_detail): ?>
            { 
                data: '<?= $sales_campaign_detail['id'] ?>_sisa',
                render: function(data){
                    decimal_point = data % 1 == 0 ? 0 : 2;

                    return accounting.formatNumber(data, decimal_point, '.') + '<?= $sales_campaign['satuan_rekapan_gimmick'] == 'Dus' ? ' Dus' : '' ?>';
                }
            },
            <?php endforeach; ?>
            { data: 'action', orderable: false, class: 'text-center', width: '200px' },
        ],
    });
});
</script>