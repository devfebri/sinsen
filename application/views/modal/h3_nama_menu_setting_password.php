<!-- Modal -->
<div id="h3_nama_menu_setting_password" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Menu</h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover table-condensed" id="h3_nama_menu_setting_password_datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Menu</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_nama_menu_setting_password_datatable = $('#h3_nama_menu_setting_password_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    searching: true,
                    ajax: {
                        url: '<?= base_url('api/md/h3/nama_menu_setting_password') ?>',
                        dataSrc: 'data',
                        type: 'POST',
                    },
                    columns: [
                        { data: null, orderable: false, width: '3%'},
                        { data: 'menu_name' },
                        { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                    ],
                });

                h3_nama_menu_setting_password_datatable.on('draw.dt', function() {
                    var info = h3_nama_menu_setting_password_datatable.page.info();
                    h3_nama_menu_setting_password_datatable.column(0, {
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