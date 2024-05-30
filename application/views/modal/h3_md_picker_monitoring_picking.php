<!-- Modal -->
<div id="h3_md_picker_monitoring_picking" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Picker</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_picker_monitoring_picking_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Nama Picker</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_picker_monitoring_picking_datatable = $('#h3_md_picker_monitoring_picking_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/picker_monitoring_picking') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_picker = app.picker.id_picker;
                            }
                        },
                        columns: [
                            { data: 'nama_lengkap' },
                            { data: 'action', className: 'text-center', width: '3%', orderable: false }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>