<!-- Modal -->
<div id="purchase_order_tracking_modal" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Purchase Order</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="purchase_order_tracking_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>PO Number</th>
                            <th>Tanggal PO</th>
                            <th>Nama Konsumen</th>
                            <th>No. HP Konsumen</th>
                            <th>Tipe Kendaraan</th>
                            <th>No. Mesin</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        purchase_order_tracking_datatable = $('#purchase_order_tracking_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/dealer/purchase_order_tracking') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    if(form_.tanggal_po_start != '' && form_.tanggal_po_end){
                                        d.date_range = true;
                                        d.tanggal_po_start = form_.tanggal_po_start;
                                        d.tanggal_po_end = form_.tanggal_po_end;
                                    }
                                    
                                    if(form_.tipe_po != ''){
                                        d.filter_tipe = true;
                                        d.tipe_po = form_.tipe_po;
                                    }
                                },
                                type: "POST"
                            },
                            columns: [
                                { data: 'index', orderable: false, width: '3%' },
                                { data: 'po_id' },
                                { data: 'tanggal_order', name: 'po.tanggal_order' },
                                { data: 'nama_customer' },
                                { data: 'no_hp' },
                                { data: 'id_tipe_kendaraan' },
                                { data: 'no_mesin' },
                                {  data: 'action', width: '2%', orderable: false, className: 'text-center' }
                            ],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>