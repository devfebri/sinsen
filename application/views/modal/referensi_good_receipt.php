<!-- Modal -->
<div id="referensi_good_receipt" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Referensi Good Receipt</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="referensi_good_receipt_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>ID Referensi</th>
                            <th>Tanggal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        referensi_good_receipt_datatable = $('#referensi_good_receipt_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/dealer/referensi_good_receipt') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    d.tipe_referensi = form_.good_receipt.ref_type;
                                },
                                type: "POST"
                            },
                            columns: [
                                { data: null, width: '3%', orderable: false },
                                { data: 'id_referensi' },
                                { data: 'tanggal' },
                                {  data: 'action', width: '1%', className: 'text-center', orderable: false, }
                            ],
                        });

                        referensi_good_receipt_datatable.on('draw.dt', function() {
                            var info = referensi_good_receipt_datatable.page.info();
                            referensi_good_receipt_datatable.column(0, {
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