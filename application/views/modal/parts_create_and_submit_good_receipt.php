<div id='parts_create_and_submit_good_receipt'  class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Rak</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="parts_create_and_submit_good_receipt_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Part Number</th>
                            <th>Part Deskripsi</th>
                            <th>Kelompok Part</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    $(document).ready(function(){
                        parts_create_and_submit_good_receipt_datatable = $('#parts_create_and_submit_good_receipt_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/dealer/parts_good_receipt') ?>",
                                dataSrc: "data",
                                type: "POST"
                            },
                            columns: [
                                { data: 'id_part' },
                                { data: 'nama_part' },
                                { data: 'kelompok_part' },
                                { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                            ],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>