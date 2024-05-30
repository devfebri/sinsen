<!-- Modal -->
<div id="modal_nrfs_purchase_order" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Not Ready For Sale (NRFS)</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="datatable_nrfs_purchase_order" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Dokumen NRFS</th>
                            <th>Tanggal Dokumen</th>
                            <th>No Shipping List</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        datatable_nrfs_purchase_order = $('#datatable_nrfs_purchase_order').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= base_url('api/nrfs') ?>",
                dataSrc: "data",
                data: function(d) {

                },
                type: "POST"
            },
            columns: [
                { data: null, width: '3%', orderable: false },
                { data: 'dokumen_nrfs_id' },
                { data: 'tgl_dokumen' },
                { data: 'no_shiping_list' },
                { data: 'action', width: '3%', orderable: false, className: 'text-center' },
            ],
        });

        datatable_nrfs_purchase_order.on('draw.dt', function() {
            var info = datatable_nrfs_purchase_order.page.info();
            datatable_nrfs_purchase_order.column(0, {
                search: 'applied',
                order: 'applied',
                page: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1 + info.start + ".";
            });
        });
    });
</script>