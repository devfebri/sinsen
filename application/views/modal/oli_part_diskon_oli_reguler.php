<!-- Modal -->
<div id="oli_part_diskon_oli_reguler" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Part Oli</h4>
        </div>
        <div class="modal-body">
            <table id="oli_part_diskon_oli_reguler_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                    <th>Part</th>
                    <th>Nama</th>
                    <th>HET</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                oli_part_diskon_oli_reguler_datatable = $('#oli_part_diskon_oli_reguler_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/oli_part_diskon_oli_reguler') ?>",
                        dataSrc: "data",
                        type: "POST"
                    },
                    columns: [
                        { data: 'id_part' },
                        { data: 'nama_part' },
                        { data: 'het', name: 'p.harga_dealer_user' },
                        { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>