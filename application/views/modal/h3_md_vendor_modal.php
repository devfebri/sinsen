<!-- Modal -->
<div id="modal-vendor" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Part</h4>
        </div>
        <div class="modal-body">
            <table id="datatable-vendor" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                    <th>ID Vendor</th>
                    <th>Nama Vendor</th>
                    <th>Alamat</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                $('#datatable-vendor').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/vendor') ?>",
                        dataSrc: "data",
                        type: "POST"
                    },
                    columns: [
                        { data: 'id_vendor' },
                        { data: 'vendor_name' },
                        { data: 'alamat' },
                        { data: 'aksi' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>