<!-- Modal -->
<div id="h3_md_filter_kode_part_monitoring_ptrac" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Kode Part</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_filter_kode_part_monitoring_ptrac_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Part</th>
                            <th>Deskripsi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_filter_kode_part_monitoring_ptrac_datatable = $('#h3_md_filter_kode_part_monitoring_ptrac_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/filter_kode_part_monitoring_ptrac') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.filters = filter_kode_part.filters;
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' },
                            { data: 'id_part' },
                            { data: 'nama_part' },
                            { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>