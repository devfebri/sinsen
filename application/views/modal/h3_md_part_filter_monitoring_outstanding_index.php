<!-- Modal -->
<div id="h3_md_part_filter_monitoring_outstanding_index" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Part Filter</h4>
        </div>
        <div class="modal-body">
            <table id="h3_md_part_filter_monitoring_outstanding_index_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <th>Kode Part</th>
                        <th>Nama Part</th>
                        <th>Kelompok Part</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_part_filter_monitoring_outstanding_index_datatable = $('#h3_md_part_filter_monitoring_outstanding_index_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/part_filter_monitoring_outstanding') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                            d.filters = part_filter.filters;
                        }
                    },
                    columns: [
                        { data: 'id_part' },
                        { data: 'nama_part' },
                        { data: 'kelompok_part' },
                        { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>