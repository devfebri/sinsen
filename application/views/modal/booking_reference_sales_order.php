<!-- Modal -->
<div id="booking_reference_sales_order" class="modal  modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Booking Reference</h4>
            </div>
            <div class="modal-body">
            <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="cari" id="cari" placeholder="Masukkan No Booking"/>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="cari2" id="cari2" placeholder="Masukkan Nama Customer"/>
                            </div>
                            <div class="col-sm-4">
                               <button class="btn btn-primary btn-sm btn-flat" type="button" id="btn-cari_booking"><span class="fa fa-search"></span>Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover table-condensed" id="booking_reference_sales_order_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Booking</th>
                            <th>Kode Customer</th>
                            <th>Customer</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    // $(document).ready(function() {
                    //     booking_reference_sales_order_datatable = $('#booking_reference_sales_order_datatable').DataTable({
                    //         processing: true,
                    //         serverSide: true,
                    //         order: [],
                    //         ajax: {
                    //             url: "",
                    //             dataSrc: "data",
                    //             data: function(d) {
                    //                 d.id_customer = form_.sales_order.id_customer;
                    //             },
                    //             type: "POST"
                    //         },
                    //         columns: [
                    //             { data: 'index', orderable: false, width: '3%' }, 
                    //             { data: 'id_booking' }, 
                    //             { data: 'id_customer' }, 
                    //             { data: 'nama_customer' }, 
                    //             { data: 'action', orderable: false, width: '3%' }
                    //         ],
                    //     });
                    // });
                    function drawing()
                    {
                         $('#booking_reference_sales_order_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            searching: false,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/dealer/booking_reference_sales_order') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    d.id_customer = form_.sales_order.id_customer;
                                    d.search=$('#cari').val();
                                    d.search2=$('#cari2').val();
                                },
                                type: "POST"
                            },
                            columns: [
                                { data: 'index', orderable: false, width: '3%' }, 
                                { data: 'id_booking' }, 
                                { data: 'id_customer' }, 
                                { data: 'nama_customer' }, 
                                { data: 'action', orderable: false, width: '3%' }
                            ],
                        });
                    }

                    $(document).ready(function() {
                        $('#btn-cari_booking').click(function(e){
                            $('#booking_reference_sales_order_datatable').DataTable().clear().destroy();
                            e.preventDefault();
                           //alert($('#cari').val());
                            drawing();
                         });
                    });
                </script>
            </div>
        </div>
    </div>
</div>