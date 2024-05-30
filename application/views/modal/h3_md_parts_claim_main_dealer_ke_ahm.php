<!-- Modal -->
<div id="h3_md_parts_claim_main_dealer_ke_ahm" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style='width: 80%;'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Parts</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_parts_claim_main_dealer_ke_ahm_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. Penerimaan Barang</th>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th>No. Packing Sheet</th>
                            <th>Nomor Karton</th>
                            <th>Qty PS</th>
                            <th>Qty Available</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_parts_claim_main_dealer_ke_ahm_datatable = $('#h3_md_parts_claim_main_dealer_ke_ahm_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/parts_claim_main_dealer_ke_ahm') ?>",
                            dataSrc: "data",
                            data: function(d){
                                d.packing_sheet_number_int = app.claim_main_dealer.packing_sheet_number_int;
                                d.packing_sheet_number = app.claim_main_dealer.packing_sheet_number;
                                d.selected_id_parts = _.map(app.parts, function(part){
                                    return _.pick(part, ['id_part', 'id_part_int', 'no_doos', 'id_part_int']);
                                });
                            },
                            type: "POST"
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' }, 
                            { data: 'no_penerimaan_barang' },
                            { data: 'id_part' },
                            { data: 'nama_part' },
                            { data: 'packing_sheet_number' },
                            { data: 'no_doos' },
                            { data: 'packing_sheet_quantity' },
                            { data: 'qty_avs', orderable: false, },
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>