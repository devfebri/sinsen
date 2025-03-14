<!-- Modal -->
<div id="h3_md_jabatan_filter_salesman_target_salesman" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Filter Jabatan</h4>
        </div>
        <div class="modal-body">
            <table id="h3_md_jabatan_filter_salesman_target_salesman_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <th>Kode Jabatan</th>
                        <th>Jabatan</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_jabatan_filter_salesman_target_salesman_datatable = $('#h3_md_jabatan_filter_salesman_target_salesman_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/jabatan_filter_salesman_target_salesman') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                            d.id_jabatan_filter = $('#id_jabatan_filter').val();
                        }
                    },
                    columns: [
                        { data: 'id_jabatan' },
                        { data: 'jabatan' },
                        { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>