<!-- Modal -->
<div id="h3_md_po_hotline_purchase_order" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">PO Hotline</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_po_hotline_purchase_order_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nomor Referensi</th>
                            <th>Nama Customer</th>
                            <th>Nama Konsumen</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_po_hotline_purchase_order_datatable = $('#h3_md_po_hotline_purchase_order_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: '<?= base_url('api/md/h3/po_hotline_purchase') ?>',
                            dataSrc: 'data',
                            type: 'POST',
                            data: function(d){
                                d.jenis_po = app.purchase.jenis_po;
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%'},
                            { data: 'referensi' },
                            { data: 'nama_dealer' },
                            { data: 'nama_customer' },
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>