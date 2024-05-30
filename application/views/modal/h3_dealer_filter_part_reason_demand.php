<!-- Modal -->
<div id="h3_dealer_filter_part_reason_demand" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Filter Part</h4>
            </div>
            <div class="modal-body">
                <table id="h3_dealer_filter_part_reason_demand_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_dealer_filter_part_reason_demand_datatable = $('#h3_dealer_filter_part_reason_demand_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/dealer/filter_part_reason_demand') ?>",
                            dataSrc: "data",
                            type: "POST",
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' },
                            { data: 'id_part' },
                            { data: 'nama_part' },
                            { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>