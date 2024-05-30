<input type="hidden" id='po_id_monitoring_permintaan_part'>
<!-- Modal -->
<div id="h3_md_view_sales_order_monitoring_permintaan_part" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Sales Order</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_view_sales_order_monitoring_permintaan_part_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No. Sales Order</th>
                            <th>Tanggal Order</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_view_sales_order_monitoring_permintaan_part_datatable = $('#h3_md_view_sales_order_monitoring_permintaan_part_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        searching: false,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/view_sales_order_monitoring_permintaan_part') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.po_id_monitoring_permintaan_part = $('#po_id_monitoring_permintaan_part').val();
                            }
                        },
                        columns: [
                            { data: 'id_sales_order' },
                            { data: 'tanggal_order' },
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>