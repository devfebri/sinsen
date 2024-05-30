<!-- Modal -->
<div id="h3_md_dealer_proses_jawaban_claim_dealer" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Dealer</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_dealer_proses_jawaban_claim_dealer_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Customer</th>
                            <th>Nama Customer</th>
                            <th>Alamat</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_dealer_proses_jawaban_claim_dealer_datatable = $('#h3_md_dealer_proses_jawaban_claim_dealer_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/dealer_proses_jawaban_claim_dealer') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_jawaban_claim_dealer = app.jawaban_claim_dealer.id_jawaban_claim_dealer;
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' },
                            { data: 'kode_dealer_md' },
                            { data: 'nama_dealer' },
                            { data: 'alamat' },
                            { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>