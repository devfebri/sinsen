<!-- Modal -->
<div id="h3_md_purchase_order_logistik_rekap_purchase_order_dealer" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Purchase Order</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_purchase_order_logistik_rekap_purchase_order_dealer_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No. Purchase Order</th>
                            <th>Tanggal Order</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_purchase_order_logistik_rekap_purchase_order_dealer_datatable = $('#h3_md_purchase_order_logistik_rekap_purchase_order_dealer_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/purchase_order_logistik_rekap_purchase_order_dealer') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_dealer = app.rekap.id_dealer;
                                d.tipe_po = app.rekap.tipe_po;
                                d.items = _.chain(app.items)
                                .map(function(data){
                                    return data.id_referensi;
                                })
                                .value();
                            }
                        },
                        columns: [
                            { data: 'po_id' },
                            { data: 'tanggal_order' },
                            { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>