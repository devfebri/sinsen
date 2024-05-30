<!-- Modal -->
<div id="modal-parts-claim-dealer" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Part</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="datatable-parts-claim-dealer" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th>Qty Packing Sheet</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    datatable_parts_claim_dealer = $('#datatable-parts-claim-dealer').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/parts_claim_dealer') ?>",
                            dataSrc: "data",
                            data: function(data){
                                data.id_packing_sheet = app.claim_dealer.id_packing_sheet;
                                data.selected_id_part = _.chain(app.parts)
                                .map(function(part){
                                    return part.id_part;
                                })
                                .value();
                            },
                            type: "POST"
                        },
                        columns: [
                            { data: 'id_part' },
                            { data: 'nama_part' },
                            { data: 'qty_packing_sheet' },
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>