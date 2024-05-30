<!-- Modal -->
<div id="h3_md_picking_list_filter_monitoring_picking" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Picking List</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_picking_list_filter_monitoring_picking_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Picking List</th>
                            <th>No. DO</th>
                            <th>No. SO</th>
                            <th>Nama Dealer</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_picking_list_filter_monitoring_picking_datatable = $('#h3_md_picking_list_filter_monitoring_picking_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/picking_list_filter_monitoring_picking') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.filters = app.filters_picking_list;
                            }
                        },
                        columns: [
                            { data: 'id_picking_list' },
                            { data: 'id_do_sales_order' },
                            { data: 'id_sales_order' },
                            { data: 'nama_dealer' },
                            { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>