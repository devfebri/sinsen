<!-- Modal -->
<div id="h3_md_kelurahan_customer" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Domisili</h4>
        </div>
        <div class="modal-body">
            <table id="h3_md_kelurahan_customer_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <th>Kelurahan</th>
                        <th>Kecamatan</th>
                        <th>Kabupaten</th>
                        <th>Provinsi</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_kelurahan_customer_datatable = $('#h3_md_kelurahan_customer_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/kelurahan_customer') ?>",
                        dataSrc: "data",
                        type: "POST"
                    },
                    columns: [
                        { data: 'kelurahan' },
                        { data: 'kecamatan' },
                        { data: 'kabupaten' },
                        { data: 'provinsi' },
                        { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>