<!-- Modal -->
<div id="h3_dealer_target_salesman" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Dealer</h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover table-condensed" id="h3_dealer_target_salesman_datatable" style="width: 100%">
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
                h3_dealer_target_salesman_datatable = $('#h3_dealer_target_salesman_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: '<?= base_url('api/md/h3/dealer_target_salesman') ?>',
                        dataSrc: 'data',
                        type: 'POST',
                        data: function(d){
                            if(app.target_salesman.jenis_target_salesman == 'Parts'){
                                d.selected_id_dealer = _.map(app.target_salesman_parts, function(data){
                                    return data.id_dealer;
                                });
                            }

                            if(app.target_salesman.jenis_target_salesman == 'Oil'){
                                d.selected_id_dealer = _.map(app.target_salesman_oils, function(data){
                                    return data.id_dealer;
                                });
                            }

                            if(app.target_salesman.jenis_target_salesman == 'Acc'){
                                d.selected_id_dealer = _.map(app.target_salesman_acc, function(data){
                                    return data.id_dealer;
                                });
                            }
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

                h3_dealer_target_salesman_datatable.on('draw.dt', function() {
                    var info = h3_dealer_target_salesman_datatable.page.info();
                    h3_dealer_target_salesman_datatable.column(0, {
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