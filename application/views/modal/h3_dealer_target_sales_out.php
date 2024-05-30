<!-- Modal -->
<div id="h3_dealer_target_sales_out" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Dealer</h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover table-condensed" id="h3_dealer_target_sales_out_datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode Dealer</th>
                        <th>Nama Dealer</th>
                        <th>Alamat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_dealer_target_sales_out_datatable = $('#h3_dealer_target_sales_out_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: '<?= base_url('api/md/h3/dealer_target_sales_out') ?>',
                        dataSrc: 'data',
                        type: 'POST',
                        data: function(d){
                            d.selected_id_dealer = _.chain(app.target_dealer_detail)
                            .map(function(dealer){
                                return _.pick(dealer, ['id_dealer']);
                            })
                            .value();
                            // d.selected_id_dealer = 103;
                        }
                    },
                    columns: [
                        { data: null, orderable: false, width: '3%'},
                        { data: 'kode_dealer_md' },
                        { data: 'nama_dealer' },
                        { data: 'alamat' },
                        { data: 'action', orderable: false, widht: '3%', className: 'text-center' }
                    ],
                });

                h3_dealer_target_sales_out_datatable.on('draw.dt', function() {
                    var info = h3_dealer_target_sales_out_datatable.page.info();
                    h3_dealer_target_sales_out_datatable.column(0, {
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