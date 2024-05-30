<!-- Modal -->
<div id="h3_md_qty_supply_pemenuhan_po_dealer" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style='width: 80%;'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Delivery Order</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_qty_supply_pemenuhan_po_dealer_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. SO</th>
                            <th>Tanggal SO</th>
                            <th>No. DO</th>
                            <th>Tanggal DO</th>
                            <th>No. Faktur</th>
                            <th>Tanggal Faktur</th>
                            <th>Kuantitas</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        h3_md_qty_supply_pemenuhan_po_dealer_datatable = $('#h3_md_qty_supply_pemenuhan_po_dealer_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: '<?= base_url('api/md/h3/qty_supply_pemenuhan_po_dealer') ?>',
                                dataSrc: 'data',
                                type: 'POST',
                                data: function(d) {
                                    d.id_part = app.parts[app.index_part].id_part;
                                    d.po_id = app.purchase.po_id;
                                }
                            },
                            columns: [
                                {
                                    data: 'index',
                                    orderable: false,
                                    width: '3%'
                                },
                                { data: 'id_sales_order' },
                                { 
                                    data: 'tgl_so',
                                    render: function(data){
                                        return moment(data).format('DD/MM/YYYY');
                                    }
                                },
                                { data: 'id_do_sales_order' },
                                { 
                                    data: 'tgl_do',
                                    render: function(data){
                                        return moment(data).format('DD/MM/YYYY');
                                    }
                                },
                                { data: 'no_faktur' },
                                { 
                                    data: 'tgl_faktur',
                                    render: function(data){
                                        return moment(data).format('DD/MM/YYYY');
                                    }
                                },
                                { data: 'kuantitas' },
                            ],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>