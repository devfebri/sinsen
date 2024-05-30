<!-- Modal -->
<div id="rak_outbound_form" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Lokasi Tujuan</h4>
        </div>
        <div class="modal-body">
            <table id="rak_outbound_form_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                    <th>Kode Gudang</th>
                    <th>Kode Rak</th>
                    <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                rak_outbound_form_datatable = $('#rak_outbound_form_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/dealer/rak_outbound_form') ?>",
                        dataSrc: "data",
                        type: "POST",
                    },
                    columns: [
                        { data: 'id_gudang' },
                        { data: 'id_rak' },
                        { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>