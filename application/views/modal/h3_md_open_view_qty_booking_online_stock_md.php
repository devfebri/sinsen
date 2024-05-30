<input type="hidden" id='id_part_open_view_qty_booking'>
<!-- Modal -->
<div id="h3_md_open_view_qty_booking_online_stock_md" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Qty In Booking</h4>
            </div>
            <div class="modal-body">
                <table id='h3_md_open_view_qty_booking_online_stock_md_datatable' class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th width='3%'>No.</th>
                            <th>No. Referensi</th>
                            <th>Tanggal</th>
                            <th>Kuantitas</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_open_view_qty_booking_online_stock_md_datatable = $('#h3_md_open_view_qty_booking_online_stock_md_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/open_view_qty_booking') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_part_open_view_qty_booking = $('#id_part_open_view_qty_booking').val();
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' }, 
                            { data: 'referensi' },
                            { 
                                data: 'tanggal',
                                render: function(data){
                                    return moment(data).format('DD/MM/YYYY');
                                }
                            },
                            { data: 'kuantitas' },
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>