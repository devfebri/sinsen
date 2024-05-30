<div id='purchase_order_create_submit_good_receipt'  class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Purchase Order</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="cari" id="cari" placeholder="Masukkan No PO"/>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="cari2" id="cari2" placeholder="Masukkan ID Booking"/>
                            </div>
                            <div class="col-sm-4">
                               <button class="btn btn-primary btn-sm btn-flat" type="button" id="btn-cari"><span class="fa fa-search"></span>Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover table-condensed" id="purchase_order_create_submit_good_receipt_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>PO Number</th>
                            <th>Tanggal PO</th>
                            <th>Booking Number</th>
                            <th>Customer</th>
                            <!--<th>SA Form</th>-->
                            <!--<th>Work Order</th>-->
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                        function drawing(){
                            $('#purchase_order_create_submit_good_receipt_datatable').DataTable({
                                processing: true,
                                serverSide: true,
                                searching:false,
                                order: [],
                                ajax: {
                                    url: "<?= base_url('api/dealer/purchase_order_create_submit_good_receipt') ?>",
                                    type: "POST",
                                    dataSrc: "data",
                                    data: function(d) {
                                      d.search=$('#cari').val();
                                      d.search2=$('#cari2').val();
                                    },
                                  
                                },
                                columns: [
                                    { data: 'index', orderable: false, width: '3%' },
                                    { data: 'po_id' },
                                    { 
                                        data: 'tanggal_order',
                                        render: function(data){
                                            if(data != null){
                                                return data;
                                            }
                                            return '-';
                                        },
                                        name: 'po.created_at'
                                    },
                                    { 
                                        data: 'id_booking',
                                        render: function(data){
                                            if(data != null){
                                                return data;
                                            }
                                            return '-';
                                        }
                                    },
                                    { data: 'customer' },
                                    // { 
                                    //     data: 'id_sa_form',
                                    //     render: function(data, type, row){
                                    //         if(data == null){
                                    //             return '-';
                                    //         }
                                    //         return data;
                                    //     }
                                    // },
                                    // {   
                                    //     data: 'id_work_order',
                                    //     render: function(data, type, row){
                                    //         if(data == null){
                                    //             return '-';
                                    //         }
                                    //         return data;
                                    //     } 
                                    // },
                                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                                ],
                            });
                        }
                     $(document).ready(function(){
                         $('#btn-cari').click(function(e){
                            $('#purchase_order_create_submit_good_receipt_datatable').DataTable().clear().destroy();
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