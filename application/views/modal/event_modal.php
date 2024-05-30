<!-- Modal -->
<div id="modal-event" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Event</h4>
        </div>
        <div class="modal-body">
            <table id="datatable-event" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                $('#datatable-event').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/MD/H3/event') ?>",
                        dataSrc: "data",
                        type: "POST"
                    },
                    columns: [
                        { data: 'kode_event' },
                        { data: 'nama_event' },
                        { data: 'aksi' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>