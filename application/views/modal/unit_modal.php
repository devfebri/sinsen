<!-- Modal -->
<div id="modal-unit" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Unit</h4>
        </div>
        <div class="modal-body">
            <table id="datatable-unit" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                    <th>ID Item</th>
                    <th>Tipe Kendaraan</th>
                    <th>Warna</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                $('#datatable-unit').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/MD/H3/unit') ?>",
                        dataSrc: "data",
                        type: "POST"
                    },
                    columns: [
                        { data: 'id_item' },
                        { data: 'tipe_kendaraan' },
                        { data: 'warna' },
                        { data: 'aksi' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>