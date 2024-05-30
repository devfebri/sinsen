<input type="hidden" id='id_purchase_order_for_view_customer'>
<div id="h3_md_view_customer_purchase_order" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Customer</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_view_customer_purchase_order_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Customer</th>
                            <th>Nama Customer</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_view_customer_purchase_order_datatable = $('#h3_md_view_customer_purchase_order_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: '<?= base_url('api/md/h3/view_customer_purchase_order') ?>',
                            dataSrc: 'data',
                            type: 'POST',
                            data: function(d){
                                d.id_purchase_order_for_view_customer = $('#id_purchase_order_for_view_customer').val();
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%'},
                            { data: 'kode_dealer_md' },
                            { data: 'nama_dealer' },
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>