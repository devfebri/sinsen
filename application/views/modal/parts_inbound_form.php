<!-- Modal -->
<div id="parts_inbound_form" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Parts</h4>
        </div>
        <div class="modal-body">
            <table id="parts_inbound_form_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                    <th>ID Part</th>
                    <th>Nama Part</th>
                    <th>Qty Pinjam</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                parts_inbound_form_datatable = $('#parts_inbound_form_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/dealer/parts_inbound_form') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                            d.id_outbound_form = form_.surat_jalan.id_outbound_form;
                        },
                    },
                    columns: [
                        { data: 'id_part' },
                        { data: 'nama_part' },
                        { data: 'qty' },
                        { data: 'action' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>