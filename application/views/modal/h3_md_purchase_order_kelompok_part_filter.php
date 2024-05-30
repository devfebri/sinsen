<!-- Modal -->
<div id="h3_md_purchase_order_kelompok_part_filter" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Filter Kelompok Part</h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_purchase_order_kelompok_part_filter_datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kelompok Part</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_purchase_order_kelompok_part_filter_datatable = $('#h3_md_purchase_order_kelompok_part_filter_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: '<?= base_url('api/md/h3/kelompok_part_filter_part_purchase_order') ?>',
                        dataSrc: 'data',
                        type: 'POST',
                        data: function(d){
                            d.produk = app.purchase.produk;
                        }
                    },
                    columns: [
                        { data: 'index', orderable: false, width: '3%'},
                        { data: 'id_kelompok_part' },
                        { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#h3_md_purchase_order_kelompok_part_filter').on('hidden.bs.modal', function (e) {
            $('#h3_md_parts_purchase_order_reguler_and_fix').modal('show');
        });

        $('#h3_md_purchase_order_kelompok_part_filter').on('show.bs.modal', function (e) {
            $('#h3_md_parts_purchase_order_reguler_and_fix').modal('hide');
        });
    });
</script>