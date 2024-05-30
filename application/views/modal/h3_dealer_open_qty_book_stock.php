<input type="hidden" id='id_part_open_qty_book_stock'>
<!-- Modal -->
<div id="h3_dealer_open_qty_book_stock" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Qty Booking</h4>
            </div>
            <div class="modal-body">
                <table id='h3_dealer_open_qty_book_stock_datatable' class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th width='3%'>No.</th>
                            <th>Referensi</th>
                            <th>Status</th>
                            <th>Tipe Referensi</th>
                            <th>Qty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_dealer_open_qty_book_stock_datatable = $('#h3_dealer_open_qty_book_stock_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/dealer/open_qty_book_stock') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_part_open_qty_book_stock = $('#id_part_open_qty_book_stock').val();
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' }, 
                            { data: 'referensi' },
                            { data: 'status' },
                            { data: 'tipe_referensi' },
                            { data: 'kuantitas' },
                            { data: 'action', orderable: false, width: '3%' },
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>