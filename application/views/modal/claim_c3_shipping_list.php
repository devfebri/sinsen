<div id='claim_c3_shipping_list'  class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Rak</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="datatable_claim_c3_shipping_list" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Claim</th>
                            <th>Nama Claim</th>
                            <th>Tipe Claim</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    $(document).ready(function(){
                        datatable_claim_c3_shipping_list = $('#datatable_claim_c3_shipping_list').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/dealer/claim_c3_shipping_list') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    
                                },
                                type: "POST"
                            },
                            columns: [
                                { data: null, width: '3%', orderable: false },
                                { data: 'kode_claim' },
                                { data: 'nama_claim' },
                                { data: 'tipe_claim' },
                                { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                            ],
                        });

                        datatable_claim_c3_shipping_list.on('draw.dt', function() {
                            var info = datatable_claim_c3_shipping_list.page.info();
                            datatable_claim_c3_shipping_list.column(0, {
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