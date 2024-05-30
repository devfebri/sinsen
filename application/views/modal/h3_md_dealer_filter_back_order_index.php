<!-- Modal -->
<div id="h3_md_dealer_filter_back_order_index" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">Customer</h4>
        </div>
        <div class="modal-body">
            <table id="h3_md_dealer_filter_back_order_index_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <th>Kode Dealer</th>
                        <th>Nama Dealer</th>
                        <th>Alamat</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_dealer_filter_back_order_index_datatable = $('#h3_md_dealer_filter_back_order_index_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/dealer_filter_back_order_index') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                            d.filters = filter_customer.filters;
                        }
                    },
                    columns: [
                        { data: 'kode_dealer_md' },
                        { data: 'nama_dealer' },
                        { data: 'alamat' },
                        { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>