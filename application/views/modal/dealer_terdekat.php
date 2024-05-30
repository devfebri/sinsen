<!-- Modal -->
<div id="modal_dealer_terdekat" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Order to</h4>
            </div>
            <div class="modal-body">
                <table  id="datatable_dealer_terdekat" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Dealer</th>
                            <th>Nama Dealer</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    datatable_dealer_terdekat = $('#datatable_dealer_terdekat').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/dealer_terdekat') ?>",
                            dataSrc: "data",
                            type: "POST"
                        },
                        columns: [
                            { data: 'index', width: '3%', orderable: false }, 
                            { data: 'kode_dealer_md' }, 
                            { data: 'nama_dealer' }, 
                            { data: 'action', width: '3%', orderable: false, className: 'text-center' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>