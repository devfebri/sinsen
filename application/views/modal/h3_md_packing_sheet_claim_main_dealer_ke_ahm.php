<!-- Modal -->
<div id="h3_md_packing_sheet_claim_main_dealer_ke_ahm" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Packing Sheet</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_packing_sheet_claim_main_dealer_ke_ahm_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Packing Sheet Number</th>
                            <th>Packing Sheet Date</th>
                            <th>Invoice Number</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_packing_sheet_claim_main_dealer_ke_ahm_datatable = $('#h3_md_packing_sheet_claim_main_dealer_ke_ahm_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/packing_sheet_claim_main_dealer_ke_ahm') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.selected_parts = _.chain(app.parts)
                                .map(function(part){
                                    return _.pick(part, ['id_part', 'id_part_int', 'qty_part_diclaim', 'no_doos', 'no_doos_int']);
                                })
                                .value();
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' }, 
                            { data: 'packing_sheet_number' },
                            { data: 'packing_sheet_date' },
                            { data: 'invoice_number' },
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>