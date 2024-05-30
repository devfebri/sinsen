<!-- Modal -->

<div id="modal_purchase_order_dealer_lain" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="cari_po_id" id="cari_po_id" placeholder="Masukkan No.PO (format : PO/HLO/00000/2302/00000)"/>
                            </div>
                            <div class="col-sm-3">
                               <button class="btn btn-primary btn-sm btn-flat" type="button" id="btn-cari_booking_dealer"><span class="fa fa-search"></span>Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <table id="purchase_order" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>PO ID</th>
                            <th>PO Type</th>
                            <th>Dealer</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    // $(document).ready(function() {
                    //     purchase_order = $('#purchase_order').DataTable({
                    //         processing: true,
                    //         serverSide: true,
                    //         ordering: false,
                    //         order: [],
                    //         ajax: {
                    //             url: "",
                    //             dataSrc: "data",
                    //             type: "POST",
                    //             data: function(data) {
                                    
                    //             }
                    //         },
                    //         createdRow: function(row, data, index) {
                    //             $('td', row).addClass('align-middle');
                    //         },
                    //         columns: [{
                    //                 data: 'po_id'
                    //             },{
                    //                 data: 'po_type'
                    //             }, {
                    //                 data: 'dealer'
                    //             },{
                    //                 data: 'status'
                    //             },{
                    //                 data: 'action_modal'
                    //             },
                    //         ],
                    //     });
                    // });
                    function drawing_po_dealer() {
                        $('#purchase_order').DataTable({
                            processing: true,
                            serverSide: true,
                            ordering: false,
                            searching: false,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/dealer/purchase_order_dealer_lain') ?>",
                                dataSrc: "data",
                                type: "POST",
                                data: function(data) {
                                    data.cari_po_id=$('#cari_po_id').val();
                                }
                            },
                            createdRow: function(row, data, index) {
                                $('td', row).addClass('align-middle');
                            },
                            columns: [{
                                    data: 'po_id'
                                },{
                                    data: 'po_type'
                                }, {
                                    data: 'dealer'
                                },{
                                    data: 'status'
                                },{
                                    data: 'action_modal'
                                },
                            ],
                        });
                    };

                    $(document).ready(function() {
                        $('#btn-cari_booking_dealer').click(function(e){
                            $('#purchase_order').DataTable().clear().destroy();
                            e.preventDefault();
                           //alert($('#cari').val());
                           drawing_po_dealer();
                         });
                    });
                </script>
            </div>
        </div>
    </div>
</div>