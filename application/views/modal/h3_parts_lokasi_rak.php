<!-- Modal -->
<div id="h3_parts_lokasi_rak" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Part</h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover table-condensed" id="h3_parts_lokasi_rak_datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode Part</th>
                        <th>Nama Part</th>
                        <th>Kelompok Part</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_parts_lokasi_rak_datatable = $('#h3_parts_lokasi_rak_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: '<?= base_url('api/md/h3/parts_lokasi_rak') ?>',
                        dataSrc: 'data',
                        type: 'POST',
                        data: function(data){
                            if(form_.parts.length > 0){
                                data.selected_id_parts = _.map(form_.parts, function(p){
                                    return p.id_part_int;
                                });
                            }
                        }
                    },
                    columns: [
                        { data: 'index', orderable: false, width: '3%'},
                        { data: 'id_part' },
                        { data: 'nama_part' },
                        { data: 'kelompok_part' },
                        { data: 'action', orderable: false, widht: '3%', className: 'text-center' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>