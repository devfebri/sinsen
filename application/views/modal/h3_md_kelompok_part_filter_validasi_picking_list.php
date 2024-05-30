<!-- Modal -->
<div id="h3_md_kelompok_part_filter_validasi_picking_list" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Kelompok Part</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_kelompok_part_filter_validasi_picking_list_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Kelompok Part</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_kelompok_part_filter_validasi_picking_list_datatable = $('#h3_md_kelompok_part_filter_validasi_picking_list_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/kelompok_part_filter_validasi_picking_list') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_picking_list = app.picking.id_picking_list;
                                d.filters_kelompok_part = app.filters_kelompok_part;
                            }
                        },
                        columns: [
                            { data: 'id_kelompok_part' },
                            { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>