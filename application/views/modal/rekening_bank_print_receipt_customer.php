<!-- Modal -->
<div id="coa_items_penerimaan_kas" class="modal fade modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Part</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="coa_items_penerimaan_kas_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode COA</th>
                            <th>COA</th>
                            <th>Tipe Transaksi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        coa_items_penerimaan_kas_datatable = $('#coa_items_penerimaan_kas_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?=base_url('api/dealer/rekening_bank_print_receipt_customer') ?>",
                                dataSrc: "data",
                                data: function(d) {

                                },
                                type: "POST"
                            },
                            columns: [
                                { data: null, width: '3%', orderable: false },
                                { data: 'kode_coa' },
                                { data: 'coa' },
                                { data: 'tipe_transaksi' },
                                { data: 'action', width: '3%', className: 'text-center', orderable: false },
                            ],
                        });

                        coa_items_penerimaan_kas_datatable.on('draw.dt', function() {
                            var info = coa_items_penerimaan_kas_datatable.page.info();
                            coa_items_penerimaan_kas_datatable.column(0, {
                                search: 'applied',
                                order: 'applied',
                                page: 'applied'
                            }).nodes().each(function(cell, i) {
                                cell.innerHTML = i + 1 + info.start + ".";
                            });
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>