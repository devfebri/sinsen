<!-- Modal -->
<div id="rak_parts_inbound_form" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Parts</h4>
        </div>
        <div class="modal-body">
            <table id="rak_parts_inbound_form_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                    <th>Gudang</th>
                    <th>Rak</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                rak_parts_inbound_form_datatable = $('#rak_parts_inbound_form_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/dealer/rak') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                            d.index = form_.index_part;
                        },
                    },
                    columns: [
                        { data: 'id_gudang' },
                        { data: 'id_rak' },
                        { data: 'action' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>