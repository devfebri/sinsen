<!-- Modal -->
<div id="h3_md_qty_po_pemenuhan_po_dealer" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">PO Hotline</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_qty_po_pemenuhan_po_dealer_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. PO</th>
                            <th>Tanggal PO</th>
                            <th>Jenis PO</th>
                            <th>Qty PO</th>
                            <th>ETD</th>
                            <th>ETD Revisi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_qty_po_pemenuhan_po_dealer_datatable = $('#h3_md_qty_po_pemenuhan_po_dealer_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: '<?= base_url('api/md/h3/qty_po_pemenuhan_po_dealer') ?>',
                            dataSrc: 'data',
                            type: 'POST',
                            data: function(d){
                                console.log(app.purchase);
                                d.id_part = app.parts[app.index_part].id_part;
                                d.po_id = app.purchase.po_id;
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%'},
                            { data: 'id_purchase_order' },
                            { data: 'tanggal_po' },
                            { data: 'jenis_po' },
                            { data: 'qty_order' },
                            { 
                                data: 'etd',
                                render: function(data){
                                    if(data != null){
                                        return moment(data).format('DD/MM/YYYY');
                                    }
                                    return '-';
                                }
                            },
                            { 
                                data: 'etd_revisi',
                                render: function(data){
                                    if(data != null){
                                        return moment(data).format('DD/MM/YYYY');
                                    }
                                    return '-';
                                }
                            },
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>