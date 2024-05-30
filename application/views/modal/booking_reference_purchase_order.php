<!-- Modal -->
<div id="modal-booking-ref" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Booking Reference</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="datatable-booking-ref" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Booking</th>
                            <th>ID Customer</th>
                            <th>Customer</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        booking_reference = $('#datatable-booking-ref').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/dealer/request_document') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    return d;
                                },
                                type: "POST"
                            },
                            columns: [
                                { data: null, width: '3%', orderable: false },
                                { data: 'id_booking' },
                                { data: 'id_customer' },
                                { data: 'nama_customer'},
                                { data: 'action', width: '3%', className: 'text-center', orderable: false}
                            ],
                        });
                        
                        booking_reference.on('draw.dt', function() {
                            var info = booking_reference.page.info();
                            booking_reference.column(0, {
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