<!-- Modal -->
<div id="modal-packing-sheet-surat-pengantar" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Packing Sheet</h4>
        </div>
        <div class="modal-body">
            <table id="datatable-packing-sheet-surat-pengantar" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                    <th>No Packing Sheet</th>
                    <th>Tgl Packing Sheet</th>
                    <th>Nama Customer</th>
                    <th>NO DO</th>
                    <th>Jumlah Koli</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                $('#datatable-packing-sheet-surat-pengantar').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/packing_sheet_surat_pengantar') ?>",
                        dataSrc: "data",
                        type: "POST"
                    },
                    columns: [
                        { data: 'id_packing_sheet' },
                        { data: 'tgl_packing_sheet' },
                        { data: 'nama_customer' },
                        { data: 'id_ref' },
                        { data: 'jumlah_koli' },
                        { data: 'aksi' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>