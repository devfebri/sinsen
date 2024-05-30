<!-- Modal -->
<div id="h3_md_packing_sheet_penerimaan_barang" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Packing Sheet</h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_packing_sheet_penerimaan_barang_datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Surat Jalan AHM</th>
                        <th>Packing Sheet Number</th>
                        <th>Packing Sheet Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_packing_sheet_penerimaan_barang_datatable = $('#h3_md_packing_sheet_penerimaan_barang_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: '<?= base_url('api/md/h3/packing_sheet_filter_penerimaan_barang') ?>',
                        dataSrc: 'data',
                        type: 'POST',
                        data: function(d){
                            d.surat_jalan_ahm_int = form_.surat_jalan_ahm;
                            d.list_packing_sheet_number = _.map(form_.list_packing_sheet_number, function(data){
                                return data.packing_sheet_number_int;
                            });
                        }
                    },
                    columns: [
                        { data: 'index', orderable: false, width: '3%'},
                        { data: 'surat_jalan_ahm' },
                        { data: 'packing_sheet_number' },
                        { data: 'packing_sheet_date' },
                        { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>