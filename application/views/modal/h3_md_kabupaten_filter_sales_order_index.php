<!-- Modal -->
<div id="h3_md_kabupaten_filter_sales_order_index" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Kabupaten</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_kabupaten_filter_sales_order_index_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Kabupaten</th>
                            <th>Provinsi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_kabupaten_filter_sales_order_index_datatable = $('#h3_md_kabupaten_filter_sales_order_index_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/kabupaten_filter_sales_order_index') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.filters = kabupaten_filter.filters;
                            }
                        },
                        columns: [
                            { data: 'kabupaten' },
                            { data: 'provinsi' },
                            { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>