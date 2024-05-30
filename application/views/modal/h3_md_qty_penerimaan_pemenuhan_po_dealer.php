<!-- Modal -->
<div id="h3_md_qty_penerimaan_pemenuhan_po_dealer" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Penerimaan Barang</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_qty_penerimaan_pemenuhan_po_dealer_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. Penerimaan</th>
                            <th>Tanggal</th>
                            <th>No. PO MD</th>
                            <th>Surat Jalan AHM</th>
                            <th>No. Packing Sheet</th>
                            <th>No. Karton</th>
                            <th>Kuantitas</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_qty_penerimaan_pemenuhan_po_dealer_datatable = $('#h3_md_qty_penerimaan_pemenuhan_po_dealer_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: '<?= base_url('api/md/h3/qty_penerimaan_pemenuhan_po_dealer') ?>',
                            dataSrc: 'data',
                            type: 'POST',
                            data: function(d){
                                d.id_part = app.parts[app.index_part].id_part;
                                d.po_id = app.purchase.po_id;
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%'},
                            { data: 'no_penerimaan_barang' },
                            { 
                                data: 'tanggal_penerimaan',
                                render: function(data){
                                    return moment(data).format('DD/MM/YYYY');
                                }
                            },
                            { data: 'no_po' },
                            { data: 'surat_jalan_ahm' },
                            { data: 'packing_sheet_number' },
                            { data: 'nomor_karton' },
                            { 
                                data: 'qty_diterima',
                                render: function(data){
                                    return accounting.formatNumber(data, 0, '.', ',');
                                }
                            },
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>