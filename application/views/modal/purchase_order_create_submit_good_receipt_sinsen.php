<div id='purchase_order_create_submit_good_receipt'  class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Purchase Order</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="purchase_order_create_submit_good_receipt_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>PO Number</th>
                            <th>Tanggal PO</th>
                            <th>Booking Number</th>
                            <th>Customer</th>
                            <th>SA Form</th>
                            <th>Work Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    $(document).ready(function(){
                        purchase_order_create_submit_good_receipt_datatable = $('#purchase_order_create_submit_good_receipt_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/dealer/purchase_order_create_submit_good_receipt') ?>",
                                dataSrc: "data",
                                type: "POST"
                            },
                            columns: [
                                { data: 'po_id' },
                                { data: 'tanggal_order' },
                                { data: 'id_booking' },
                                { data: 'nama_customer' },
                                { 
                                    data: 'id_sa_form',
                                    render: function(data, type, row){
                                        if(data == null){
                                            return '-';
                                        }
                                        return data;
                                    }
                                },
                                {   
                                    data: 'id_work_order',
                                    render: function(data, type, row){
                                        if(data == null){
                                            return '-';
                                        }
                                        return data;
                                    } 
                                },
                                { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                            ],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>