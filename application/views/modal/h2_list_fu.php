<!-- Modal -->
<div id="h2_list_fu" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">M/C Type</h4>
            </div>
            <div class="modal-body">
                <table id="h2_list_fu_table" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Type Kendaraan</th>
                            <th>Tipe AHM</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h2_list_fu_table = $('#h2_list_fu_table').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('dealer/H2_dealer_fu_list_datatable/getDataMCType') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.filters = filter_mc_type.filters;
                            }
                        },
                        columns: [
                            { data: 'id_tipe_kendaraan' },
                            { data: 'tipe_ahm' },
                            { data: 'action', className: 'text-center', width: '3%', orderable: false }
                        ],
                         
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>