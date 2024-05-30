<!-- Modal -->
<div id="modal_dealer_terdekat" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Dealer</h4>
            </div>
            <div class="modal-body">
                <table id="dealer_terdekat_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Dealer</th>
                            <th>Nama Dealer</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    dealer_terdekat_datatable = $('#dealer_terdekat_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/cari_dealer_terdekat') ?>",
                            dataSrc: "data",
                            type: "POST",
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' },
                            { data: 'kode_dealer_md' },
                            { data: 'nama_dealer' },
                            { data: 'action', orderable: false, width: '3%' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>