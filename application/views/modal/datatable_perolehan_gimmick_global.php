<table id="perolehan_gimmick_global" class="table table-bordered table-hover table-condensed">
    <thead>
    <tr>
        <?php $start_date = Mcarbon::parse($sales_campaign['start_date']); ?>
        <?php $end_date = Mcarbon::parse($sales_campaign['end_date']); ?>
        <?php $perbedaan_bulan = $start_date->diffInMonths($end_date); ?>
        <?php if($perbedaan_bulan == 0): ?>
        <th colspan='<?= ( 2 + count($sales_campaign_details) + 1 ) ?>' class='text-center'>Rincian pembelian bulan <?= lang('month_' . $start_date->format('n')) ?> <?= $start_date->format('Y') ?></th>
        <?php else: ?>
        <th colspan='<?= ( 2 + count($sales_campaign_details) + 1 ) ?>' class='text-center'>Rincian pembelian bulan <?= lang('month_' . $start_date->format('n')) ?> <?= $start_date->format('Y') ?> - <?= lang('month_' . $end_date->format('n')) ?> <?= $end_date->format('Y') ?></th>
        <?php endif; ?>
        <?php foreach($sales_campaign_gimmick_globals as $sales_campaign_gimmick_global): ?>
        <th rowspan='2'>Jumlah Bonus <br> <?= $sales_campaign_gimmick_global['nama_hadiah'] ?> <br>(<?= strtoupper($sales_campaign_gimmick_global['qty_hadiah']) ?> <?= strtoupper($sales_campaign_gimmick_global['satuan_hadiah']) ?>)</th>
        <?php endforeach; ?>
        <th rowspan='2'>Sisa Pembelian tidak dihitung</th>
        <th rowspan='2'></th>
    </tr>
    <tr>
        <th>No.</th>
        <th>Nama Dealer</th>
        <?php foreach($sales_campaign_details as $sales_campaign_detail): ?>
        <?php if($sales_campaign_detail['jenis_item_gimmick'] == 'Per Kelompok Part'): ?>
        <th><?= $sales_campaign_detail['id_kelompok_part'] ?></th>
        <?php else: ?>
        <th><?= $sales_campaign_detail['nama_part'] ?> (<?= $sales_campaign_detail['id_part'] ?>)</th>
        <?php endif; ?>
        <?php endforeach; ?>
        <th>Total Pembelian <?= $sales_campaign['satuan_rekapan_gimmick'] == 'Dus' ? '(DUS)' : '' ?></th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script>
    $(document).ready(function() {
    perolehan_gimmick_global = $('#perolehan_gimmick_global').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        ordering: false,
        ajax: {
            url: "<?= base_url('api/md/h3/perolehan_gimmick_global') ?>",
            dataSrc: "data",
            type: "POST",
            data: function(d){
            d.id_campaign = <?= $this->input->get('id') ?>;
            }
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

                    return accounting.formatNumber(data, decimal_point, '.');
                }
            },
            <?php endforeach; ?>
            { 
                data: '<?= $sales_campaign['satuan_rekapan_gimmick'] == 'Satuan' ? 'total_pembelian' : 'total_pembelian_dus' ?>',
                render: function(data){
                decimal_point = data % 1 == 0 ? 0 : 2;

                    return accounting.formatNumber(data, decimal_point, '.');
                }
            },
            <?php foreach($sales_campaign_gimmick_globals as $sales_campaign_gimmick_global): ?>
            { 
                data: '<?= $sales_campaign_gimmick_global['label_key'] ?>',
                render: function(data){
                    decimal_point = data % 1 == 0 ? 0 : 2;

                    return accounting.formatNumber(data, decimal_point, '.');
                }
            },
            <?php endforeach; ?>
            { 
                data: 'total_pembelian_sisa',
                render: function(data){
                decimal_point = data % 1 == 0 ? 0 : 2;

                    return accounting.formatNumber(data, decimal_point, '.');
                }
            },
            { data: 'action', className: 'text-center', orderable: false, width: '200px' },
        ],
    });
    });
</script>