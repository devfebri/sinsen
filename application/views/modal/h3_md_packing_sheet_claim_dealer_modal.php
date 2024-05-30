<!-- Modal -->
<div id="modal-packing-sheet-claim-dealer" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style='width: 60%;'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Packing Sheet</h4>
            </div>
            <div class="modal-body">
                <table id="datatable-packing-sheet-claim-dealer" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No Packing Sheet</th>
                            <th>Tgl Packing Sheet</th>
                            <th>Nama Customer</th>
                            <th>NO DO</th>
                            <th>Jumlah Koli</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    datatable_packing_sheet_claim_dealer = $('#datatable-packing-sheet-claim-dealer').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/packing_sheet_claim_dealer') ?>",
                            dataSrc: "data",
                            data: function(data){
                                data.id_dealer = app.claim_dealer.id_dealer;
                            },
                            type: "POST"
                        },
                        columns: [
                            { data: 'id_packing_sheet' },
                            { data: 'tgl_packing_sheet' },
                            { data: 'nama_customer' },
                            { data: 'nomor_do' },
                            { data: 'jumlah_koli' },
                            { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>