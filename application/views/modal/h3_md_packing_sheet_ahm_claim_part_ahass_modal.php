<!-- Modal -->
<div id="h3_md_packing_sheet_ahm_claim_part_ahass_modal" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style='width: 60%;'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Packing Sheet</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_packing_sheet_ahm_claim_part_ahass_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Packing Sheet</th>
                            <th>Nomor karton</th>
                            <th>Kuantitas dalam karton</th>
                            <th>Tgl Packing Sheet</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_packing_sheet_ahm_claim_part_ahass_datatable = $('#h3_md_packing_sheet_ahm_claim_part_ahass_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/packing_sheet_ahm_claim_part_ahass') ?>",
                            dataSrc: 'data',
                            type: "POST",
                            data: function(d){
                                d.selected_packing_sheet_number = app.claim_part_ahass.packing_sheet_number;
                                d.claim_parts_to_ahm = _.chain(app.claim_parts_to_ahm)
                                .map(function(part){
                                    return _.pick(part, ['id_part_int', 'id_part', 'qty_part_diclaim']);
                                })
                                .value();
                                d.id_claim_part_ahass = app.claim_part_ahass.id_claim_part_ahass;
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' },
                            { data: 'packing_sheet_number', name: 'ps.packing_sheet_number' },
                            { data: 'nomor_karton', name: 'psp.no_doos' },
                            { data: 'jumlah_item', name: 'nk.jumlah_item' },
                            { data: 'packing_sheet_date', name: 'ps.packing_sheet_date' },
                            { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>