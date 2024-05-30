<input type="hidden" id='id_part_open_view_qty_po'>
<!-- Modal -->
<div id="h3_md_open_view_qty_po_online_stock_md" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Qty PO</h4>
            </div>
            <div class="modal-body">
                <table id='h3_md_open_view_qty_po_online_stock_md_datatable' class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th width='3%'>No.</th>
                            <th>Jenis PO</th>
                            <th>Kuantitas</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_open_view_qty_po_online_stock_md_datatable = $('#h3_md_open_view_qty_po_online_stock_md_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/open_view_qty_po') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_part_open_view_qty_po = $('#id_part_open_view_qty_po').val();
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' }, 
                            { data: 'jenis_po' },
                            { data: 'sisa_belum_terpenuhi' },
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>