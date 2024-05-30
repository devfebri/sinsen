<!-- Modal -->
<div id="h3_md_parts_terima_claim_ahm" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style='width: 80%'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Parts</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_parts_terima_claim_ahm_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. Claim</th>
                            <th>No. Packing Sheet</th>
                            <th>No. Invoice</th>
                            <th>Kode Part</th>
                            <th>Deskripsi Part</th>
                            <th>Kode Claim</th>
                            <th>Qty Claim</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_parts_terima_claim_ahm_datatable = $('#h3_md_parts_terima_claim_ahm_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/parts_terima_claim_ahm') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.selected_parts = _.chain(app.parts)
                                .map(function(part){
                                    return _.pick(part, ['id_part', 'no_doos', 'no_po', 'id_claim', 'id_kode_claim']);
                                })
                                .value();
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' }, 
                            { data: 'id_claim' },
                            { data: 'packing_sheet_number' },
                            { data: 'invoice_number' },
                            { data: 'id_part' },
                            { data: 'nama_part' },
                            { 
                                data: 'kode_claim',
                                render: function(data, type, row){
                                    return row.kode_claim + ' - ' + row.nama_claim;
                                }
                            },
                            { data: 'qty_part_diclaim' },
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>