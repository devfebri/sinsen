<!-- Modal -->
<div id="h3_md_so_filter_monitoring_kerja_picker_index" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Sales Order</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_so_filter_monitoring_kerja_picker_index_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No. Sales Order</th>
                            <th>Kode Customer</th>
                            <th>Nama Customer</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_so_filter_monitoring_kerja_picker_index_datatable = $('#h3_md_so_filter_monitoring_kerja_picker_index_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/sales_order_filter_monitoring_kerja_picker') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.filters = filter_sales_order.filters;
                            }
                        },
                        columns: [
                            { data: 'id_sales_order' },
                            { data: 'kode_dealer_md' },
                            { data: 'nama_dealer' },
                            { data: 'action', className: 'text-center', width: '3%', orderable: false }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>