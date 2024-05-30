<div id="h3_md_dealer_sim_part" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Customer</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_dealer_sim_part_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Dealer</th>
                            <th>Nama Dealer</th>
                            <th>Kabupaten</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_dealer_sim_part_datatable = $('#h3_md_dealer_sim_part_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        "bDestroy": true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/dealer_sim_part') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_dealers = _.map(app.dealers, function(d){
                                    return d.id_dealer;
                                });
                            }
                        },
                        columns: [
                            { data: null, orderable: false, width: '3%' }, 
                            { data: 'kode_dealer_md' },
                            { data: 'nama_dealer' },
                            { data: 'kabupaten' },
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                    });
                    h3_md_dealer_sim_part_datatable.on('draw.dt', function() {
                        var info = h3_md_dealer_sim_part_datatable.page.info();
                        h3_md_dealer_sim_part_datatable.column(0, {
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