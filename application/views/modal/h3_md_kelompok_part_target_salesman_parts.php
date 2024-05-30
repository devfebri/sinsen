<!-- Modal -->
<div id="h3_md_kelompok_part_target_salesman_parts" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Salesman</h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_kelompok_part_target_salesman_parts_datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kelompok Part</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_kelompok_part_target_salesman_parts_datatable = $('#h3_md_kelompok_part_target_salesman_parts_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: '<?= base_url('api/md/h3/kelompok_part_target_salesman_parts') ?>',
                        dataSrc: 'data',
                        type: 'POST',
                    },
                    columns: [
                        { data: null, orderable: false, width: '3%'},
                        { data: 'kelompok_part' },
                        { data: 'action', orderable: false, widht: '3%', className: 'text-center' }
                    ],
                });

                h3_md_kelompok_part_target_salesman_parts_datatable.on('draw.dt', function() {
                    var info = h3_md_kelompok_part_target_salesman_parts_datatable.page.info();
                    h3_md_kelompok_part_target_salesman_parts_datatable.column(0, {
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