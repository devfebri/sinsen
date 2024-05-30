<!-- Modal -->
<div id="h3_md_nomor_dus_scan_picking_list" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Nomor Dus</h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_nomor_dus_scan_picking_list_datatable" style="width: 100%">
                <thead>
                    <tr>
                    <th>No.</th>
                    <th>Nomor Dus</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_nomor_dus_scan_picking_list_datatable = $('#h3_md_nomor_dus_scan_picking_list_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: '<?= base_url('api/md/h3/nomor_dus_scan_picking_list') ?>',
                        dataSrc: 'data',
                        type: 'POST',
                        data: function(data){
                            data.id_picking_list = app.picking_list.id_picking_list;
                        }
                    },
                    columns: [
                        { data: null, orderable: false, width: '3%'},
                        { data: 'no_dus' },
                        { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                    ],
                });

                h3_md_nomor_dus_scan_picking_list_datatable.on('draw.dt', function() {
                    var info = h3_md_nomor_dus_scan_picking_list_datatable.page.info();
                    h3_md_nomor_dus_scan_picking_list_datatable.column(0, {
                        search: 'applied',
                        order: 'applied',
                        page: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1 + info.start + ".";
                    });
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>